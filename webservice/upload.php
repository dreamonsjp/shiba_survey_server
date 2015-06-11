<?php
require("config.inc.php");
if($_FILES['file']['name'] != NULL){ 
   $path = "tmp/";
		  $tmp_name = $_FILES['file']['tmp_name'];
		  $name = $_FILES['file']['name'];
		  $date = time();
		  $type = $_FILES['file']['type']; 
		  $size = $_FILES['file']['size']; 
		  move_uploaded_file($tmp_name,$path.$date.$name);
		  $namefile = $date.$name;
		  //echo  $namefile;
}else{
   echo "file error";
}
?>
<?php
// uploadapp | 1 Upload with app token empty | != 1 Upload with browser support token and cookie
$surveyid = $_POST['surveyid'];
$language = $_POST['language'];
$fieldname = $_POST['fieldname'];
$urlremote = $domain."index.php/uploader/index/mode/upload?sid=".$surveyid."&language=".$language."&uploadapp=1/sid/".$surveyid."/preview/0/fieldname/".$fieldname."/";
$ch = curl_init();
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_VERBOSE, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: multipart/form-data"));
curl_setopt($ch, CURLOPT_USERAGENT, false);
curl_setopt($ch, CURLOPT_URL, $urlremote);
curl_setopt($ch, CURLOPT_POST, true);
$filename = $namefile;
$post = array(
    'uploadfile' => '@'.dirname(__FILE__).'/tmp/'.$filename.';type='.$type.'',
	'surveyid' => ''.$surveyid.'' ,
	'fieldname'=>''.$fieldname.''
);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
$response = curl_exec($ch);
	echo $response;
// delete file tmp
if($namefile != ''){
	$delete_tmp    = dirname(__FILE__).'/tmp/'.$namefile;
	unlink($delete_tmp);
}

?>
