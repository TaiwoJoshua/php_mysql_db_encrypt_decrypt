<?php
// Load environment variables (using a library like vlucas/phpdotenv)
// This helps in securely loading sensitive data like database credentials and encryption keys from a .env file.
require 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Database configuration from environment variables
// These values are retrieved from the .env file to ensure sensitive data isn't hardcoded.
$host = $_ENV['DB_HOST'];
$username = $_ENV['DB_USERNAME'];
$password = $_ENV['DB_PASSWORD'];
$database = $_ENV['DB_DATABASE'];

// Generate the file path for the backup file.
// The backup file will be stored in a "backups" directory with a timestamp in its name.
$backupFile = dirname(__FILE__) . "/backups/" . $database . date("Y-m-d-H-i-s") . '.sql';

// Define the path for the encrypted backup file by appending ".enc" to the backup file's name.
$encryptedFile = $backupFile . '.enc';

// Retrieve the encryption key from environment variables.
$encryptionKey = $_ENV['ENCRYPTION_KEY'];

// Specify the paths to the mysqldump and OpenSSL executables in XAMPP.
// Adjust these paths if the script is being used on a different server or environment.
$mysqldumpPath = 'C:/xampp/mysql/bin/mysqldump.exe';
$opensslPath = 'C:/xampp/apache/bin/openssl.exe';

// Construct the MySQL dump command.
// - This command generates a backup of the specified database.
// - `escapeshellarg` is used for security to prevent command injection.
$command = sprintf(
    '%s --user=%s --password=%s --host=%s %s > %s',
    escapeshellarg($mysqldumpPath),
    escapeshellarg($username),
    escapeshellarg($password),
    escapeshellarg($host),
    escapeshellarg($database),
    escapeshellarg($backupFile)
);

// Execute the database backup command.
exec($command, $output, $result);

// Check if the database backup command was successful.
// A result code of 0 indicates that the command executed without errors.
if ($result == 0) {
    // Encrypt the backup file using OpenSSL for added security.
    // - `enc -aes-256-cbc -salt` specifies encryption using AES-256-CBC with added salt for randomness.
    // - `-in` specifies the input file (backup file).
    // - `-out` specifies the output file (encrypted backup file).
    // - `-k` provides the encryption key.
    $encryptCommand = sprintf(
        '%s enc -aes-256-cbc -salt -in %s -out %s -k %s',
        escapeshellarg($opensslPath),
        escapeshellarg($backupFile),
        escapeshellarg($encryptedFile),
        escapeshellarg($encryptionKey)
    );

    // Execute the encryption command.
    exec($encryptCommand, $encryptOutput, $encryptResult);

    // Remove the unencrypted backup file to ensure only the encrypted version is retained.
    unlink($backupFile);

    // Check if the encryption command was successful.
    if ($encryptResult == 0) {
        echo "Database backup and encryption successful.";
    } else {
        // If encryption fails, display an error message with the output for debugging.
        echo "Database backup successful, but encryption failed. Error: " . implode("\n", $encryptOutput);
    }
} else {
    // If the database backup fails, display an error message with the output for debugging.
    echo "Database backup failed. Error: " . implode("\n", $output);
}
