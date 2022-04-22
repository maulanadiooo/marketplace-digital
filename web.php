<?php

date_default_timezone_set('Asia/Jakarta');
ini_set('memory_limit', '128M');

/* CONFIG */
$config['web'] = array(
    'close' => 1,
	'base_url' => '', // ex: http://domain.com/
);

$date = date('Y-m-d H:i:s');
$smtp = '';
$username_smtp = '';
$password_smtp = '';


$config['db'] = array(
	'host' => '',
	'name' => '',
	'username' => '',
	'password' => ''
);
/* END - CONFIG */

require 'lib/db.php';
require 'lib/model.php';
require 'lib/function.php';

session_start();
$model = new Model();

if($config['web']['close'] == 1){
    header("Location: https://yourdomain.com/close.php");
}