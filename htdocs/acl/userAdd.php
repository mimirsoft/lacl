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

//$_POST = $_GET;
//this function takes info, and 
 
if(isset($_POST['user']) && isset($_POST['apikey']) && $_POST['apikey']=='cde3xsw2' && isset($_POST['application']) && isset($_POST['application_url'])&& isset($_POST['confirmation_url']) )
{
	include("../../globalconstants.php");
	include("../../Database_Mysql.class.php");
	
	include("../../Temp_User.class.php");

	
	// Random confirmation code
	$confirm_code=md5(uniqid(rand()));

	// values sent from form
	$user_info=$_POST['user'];
	$confirmation_url=$_POST['confirmation_url'];
	
	// take a given email address and split it into the username and domain.
	list($userName, $mailDomain) = split("@", $user_info['email']);
	if (checkdnsrr($mailDomain, "MX")) {
	 // this is a valid email domain!
	}
	else {
	  $response['error'] = "bad_email";
	  echo json_encode($response);
	  exit;
	}
	//check if the user already exists
	$dbh = new DB_Mysql();
	$stmt = $dbh->prepare("SELECT * FROM users_main WHERE username =:1:");
	$stmt->execute($user_info['login']);
	if($stmt->num_rows() > 0)
	{
	  	$response['error'] = "u_taken";
	  	echo json_encode($response);
		exit;
	}    
	//check if the email already exists
	$stmt = $dbh->prepare("SELECT * FROM users_main WHERE email =:1:");
	$stmt->execute($user_info['email']);
	if($stmt->num_rows() > 0)
	{
	  	$response['error'] = "e_taken";
	  	echo json_encode($response);
		exit;
	}    
	
	// check the passwords if they match
	if($user_info['password'] != $user_info['password_confirmation'])
	{
	  	$response['error'] = "p_match";
	  	echo json_encode($response);
		exit;
	}

	include('../../password.php');
	$hash = getPasswordHash( getPasswordSalt(), $user_info['password'] );
	
	// Insert data into database
	$stmt = $dbh->prepare("INSERT INTO temp_members (confirm_code, username, email, hashed_password, lastname, firstname, join_app, join_url)VALUES(:1:, :2:, :3:, :4:, :5:, :6:, :7:, :8:)");
	
	// if suceesfully inserted data into database, send confirmation link to email
	if($stmt->execute($confirm_code, $user_info['login'], $user_info['email'], $hash, $user_info['lastname'], $user_info['firstname'], $_POST['application'], $user_info['join_url'])){
	
	// ---------------- SEND MAIL FORM ----------------
	
	// send e-mail to ...
	$to=$user_info['email'];
	
	// Your subject
	$subject="Your confirmation link here";
	
	// From
	$header="from: Signup from ".$_POST['application']." <signup@".$_POST['application_url'].">";
	
	// Your message
	$message="Your Comfirmation link \r\n";
	$message.="Click on this link to activate your account \r\n";
	$message.=$confirmation_url."?passkey=$confirm_code\n";
	
	// send email
	$sentmail = mail($to,$subject,$message,$header);

	}
	
	// if not insert
	else {
		$response['error'] = "no_insert";
		$response['errormsg'] = "Unable to add your email to our database";
	  	echo json_encode($response);
		exit;
	}
	
	// if your email succesfully sent
	if($sentmail){
		$response['sent'] = true;
	  	echo json_encode($response);
		exit;
		
	}
	else {
		$response['error'] = "no_mail";
		$response['errormsg'] = "Cannot send Confirmation link to your e-mail address";
	  	echo json_encode($response);
		exit;
	}
}
?>