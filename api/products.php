<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Product.php';

header('Content-Type: application/json; charset=utf-8');

$rawCategoryId = $_GET['category_id'] ?? '';
$categoryId = is_numeric($rawCategoryId)
    ? (int)$rawCategoryId
    : null;

$sort = isset($_GET['sort']) && is_string($_GET['sort'])
    ? $_GET['sort']
    : 'date_desc';

try {
    $productModel = new Product();
    $products = $productModel->getAll($categoryId, $sort);

    echo json_encode(['products' => $products], JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
}
