<?php

declare(strict_types=1);

require_once 'config/Database.php';

function log_message($message) {
    echo $message . "\n";
    error_log($message);
}

$database = new Database();
$pdo = $database->getConnection();

try {
    if (!$pdo) {
        throw new Exception("Failed to establish database connection.");
    }

    log_message("Database connection established.");

    $pdo->setAttribute(PDO::ATTR_AUTOCOMMIT, 0);
    log_message("Autocommit disabled.");

    if (!$pdo->beginTransaction()) {
        throw new Exception("Failed to start transaction.");
    }
    log_message("Transaction started successfully.");

    $pdo->exec("CREATE TABLE IF NOT EXISTS products (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        description TEXT,
        price DECIMAL(10, 2) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )");

    log_message("Products table created or already exists.");

    $products = [
        ['name' => 'iPhone 16', 'description' => 'Latest model with advanced features', 'price' => 799.99],
        ['name' => 'MacBook Pro', 'description' => 'High-performance laptop for professionals', 'price' => 1299.99],
        ['name' => 'AirPods Pro', 'description' => 'True wireless earbuds with noise cancellation', 'price' => 159.99],
        ['name' => 'Apple Watch', 'description' => 'Fitness tracker and smartwatch in one', 'price' => 249.99],
        ['name' => 'Apple TV', 'description' => '4K Ultra HD Smart TV', 'price' => 699.99]
    ];

    $stmt = $pdo->prepare("INSERT INTO products (name, description, price, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())");

    foreach ($products as $product) {
        $stmt->execute([
            $product['name'],
            $product['description'],
            $product['price']
        ]);
    }

    log_message("Products inserted successfully.");

    if (!$pdo->commit()) {
        throw new Exception("Failed to commit transaction");
    }
    log_message("Transaction committed successfully.");

    log_message("Database seeded successfully with " . count($products) . " products.");
} catch (Exception $e) {
    if ($pdo && $pdo->inTransaction()) {
        $pdo->rollBack();
        log_message("Transaction rolled back.");
    }
    log_message("Error seeding database: " . $e->getMessage());
    log_message("PDO Error Info: " . print_r($pdo->errorInfo(), true));
} finally {
    if ($pdo) {
        log_message("PDO Attributes:");
        log_message("AUTOCOMMIT: " . $pdo->getAttribute(PDO::ATTR_AUTOCOMMIT));
        log_message("ERRMODE: " . $pdo->getAttribute(PDO::ATTR_ERRMODE));
        log_message("DRIVER NAME: " . $pdo->getAttribute(PDO::ATTR_DRIVER_NAME));
    }
}
