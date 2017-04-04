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


class AccessCodeException extends Application 
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

class Access_Code
{
    private $application_id;
    private $code;
    private $user_id;

    function __construct() 
    {
        $argv = func_get_args();
        switch( func_num_args() ) 
        {
            case 0:
                break;
            case 1:
                self::__construct1($argv[0]);
                break;
        }
    }
    public function __construct1($id) 
    {
        $dbh = new DB_Mysql();
        $stmt = $dbh->prepare("SELECT application_id, user_id, code 
                                FROM user_application_permissions 
                                WHERE code = :1:");
        $stmt->execute($id);
        if($stmt->num_rows() > 0)
        {
            $row = $stmt->fetch_assoc();
            $this->application_id = $row['application_id'];
            $this->code = $row['code'];
            $this->user_id = $row['user_id'];
        }
        return(false);
        
    }
    /*
    function comparePassword( $password, $hash )
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
    function getApplicationId()
    {
        return $this->application_id;
    }
    function checkCode($code)
    {
        $dbh = new DB_Mysql();
        $stmt = $dbh->prepare("SELECT * 
        						 FROM user_application_permissions 
        					    WHERE application_id = $this->application_id
        					      AND code = :1:");
        $stmt->execute($code);
        if($stmt->num_rows() > 0)
        {
            return(true);
        }
        return(false);
    }
    function getCode()
    {
        return $this->code;
    }
    function getUserId()
    {
        return $this->user_id;
    }
    
    public function allowClient($client_id, $scope, $user_id) {
        
        $dbh = new DB_Mysql();
        $stmt = $dbh->prepare("INSERT INTO user_application_permissions (user_id, application_id, basic) VALUES (:2:, :1:, 1)
                    ON DUPLICATE KEY UPDATE basic=1");
        $stmt->execute($client_id, $user_id);
        if($scope != '')
        {
            $permissions = explode(",", $scope);     
            foreach($permission as $p)
            {
                switch($p)
                {
                    case "email":
                        $this->allowClientEmail($client_id, $user_id);
                    break;
                }   
            }
        }
        
        return(true);
    }
    public function allowClientEmail($client_id, $user_id) {
        
        $dbh = new DB_Mysql();
        $stmt = $dbh->prepare("INSERT INTO user_application_permissions (user_id, application_id, email) VALUES (:2:, :1:, 1)
                    ON DUPLICATE KEY UPDATE email=1");
        $stmt->execute($client_id, $user_id);
        return(true);
    }
    public function allowsClient($client_id, $user_id) {
        
        $dbh = new DB_Mysql();
        $stmt = $dbh->prepare("SELECT * 
        						 FROM user_application_permissions 
        					    WHERE user_id = :2:
        					      AND application_id = :1:
        					      AND basic = 1");
        $stmt->execute($client_id, $user_id);
        if($stmt->num_rows() > 0)
        {
            return(true);
        }
        return(false);
    }
    public function setClientCode($client_id, $code, $user_id) {
        
        $dbh = new DB_Mysql();
        $stmt = $dbh->prepare("INSERT INTO user_application_permissions (user_id, application_id, code) VALUES (:3:, :1:, :2:)
                    ON DUPLICATE KEY UPDATE code=:2:");
        $stmt->execute($client_id, $code, $user_id);
        return(true);
    }
}



?>