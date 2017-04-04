<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');


include("../globalconstants.php");

include("../Database_Mysql.class.php");
include("../User.class.php");
include("../User_Session.class.php");
include("../Password.class.php");

//this is our database object class
$dbh = new DB_Mysql($DB_SETTINGS);
$objSession = new User_Session($dbh);
//initialize the session
$objSession->Impress();
$USER = $objSession->GetUserObject();

require 'facebook-sdk/facebook.php';

// Create our Application instance (replace this with your appId and secret).
$facebook = new Facebook(array(
  'appId'  => $client_id,
  'secret' => $client_secret,
    'cookie' => true  
));
$user = $facebook->getUser();
if($user) 
{
	try 
	{
    	$user_profile = $facebook->api('/me');
  	} 
  	catch (FacebookApiException $e) 
  	{
    	error_log($e);
    	$user = null;
  	}
  	//check if user already exits with external ID
  	if($USER->getUserFromExternalID($user_profile['id'], "facebook"))
  	{
  		$USER->setLoggedIn(true);
  		$objSession->updateUserId($USER->getUserId());
  	}
	//if not....
  	else
  	{
	  	//create($name, $email, $password, $lastname, $firstname, $join_app, $displayname, $externalId, $externalLink, $externalSource)
	  	$PASS = new Password();
	  	$password = $PASS->generatePassword(12);
	  	//create a record in our system from our user
	  	$USER->create($user_profile['name'], '', $password,$user_profile['last_name'],$user_profile['first_name'],
	  			"mimirsoft_acl", $user_profile['name'], $user_profile['id'],$user_profile['link'], "facebook");
	
	  	//now log them in
	  	$objSession->updateUserId($USER->getUserId());
	}

}
if ($user) {
  $logoutUrl = $facebook->getLogoutUrl(array('next' => $SITE_URL.'/logout.php'));
} else {
  $loginUrl = $facebook->getLoginUrl();
}

if(isset($_POST['RETURN_URL']))
{
	//header('location: '.$_POST['RETURN_URL']);
	header('location: index.php');
}
else
{
	header('location: index.php');
}
/*
<!doctype html>
<html xmlns:fb="http://www.facebook.com/2008/fbml">
  <body>
    <?php if ($user): ?>
      <a href="<?php echo $logoutUrl; ?>">Logout</a>
    <?php else: ?>
      <div>
        Login using OAuth 2.0 handled by the PHP SDK:
        <a href="<?php echo $loginUrl; ?>">Login with Facebook</a>
      </div>
    <?php endif ?>

    <h3>PHP Session</h3>
    <pre><?php print_r($_SESSION); ?></pre>

    <?php if ($user): ?>
      <h3>You</h3>
      <img src="https://graph.facebook.com/<?php echo $user; ?>/picture">

      <h3>Your User Object (/me)</h3>
      <pre><?php print_r($user_profile); ?></pre>
    <?php else: ?>
      <strong><em>You are not Connected.</em></strong>
    <?php endif ?>
  </body>
</html>

*/

?>
