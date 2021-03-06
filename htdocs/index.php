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

//this is our database object class
$dbh = new DB_Mysql($DB_SETTINGS);
$objSession = new User_Session($dbh);
//initialize the session
$objSession->Impress();
$USER = $objSession->GetUserObject();

//this is the URL the response from facebook needs to post to. 
//replace acl.mimirsoft.com with your url in the globalconstants.php file
$redirect_url = $SITE_URL."/signup_facebook.php";


?>

<div class="logo" >YOUR LOGO HERE

</div>
<?php

if($USER->isLoggedIn())
{
	?><div class="left"><?php echo $USER->GetUserName(); ?><a href="/logout.php">LOGOUT</a></div>
    <?php
}
else
{
	/*
	 *  Mimirsoft ACL system 
	 *	This is if you want to use it as an API for a third party site
	 * <form class="left" action="http://mimirsoft.com/acl/authenticate.php?client_id=1&redirect_uri=<?php  echo urlencode("http://foodhat/signup_ac.php") ;?>"  method=POST>
    <input type="SUBMIT"  name="LOGIN"  value="Make Account" >
    </form>
    
	 */
	
?>  <div class="left">
    <form class="left" action="/authenticate.php"  method=POST>
    USER:<input type="TEXT"  name="username"  value="" size="20">
    
    PASS:<input type="PASSWORD"  name="password"  value="" size="20">
    <input type="SUBMIT"  name="ACTION"  value="LOGIN" >
    </form>
    <form class="left" action="/join.php"  method=POST>
    <input type="SUBMIT"  name="GOTO"  value="SIGN UP" >
    </form>
    <form class="left" action="https://graph.facebook.com/oauth/authorize?client_id=<?php  echo $client_id ?>&redirect_uri=<?php  echo urlencode($redirect_url) ;?>"  method=POST>
    <input type="SUBMIT"  name="LOGIN"  value="LOGIN VIA FACEBOOK" >
    </form>
    </div>
    </div>
<?php
}
?>

<div>
	<ul>
  	<LI><a href="/index.php">HOME</a></LI>
	</ul>
</div>

</div>
