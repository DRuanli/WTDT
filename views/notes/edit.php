<div class="row">
    <div class="col-md-10 mx-auto">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><?= isset($data['note']['id']) ? 'Edit Note' : 'Create Note' ?></h4>
                    <div>
                        <!-- Save button -->
                        <button type="button" id="save-note-btn" class="btn btn-primary me-2">
                            <i class="fas fa-save me-1"></i> Save
                        </button>
                        <button type="button" class="btn btn-outline-primary" id="toggle-options">
                            <i class="fas fa-cog me-1"></i> Options
                        </button>
                        <a href="<?= BASE_URL ?>/notes" class="btn btn-outline-secondary ms-2">
                            <i class="fas fa-times me-1"></i> Cancel
                        </a>
                    </div>
                </div>
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
                               required>
                        <?php if (!empty($data['errors']['title'])): ?>
                            <div class="invalid-feedback d-block"><?= $data['errors']['title'] ?></div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Options Panel -->
                    <div class="options-panel border rounded p-3 mb-4" style="display: none;">
                        <div class="row">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <h5 class="fw-bold mb-3">Labels</h5>
                                <div class="labels-container">
                                    <?php if (empty($data['labels'])): ?>
                                        <p class="text-muted">No labels available. <a href="<?= BASE_URL ?>/labels">Create labels</a></p>
                                    <?php else: ?>
                                        <div class="row row-cols-2 row-cols-xl-3 g-2">
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
                            </div>
                            
                            <div class="col-md-6">
                                <h5 class="fw-bold mb-3">Attachments</h5>
                                <div class="attachments-container">
                                    <div class="image-upload mb-3">
                                        <label for="note-images" class="form-label d-block p-3 text-center border rounded-3 border-dashed">
                                            <i class="fas fa-cloud-upload-alt fs-3 mb-2"></i>
                                            <div>Drop images here or click to browse</div>
                                        </label>
                                        <input type="file" name="images[]" id="note-images" class="d-none" multiple accept="image/*">
                                    </div>
                                    
                                    <?php if (!empty($data['note']['images'])): ?>
                                        <h6 class="fw-bold mb-2">Current Attachments</h6>
                                        <div class="row row-cols-2 row-cols-lg-4 g-2 mb-3">
                                            <?php foreach ($data['note']['images'] as $image): ?>
                                                <div class="col">
                                                    <div class="position-relative border rounded">
                                                        <img src="<?= UPLOADS_URL . '/' . $image['file_path'] ?>" 
                                                             alt="<?= htmlspecialchars($image['file_name']) ?>"
                                                             class="img-fluid rounded">
                                                        <a href="<?= BASE_URL ?>/notes/delete-image/<?= $image['id'] ?>" 
                                                           class="position-absolute top-0 end-0 btn btn-sm btn-danger rounded-circle m-1 delete-image" 
                                                           data-id="<?= $image['id'] ?>">
                                                            <i class="fas fa-times"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div id="image-preview-container" class="d-none mt-3">
                                        <h6 class="fw-bold mb-2">Images to Upload</h6>
                                        <div class="row row-cols-2 row-cols-lg-4 g-2" id="image-previews"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
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
    // Toggle options panel
    const toggleOptionsBtn = document.getElementById('toggle-options');
    const optionsPanel = document.querySelector('.options-panel');
    
    toggleOptionsBtn.addEventListener('click', function() {
        if (optionsPanel.style.display === 'none') {
            optionsPanel.style.display = 'block';
            toggleOptionsBtn.innerHTML = '<i class="fas fa-times me-1"></i> Close Options';
        } else {
            optionsPanel.style.display = 'none';
            toggleOptionsBtn.innerHTML = '<i class="fas fa-cog me-1"></i> Options';
        }
    });
    
    // Preview uploaded images
    const imageInput = document.getElementById('note-images');
    const previewContainer = document.getElementById('image-preview-container');
    const previewsDiv = document.getElementById('image-previews');
    
    if (imageInput) {
        imageInput.addEventListener('change', function() {
            previewsDiv.innerHTML = '';
            
            if (this.files.length > 0) {
                previewContainer.classList.remove('d-none');
                
                for (let i = 0; i < this.files.length; i++) {
                    const file = this.files[i];
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        const preview = document.createElement('div');
                        preview.className = 'col';
                        
                        reader.onload = function(e) {
                            preview.innerHTML = `
                                <div class="position-relative border rounded">
                                    <img src="${e.target.result}" class="img-fluid rounded" alt="${file.name}">
                                    <div class="position-absolute bottom-0 start-0 end-0 bg-dark bg-opacity-50 text-white p-1 small text-truncate">
                                        ${file.name}
                                    </div>
                                </div>
                            `;
                        };
                        
                        reader.readAsDataURL(file);
                        previewsDiv.appendChild(preview);
                    }
                }
            } else {
                previewContainer.classList.add('d-none');
            }
        });
    }
    
    // Auto-save functionality
    const noteForm = document.getElementById('note-form');
    const titleInput = document.getElementById('note-title');
    const contentInput = document.getElementById('note-content');
    const saveStatus = document.getElementById('save-status');
    const savingIcon = document.getElementById('saving-icon');
    const savedIcon = document.getElementById('saved-icon');
    const saveMessage = document.getElementById('save-message');
    
    let saveTimeout;
    let lastSavedContent = contentInput.value;
    let lastSavedTitle = titleInput.value;
    let autoSaveEnabled = true;
    
    // Add event listener for unload event to prevent data loss
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
    
    function autoSave() {
        // Clear any existing timeout
        clearTimeout(saveTimeout);
        
        // Set a new timeout to save after 1.5 seconds of inactivity
        saveTimeout = setTimeout(saveChanges, 1500);
    }
    
    // Add event listeners for auto-save
    titleInput.addEventListener('input', autoSave);
    contentInput.addEventListener('input', autoSave);
    
    // Add event listeners for label checkboxes
    const labelCheckboxes = document.querySelectorAll('input[name="labels[]"]');
    labelCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', autoSave);
    });
    
    // Save button functionality
    const saveButton = document.getElementById('save-note-btn');
    if (saveButton) {
        saveButton.addEventListener('click', function() {
            // Show saving indicator
            showSaveStatus('saving');
            
            // Submit the form without waiting for auto-save
            const formData = new FormData(noteForm);
            
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
                    showSaveStatus('saved', 'Note saved successfully');
                    
                    // If this was a new note, redirect to edit page
                    if (data.note_id && !window.location.href.includes('/edit/')) {
                        window.location.href = BASE_URL + '/notes/edit/' + data.note_id;
                    }
                } else {
                    // Show error indicator
                    showSaveStatus('error', data.errors?.general || 'Error saving note');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showSaveStatus('error', 'Network error');
            });
        });
    }
    
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
});
</script>

<style>
/* Custom styles for drag and drop */
.border-dashed {
    border-style: dashed !important;
    cursor: pointer;
    transition: all 0.2s ease;
}

.border-dashed:hover {
    background-color: rgba(0, 123, 255, 0.05);
    border-color: #007bff;
}

/* Note content area */
#note-content {
    min-height: 300px;
    resize: vertical;
}

/* Animation for auto-save toast */
.toast {
    transition: opacity 0.3s ease;
}
</style>