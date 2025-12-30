<?php
if (!isset($pageTitle)) $pageTitle = "BLOPS";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet"
 href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        .site-header{
            box-shadow: 4px 4px 4px grey;
        }
        
        /* ===== HEADER ===== */
.site-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 14px 28px;
    background: linear-gradient(135deg, #0a58ca, #0d6efd);
    color: #fff;
    box-shadow: 0 6px 18px rgba(0,0,0,0.15);
    position: sticky;
    top: 0;
    z-index: 1000;
}

/* ===== LOGO AREA ===== */
.logo-area {
    display: flex;
    flex-direction: column;
    line-height: 1.2;
}

.logo-main {
    font-size: 28px;
    font-weight: 800;
    letter-spacing: 2px;
}

.logo-sub {
    font-size: 12px;
    opacity: 0.9;
}

/* ===== NAV ===== */
.top-nav {
    display: flex;
    align-items: center;
    gap: 15px;
}

/* User info */
.user-info {
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 600;
    background: rgba(255,255,255,0.15);
    padding: 6px 14px;
    border-radius: 30px;
}

.user-info i {
    font-size: 18px;
}

/* ===== BUTTONS ===== */
.btn {
    padding: 8px 16px;
    border-radius: 30px;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: all 0.3s ease;
}

/* Outline button */
.btn-outline {
    border: 2px solid #fff;
    color: #fff;
}

.btn-outline:hover {
    background: #fff;
    color: #0d6efd;
}

/* Primary button */
.btn-primary {
    background: #28a745;
    color: #fff;
    border: 2px solid transparent;
}

.btn-primary:hover {
    background: #9acd32; /* lemon green */
    color: #000;
}

/* Icons inside buttons */
.btn i {
    font-size: 14px;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
    .site-header {
        flex-direction: column;
        gap: 10px;
        text-align: center;
    }

    .top-nav {
        flex-wrap: wrap;
        justify-content: center;
    }

    .logo-main {
        font-size: 22px;
    }

    .logo-sub {
        font-size: 11px;
    }
}

    </style>
</head>
<body>
<header class="site-header">
    <div class="logo-area">
        <span class="logo-main">BLOPS</span>
        <span class="logo-sub">Bright Learners Organization & Concour Prep School</span>
    </div>
    <nav class="top-nav">
        <?php if (isLoggedIn()): ?>
            <span class="user-info">
                <i class="fa fa-user-circle"></i>
                <?= htmlspecialchars($_SESSION['full_name']) ?>
            </span>
            <a href="logout.php" class="btn btn-outline" id="logout"><i class="fa fa-sign-out-alt" id="i-logout"></i> Logout</a>
        <?php else: ?>
            <a href="login.php" class="btn btn-outline" id="logout"><i class="fa fa-sign-in-alt" id="i-logout"></i> Login</a>
            <a href="register.php" class="btn btn-primary" id="logout"><i class="fa fa-user-plus" id="i-logout"></i> Register</a>
        <?php endif; ?>
    </nav>
</header>
<main class="main-content">