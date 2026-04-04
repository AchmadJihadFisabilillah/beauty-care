CREATE DATABASE IF NOT EXISTS glowe_ecommerce;
USE glowe_ecommerce;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    phone VARCHAR(30) DEFAULT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin','user') NOT NULL DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE addresses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    recipient_name VARCHAR(100) NOT NULL,
    phone VARCHAR(30) NOT NULL,
    address_line TEXT NOT NULL,
    city VARCHAR(100) NOT NULL,
    province VARCHAR(100) NOT NULL,
    postal_code VARCHAR(20) NOT NULL,
    notes VARCHAR(255) DEFAULT NULL,
    is_default TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE brands (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    brand_id INT NOT NULL,
    category_id INT NOT NULL,
    name VARCHAR(150) NOT NULL,
    slug VARCHAR(180) NOT NULL UNIQUE,
    description TEXT NOT NULL,
    price DECIMAL(12,2) NOT NULL,
    stock INT NOT NULL DEFAULT 0,
    sku VARCHAR(100) DEFAULT NULL,
    image VARCHAR(255) DEFAULT NULL,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (brand_id) REFERENCES brands(id),
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

CREATE TABLE shipping_rates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    label VARCHAR(100) NOT NULL,
    city VARCHAR(100) NOT NULL,
    cost DECIMAL(12,2) NOT NULL,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_code VARCHAR(50) NOT NULL UNIQUE,
    user_id INT NOT NULL,
    address_id INT NOT NULL,
    subtotal DECIMAL(12,2) NOT NULL,
    shipping_cost DECIMAL(12,2) NOT NULL,
    total DECIMAL(12,2) NOT NULL,
    payment_method ENUM('bank_transfer') NOT NULL DEFAULT 'bank_transfer',
    payment_status ENUM('pending','waiting_verification','paid','rejected') NOT NULL DEFAULT 'pending',
    order_status ENUM('new','processed','shipped','completed','cancelled') NOT NULL DEFAULT 'new',
    notes TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (address_id) REFERENCES addresses(id)
);

CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    product_name VARCHAR(150) NOT NULL,
    product_price DECIMAL(12,2) NOT NULL,
    qty INT NOT NULL,
    line_total DECIMAL(12,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id)
);

CREATE TABLE payment_confirmations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    sender_name VARCHAR(100) NOT NULL,
    bank_name VARCHAR(100) NOT NULL,
    transfer_amount DECIMAL(12,2) NOT NULL,
    proof_image VARCHAR(255) DEFAULT NULL,
    verification_status ENUM('pending','verified','rejected') NOT NULL DEFAULT 'pending',
    admin_notes TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
);

CREATE TABLE settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) NOT NULL UNIQUE,
    setting_value TEXT NOT NULL
);

CREATE TABLE stock_movements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    movement_type ENUM('in','out','adjustment') NOT NULL,
    qty INT NOT NULL,
    note VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

CREATE TABLE product_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    image VARCHAR(255) NOT NULL,
    is_primary TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

CREATE TABLE admin_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    admin_id INT NOT NULL,
    activity VARCHAR(255) NOT NULL,
    context TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (admin_id) REFERENCES users(id) ON DELETE CASCADE
);

INSERT INTO brands (name, description) VALUES
('Glowé', 'Brand utama Glowe'),
('SkinMuse', 'Partner brand skincare'),
('Pureveil', 'Brand perawatan kulit premium');

INSERT INTO categories (name) VALUES
('Serum'),
('Cleanser'),
('Moisturizer'),
('Sunscreen');

INSERT INTO products (brand_id, category_id, name, slug, description, price, stock, sku, image, is_active) VALUES
(1, 1, 'Hydrating Glow Serum', 'hydrating-glow-serum', 'Serum ringan dengan hyaluronic acid untuk menjaga kelembapan kulit.', 189000, 50, 'SERUM-001', NULL, 1),
(1, 2, 'Gentle Facial Cleanser', 'gentle-facial-cleanser', 'Cleanser lembut untuk mengangkat kotoran tanpa membuat kulit kering.', 149000, 60, 'CLEANSER-001', NULL, 1),
(2, 3, 'Brightening Day Cream', 'brightening-day-cream', 'Krim siang dengan niacinamide untuk membantu kulit tampak cerah.', 219000, 35, 'MOIST-001', NULL, 1),
(3, 4, 'Daily UV Shield SPF 50', 'daily-uv-shield-spf-50', 'Sunscreen nyaman untuk perlindungan harian dari sinar UV.', 179000, 45, 'SUN-001', NULL, 1),
(2, 1, 'Barrier Repair Serum', 'barrier-repair-serum', 'Serum untuk membantu memperkuat skin barrier.', 205000, 25, 'SERUM-002', NULL, 1),
(3, 3, 'Overnight Renewal Cream', 'overnight-renewal-cream', 'Night cream lembut untuk pemakaian malam hari.', 239000, 20, 'MOIST-002', NULL, 1);

INSERT INTO settings (setting_key, setting_value) VALUES
('bank_name', 'BCA'),
('bank_account_name', 'PT Glowe Skincare'),
('bank_account_number', '1234567890');

DELETE FROM users WHERE email = 'admin@glowe.test';

INSERT INTO users (name, email, phone, password, role) VALUES
('Admin Glowe', 'admin@glowe.test', '081234567890', '$2y$10$XFEujG4R7QK4nVQJ1QvV1eQvN4m3WvZJ2OqL7Q5FjYgF6qf7m6M6y', 'admin');