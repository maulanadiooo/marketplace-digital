<?php

require 'vendor/autoload.php';

use SMSGatewayMe\Client\ApiClient;
use SMSGatewayMe\Client\Configuration;
use SMSGatewayMe\Client\Api\CallbackApi;
use SMSGatewayMe\Client\Model\CreateCallbackRequest;

// Configure client
$config = Configuration::getDefaultConfiguration();
$config->setApiKey('Authorization', 'your-token-here');
$apiClient = new ApiClient($config);

// Create callback client
$callbackClient = new CallbackApi($apiClient);

/**
 * Create Callback Request
 * For valid events, filter types and methods please view the swagger definition
 */
$createCallbackRequest = new CreateCallbackRequest([
    'name' => 'Test Callback',
    'event' => 'MESSAGE_RECEIVED',
    'deviceId' => 123456,
    'filterType' => 'contains',
    'filter' => 'hello',
    'method' => 'HTTP',
    'action' => 'http://mywebsite.com/sms-callback.php',
    'secret' => 'SsshhhhNotASecret'
]);

$callback = $callbackClient->createCallback($createCallbackRequest);
print_r($callback);
