<?

$website_paypal = $model->db_query($db, "*", "bank_information", "id = '7' AND status ='active' ");

define('PAYPAL_ID', $website_paypal['rows']['no_rek']); 
define('PAYPAL_SANDBOX', FALSE); //TRUE or FALSE 
define('PAYPAL_RETURN_URL', $config['web']['base_url'].'checkout/paypal/success.php'); 
define('PAYPAL_CANCEL_URL', $config['web']['base_url'].'checkout/paypal/cancel.php'); 
define('PAYPAL_NOTIFY_URL', $config['web']['base_url'].'checkout/paypal/ipn.php'); 
define('PAYPAL_CURRENCY', 'USD');
 
define('PAYPAL_URL', (PAYPAL_SANDBOX == true)?"https://www.sandbox.paypal.com/cgi-bin/webscr":"https://www.paypal.com/cgi-bin/webscr");