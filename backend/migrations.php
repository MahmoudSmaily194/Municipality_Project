<?php
require_once '/wamp64/www/Municipality/backend/config/db.php';
try {
    $pdo = new PDO($dsn, $user, $pass, $options);

    // ---------------------
    // 1. Users
    // ---------------------
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS users (
            id CHAR(36) PRIMARY KEY,
            first_name VARCHAR(50) NULL,
            last_name VARCHAR(50) NULL,
            email VARCHAR(50) NOT NULL UNIQUE,
            password_hash VARCHAR(70) NOT NULL,
            phone_number VARCHAR(50) NULL,
            role VARCHAR(50) NOT NULL,
            profile_photo VARCHAR(50) NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        );
    ");

    // ---------------------
    // 2. Complaint
    // ---------------------
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS complaints (
            id CHAR(36) PRIMARY KEY,
            full_name VARCHAR(50),
            phone_number VARCHAR(50),
            description TEXT,
            importance INT DEFAULT 0,
            status INT DEFAULT 0,
            visibility INT DEFAULT 1,
            is_seen TINYINT(1) DEFAULT 0,
            issue_id CHAR(36),
            image_url VARCHAR(50) NULL,
            latitude DOUBLE NULL,
            longitude DOUBLE NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        );
    ");

    // ---------------------
    // 3. ComplaintIssue
    // ---------------------
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS complaint_issues (
            id CHAR(36) PRIMARY KEY,
            issue_name VARCHAR(50),
            is_deleted TINYINT(1) DEFAULT 0
        );
    ");

    // ---------------------
    // 4. Event
    // ---------------------
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS events (
            id CHAR(36) PRIMARY KEY,
            title VARCHAR(50),
            slug VARCHAR(50) UNIQUE,
            description TEXT NULL,
            image_url VARCHAR(50) NULL,
            location VARCHAR(50) NULL,
            date DATETIME NULL,
            marked_as_done TINYINT(1) DEFAULT 0
        );
    ");

    // ---------------------
    // 5. News
    // ---------------------
    $pdo->exec("
        DROP TABLE IF EXISTS `news`;
        CREATE TABLE IF NOT EXISTS `news` (
            id CHAR(36) PRIMARY KEY,
            title VARCHAR(50),
            slug VARCHAR(50) UNIQUE,
            description TEXT NULL,
            image_url VARCHAR(50) NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            visibility INT DEFAULT 1
        );
    ");

    // ---------------------
    // 6. ServicesCategories
    // ---------------------
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS services_categories (
            id CHAR(36) PRIMARY KEY,
            name VARCHAR(50),
            is_deleted TINYINT(1) DEFAULT 0
        );
    ");

    // ---------------------
    // 7. Service
    // ---------------------
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS services (
            id CHAR(36) PRIMARY KEY,
            category_id CHAR(36) NULL,
            title VARCHAR(50),
            slug VARCHAR(50) NULL,
            description TEXT NULL,
            image_url VARCHAR(50) NULL,
            status INT DEFAULT 1,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (category_id) REFERENCES services_categories(id) ON DELETE SET NULL
        );
    ");

    echo "All tables created successfully!\n";

} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}
