<?php
require_once MODELS_PATH . '/User.php';

// Default preferences
$theme = 'light';
$font_size = 'medium';
$note_color = 'white';

// Get user preferences if logged in
if (Session::isLoggedIn()) {
    $user_id = Session::getUserId();
    $userModel = new User();
    $preferences = $userModel->getUserPreferences($user_id);
    
    // Apply preferences
    $theme = $preferences['theme'] ?? 'light';
    $font_size = $preferences['font_size'] ?? 'medium';
    $note_color = $preferences['note_color'] ?? 'white';
}

// Font size classes
$font_size_class = '';
switch ($font_size) {
    case 'small':
        $font_size_class = 'font-size-small';
        break;
    case 'medium':
        $font_size_class = 'font-size-medium';
        break;
    case 'large':
        $font_size_class = 'font-size-large';
        break;
}

// Get unread notifications if user is logged in
$unread_notifications = [];
$unread_count = 0;
if (Session::isLoggedIn()) {
    require_once MODELS_PATH . '/Notification.php';
    $notificationModel = new Notification();
    $user_id = Session::getUserId();
    $unread_notifications = $notificationModel->getUnreadNotifications($user_id);
    $unread_count = count($unread_notifications);
}
?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="<?= $theme ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? htmlspecialchars($pageTitle) . ' - ' : '' ?><?= APP_NAME ?></title>
    
    <!-- Favicon -->
    <link rel="icon" href="<?= ASSETS_URL ?>/img/favicon.ico" type="image/x-icon">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Base CSS -->
    <link rel="stylesheet" href="<?= ASSETS_URL ?>/css/main.css">
    
    <!-- Page-specific CSS -->
    <?php if (isset($pageStyles) && is_array($pageStyles)): ?>
        <?php foreach ($pageStyles as $style): ?>
            <link rel="stylesheet" href="<?= ASSETS_URL ?>/css/<?= $style ?>.css">
        <?php endforeach; ?>
    <?php endif; ?>
    
    <!-- PWA support -->
    <?php if (defined('ENABLE_OFFLINE_MODE') && ENABLE_OFFLINE_MODE): ?>
        <link rel="manifest" href="<?= BASE_URL ?>/manifest.json">
        <meta name="theme-color" content="#4361ee">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        <meta name="apple-mobile-web-app-title" content="<?= APP_NAME ?>">
        <link rel="apple-touch-icon" href="<?= ASSETS_URL ?>/img/icon-192x192.png">
    <?php endif; ?>
    
    <!-- Enhanced custom styles -->
    <style>
        :root {
            --primary-color: #4361ee;
            --primary-light: #738bff;
            --primary-dark: #3a56da;
            --secondary-color: #6c757d;
            --success-color: #38b000;
            --danger-color: #e5383b;
            --warning-color: #f9c74f;
            --info-color: #4cc9f0;
            --light-color: #f8f9fa;
            --dark-color: #212529;
            --border-radius: 0.5rem;
            --border-radius-lg: 0.75rem;
            --box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            --box-shadow-lg: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            --transition: all 0.3s ease;
        }
        
        /* Base styling */
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            min-height: 100vh;
            line-height: 1.6;
            background-color: #f5f7fa;
        }
        
        /* Font size preferences */
        .font-size-small {
            font-size: 0.875rem !important;
        }
        .font-size-medium {
            font-size: 1rem !important;
        }
        .font-size-large {
            font-size: 1.125rem !important;
        }
        
        /* Enhanced navbar styling */
        .navbar {
            padding: 0.75rem 1rem;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
        }
        
        .navbar-brand {
            font-weight: 600;
            font-size: 1.35rem;
            padding: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .navbar-brand i {
            color: var(--primary-light);
        }
        
        .navbar-dark {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        }
        
        .navbar-nav .nav-link {
            position: relative;
            font-weight: 500;
            padding: 0.75rem 1rem;
            border-radius: var(--border-radius);
            transition: var(--transition);
            color: rgba(255, 255, 255, 0.85);
        }
        
        .navbar-nav .nav-link:hover {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.1);
            transform: translateY(-2px);
        }
        
        .navbar-nav .nav-link.active {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.2);
        }
        
        .navbar-nav .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: 6px;
            left: 50%;
            transform: translateX(-50%);
            width: 20px;
            height: 3px;
            border-radius: 3px;
            background-color: #fff;
        }
        
        /* Enhanced dropdown styling */
        .dropdown-menu {
            border: none;
            box-shadow: var(--box-shadow-lg);
            border-radius: var(--border-radius);
            margin-top: 0.5rem;
            overflow: hidden;
            animation: dropdownFade 0.2s ease-out;
        }
        
        @keyframes dropdownFade {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .dropdown-item {
            padding: 0.7rem 1.25rem;
            font-weight: 500;
            transition: var(--transition);
            position: relative;
        }
        
        .dropdown-item:hover {
            background-color: rgba(67, 97, 238, 0.1);
            color: var(--primary-color);
            padding-left: 1.5rem;
        }
        
        .dropdown-item i {
            margin-right: 0.5rem;
            width: 1.25rem;
            text-align: center;
        }
        
        .dropdown-divider {
            margin: 0.3rem 0;
            opacity: 0.1;
        }
        
        /* User profile dropdown */
        .navbar .dropdown-toggle {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .navbar .dropdown-toggle::after {
            border: none;
            content: '\f107';
            font-family: 'Font Awesome 5 Free';
            font-weight: 900;
            margin-left: 0.5rem;
            transition: transform 0.3s ease;
        }
        
        .navbar .dropdown-toggle[aria-expanded="true"]::after {
            transform: rotate(180deg);
        }
        
        /* Note color preferences */
        .note-color-white .note-card,
        .note-color-white .card.h-100,
        .note-color-white .card-body {
            background-color: #ffffff !important;
        }
        .note-color-blue .note-card,
        .note-color-blue .card.h-100,
        .note-color-blue .card-body {
            background-color: #f0f5ff !important;
        }
        .note-color-green .note-card,
        .note-color-green .card.h-100,
        .note-color-green .card-body {
            background-color: #f0fff5 !important;
        }
        .note-color-yellow .note-card,
        .note-color-yellow .card.h-100,
        .note-color-yellow .card-body {
            background-color: #fffbeb !important;
        }
        .note-color-purple .note-card,
        .note-color-purple .card.h-100,
        .note-color-purple .card-body {
            background-color: #f8f0ff !important;
        }
        .note-color-pink .note-card,
        .note-color-pink .card.h-100,
        .note-color-pink .card-body {
            background-color: #fff0f7 !important;
        }

        /* Dark mode adjustments for note colors */
        [data-bs-theme="dark"] {
            --bs-body-bg: #121212;
            --bs-body-color: #e9ecef;
        }
        
        [data-bs-theme="dark"] .navbar-dark {
            background: linear-gradient(135deg, #344cb5, #2a3d99);
        }
        
        [data-bs-theme="dark"] .dropdown-menu {
            background-color: #212529;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        [data-bs-theme="dark"] .dropdown-item {
            color: #e9ecef;
        }
        
        [data-bs-theme="dark"] .dropdown-item:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: #fff;
        }
        
        [data-bs-theme="dark"] .card {
            background-color: #1e1e1e;
            border-color: rgba(255, 255, 255, 0.1);
        }
        
        [data-bs-theme="dark"] .note-color-white .note-card,
        [data-bs-theme="dark"] .note-color-white .card.h-100,
        [data-bs-theme="dark"] .note-color-white .card-body {
            background-color: #2b2b2b !important;
        }
        [data-bs-theme="dark"] .note-color-blue .note-card,
        [data-bs-theme="dark"] .note-color-blue .card.h-100,
        [data-bs-theme="dark"] .note-color-blue .card-body {
            background-color: #1a2035 !important;
        }
        [data-bs-theme="dark"] .note-color-green .note-card,
        [data-bs-theme="dark"] .note-color-green .card.h-100,
        [data-bs-theme="dark"] .note-color-green .card-body {
            background-color: #1a2e22 !important;
        }
        [data-bs-theme="dark"] .note-color-yellow .note-card,
        [data-bs-theme="dark"] .note-color-yellow .card.h-100,
        [data-bs-theme="dark"] .note-color-yellow .card-body {
            background-color: #2e2a1a !important;
        }
        [data-bs-theme="dark"] .note-color-purple .note-card,
        [data-bs-theme="dark"] .note-color-purple .card.h-100,
        [data-bs-theme="dark"] .note-color-purple .card-body {
            background-color: #25192e !important;
        }
        [data-bs-theme="dark"] .note-color-pink .note-card,
        [data-bs-theme="dark"] .note-color-pink .card.h-100,
        [data-bs-theme="dark"] .note-color-pink .card-body {
            background-color: #2e1923 !important;
        }
        
        /* Enhanced notification badge */
        .notification-badge {
            position: absolute;
            top: 0.2rem;
            right: 0.1rem;
            transform: translate(25%, -25%);
            padding: 0.25rem 0.45rem;
            font-size: 0.75rem;
            border-radius: 50%;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(229, 56, 59, 0.7); }
            70% { box-shadow: 0 0 0 6px rgba(229, 56, 59, 0); }
            100% { box-shadow: 0 0 0 0 rgba(229, 56, 59, 0); }
        }
        
        /* Responsive navbar adjustments */
        @media (max-width: 991.98px) {
            .navbar-collapse {
                background-color: rgba(0, 0, 0, 0.05);
                border-radius: var(--border-radius);
                padding: 1rem;
                margin-top: 1rem;
            }
            
            .navbar-nav .nav-link.active::after {
                bottom: 4px;
            }
        }
    </style>
    
    <script>
    // Make PHP constants available to JavaScript
    const BASE_URL = "<?= BASE_URL ?>";
    
    // Make user preferences available to JavaScript
    const USER_PREFERENCES = {
        theme: "<?= $theme ?>",
        font_size: "<?= $font_size ?>",
        note_color: "<?= $note_color ?>"
    };
    
    <?php if (Session::isLoggedIn()): ?>
    const USER_ID = <?= Session::getUserId() ?>;
    const ENABLE_WEBSOCKETS = <?= defined('ENABLE_WEBSOCKETS') && ENABLE_WEBSOCKETS ? 'true' : 'false' ?>;
    <?php endif; ?>
    </script>
</head>
<body class="d-flex flex-column min-vh-100 <?= $font_size_class ?> note-color-<?= $note_color ?>" data-bs-theme="<?= $theme ?>">
    <?php if (Session::isLoggedIn()): ?>
        <header>
            <nav class="navbar navbar-expand-lg navbar-dark">
                <div class="container">
                    <a class="navbar-brand" href="<?= BASE_URL ?>">
                        <i class="fas fa-sticky-note"></i>
                        <span><?= APP_NAME ?></span>
                    </a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarMain">
                        <ul class="navbar-nav me-auto">
                            <li class="nav-item">
                                <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/notes') !== false && !strpos($_SERVER['REQUEST_URI'], '/notes/shared') ? 'active' : '' ?>" href="<?= BASE_URL ?>/notes">
                                    <i class="fas fa-sticky-note me-1"></i> My Notes
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/notes/shared') !== false ? 'active' : '' ?>" href="<?= BASE_URL ?>/notes/shared">
                                    <i class="fas fa-share-alt me-1"></i> Shared Notes
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/labels') !== false ? 'active' : '' ?>" href="<?= BASE_URL ?>/labels">
                                    <i class="fas fa-tags me-1"></i> Labels
                                </a>
                            </li>
                        </ul>
                        <ul class="navbar-nav">
                            <!-- Include the notification dropdown -->
                            <?php include VIEWS_PATH . '/components/notification-dropdown.php'; ?>
                            
                            <!-- User Profile Dropdown -->
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <?php if (!empty(Session::get('user_display_name'))): ?>
                                        <span class="d-none d-sm-inline"><?= htmlspecialchars(Session::get('user_display_name')) ?></span>
                                    <?php endif; ?>
                                    <i class="fas fa-user-circle"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="<?= BASE_URL ?>/profile">
                                            <i class="fas fa-user"></i> My Profile
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="<?= BASE_URL ?>/profile/preferences">
                                            <i class="fas fa-cog"></i> Preferences
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item" href="<?= BASE_URL ?>/logout">
                                            <i class="fas fa-sign-out-alt"></i> Logout
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </header>
    <?php endif; ?>
    
    <?php 
    // Notification for unverified accounts
    if (Session::isLoggedIn()): 
        $user = (new User())->getUserById(Session::getUserId());
        if ($user && !$user['is_activated']):
    ?>
    <div class="alert alert-warning text-center mb-0 rounded-0 d-flex align-items-center justify-content-center py-2">
        <i class="fas fa-exclamation-triangle me-2"></i>
        <span>Your account is not verified. Please check your email to complete the activation process.</span>
        <form action="<?= BASE_URL ?>/resend-activation" method="POST" class="d-inline ms-2">
            <input type="hidden" name="resend" value="1">
            <button type="submit" class="btn btn-link alert-link p-0 d-inline text-decoration-underline">Resend activation email</button>
        </form>
    </div>
    <?php 
        endif;
    endif;
    ?>
    
    <main class="flex-grow-1 py-4">
        <div class="container"><?= PHP_EOL ?>