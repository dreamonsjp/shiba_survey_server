<?php
require("config.inc.php");
error_reporting(0);
$import = $_GET['import'];
$code = $_GET['code'];
$multi = $_GET['multi'];

if($import == 1){

require_once '../application/libraries/jsonRPCClient.php';

define( 'LS_BASEURL', 'http://myphamteen.com/esurvey/');  // adjust this one to your actual LimeSurvey URL

define( 'LS_USER', 'admin' );

define( 'LS_PASSWORD', '123456' );

// the survey to process

$IdQuestion = $_GET['qid'];

$NewHolder = $_GET['holder'];

$urlQuestionBank = "http://question_bank.dreamons.jp/questions/json_get/".$IdQuestion."";

//echo $urlQuestionBank;

$GetJson = (file_get_contents($urlQuestionBank));

//echo $GetJson;


$ObJSon = json_decode($GetJson,true);

//print_r($ObJSon);

foreach ($ObJSon as $question_data) {
    $dataXML = $question_data['question_data'];
    $holder = $question_data['question'];
}

//echo $dataXML;
//echo $holder;
if($NewHolder != ""){

	$NewDataXML = str_replace($holder,$NewHolder,$dataXML);

}else{

	$NewDataXML = $dataXML;

}
//$new = "con nay la con gi";

//$NewDataXML = str_replace($holder,$new,$dataXML);

//echo $NewDataXML;

$survey_id = $_GET['survey_id'];
$iGroupID = $_GET['iGroupID'];
$sImportDataType = $_GET['sImportDataType'];

/*$str = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<document>\n <LimeSurveyDocType>Question<\/LimeSurveyDocType>\n <DBVersion>164<\/DBVersion>\n <languages>\n  <language>vi<\/language>\n  <language>en<\/language>\n <\/languages>\n <questions>\n  <fields>\n   <fieldname>qid<\/fieldname>\n   <fieldname>parent_qid<\/fieldname>\n   <fieldname>sid<\/fieldname>\n   <fieldname>gid<\/fieldname>\n   <fieldname>type<\/fieldname>\n   <fieldname>title<\/fieldname>\n   <fieldname>question<\/fieldname>\n   <fieldname>preg<\/fieldname>\n   <fieldname>help<\/fieldname>\n   <fieldname>other<\/fieldname>\n   <fieldname>mandatory<\/fieldname>\n   <fieldname>question_order<\/fieldname>\n   <fieldname>language<\/fieldname>\n   <fieldname>scale_id<\/fieldname>\n   <fieldname>same_default<\/fieldname>\n   <fieldname>relevance<\/fieldname>\n  <\/fields>\n  <rows>\n   <row>\n    <qid><![CDATA[423]]><\/qid>\n    <parent_qid><![CDATA[0]]><\/parent_qid>\n    <sid><![CDATA[587113]]><\/sid>\n    <gid><![CDATA[1]]><\/gid>\n    <type><![CDATA[L]]><\/type>\n    <title><![CDATA[test]]><\/title>\n    <question><![CDATA[Which kind of %dog% do you like the best?]]><\/question>\n    <preg\/>\n    <help><![CDATA[SHIBA or AKITA is recommended.]]><\/help>\n    <other><![CDATA[N]]><\/other>\n    <mandatory><![CDATA[N]]><\/mandatory>\n    <question_order><![CDATA[4]]><\/question_order>\n    <language><![CDATA[en]]><\/language>\n    <scale_id><![CDATA[0]]><\/scale_id>\n    <same_default><![CDATA[0]]><\/same_default>\n    <relevance><![CDATA[1]]><\/relevance>\n   <\/row>\n   <row>\n    <qid><![CDATA[423]]><\/qid>\n    <parent_qid><![CDATA[0]]><\/parent_qid>\n    <sid><![CDATA[587113]]><\/sid>\n    <gid><![CDATA[1]]><\/gid>\n    <type><![CDATA[L]]><\/type>\n    <title><![CDATA[test]]><\/title>\n    <question\/>\n    <preg\/>\n    <help\/>\n    <other><![CDATA[N]]><\/other>\n    <mandatory><![CDATA[N]]><\/mandatory>\n    <question_order><![CDATA[4]]><\/question_order>\n    <language><![CDATA[vi]]><\/language>\n    <scale_id><![CDATA[0]]><\/scale_id>\n    <same_default><![CDATA[0]]><\/same_default>\n   <\/row>\n  <\/rows>\n <\/questions>\n <answers>\n  <fields>\n   <fieldname>qid<\/fieldname>\n   <fieldname>code<\/fieldname>\n   <fieldname>answer<\/fieldname>\n   <fieldname>sortorder<\/fieldname>\n   <fieldname>assessment_value<\/fieldname>\n   <fieldname>language<\/fieldname>\n   <fieldname>scale_id<\/fieldname>\n  <\/fields>\n  <rows>\n   <row>\n    <qid><![CDATA[423]]><\/qid>\n    <code><![CDATA[A1]]><\/code>\n    <answer><![CDATA[SHIBA]]><\/answer>\n    <sortorder><![CDATA[1]]><\/sortorder>\n    <assessment_value><![CDATA[0]]><\/assessment_value>\n    <language><![CDATA[en]]><\/language>\n    <scale_id><![CDATA[0]]><\/scale_id>\n   <\/row>\n   <row>\n    <qid><![CDATA[423]]><\/qid>\n    <code><![CDATA[A2]]><\/code>\n    <answer><![CDATA[AKITA]]><\/answer>\n    <sortorder><![CDATA[2]]><\/sortorder>\n    <assessment_value><![CDATA[1]]><\/assessment_value>\n    <language><![CDATA[en]]><\/language>\n    <scale_id><![CDATA[0]]><\/scale_id>\n   <\/row>\n   <row>\n    <qid><![CDATA[423]]><\/qid>\n    <code><![CDATA[A3]]><\/code>\n    <answer><![CDATA[BULLDOG]]><\/answer>\n    <sortorder><![CDATA[3]]><\/sortorder>\n    <assessment_value><![CDATA[1]]><\/assessment_value>\n    <language><![CDATA[en]]><\/language>\n    <scale_id><![CDATA[0]]><\/scale_id>\n   <\/row>\n   <row>\n    <qid><![CDATA[423]]><\/qid>\n    <code><![CDATA[A1]]><\/code>\n    <answer><![CDATA[Some example answer option]]><\/answer>\n    <sortorder><![CDATA[1]]><\/sortorder>\n    <assessment_value><![CDATA[0]]><\/assessment_value>\n    <language><![CDATA[vi]]><\/language>\n    <scale_id><![CDATA[0]]><\/scale_id>\n   <\/row>\n   <row>\n    <qid><![CDATA[423]]><\/qid>\n    <code><![CDATA[A2]]><\/code>\n    <answer><![CDATA[New answer option]]><\/answer>\n    <sortorder><![CDATA[2]]><\/sortorder>\n    <assessment_value><![CDATA[1]]><\/assessment_value>\n    <language><![CDATA[vi]]><\/language>\n    <scale_id><![CDATA[0]]><\/scale_id>\n   <\/row>\n   <row>\n    <qid><![CDATA[423]]><\/qid>\n    <code><![CDATA[A3]]><\/code>\n    <answer><![CDATA[New answer option]]><\/answer>\n    <sortorder><![CDATA[3]]><\/sortorder>\n    <assessment_value><![CDATA[1]]><\/assessment_value>\n    <language><![CDATA[vi]]><\/language>\n    <scale_id><![CDATA[0]]><\/scale_id>\n   <\/row>\n  <\/rows>\n <\/answers>\n<\/document>";*/

$data = str_replace('\"',"",$NewDataXML);
$data = str_replace("\/","/",$data);
$data = str_replace("\n", "", $data);

$sImportData = base64_encode(utf8_encode($data));
//echo $data;

// instanciate a new client

$myJSONRPCClient = new jsonRPCClient( LS_BASEURL.'/admin/remotecontrol' );

// receive session key

$sessionKey= $myJSONRPCClient->get_session_key( LS_USER, LS_PASSWORD );

// receive all ids and info of groups belonging to a given survey


$url = $_SERVER['REQUEST_URI'];
//$post="362167X6X114=2&362167X6X115=M";
$post= ".$url.";
 
	// convert string to an associative array
	parse_str($post, $output);
	 
	 
	//add response to the LS database
	$responseadded=$myJSONRPCClient->import_question($sessionKey, $survey_id, $iGroupID, $sImportData,lsq);
	//print_r($responseadded);
	// show the reponse code
	//echo"[";
	if (is_numeric ($responseadded)) 
	{
		$response["result"] = "SUCCESS";
		$response["code"] = 123;
        echo(json_encode($response));
		} else if(!is_numeric ($responseadded)){
		$response["result"] = "False";
		$response["code"] = 122;
        echo(json_encode($response));
	 }else{
		$response["result"] = "Error";
		$response["code"] = 124;
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
//echo"]";
$myJSONRPCClient->release_session_key( $sessionKey );

}else if($code == 1){

	$gettitle = $_GET['title'];
	$getsid = $_GET['survey_id'];
	//// Start
	$query = "Select * FROM e_questions WHERE title = '$gettitle' AND sid = '$getsid' LIMIT 1";
	$query_params = array(
		':' => ""
	);
	//execute query
	try {
		$stmt   = $db->prepare($query);
		$result = $stmt->execute($query_params);
	}
	catch (PDOException $ex) {
		$response["success"] = 0;
		$response["message"] = "Database Error!";
		echo (json_encode($response));
	}
	
	$rowsq = $stmt->fetchAll();
	if(!$rowsq){
		echo "0";
	}else{
		echo "1";
	}
}else if($multi == 1){
	$survey_id = $_GET['survey_id'];
	$iGroupID = $_GET['iGroupID'];
	$dataMulti = $_GET['datamulti'];
	
	$Edata = explode(",", $dataMulti);
	foreach($Edata as $data) {    
		//echo $data."<br/>";
		$rtData = file_get_contents('http://myphamteen.com/esurvey/webservice/questionbank.php?import=1&survey_id='.$survey_id.'&iGroupID='.$iGroupID.'&qid='.$data.'');
		//echo $rtData."<br/>";
		$ObjData= json_decode($rtData,true);
		//print_r($ObjData);
		foreach ($ObjData as $multi_data => $value) {
			$rtCode = $value;
			//echo $rtCode;
			if($rtCode == 123){
				echo $data.",";
			}
		}
		
		
	}

}
?>