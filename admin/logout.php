<?php

require_once __DIR__ . '/../include/init.php';

Auth::logout();

Url::redirect('/admin/login.php');
