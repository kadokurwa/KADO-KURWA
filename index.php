<?php
require __DIR__ . '/src/config/config.php';
require __DIR__ . '/src/config/database.php';

session_start();

if (!empty($_SESSION['student_id'])) {
    header('Location: src/pages/dashboard.php');
    exit;
}

include __DIR__ . '/src/pages/login.php';
