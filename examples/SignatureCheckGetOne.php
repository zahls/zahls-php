<?php

/**
 * Example: SignatureCheck request model
 *
 * @author    zahls <info@zahls.ch>
 * @copyright zahls
 * @since     v1.5.0
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
$zahls->setHttpHeaders([
    'Shop-ID' => 1,
]);

$signatureCheck = new \Zahls\Models\Request\SignatureCheck();
try {
    $zahls->getOne($signatureCheck);
    die('Signature correct');
} catch (\Zahls\ZahlsException $e) {
    print $e->getMessage();
    die('Signature wrong');
}
