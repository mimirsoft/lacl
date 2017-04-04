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
*
*
*/

//This is our password test script.  It tests and matchs our passwords.  We can feed it password, or have it autogenerate them. 
//It then hashes the password, and compares the original to the hash.  

// PHP code required by both registration and validation

ini_set("display_errors","1");
ERROR_REPORTING(E_ALL);
CRYPT_BLOWFISH or die ('No Blowfish found.');


$mysqli = new mysqli("localhost", "lacluser", "password123", "lacl");

//this line currently does nothing, as we don't use $password
$password = $mysqli->real_escape_string($_GET['password']);
$email = $mysqli->real_escape_string($_GET['email']);



include('../Password.class.php');
$pass_obj = new Password();

$password2 = $pass_obj->generatePassword(18);
echo "PASSWORD<BR/>";
echo $password2;
echo "<BR/>";

$hash = $pass_obj->createPasswordHash($password2);
echo "HASH<BR/>";
echo $hash;
echo "<BR/>";


if ($pass_obj->comparePassword($password2,$hash)) {
	// Success!
	echo "SUCCESS!!";
	echo "<BR/>";
	
}
else {
	// Invalid credentials
	echo "FAILURE!!";
	echo "<BR/>";
}


?>