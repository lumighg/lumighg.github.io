<?php

function isLoggedIn(): bool
{
    return isset($_SESSION['user_id']);
}

function isAdmin(): bool
{
    return isset($_SESSION['role'])
        && $_SESSION['role'] === 'admin';
}

function requireLogin(): void
{
    if (!isLoggedIn()) {

        header("Location: /admin/login.php");
        exit;
    }
}

function requireAdmin(): void
{
    if (!isAdmin()) {

        http_response_code(403);

        die("Zugriff verweigert");
    }
}