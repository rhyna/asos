<?php

require_once __DIR__ . '/include/init.php';

$conn = require_once __DIR__ . '/include/db.php';

$error = '';

$rootCategoryFlag = '';

if (isset($_GET['gender']) && $_GET['gender'] === 'men') {
    $rootCategoryFlag = 'men';

} else if (isset($_GET['gender']) && $_GET['gender'] === 'women') {
    $rootCategoryFlag = 'women';
}

require_once __DIR__ . '/include/header.php';

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

try {
    $gender = $_GET['gender'] ?? null;

    if (!$gender) {
        throw new Exception('No gender name provided');
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
        <ul>
            <?php foreach ($brandsByGender as $brand): ?>
                <li>
                    <a href="/brands.php?gender=<?= $rootCategoryFlag ?>&id=<?= $brand['id'] ?>">
                        <?= $brand['title'] ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</main>
<?php endif; ?>

<?php require_once __DIR__ . '/include/footer.php'; ?>

