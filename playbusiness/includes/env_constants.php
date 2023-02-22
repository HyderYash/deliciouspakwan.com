<?php
$host = $_SERVER['HTTP_HOST'];
//print $host;die;
	switch($host)
    {
        case 'local.deliciouspakwan.com':
            define('ENV_NAME', 'local');
			define('ERROR_LEVEL', E_ALL);
			define('PROFILER', TRUE);
			define('HTDOCS_PATH', 'C:/xampp/htdocs/deliciouspakwan.com/playbusiness');
			define('SYSTEM_PATH', HTDOCS_PATH . '/system');
			define('APP_PATH', HTDOCS_PATH . '/application');
			define('INCLUDE_PATH', '.;' . HTDOCS_PATH . '/includes;');
			define('CURRENT_MONTH', date('m'));
			define('CURRENT_MONTH_NAME', date('M'));
			define('CURRENT_YEAR', date('Y'));
			define('DEFAULT_EXP_DISPLAY', 123);
			define('DEBUG_STATUS', 0);
            define('BASEURL', 'http://local.deliciouspakwan.com/playbusiness');
			/*DATABASE SETTINGS*/
			define('CONNECT_SERVER', 'mysql');
			define('DB_HOST', 'localhost');
			define('DB_NAME', 'home_business');
			define('DB_USER', 'local_user');
			define('DB_PASS', 'local_pass');
            break;
        default:
			define('ENV_NAME', 'www');
            define('BASEURL', 'http://www.deliciouspakwan.com');
			define('ERROR_LEVEL', 0);
			define('PROFILER', TRUE);
			$commonPath = '/home/buodw97cz4zw/public_html/playbusiness';
			define('HTDOCS_PATH', $commonPath);
			define('SYSTEM_PATH', $commonPath . '/system');
			define('APP_PATH', $commonPath . '/application');
			define('INCLUDE_PATH', '.;' . $commonPath . '/includes;');
			define('CURRENT_MONTH', date('m'));
			define('CURRENT_MONTH_NAME', date('M'));
			define('CURRENT_YEAR', date('Y'));
			define('DEFAULT_EXP_DISPLAY', 123);
			define('DEBUG_STATUS', 0);
			/*DATABASE SETTINGS*/
			define('CONNECT_SERVER', 'mysql');
			define('DB_HOST', 'localhost');
			define('DB_NAME', 'ythomedp');
			define('DB_USER', 'pinki_user');
			define('DB_PASS', '53U3b7,nStu0');		
            break;
    }


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