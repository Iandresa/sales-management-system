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
| 	example.com/class/method/id/
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
| There are two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['scaffolding_trigger'] = 'scaffolding';
|
| This route lets you set a "secret" word that will trigger the
| scaffolding feature for added security. Note: Scaffolding must be
| enabled in the controller in which you intend to use it.   The reserved 
| routes must come before any wildcard or regular expression routes.
|
*/

$route['default_controller'] = "login";
$route['no_access/(:any)'] = "no_access/index/$1";
$route['reports/(summary_:any)/(:any)/(:any)'] = "reports/$1/$2/$3";
//$route['reports/summary_:any'] = "reports/date_input_excel_export";
$route['reports/summary_sales'] = "reports/date_input_excel_export/1";//hector
$route['reports/summary_categories'] = "reports/date_input_excel_export/2";
$route['reports/summary_customers'] = "reports/date_input_excel_export/3";
$route['reports/summary_suppliers'] = "reports/date_input_excel_export/4";
$route['reports/summary_items'] = "reports/date_input_excel_export/5";
$route['reports/summary_employees'] = "reports/date_input_excel_export/6";
$route['reports/summary_taxes'] = "reports/date_input_excel_export/7";
//$route['reports/summary_discounts'] = "reports/date_input_excel_export/8";
$route['reports/summary_payments'] = "reports/date_input_excel_export/9";

$route['reports/(graphical_:any)/(:any)/(:any)'] = "reports/$1/$2/$3";
//$route['reports/graphical_:any'] = "reports/date_input";
//$route['reports/'.basename($_SERVER[PHP_SELF])] = "reports/date_input/".basename($_SERVER[PHP_SELF]);//oscar
$route['reports/graphical_summary_sales'] = "reports/date_input/1";//oscar
$route['reports/graphical_summary_categories'] = "reports/date_input/2";
$route['reports/graphical_summary_customers'] = "reports/date_input/3";
$route['reports/graphical_summary_suppliers'] = "reports/date_input/4";
$route['reports/graphical_summary_items'] = "reports/date_input/5";
$route['reports/graphical_summary_employees'] = "reports/date_input/6";
$route['reports/graphical_summary_taxes'] = "reports/date_input/7";
//$route['reports/graphical_summary_discounts'] = "reports/date_input/8";
$route['reports/graphical_summary_payments'] = "reports/date_input/9";

$route['reports/(inventory_:any)/(:any)'] = "reports/$1/$2";
$route['reports/inventory_:any'] = "reports/excel_export";

$route['reports/(detailed_sales)/(:any)/(:any)'] = "reports/$1/$2/$3";
$route['reports/detailed_sales'] = "reports/date_input";
$route['reports/(detailed_receivings)/(:any)/(:any)'] = "reports/$1/$2/$3";
$route['reports/detailed_receivings'] = "reports/date_input";
$route['reports/(specific_:any)/(:any)/(:any)/(:any)'] = "reports/$1/$2/$3/$4";
$route['reports/specific_customer'] = "reports/specific_customer_input";
$route['reports/specific_employee'] = "reports/specific_employee_input";

$route['scaffolding_trigger'] = "";


/* End of file routes.php */
/* Location: ./application/config/routes.php */