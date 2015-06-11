<?php

/*
* VitekSoft
*/

include ('application/config/config_shiba.php');
error_reporting(0);
if (isset($_POST['formsubmitted'])) {
	//$Password = $_POST['Password'];
	$language = $_POST['language'];
	$fullname = $_POST['fullname'];
	$Password_again = $_POST['Password_again'];
	$category = $_POST['category'];
	$company = $_POST['company'];
	$telephone = $_POST['telephone'];
	$address = $_POST['address'];
	$city = $_POST['city'];
	$country = $_POST['country'];
	$created = date('Y-m-d H:i:s');
    $error = array();//Declare An Array to store any error message  
    if (empty($_POST['name'])) {//if no name has been supplied 
        $error[] = 'Please Enter a name ';//add to array "error"
    } else {
        $name = $_POST['name'];//else assign it a variable
    }

    if (empty($_POST['email'])) {
        $error[] = 'Please Enter your Email ';
    } else {


        if (preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $_POST['email'])) {
           //regular expression for email validation
            $Email = $_POST['email'];
        } else {
             $error[] = 'Your EMail Address is invalid  ';
        }


    }


    if (empty($_POST['Password'])) {
        $error[] = 'Please Enter Your Password ';
    } else {
        $Password = $_POST['Password'];
		$sPasswordHash=hash('sha256', $Password);
    }


    if (empty($error)) //send to Database if there's no error '

    { // If everything's OK...

        // Make sure the email address is available:
        $query_verify_email = "SELECT * FROM e_users  WHERE email ='$Email'";
        $result_verify_email = mysqli_query($dbc, $query_verify_email);
        if (!$result_verify_email) {//if the Query Failed ,similar to if($result_verify_email==false)
            echo ' Database Error Occured ';
        }

        if (mysqli_num_rows($result_verify_email) == 0) { // IF no previous user is using this email .


            // Create a unique  activation code:
            $activation = md5(uniqid(rand(), true));


            $query_insert_user = "INSERT INTO `e_users` 
			(
			 `users_name`,
			 `email`,
			 `password`,
			 `full_name`,
			 `lang`,
			 `category`,
			 `company`,
			 `telephone`,
			 `address`,
			 `city`,
			 `country`,
			 `created`,
			 `activation`
			 			   )
			 VALUES 
			 ( 
			 '$name',
			 '$Email',
			 '$sPasswordHash',
			 '$fullname',
			 '$language',
			 '$category',
			 '$company',
			 '$telephone',
			 '$address',
			 '$city',
			 '$country',
			 '$created',
			 '$activation'
			 )";


            $result_insert_user = mysqli_query($dbc, $query_insert_user);
            if (!$result_insert_user) {
                echo 'Query Failed ';
				echo $query_insert_user;
            }

            if (mysqli_affected_rows($dbc) == 1) { //If the Insert Query was successfull.


                // Send the email:
                $message = " To activate your account, please click on this link:\n\n";
                $message .= WEBSITE_URL . '/activate.php?email=' . urlencode($Email) . "&key=$activation";
                mail($Email, 'Registration Confirmation', $message, 'From: shibasurvey@gmail.com');

                // Flush the buffered output.
				setcookie("email",$Email,time() + 3600);

                // Finish the page:
                echo '<meta http-equiv=refresh content="0; URL=http://myphamteen.com/esurvey/confirm.php">';


            } else { // If it did not run OK.
                echo '<div class="errormsgbox">You could not be registered due to a system
error. We apologize for any
inconvenience.</div>';
            }

        } else { // The email address is not available.
            echo '<div class="errormsgbox" >That email
address has already been registered.
</div>';
        }

    } else {//If the "error" array contains error msg , display them
        
        

echo '<div class="errormsgbox"> <ol>';
        foreach ($error as $key => $values) {
            
            echo '	<li>'.$values.'</li>';


       
        }
        echo '</ol></div>';

    }
  
    mysqli_close($dbc);//Close the DB Connection

} // End of the main Submit conditional.



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
<!-- ==============================================
		 JavaScript below! 															--> 

<!-- jQuery via Google + local fallback, see h5bp.com --> 
<script src="assets/js/jquery-1.7.1.min.js"></script> 

<!-- Bootstrap JS --> 
<script src="assets/js/bootstrap.min.js"></script> 

<!-- Validate plugin --> 
<script src="assets/js/jquery.validate.min.js"></script> 

<!-- Prettify plugin --> 
<script src="assets/js/prettify/prettify.js"></script> 

<!-- Scripts specific to this page --> 
<script src="script.js"></script> 
<script>
			// Activate Google Prettify in this page
				addEventListener('load', prettyPrint, false);

			$(document).ready(function(){

				// Add prettyprint class to pre elements
					$('pre').addClass('prettyprint linenums');

			});

		</script>
<div style="width:80%; margin:0 auto;"><a style="font-size:40px; color:#060; font-weight:bold;">eSurvey</a></div>
<div style="clear:both;"></div>
<br />
<div style="width:80%; margin:0 auto;"><a style="font-size:12px; color:#787; font-weight:bold;">Register Input</a></div>
<form action="register.php" method="post" class="registration_form" id="contact-form">
  <fieldset>
    <legend>Account </legend>
    <p>Create A new Account <span style="background:#EAEAEA none repeat scroll 0 0;line-height:1;margin-left:65%;padding:5px 7px;">Already a member? <a href="/esurvey/admin">Log in</a></span> </p>
    <div class="elements">
      <label for="company" >Main Language </label>
      <select name="language" class="language" id="language">
        <option value="0">Select</option>
        <option value="2">English</option>
        <option value="3">Vietnamese</option>
      </select>
    </div>
    <div class="elements">
      <label for="company">Account Name</label>
      <input type="text" id="name" name="name" size="55" />
    </div>
    <div class="elements">
      <label for="company">Full Name</label>
      <input type="text" id="fullname" name="fullname" size="55" />
    </div>
    <div class="elements">
      <label for="telephone">Email</label>
      <input type="text" id="email" name="email" size="40" />
    </div>
    <div class="elements">
      <label for="address">Password</label>
      <input type="password" id="Password" name="Password" size="25" />
    </div>
    <div class="elements">
      <label for="address">Password again</label>
      <input type="password" id="Password_again" name="Password_again" size="25" />
    </div>
  </fieldset>
  <div style="clear:both;"></div>
  <br />
  <fieldset>
    <legend>Company </legend>
    <div class="elements">
      <label for="company" >Category</label>
      <select name="category" class="language" id="category">
        <option value="0">Select</option>
        <option value="1">Category 01</option>
        <option value="2">Category 02</option>
      </select>
    </div>
    <div class="elements">
      <label for="company">Company Name</label>
      <input type="text" id="company" name="company" size="55" />
    </div>
    <div class="elements">
      <label for="telephone">Tel</label>
      <input type="text" id="telephone" name="telephone" size="40" />
    </div>
    <div class="elements">
      <label for="address">Address</label>
      <input type="text" id="address" name="address" size="55" />
    </div>
    <div class="elements">
      <label for="address">City, District, States</label>
      <input type="text" id="city" name="city" size="55" />
    </div>
    <div class="elements">
      <label for="address">Country</label>
      <select name="country" size="5" multiple="MULTIPLE" class="country">
        <option value="0">USA</option>
        <option value="1" selected="selected">Vietnam</option>
        <option value="2">Japan</option>
      </select>
    </div>
    <div class="elements">
      <label for="address">Agreement</label>
    </div>
    <div class="elements">
      <textarea name="textarea" cols="55" rows="5" disabled="disabled" id="textarea" style="width:99%;">This document is visited every interation and changes are made. This is one of the visible indicators that should be in the area where the teams are working.

This document is visited every interation and changes are made. This is one of the visible indicators that should be in the area where the teams are working.

Here is an actual example of one such document from a highly productive scrum tea
This document is visited every interation and changes are made. This is one of the visible indicators that should be in the area where the teams are working.

Here is an actual example of one such document from a highly productive scrum teaHere is an actual example of one such document from a highly productive scrum team</textarea>
    </div>
    <div class="elements" style="text-align:center; display:block;">
     <label style="float:none;"><input type="checkbox" checked="checked" id="agreea" name="agreea">Agree</label>
       </div>
       
  </fieldset>
  <div style="clear:both;"></div>
  <div class="submit" style="text-align:center; display:block;">
    <input type="hidden" name="formsubmitted" value="TRUE" />
    <input type="submit" value="Next (Confirm Email)" />
  </div>
</form>
</body>
</html>
