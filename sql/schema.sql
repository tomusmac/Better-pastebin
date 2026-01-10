CREATE TABLE IF NOT EXISTS pastes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    uid VARCHAR(50) NOT NULL UNIQUE,
    content MEDIUMTEXT NOT NULL,
    title VARCHAR(255),
    syntax VARCHAR(50) DEFAULT 'text',
    password_hash VARCHAR(255),
    attachment VARCHAR(255) DEFAULT NULL,
    burn_after_reading TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    expires_at TIMESTAMP NULL,
    INDEX (uid),
    INDEX (created_at),
    INDEX (expires_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
