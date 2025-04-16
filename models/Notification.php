<?php
class Notification {
    private $db;
    
    public function __construct() {
        $this->db = getDB();
    }
    
    // Get unread notifications for a user
    public function getUnreadNotifications($user_id) {
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
}