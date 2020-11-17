<?php

require_once __DIR__ . '/include/header.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (User::authenticate($conn, $_POST['username'], $_POST['password'])) {
        Auth::login();

        Url::redirect('/admin');

    } else {
        die('login failed, check credentials');
    }
}

if (Auth::isLoggedIn()) {
    echo 'You are already logged in, log out to switch to another user account';
    exit;
}

?>
<div class="container">
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
</div>


