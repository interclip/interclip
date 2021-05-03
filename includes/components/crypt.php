<?php
$message_to_encrypt = "https://stackoverflow.com/questions/9262109/simplest-two-way-encryption-using-php";
$secret_key = "iosxd";
$method = "aes256";
$iv_length = openssl_cipher_iv_length($method);
$iv = openssl_random_pseudo_bytes($iv_length);

$encrypted_message = openssl_encrypt($message_to_encrypt, $method, $secret_key, 0, $iv);
$decrypted_message = openssl_decrypt($encrypted_message, $method, $secret_key, 0, $iv);

echo "Original: {$message_to_encrypt}\nEncrypted: {$encrypted_message}.\nDecrypted: {$decrypted_message}";
