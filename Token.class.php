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
class TokenException extends Application 
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

class Token
{
    private $token;
    private $user_id;
    
    public function __construct($user_id) 
    {
        $this->user_id = $user_id;
        
        $this->token=md5(uniqid(rand()));
        $dbh = new DB_Mysql();
        $stmt = $dbh->prepare("INSERT INTO tokens (user_id, token) VALUES (:2:, :1:)");
        $stmt->execute($this->token, $this->user_id);
    }
    
     public function getToken()
     {
         return $this->token;
     }
}



?>