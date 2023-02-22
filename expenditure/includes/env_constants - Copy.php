<?php
$host = $_SERVER['HTTP_HOST'];
//print $host;die;
	switch($host)
    {
        case 'local.expenditure.com':
            define('ENV_NAME', 'local');

            define('BASEURL', 'http://local.expenditure.com');

            break;
        case '192.168.0.107':
		//print '<pre>';print_R($_SERVER);die;
            define('ENV_NAME', 'local');
            
            define('BASEURL', 'http://192.168.0.107/local.expenditure.com');
            break;
        case '192.168.0.103':
		//print '<pre>';print_R($_SERVER);die;
            define('ENV_NAME', 'local');
            
            define('BASEURL', 'http://192.168.0.103/local.expenditure.com');
            break;			
        default:
            break;
    }
	define('ERROR_LEVEL', E_ALL);
	define('PROFILER', TRUE);
	define('HTDOCS_PATH', 'C:/xampp/htdocs/expenditure.com');
	define('SYSTEM_PATH', HTDOCS_PATH . '/system');
	define('APP_PATH', HTDOCS_PATH . '/application');
	define('INCLUDE_PATH', '.;' . HTDOCS_PATH . '/includes;');
	define('CURRENT_MONTH', date('m'));
	define('CURRENT_MONTH_NAME', date('M'));
	define('CURRENT_YEAR', date('Y'));
	define('DEFAULT_EXP_DISPLAY', 123);
	define('DEBUG_STATUS', 0);
	/*DATABASE SETTINGS*/
	define('CONNECT_SERVER', 'mysql');
	define('DB_HOST', 'localhost');
	define('DB_NAME', 'home_expenditure');
	define('DB_USER', 'local_user');
	define('DB_PASS', 'local_pass');
set_include_path(implode(PATH_SEPARATOR, array(get_include_path(), INCLUDE_PATH)));	

// error handling
error_reporting(ERROR_LEVEL);
if(ERROR_LEVEL === 0)
{
    ini_set('display_errors','0');
}
else
{
    ini_set('display_errors','1');
} 
?>