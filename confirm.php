<?php
	error_reporting(0);
	if (empty($_COOKIE['email'])){
	echo '<meta http-equiv=refresh content="0; URL=http://'.$_SERVER['SERVER_NAME'].'/esurvey/register.php">';
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Registration Form</title>
<style type="text/css">
body {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 13px;
}
.registration_form {
	margin: 0 auto;
	width: 80%;
	padding: 14px;
}
label {
	width: 10em;
	float: left;
	margin-right: 0.5em;
}
.submit {
 float:;
}
fieldset {
	background: #EBF4FB none repeat scroll 0 0;
	border: 2px solid #B7DDF2;
}
legend {
	color: #787878;
	font-size: 14px;
	font-weight: bold;
}
.elements {
	padding: 10px;
}
p {
	border-bottom: 1px solid #B7DDF2;
	color: #666666;
	font-size: 11px;
	margin-bottom: 20px;
	padding-bottom: 10px;
}
a {
	color: #0099FF;
	font-weight: bold;
}
/* Box Style */


/*.success, .warning, .errormsgbox, .validation {
	border: 1px solid;
	margin: 0 auto;
	padding: 10px 5px 10px 50px;
	background-repeat: no-repeat;
	background-position: 10px center;
	font-weight: bold;
}
.success {
	color: #4F8A10;
	background-color: #DFF2BF;
	background-image: url('images/success.png');
}
.warning {
	color: #9F6000;
	background-color: #FEEFB3;
	background-image: url('images/warning.png');
}
.errormsgbox {
	color: #D8000C;
	background-color: #FFBABA;
	background-image: url('images/error.png');
}
.validation {
	color: #D63301;
	background-color: #FFCCBA;
	background-image: url('images/error.png');
}*/
.language {
	width: 180px;
}
.country {
	width: 37%;
}
label.valid {
	width: 24px;
	background: url(assets/img/valid.png) center center no-repeat;
	display: inline-block;
	text-indent: -9999px;
	float: none;
}
label.error {
	font-weight: bold;
	color: red;
	padding: 2px 8px;
	margin-top: 2px;
	float: none;
}
</style>
</head>
<body>
<div style="width:80%; margin:0 auto;"><a style="font-size:40px; color:#060; font-weight:bold;">eSurvey</a></div>
<div style="clear:both;"></div>
<br />
<div style="width:80%; margin:0 auto;"><a style="font-size:12px; color:#787; font-weight:bold;">Register Input</a> > <a style="font-size:12px; color:#787;">Confirm Email</a></div>
<div style="clear:both;"></div>
<div style="width:80%; margin:20px auto;" class="success">Thank you for
registering! <br /> A confirmation email has been sent to  <?php echo $_COOKIE['email']; ?>  Please click on the Activation Link to Activate your account. Thank you</div>
</body>
</html>
