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
class ApplicationException extends Application 
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

class Application
{
    private $application_id;
    private $application_name;
    private $api_key;
    private $application_secret;
    private $user_id;
    /*public function __construct($username, $pass) 
    {
        $dbh = new DB_Mysql();
        $stmt = $dbh->prepare("SELECT *
                                FROM users_main 
                                WHERE username = :1: LIMIT 1");
        $stmt->execute($_POST['username']);
        $row = $stmt->fetch_assoc();
        $this->username = $row['username'];
        $this->lastname = $row['lastname'];
        $this->email = $row['email'];
        $this->firstname = $row['firstname'];
        $this->fullname = $row['firstname']." ".$row['lastname'];
        $this->user_id = $row['user_id'];
        $this->hashed_password = $row['hashed_password'];
        $this->authenticated = $this->comparePassword($pass, $this->hashed_password);
        //$this->logged_in = $logged_in;
        //$this->native_session_id = $native_session_id;
    }
    */
    public function __construct($id) 
    {
        $dbh = new DB_Mysql();
        $stmt = $dbh->prepare("SELECT application_id, application_name, api_key, application_secret, user_id 
                                FROM applications 
                                WHERE application_id = :1:");
        $stmt->execute($id);
        $row = $stmt->fetch_assoc();
        $this->application_id = $id;
        $this->application_name = $row['application_name'];
        $this->api_key = $row['api_key'];
        $this->application_secret = $row['application_secret'];
        $this->user_id = $row['user_id'];
    }
    
    /*function comparePassword( $password, $hash )
    {
        $salt = substr( $hash, 0, 8 );
        $hashed = $this->getPasswordHash( $salt, $password );
        return $hash == $hashed;
    }
    function getPasswordHash( $salt, $password )
    {
        return $salt . ( hash( 'whirlpool', $salt . $password ) );
    }
    function getPasswordHash( $salt, $password )
    {
        return $salt . ( hash( 'whirlpool', $salt . $password ) );
    }
    function getAuthenticated()
    {
        return $this->authenticated;
    }   
   */
    function getApplicationName()
    {
        return $this->application_name;
    }
    function getApplicationId()
    {
        return $this->application_id;
    }
    
    function getApplicationSecret()
    {
        return $this->application_secret;
    }
    function getApiKey()
    {
        return $this->api_key;
    }
}



?>