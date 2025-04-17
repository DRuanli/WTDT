<?php
// Check if note is shared with current user
$user_id = Session::getUserId();
$is_shared = isset($data['note']['user_id']) && $data['note']['user_id'] != $user_id;
$can_edit = !$is_shared || (isset($data['note']['can_edit']) && $data['note']['can_edit']);
?>
<div class="row">
    <div class="col-md-10 mx-auto">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <?php if (isset($data['note']['id'])): ?>
                            <?php if ($is_shared): ?>
                                <?php if ($can_edit): ?>
                                    <i class="fas fa-edit text-primary me-2"></i>Edit Shared Note
                                    <span class="badge bg-success ms-2">Can Edit</span>
                                <?php else: ?>
                                    <i class="fas fa-eye text-primary me-2"></i>View Shared Note
                                    <span class="badge bg-secondary ms-2">Read Only</span>
                                <?php endif; ?>
                            <?php else: ?>
                                <i class="fas fa-edit text-primary me-2"></i>Edit Note
                            <?php endif; ?>
                        <?php else: ?>
                            <i class="fas fa-plus text-primary me-2"></i>Create Note
                        <?php endif; ?>
                    </h4>
                    <div>
                        <?php if (isset($data['note']['id']) && !$is_shared): ?>
                            <div class="btn-group me-2">
                                <a href="<?= BASE_URL ?>/notes/share/<?= $data['note']['id'] ?>" class="btn btn-outline-info">
                                    <i class="fas fa-share-alt me-1"></i> Share
                                </a>
                                <a href="<?= BASE_URL ?>/notes/toggle-password/<?= $data['note']['id'] ?>" class="btn btn-outline-warning">
                                    <?php if (isset($data['note']['is_password_protected']) && $data['note']['is_password_protected']): ?>
                                        <i class="fas fa-unlock me-1"></i> Remove Password
                                    <?php else: ?>
                                        <i class="fas fa-lock me-1"></i> Add Password
                                    <?php endif; ?>
                                </a>
                            </div>
                        <?php endif; ?>
                        <a href="<?= BASE_URL ?>/notes" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-1"></i> Cancel
                        </a>
                    </div>
                </div>
                
                <?php if ($is_shared): ?>
                <div class="mt-2 small text-muted">
                    <strong>Shared by:</strong> <?= htmlspecialchars($data['note']['owner_name'] ?? 'Unknown User') ?> &middot;
                    <strong>Shared on:</strong> <?php 
                        if (isset($data['note']['shared_at'])) {
                            $shared_at = new DateTime($data['note']['shared_at']);
                            echo $shared_at->format('M j, Y g:i A');
                        } else {
                            echo 'Unknown date';
                        }
                    ?>
                </div>
                <?php endif; ?>
            </div>
            
            <form id="note-form" method="POST" action="<?= isset($data['note']['id']) ? BASE_URL . '/notes/update/' . $data['note']['id'] : BASE_URL . '/notes/store' ?>" enctype="multipart/form-data">
                <?php if (!empty($data['errors']['general'])): ?>
                    <div class="alert alert-danger m-3">
                        <?= $data['errors']['general'] ?>
                    </div>
                <?php endif; ?>
                
                <div class="card-body">
                    <!-- Title Field -->
                    <div class="mb-3">
                        <input type="text" name="title" id="note-title" 
                               class="form-control form-control-lg border-0 shadow-none" 
                               placeholder="Note title" 
                               value="<?= htmlspecialchars($data['note']['title'] ?? '') ?>" 
                               required
                               <?= (!$can_edit) ? 'readonly' : '' ?>>
                        <?php if (!empty($data['errors']['title'])): ?>
                            <div class="invalid-feedback d-block"><?= $data['errors']['title'] ?></div>
                        <?php endif; ?>
                    </div>
                    
                    <?php if ($can_edit): ?>
                    <!-- Image Attachment Section -->
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label class="form-label mb-0"><i class="fas fa-image text-primary me-1"></i> Attach Images</label>
                            <label for="note-images" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-plus me-1"></i> Add Images
                            </label>
                            <input type="file" name="images[]" id="note-images" class="d-none" multiple accept="image/*">
                        </div>
                        
                        <div id="dropzone" class="dropzone border rounded-3 border-dashed p-4 text-center mb-3 <?= !empty($data['note']['images']) ? 'd-none' : '' ?>">
                            <i class="fas fa-cloud-upload-alt fs-3 mb-2 text-muted"></i>
                            <div class="text-muted">Drag and drop images here or click "Add Images" to browse</div>
                        </div>
                        
                        <!-- Preview of images to be uploaded -->
                        <div id="image-preview-container" class="d-none mb-3">
                            <div class="row row-cols-2 row-cols-md-4 g-2" id="image-previews"></div>
                        </div>
                        
                        <!-- Display existing images -->
                        <?php if (!empty($data['note']['images'])): ?>
                            <div class="image-gallery">
                                <div class="row row-cols-2 row-cols-md-4 g-2">
                                    <?php foreach ($data['note']['images'] as $image): ?>
                                        <div class="col">
                                            <div class="position-relative border rounded">
                                                <img src="<?= UPLOADS_URL . '/' . $image['file_path'] ?>" 
                                                     alt="<?= htmlspecialchars($image['file_name']) ?>"
                                                     class="img-fluid rounded">
                                                <div class="position-absolute bottom-0 start-0 end-0 bg-dark bg-opacity-50 text-white p-1 small text-truncate">
                                                    <?= htmlspecialchars($image['file_name']) ?>
                                                </div>
                                                <a href="<?= BASE_URL ?>/notes/delete-image/<?= $image['id'] ?>" 
                                                   class="position-absolute top-0 end-0 btn btn-sm btn-danger rounded-circle m-1 delete-image" 
                                                   data-id="<?= $image['id'] ?>">
                                                    <i class="fas fa-times"></i>
                                                </a>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($can_edit): ?>
                    <!-- Labels Section -->
                    <div class="mb-4">
                        <label class="form-label mb-2"><i class="fas fa-tag text-primary me-1"></i> Labels</label>
                        <?php if (empty($data['labels'])): ?>
                            <p class="text-muted small">No labels available. <a href="<?= BASE_URL ?>/labels">Create labels</a></p>
                        <?php else: ?>
                            <div class="row row-cols-2 row-cols-md-4 g-2">
                                <?php foreach ($data['labels'] as $label): ?>
                                    <div class="col">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" 
                                                   name="labels[]" 
                                                   id="label-<?= $label['id'] ?>" 
                                                   value="<?= $label['id'] ?>"
                                                   <?= isset($data['note']['labels']) && in_array($label['id'], $data['note']['labels']) ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="label-<?= $label['id'] ?>">
                                                <?= htmlspecialchars($label['name']) ?>
                                            </label>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Content Field -->
                    <div class="mb-3">
                        <label class="form-label mb-2"><i class="fas fa-align-left text-primary me-1"></i> Note Content</label>
                        <textarea name="content" id="note-content" 
                                  class="form-control" 
                                  placeholder="Write your note here..." 
                                  rows="12"
                                  <?= (!$can_edit) ? 'readonly' : '' ?>><?= htmlspecialchars($data['note']['content'] ?? '') ?></textarea>
                    </div>

                    <?php if ($is_shared && $can_edit): ?>
                    <div class="alert alert-info mt-3">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Real-time Collaboration:</strong> You're now in collaborative editing mode. Any changes you make will be visible to other users in real-time, and you'll see their changes and cursor positions as they edit.
                    </div>
                    <?php endif; ?>

                    <!-- Collaborators panel - Add this section -->
                    <div id="collaborators-panel" class="position-fixed top-0 end-0 p-3 d-none" style="z-index: 1050; margin-top: 80px;">
                        <div class="card shadow">
                            <div class="card-header bg-primary text-white py-2">
                                <h6 class="m-0"><i class="fas fa-users me-2"></i>Active Collaborators</h6>
                            </div>
                            <div class="card-body p-0">
                                <ul id="collaborators-list" class="list-group list-group-flush"></ul>
                            </div>
                        </div>
                    </div>

                    <!-- Real-time collaboration notification toast - Add this section -->
                    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1050; margin-bottom: 60px;">
                        <div id="collaborationToast" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true">
                            <div class="toast-header">
                                <i class="fas fa-users me-2 text-primary"></i>
                                <strong class="me-auto">Collaboration</strong>
                                <small>Just now</small>
                                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                            </div>
                            <div class="toast-body" id="collaborationToastBody">
                                Someone is editing this note.
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Collaboration participants view -->
<div id="collaborators-panel" class="position-fixed top-0 end-0 p-3 d-none" style="z-index: 1050; margin-top: 80px;">
    <div class="card shadow">
        <div class="card-header bg-primary text-white py-2">
            <h6 class="m-0"><i class="fas fa-users me-2"></i>Active Collaborators</h6>
        </div>
        <div class="card-body p-0">
            <ul id="collaborators-list" class="list-group list-group-flush"></ul>
        </div>
    </div>
</div>

<!-- Auto-save indicator -->
<div id="save-status" class="position-fixed bottom-0 end-0 m-3 p-2 px-3 rounded toast align-items-center" role="alert" aria-live="assertive" aria-atomic="true" style="display: none;">
    <div class="d-flex">
        <div class="toast-body d-flex align-items-center">
            <span id="saving-icon" class="me-2"><i class="fas fa-circle-notch fa-spin"></i></span>
            <span id="saved-icon" class="me-2" style="display: none;"><i class="fas fa-check text-success"></i></span>
            <span id="save-message">Saving...</span>
        </div>
    </div>
</div>

<!-- Real-time collaboration notification toast -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1050; margin-bottom: 60px;">
    <div id="collaborationToast" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <i class="fas fa-users me-2 text-primary"></i>
            <strong class="me-auto">Collaboration</strong>
            <small>Just now</small>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body" id="collaborationToastBody">
            Someone is editing this note.
        </div>
    </div>
</div>

<!-- Add these styles for remote cursors -->
<style>
/* Remote cursor styles */
.remote-cursor {
    position: absolute;
    z-index: 100;
    pointer-events: none;
}

.remote-cursor-label {
    position: absolute;
    top: -20px;
    left: 0;
    background-color: #3498db;
    color: white;
    padding: 2px 5px;
    border-radius: 3px;
    font-size: 12px;
    white-space: nowrap;
}

.remote-cursor-caret {
    width: 2px;
    height: 20px;
    background-color: #3498db;
    animation: blink 1s infinite;
}

@keyframes blink {
    0%, 100% { opacity: 1; }
    50% { opacity: 0; }
}

/* Collaborators list styles */
#collaborators-list .user-avatar {
    width: 24px;
    height: 24px;
    font-size: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    color: white;
    margin-right: 8px;
}
</style>

<!-- Now add improved JavaScript for collaboration -->
<script>
// Initialize WebSocket connection
let noteWebsocket;
let isCollaborationEnabled = <?= ($is_shared && $can_edit) ? 'true' : 'false' ?>;
let remoteCursors = {};
let lastCursorPositions = {};
let currentCollaborators = {};
let noteId = <?= isset($data['note']['id']) ? $data['note']['id'] : '0' ?>;
let userId = <?= Session::getUserId() ?>;

// Connect to WebSocket server for collaboration
if (isCollaborationEnabled || <?= (isset($data['note']['id']) && !$is_shared) ? 'true' : 'false' ?>) {
    initWebsocket();
}

function initWebsocket() {
    if (!window.ENABLE_WEBSOCKETS) return;
    
    const protocol = window.location.protocol === 'https:' ? 'wss:' : 'ws:';
    const wsPort = 8080; // WebSocket server port
    const hostName = window.location.hostname;
    const wsUrl = `${protocol}//${hostName}:${wsPort}`;
    
    noteWebsocket = new WebSocket(wsUrl);
    
    noteWebsocket.onopen = function() {
        console.log('WebSocket connection established');
        
        // Authenticate with the server
        noteWebsocket.send(JSON.stringify({
            type: 'auth',
            user_id: userId,
            token: 'simple-auth-token' // In a real application, use a secure token
        }));
        
        // Subscribe to this note's updates
        setTimeout(() => {
            if (noteId > 0) {
                noteWebsocket.send(JSON.stringify({
                    type: 'subscribe',
                    note_id: noteId
                }));
            }
        }, 500); // Short delay to ensure authentication completes
    };
    
    noteWebsocket.onmessage = function(event) {
        try {
            const data = JSON.parse(event.data);
            
            switch (data.type) {
                case 'auth_response':
                    if (data.success) {
                        console.log('Authenticated with WebSocket server');
                    } else {
                        console.error('Authentication failed:', data.message);
                    }
                    break;
                    
                case 'note_updated':
                    if (data.user_id !== userId) {
                        handleRemoteUpdate(data);
                    }
                    break;
                    
                case 'cursor_position':
                    if (data.user_id !== userId) {
                        updateRemoteCursor(data);
                    }
                    break;
                    
                case 'user_joined':
                    if (data.user_id !== userId) {
                        showCollaborationToast(`${data.user_name} joined the note`);
                        currentCollaborators[data.user_id] = data.user_name;
                        updateCollaboratorsList();
                    }
                    break;
                    
                case 'user_left':
                    if (data.user_id !== userId) {
                        showCollaborationToast(`${data.user_name} left the note`);
                        delete currentCollaborators[data.user_id];
                        removeRemoteCursor(data.user_id);
                        updateCollaboratorsList();
                    }
                    break;
                
                case 'new_shared_notes':
                    // Handle notification for new shared notes
                    if (data.notes && data.notes.length > 0) {
                        showNotification("New shared notes", `You have ${data.notes.length} new shared note(s)`);
                    }
                    break;
            }
        } catch (error) {
            console.error('Error processing WebSocket message:', error);
        }
    };
    
    noteWebsocket.onclose = function() {
        console.log('WebSocket connection closed');
        
        // Attempt to reconnect after 5 seconds
        setTimeout(initWebsocket, 5000);
    };
    
    noteWebsocket.onerror = function(error) {
        console.error('WebSocket error:', error);
    };
    
    // Only set up editing events if we're in collaborative mode
    if (isCollaborationEnabled) {
        setupCollaborativeEditing();
    }
    
    // Clean up when leaving the page
    window.addEventListener('beforeunload', function() {
        if (noteWebsocket && noteWebsocket.readyState === WebSocket.OPEN && noteId > 0) {
            noteWebsocket.send(JSON.stringify({
                type: 'unsubscribe',
                note_id: noteId
            }));
        }
    });
}

// Set up collaborative editing
function setupCollaborativeEditing() {
    const titleInput = document.getElementById('note-title');
    const contentInput = document.getElementById('note-content');
    
    if (!titleInput || !contentInput) return;
    
    // Send updates when the content changes
    let lastUpdateTime = 0;
    const updateDebounceTime = 500; // 500ms debounce
    
    function debounce(func, wait) {
        let timeout;
        return function() {
            const context = this;
            const args = arguments;
            const later = function() {
                timeout = null;
                func.apply(context, args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
    
    const sendUpdate = debounce(function() {
        if (noteWebsocket && noteWebsocket.readyState === WebSocket.OPEN && noteId > 0) {
            const title = titleInput.value;
            const content = contentInput.value;
            
            noteWebsocket.send(JSON.stringify({
                type: 'note_update',
                note_id: noteId,
                title: title,
                content: content
            }));
            
            lastUpdateTime = Date.now();
        }
    }, updateDebounceTime);
    
    // Listen for changes in the editor
    titleInput.addEventListener('input', sendUpdate);
    contentInput.addEventListener('input', sendUpdate);
    
    // Send cursor position updates
    contentInput.addEventListener('click', sendCursorPosition);
    contentInput.addEventListener('keyup', sendCursorPosition);
    contentInput.addEventListener('select', sendCursorPosition);
}

// Handle remote updates to the note
function handleRemoteUpdate(data) {
    if (!isCollaborationEnabled) return;
    
    const titleInput = document.getElementById('note-title');
    const contentInput = document.getElementById('note-content');
    
    if (!titleInput || !contentInput) return;
    
    // Don't apply updates if the user is actively typing
    const isUserActiveInTitle = document.activeElement === titleInput;
    const isUserActiveInContent = document.activeElement === contentInput;
    
    // Update the title if provided and not currently being edited
    if (data.title && !isUserActiveInTitle) {
        titleInput.value = data.title;
    }
    
    // Update the content if not currently being edited
    if (data.content && !isUserActiveInContent) {
        // Save current scroll position
        const scrollTop = contentInput.scrollTop;
        
        // Update content
        contentInput.value = data.content;
        
        // Restore scroll position
        contentInput.scrollTop = scrollTop;
        
        // Show toast notification
        showCollaborationToast(`${data.user_name} updated the note`);
    }
}

// Send cursor position to server
function sendCursorPosition() {
    if (!isCollaborationEnabled || !noteWebsocket) return;
    
    const contentInput = document.getElementById('note-content');
    if (!contentInput) return;
    
    const position = contentInput.selectionStart;
    
    // Only send if position changed
    if (lastCursorPositions[userId] !== position) {
        lastCursorPositions[userId] = position;
        
        if (noteWebsocket.readyState === WebSocket.OPEN && noteId > 0) {
            noteWebsocket.send(JSON.stringify({
                type: 'cursor_position',
                note_id: noteId,
                position: position
            }));
        }
    }
}

// Update remote cursor position
function updateRemoteCursor(data) {
    if (!isCollaborationEnabled) return;
    
    const contentInput = document.getElementById('note-content');
    if (!contentInput) return;
    
    const userId = data.user_id;
    const position = data.position;
    const userName = data.user_name;
    
    // Generate a consistent color for this user
    const userColor = getColorForUser(userId);
    
    // Create or update cursor element
    let cursor = remoteCursors[userId];
    if (!cursor) {
        cursor = document.createElement('div');
        cursor.className = 'remote-cursor';
        cursor.innerHTML = `
            <div class="remote-cursor-label" style="background-color: ${userColor}">${userName}</div>
            <div class="remote-cursor-caret" style="background-color: ${userColor}"></div>
        `;
        document.body.appendChild(cursor);
        remoteCursors[userId] = cursor;
    }
    
    // Position the cursor at the right location in the textarea
    const coords = getCaretCoordinates(contentInput, position);
    const rect = contentInput.getBoundingClientRect();
    cursor.style.left = (rect.left + coords.left) + 'px';
    cursor.style.top = (rect.top + coords.top) + 'px';
    
    // Remember the position
    lastCursorPositions[userId] = position;
    
    // Add to current collaborators
    if (!currentCollaborators[userId]) {
        currentCollaborators[userId] = userName;
        updateCollaboratorsList();
    }
    
    // Set a timeout to remove cursor after inactivity
    if (cursor.timeout) clearTimeout(cursor.timeout);
    cursor.timeout = setTimeout(() => {
        removeRemoteCursor(userId);
    }, 10000); // 10 seconds
}

// Remove a remote cursor
function removeRemoteCursor(userId) {
    if (remoteCursors[userId]) {
        document.body.removeChild(remoteCursors[userId]);
        delete remoteCursors[userId];
    }
}

// Generate a consistent color for a user
function getColorForUser(userId) {
    // List of distinctive colors
    const colors = [
        '#e6194B', '#3cb44b', '#ffe119', '#4363d8', '#f58231', 
        '#911eb4', '#42d4f4', '#f032e6', '#bfef45', '#fabed4'
    ];
    
    // Use modulo to ensure we always get a valid index
    const colorIndex = parseInt(userId, 10) % colors.length;
    return colors[colorIndex];
}

// Update the collaborators list panel
function updateCollaboratorsList() {
    if (!isCollaborationEnabled) return;
    
    const collaboratorsPanel = document.getElementById('collaborators-panel');
    const collaboratorsList = document.getElementById('collaborators-list');
    
    if (!collaboratorsPanel || !collaboratorsList) return;
    
    // Show the panel if there are collaborators
    const collabCount = Object.keys(currentCollaborators).length;
    if (collabCount > 0) {
        collaboratorsPanel.classList.remove('d-none');
        
        // Update the list
        collaboratorsList.innerHTML = '';
        
        for (const [userId, userName] of Object.entries(currentCollaborators)) {
            const userColor = getColorForUser(userId);
            const li = document.createElement('li');
            li.className = 'list-group-item d-flex align-items-center py-2';
            
            // Get first letter of user's name for avatar
            const firstLetter = userName.charAt(0).toUpperCase();
            
            li.innerHTML = `
                <div class="user-avatar" style="background-color: ${userColor}">${firstLetter}</div>
                <span>${userName}</span>
            `;
            
            collaboratorsList.appendChild(li);
        }
    } else {
        collaboratorsPanel.classList.add('d-none');
    }
}

// Show toast notification for collaboration events
function showCollaborationToast(message) {
    const toast = document.getElementById('collaborationToast');
    const toastBody = document.getElementById('collaborationToastBody');
    
    if (toast && toastBody) {
        toastBody.textContent = message;
        
        // Use Bootstrap's toast API if available, otherwise manually show/hide
        if (typeof bootstrap !== 'undefined') {
            const bsToast = new bootstrap.Toast(toast);
            bsToast.show();
        } else {
            toast.classList.add('show');
            setTimeout(() => {
                toast.classList.remove('show');
            }, 3000);
        }
    }
}

// Show generic notification
function showNotification(title, message) {
    if ('Notification' in window && Notification.permission === 'granted') {
        new Notification(title, { body: message });
    } else if ('Notification' in window && Notification.permission !== 'denied') {
        Notification.requestPermission().then(permission => {
            if (permission === 'granted') {
                new Notification(title, { body: message });
            }
        });
    }
    
    // Also show as toast
    showCollaborationToast(message);
}

// Utility function to get caret coordinates in a textarea
function getCaretCoordinates(element, position) {
    // Create a dummy element to measure text dimensions
    const div = document.createElement('div');
    const styles = window.getComputedStyle(element);
    
    // Copy styles from textarea
    div.style.position = 'absolute';
    div.style.visibility = 'hidden';
    div.style.whiteSpace = 'pre-wrap';
    div.style.height = 'auto';
    div.style.width = element.offsetWidth + 'px';
    div.style.font = styles.font;
    div.style.padding = styles.padding;
    div.style.border = styles.border;
    div.style.boxSizing = styles.boxSizing;
    div.style.lineHeight = styles.lineHeight;
    
    document.body.appendChild(div);
    
    // Set content up to cursor position
    div.textContent = element.value.substring(0, position);
    
    // Add a span to mark cursor position
    const span = document.createElement('span');
    span.textContent = '.'; // Just need something to measure
    div.appendChild(span);
    
    // Get position
    const coordinates = {
        left: span.offsetLeft,
        top: span.offsetTop,
        height: span.offsetHeight
    };
    
    // Clean up
    document.body.removeChild(div);
    
    return coordinates;
}
</script>

<style>
/* Improved dropzone */
.dropzone {
    transition: all 0.3s ease;
    min-height: 120px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    cursor: pointer;
}

.dropzone:hover, .dropzone.dragover {
    background-color: rgba(0, 123, 255, 0.05);
    border-color: #007bff;
}

/* Image gallery */
.image-gallery {
    margin-bottom: 20px;
}

.image-gallery img {
    width: 100%;
    height: 150px;
    object-fit: cover;
}

/* Note content area */
#note-content {
    min-height: 300px;
    resize: vertical;
    border: 1px solid rgba(0, 0, 0, 0.1);
    border-radius: 4px;
}

#note-content:focus {
    border-color: #4a89dc;
    box-shadow: 0 0 0 0.2rem rgba(74, 137, 220, 0.25);
}

/* Animation for auto-save toast */
.toast {
    transition: opacity 0.3s ease;
}

/* Improved card styles */
.card {
    border: none;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}

.form-control:focus {
    box-shadow: none;
}

/* Remote cursor styles */
.remote-cursor {
    position: absolute;
    z-index: 100;
    pointer-events: none;
}

.remote-cursor-label {
    position: absolute;
    top: -20px;
    left: 0;
    background-color: #3498db;
    color: white;
    padding: 2px 5px;
    border-radius: 3px;
    font-size: 12px;
    white-space: nowrap;
}

.remote-cursor-caret {
    width: 2px;
    height: 20px;
    background-color: #3498db;
    animation: blink 1s infinite;
}

@keyframes blink {
    0%, 100% { opacity: 1; }
    50% { opacity: 0; }
}

/* Collaborators list styles */
#collaborators-list .user-avatar {
    width: 24px;
    height: 24px;
    font-size: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    color: white;
    margin-right: 8px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const noteForm = document.getElementById('note-form');
    const titleInput = document.getElementById('note-title');
    const contentInput = document.getElementById('note-content');
    const saveStatus = document.getElementById('save-status');
    const savingIcon = document.getElementById('saving-icon');
    const savedIcon = document.getElementById('saved-icon');
    const saveMessage = document.getElementById('save-message');
    const imageInput = document.getElementById('note-images');
    const previewContainer = document.getElementById('image-preview-container');
    const previewsDiv = document.getElementById('image-previews');
    const dropzone = document.getElementById('dropzone');
    const collaboratorsPanel = document.getElementById('collaborators-panel');
    const collaboratorsList = document.getElementById('collaborators-list');
    
    // Variables for auto-save
    let saveTimeout;
    let lastSavedContent = contentInput.value;
    let lastSavedTitle = titleInput.value;
    let autoSaveEnabled = true;
    
    // Show saving status
    function showSaveStatus(status, message) {
        saveStatus.style.display = 'block';
        
        if (status === 'saving') {
            savingIcon.style.display = 'inline-block';
            savedIcon.style.display = 'none';
            saveMessage.textContent = message || 'Saving...';
            saveStatus.classList.add('bg-dark', 'text-white');
            saveStatus.classList.remove('bg-success', 'bg-danger');
        } else if (status === 'saved') {
            savingIcon.style.display = 'none';
            savedIcon.style.display = 'inline-block';
            saveMessage.textContent = message || 'Saved';
            saveStatus.classList.remove('bg-dark', 'bg-danger');
            saveStatus.classList.add('bg-success', 'text-white');
            
            // Hide after 2 seconds
            setTimeout(() => {
                saveStatus.style.opacity = '0';
                setTimeout(() => {
                    saveStatus.style.display = 'none';
                    saveStatus.style.opacity = '1';
                }, 300);
            }, 2000);
        } else if (status === 'error') {
            savingIcon.style.display = 'none';
            savedIcon.style.display = 'none';
            saveMessage.textContent = message || 'Error saving';
            saveStatus.classList.remove('bg-dark', 'bg-success');
            saveStatus.classList.add('bg-danger', 'text-white');
            
            // Hide after 3 seconds
            setTimeout(() => {
                saveStatus.style.opacity = '0';
                setTimeout(() => {
                    saveStatus.style.display = 'none';
                    saveStatus.style.opacity = '1';
                }, 300);
            }, 3000);
        }
    }
    
    // Save changes
    function saveChanges() {
        // Auto-save if content has changed and title is not empty
        if ((lastSavedContent !== contentInput.value || lastSavedTitle !== titleInput.value) &&
            titleInput.value.trim() !== '') {
            
            // Show saving indicator
            showSaveStatus('saving');
            
            const formData = new FormData(noteForm);
            
            // Send AJAX request
            fetch(noteForm.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update last saved content
                    lastSavedContent = contentInput.value;
                    lastSavedTitle = titleInput.value;
                    
                    // Show saved indicator
                    showSaveStatus('saved');
                    
                    // If this was a new note, redirect to edit page for this note
                    if (data.note_id && !window.location.href.includes('/edit/')) {
                        window.location.href = BASE_URL + '/notes/edit/' + data.note_id;
                    }
                } else {
                    // Show error indicator
                    showSaveStatus('error', data.errors?.general || 'Error saving');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showSaveStatus('error', 'Network error');
            });
        }
    }
    
    // Auto-save functionality
    function autoSave() {
        // Clear any existing timeout
        clearTimeout(saveTimeout);
        
        // Set a new timeout to save after 1.5 seconds of inactivity
        saveTimeout = setTimeout(saveChanges, 1500);
    }
    
    // Add event listeners for auto-save
    if (titleInput && contentInput) {
        const isReadOnly = contentInput.hasAttribute('readonly');
        
        if (!isReadOnly) {
            titleInput.addEventListener('input', autoSave);
            contentInput.addEventListener('input', autoSave);
        }
    }
    
    // Add event listeners for label checkboxes
    const labelCheckboxes = document.querySelectorAll('input[name="labels[]"]');
    labelCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', autoSave);
    });
    
    // Image upload handling
    if (imageInput) {
        // Preview uploaded images
        imageInput.addEventListener('change', handleFileSelect);
        
        // Delete image
        const deleteImageLinks = document.querySelectorAll('.delete-image');
        deleteImageLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                
                if (confirm('Are you sure you want to delete this image?')) {
                    const imageId = this.getAttribute('data-id');
                    const imageElement = this.closest('.col');
                    
                    fetch(this.href, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Remove the image element
                            imageElement.remove();
                            
                            // Show the dropzone if no more images
                            const remainingImages = document.querySelectorAll('.image-gallery .col');
                            if (remainingImages.length === 0 && previewsDiv.children.length === 0) {
                                dropzone.classList.remove('d-none');
                            }
                            
                            // Auto-save after image deletion
                            autoSave();
                        } else {
                            alert('Error deleting image: ' + (data.message || 'Unknown error'));
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Network error while deleting image');
                    });
                }
            });
        });
    }
    
    // Handle file selection
    function handleFileSelect(event) {
        const files = event.target.files;
        
        if (files.length > 0) {
            previewContainer.classList.remove('d-none');
            dropzone.classList.add('d-none');
            
            previewsDiv.innerHTML = '';
            
            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    const preview = document.createElement('div');
                    preview.className = 'col';
                    
                    reader.onload = function(e) {
                        preview.innerHTML = `
                            <div class="position-relative border rounded">
                                <img src="${e.target.result}" class="img-fluid rounded" alt="${file.name}" style="height: 150px; object-fit: cover; width: 100%;">
                                <div class="position-absolute bottom-0 start-0 end-0 bg-dark bg-opacity-50 text-white p-1 small text-truncate">
                                    ${file.name}
                                </div>
                                <button type="button" class="position-absolute top-0 end-0 btn btn-sm btn-danger rounded-circle m-1 remove-preview">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        `;
                        
                        // Add event listener for remove button
                        const removeBtn = preview.querySelector('.remove-preview');
                        removeBtn.addEventListener('click', function() {
                            preview.remove();
                            
                            // Show dropzone if no more previews
                            if (previewsDiv.children.length === 0) {
                                const galleryImages = document.querySelectorAll('.image-gallery .col');
                                if (galleryImages.length === 0) {
                                    dropzone.classList.remove('d-none');
                                    previewContainer.classList.add('d-none');
                                }
                            }
                        });
                    };
                    
                    reader.readAsDataURL(file);
                    previewsDiv.appendChild(preview);
                }
            }
            
            // Auto-save after image upload
            autoSave();
        }
    }
    
    // Initialize drag and drop
    if (dropzone) {
        // Prevent default behavior to allow drop
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropzone.addEventListener(eventName, preventDefaults, false);
            document.body.addEventListener(eventName, preventDefaults, false);
        });
        
        // Highlight drop area when item is dragged over it
        ['dragenter', 'dragover'].forEach(eventName => {
            dropzone.addEventListener(eventName, highlight, false);
        });
        
        ['dragleave', 'drop'].forEach(eventName => {
            dropzone.addEventListener(eventName, unhighlight, false);
        });
        
        // Handle drop
        dropzone.addEventListener('drop', handleDrop, false);
        
        // Click on dropzone to select files
        dropzone.addEventListener('click', function() {
            imageInput.click();
        });
    }
    
    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }
    
    function highlight() {
        dropzone.classList.add('dragover');
    }
    
    function unhighlight() {
        dropzone.classList.remove('dragover');
    }
    
    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        
        // Create a new FileList-like object
        const dataTransfer = new DataTransfer();
        
        // Add the dropped files
        for (let i = 0; i < files.length; i++) {
            if (files[i].type.startsWith('image/')) {
                dataTransfer.items.add(files[i]);
            }
        }
        
        // Set the files in the input element
        imageInput.files = dataTransfer.files;
        
        // Handle the file selection
        handleFileSelect({target: {files: dataTransfer.files}});
    }
    
    // Add warning when leaving page with unsaved changes
    window.addEventListener('beforeunload', function(e) {
        if (autoSaveEnabled && 
            (lastSavedContent !== contentInput.value || lastSavedTitle !== titleInput.value) &&
            titleInput.value.trim() !== '') {
            // Auto-save before leaving page
            saveChanges();
            
            // Show warning if there are unsaved changes
            const message = 'You have unsaved changes. Are you sure you want to leave?';
            e.returnValue = message;
            return message;
        }
    });

    // Initialize WebSocket connection
    let noteWebsocket;
    let isCollaborationEnabled = <?= ($is_shared && $can_edit) ? 'true' : 'false' ?>;
    let remoteCursors = {};
    let lastCursorPositions = {};
    let currentCollaborators = {};
    let noteId = <?= isset($data['note']['id']) ? $data['note']['id'] : '0' ?>;
    let userId = <?= Session::getUserId() ?>;

    // Connect to WebSocket server for collaboration
    if (isCollaborationEnabled || <?= (isset($data['note']['id']) && !$is_shared) ? 'true' : 'false' ?>) {
        initWebsocket();
    }

    function initWebsocket() {
        if (!ENABLE_WEBSOCKETS) return;
        
        const protocol = window.location.protocol === 'https:' ? 'wss:' : 'ws:';
        const wsUrl = `${protocol}//${window.location.host}:8080`;
        
        noteWebsocket = new WebSocket(wsUrl);
        
        noteWebsocket.onopen = function() {
            console.log('WebSocket connection established');
            
            // Authenticate with the server
            noteWebsocket.send(JSON.stringify({
                type: 'auth',
                user_id: userId,
                token: 'simple-auth-token' // In a real application, use a secure token
            }));
            
            // Subscribe to this note's updates
            setTimeout(() => {
                if (noteId > 0) {
                    noteWebsocket.send(JSON.stringify({
                        type: 'subscribe',
                        note_id: noteId
                    }));
                }
            }, 500); // Short delay to ensure authentication completes
        };
        
        noteWebsocket.onmessage = function(event) {
            const data = JSON.parse(event.data);
            
            switch (data.type) {
                case 'auth_response':
                    if (data.success) {
                        console.log('Authenticated with WebSocket server');
                    } else {
                        console.error('Authentication failed:', data.message);
                    }
                    break;
                    
                case 'note_updated':
                    if (data.user_id !== userId) {
                        handleRemoteUpdate(data);
                    }
                    break;
                    
                case 'cursor_position':
                    if (data.user_id !== userId) {
                        updateRemoteCursor(data);
                    }
                    break;
                    
                case 'user_joined':
                    if (data.user_id !== userId) {
                        showCollaborationToast(`${data.user_name} joined the note`);
                        currentCollaborators[data.user_id] = data.user_name;
                        updateCollaboratorsList();
                    }
                    break;
                    
                case 'user_left':
                    if (data.user_id !== userId) {
                        showCollaborationToast(`${data.user_name} left the note`);
                        delete currentCollaborators[data.user_id];
                        removeRemoteCursor(data.user_id);
                        updateCollaboratorsList();
                    }
                    break;
            }
        };
        
        noteWebsocket.onclose = function() {
            console.log('WebSocket connection closed');
            
            // Attempt to reconnect after 5 seconds
            setTimeout(initWebsocket, 5000);
        };
        
        noteWebsocket.onerror = function(error) {
            console.error('WebSocket error:', error);
        };
        
        // Send updates when the content changes
        let lastUpdateTime = 0;
        
        function debounce(func, wait) {
            let timeout;
            return function() {
                const context = this;
                const args = arguments;
                const later = function() {
                    timeout = null;
                    func.apply(context, args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }
        
        const sendUpdate = debounce(function() {
            if (noteWebsocket.readyState === WebSocket.OPEN && noteId > 0) {
                const title = document.getElementById('note-title').value;
                const content = document.getElementById('note-content').value;
                
                noteWebsocket.send(JSON.stringify({
                    type: 'note_update',
                    note_id: noteId,
                    title: title,
                    content: content
                }));
                
                lastUpdateTime = Date.now();
            }
        }, 500);
        
        // Listen for changes in the editor if editing is allowed
        if (titleInput && !titleInput.readOnly) {
            titleInput.addEventListener('input', sendUpdate);
        }
        
        if (contentInput && !contentInput.readOnly) {
            contentInput.addEventListener('input', sendUpdate);
            
            // Send cursor position updates
            contentInput.addEventListener('click', sendCursorPosition);
            contentInput.addEventListener('keyup', sendCursorPosition);
            contentInput.addEventListener('select', sendCursorPosition);
        }
        
        // Clean up when leaving the page
        window.addEventListener('beforeunload', function() {
            if (noteWebsocket.readyState === WebSocket.OPEN && noteId > 0) {
                noteWebsocket.send(JSON.stringify({
                    type: 'unsubscribe',
                    note_id: noteId
                }));
            }
        });
    }

    // Handle remote updates to the note
    function handleRemoteUpdate(data) {
        if (!isCollaborationEnabled) return;
        
        // Don't apply updates if the user is actively typing
        const isUserActive = document.activeElement === titleInput || 
                            document.activeElement === contentInput;
        
        // Update the title if provided and not currently being edited
        if (data.title && document.activeElement !== titleInput) {
            titleInput.value = data.title;
        }
        
        // Update the content if not currently being edited
        if (data.content && document.activeElement !== contentInput) {
            // Save current scroll position
            const scrollTop = contentInput.scrollTop;
            
            // Update content
            contentInput.value = data.content;
            
            // Restore scroll position
            contentInput.scrollTop = scrollTop;
            
            // Show toast notification
            showCollaborationToast(`${data.user_name} updated the note`);
        }
    }

    // Send cursor position to server
    function sendCursorPosition() {
        if (!isCollaborationEnabled || !noteWebsocket) return;
        
        const position = contentInput.selectionStart;
        
        // Only send if position changed
        if (lastCursorPositions[userId] !== position) {
            lastCursorPositions[userId] = position;
            
            if (noteWebsocket.readyState === WebSocket.OPEN && noteId > 0) {
                noteWebsocket.send(JSON.stringify({
                    type: 'cursor_position',
                    note_id: noteId,
                    position: position
                }));
            }
        }
    }

    // Update remote cursor position
    function updateRemoteCursor(data) {
        if (!isCollaborationEnabled) return;
        
        const userId = data.user_id;
        const position = data.position;
        const userName = data.user_name;
        
        // Generate a consistent color for this user
        const userColor = getColorForUser(userId);
        
        // Create or update cursor element
        let cursor = remoteCursors[userId];
        if (!cursor) {
            cursor = document.createElement('div');
            cursor.className = 'remote-cursor';
            cursor.innerHTML = `
                <div class="remote-cursor-label" style="background-color: ${userColor}">${userName}</div>
                <div class="remote-cursor-caret" style="background-color: ${userColor}"></div>
            `;
            document.body.appendChild(cursor);
            remoteCursors[userId] = cursor;
        }
        
        // Position the cursor at the right location in the textarea
        const coords = getCaretCoordinates(contentInput, position);
        const rect = contentInput.getBoundingClientRect();
        cursor.style.left = (rect.left + coords.left) + 'px';
        cursor.style.top = (rect.top + coords.top) + 'px';
        
        // Remember the position
        lastCursorPositions[userId] = position;
        
        // Add to current collaborators
        if (!currentCollaborators[userId]) {
            currentCollaborators[userId] = userName;
            updateCollaboratorsList();
        }
        
        // Set a timeout to remove cursor after inactivity
        if (cursor.timeout) clearTimeout(cursor.timeout);
        cursor.timeout = setTimeout(() => {
            removeRemoteCursor(userId);
        }, 10000); // 10 seconds
    }

    // Remove a remote cursor
    function removeRemoteCursor(userId) {
        if (remoteCursors[userId]) {
            document.body.removeChild(remoteCursors[userId]);
            delete remoteCursors[userId];
        }
    }

    // Generate a consistent color for a user
    function getColorForUser(userId) {
        // List of distinctive colors
        const colors = [
            '#e6194B', '#3cb44b', '#ffe119', '#4363d8', '#f58231', 
            '#911eb4', '#42d4f4', '#f032e6', '#bfef45', '#fabed4'
        ];
        
        // Use modulo to ensure we always get a valid index
        const colorIndex = parseInt(userId, 10) % colors.length;
        return colors[colorIndex];
    }

    // Update the collaborators list panel
    function updateCollaboratorsList() {
        if (!isCollaborationEnabled) return;
        
        // Show the panel if there are collaborators
        const collabCount = Object.keys(currentCollaborators).length;
        if (collabCount > 0) {
            collaboratorsPanel.classList.remove('d-none');
            
            // Update the list
            collaboratorsList.innerHTML = '';
            
            for (const [userId, userName] of Object.entries(currentCollaborators)) {
                const userColor = getColorForUser(userId);
                const li = document.createElement('li');
                li.className = 'list-group-item d-flex align-items-center py-2';
                
                // Get first letter of user's name for avatar
                const firstLetter = userName.charAt(0).toUpperCase();
                
                li.innerHTML = `
                    <div class="user-avatar" style="background-color: ${userColor}">${firstLetter}</div>
                    <span>${userName}</span>
                `;
                
                collaboratorsList.appendChild(li);
            }
        } else {
            collaboratorsPanel.classList.add('d-none');
        }
    }

    // Show toast notification for collaboration events
    function showCollaborationToast(message) {
        const toast = document.getElementById('collaborationToast');
        const toastBody = document.getElementById('collaborationToastBody');
        
        if (toast && toastBody) {
            toastBody.textContent = message;
            
            // Use Bootstrap's toast API if available, otherwise manually show/hide
            if (typeof bootstrap !== 'undefined') {
                const bsToast = new bootstrap.Toast(toast);
                bsToast.show();
            } else {
                toast.classList.add('show');
                setTimeout(() => {
                    toast.classList.remove('show');
                }, 3000);
            }
        }
    }

    // Utility function to get caret coordinates in a textarea
    // Adapted from https://github.com/component/textarea-caret-position
    function getCaretCoordinates(element, position) {
        // Create a dummy element to measure text dimensions
        const div = document.createElement('div');
        const style = div.style;
        const computed = window.getComputedStyle(element);
        
        // Copy styles that affect text dimensions
        style.width = element.offsetWidth + 'px';
        style.height = element.offsetHeight + 'px';
        style.fontSize = computed.fontSize;
        style.fontFamily = computed.fontFamily;
        style.fontWeight = computed.fontWeight;
        style.lineHeight = computed.lineHeight;
        style.paddingLeft = computed.paddingLeft;
        style.paddingTop = computed.paddingTop;
        style.paddingRight = computed.paddingRight;
        style.paddingBottom = computed.paddingBottom;
        style.boxSizing = 'border-box';
        style.whiteSpace = 'pre-wrap';
        style.wordWrap = 'break-word';
        style.position = 'absolute';
        style.visibility = 'hidden';
        document.body.appendChild(div);
        
        // Get text up to position
        const text = element.value.substring(0, position);
        
        // Add a span at the position to measure its coordinates
        div.textContent = text;
        const span = document.createElement('span');
        span.textContent = element.value.substring(position) || '.'; // Add a period if at the end
        div.appendChild(span);
        
        const coords = {
            top: span.offsetTop + parseInt(computed.borderTopWidth, 10),
            left: span.offsetLeft + parseInt(computed.borderLeftWidth, 10),
            height: parseInt(computed.lineHeight, 10)
        };
        
        document.body.removeChild(div);
        return coords;
    }
});
</script>