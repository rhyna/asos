<?php

require_once __DIR__ . '/include/init.php';

$conn = require_once __DIR__ . '/include/db.php';

$error = '';

$brandsByGender = [];

$rootCategoryFlag = require_once __DIR__ . '/include/root-category-flag.php';

require_once __DIR__ . '/include/header.php';

try {
    if (!isset($_GET['gender'])) {
        throw new Exception('No gender provided');
    }

     if ($_GET['gender'] !== 'men' && $_GET['gender'] !== 'women') {
         throw new Exception('Provide correct gender (men / women)');
     }

    $gender = $_GET['gender'];

    $config = require __DIR__ . "/include/categories-config.php";

    $categoriesByGender = [];

    $brandsByGender = [];

    $targetNode = [];

    foreach ($config as $configData) {
        if ($rootCategoryFlag === $configData['flag']) {
            $targetNode = $configData;

            break;
        }
    }

    foreach ($targetNode['categories'] as $firstLevelCategories) {
        foreach ($firstLevelCategories as $secondLevelCategories) {
            $data = [];

            $data['id'] = $secondLevelCategories['id'];

            $data['title'] = $secondLevelCategories['title'];

            $categoriesByGender[] = $data;
        }
    }

    $brandsByGender = Product::getAllBrandsByGender($conn, $categoriesByGender);


} catch (Throwable $e) {
    $error = $e->getMessage();
}

?>

<?php if ($error): ?>
    <p class="error-message"><?= $error ?></p>
<?php else: ?>
    <main class="main-content">
        <div class="brands-catalog">
            <h1 class="brands-catalog-title">
                A-Z <?= $rootCategoryFlag ?> Brands
            </h1>
            <ul>
                <?php foreach ($brandsByGender as $brand): ?>
                    <li class="brands-catalog-item">
                        <a href="/brand-catalog.php?gender=<?= $rootCategoryFlag ?>&id=<?= $brand['id'] ?>">
                            <?= $brand['title'] ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </main>
<?php endif; ?>

<?php require_once __DIR__ . '/include/footer.php'; ?>

