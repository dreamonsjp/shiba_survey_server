<?php

require_once '../application/libraries/jsonRPCClient.php';

define( 'LS_BASEURL', 'http://myphamteen.com/esurvey/');  // adjust this one to your actual LimeSurvey URL

define( 'LS_USER', 'admin' );

define( 'LS_PASSWORD', '123456' );

// the survey to process





$survey_id = $_GET['survey_id'];

// instanciate a new client

$myJSONRPCClient = new jsonRPCClient( LS_BASEURL.'/admin/remotecontrol' );

// receive session key

$sessionKey= $myJSONRPCClient->get_session_key( LS_USER, LS_PASSWORD );

// receive all ids and info of groups belonging to a given survey


$url = $_SERVER['REQUEST_URI'];

$url = urldecode($url);

$url = html_entity_decode($url);

//$post="362167X6X114=2&362167X6X115=M";
$post= ".$url.";
 
	// convert string to an associative array
	parse_str($post, $output);
	 
	// does it look ok?
	//print_r($output);
	 
	//add response to the LS database
	$responseadded=$myJSONRPCClient->add_response($sessionKey, $survey_id, $output);
	 
	// show the reponse code
	echo"[";
	if (is_numeric ($responseadded)) 
	{
		$response["result"] = "SUCCESS";
		$response["code"] = 121;
        echo(json_encode($response));
		} else {
		$response["result"] = "False";
		$response["code"] = 122;
        echo(json_encode($response));
	 }
	// release the session key
	//$url="http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	if($url)
	{
		$file = fopen("test.txt","a");
		fwrite($file,time().".".$url." "."|"."\n");
		fclose($file);
		//echo "<center>OK";
	}
echo"]";
$myJSONRPCClient->release_session_key( $sessionKey );

?>