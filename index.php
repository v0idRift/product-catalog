<?php

declare(strict_types=1);

require_once __DIR__ . '/config/Database.php';
require_once __DIR__ . '/models/Category.php';
require_once __DIR__ . '/models/Product.php';

$categoryModel = new Category();
$productModel = new Product();

$rawCategoryId = $_GET['category_id'] ?? '';
$categoryId = is_numeric($rawCategoryId)
        ? (int)$rawCategoryId
        : null;

$sort = isset($_GET['sort']) && is_string($_GET['sort'])
        ? $_GET['sort']
        : 'date_desc';

$categories = $categoryModel->getAll();
$products = $productModel->getAll($categoryId, $sort);

$initialData = json_encode([
        'categories' => $categories,
        'products' => $products,
        'filters' => [
                'category_id' => $categoryId,
                'sort' => $sort,
        ],
], JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);

?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Каталог товарів — Електроніка</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>

<!-- Header -->
<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
    <div class="container-fluid px-4">
        <a class="navbar-brand fw-bold text-primary" href="/">
            <i class="bi bi-cpu"></i> Електроніка
        </a>
        <ul class="navbar-nav ms-auto">
            <li class="nav-item"><a class="nav-link" href="/">Головна</a></li>
            <li class="nav-item"><a class="nav-link active fw-semibold" href="/">Каталог</a></li>
            <li class="nav-item"><a class="nav-link" href="#">Про нас</a></li>
            <li class="nav-item"><a class="nav-link" href="#">Контакти</a></li>
        </ul>
    </div>
</nav>

<!-- Breadcrumbs -->
<div class="container-fluid px-4 py-2">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0 small">
            <li class="breadcrumb-item"><a href="/">Головна</a></li>
            <li class="breadcrumb-item active" aria-current="page">Каталог товарів</li>
        </ol>
    </nav>
</div>

<div class="container-fluid px-4 py-3 main-content">
    <div class="row g-4">
        <!-- Sidebar: categories -->
        <div class="col-lg-3">
            <h6 class="text-uppercase fw-bold text-muted mb-3">Категорії</h6>
            <div class="list-group list-group-flush" id="category-list">
                <a class="list-group-item list-group-item-action d-flex justify-content-between align-items-center category-item<?= $categoryId === null ? ' active' : '' ?>"
                   href="#" data-category-id="">
                    Усі категорії
                    <span class="badge rounded-pill"><?= array_sum(array_column($categories, 'product_count')) ?></span>
                </a>
                <?php foreach ($categories as $cat): ?>
                    <a class="list-group-item list-group-item-action d-flex justify-content-between align-items-center category-item<?= $categoryId === (int)$cat['id'] ? ' active' : '' ?>"
                       href="#" data-category-id="<?= (int)$cat['id'] ?>">
                        <?= htmlspecialchars($cat['name'], ENT_QUOTES, 'UTF-8') ?>
                        <span class="badge rounded-pill"><?= (int)$cat['product_count'] ?></span>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Main: sort + products -->
        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold mb-0">Каталог товарів</h4>
                <div class="d-flex align-items-center gap-2">
                    <label for="sort-select" class="text-muted small text-nowrap mb-0">Сортувати:</label>
                    <select class="form-select form-select-sm w-auto" id="sort-select">
                        <option value="price_asc"<?= $sort === 'price_asc' ? ' selected' : '' ?>>Спочатку дешевші</option>
                        <option value="name_asc"<?= $sort === 'name_asc' ? ' selected' : '' ?>>По алфавіту</option>
                        <option value="date_desc"<?= $sort === 'date_desc' ? ' selected' : '' ?>>Спочатку нові</option>
                    </select>
                </div>
            </div>

            <div class="row g-4" id="product-list">
                <!-- filled by JS -->
            </div>
        </div>
    </div>
</div>

<!-- Buy modal -->
<div class="modal fade" id="buyModal" tabindex="-1" aria-labelledby="buyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom">
                <h5 class="modal-title fw-bold" id="buyModalLabel">Підтвердження замовлення</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрити"></button>
            </div>
            <div class="modal-body" id="buyModalBody">
                <!-- filled by JS -->
            </div>
            <div class="modal-footer border-top">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Закрити</button>
                <button type="button" class="btn btn-success px-4">Підтвердити</button>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="bg-dark text-light mt-5 pt-4 pb-3">
    <div class="container-fluid px-4">
        <div class="row g-4">
            <div class="col-md-4">
                <h6 class="fw-bold text-white"><i class="bi bi-cpu"></i> Електроніка</h6>
                <p class="small text-secondary">Найкращий вибір електроніки в Україні за вигідними цінами.</p>
            </div>
            <div class="col-md-4">
                <h6 class="fw-bold text-uppercase small text-white">Компанія</h6>
                <ul class="list-unstyled small text-secondary">
                    <li><a href="#" class="text-secondary text-decoration-none">Про нас</a></li>
                    <li><a href="#" class="text-secondary text-decoration-none">Контакти</a></li>
                </ul>
            </div>
            <div class="col-md-4">
                <h6 class="fw-bold text-uppercase small text-white">Допомога</h6>
                <ul class="list-unstyled small text-secondary">
                    <li><a href="#" class="text-secondary text-decoration-none">Оплата і доставка</a></li>
                    <li><a href="#" class="text-secondary text-decoration-none">Обмін та повернення</a></li>
                </ul>
            </div>
        </div>
        <hr class="border-secondary">
        <p class="text-center text-secondary small mb-0">&copy; 2026 Магазин Електроніки. Усі права захищені.</p>
    </div>
</footer>

<script>const initialData = <?= $initialData ?>;</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/app.js"></script>
</body>
</html>
