<?php


class Auth
{

    static public function login(): void
    {
        session_regenerate_id();

        $_SESSION['is_logged_in'] = true;
    }

    static public function logout(): void
    {
        $_SESSION['is_logged_in'] = null;

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        session_destroy();
    }

    /**
     * @return bool
     */
    static public function isLoggedIn(): bool
    {
        if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in']) {
            return true;
        } else {
            return false;
        }
    }

    static public function ifNotLoggedIn(): void
    {
        if (!self::isLoggedIn()) {
            echo "<p>Access denied to unauthorised users, <a href='/admin/login.php' style='color: #0770cf'>login</a> first</p>";
            exit;
        }
    }
}
