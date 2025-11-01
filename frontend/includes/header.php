<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title : 'NETH Bookhive'; ?> - NETH Bookhive</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/student.css">
    <link rel="stylesheet" href="css/unique-vibes.css">
    <link rel="stylesheet" href="css/premium-design.css">
    <link rel="stylesheet" href="css/responsive.css">
    <link rel="stylesheet" href="css/accessibility.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <?php if(isset($extra_css)): ?>
        <?php foreach($extra_css as $css): ?>
            <link rel="stylesheet" href="<?php echo $css; ?>">
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-logo">
                <h2>NETH Bookhive</h2>
            </div>
            <div class="nav-menu">
                <a href="index.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'active' : ''; ?>">Home</a>
                <a href="shop.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'shop.php') ? 'active' : ''; ?>">Shop</a>
                <a href="about.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'about.php') ? 'active' : ''; ?>">About</a>
                <a href="../contact.php" class="nav-link">Contact</a>
                <div class="nav-auth">
                    <a href="login.html" class="nav-link" id="loginLink">Login</a>
                    <a href="register.html" class="nav-link" id="registerLink">Register</a>
                    <a href="profile.html" class="nav-link" id="profileLink" style="display: none;"><i class="fas fa-user"></i> Profile</a>
                    <a href="#" class="nav-link" id="logoutLink" style="display: none;">Logout</a>
                    <span id="userName" style="display: none;"></span>
                </div>
                <a href="cart.php" class="nav-link cart-icon">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="cart-count">0</span>
                </a>
            </div>
            <div class="hamburger">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </nav>
