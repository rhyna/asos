<?php

require_once __DIR__ . "/../include/init.php";

if ($_SERVER['REQUEST_URI'] === '/admin/index.php') {
    Url::redirect('/admin/');
}

require_once __DIR__ . '/include/header.php';

Auth::ifNotLoggedIn();

?>

    <main>
        123
    </main>

<?php require_once __DIR__ . '/include/footer.php'; ?>