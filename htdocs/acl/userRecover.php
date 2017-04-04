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

if(isset($_POST['email']) && isset($_POST['apikey']) && $_POST['apikey']=='cde3xsw2' && isset($_POST['application']) && isset($_POST['confirmation_url']) )
{
	include("../../globalconstants.php");
	include("../../Database_Mysql.class.php");
	
	$email = $_POST['email'];
	$confirmation_url = $_POST['confirmation_url'];
	
    $sql="SELECT * FROM users_main WHERE email = :1:";
    $dbh = new DB_Mysql();
    $stmt = $dbh->prepare($sql);
    $stmt->execute($email);
    if($stmt->num_rows() > 0)
    {
        $row = $stmt->fetch_assoc();
    }
    else{
      //if we did not find the email
	  $response['error'] = "emailnotfound";
	  echo json_encode($response);
	  exit;
    }

    // Random confirmation code
    $confirm_code=md5(uniqid(rand()));
    
    // Insert data into database
    $stmt = $dbh->prepare("UPDATE users_main SET confirm_code=:1: WHERE user_id = :2:");
    
    // if suceesfully inserted data into database, send confirmation link to email
    if($stmt->execute($confirm_code, $row['user_id']) ) 
    {
        // ---------------- SEND MAIL FORM ----------------
        // send e-mail to ...
        $to=$row['email'];
        // Your subject
        $subject="Username/Password Recovery";
        // From
        $header="from: Signup from ".$_POST['application']." <signup@".$_POST['application_url'].">";// Your message
        $message="Click on this link to reset your password \n";
        $message.="Your username is $row[username].\n";
        $message.=$confirmation_url."?passkey=$confirm_code\n";
        
        // send email
        $sentmail = mail($to,$subject,$message,$header);
    
    }
    // if not insert
    else {
    	  $response['error'] = "noconfirmationcode";
    	  echo json_encode($response);
    	  exit;
    }

    // if your email succesfully sent
    if($sentmail){
        	  $response['success'] = true;
        	  $response['message'] = "An Email to recover your username/password Has Been Sent To Your Email Address.";
        	  echo json_encode($response);
        	  exit;
    }
    else {
        $response['error'] = "couldnotsendemail";
        echo json_encode($response);
        exit;
    }


}
?>
