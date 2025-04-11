<?php
require_once MODELS_PATH . '/User.php';
require_once MODELS_PATH . '/Note.php';
require_once MODELS_PATH . '/Label.php';
require_once MODELS_PATH . '/SharedNote.php';

class ProfileController {
    private $user;
    private $note;
    private $label;
    private $sharedNote;
    
    public function __construct() {
        $this->user = new User();
        $this->note = new Note();
        $this->label = new Label();
        $this->sharedNote = new SharedNote();
    }
    
    // Display user profile
    public function index() {
        // Get user data
        $user_id = Session::getUserId();
        $user = $this->user->getUserById($user_id);
        
        if (!$user) {
            Session::setFlash('error', 'User not found');
            header('Location: ' . BASE_URL);
            exit;
        }
        
        // Get actual statistics
        $stats = $this->getUserStatistics($user_id);
        
        // Set page data
        $data = [
            'pageTitle' => 'My Profile',
            'user' => $user,
            'stats' => $stats
        ];
        
        // Load view
        include VIEWS_PATH . '/components/header.php';
        include VIEWS_PATH . '/profile/view.php';
        include VIEWS_PATH . '/components/footer.php';
    }
    
    // Get user statistics from database
    private function getUserStatistics($user_id) {
        // Count total notes
        $totalNotes = $this->countUserNotes($user_id);
        
        // Count total labels
        $totalLabels = $this->countUserLabels($user_id);
        
        // Count shared notes
        $sharedNotes = $this->countSharedNotes($user_id);
        
        // Count uploaded images
        $uploadedImages = $this->countUploadedImages($user_id);
        
        return [
            'total_notes' => $totalNotes,
            'total_labels' => $totalLabels,
            'shared_notes' => $sharedNotes,
            'uploaded_images' => $uploadedImages
        ];
    }
    
    // Count user's notes
    private function countUserNotes($user_id) {
        $db = getDB();
        $stmt = $db->prepare("SELECT COUNT(*) as count FROM notes WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['count'];
    }
    
    // Count user's labels
    private function countUserLabels($user_id) {
        $db = getDB();
        $stmt = $db->prepare("SELECT COUNT(*) as count FROM labels WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['count'];
    }
    
    // Count notes shared with user
    private function countSharedNotes($user_id) {
        $db = getDB();
        $stmt = $db->prepare("SELECT COUNT(*) as count FROM shared_notes WHERE recipient_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['count'];
    }
    
    // Count images uploaded by user
    private function countUploadedImages($user_id) {
        $db = getDB();
        $stmt = $db->prepare("
            SELECT COUNT(*) as count FROM images 
            WHERE note_id IN (SELECT id FROM notes WHERE user_id = ?)
        ");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['count'];
    }
    
    // Display and process profile edit form
    public function edit() {
        // Get user data
        $user_id = Session::getUserId();
        $user = $this->user->getUserById($user_id);
        
        if (!$user) {
            Session::setFlash('error', 'User not found');
            header('Location: ' . BASE_URL);
            exit;
        }
        
        // Set default data
        $data = [
            'pageTitle' => 'Edit Profile',
            'user' => $user,
            'errors' => []
        ];
        
        // Process form submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $display_name = trim($_POST['display_name'] ?? '');
            
            // Validate input
            if (empty($display_name)) {
                $data['errors']['display_name'] = 'Display name is required';
            }
            
            // If no errors, update profile
            if (empty($data['errors'])) {
                // Update display name function would need to be added to User model
                $result = $this->user->updateProfile($user_id, $display_name);
                
                if ($result['success']) {
                    Session::setFlash('success', 'Profile updated successfully');
                    header('Location: ' . BASE_URL . '/profile');
                    exit;
                } else {
                    $data['errors']['general'] = $result['message'];
                }
            }
            
            // Update user data for form
            $data['user']['display_name'] = $display_name;
        }
        
        // Load view
        include VIEWS_PATH . '/components/header.php';
        include VIEWS_PATH . '/profile/edit.php';
        include VIEWS_PATH . '/components/footer.php';
    }
    
    // Display and process change password form
    public function changePassword() {
        // Get user data
        $user_id = Session::getUserId();
        
        // Set default data
        $data = [
            'pageTitle' => 'Change Password',
            'errors' => []
        ];
        
        // Process form submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $current_password = $_POST['current_password'] ?? '';
            $new_password = $_POST['new_password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';
            
            // Validate input
            if (empty($current_password)) {
                $data['errors']['current_password'] = 'Current password is required';
            }
            
            if (empty($new_password)) {
                $data['errors']['new_password'] = 'New password is required';
            } elseif (strlen($new_password) < 8) {
                $data['errors']['new_password'] = 'Password must be at least 8 characters';
            }
            
            if ($new_password !== $confirm_password) {
                $data['errors']['confirm_password'] = 'Passwords do not match';
            }
            
            // If no errors, change password
            if (empty($data['errors'])) {
                // Need to add changePassword method to User model
                $result = $this->user->changePassword($user_id, $current_password, $new_password);
                
                if ($result['success']) {
                    Session::setFlash('success', 'Password changed successfully');
                    header('Location: ' . BASE_URL . '/profile');
                    exit;
                } else {
                    $data['errors']['general'] = $result['message'];
                }
            }
        }
        
        // Load view
        include VIEWS_PATH . '/components/header.php';
        include VIEWS_PATH . '/profile/change-password.php';
        include VIEWS_PATH . '/components/footer.php';
    }
    
    // Display and process user preferences form
    public function preferences() {
        // Set default data
        $data = [
            'pageTitle' => 'Preferences',
            'errors' => []
        ];
        
        // Load view
        include VIEWS_PATH . '/components/header.php';
        include VIEWS_PATH . '/profile/preferences.php';
        include VIEWS_PATH . '/components/footer.php';
    }

    public function savePreferences() {
        $user_id = Session::getUserId();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $theme = isset($_POST['theme']) ? $_POST['theme'] : 'light';
            $font_size = isset($_POST['font_size']) ? $_POST['font_size'] : 'medium';
            $note_color = isset($_POST['note_color']) ? $_POST['note_color'] : 'white';
            
            $preferences = [
                'font_size' => $font_size,
                'theme' => $theme,
                'note_color' => $note_color
            ];
            
            $result = $this->user->updatePreferences($user_id, $preferences);
            
            if ($result['success']) {
                Session::setFlash('success', 'Preferences updated successfully');
            } else {
                Session::setFlash('error', $result['message']);
            }
            
            header('Location: ' . BASE_URL . '/profile/preferences');
            exit;
        }
    }
    
    public function uploadAvatar() {
        $user_id = Session::getUserId();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Create uploads directory if it doesn't exist
            $avatars_dir = ROOT_PATH . '/uploads/avatars';
            if (!file_exists($avatars_dir)) {
                mkdir($avatars_dir, 0755, true);
            }
            
            // Handle avatar upload
            if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
                $allowed_types = ['image/jpeg', 'image/jpg', 'image/png'];
                $max_size = 2 * 1024 * 1024; // 2MB
                
                $file = $_FILES['avatar'];
                
                // Validate file type
                if (!in_array($file['type'], $allowed_types)) {
                    Session::setFlash('error', 'Invalid file type. Only JPEG and PNG are allowed.');
                    header('Location: ' . BASE_URL . '/profile/edit');
                    exit;
                }
                
                // Validate file size
                if ($file['size'] > $max_size) {
                    Session::setFlash('error', 'File is too large. Maximum size is 2MB.');
                    header('Location: ' . BASE_URL . '/profile/edit');
                    exit;
                }
                
                // Generate unique filename
                $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
                $new_filename = 'avatar_' . $user_id . '_' . uniqid() . '.' . $extension;
                $destination = $avatars_dir . '/' . $new_filename;
                
                // Move uploaded file
                if (move_uploaded_file($file['tmp_name'], $destination)) {
                    // Get current avatar to delete
                    $user = $this->user->getUserById($user_id);
                    if ($user && !empty($user['avatar_path'])) {
                        $old_avatar = $avatars_dir . '/' . $user['avatar_path'];
                        if (file_exists($old_avatar)) {
                            unlink($old_avatar);
                        }
                    }
                    
                    // Update database
                    $result = $this->user->updateAvatar($user_id, $new_filename);
                    
                    if ($result['success']) {
                        Session::setFlash('success', 'Avatar updated successfully');
                    } else {
                        Session::setFlash('error', $result['message']);
                    }
                } else {
                    Session::setFlash('error', 'Failed to upload avatar');
                }
            } else if (isset($_POST['remove_avatar']) && $_POST['remove_avatar'] === '1') {
                // Remove avatar
                $user = $this->user->getUserById($user_id);
                if ($user && !empty($user['avatar_path'])) {
                    $avatar_path = $avatars_dir . '/' . $user['avatar_path'];
                    if (file_exists($avatar_path)) {
                        unlink($avatar_path);
                    }
                }
                
                // Update database
                $result = $this->user->removeAvatar($user_id);
                
                if ($result['success']) {
                    Session::setFlash('success', 'Avatar removed successfully');
                } else {
                    Session::setFlash('error', $result['message']);
                }
            }
            
            header('Location: ' . BASE_URL . '/profile/edit');
            exit;
        }
    }
}