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

error_reporting(E_ALL);
ini_set('display_errors', '1');

include("../globalconstants.php");
include("../Database_Mysql.class.php");
include("../User.class.php");
include("../User_Session.class.php");
//include("../Application.class.php");
//include("../Access_Code.class.php");

//this is our database object class
$dbh = new DB_Mysql($DB_SETTINGS);
$objSession = new User_Session($dbh);
//initialize the session
$objSession->Impress();
$USER = $objSession->GetUserObject();

$ACTION = '';
if(isset($_REQUEST['ACTION']))
{
    $ACTION=$_REQUEST['ACTION'];
}

@$client_id=$_REQUEST['client_id'];
@$redirect_uri=$_REQUEST['redirect_uri'];
@$username=$_REQUEST['username'];
@$password=$_REQUEST['password'];

       

        
switch($ACTION)
{
    case "LOGIN":
        try{
            $USER->login($username,$password);//try to log in.
        }
        catch(UserException $e)
        {
            $WARNING['message'] = $e->getMessage();
            $WARNING['show'] = true;
        }
    break;
    case "Allow":
        //allow this client_id to acces this user
        $access_code->allowClient($client_id, $scope, $USER->GetUserID());
    break;
        
}



if($USER->IsLoggedIn())
{
	if(isset($_POST['RETURN_URL']))
	{
	header('location: '.$_POST['RETURN_URL']);
	}
	else
	{
	header('location: index.php');
	}
}
else
{
    
    //offer login or join
    
?>  <div class="left">
LOGIN TO Mimir Software Lightweight ACL
    <form action="/authenticate.php"  method=POST>
    EMAIL:<input type="TEXT"  name="username"  value="" size="40">
    
    PASS:<input type="PASSWORD"  name="password"  value="" size="40">
    <input type="SUBMIT"  name="ACTION"  value="LOGIN" >
    <input type="HIDDEN"  name="client_id"  value="<?php echo $client_id ?>" >
    <input type="HIDDEN"  name="redirect_uri"  value="<?php echo $redirect_uri ?>" >
    or <a href="/join.php">JOIN</a>
    </form>
    </div>
<?php
}





?>
