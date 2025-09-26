<?php
require_once __DIR__ . '/../includes/encryption.php';
require_once __DIR__ . '/../config/config.php';
$config = require __DIR__ . '/../config/config.php';
$secret_key = $config['encryption_key'];
$method = 'aes-256-cbc';

// Test data with valid keys
$test_data = [
    'e' => '00000000',           // Emp_ID
    'k' => 'ចិត្ត្រា​ ប៉ាង',    // KhmerName
    's' => 'ខែ មករា ២០២៥', // SalaryDTKH
    'b' => 300.30,              // Basic
    'tn' => 10,               // Total_Normal_OT
    'na' => 5,               // Normal_Amount
    'an' => 4,                // Aft_Night_OT
    'oa' => 30,               // OT_Aft_Night
    'hn' => 8,                // Holiday_Normal_OT
    'th' => 480.20,               // Total_HOT
    'nw' => 20.00,               // Night_Wage
    'aa' => 10.00,               // Alw_Att
    'ah' => 50.00,               // Alw_Housing
    'ag' => 15.00,               // Alw_GSTARS
    'al' => 25.00,               // Alw_License
    'ap' => 30.00,               // Alw_Position
    'a1' => 10.00,               // Alw_Additional
    'sn' => 20.00,               // Seniority
    'sa' => 15.00,               // SaleAL
    'aj' => 500.00,                // Adjust
    't1' => 168.00,            // Total_1
    'a2' => 5,                // Abs_Day
    'a3' => 8,                // Abs_Hour
    'a4' => 3,   
    'a5' => 4,
    'a5' => 7,
    'a6' => 5,
    'a7' => 6,
    'au' => 4,                // Abs_Unpaid
    'am' => 20.00,               // Abs_Amount
    'ak' => 100.00,              // Alw_KHNY
    'av' => 200.00,              // Advance
    'dd' => 10.00,               // Deduct
    'pn' => 15.00,               // Pension
    't2' => 183.00             // Total_2
];

// // Convert to JSON and compress
// $json = json_encode($test_data, JSON_UNESCAPED_UNICODE);
// $compressed = gzcompress($json);

// // Encrypt the compressed data
// $enc = encryptData($compressed, $secret_key, $method);

// // Use a shorter base URL
// $base_url = 'localhost/SalaryCheck/public/index.php';
// $url = $base_url . '?enc=' . urlencode($enc);
// // $url = $base_url . '?enc=' . $enc;

// test new dencrypt func
$json = json_encode($test_data, JSON_UNESCAPED_UNICODE);

$enc = encodeStr($json);

$base_url = 'localhost/pay/public/index.php';
$url = $base_url . '?enc=' . $enc;

echo $url . "\n";
echo "\n\n";

// $Decodedata = decodeString($enc);
// if ($Decodedata === false) {
//     throw new Exception('Decryption failed');
// }
// echo "\n\nDecode URL" . $Decodedata ;

// $data = json_decode($Decodedata,  true);
// if (json_last_error() !== JSON_ERROR_NONE) {
//     throw new Exception('JSON decode failed: ' . json_last_error_msg());    
// }
// echo "json Decode: " . $data;
?>