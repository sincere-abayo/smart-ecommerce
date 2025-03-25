<?php
$host = 'localhost';
$dbname = 'ecommerce_db_22rp05419';
//$dbname = 'ecommerce_db';
$username = 'root';
$password = 'wordpass';
//$password = '';

try {
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create database if not exists
    $pdo->exec("CREATE DATABASE IF NOT EXISTS $dbname");
    $pdo->exec("USE $dbname");
    
    // Create products table
    $pdo->exec("CREATE TABLE IF NOT EXISTS products (
        id INT AUTO_INCREMENT PRIMARY KEY,
        product_name VARCHAR(255) NOT NULL,
        sku VARCHAR(100) UNIQUE NOT NULL,
        category VARCHAR(100),
        brand VARCHAR(100),
        regular_price DECIMAL(10,2),
        sale_price DECIMAL(10,2),
        tax_rate DECIMAL(5,2),
        stock_quantity INT,
        low_stock_alert INT,
        description TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
