<?php
echo "[";
//load and connect to MySQL database stuff
require("config.inc.php");
error_reporting(0);
header("Access-Control-Allow-Origin: *");
//echo $domain;
$GetType     = $_GET['type'];
$GetSurveys  = $_GET['SurveyId'];
$GetLanguage = $_GET['Language'];
$GetIdUser   = $_GET['user'];
//$GetPrivate = $_GET['Public'];
//$GetPrivate = $_COOKIE['uid'];

// Get User Create Survey

	$query = "Select sid FROM e_surveys WHERE owner_id = (Select uid FROM e_users WHERE users_name = '$GetIdUser') AND active = 'Y' AND sid = '$GetSurveys'";
                        
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
	
	$rows = $stmt->fetchAll();
		
		//print_r($rows);
	
	if ($rows) {

		foreach ($rows as $row) {

			$SIds = $row['sid'];
			
		}
	}
	//echo $SIds;
// Get User Create Survey end
// Get SID in permissions

	$query = "Select entity_id FROM e_permissions WHERE uid = (Select uid FROM e_users WHERE users_name = '$GetIdUser') AND permission = 'responses' AND entity_id = '$GetSurveys'";
                        
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
	
	$rows = $stmt->fetchAll();
		
		//print_r($rows);
	
	if ($rows) {

		foreach ($rows as $row) {

			$SIdPermission = $row['entity_id'];
			
		}
	}
	//echo $SIdPermission;
// Get SID in permissions end


//echo $GetPrivate;
$query       = "Select uid FROM e_survey_user_online WHERE uid = (Select uid FROM e_users WHERE users_name = '$GetIdUser')";

$query_params = array(
    ':' => ""
);

try {
    $stmt   = $db->prepare($query);
    $result = $stmt->execute($query_params);
}
catch (PDOException $ex) {
    // For testing, you could use a echo and message. 
    //echo("Failed to run query: " . $ex->getMessage());
    
    //or just use this use this one to product JSON data:
    $response["code"]    = 100;
    $response["message"] = "Database Error1. Please Try Again!";
    echo (json_encode($response));
    
}

//This will be the variable to determine whether or not the user's information is correct.
//we initialize it as false.
$validated_info = false;

//fetching all the rows from the query
$row = $stmt->fetch();
if ($row) {
    //if we encrypted the password, we would unencrypt it here, but in our case we just
    //compare the two passwords
    $IdUserLogin = $row['uid'];
    //print_r($row['listpublic']);
    // echo $IdUserLogin;
    //echo 17777;
    
}
switch ($GetType) {
    
    case details:
        $query = "Select * FROM e_surveys WHERE sid = '$GetSurveys'";
        
        $query_params = array(
            ':' => ""
        );
        
        try {
            $stmt   = $db->prepare($query);
            $result = $stmt->execute($query_params);
        }
        catch (PDOException $ex) {
            // For testing, you could use a echo and message. 
            //echo("Failed to run query: " . $ex->getMessage());
            
            //or just use this use this one to product JSON data:
            $response["success"] = 0;
            $response["message"] = "Database Error1. Please Try Again!";
            echo (json_encode($response));
            
        }
        
        //This will be the variable to determine whether or not the user's information is correct.
        //we initialize it as false.
        $validated_info = false;
        
        //fetching all the rows from the query
        $row = $stmt->fetch();
        if ($row) {
            //if we encrypted the password, we would unencrypt it here, but in our case we just
            //compare the two passwords
            $PublicY = $row['listpublic'];
            //print_r($row['listpublic']);
            // echo $PublicY;
            //echo 1;
            
        }
        if ((($PublicY == "Y" || !empty($IdUserLogin)) && !empty($SIds)) || !empty($SIdPermission)) {
            
            // start get database by SurveyId
            
            //initial query
            $query = "Select * FROM e_questions WHERE sid = '$GetSurveys' AND language = '$GetLanguage' AND parent_qid = '0' ORDER BY gid ASC, qid ASC";
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
            
            // Finally, we can retrieve all of the found rows into an array using fetchAll 
            $rows = $stmt->fetchAll();
            
            
            if ($rows) {
                $response["result"]  = "SUCCESS";
                $response["message"] = "Get Syrveys";
                $response["posts"]   = array();
                //print_r($rows);
                foreach ($rows as $row) {
                    $post             = array();
                    $qid              = $row["qid"];
                    $post["qid"]      = $row["qid"];
                    $post["gid"]      = $row["gid"];
                    $post["type"]     = $row["type"];
                    $typeOption       = $row["type"];
                    $questionStr 	  = $row["question"];
					$questionStr 	  = str_replace('"','&quot;',$questionStr);
					$questionStr 	  = str_replace("'","&#39;",$questionStr);
                    $post["question"] = $questionStr;
                    $post["mandatory"] = $row["mandatory"];
                    
                    /// get subquestion 
                    
                    if ($typeOption == ":" || $typeOption == ";") { // get type ":" & ";" subquestion
                        
                        // :;
                        
                        $query = "Select * FROM e_questions WHERE parent_qid = '$qid' AND language = '$GetLanguage' AND scale_id = '0' ORDER BY title ASC";
                        
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
                        
                        
                        if ($rowsq) {
                            
                            $post["subquestion"] = array();
                            //print_r($rows);
                            foreach ($rowsq as $rowq) {
                                $postq             = array();
                                $qidS              = $rowq["qid"];
                                //$postq["qid"]  = $rowq["qid"];
                                $postq["type"]     = $rowq["type"];
                                $postq["scale_id"] = $rowq["scale_id"];
                                $postq["code"]     = $rowq["title"];
                                $postq["question"] = $rowq["question"];
                                $postq["mandatory"] = $rowq["mandatory"];
                                
                                
                                
                                
                                array_push($post["subquestion"], $postq);
                                //array_push($response["listqid"],$postq);
                                
                            }
                            
                            //echo json_encode($response);
                            
                            
                        }
                        
                        // 0:;
                        // 1:;
                        
                        $query = "Select * FROM e_questions WHERE parent_qid = '$qid' AND language = '$GetLanguage' AND scale_id = '1' ORDER BY title ASC";
                        
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
                        
                        
                        if ($rowsq) {
                            
                            $post["subquestion1"] = array();
                            //print_r($rows);
                            foreach ($rowsq as $rowq) {
                                $postq             = array();
                                $qidS              = $rowq["qid"];
                                //$postq["qid"]  = $rowq["qid"];
                                $postq["type"]     = $rowq["type"];
                                $postq["scale_id"] = $rowq["scale_id"];
                                $postq["code"]     = $rowq["title"];
                                $postq["question"] = $rowq["question"];
                                $postq["mandatory"] = $rowq["mandatory"];
                                
                                
                                
                                
                                array_push($post["subquestion1"], $postq);
                                //array_push($response["listqid"],$postq);
                                
                            }
                            
                            //echo json_encode($response);
                            
                            
                        }
                        
                        // :;
                        
                    } else if($typeOption == "|"){ // get upload
						// $post["upload"] = array();
						// $getupload = "".$domain."webservice/upload.php?qid=".$qid."&lang=".$GetLanguage."&upload=true";
						
						// $OutcodeUpload = htmlentities(file_get_contents($getupload));
						// $OutcodeUpload = preg_replace("/\\n/m", "", $OutcodeUpload);
						// //$response["upload"]   = $OutcodeUpload;
						 // array_push($post["upload"], $OutcodeUpload);
						 
						 $query = "Select value,attribute FROM e_question_attributes WHERE qid = '$qid'";
						//echo $query;
											
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
						
						$GetUpload = $stmt->fetchAll();
						
						if ($GetUpload){
							//print_r($GetUpload);
							$post["upload"] = array();
							 foreach ($GetUpload as $InfoUpload) {
								$attribute[$InfoUpload["attribute"]]=$InfoUpload["value"];
								
							}array_push($post["upload"], $attribute);
							unset($attribute);
						}else{
							$post["upload"] = array();
							$attribute2['max_filesize'] = 10240;
							$attribute2['allowed_filetypes']= "png, gif, doc, odt";
							
							array_push($post["upload"], $attribute2);
						}
						
					} else if ($typeOption == "1") { // get type "1" & ";" answer
                        
                        // :;
                        
                        $query = "Select * FROM e_answers WHERE qid = '$qid' AND language = '$GetLanguage' AND scale_id = '0'";
                        
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
                        
						
                        
                        if ($rowsq) {
							
							/// get subquestion
							
							$query = "Select * FROM e_questions WHERE parent_qid = '$qid' AND language = '$GetLanguage' ORDER BY title ASC";
							
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
							
							
							if ($rowsq) {
								
								$post["subquestion"] = array();
								//print_r($rows);
								foreach ($rowsq as $rowq) {
									$postq             = array();
									$qidS              = $rowq["qid"];
									//$postq["qid"]  = $rowq["qid"];
									$postq["type"]     = $rowq["type"];
									$postq["code"]     = $rowq["title"];
									$postq["question"] = $rowq["question"];
									$postq["mandatory"] = $rowq["mandatory"];
									
									
									
									
									array_push($post["subquestion"], $postq);
									//array_push($response["listqid"],$postq);
									
								}
								
								//echo json_encode($response);
								
								
							}
							/// end get subquestion
                            
                            $query = "Select * FROM e_answers WHERE qid = '$qid' AND language = '$GetLanguage' AND scale_id = '0'";

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
								echo(json_encode($response));
							}

							$rowsq = $stmt->fetchAll();


							if ($rowsq) {

								$post["answer0"]   = array();
								//print_r($rows);
								foreach ($rowsq as $rowq) {
									$postq             = array();
									$qidS  = $rowq["qid"];
									//$postq["qid"]  = $rowq["qid"];
									//$postq["type"]  = $rowq["type"];
									$postq["scale_id"]  = $rowq["scale_id"];
									$postq["code"]  = $rowq["code"];
									$postq["answer"]  = $rowq["answer"];
									
										
									
									
									array_push($post["answer0"],$postq);
									//array_push($response["listqid"],$postq);
									
								}
							}
                            
                            
                        }
                        
                        // 0:;
                        // 1:;
                        
                        $query = "Select * FROM e_answers WHERE qid = '$qid' AND language = '$GetLanguage' AND scale_id = '1'";
                        
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
                        
                        
                        if ($rowsq) {
						
							/// get subquestion
							
							$query = "Select * FROM e_questions WHERE parent_qid = '$qid' AND language = '$GetLanguage' ORDER BY title ASC";
							
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
							
							
							if ($rowsq) {
								
								$post["subquestion"] = array();
								//print_r($rows);
								foreach ($rowsq as $rowq) {
									$postq             = array();
									$qidS              = $rowq["qid"];
									//$postq["qid"]  = $rowq["qid"];
									$postq["type"]     = $rowq["type"];
									$postq["code"]     = $rowq["title"];
									$postq["question"] = $rowq["question"];
									$postq["mandatory"] = $rowq["mandatory"];
									
									
									
									
									array_push($post["subquestion"], $postq);
									//array_push($response["listqid"],$postq);
									
								}
								
								//echo json_encode($response);
								
								
							}
							/// end get subquestion
                            
                            $query = "Select * FROM e_answers WHERE qid = '$qid' AND language = '$GetLanguage' AND scale_id = '1'";

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
								echo(json_encode($response));
							}

							$rowsq = $stmt->fetchAll();


							if ($rowsq) 
							{
							

								$post["answer1"]   = array();
								//print_r($rows);
								foreach ($rowsq as $rowq) {
									$postq             = array();
									$qidS  = $rowq["qid"];
									//$postq["qid"]  = $rowq["qid"];
									//$postq["type"]  = $rowq["type"];
									$postq["scale_id"]  = $rowq["scale_id"];
									$postq["code"]  = $rowq["code"];
									$postq["answer"]  = $rowq["answer"];

									array_push($post["answer1"],$postq);
									//array_push($response["listqid"],$postq);
									
								}

								//echo json_encode($response);
								
								
							}
                            
                           
                            
                            
                        }
                        
                        // :;
                        
                    } else if($typeOption == "U" || $typeOption == "O" || $typeOption =="T") { // get maximum_chars
                        
						$query = "Select value,attribute FROM e_question_attributes WHERE qid = '$qid' AND attribute = 'maximum_chars'";
						
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
                        
                        $Maxchars = $stmt->fetchAll();
						
						//$post["maxchars"] = array();
						if ($Maxchars){
							//print_r($Maxchars);
							 foreach ($Maxchars as $chars) {
								$post["maxchars"] = $chars["value"];
							}
						}
					} else if($typeOption == "U" || $typeOption == "O" || $typeOption =="T") { // get em_validation_q_tip
                        
						$query = "Select value,attribute FROM e_question_attributes WHERE qid = '$qid' AND attribute = 'em_validation_q_tip'";
						
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
                        
                        $validation = $stmt->fetchAll();
						
						//$post["maxchars"] = array();
						if ($validation){
							//print_r($Maxchars);
							 foreach ($validation as $val) {
								$post["em_validation_q_tip"] = $val["value"];
							}
						}
					}
					
					 else {
                        
                        // all:;
                        
                        $query = "Select * FROM e_questions WHERE parent_qid = '$qid' AND language = '$GetLanguage' ORDER BY title ASC";
                        
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
                        
                        
                        if ($rowsq) {
                            
                            $post["subquestion"] = array();
                            //print_r($rows);
                            foreach ($rowsq as $rowq) {
                                $postq             = array();
                                $qidS              = $rowq["qid"];
                                //$postq["qid"]  = $rowq["qid"];
                                $postq["type"]     = $rowq["type"];
                                $postq["code"]     = $rowq["title"];
                                $postq["question"] = $rowq["question"];
                                $postq["mandatory"] = $rowq["mandatory"];
                                
                                
                                
                                
                                array_push($post["subquestion"], $postq);
                                //array_push($response["listqid"],$postq);
                                
                            }
                            
                            //echo json_encode($response);
                            
                            
                        }
                        
                        // all:;
                        
                    }
                    
                    /// end get subquestion
                    /////////////////////////////////////////////////////////////
                    
                    /// get answer 
                    
                     
                    
                    /// end get subquestion
                    
                    /////////////////////////////////////////////////////////////
                    //$post["answer"]  = array();
                    // get question
                    $post["help"] = $row["help"];
                    $query        = "Select code,answer,sortorder FROM e_answers WHERE qid = '$qid' AND language = '$GetLanguage'";
                    
                    
                    $query_params = array(
                        ':' => ""
                    );
                    //execute query
                    try {
                        $stmt = $db->prepare($query);
                        $stmt->execute($query_params);
                    }
                    catch (PDOException $ex) {
                        $response["success"] = 0;
                        $response["message"] = "Database Error!";
                        echo (json_encode($response));
                    }
                    
                    // Finally, we can retrieve all of the found rows into an array using fetchAll 
                    $listAnswers    = $stmt->fetchAll();
                    //$response["answer"]  = array();
                    $post["answer"] = array();
                    foreach ($listAnswers as $answer) {
                        //print_r($post["answer"]);
                        array_push($post["answer"], $answer);
                        
                        
                        
                    }
                    
                    // end get name
                    
                    array_push($response["posts"], $post);
					
					
					
					
                    
                }
				//////////////////////////////////////////////
				///////////// Get JS CONDITIONS //////////////
					
					//$surveyid = $_GET['surveyid'];
					$link = "".$domain."index.php/".$GetSurveys."/lang-".$GetLanguage."";
					$getjs = "".$domain."webservice/getjs.php?surveyid=".$GetSurveys."&lang=".$GetLanguage."";
					$gettoken = "".$domain."webservice/token.php?surveyid=".$GetSurveys."&lang=".$GetLanguage."";
					$homepage = file_get_contents($link);
					$homepage2 = file_get_contents($link);
					

					$a = "<input";
					$b = '';
					$c = 'name=';
					$d = 'id';
					$e = '/>';
					$f = ',';
					$g = 'value=';
					$h = ':';
					$p = '"';
					$q = "";
					$homepage2 = str_replace($a, $b, $homepage2);
					$homepage2 = str_replace($c, $d, $homepage2);
					$homepage2 = str_replace($e, $f, $homepage2);
					$homepage2 = str_replace($g, $h, $homepage2);
					//$homepage2 = str_replace($p, $q, $homepage2);
					//$homepage2 = preg_replace("/\\n/m", "", $homepage2);
					$var = htmlentities($homepage2);
					$relevancebegin = strpos($var, 'EndAppMobile') + strlen('EndRelevane');
					$relevanceend   = strpos($var, 'EndRelevane');
					$jstext = substr($var, $relevancebegin, ($relevanceend - $relevancebegin));
					$jstext = substr($jstext , 7, -11);
					$jstext = preg_replace('/(.*)id/','',$jstext);
					$jstext2 = preg_replace("/\\n/m", "", $jstext);
					
					//$jstext2 = str_replace($p,$q, $jstext);
					$jstext = array();
					$jstext["dJYJFKLUJKTUIBHKUdnfhGB7"] = $jstext2;
					///
						$OutcodeJs = htmlentities(file_get_contents($getjs));
						$OutcodeJs = preg_replace("/\\n/m","",$OutcodeJs);

					///
					$response["jscode"]   = $OutcodeJs;
					if(strlen($jstext2) > 5){
						
						$response["jsinput"] = $jstext;
						
					}else{
					
						$response["jsinput"] = "";
					
					}
					
					//$Outtoken = htmlentities(file_get_contents($gettoken));
					//$response["token"]   = $Outtoken;

				//////////////////////////////////////////////
				
				
                $response["code"] = 118;
                //update our repsonse JSON data
                //array_push($response["posts"], $post);
                // echoing JSON response
                //$check = json_encode($response);
				echo str_replace("'",'"',str_replace('""relevance','"relevance',str_replace("'relevance",'"relevance',str_replace('"dJYJFKLUJKTUIBHKUdnfhGB7":','',json_encode($response)))));
				//echo str_replace('"dJYJFKLUJKTUIBHKUdnfhGB7":','',json_encode($response));
                
                
            } else {
                $response["success"] = 0;
                $response["message"] = "No Post Available!";
                echo (json_encode($response));
            }
            
        } else {
            
            $response["result"]  = "SUCCESS";
            $response["message"] = "Private Surveys";
            $response["code"]    = 116;
            echo (json_encode($response));
            
            
        }
        // end 
        break;
    
    default:
        if (!empty($IdUserLogin)) {
            
            //initial query
			//$query        = "Select * FROM e_surveys WHERE active = 'Y' AND owner_id = (Select uid FROM e_users WHERE users_name = '$GetIdUser')";
            $query        = "Select DISTINCT e_surveys.* FROM e_surveys,e_permissions WHERE e_surveys.sid = e_permissions.entity_id AND e_surveys.active = 'Y' AND e_permissions.permission = 'responses' AND e_permissions.read_p = 1 AND e_permissions.uid = (Select uid FROM e_users WHERE users_name = '$GetIdUser') OR e_surveys.owner_id = (Select uid FROM e_users WHERE users_name = '$GetIdUser') AND e_surveys.active = 'Y'";
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
            
            // Finally, we can retrieve all of the found rows into an array using fetchAll 
            $rows = $stmt->fetchAll();
            
            
            if ($rows) {
                $response["result"]  = "SUCCESS";
                $response["message"] = "Show All Surveys";
                $response["posts"]   = array();
                
                foreach ($rows as $row) {
                    $post         = array();
                    $post["sid"]  = $row["sid"];
                    $sid          = $row["sid"];
                    $infolanguage          = $row["language"];
                    // get name surveys
                    //initial query
                    $query        = "Select surveyls_title FROM e_surveys_languagesettings WHERE surveyls_survey_id = $sid AND surveyls_language = '$infolanguage'";
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
                    $row2 = $stmt->fetch();
                    if ($row2)
                        $post["surveyls_title"] = $row2['surveyls_title'];
                    
                    // end get name
                    //$post["active"] = $row["active"];
                    $post["language"]     = $row["language"];
                    //$post["languagelist"]    = $row["additional_languages"];
                    $post["languagelist"] = str_replace(" ", ",", $row["additional_languages"]);
                    //$post["listpublic"]    = $row["listpublic"];
                    
                    
                    //update our repsonse JSON data
                    array_push($response["posts"], $post);
                }
                $response["code"] = 119;
                // echoing JSON response
                echo json_encode($response);
                
                
            } else {
                $response["success"] = 0;
                $response["message"] = "No Surveys Available!";
                echo (json_encode($response));
            }
            
        } else {
            
            
            //initial query
            $query        = "Select * FROM e_surveys WHERE active = 'Y' AND listpublic = 'Y'";
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
            
            // Finally, we can retrieve all of the found rows into an array using fetchAll 
            $rows = $stmt->fetchAll();
            
            
            if ($rows) {
                $response["result"]  = "SUCCESS";
                $response["message"] = "Show All Surveys";
                $response["posts"]   = array();
                
                foreach ($rows as $row) {
                    $post         = array();
                    $post["sid"]  = $row["sid"];
                    $sid          = $row["sid"];
                    // get name surveys
                    //initial query
                    $query        = "Select surveyls_title FROM e_surveys_languagesettings WHERE surveyls_survey_id = $sid";
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
                    $row2 = $stmt->fetch();
                    if ($row2)
                        $post["surveyls_title"] = $row2['surveyls_title'];
                    
                    // end get name
                    //$post["active"] = $row["active"];
                    $post["language"]     = $row["language"];
                    //$post["languagelist"]    = $row["additional_languages"];
                    $post["languagelist"] = str_replace(" ", ",", $row["additional_languages"]);
                    //$post["listpublic"]    = $row["listpublic"];
                    
                    
                    //update our repsonse JSON data
                    array_push($response["posts"], $post);
                }
                $response["code"] = 119;
                // echoing JSON response
                echo json_encode($response);
                
                
            } else {
                $response["success"] = 0;
                $response["message"] = "No Surveys Available!";
                echo (json_encode($response));
            }
        }
}
echo "]";
?>
