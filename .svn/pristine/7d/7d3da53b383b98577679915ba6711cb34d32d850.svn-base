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

//test if we can make new user.  If we can, make it and log them in, and redirect to main page.
//if not, stay on this page with an error message.
$login = '';
$email = '';
$password = '';
$password_confirmation = '';
$firstname = '';
$lastname = '';
$WARNING['message'] = '';

if(isset($_POST['ACTION']))
{
	@$login = $_POST['user']['login'];
	@$email = $_POST['user']['email'];
	@$password = $_POST['user']['password'];
	@$password_confirmation = $_POST['user']['password_confirmation'];
	@$firstname = $_POST['user']['firstname'];
	@$lastname = $_POST['user']['lastname'];
	
	switch($_POST['ACTION'])
	{
		
		case "Signup":
			try{
				$email_exist = $USER->CheckEmail($email);
				if($email_exist)
				{
					throw new UserException("EMAIL EXISTS IN DATABASE ALREADY");
				}
				if($password != $password_confirmation)
				{
					throw new UserException("Password Doesn't Match");
				}
				else{
					//the user object takes the following variables
					
					$USER->create($login, $email, $password,$lastname,$firstname,
							"mywebsite", $login, '','', '');
					//
					$objSession->updateUserId($USER->getUserId());
					 
					//need to send confirmation email
					
					$WARNING['message'] .=  "CONGRATULATIONS, YOU HAVE SUCESSFULLY SIGNED UP.  YOUR USERNAME AND PASSWORD HAVE BEEN EMAILED TO YOU";
								
					$USER->login($login, $password);
					//now email signup to user
					
					// send e-mail to ...
					$to=$email;
					
					// Your subject
					$subject="SIGN UP AT MYWEBSITE.COM";
					
					// From
					$header="from: JOIN@MYSITE <noreply@MYWEBSITE.COM>";
					// Your message
					$message="THIS IS AN AUTOMATED EMAIL \r\n";
					$message.= " \r\n";
					$message.="YOU HAVE SIGNED UP FOR PRIVATE.LIFE! \r\n";
					$message.="HERE IS YOUR USERNAME AND PASSWORD THAT HAS BEEN GENERATED FOR YOU!! \r\n";
					$message.= " \r\n";
					$message.= "USERNAME: ".$login." \r\n";
					$message.= "PASSWORD: ".$password." \r\n";
					
					
					$message.= " \r\n";
					// send email
					$sentmail = mail($to,$subject,$message,$header);
					//$WARNING['show'] = true;
					$WARNING['message'] .= "::EMAIL SENT!!";
				
				}
				
				
			}
			catch(UserException $exception)
			{
				$WARNING['show'] = true;
				$WARNING['message'] = $exception->message;
			}
		break;
		default:
		break;
		
		
	}
	
}


$rejection ='';
if(@$_GET['rejection'])
{
  $rejection = $_GET['rejection'];
}
if($rejection == 'p_match')
{
  echo "PASSWORDS DO NOT MATCH";
}
if($rejection == 'bad_email')
{
  echo "EMAIL IS INVALID";
}
if($rejection == 'u_taken')
{
  echo "USERNAME IS TAKEN";
}
if($rejection == 'e_taken')
{
  echo "EMAIL ADDRESS ALREADY HAS AN ACCOUNT";
}



$THISPAGE = "Join"; ?>


<!DOCTYPE html>

<html>
<title>MYWEBSITE - SIGNUP</title>
<body>
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
    
    </div>
<?php
}
?>
<div>
	<ul>
  	<LI><a href="/index.php">HOME</a></LI>
	</ul>
</div>

<div class="main_box">
				<?php if($WARNING['message'] != '')
	{
		?>
		<h2><?php echo @$WARNING['message']?></h2>
		<?php 
	}
	?>
	
	<form action="join.php" method="post">
	<?php

	 /*Currently not in use, left for later reactiviation.  Structure should already be in database
	  * 
	  * 
	  *
	 */
	 
	?>
	<label for="user_login">Username</label><br/>
	<input id="user_login" name="user[login]" size="20" type="text" value="" /><br/>
	
	<label for="user_password">Password(needs uppercase, lower case, and numeral)</label><br/>
	<input id="user_password" name="user[password]" size="20" type="password" /><br/>
	
	<label for="user_password_confirmation">Password Confirmation</label><br/>
	<input id="user_password_confirmation" name="user[password_confirmation]" size="20" type="password" /><br/>
	
	<label for="user_email">Email</label><br/>
	<input id="user_email" name="user[email]" size="20" type="text" value="" /><br/>
	
	<label for="user_firstname">First Name </label><br/>
	<input id="user_firstname" name="user[firstname]" size="20" type="text" value="" /><br/>
	<label for="user_lastname">Last Name</label><br/>
	<input id="user_lastname" name="user[lastname]" size="20" type="text" value="" /><br/>
	
	<input name="ACTION" type="submit" value="Signup" />
	</form>
</div>  		


</body>
</html>
