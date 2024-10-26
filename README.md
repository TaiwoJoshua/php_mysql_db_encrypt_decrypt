# PHP Database Backup and Encryption

This project is a PHP script that automates the process of backing up a MySQL database, encrypting the backup, and saving it securely. It uses the `mysqldump` utility to create a `.sql` backup file and then encrypts the file using `OpenSSL` with AES-256-CBC encryption.

## Features
- Creates a backup of a MySQL database.
- Encrypts the backup file using AES-256-CBC for enhanced security.
- Stores backups in a designated `backups` directory.
- Securely handles credentials and sensitive information through environment variables.

## Prerequisites
- PHP 7.4 or higher.
- [Composer](https://getcomposer.org/) for managing dependencies.
- MySQL installed (e.g., through [XAMPP](https://www.apachefriends.org/index.html)).
- `mysqldump` and `openssl` utilities installed and available in the system's PATH.

## Getting Started

### 1. Clone the repository
```
git clone https://github.com/TaiwoJoshua/php_mysql_db_encrypt_decrypt.git
cd php-database-backup-encryption
```

## 2. Install dependencies
Make sure [Composer](https://getcomposer.org/) is installed, then run:
```
composer install
```

## 3. Set up environment variables
Create a `.env` file in the project root and configure the following variables:

```
DB_HOST=your_database_host
DB_USERNAME=your_database_username
DB_PASSWORD=your_database_password
DB_DATABASE=your_database_name
ENCRYPTION_KEY=your_encryption_key
```

- Replace your_database_host with your MySQL host (e.g., localhost).
- Replace your_database_username and your_database_password with your database credentials.
- Replace your_database_name with the name of the database you want to back up.
- Replace your_encryption_key with a secure key for encrypting the backup file.

### 4. Adjust paths for mysqldump and openssl
If you're using XAMPP, make sure the paths to mysqldump and openssl are correct in the PHP script.
Adjust the paths in the script if you're using a different environment.

### 5. Run the script
Execute the script from the command line:
```
php backup.php
```

This will create a backup of the database, encrypt it, and store the encrypted backup file in the backups directory.

## Directory Structure
```
php_mysql_db_encrypt_decrypt/
│
├── backups/               # Directory where encrypted backup files are saved.
├── decryptions/           # Directory for storing decrypted files (if needed).
├── vendor/                # Composer dependencies.
├── .env                   # Environment variables.
├── composer.json          # Composer configuration file.
├── decrypt.php            # Main script for decrypting backups.
├── encrypt.php            # Main script for creating and encrypting backups.
└── README.md              # Project documentation.
```

### Security Considerations
Never expose your .env file: It contains sensitive information like database credentials and encryption keys.
Use a strong encryption key: Choose a complex and unique encryption key to secure your backup files.
Secure the backups directory: Store backups in a location that is not publicly accessible.