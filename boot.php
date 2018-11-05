<?php
/**
	OpenTHC API Application Bootstrap
*/

define('APP_NAME', 'OpenTHC');
define('APP_SITE', 'https://api.openthc.org');
define('APP_ROOT', __DIR__);
define('APP_SALT', sha1(APP_NAME . APP_SITE . APP_ROOT));

error_reporting(E_ALL & ~ E_NOTICE);

openlog('openthc-bunk', LOG_ODELAY|LOG_PID, LOG_LOCAL0);

require_once(APP_ROOT . '/vendor/autoload.php');
