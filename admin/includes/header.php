<?php
require_once __DIR__ . '/config/auth.php';
requireLogin();

$username = $_SESSION['admin_username'] ?? 'Admin';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Admin' ?> - BYD Asia Afrika CMS</title>
    <link rel="stylesheet" href="/assets/admin.css">
</head>
<body>
    <nav class="topnav">
        <div class="nav-brand">🚗 BYD Asia Afrika CMS</div>
        <div class="nav-right">
            <span class="nav-user">👤 <?= htmlspecialchars($username) ?></span>
            <button onclick="logout()" class="btn-logout">Logout</button>
        </div>
    </nav>
    
    <div class="layout">
        <aside class="sidebar">
            <a href="/dashboard.php" class="sidebar-link <?= basename($_SERVER['PHP_SELF']) === 'dashboard.php' ? 'active' : '' ?>">
                📊 Dashboard
            </a>
            <a href="/site.php" class="sidebar-link <?= basename($_SERVER['PHP_SELF']) === 'site.php' ? 'active' : '' ?>">
                ⚙️ Site Config
            </a>
            <a href="/slides.php" class="sidebar-link <?= basename($_SERVER['PHP_SELF']) === 'slides.php' ? 'active' : '' ?>">
                🖼️ Banner Hero
            </a>
            <a href="/models.php" class="sidebar-link <?= basename($_SERVER['PHP_SELF']) === 'models.php' ? 'active' : '' ?>">
                🚘 Model Mobil
            </a>
            <hr style="margin: 20px 0; border-color: #444;">
            <button onclick="rebuild()" class="sidebar-link rebuild-btn" style="width: 100%; text-align: left; background: #2563eb; color: white; border: none; cursor: pointer; padding: 12px 16px; border-radius: 6px;">
                🔄 Rebuild Site
            </button>
        </aside>
        
        <main class="content">
