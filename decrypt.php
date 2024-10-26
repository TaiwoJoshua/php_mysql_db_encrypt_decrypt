<?php
// Load environment variables (using a library like vlucas/phpdotenv)
// This helps to keep sensitive information like encryption keys out of the code.
require 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Define the name of the encrypted backup file.
// This is the file that will be decrypted.
$filename = 'encrypted_file_name.sql.enc';

// Construct the full path to the encrypted file, stored in the "backups" directory.
$encryptedFile = dirname(__FILE__) . "/backups/$filename";

// Generate the name for the decrypted file by removing the ".enc" extension.
// This will be used as the output file after decryption.
$decryptname = str_replace(".enc", "", $filename);
$decryptedFile = dirname(__FILE__) . "/decryptions/$decryptname";

// Retrieve the encryption key from the environment variables.
$encryptionKey = $_ENV['ENCRYPTION_KEY'];

// Specify the path to the OpenSSL executable.
// Adjust this path if the script is being used on a different environment or server.
$opensslPath = 'C:/xampp/apache/bin/openssl.exe';

// Construct the OpenSSL command for decrypting the file.
// - `enc -d -aes-256-cbc` specifies decryption using AES-256-CBC.
// - `-in` is the input file (encrypted file).
// - `-out` is the output file (decrypted file).
// - `-k` is the key for encryption/decryption.
$decryptCommand = sprintf(
    '%s enc -d -aes-256-cbc -in %s -out %s -k %s',
    escapeshellarg($opensslPath),
    escapeshellarg($encryptedFile),
    escapeshellarg($decryptedFile),
    escapeshellarg($encryptionKey)
);

// Execute the decryption command.
exec($decryptCommand, $decryptOutput, $decryptResult);

// Check if the decryption was successful by checking the result code.
// A result code of 0 indicates success.
if ($decryptResult == 0) {
    echo "Decryption successful.";
} else {
    // If decryption fails, display an error message.
    echo "Decryption failed.";
}
