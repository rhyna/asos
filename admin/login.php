<?php

/**
 * @var PDO $conn;
 */

ob_start(); 
require_once __DIR__ . '/include/header.php';
$header = ob_get_contents();
ob_end_clean();

$error = null;

try {
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        if (User::authenticate($conn, $_POST['username'], $_POST['password'])) {
            Auth::login();

            Url::redirect('/admin');

        } else {
            throw new Exception('Login failed, check credentials');
        }
    }

    if (Auth::isLoggedIn()) {
        throw new Exception('You are already logged in, log out to switch to another user account');
    }

} catch (Throwable $e) {
    $error = $e->getMessage();
}

?>

<?=$header?>
<div class="container">
    <?php if ($error): ?>
        <div><?= $error ?></div>
    <?php else: ?>
        <div class="login-form__wrapper">
            <form action="" method="post" class="login-form">
                <h1>Login</h1>
                <div class="form-group">
                    <label for="username">Username</label>
                    <input class="form-control" type="text" name="username" id="username">
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input class="form-control" type="password" name="password" id="password">
                </div>
                <button type="submit" class="admin-button">Submit</button>
            </form>
        </div>
    <?php endif; ?>
</div>


