<?php
/*
	CoinPayments.net API Example
	Copyright 2016 CoinPayments.net. All rights reserved.	
	License: GPLv2 - http://www.gnu.org/licenses/gpl-2.0.txt
*/
$cp = $model->db_query($db, "*", "payment_setting", "id = '4'");
    // Fill these in with the information from your CoinPayments.net account.
	require('./coinpayments.inc.php');
	$cps = new CoinPaymentsAPI();
	$cps->Setup(decrypt($cp['rows']['value_2']), decrypt($cp['rows']['value_1']));

	$result = $cps->SendToPayByName(0.1, 'BTC', '$CoinPayments');
	if ($result['error'] == 'ok') {
		print 'Transfer created with ID: '.$result['result']['id'];
	} else {
		print 'Error: '.$result['error']."\n";
	}
