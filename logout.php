<?php
require_once __DIR__ . '/../app/bootstrap.php';
requireLogin();
session_destroy();

header("Location: login.php");
exit;