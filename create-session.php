<?php

require_once __DIR__ . '/vendor/autoload.php';

// 2. Load the .env file
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();


// 3. Access your keys securely!
$secretKey = $_SERVER['CHECKOUT_SECRET_KEY'] ?? null;



if (!$secretKey) {
    // If the key is empty, stop the script and print an error so we know for sure
    http_response_code(500);
    echo json_encode(['error' => 'The secret key is not loading!']);
    exit;
}

// Hide default HTML errors from breaking our JSON response
ini_set('display_errors', 0);
error_reporting(E_ALL & ~E_DEPRECATED); // Specifically ignore deprecation warnings
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["error" => "Method not allowed"]);
    exit;
}

if (!function_exists('curl_init')) {
    http_response_code(500);
    echo json_encode(["error" => "The PHP cURL extension is missing or disabled."]);
    exit;
}


$url = "https://api.sandbox.checkout.com/payment-sessions";

// Dynamically get the base URL (works for localhost and Heroku)
if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
    $protocol = 'https';
} else {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
}
$host = $_SERVER['HTTP_HOST'];
$baseUrl = $protocol . "://" . $host;

$payload = [
    "amount" => 34930,
    "currency" => "AED",
    "capture" => true,
    "reference" => "order_ref_12345",
    "description" => "order_ref_12345",
    "customer" => [
        "email" => "demo_user@hotmail.com",
        "name" => "John Smith",
        "phone" => [
            "country_code" => "971",
            "number" => "500000001"
        ]
    ],
    // "disabled_payment_methods" => ["remember_me"],
    "billing" => [
        "address" => [
            "address_line1" => "123 High St.",
            "city" => "Dubai",
            "zip" => "SW1A 1AA",
            "country" => "AE"
        ]
    ],
    "shipping" => [
        "address" => [
            "address_line1" => "123 High St.",
            "city" => "Dubai",
            "zip" => "SW1A 1AA",
            "country" => "AE"
        ]
    ],
    "3ds" => [
        "enabled" => true
    ],
    "items" => [
        [
            "name" => "Shoes",
            "quantity" => 1,
            "unit_price" => 34930,
            "reference" => "858818ac"
        ]
    ],
    "processing_channel_id" => "pc_b6rt5bz6gw7ujnzwuvgn3zez2u",
    "success_url" => $baseUrl . "/success.php",
    "failure_url" => $baseUrl . "/failed.php"
];

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer " . $secretKey,
    "Content-Type: application/json"
]);

$response = curl_exec($ch);

if(curl_errno($ch)){
    $error_msg = curl_error($ch);
    http_response_code(500);
    // Clear output buffer to ensure pure JSON
    ob_clean();
    echo json_encode(["error" => "cURL Request Failed: " . $error_msg]);
    exit;
}

$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

// Clear output buffer before sending the final response to prevent rogue characters/warnings
ob_clean();
http_response_code($httpcode);
echo $response;
?>