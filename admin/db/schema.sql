-- CMS Database Schema for BYD Asia Afrika

-- Admin users
CREATE TABLE IF NOT EXISTS admin_users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert default admin (password: admin123)
INSERT INTO admin_users (username, password) VALUES 
('admin', '$2y$10$YrKzlZGEZ8jK5xS1f3P8zuN8LjVxBd.lZqKGvFjJ3j7LqJ3K3xH/S')
ON DUPLICATE KEY UPDATE username=username;

-- Site configuration (key-value store)
CREATE TABLE IF NOT EXISTS site_config (
    id INT PRIMARY KEY AUTO_INCREMENT,
    config_key VARCHAR(100) UNIQUE NOT NULL,
    config_value TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Slides (hero banner)
CREATE TABLE IF NOT EXISTS slides (
    id INT PRIMARY KEY AUTO_INCREMENT,
    eyebrow VARCHAR(100),
    title VARCHAR(255) NOT NULL,
    text TEXT,
    cta_label VARCHAR(100),
    cta_href VARCHAR(255),
    image VARCHAR(255),
    display_order INT DEFAULT 0,
    active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Car models
CREATE TABLE IF NOT EXISTS car_models (
    id INT PRIMARY KEY AUTO_INCREMENT,
    slug VARCHAR(100) UNIQUE NOT NULL,
    name VARCHAR(100) NOT NULL,
    category ENUM('MPV', 'SUV', 'Sedan', 'Citycar') NOT NULL,
    powertrain ENUM('BEV', 'PHEV') NOT NULL,
    is_new BOOLEAN DEFAULT FALSE,
    tagline VARCHAR(255),
    harga_otr DECIMAL(15,2),
    specs JSON,
    highlights JSON,
    image VARCHAR(255),
    brosur VARCHAR(255),
    display_order INT DEFAULT 0,
    active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
