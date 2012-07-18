<?php

require_once('fitbitphp.php');
require_once('../config.php');

$fitbit = new FitBitPHP(
		$GLOBALS['FitbitConsumerKey'], 
		$GLOBALS['FitbitConsumerSecret'] 
		);

$fitbit->initSession('http://fredtrotter.com/oscon/fitbit_callback.php');
$oauth_secret = $fitbit->getOAuthSecret();
$oauth_token = $fitbit->getOAuthToken();
$fitbit->setResponseFormat('json');

echo "<h1>Got OAuth, copy these to config.php</h1>";
echo "<h3> OAuth Secret : '$oauth_secret' </h3>\n";
echo "<h3> OAuth Token : '$oauth_token' </h3>\n";

$profile = $fitbit->getProfile();
//$recent = $fitbit->getRecentActivities();
echo "<h1> Test data </h1>";
echo "<pre>";
print_r($profile);
echo "\n\n\n";
//print_r($recent);
echo "</pre>";

/*
getOAuthToken()
    public function getOAuthSecret()
    public function setUser($userId)
    public function setMetric($metric)
    public function getProfile()
    public function updateProfile($gender = null, $birthday = null, $height = null, $nickname = null, $fullName = null, $timezone = null)
    public function getActivities($date, $dateStr = null)
    public function getRecentActivities()

*/

?>
