<?php

/*
 *
This file is part of Mimirsoft Lightweight ACL System
Copyright (C) 2011, Kevin Milhoan, Mimir Software Corporation

Mimirsoft Lightweight ACL System is free software: you can redistribute it and/or modify
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
class Temp_User
{
    private $native_session_id;
    private $logged_in;
    private $user_id;
    private $user_name;
    private $user_lastname;
    private $user_email;
    private $user_firstname;
    private $user_fullname;
    private $permissions;
    private $limited_permissions;
    private $hashed_password;
    private $authenticated;
    
    public function __construct($username, $pass) 
    {
        $dbh = new DB_Mysql();
        $stmt = $dbh->prepare("SELECT *
                                FROM users_main 
                                WHERE username = :1: LIMIT 1");
        $stmt->execute($_POST['username']);
        $row = $stmt->fetch_assoc();
        $this->user_name = $row['username'];
        $this->user_lastname = $row['lastname'];
        $this->user_email = $row['email'];
        $this->user_firstname = $row['firstname'];
        $this->user_fullname = $row['firstname']." ".$row['lastname'];
        $this->user_id = $row['user_id'];
        $this->hashed_password = $row['hashed_password'];
        $this->authenticated = $this->comparePassword($pass, $this->hashed_password);
        //$this->logged_in = $logged_in;
        //$this->native_session_id = $native_session_id;
    }
    /*public function __construct($id, $logged_in, $native_session_id) 
    {
        $dbh = new DB_Mysql();
        $stmt = $dbh->prepare("SELECT username, lastname, firstname, email 
                                FROM users_main 
                                WHERE user_id = '$id'");
        $stmt->execute();
        $row = $stmt->fetch_assoc();
        $this->user_name = $row['username'];
        $this->user_lastname = $row['lastname'];
        $this->user_email = $row['email'];
        $this->user_firstname = $row['firstname'];
        $this->user_fullname = $row['firstname']." ".$row['lastname'];
        $this->user_id = $id;
        $this->logged_in = $logged_in;
        $this->native_session_id = $native_session_id;
    }
    */
    function comparePassword( $password, $hash )
    {
        return password_verify($password, $hash);
        //$salt = substr( $hash, 0, 8 );
        //$hashed = $this->getPasswordHash( $salt, $password );
        //return $hash == $hashed;
    }
    function getPasswordHash( $salt, $password )
    {
        return mcrypt_create_iv(22, MCRYPT_DEV_URANDOM);
        //return $salt . ( hash( 'whirlpool', $salt . $password ) );
    }
    function getAuthenticated()
    {
        return $this->authenticated;
    }   
    function getUserArray()
    {
    	$obj['user_id'] = $this->user_id;
        $obj['username'] = $this->user_name;
        $obj['lastname'] = $this->user_lastname;
        $obj['email'] = $this->user_email;
        $obj['firstname'] = $this->user_firstname;
        $obj['fullname'] = $this->user_fullname;
		return $obj;        
    }
    public function print_native()
    {
        echo $this->native_session_id;
    }
    
    //not in use
    public function Login($strUsername, $strPlainPassword) 
    {
        $dbh = new DB_Mysql();
        $stmt = $dbh->prepare("SELECT user_id, username, hashed_password, email 
                                FROM users_main 
                                WHERE username = '$strUsername' LIMIT 1");
        $stmt->execute();
        $row = $stmt->fetch_assoc();
        if ($this->comparePassword($strPlainPassword, $row['hashed_password'])) {
            $this->user_id = $row["user_id"];
            $this->user_name = $row["username"];
            $this->user_email = $row['email'];
            $this->logged_in = true;
            $dbh = new DB_Mysql();
            $stmt = $dbh->prepare("UPDATE user_session 
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

    public function LogOut() {
    if ($this->logged_in == true) {
        $dbh = new DB_Mysql();
        $stmt = $dbh->prepare("UPDATE user_session 
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
    return($this->user_name);
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
        $dbh = new DB_Mysql();
        $stmt = $dbh->prepare("SELECT *
                                FROM users_main 
                                ORDER BY username");
        $stmt->execute();
        return $stmt->fetchall_assoc();
    }
    public function GetUserFromUsername($name) {
        $dbh = new DB_Mysql();
        $stmt = $dbh->prepare("SELECT username, user_id
                                FROM users_main 
                                WHERE username = :1: LIMIT 1");
        $stmt->execute($name);
        return $stmt->fetch_assoc();
    }
    
}



?>