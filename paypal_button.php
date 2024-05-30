<?php
// paypal_button.php

// Include the PayPal SDK
require 'paypal-php-sdk/autoload.php';

// Set up the PayPal environment
$clientId = 'YOUR_PAYPAL_CLIENT_ID';
$clientSecret = 'YOUR_PAYPAL_CLIENT_SECRET';

$paypalConfig = [
    'mode' => 'sandbox', // Change to 'live' for production mode
    'http.ConnectionTimeOut' => 30,
    'log.LogEnabled' => true,
    'log.FileName' => 'logs/paypal.log',
    'log.LogLevel' => 'FINE'
];

$apiContext = new \PayPal\Rest\ApiContext(
    new \PayPal\Auth\OAuthTokenCredential($clientId, $clientSecret)
);

// Create a PayPal payment button
$payment = new \PayPal\Api\Payment();
$payment->setIntent('sale')
    ->setPayer(
        new \PayPal\Api\Payer(['payment_method' => 'paypal'])
    )
    ->setRedirectUrls(
        new \PayPal\Api\RedirectUrls(
            [
                'return_url' => 'https://example.com/success.php', // Replace with your success URL
                'cancel_url' => 'https://example.com/cancel.php' // Replace with your cancel URL
            ]
        )
    )
    ->setTransactions(
        [
            new \PayPal\Api\Transaction(
                [
                    'amount' => ['total' => $totalPrice, 'currency' => 'USD'] // Set the total amount and currency
                ]
            )
        ]
    );

try {
    // Create the PayPal payment button
    $payment->create($apiContext);

    // Get the PayPal approval URL
    $approvalUrl = $payment->getApprovalLink();

    // Redirect the user to the PayPal approval URL
    header("Location: $approvalUrl");
} catch (\PayPal\Exception\PayPalConnectionException $ex) {
    // Handle any errors
    echo $ex->getCode(); // Log or display the error code
    echo $ex->getData(); // Log or display the detailed error message
    exit();
}
?>