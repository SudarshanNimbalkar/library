<?php
require_once 'database/db.php';
require_once 'config/config.php';
require_once 'includes/functions.php';
start_secure_session();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
    <title><?= ($title ?? APP_NAME) ?></title>
</head>

<body>