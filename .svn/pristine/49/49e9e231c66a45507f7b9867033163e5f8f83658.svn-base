<?php
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

$USER->LogOut();

header('location: index.php');
?>
