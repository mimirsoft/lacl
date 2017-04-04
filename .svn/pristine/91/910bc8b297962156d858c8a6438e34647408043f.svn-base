
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
include("../../globalconstants.php");
include("../../Database_Mysql.class.php");
	
// Passkey that got from link
$passkey=$_POST['passkey'];
$inserted=false;

if(isset($_POST['apikey']) && $_POST['apikey']=='cde3xsw2' && isset($_POST['application']) )
{
	
	// Retrieve data from table where row that match this passkey
	$dbh = new DB_Mysql();
	// Insert data into database
	$stmt = $dbh->prepare("SELECT * FROM temp_members WHERE confirm_code=:1:");
	
	// If successfully queried
	if($stmt->execute($passkey))
	{
		  // Count how many row has this passkey
		  // if found this passkey in our database, retrieve data from table "temp_members_db"
		  if($stmt->num_rows()==1){
		    $rows=$stmt->fetch_assoc();
		    $name=$rows['username'];
		    $email=$rows['email'];
		    $password=$rows['hashed_password'];
		    $firstname=$rows['firstname'];
		    $lastname=$rows['lastname'];
		    $join_app=$rows['join_app'];
		    $join_url=$rows['join_url'];
		    // Insert data that retrieves from "temp_members_db" into table "registered_members"
		    $sql2="INSERT INTO users_main(username, email, hashed_password, lastname, firstname, created_at, join_app)VALUES('$name', '$email', '$password', '$lastname', '$firstname', NOW(), '$join_app')";
		    $stmt2 = $dbh->prepare($sql2);
		    $stmt2->execute();
		    $user_id = $stmt2->insert_id();
		    $inserted=true;
		  }
		  // if not found passkey, display message "Wrong Confirmation code"
		  else {
		    echo "Wrong Confirmation code";
		  }
		  // if successfully moved data from table"temp_members_db" to table "registered_members" displays message "Your account has been activated" and don't forget to delete confirmation code from table "temp_members_db"
		  if($inserted){
			//echo "Your account has been activated<BR />";
		
		    //currently, roles are to be done client side, this meerly does authentication
		    //$stmt3 = $dbh->prepare("INSERT INTO `users_has_roles` VALUES (NULL, 2, :1:)");
		    //$stmt3->execute($stmt2->insert_id());
		    
		    // Delete information of this user from table "temp_members_db" that has this passkey
		    $stmt3 = $dbh->prepare("DELETE FROM temp_members WHERE confirm_code = :1: OR username = :2: or email = :3: ");
		    $stmt3->execute($passkey, $name, $email);
		    //echo "<a href=http://".$join_url.">Click here to login here.</a>";
		    $array['user_id'] = $user_id;
			$array['inserted'] = true;
			echo json_encode($array);
		  	
		  }
		  else{
		    echo "Account Not Activated";
		  }
	}
}
?>

