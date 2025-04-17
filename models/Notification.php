<?php
class Notification {
    private $db;
    
    public function __construct() {
        $this->db = getDB();
        // Ensure notifications table exists
        $this->ensureNotificationsTable();
    }
    
    // Create notifications table if it doesn't exist
    private function ensureNotificationsTable() {
        // Check if table exists
        $result = $this->db->query("SHOW TABLES LIKE 'notifications'");
        if ($result->num_rows == 0) {
            // Create notifications table
            $this->db->query("
                CREATE TABLE IF NOT EXISTS `notifications` (
                  `id` INT AUTO_INCREMENT PRIMARY KEY,
                  `user_id` INT NOT NULL,
                  `type` VARCHAR(50) NOT NULL,
                  `data` TEXT NOT NULL,
                  `is_read` TINYINT(1) DEFAULT 0,
                  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
                )
            ");
        }
    }
    
    // Get unread notifications for a user
    public function getUnreadNotifications($user_id) {
        // Check if table exists, create if not
        $this->ensureNotificationsTable();
        
        // For initial setup, return empty array if table is new
        $tableExists = $this->db->query("SHOW TABLES LIKE 'notifications'")->num_rows > 0;
        if (!$tableExists) {
            return [];
        }
        
        $stmt = $this->db->prepare("
            SELECT * FROM notifications 
            WHERE user_id = ? AND is_read = 0 
            ORDER BY created_at DESC
        ");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $notifications = [];
        while ($row = $result->fetch_assoc()) {
            $row['data'] = json_decode($row['data'], true);
            $notifications[] = $row;
        }
        
        return $notifications;
    }
    
    // Mark a notification as read
    public function markAsRead($notification_id, $user_id) {
        // Check if table exists, create if not
        $this->ensureNotificationsTable();
        
        $stmt = $this->db->prepare("
            UPDATE notifications
            SET is_read = 1
            WHERE id = ? AND user_id = ?
        ");
        $stmt->bind_param("ii", $notification_id, $user_id);
        return $stmt->execute();
    }
    
    // Mark all notifications as read for a user
    public function markAllAsRead($user_id) {
        // Check if table exists, create if not
        $this->ensureNotificationsTable();
        
        $stmt = $this->db->prepare("
            UPDATE notifications
            SET is_read = 1
            WHERE user_id = ?
        ");
        $stmt->bind_param("i", $user_id);
        return $stmt->execute();
    }
    
    // Count unread notifications for a user
    public function countUnread($user_id) {
        // Check if table exists, create if not
        $this->ensureNotificationsTable();
        
        // For initial setup, return 0 if table is new
        $tableExists = $this->db->query("SHOW TABLES LIKE 'notifications'")->num_rows > 0;
        if (!$tableExists) {
            return 0;
        }
        
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as count
            FROM notifications
            WHERE user_id = ? AND is_read = 0
        ");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        return $row['count'];
    }
    
    // Get all notifications for a user (for notifications page)
    public function getAllNotifications($user_id) {
        // Check if table exists, create if not
        $this->ensureNotificationsTable();
        
        // For initial setup, return empty array if table is new
        $tableExists = $this->db->query("SHOW TABLES LIKE 'notifications'")->num_rows > 0;
        if (!$tableExists) {
            return [];
        }
        
        $stmt = $this->db->prepare("
            SELECT * FROM notifications 
            WHERE user_id = ? 
            ORDER BY created_at DESC
        ");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $notifications = [];
        while ($row = $result->fetch_assoc()) {
            $row['data'] = json_decode($row['data'], true);
            $notifications[] = $row;
        }
        
        return $notifications;
    }
    
    // Create a notification
    public function create($user_id, $type, $data) {
        // Check if table exists, create if not
        $this->ensureNotificationsTable();
        
        // Serialize data
        $serialized_data = json_encode($data);
        
        // Insert notification
        $stmt = $this->db->prepare("
            INSERT INTO notifications (user_id, type, data, is_read, created_at) 
            VALUES (?, ?, ?, 0, NOW())
        ");
        $stmt->bind_param("iss", $user_id, $type, $serialized_data);
        return $stmt->execute();
    }
}