<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');


/* End of file constants.php */
/* Location: ./application/config/constants.php */




    define('DOMAIN_URL', "http://".$_SERVER['HTTP_HOST'].'/');
    define("DOMAIN_PATH", dirname(__FILE__));

    /*admin path*/
    define('ADMIN_URL', DOMAIN_URL."admin/");
    define('ASSETS_PATH', DOMAIN_URL."assets/");
    define('ADMIN_ASSETS_PATH', ASSETS_PATH."admin/");
    define('ADMIN_CSS_PATH', ADMIN_ASSETS_PATH."css/");
    define('ADMIN_JS_PATH', ADMIN_ASSETS_PATH."js/");
    define('ADMIN_IMAGE_PATH', ADMIN_ASSETS_PATH."img/");

    define('FRONT_ASSETS_PATH', ASSETS_PATH."front/");
    define('FRONT_IMAGE_PATH', FRONT_ASSETS_PATH."img/");