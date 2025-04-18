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
        <meta name="theme-color" content="#4a89dc">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        <meta name="apple-mobile-web-app-title" content="<?= APP_NAME ?>">
        <link rel="apple-touch-icon" href="<?= ASSETS_URL ?>/img/icon-192x192.png">
    <?php endif; ?>
    
    <!-- Enhanced styles for header and components -->
    <style>
        :root {
            --primary-color: #4a89dc;
            --primary-hover: #3a77c5;
            --secondary-color: #6c757d;
            --light-bg: #f8f9fa;
            --border-radius: 12px;
            --box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            --transition: all 0.3s ease;
        }
        
        body {
            background-color: #f0f2f5;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
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
        
        /* Navbar styling */
        .navbar {
            padding: 0.75rem 1rem;
            transition: var(--transition);
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
        }
        
        .navbar-brand {
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: var(--transition);
        }
        
        .navbar-brand:hover {
            transform: translateY(-2px);
        }
        
        .navbar-brand i {
            transition: transform 0.3s ease;
        }
        
        .navbar-brand:hover i {
            transform: rotate(-10deg);
        }
        
        .navbar-nav .nav-link {
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            transition: var(--transition);
            position: relative;
        }
        
        .navbar-nav .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background-color: currentColor;
            transition: all 0.3s ease;
            transform: translateX(-50%);
            opacity: 0;
        }
        
        .navbar-nav .nav-link:hover::after,
        .navbar-nav .nav-link.active::after {
            width: 60%;
            opacity: 1;
        }
        
        .navbar-nav .nav-link i {
            transition: transform 0.3s ease;
        }
        
        .navbar-nav .nav-link:hover i {
            transform: translateY(-2px);
        }
        
        /* Notification badge */
        .notification-badge {
            position: absolute;
            top: 0;
            right: 0;
            transform: translate(40%, -40%);
            transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        
        .nav-link:hover .notification-badge {
            transform: translate(40%, -40%) scale(1.1);
        }
        
        /* User dropdown menu */
        .dropdown-menu {
            border: none;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.12);
            overflow: hidden;
        }
        
        .dropdown-item {
            padding: 0.65rem 1.25rem;
            transition: var(--transition);
        }
        
        .dropdown-item:hover {
            background-color: rgba(74, 137, 220, 0.1);
            transform: translateX(5px);
        }
        
        .dropdown-item i {
            transition: transform 0.3s ease;
        }
        
        .dropdown-item:hover i {
            transform: translateY(-2px);
        }
        
        /* Notification dropdown */
        .notification-list {
            max-height: 360px;
            overflow-y: auto;
        }
        
        .notification-list::-webkit-scrollbar {
            width: 6px;
        }
        
        .notification-list::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        .notification-list::-webkit-scrollbar-thumb {
            background: #ccc;
            border-radius: 10px;
        }
        
        .notification-item {
            transition: var(--transition);
            border-left: 3px solid transparent;
        }
        
        .notification-item:hover {
            background-color: rgba(0, 0, 0, 0.02);
            border-left-color: var(--primary-color);
        }
        
        .notification-item.unread {
            background-color: rgba(74, 137, 220, 0.05);
        }
        
        .notification-icon {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            margin-right: 12px;
            flex-shrink: 0;
        }
        
        .notification-icon.share {
            background: linear-gradient(45deg, #4a89dc, #5a9cef);
        }
        
        .notification-icon.edit {
            background: linear-gradient(45deg, #17a2b8, #1fc8e3);
        }
        
        /* User avatar in the navbar */
        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid rgba(255, 255, 255, 0.5);
            transition: var(--transition);
        }
        
        .nav-link:hover .user-avatar {
            transform: scale(1.1);
            border-color: rgba(255, 255, 255, 0.8);
        }
        
        /* Alert customization */
        .alert {
            border-radius: 10px;
            border: none;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
        }
        
        .alert-warning {
            background-color: rgba(255, 193, 7, 0.15);
            color: #856404;
        }
        
        /* Animated wave for unverified account alert */
        .wave-alert {
            position: relative;
            overflow: hidden;
        }
        
        .wave-alert::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, 
                transparent, 
                rgba(255, 255, 255, 0.2), 
                transparent);
            animation: wave 2s infinite linear;
            z-index: 1;
        }
        
        @keyframes wave {
            0% { left: -100%; }
            100% { left: 100%; }
        }
        
        /* Responsive adjustments */
        @media (max-width: 991px) {
            .navbar-nav .nav-link {
                padding: 0.75rem 1rem;
            }
            
            .navbar-nav .nav-link::after {
                display: none;
            }
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
    </style>
</head>
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
<body class="d-flex flex-column min-vh-100 <?= $font_size_class ?> note-color-<?= $note_color ?>" data-bs-theme="<?= $theme ?>">
    <?php if (Session::isLoggedIn()): ?>
        <header>
            <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
                <div class="container">
                    <a class="navbar-brand d-flex align-items-center" href="<?= BASE_URL ?>">
                        <i class="fas fa-sticky-note me-2"></i>
                        <span><?= APP_NAME ?></span>
                    </a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
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
                            <li class="nav-item dropdown">
                                <a class="nav-link position-relative" href="#" id="notificationsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-bell"></i>
                                    <?php if ($unread_count > 0): ?>
                                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger notification-badge">
                                            <?= $unread_count ?>
                                            <span class="visually-hidden">unread notifications</span>
                                        </span>
                                    <?php endif; ?>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="notificationsDropdown" style="width: 320px; max-height: 500px; overflow-y: auto;">
                                    <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
                                        <h6 class="dropdown-header m-0 p-0 fw-bold">Notifications</h6>
                                        <?php if ($unread_count > 0): ?>
                                            <a href="<?= BASE_URL ?>/notifications/mark-all-read" class="btn btn-sm btn-primary rounded-pill px-3">
                                                <i class="fas fa-check-double me-1"></i> Mark all read
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <?php if (empty($unread_notifications)): ?>
                                        <div class="p-4 text-center text-muted">
                                            <div class="mb-3">
                                                <i class="fas fa-bell-slash fa-3x opacity-50"></i>
                                            </div>
                                            <p class="mb-0">No new notifications</p>
                                        </div>
                                    <?php else: ?>
                                        <div class="notification-list">
                                            <?php 
                                            // Group notifications by entity (note) to avoid duplicates
                                            $grouped_notifications = [];
                                            foreach ($unread_notifications as $notification) {
                                                if (!empty($notification['data']['note_id'])) {
                                                    $key = $notification['type'] . '-' . $notification['data']['note_id'];
                                                    // Keep only the most recent notification per note and type
                                                    if (!isset($grouped_notifications[$key]) || 
                                                        strtotime($notification['created_at']) > strtotime($grouped_notifications[$key]['created_at'])) {
                                                        $grouped_notifications[$key] = $notification;
                                                    }
                                                } else {
                                                    // For notifications without note_id, keep as is
                                                    $grouped_notifications[] = $notification;
                                                }
                                            }
                                            
                                            // Display the grouped notifications
                                            foreach ($grouped_notifications as $notification): 
                                            ?>
                                                <div class="dropdown-item p-3 border-bottom notification-item <?= !$notification['is_read'] ? 'unread' : '' ?>">
                                                    <?php if ($notification['type'] === 'new_shared_note'): ?>
                                                        <div class="d-flex">
                                                            <div class="notification-icon share">
                                                                <i class="fas fa-share-alt"></i>
                                                            </div>
                                                            <div>
                                                                <p class="mb-1 fw-bold">Note Shared With You</p>
                                                                <p class="mb-1 small">
                                                                    <strong><?= htmlspecialchars($notification['data']['owner_name']) ?></strong> shared 
                                                                    "<strong><?= htmlspecialchars($notification['data']['note_title']) ?></strong>"
                                                                </p>
                                                                <p class="text-muted small mb-2">
                                                                    <?= formatTimeAgo($notification['created_at']) ?>
                                                                </p>
                                                                <div class="d-flex mt-1">
                                                                    <a href="<?= BASE_URL ?>/notes/shared" class="btn btn-sm btn-primary me-2 rounded-pill">
                                                                        <i class="fas fa-eye me-1"></i> View
                                                                    </a>
                                                                    <a href="<?= BASE_URL ?>/notifications/mark-read/<?= $notification['id'] ?>" class="btn btn-sm btn-link text-decoration-none">
                                                                        Dismiss
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php elseif ($notification['type'] === 'share_permission_changed'): ?>
                                                        <div class="d-flex">
                                                            <div class="notification-icon edit">
                                                                <i class="fas fa-edit"></i>
                                                            </div>
                                                            <div>
                                                                <p class="mb-1 fw-bold">Permissions Updated</p>
                                                                <p class="mb-1 small">
                                                                    Your access to "<strong><?= htmlspecialchars($notification['data']['note_title']) ?></strong>" 
                                                                    is now <span class="<?= $notification['data']['permission'] === 'edit' ? 'text-success' : 'text-secondary' ?>">
                                                                        <?= $notification['data']['permission'] ?>
                                                                    </span>
                                                                </p>
                                                                <p class="text-muted small mb-2">
                                                                    <?= formatTimeAgo($notification['created_at']) ?>
                                                                </p>
                                                                <div class="d-flex mt-1">
                                                                    <a href="<?= BASE_URL ?>/notes/shared" class="btn btn-sm btn-primary me-2 rounded-pill">
                                                                        <i class="fas fa-eye me-1"></i> View
                                                                    </a>
                                                                    <a href="<?= BASE_URL ?>/notifications/mark-read/<?= $notification['id'] ?>" class="btn btn-sm btn-link text-decoration-none">
                                                                        Dismiss
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                        
                                        <div class="text-center p-2 border-top">
                                            <a href="<?= BASE_URL ?>/notifications" class="btn btn-sm btn-link text-decoration-none">
                                                View all notifications
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </li>
                            
                            <!-- User Profile Dropdown -->
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                    <?php if (!empty($userModel->getUserById(Session::getUserId())['avatar_path'])): ?>
                                        <img src="<?= BASE_URL ?>/uploads/avatars/<?= $userModel->getUserById(Session::getUserId())['avatar_path'] ?>" 
                                             alt="Avatar" class="user-avatar me-1">
                                    <?php else: ?>
                                        <i class="fas fa-user-circle me-1"></i>
                                    <?php endif; ?>
                                    <span class="d-none d-lg-inline"><?= htmlspecialchars(Session::get('user_display_name')) ?></span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="<?= BASE_URL ?>/profile">
                                            <i class="fas fa-user me-2"></i> My Profile
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="<?= BASE_URL ?>/profile/edit">
                                            <i class="fas fa-edit me-2"></i> Edit Profile
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="<?= BASE_URL ?>/profile/preferences">
                                            <i class="fas fa-cog me-2"></i> Preferences
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item text-danger" href="<?= BASE_URL ?>/logout">
                                            <i class="fas fa-sign-out-alt me-2"></i> Logout
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
    <div class="alert alert-warning text-center mb-0 rounded-0 wave-alert">
        <div class="d-flex align-items-center justify-content-center">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <span>Your account is not verified. Please check your email to complete the activation process.</span>
            <form action="<?= BASE_URL ?>/resend-activation" method="POST" class="d-inline ms-2">
                <input type="hidden" name="resend" value="1">
                <button type="submit" class="btn btn-sm btn-warning rounded-pill">Resend activation email</button>
            </form>
        </div>
    </div>
    <?php 
        endif;
    endif;
    ?>
    
    <main class="flex-grow-1 py-4">
        <div class="container"><?= PHP_EOL ?></document_content>
</invoke>