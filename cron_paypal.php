<?php

// you may need to hard code this..
require_once('../config.php');
require_once('fitbitphp.php');

$environment = 'live';	// or 'beta-sandbox' or 'live'

//first we check fitbit for out data...
//we have copied and hard coded our OAuth data here...

$fitbit = new FitBitPHP(
		$GLOBALS['FitbitConsumerKey'], 
		$GLOBALS['FitbitConsumerSecret'] 
		);

$fitbit->setOAuthDetails(
		$GLOBALS['FitbitOAuthToken'],
		$GLOBALS['FitbitOAuthSecret']
	);
$fitbit->setResponseFormat('json');

try{
	$recent = json_decode($fitbit->getRecentActivities());
} catch (Exception $e) {
	echo "Shit, we could not get to FitBit... lets give you the benifit of the doubt\n";
	exit();
}
	
	$steps = $recent['activities']['goals'];
	if($steps < $GLOBALS['']){
		lets_pay("donations@wikimedia.org",1.50);
	}else{
		//perhaps an email or tweet for success...
	}

//this is my function that simply sends a payment using the PayPal MassPay API...
function lets_pay($to_paypal,$amount){

// Set request-specific fields.
// these are static from paypals examples...
	$emailSubject =urlencode('Made an automatic payment');
	$receiverType = urlencode('EmailAddress');
	$currency = urlencode('USD');  // or other currency ('GBP', 'EUR', 'JPY', 'CAD', 'AUD')

// Add request-specific fields to the request string.
	$nvpStr="&EMAILSUBJECT=$emailSubject&RECEIVERTYPE=$receiverType&CURRENCYCODE=$currency";

        $receiverEmail = urlencode($to_paypal);
        $amount = urlencode($amount);
        $uniqueID = urlencode(uniqid());
        $note = urlencode("An Automatic Payment");
        $nvpStr .= "&L_EMAIL0=$receiverEmail&L_Amt0=$amount&L_UNIQUEID0=$uniqueID&L_NOTE0=$note";

// Execute the API operation; see the PPHttpPost function above.
$httpParsedResponseAr = PPHttpPost('MassPay', $nvpStr);

	if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) {
        	return('MassPay Completed Successfully: '.print_r($httpParsedResponseAr, true));
	} else  {
        	exit('MassPay failed: ' . print_r($httpParsedResponseAr, true));
	}
}

//this function comes from paypal...
//it has been altered to get credentials from global config values... 
//bad design, but what the hell...
/**
 * Send HTTP POST Request
 *
 * @param	string	The API method name
 * @param	string	The POST Message fields in &name=value pair format
 * @return	array	Parsed HTTP Response body
 */
function PPHttpPost($methodName_, $nvpStr_) {
	global $environment;

	// Set up your API credentials, PayPal end point, and API version.
	$API_UserName = urlencode($GLOBALS['API_UserName']);
	$API_Password = urlencode($GLOBALS['API_Password']);
	$API_Signature = urlencode($GLOBALS['API_Signature']);
	$API_Endpoint = "https://api-3t.paypal.com/nvp";
	if("sandbox" === $environment || "beta-sandbox" === $environment) {
		$API_Endpoint = "https://api-3t.$environment.paypal.com/nvp";
	}
	$version = urlencode('51.0');

	// Set the curl parameters.
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $API_Endpoint);
	curl_setopt($ch, CURLOPT_VERBOSE, 1);

	// Turn off the server and peer verification (TrustManager Concept).
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);

	// Set the API operation, version, and API signature in the request.
	$nvpreq = "METHOD=$methodName_&VERSION=$version&PWD=$API_Password&USER=$API_UserName&SIGNATURE=$API_Signature$nvpStr_";

	// Set the request as a POST FIELD for curl.
	curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);

	// Get response from the server.
	$httpResponse = curl_exec($ch);

	if(!$httpResponse) {
		exit("$methodName_ failed: ".curl_error($ch).'('.curl_errno($ch).')');
	}

	// Extract the response details.
	$httpResponseAr = explode("&", $httpResponse);

	$httpParsedResponseAr = array();
	foreach ($httpResponseAr as $i => $value) {
		$tmpAr = explode("=", $value);
		if(sizeof($tmpAr) > 1) {
			$httpParsedResponseAr[$tmpAr[0]] = $tmpAr[1];
		}
	}

	if((0 == sizeof($httpParsedResponseAr)) || !array_key_exists('ACK', $httpParsedResponseAr)) {
		exit("Invalid HTTP Response for POST request($nvpreq) to $API_Endpoint.");
	}

	return $httpParsedResponseAr;
}
?>
