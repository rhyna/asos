<?php

require_once __DIR__ . "/../include/init.php";

if ($_SERVER['REQUEST_URI'] === '/admin/index.php') {
    Url::redirect('/admin/products.php');
}

if ($_SERVER['REQUEST_URI'] === '/admin/') {
    Url::redirect('/admin/products.php');
}

require_once __DIR__ . '/include/header.php';

$error = null;

try {
    Auth::ifNotLoggedIn();

} catch (Throwable $e) {
    $error = $e->getMessage();
}
?>
    <main>
        <div class="container">
            <?php if ($error): ?>
                <div><?= $error ?></div>
            <?php else: ?>
                <div>
                    Home
                </div>
            <?php endif; ?>
        </div>
    </main>

<?php require_once __DIR__ . '/include/footer.php'; ?>