<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/
	
$route['default_controller'] = "home";

$route['signUp'] = "home/signUp";
$route['recover'] = "home/recoverPassword";
$route['showMonthlyStatusCurrent'] = "home/showMonthlyStatusCurrent";

$route['showExpByItemCategoryMonthly/(.*)'] = "home/showExpByItemCategoryMonthly/$1";
$route['showExpByItemCategoryMonthly'] = "home/showExpByItemCategoryMonthly";

$route['showExpForCategoryByAccountsMonthly/(.*)/(.*)/(.*)'] = "home/showExpForCategoryByAccountsMonthly/$1/$2/$3";
$route['showExpForCategoryByAccountsMonthly/(.*)/(.*)'] = "home/showExpForCategoryByAccountsMonthly/$1/$2";
$route['showExpForCategoryByAccountsMonthly/(.*)'] = "home/showExpForCategoryByAccountsMonthly/$1";
$route['showExpForCategoryByAccountsMonthly'] = "home/showExpForCategoryByAccountsMonthly";

$route['showExpForCategoryByItemsMonthly/(.*)/(.*)/(.*)'] = "home/showExpForCategoryByItemsMonthly/$1/$2/$3";
$route['showExpForCategoryByItemsMonthly/(.*)/(.*)'] = "home/showExpForCategoryByItemsMonthly/$1/$2";
$route['showExpForCategoryByItemsMonthly/(.*)'] = "home/showExpForCategoryByItemsMonthly/$1";
$route['showExpForCategoryByItemsMonthly'] = "home/showExpForCategoryByItemsMonthly";

$route['showExpForAccountByItemsMonthly/(.*)/(.*)/(.*)'] = "home/showExpForAccountByItemsMonthly/$1/$2/$3";
$route['showExpForAccountByItemsMonthly/(.*)/(.*)'] = "home/showExpForAccountByItemsMonthly/$1/$2";
$route['showExpForAccountByItemsMonthly/(.*)'] = "home/showExpForAccountByItemsMonthly/$1";
$route['showExpForAccountByItemsMonthly'] = "home/showExpForAccountByItemsMonthly";

$route['dailyDraw'] = "home/dailyDraw";
$route['showPrize/(.*)'] = "home/showPrize/$1";
$route['showPrize'] = "home/showPrize";
$route['logout'] = "home/doLogout";
$route['ajax/(.*)'] = 'ajax/$1';
$route['404_override'] = '';
/*COM WORDS SECTION */
$route['comWordList'] = "word/comWordListWithSent";
$route['comQuesList'] = "word/comQuesListWithAns";



/* ADMIN PANEL SECTION --- Start--- */
/**********CI Admin Panel *******Start*********/
$route['adminDashboard'] = "adminpanel/index";
$route['getListing'] = "adminpanel/getListing";//For Add Form and Listing
$route['getListing/(.*)'] = "adminpanel/getListing/$1";//For Add Form and Listing
$route['getListing/(.*)/(.*)/(.*)'] = "adminpanel/getListing/$1/$2/$3";//For Edit,Delete form and Listing

$route['postData'] = "adminpanel/postData";

/**********CI Admin Panel *******End*********/


//Durandal admin Panel/*****************/
$route['adminUserList'] = "home/adminUserList";
$route['adminCreateUser'] = "home/adminCreateUser";
$route['adminDeleteUser'] = "home/adminDeleteUser";

$route['adminAccountList'] = "home/adminAccountList";
$route['adminCreateAccount'] = "home/adminCreateAccount";
$route['adminDeleteAccount'] = "home/adminDeleteAccount";
//*******************************************/


// Business Play
$route['startBusiness'] = "business/index";
//***************************************/
/* End of file routes.php */
/* Location: ./application/config/routes.php */