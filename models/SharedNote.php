<?php
class SharedNote {
    private $db;
    
    public function __construct() {
        $this->db = getDB();
    }
    
    // Share a note with another user
    public function shareNote($note_id, $owner_id, $recipient_email, $can_edit = false) {
        // First, check if the note exists and belongs to the owner
        $stmt = $this->db->prepare("SELECT id FROM notes WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ii", $note_id, $owner_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            return [
                'success' => false,
                'message' => 'Note not found or you are not the owner'
            ];
        }
        
        // Find the recipient user by email
        $stmt = $this->db->prepare("SELECT id, display_name FROM users WHERE email = ?");
        $stmt->bind_param("s", $recipient_email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            return [
                'success' => false,
                'message' => 'Recipient user not found'
            ];
        }
        
        $recipient = $result->fetch_assoc();
        $recipient_id = $recipient['id'];
        $recipient_name = $recipient['display_name'];
        
        // Cannot share with yourself
        if ($recipient_id == $owner_id) {
            return [
                'success' => false,
                'message' => 'You cannot share a note with yourself'
            ];
        }
        
        // Check if already shared with this user
        $stmt = $this->db->prepare("SELECT id FROM shared_notes WHERE note_id = ? AND recipient_id = ?");
        $stmt->bind_param("ii", $note_id, $recipient_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            // Update sharing permissions
            $share = $result->fetch_assoc();
            $share_id = $share['id'];
            
            $stmt = $this->db->prepare("UPDATE shared_notes SET can_edit = ? WHERE id = ?");
            $can_edit_int = $can_edit ? 1 : 0;
            $stmt->bind_param("ii", $can_edit_int, $share_id);
            
            if ($stmt->execute()) {
                return [
                    'success' => true,
                    'message' => 'Sharing permissions updated successfully'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Failed to update sharing permissions: ' . $stmt->error
                ];
            }
        }
        
        // Insert new share
        $stmt = $this->db->prepare("INSERT INTO shared_notes (note_id, owner_id, recipient_id, can_edit) VALUES (?, ?, ?, ?)");
        $can_edit_int = $can_edit ? 1 : 0;
        $stmt->bind_param("iiii", $note_id, $owner_id, $recipient_id, $can_edit_int);
        
        if ($stmt->execute()) {
            // Get note title for email
            $stmt = $this->db->prepare("SELECT title FROM notes WHERE id = ?");
            $stmt->bind_param("i", $note_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $note = $result->fetch_assoc();
            
            // Get owner's name
            $stmt = $this->db->prepare("SELECT display_name FROM users WHERE id = ?");
            $stmt->bind_param("i", $owner_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $owner = $result->fetch_assoc();
            
            // Send email notification
            if (function_exists('sendNoteSharedEmail')) {
                sendNoteSharedEmail(
                    $recipient_email,
                    $recipient_name,
                    $owner['display_name'],
                    $note['title'],
                    $can_edit ? 'edit' : 'view'
                );
            }
            
            return [
                'success' => true,
                'share_id' => $stmt->insert_id
            ];
        }
        
        return [
            'success' => false,
            'message' => 'Failed to share note: ' . $stmt->error
        ];
    }
    
    // Get all shares for a note
    public function getNoteShares($note_id) {
        $stmt = $this->db->prepare("
            SELECT s.*, u.email as recipient_email, u.display_name as recipient_name
            FROM shared_notes s
            JOIN users u ON s.recipient_id = u.id
            WHERE s.note_id = ?
            ORDER BY s.shared_at DESC
        ");
        $stmt->bind_param("i", $note_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $shares = [];
        while ($row = $result->fetch_assoc()) {
            $shares[] = $row;
        }
        
        return $shares;
    }
    
    // Remove sharing
    public function removeShare($share_id, $owner_id) {
        // First check if the share exists and user is the owner
        $stmt = $this->db->prepare("SELECT id FROM shared_notes WHERE id = ? AND owner_id = ?");
        $stmt->bind_param("ii", $share_id, $owner_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            return [
                'success' => false,
                'message' => 'Share not found or you are not the owner'
            ];
        }
        
        // Remove the share
        $stmt = $this->db->prepare("DELETE FROM shared_notes WHERE id = ?");
        $stmt->bind_param("i", $share_id);
        
        if ($stmt->execute()) {
            return [
                'success' => true
            ];
        }
        
        return [
            'success' => false,
            'message' => 'Failed to remove share: ' . $stmt->error
        ];
    }
    
    // Get notes shared with user
    public function getNotesSharedWithUser($user_id) {
        // Fixed query to avoid column errors - use a simpler query
        $stmt = $this->db->prepare("
            SELECT n.*, u.display_name as owner_name, u.email as owner_email, s.shared_at  
            FROM notes n
            JOIN shared_notes s ON n.id = s.note_id
            JOIN users u ON s.owner_id = u.id
            WHERE s.recipient_id = ?
            ORDER BY s.shared_at DESC
        ");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $notes = [];
        while ($row = $result->fetch_assoc()) {
            $notes[] = $row;
        }
        
        return $notes;
    }
    
    // Check if a note is shared with a user
    public function isSharedWithUser($note_id, $user_id) {
        $stmt = $this->db->prepare("SELECT 1 FROM shared_notes WHERE note_id = ? AND recipient_id = ?");
        $stmt->bind_param("ii", $note_id, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->num_rows > 0;
    }
    
    // Check if a user can edit a shared note
    public function canEditSharedNote($note_id, $user_id) {
        $stmt = $this->db->prepare("SELECT can_edit FROM shared_notes WHERE note_id = ? AND recipient_id = ?");
        $stmt->bind_param("ii", $note_id, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return (bool) $row['can_edit'];
        }
        
        return false;
    }

    // Update sharing permissions
    public function updateSharePermissions($share_id, $owner_id, $can_edit) {
        // First check if the share exists and user is the owner
        $stmt = $this->db->prepare("SELECT note_id, recipient_id FROM shared_notes WHERE id = ? AND owner_id = ?");
        $stmt->bind_param("ii", $share_id, $owner_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            return [
                'success' => false,
                'message' => 'Share not found or you are not the owner'
            ];
        }
        
        $share = $result->fetch_assoc();
        $can_edit_int = $can_edit ? 1 : 0;
        
        // Update the permissions
        $stmt = $this->db->prepare("UPDATE shared_notes SET can_edit = ? WHERE id = ?");
        $stmt->bind_param("ii", $can_edit_int, $share_id);
        
        if ($stmt->execute()) {
            // Get recipient information for notification
            $stmt = $this->db->prepare("SELECT email, display_name FROM users WHERE id = ?");
            $stmt->bind_param("i", $share['recipient_id']);
            $stmt->execute();
            $result = $stmt->get_result();
            $recipient = $result->fetch_assoc();
            
            // Get note information
            $stmt = $this->db->prepare("SELECT title FROM notes WHERE id = ?");
            $stmt->bind_param("i", $share['note_id']);
            $stmt->execute();
            $result = $stmt->get_result();
            $note = $result->fetch_assoc();
            
            // Get owner's name
            $stmt = $this->db->prepare("SELECT display_name FROM users WHERE id = ?");
            $stmt->bind_param("i", $owner_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $owner = $result->fetch_assoc();
            
            // Send email notification about permission change
            if (function_exists('sendSharePermissionChangedEmail')) {
                sendSharePermissionChangedEmail(
                    $recipient['email'],
                    $recipient['display_name'],
                    $owner['display_name'],
                    $note['title'],
                    $can_edit ? 'edit' : 'view'
                );
            }
            
            return [
                'success' => true,
                'message' => 'Share permissions updated successfully'
            ];
        }
        
        return [
            'success' => false,
            'message' => 'Failed to update permissions: ' . $stmt->error
        ];
    }
}