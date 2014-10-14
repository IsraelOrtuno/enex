<?php
session_start();

ob_start();

$path = $_SERVER['PHP_SELF'];
$path = str_replace('index.php', '', $path);

define('WEBROOT', $path);
define('APPPATH', WEBROOT.'app/');
define('APPROOT', APPPATH.'web/');
define('DOCROOT', $_SERVER['DOCUMENT_ROOT']);

require_once('./autoloader.php');
require_once('./enex/config/load.php');

$registry   = Registry::getInstance();
$db         = new DataSource();
$registry->add('db', $db);              ///// COOOOOMMMMEEEENNNTTTSSS!!!!
/*$login      = new loginController();
$registry->add('login', $login);*/

require_once('./app/web/index.php');

ob_end_flush();
?>