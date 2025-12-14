CREATE DATABASE IF NOT EXISTS toko_bangunan;
USE toko_bangunan;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL, -- password_hash
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL,
    image VARCHAR(255),
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT,
    name VARCHAR(200) NOT NULL,
    description TEXT,
    price DECIMAL(15, 2), -- Prices in IDR
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS gallery (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200),
    image VARCHAR(255) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100),
    message TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert Dummy Data based on current website
INSERT INTO categories (name, slug, image, description) VALUES
('Bahan Bangunan', 'bahan-bangunan', 'assets/cat-material.png', 'Semen, Pasir, Bata, Besi Beton'),
('Alat Listrik', 'alat-listrik', 'assets/cat-electric.png', 'Kabel, Saklar, Lampu, Pipa Listrik'),
('Cat & Pelapis', 'cat-pelapis', 'assets/cat-paint.png', 'Cat Tembok, Kayu, Besi & Waterproofing'),
('Alat Pertukangan', 'alat-pertukangan', 'assets/cat-tools.png', 'Bor, Palu, Gergaji, Kunci Ingles');

-- Insert Dummy Products (linked to categories)
INSERT INTO products (category_id, name, description, price, image) VALUES
(1, 'Semen Padang 50kg', 'Semen berkualitas tinggi', 65000, 'assets/product-semen.png'),
(2, 'Lampu LED Philips 10W', 'Hemat energi', 45000, 'assets/product-lampu.png'),
(3, 'Cat Dulux Weathershield', 'Tahan cuaca ekstrim', 250000, 'assets/product-cat.png'),
(4, 'Bor Listrik Bosch', 'Bor tangan kuat', 750000, 'assets/product-bor.png');

-- Insert Gallery
INSERT INTO gallery (title, image) VALUES
('Stok Melimpah', 'assets/cat-material.png'),
('Display Modern', 'assets/cat-electric.png'),
('Koleksi Lengkap', 'assets/cat-paint.png'),
('Alat Berkualitas', 'assets/cat-tools.png');
