<?php

declare(strict_types=1);

require_once 'models/Product.php';

function getProducts(Product $product): void
{
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 10;

    $products = $product->readPaginated($page, $perPage);
    $totalCount = $product->getTotalCount();

    $totalPages = ceil($totalCount / $perPage);

    $response = [
        'data' => $products,
        'meta' => [
            'current_page' => $page,
            'per_page' => $perPage,
            'total_count' => $totalCount,
            'total_pages' => $totalPages
        ]
    ];

    echo json_encode($response);
}

function getProduct(Product $product, int $id): void
{
    $productData = $product->getById($id);

    if ($productData) {
        echo json_encode($productData);
    } else {
        http_response_code(404);
        echo json_encode(["error" => "Product not found"]);
    }
}

function addProduct(Product $product): void
{
    $data = json_decode(file_get_contents("php://input"), true);

    if (!empty($data['name']) && !empty($data['description']) && !empty($data['price'])) {
        if ($product->create($data)) {
            http_response_code(201);
            echo json_encode(["message" => "Product created successfully"]);
        } else {
            http_response_code(503);
            echo json_encode(["error" => "Unable to create product"]);
        }
    } else {
        http_response_code(400);
        echo json_encode(["error" => "Unable to create product. Data is incomplete."]);
    }
}

function updateProduct(Product $product, int $id): void
{
    $data = json_decode(file_get_contents("php://input"), true);

    if (!empty($data['name']) && !empty($data['description']) && !empty($data['price'])) {
        if ($product->update($id, $data)) {
            http_response_code(200);
            echo json_encode(["message" => "Product updated successfully"]);
        } else {
            http_response_code(503);
            echo json_encode(["error" => "Unable to update product"]);
        }
    } else {
        http_response_code(400);
        echo json_encode(["error" => "Unable to update product. Data is incomplete."]);
    }
}

function deleteProduct(Product $product, int $id): void
{
    if ($product->delete($id)) {
        http_response_code(200);
        echo json_encode(["message" => "Product deleted successfully"]);
    } else {
        http_response_code(503);
        echo json_encode(["error" => "Unable to delete product"]);
    }
}