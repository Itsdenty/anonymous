<?php


//main variables
  define("SITE_URL", 'http://'.$_SERVER['HTTP_HOST']. '/app/Bal - Email Editor/');
  define("SITE_DIRECTORY",$_SERVER['DOCUMENT_ROOT'] .'/app/Bal - Email Editor/');


 //elements.json file directory
 define("ELEMENTS_DIRECTORY",SITE_DIRECTORY.'elements.json');

 //uploads directory,url
define("UPLOADS_DIRECTORY",SITE_DIRECTORY.'uploads/');
define("UPLOADS_URL",SITE_URL.'uploads/');

//EXPORTS directory,url
define("EXPORTS_DIRECTORY",SITE_DIRECTORY.'exports/');
define("EXPORTS_URL",SITE_URL.'exports/');

//Db settings

// define('DB_SERVER','Database server IN HERE');
// define('DB_USER','DATABASE USER IN HERE');
// define('DB_PASS' ,'DATABASE PASS IN HERE');
// define('DB_NAME', 'DATABASE NAME IN HERE');


define('DB_SERVER','localhost');
define('DB_USER','root');
define('DB_PASS' ,'root');
define('DB_NAME', 'email-ci');

define('EMAIL_SMTP','EMAIL SMTP IN HERE');
define('EMAIL_PASS' ,'EMAIL PASSWORD IN HERE');
define('EMAIL_ADDRESS', 'EMAIL ADRESS IN HERE');


//for check used in demo or not
define('IS_DEMO', false);


?>
