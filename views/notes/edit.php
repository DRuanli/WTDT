<div class="row">
    <div class="col-md-10 mx-auto">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><?= isset($data['note']['id']) ? 'Edit Note' : 'Create Note' ?></h4>
                    <div>
                        <?php if (!isset($data['note']['id'])): ?>
                            <!-- Show Create button only for new notes -->
                            <button type="button" id="create-note-btn" class="btn btn-primary me-2">
                                <i class="fas fa-plus me-1"></i> Create
                            </button>
                        <?php endif; ?>
                        <a href="<?= BASE_URL ?>/notes" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Back to Notes
                        </a>
                    </div>
                </div>
            </div>
            
            <form id="note-form" method="POST" action="<?= isset($data['note']['id']) ? BASE_URL . '/notes/update/' . $data['note']['id'] : BASE_URL . '/notes/store' ?>">
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
                               required>
                        <?php if (!empty($data['errors']['title'])): ?>
                            <div class="invalid-feedback d-block"><?= $data['errors']['title'] ?></div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Content Field -->
                    <div class="mb-3">
                        <textarea name="content" id="note-content" 
                                  class="form-control border-0 shadow-none" 
                                  placeholder="Note content..." 
                                  rows="12"><?= htmlspecialchars($data['note']['content'] ?? '') ?></textarea>
                    </div>
                </div>
            </form>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-save functionality
    const noteForm = document.getElementById('note-form');
    const titleInput = document.getElementById('note-title');
    const contentInput = document.getElementById('note-content');
    const saveStatus = document.getElementById('save-status');
    const savingIcon = document.getElementById('saving-icon');
    const savedIcon = document.getElementById('saved-icon');
    const saveMessage = document.getElementById('save-message');
    const createButton = document.getElementById('create-note-btn');
    
    let saveTimeout;
    let lastSavedContent = contentInput.value;
    let lastSavedTitle = titleInput.value;
    let autoSaveEnabled = <?= isset($data['note']['id']) ? 'true' : 'false' ?>;
    let lastSaveTime = Date.now();
    const minTimeBetweenSaves = 1000; // At least 1 second between saves to prevent excessive requests
    
    // Add event listener for unload event to prevent data loss
    window.addEventListener('beforeunload', function(e) {
        if (autoSaveEnabled && 
            (lastSavedContent !== contentInput.value || lastSavedTitle !== titleInput.value) &&
            titleInput.value.trim() !== '') {
            // Auto-save before leaving page
            saveChanges(true); // Force save before unload
            
            // Show warning if there are unsaved changes
            const message = 'You have unsaved changes. Are you sure you want to leave?';
            e.returnValue = message;
            return message;
        }
    });
    
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
    
    function saveChanges(force = false) {
        // Auto-save if content has changed and title is not empty
        if ((lastSavedContent !== contentInput.value || lastSavedTitle !== titleInput.value) &&
            titleInput.value.trim() !== '') {
            
            const now = Date.now();
            // Skip if we just saved recently, unless force=true
            if (!force && now - lastSaveTime < minTimeBetweenSaves) {
                return;
            }
            
            lastSaveTime = now;
            
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
                        // Enable auto-save after successful creation
                        autoSaveEnabled = true;
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
                
                // Retry after a delay
                setTimeout(() => {
                    saveChanges(true);
                }, 3000);
            });
        }
    }
    
    function autoSave() {
        if (!autoSaveEnabled) return;
        
        // Clear any existing timeout
        clearTimeout(saveTimeout);
        
        // Set a new timeout to save after 1 second of inactivity
        saveTimeout = setTimeout(saveChanges, 1000);
    }
    
    // Add event listeners for auto-save
    titleInput.addEventListener('input', autoSave);
    titleInput.addEventListener('blur', () => {
        if (autoSaveEnabled) saveChanges(true); // Save immediately on blur
    });
    contentInput.addEventListener('input', autoSave);
    contentInput.addEventListener('blur', () => {
        if (autoSaveEnabled) saveChanges(true); // Save immediately on blur
    });
    
    // Create button for new notes
    if (createButton) {
        createButton.addEventListener('click', function() {
            if (titleInput.value.trim() === '') {
                showSaveStatus('error', 'Title is required');
                titleInput.focus();
                return;
            }
            
            // Manual form submission for new notes
            noteForm.submit();
        });
    }
    
    // Initial save if coming to edit page with existing note
    if (noteForm.action.includes('/update/')) {
        // Set a timeout to allow page to fully load
        setTimeout(() => {
            // Only show status for new notes, not when initially loading existing notes
            lastSavedContent = contentInput.value;
            lastSavedTitle = titleInput.value;
        }, 500);
        
        // Also save periodically regardless of changes (every 30 seconds)
        setInterval(() => {
            if (autoSaveEnabled && titleInput.value.trim() !== '') {
                saveChanges(true);
            }
        }, 30000);
    }
});
</script>