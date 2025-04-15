<?php
// Note Edit View - views/notes/edit.php
?>
<div class="row">
    <div class="col-md-10 mx-auto">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><?= isset($data['note']['id']) ? 'Edit Note' : 'Create Note' ?></h4>
                    <div>
                        <button type="button" id="save-note-btn" class="btn btn-primary me-2">
                            <i class="fas fa-save me-1"></i> Save
                        </button>
                        <a href="<?= BASE_URL ?>/notes" class="btn btn-outline-secondary">
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
                    
                    <!-- Content Field -->
                    <div class="mb-3">
                        <label class="form-label mb-2"><i class="fas fa-align-left text-primary me-1"></i> Note Content</label>
                        <textarea name="content" id="note-content" 
                                  class="form-control" 
                                  placeholder="Write your note here..." 
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
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const noteForm = document.getElementById('note-form');
    const titleInput = document.getElementById('note-title');
    const contentInput = document.getElementById('note-content');
    const saveButton = document.getElementById('save-note-btn');
    const saveStatus = document.getElementById('save-status');
    const savingIcon = document.getElementById('saving-icon');
    const savedIcon = document.getElementById('saved-icon');
    const saveMessage = document.getElementById('save-message');
    const imageInput = document.getElementById('note-images');
    const previewContainer = document.getElementById('image-preview-container');
    const previewsDiv = document.getElementById('image-previews');
    const dropzone = document.getElementById('dropzone');
    
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
        titleInput.addEventListener('input', autoSave);
        contentInput.addEventListener('input', autoSave);
    }
    
    // Add event listeners for label checkboxes
    const labelCheckboxes = document.querySelectorAll('input[name="labels[]"]');
    labelCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', autoSave);
    });
    
    // Save button functionality
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
});
</script>