<?php
ini_set('display_errors', 'on');
//ob_start("ob_gzhandler");
//error_reporting(E_ALL);

// start the session
session_start();

// database connection config

$dbHost = '127.0.0.1:3308';
$dbUser = 'event_system';
$dbPass = 'password';
$dbName = 'db_event_management';

/*
$dbHost = 'localhost';
$dbUser = 'tousifkh_calenda';
$dbPass = 'ce=rgfq=C6LB';
$dbName = 'tousifkh_calendar';
*/
//Project data
$site_title 	= 'Online Banking - www.TechZoo.org';
$email_id 		= 'customerservice@hlbonline.pro';

$thisFile = str_replace('\\', '/', __FILE__);
$docRoot = $_SERVER['DOCUMENT_ROOT'];

$webRoot  = str_replace(array($docRoot, 'library/config.php'), '', $thisFile);
$srvRoot  = str_replace('library/config.php', '', $thisFile);

define('WEB_ROOT', $webRoot);
define('SRV_ROOT', $srvRoot);




require_once 'database.php';
require_once 'common.php';

?>