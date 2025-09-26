
<?php

function encryptData($data, $key, $method = 'aes-256-cbc') {
    
    $iv = random_bytes(16); 
    
   
    $encrypted = openssl_encrypt($data, $method, $key, 0, $iv);
    if ($encrypted === false) {
        throw new Exception('Encryption failed');
    }
    
    // Combine IV + encrypted data, base64-encode for URL safety
    return base64_encode($iv . $encrypted);
}


function decryptData($encrypted_data, $key, $method = 'aes-256-cbc') {
    
    $enc_data = base64_decode($encrypted_data);
    if ($enc_data === false) {
        throw new Exception('Invalid encoding');
    }
    
    
    $iv = substr($enc_data, 0, 16);
    $ciphertext = substr($enc_data, 16);
    
    // Decrypt
    $decrypted = openssl_decrypt($ciphertext, $method, $key, 0, $iv);
    if ($decrypted === false) {
        throw new Exception('Decryption failed');
    }
    
    return $decrypted;
}

// test  EncodeStr & DecodeStr

function decodeString($sIn) {
    $abfrom = '';
    $decodestr = '';
    
    // Build abfrom: uppercase A-Z, lowercase a-z, digits 0-9
    for ($x = 0; $x <= 25; $x++) {
        $abfrom .= chr(65 + $x);
    }
    for ($x = 0; $x <= 25; $x++) {
        $abfrom .= chr(97 + $x);
    }
    for ($x = 0; $x <= 9; $x++) {
        $abfrom .= strval($x);
    }
    
    // Create abto: rotate abfrom by 16 characters
    $abto = substr($abfrom, 16) . substr($abfrom, 0, 16);
    
    // Decode each character
    for ($x = 0; $x < strlen($sIn); $x++) {
        $char = substr($sIn, $x, 1);
        $y = strpos($abto, $char);
        if ($y === false) {
            $decodestr .= $char;
        } else {
            $decodestr .= substr($abfrom, $y, 1);
        }
    }
    
    return $decodestr;
}




function encodeStr($sIn) {
    $encodeStr = "";
    $abFrom = "";
    

    for ($x = 0; $x < 26; $x++) {
        $abFrom .= chr(65 + $x); 
    }
    for ($x = 0; $x < 26; $x++) {
        $abFrom .= chr(97 + $x); 
    }
    for ($x = 0; $x < 10; $x++) {
        $abFrom .= strval($x); 
    }
    
    $abTo = substr($abFrom, 16) . substr($abFrom, 0, 16);
    
    for ($x = 0; $x < strlen($sIn); $x++) {
        $char = substr($sIn, $x, 1);
        $y = strpos($abFrom, $char);
        
        if ($y === false) {
 
            $encodeStr .= $char;
        } else {
 
            $encodeStr .= substr($abTo, $y, 1);
        }
    }
    
    return $encodeStr;
}
// can you also make it change for khmer Unicode as well 

?>

