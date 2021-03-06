<?php
/*
 *
This file is part of Mimirsoft Lightweight ACL System
Copyright (C) 2011, Kevin Milhoan, Mimir Software Corporation

 Mimirsoft Lightweight ACL Sytem is free software: you can redistribute it and/or modify
it under the terms of the GNU Affero General Public License as
published by the Free Software Foundation, either version 3 of the
License, or (at your option) any later version.

Mimirsoft Lightweight ACL System is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU Affero General Public License for more details.

You should have received a copy of the GNU Affero General Public License
along with Mimirsoft Lightweight ACL System.  If not, see <http://www.gnu.org/licenses/>.

Contact MimirSoft at mimirsoft@gmail.com or www.mimirsoft.com

*
*/

class UserException extends Exception 
{ 
    public $backtrace;
    public $message;

    public function __construct($message=false) {
        if(!$message) {
            $this->message = "No message provided";
        }
        else{
            $this->message = $message;
        }
        $this->backtrace = debug_backtrace();
    }
}

class User
{
    private $native_session_id;
    private $logged_in;
    private $user_id;
    private $username;
    private $lastname;
    private $email;
    private $firstname;
    private $fullname;
    private $permissions;
    private $limited_permissions;
    private $hashed_password;
    private $authenticated;

    private $dbh;
    
    
    public function __construct($dbh, $id=false, $logged_in, $native_session_id)
    {
    
        $this->user_id = $id;
        if($id)
        {
	        $this->dbh = $dbh;
	        
	    	$stmt = $this->dbh->prepare("SELECT username, lastname, firstname, email 
	                                FROM users_main 
	                                WHERE user_id = '$id'");
	        $stmt->execute();
	        $row = $stmt->fetch_assoc();
	        $this->username = $row['username'];
	        $this->lastname = $row['lastname'];
	        $this->email = $row['email'];
	        $this->firstname = $row['firstname'];
	        $this->fullname = $row['firstname']." ".$row['lastname'];
	    }
        $this->logged_in = $logged_in;
        $this->native_session_id = $native_session_id;
        
    }
	public function getUserFromExternalID($externalId, $externalSource)
    {
    	$stmt = $this->dbh->prepare("SELECT *
            						 FROM users_main
                                      WHERE external_id = :1:
    								  AND external_source = :2:
                                      LIMIT 1");
    	$stmt->execute($externalId, $externalSource);
    	if($stmt->num_rows()>0)
    	{
    		$row = $stmt->fetch_assoc();
    		$this->user_id = $row['user_id'];
    		return($this->user_id);
    	
    	}
    	else 
    	{
    		return false;
    	}
    	
    	
    }
    
    public function setLoggedIn($value) 
    {
    	$this->logged_in = $value;
    }
    
    public function create($name, $email, $password, $lastname, $firstname, $join_app, 
    						$displayname, $externalId,$externalLink,$externalSource)
    {
        $hash = $this->createPasswordHash($password);
    	$sql2="INSERT INTO users_main
    				       (username, email, hashed_password, lastname, firstname, 
    			            created_at, join_app,displayname, external_id, external_link, external_source)
    			    VALUES (:1:, :2:, :3:, :4:, :5:, NOW(), :6:, :7:, :8:, :9:, :10:)";
    	$stmt = $this->dbh->prepare($sql2);
    	$stmt->execute($name, $email, $hash, $lastname, $firstname, $join_app,$displayname, $externalId,$externalLink,$externalSource);
    	$this->user_id = $stmt->insert_id();
    	return $this->user_id;
    	
    }
    
    //create the salt, should only be used by createPasswordHash
    private function getPasswordSalt()
    {
    	return mcrypt_create_iv(22, MCRYPT_DEV_URANDOM);
    }    
    //create the Hash, should only be used when changing password or creating user first time
    function createPasswordHash($password)
    {
    	$options = [
    	'cost' => 11,
    	'salt' => User::getPasswordSalt()
    	];
    	//echo "SALT<BR/>";
    	//echo $options['salt'];
    	//echo "<BR/>";
    	$hash = password_hash($password, PASSWORD_BCRYPT, $options);
    	return $hash;
    }
        
    function comparePassword($password, $hash)
    {
        return password_verify($password, $hash);
    }

    function getAuthenticated()
    {
        return $this->authenticated;
    }   
    function getUserArray()
    {
    	$obj['user_id'] = $this->user_id;
        $obj['username'] = $this->username;
        $obj['lastname'] = $this->lastname;
        $obj['email'] = $this->email;
        $obj['firstname'] = $this->firstname;
        $obj['fullname'] = $this->fullname;
		return $obj;        
    }
    public function print_native()
    {
        echo $this->native_session_id;
    }
    
    //not in use
    public function login($strUsername, $strPlainPassword) 
    {
        
        //username or email 
        $stmt = $this->dbh->prepare("SELECT user_id, username, hashed_password, email 
                                FROM users_main 
                                WHERE username = :1: LIMIT 1");
        $stmt->execute($strUsername);
        $row = $stmt->fetch_assoc();
        if($this->comparePassword($strPlainPassword, $row['hashed_password']))
        {
        	$this->user_id = $row["user_id"];
            $this->username = $row["username"];
            $this->email = $row['email'];
            $this->logged_in = true;
            $stmt = $this->dbh->prepare("UPDATE user_session 
                                    SET logged_in = 'Y', 
                                        session_data = '',
                                        user_id = " . $this->user_id . "
                                  WHERE session_id = " . $this->native_session_id);
            $stmt->execute();
            return(true);
        } else {
            return(false);
        };
    }

    public function logOut() {
    if ($this->logged_in == true) {
        $stmt = $this->dbh->prepare("UPDATE user_session 
                                  SET logged_in = 'N', 
                                      user_id = 0 
                                WHERE session_id = " . $this->native_session_id);
        $stmt->execute();
        $this->logged_in = false;
        $this->user_id = 0;
        return(true);
    } else {
        return(false);
    };
    }
    
    public function GetUserID() {
    if ($this->logged_in) {
        return($this->user_id);
    } else {
        return(false);
    };
    }

	public function GetUserName() {
    	return($this->username);
    }
	public function GenerateUsername() {
		
		$sets = array();
		$sets[] = 'abcdefghijklmnpqrstuvwxyz';
		$sets[] = 'ABCDEFGHIJKLMNPQRSTUVWXYZ';
		$sets[] = '123456789';
		$password = '';
		$all = '';
		foreach($sets as $set)
		{
			$password .= $set[array_rand(str_split($set))];
			$all .= $set;
		}
		$all = str_split($all);
		$length = 10;
		for($i = 0; $i < $length - count($sets); $i++)
		{
			$password .= $all[array_rand($all)];
		}
		$password = str_shuffle($password);
    	return($password);
    }
    
    
    public function GetEmail() {
    return($this->user_email);
    }
    public function GetUserFullName() {
    return($this->user_fullname);
    }
    
    
    public function IsLoggedIn() {
        return($this->logged_in);
    }
    public function GetAllUsers() {
        $stmt = $this->dbh->prepare("SELECT *
                                FROM users_main 
                                ORDER BY username");
        $stmt->execute();
        return $stmt->fetchall_assoc();
    }
    public function GetUserFromUsername($name) {
        $stmt = $this->dbh->prepare("SELECT username, user_id
                                FROM users_main 
                                WHERE username = :1: LIMIT 1");
        $stmt->execute($name);
        return $stmt->fetch_assoc();
    }
    public function CheckUsername($name) {
    	$stmt = $this->dbh->prepare("SELECT username, user_id
                                FROM users_main
                                WHERE username = :1: LIMIT 1");
    	$stmt->execute($name);
    	$row = $stmt->fetch_assoc();
    	return $row;
    }
    public function CheckEmail($email) {
    	$stmt = $this->dbh->prepare("SELECT email, user_id
                                FROM users_main
                                WHERE email = :1: LIMIT 1");
    	$stmt->execute($email);
    	$row = $stmt->fetch_assoc();
    	return $row;
    	//throw new
    }
    public function CheckPassword($pass, $confirm) {
    	if($pass != $confirm)
    	{
    		throw new UserException("PASSWORD DOESNT MATCH");
    	}
    	if(!preg_match('/[A-Z]+[a-z]+[0-9]+/', $pass))
    	{
    		throw new UserException("PASSWORD not good enough, needs uppercase, lower case, and numeral");
    	}
    	/*$dbh = new DB_Mysql();
    	$stmt = $dbh->prepare("SELECT username, user_id
                                FROM users_main
                                WHERE username = :1: LIMIT 1");
    	$stmt->execute($name);
    	$row = $stmt->fetch_assoc();
    	return $row;
    	
    	*/
    }    
}



?>