<?php
require_once '/xampp/htdocs/Municipality/backend/config/db.php';

try {

    // Create and select the database
    $pdo->exec("CREATE DATABASE IF NOT EXISTS municipality CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;");
    $pdo->exec("USE municipality;");

    // ---------------------
    // 1. Users
    // ---------------------
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS users (
            id CHAR(36) PRIMARY KEY,
            first_name VARCHAR(50) NULL,
            last_name VARCHAR(50) NULL,
            email VARCHAR(150) NOT NULL UNIQUE,
            password_hash VARCHAR(255) NOT NULL,
            phone_number VARCHAR(50) NULL,
            role ENUM('admin', 'citizen') NOT NULL,
            profile_photo VARCHAR(255) NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        );
    ");

    // ---------------------
    // 2. Complaint Issues
    // ---------------------
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS complaint_issues (
            id CHAR(36) PRIMARY KEY,
            issue_name VARCHAR(100),
            is_deleted TINYINT(1) DEFAULT 0,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        );
    ");

    // ---------------------
    // 3. Complaints
    // ---------------------
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS complaints (
            id CHAR(36) PRIMARY KEY,
            description TEXT,
            importance INT DEFAULT 0,
            status ENUM('pending', 'in_progress', 'completed', 'rejected') DEFAULT 'pending',
            visibility ENUM('hidden', 'visible') DEFAULT 'visible',
            is_seen TINYINT(1) DEFAULT 0,
            issue_id CHAR(36) NULL,
            image_url VARCHAR(255) NULL,
            latitude DOUBLE NULL,
            longitude DOUBLE NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            created_by CHAR(36) NULL,
            FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL,
            FOREIGN KEY (issue_id) REFERENCES complaint_issues(id) ON DELETE SET NULL
        );
    ");

    // ---------------------
    // 4. Events
    // ---------------------
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS events (
            id CHAR(36) PRIMARY KEY,
            title VARCHAR(100),
            slug VARCHAR(100) UNIQUE,
            description TEXT NULL,
            image_url VARCHAR(255) NULL,
            location VARCHAR(100) NULL,
            date DATETIME NULL,
            marked_as_done TINYINT(1) DEFAULT 0,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            created_by CHAR(36) NULL,
            FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
        );
    ");

    // ---------------------
    // 5. News
    // ---------------------
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS news (
            id CHAR(36) PRIMARY KEY,
            title VARCHAR(100),
            slug VARCHAR(100) UNIQUE,
            description TEXT NULL,
            image_url VARCHAR(255) NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            visibility ENUM('hidden', 'visible') DEFAULT 'visible',
            created_by CHAR(36) NULL,
            FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
        );
    ");

    // ---------------------
    // 6. Permits Categories
    // ---------------------
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS permits_categories (
            id CHAR(36) PRIMARY KEY,
            name VARCHAR(100),
            is_deleted TINYINT(1) DEFAULT 0,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        );
    ");

    // ---------------------
    // 7. Permits
    // ---------------------
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS permits (
            id CHAR(36) PRIMARY KEY,
            category_id CHAR(36) NULL,
            title VARCHAR(100),
            slug VARCHAR(100) NULL,
            description TEXT NULL,
            image_url VARCHAR(255) NULL,
            status ENUM('active', 'inActive') DEFAULT 'inActive',
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            created_by CHAR(36) NULL,
            FOREIGN KEY (category_id) REFERENCES permits_categories(id) ON DELETE SET NULL,
            FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
        );
    ");

    // ---------------------
    // 8. Permits Requests
    // ---------------------
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS permits_requests (
            id CHAR(36) PRIMARY KEY,
            permit_id CHAR(36) NULL,
            user_id CHAR(36) NULL,
            priority INT DEFAULT 1,
            status ENUM('pending', 'in_progress', 'completed', 'rejected') DEFAULT 'pending',
            requested_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            completed_at DATETIME NULL,
            FOREIGN KEY (permit_id) REFERENCES permits(id) ON DELETE SET NULL,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
        );
    ");

    // ---------------------
    // 9. Permit Request History
    // ---------------------
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS permits_requests_history (
            id CHAR(36) PRIMARY KEY,
            permit_request_id CHAR(36) NULL,
            updated_by CHAR(36) NULL,
            file_url VARCHAR(255) NULL,
            status ENUM('pending', 'in_progress', 'completed', 'rejected') DEFAULT 'pending',
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (permit_request_id) REFERENCES permits_requests(id) ON DELETE SET NULL,
            FOREIGN KEY (updated_by) REFERENCES users(id) ON DELETE SET NULL
        );
    ");

    // ---------------------
    // 10. Permit Request Attachments
    // ---------------------
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS permits_requests_attachments (
            id CHAR(36) PRIMARY KEY,
            permit_request_id CHAR(36) NULL,
            file_url VARCHAR(255) NULL,
            uploaded_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (permit_request_id) REFERENCES permits_requests(id) ON DELETE SET NULL
        );
    ");

    echo 'Database and all tables created successfully with ENUM fields added!';

} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}
