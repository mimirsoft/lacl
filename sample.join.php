<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');
require_once('../foodhat_framework/framework_masterinclude.php');
require_once("index.css");


//include('../acl/Database_Mysql.class.php');
//include('../acl/User.class.php');
//include('../acl/Password.class.php');

echo "WOOT";
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
				//create new user 
				/*
				 * 
				 * 
				 $email_exist = $USER->CheckEmail($email);
				if($email_exist) 
				{
					throw new UserException("EMAIL EXISTS");
				}
				$user_exist = $USER->CheckUsername($login);
				if($user_exist) 
				{
					throw new UserException("USERNAME EXISTS");
				}
				$user_exist = $USER->CheckPassword($password, $password_confirmation);
				if($user_exist) 
				{
					throw new UserException("PASSWORD MISMATCH");
				}
				else{
					
					$userid= $USER->create($login, $email, $password, $lastname, $firstname, '');
					//echo $userid."USERID<BR/>";
					//echo $login."login<BR/>";
					//echo $password."password<BR/>";
					//echo $USER->print_native()." session_id<BR/>";
					
					$USER->login($login, $password);
				}
				*/
				$email_exist = $USER->CheckEmail($email);
				if($email_exist)
				{
					throw new UserException("EMAIL EXISTS");
				}
				else{
					$password = Password::generatePassword(8); 
					//echo $password."password<BR/>";
					
					//Need to let people pick own username
					//$login = $USER->GenerateUsername();	
					
					
					$USER->create($user_profile['name'], '', $password,$user_profile['last_name'],$user_profile['first_name'],
							"foodhat", $user_profile['name'], $user_profile['id'],$user_profile['link'], "facebook");
					//
					$objSession->updateUserId($USER->getUserId());
					 
					
					//echo $login."login<BR/>";
					//echo $userid."USERID<BR/>";
					//echo $login."login<BR/>";
					//echo $password."password<BR/>";
					//echo $USER->print_native()." session_id<BR/>";
					
					//need to send confirmation email
					
					$WARNING['message'] .=  "CONGRATULATIONS, YOU HAVE SUCESSFULLY SIGNED UP.  YOUR USERNAME AND PASSWORD HAVE BEEN EMAILED TO YOU";
								
					$USER->login($login, $password);
					//now email signup to user
					
					// send e-mail to ...
					$to=$email;
					
					// Your subject
					$subject="SIGN UP AT PRIVATE.LIFE";
					
					// From
					$header="from: PRIVACY.LIFE <INFO@private.life>";
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
<title>FoodHat - Restaurant Search</title>
<body>
<?php
include('navbar.phtml');
?>
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
