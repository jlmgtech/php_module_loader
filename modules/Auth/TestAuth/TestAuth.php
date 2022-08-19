<?php

class TestAuth {

    private static $users = [
        "admin" => [
            "user" => "admin",
            "name" => "Administrator",
            "password" => "abc123",
        ],
    ];

    public static function onload() {
        session_start();
    }

    public static function login(string $user, string $pass): bool {
        if (isset(self::$users[$user]) && self::$users[$user]["password"] === $pass) {
            echo "Logged in as $user";
            $userdata = self::$users[$user];
            unset($userdata["password"]);
            $_SESSION["user"] = $userdata;
            var_dump($_SESSION["user"]);
            return true;
        }
        return false;
    }

    public static function login_guard($fn) {
        return function() use ($fn) {
            if (self::is_logged_in()) {
                return $fn();
            } else {
                echo self::render_login();
                return false;
            }
        };
    }

    public static function render_login(): string {
        $login_link = AutoRouter::get("Auth", "login");
        return "
            <style>".file_get_contents(__DIR__."/styles.css")."</style>
            <form action='$login_link' method='post'>
                <input type='text' name='user' placeholder='Username' />
                <input type='password' name='pass' placeholder='Password' />
                <input type='submit' value='Login' />
            </form>
        ";
    }

    public static function logout() {
        unset($_SESSION['user']);
    }

    public static function is_logged_in() {
        return isset($_SESSION['user']);
    }

    public static function get_user() {
        return $_SESSION['user'];
    }

    public static function get_login() {
        if (self::is_logged_in()) {
            header("Location: /");
        } else {
            return self::render_login();
        }
    }

    public static function get_logout() {
        if (self::is_logged_in()) {
            self::logout();
            header("Location: /");
        } else {
            return self::render_login();
        }
    }

    public static function post_login() {
        if (isset($_POST['user']) && isset($_POST['pass'])) {
            if (self::login($_POST['user'], $_POST['pass'])) {
                echo "<style>".file_get_contents(__DIR__."/styles.css")."</style>";
                echo "<div>Login successful!</div>";
                echo "<a href='/'>Go to home page</a>";
            } else {
                echo "Invalid username or password";
            }
        }
    }

    public static function get_cp() {
        return "
            <style>".file_get_contents(__DIR__."/styles.css")."</style>
            <div>
                <h1>Session</h1>
                <div><a href='/'>Main</a></div>
                <div><a href='/logout'>Logout</a></div>
            </div>
        ";
    }

}
