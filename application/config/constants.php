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

/*
|---------------------------------------------------------------------------
| My defined variables global
|---------------------------------------------------------------------------
|
*/
define('_error_name_exists_', 'User name or email already exists! Please, choose another.');
define('_error_cannot_insert_db_', 'Cannot insert into database.');
define('_error_cannot_save_db_', 'Cannot save to database.');
define('_error_access_denied_', 'Access denied.');
define('_subject_mail_create_account_','This mail sent by Administrator');

define('__keycode__', '<ho^^slDbtSS695hG>');
define('__keyat__', '(d^^gk67ty@45M)');

define('__new_user_created_by_administrator__', 'Welcome, new user created by administrator');
define('__no_approval_required__', 'Welcome, no approval required');
define('__new_rep_welcome__', 'New member’s welcome Letter');
define('__downline_to_upline_confirmation__', 'Email-Downline to upline confirmation');
define('__password_recovery__', 'Password recovery email');
define('__account_activation__', 'Account activation email');
define('__account_blocked__', 'Account blocked email');
define('__account_deleted__', 'Account deleted email');
define('__restocking_reminder__', 'Restocking reminder to Manufacturers Email');
define('__monthly_reminder__', 'Monthly reminder to member to full fill $20 Email');
define('__notify_inactive_member__', 'Notify to inactive member 2 wks before remove from network Email');
define('__new_products__', 'Announcement of new products Email');

//constant for email sending
define('SIGNATURE', 'NailSalon TV Team');
define('EMAIL_SUPPORT', 'pvm@soleilbrite.com');
define('SENDER_NAME', 'Mitchell Phan');
define('__email_to_get_order__','dong@nailsalontv.com');

define('Anonymous_user', 1);
define('Administrator', 3);
define('Network_Management', 4);
define('Sale_Representatives', 9);
define('Affiliates', 6);

define('AUTHENTICATED_USER', 2);
define('ADMINISTRATOR', 3);
define('NWMANAGEMENT', 4);
define('MANUFACTURER', 5);
define('CHARITY', 8);
//row per page
define('NUMROWPERPAGE', 20);

//download's constant

define('ALLOWED_REFERRER', '');
define('BASE_DIR',str_replace("system/", "", BASEPATH)."resource/business");
define('LOG_DOWNLOADS',true);
define('LOG_FILE','downloads.log');

//order status 
define('PENDING',1);
define('PACKERSHIPPING',2);
define('COMPLETED',3);
define('CANCELED',4);
define('REFUNDED',5);

define("_same_product_","----------");

//Default Credit Merchant Cost
define('CREDIT_MERCHANT_DEF',4);

