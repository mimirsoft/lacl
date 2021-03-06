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


// values sent to the OAUTH API for Facebook
//are we using FACEBOOK GRAPH?
//then you need to set these to the correct values.
$client_id = "aaaaaaaaaaaaaaa";//out app id
$client_secret = "aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa";//aka the app secret

//these variables control the database connection and other important settings
//set these variables
// you should change this to your own database login                        
$DB_SETTINGS = array(
    "database"=>"mysql",
    "user"=>"lacluser",
    "pass"=>"password",
   "dbhost"=>"localhost",
   "dbname"=>"lacl");

//set this to true to use SSL only, this currently is non-functional
$SYSTEM_SETTINGS = array(
    "secure"=>false);


$BASE_DIR = "";

$SITE_URL = "http://acl";

$ARRAY = array( "BASE_DIR" => $BASE_DIR,
                "DB_SETTINGS" => $DB_SETTINGS,
                "SITE_URL" => $SITE_URL,
                "SYSTEM_SETTINGS" => $SYSTEM_SETTINGS);

//set key to hostname
$GLOBAL_CONSTANTS['localhost'] = $ARRAY;
$GLOBAL_CONSTANTS['acl'] = $ARRAY;


//////////these are the live settings


$DB_SETTINGS = array(
    "database"=>"mysql",
    "user"=>"lacluser",
    "pass"=>"password",
   "dbhost"=>"localhost",
   "dbname"=>"lacl");


$SITE_URL = "http://acl.mimirsoft.com";

$ARRAY = array( "BASE_DIR" => $BASE_DIR,
                "DB_SETTINGS" => $DB_SETTINGS,
                "SITE_URL" => $SITE_URL,
                "SYSTEM_SETTINGS" => $SYSTEM_SETTINGS);


//replace this key with URL to live site
$GLOBAL_CONSTANTS['acl.mimirsoft.com'] = $ARRAY;

//this allows multiple installations of this site to exist in a single directory with the same files
// just pulling different data



$ARRAY = $GLOBAL_CONSTANTS[$_SERVER['SERVER_NAME']];
foreach ($ARRAY as $key => $value)
{
    $$key = $value;
}


                             

?>