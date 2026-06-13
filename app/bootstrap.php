<?php

$config = require __DIR__ . '/../config/config.php';

require_once __DIR__ . '/../config/database.php';

session_name($config['session_name']);

session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'httponly' => true,
    'secure' => isset($_SERVER['HTTPS']),
    'samesite' => 'Lax'
]);

session_start();