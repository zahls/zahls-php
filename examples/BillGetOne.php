<?php

/**
 * Example for get one Bill
 *
 * @author    zahls <info@zahls.ch>
 * @copyright zahls
 * @since     v2.0.0
 */

use Zahls\Models\Request\Bill;
use Zahls\Zahls;
use Zahls\ZahlsException;

spl_autoload_register(function ($class) {
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

try {
    $zahls = new Zahls($instanceName, $secret);
} catch (ZahlsException $e) {
    print $e->getMessage();
    exit();
}

$bill = new Bill();
$bill->setUuid('YOUR_UUID');

try {
    $response = $zahls->getOne($bill);
    var_dump($response);
} catch (ZahlsException $e) {
    print $e->getMessage();
}
