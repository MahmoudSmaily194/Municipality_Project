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
            importance_level ENUM('low','medium','high') DEFAULT 'low',
            status ENUM('pending', 'in_progress', 'completed', 'rejected') DEFAULT 'pending',
            ai_validation_status ENUM('approved','flagged','rejected') DEFAULT 'approved',
            visibility ENUM('hidden', 'visible') DEFAULT 'hidden',
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
// X. Event Votes
// ---------------------
$pdo->exec("
    CREATE TABLE IF NOT EXISTS event_votes (
        id CHAR(36) PRIMARY KEY,
        event_id CHAR(36) NOT NULL,
        user_id CHAR(36) NOT NULL,
        vote TINYINT(1) DEFAULT 1, -- always 1 (like/upvote)
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,

        CONSTRAINT fk_votes_event
            FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE,

        CONSTRAINT fk_votes_user
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,

        CONSTRAINT unique_user_event_vote UNIQUE (event_id, user_id)
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
            visibility ENUM('Private', 'Public') DEFAULT 'Public',
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
            FOREIGN KEY (permit_id) REFERENCES permits(id) ON DELETE CASCADE,
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
            note TEXT NULL,
            status ENUM('pending', 'in_progress', 'completed', 'rejected') DEFAULT 'pending',
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (permit_request_id) REFERENCES permits_requests(id) ON DELETE CASCADE,
            FOREIGN KEY (updated_by) REFERENCES users(id) ON DELETE SET NULL
        );
    ");
     // ---------------------
    // 10. Permit Required Docs
    // ---------------------
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS permits_required_attachments (
            id CHAR(36) PRIMARY KEY,
            permit_id CHAR(36) NULL,
            document_name VARCHAR(255) NOT NULL,
            FOREIGN KEY (permit_id) REFERENCES permits(id) ON DELETE CASCADE
        );
    ");
    // ---------------------
    // 11. Permit Request Attachments
    // ---------------------
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS permits_requests_attachments (
            id CHAR(36) PRIMARY KEY,
            permit_request_id CHAR(36) NULL,
            file_url VARCHAR(255) NULL,
            uploaded_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (permit_request_id) REFERENCES permits_requests(id) ON DELETE CASCADE
        );
    ");

    echo 'Database and all tables created successfully with ENUM fields added!';

} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}
