<?php

declare(strict_types=1);

require_once 'config/database.php';
require_once 'models/product.php';
require_once 'config/core.php';
require_once 'api/products.php';

header("Content-Type: application/json");

$method = $_SERVER['REQUEST_METHOD'];
$request = $_SERVER['REQUEST_URI'];

$url = isset($_GET['url']) ? $_GET['url'] : '';
$url = rtrim($url, '/');
$url = explode('/', $url);

$resource = $url[0] ?? '';
$id = isset($url[1]) ? (int) $url[1] : null;

$database = new Database();
$db = $database->getConnection();

$product = new Product($db);

match ($method) {
    'GET' => match ($resource) {
        'products' => getProducts($product),
        'product' => $id ? getProduct($product, $id) : sendNotFound(),
        default => sendNotFound(),
    },
    'POST' => match ($resource) {
        'product' => addProduct($product),
        default => sendNotFound(),
    },
    'PUT' => match ($resource) {
        'product' => $id ? updateProduct($product, $id) : sendNotFound(),
        default => sendNotFound(),
    },
    'DELETE' => match ($resource) {
        'product' => $id ? deleteProduct($product, $id) : sendNotFound(),
        default => sendNotFound(),
    },
    default => sendMethodNotAllowed(),
};

function sendNotFound(): void
{
    http_response_code(404);
    echo json_encode(["error" => "Resource not found"]);
}

function sendMethodNotAllowed(): void
{
    http_response_code(405);
    echo json_encode(["error" => "Method not allowed"]);
}
