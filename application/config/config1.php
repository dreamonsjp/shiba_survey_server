<?php

/*Define constant to connect to database */
DEFINE('DATABASE_USER', 'muacom_esurvey');
DEFINE('DATABASE_PASSWORD', 'v[8F0K^]gMa@');
DEFINE('DATABASE_HOST', 'localhost');
DEFINE('DATABASE_NAME', 'muacom_esurvey');
/*Default time zone ,to be able to send mail */
date_default_timezone_set('UTC');

/*You might not need this */
ini_set('SMTP', "mail.myt.mu"); // Overide The Default Php.ini settings for sending mail


//This is the address that will appear coming from ( Sender )
define('EMAIL', 'sangltdn@gmail.com');

/*Define the root url where the script will be found such as http://website.com or http://website.com/Folder/ */
DEFINE('WEBSITE_URL', 'http://myphamteen.com/esurvey/');


// Make the connection:
$dbc = @mysqli_connect(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD,
    DATABASE_NAME);

if (!$dbc) {
    trigger_error('Could not connect to MySQL: ' . mysqli_connect_error());
}

?>