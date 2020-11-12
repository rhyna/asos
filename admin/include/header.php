<?php

require_once __DIR__ . "/../../include/init.php";

$conn = require_once __DIR__ . "/../../include/db.php";

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ASOS | Admin Main Page</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
          integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh"
          crossorigin="anonymous">
    <link rel="stylesheet" href="/admin/css/style.css">
    <link rel="stylesheet" href="/vendor/font/FuturaPT/stylesheet.css">
    <link rel="stylesheet" href="/vendor/fontawesome-free-5.13.1-web/css/all.css">
</head>
<body>
<header class="admin-header">
    <nav class="navbar navbar-expand-lg navbar-light bg-light admin-header-nav">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item active admin-header-nav-item">
                    <a class="nav-link" href="/admin/index.php">Home<span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item admin-header-nav-item">
                    <a class="nav-link" href="/admin/banners.php">Banners</a>
                </li>
                <li class="nav-item admin-header-nav-item">
                    <a class="nav-link" href="/admin/brands.php">Brands</a>
                </li>
                <li class="nav-item admin-header-nav-item">
                    <a class="nav-link" href="/admin/categories.php">Categories</a>
                </li>
                <li class="nav-item admin-header-nav-item">
                    <a class="nav-link" href="/admin/products.php">Products</a>
                </li>
                <?php if (Auth::isLoggedIn()): ?>
                    <li class="nav-item admin-header-nav-item">
                        <a class="nav-link" href="/admin/logout.php">Log out</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item admin-header-nav-item">
                        <a class="nav-link" href="/admin/login.php">Log in</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>
</header>
