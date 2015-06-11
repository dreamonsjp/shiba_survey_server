<?
require("config.inc.php");
error_reporting(0);
$surveyid = $_GET['surveyid'];
$lang = $_GET['lang'];
$link = "".$domain."index.php/".$surveyid."/lang-".$lang."";

function DOMinnerHTML(DOMNode $element) 
{ 
    $innerHTML = ""; 
    $children  = $element->childNodes;

    foreach ($children as $child) 
    { 
        $innerHTML .= $element->ownerDocument->saveHTML($child);
    }

    return $innerHTML; 
} 
$dom= new DOMDocument(); 
$dom->preserveWhiteSpace = false;
$dom->formatOutput       = true;
$dom->loadHTMLFile($link); 

$domJS = $dom->getElementsByTagName("script"); 

foreach ($domJS as $jscode) 
{ 	
	
    $outcode = DOMinnerHTML($jscode); 
	$outcode = str_replace("'",'&#39;',$outcode);
	$outcode = str_replace('"','&quot;',$outcode);
    $outcode = str_replace("/*<![CDATA[*/","",$outcode); 
    $outcode = str_replace("/*]]>*/","",$outcode); 
    $outcode = str_replace("<!--","",$outcode); 
    $outcode = str_replace("//","",$outcode); 
    $outcode = str_replace("-->","",$outcode);
    $outcode = str_replace("}
showpopu","}; showpopup",$outcode);

	$outcode =  call_user_func_array('mb_convert_encoding', array(&$outcode,'HTML-ENTITIES','UTF-8'));
	//$outcode =  mb_convert_encode($outcode,'HTML-ENTITIES','UTF-8'); 
	//$outcode =   iconv('UTF-8', 'macintosh', $string); 
	//$outcode = json_encode($outcode);
	// $outcode = str_replace('"','',$outcode);
	// $outcode = str_replace('\n','',$outcode);
	echo $outcode;
	
} 
 
?>