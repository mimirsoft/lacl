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

include("../../globalconstants.php");
include("../../Database_Mysql.class.php");
include("../../User.class.php");
include("../../Application.class.php");
include("../../Access_Code.class.php");
include("../../Token.class.php");

@$client_id=$_REQUEST['client_id'];
@$redirect_uri=$_REQUEST['redirect_uri'];
@$code=$_REQUEST['code'];
@$client_secret=$_REQUEST['client_secret'];
//if scope was set, parse it into an array
$application = new Application($client_id);
$access_code = new Access_Code($code);


//check our client with our secret
if($application->getApplicationSecret() != $client_secret && $client_secret != "")
{
    $array['message'] = "IncorrectApplicationSecret";
	$array['error'] = true;
	echo json_encode($array);
	exit;
}
//if good match, check if code's app id matches our app id
if($access_code->getApplicationId() == $application->getApplicationId())
{
    
    if($access_code->getUserId() != '')
    {
        //if valid, create token
        $token = new Token($access_code->getUserId());
        $token->getToken();
        
        //insert token
        $array['access_token'] = $token->getToken();
    	$array['expires'] = 3600;
    	echo json_encode($array);
    	
    }
}






?>
