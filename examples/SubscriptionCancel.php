<?php

/**
 * Example: subscription request model
 *
 * @author    zahls <info@zahls.ch>
 * @copyright zahls
 * @since     v1.0
 */

spl_autoload_register(function($class) {
    $root = dirname(__DIR__);
    $classFile = $root . '/lib/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($classFile)) {
        require_once $classFile;
    }
});

// $instanceName is a part of the url where you access your zahls.ch account.
// https://{$instanceName}.zahls.ch
$instanceName = 'YOUR_INSTANCE_NAME';

// $secret is the API secret for the communication between the applications
// if you think someone got your secret, just regenerate it in the zahls.ch dashboard
$secret = 'YOUR_SECRET';

$zahls = new \Zahls\Zahls($instanceName, $secret);

$subscription = new \Zahls\Models\Request\Subscription();
$subscription->setId(1);
try {
    $response = $zahls->cancel($subscription);
    var_dump($response);
} catch (\Zahls\ZahlsException $e) {
    print $e->getMessage();
}
