/**
 * Notes JavaScript functionality
 */
document.addEventListener('DOMContentLoaded', function() {
    // Search functionality
    const searchInput = document.getElementById('search-input');
    const clearSearchBtn = document.getElementById('clear-search');
    
    if (searchInput) {
        let searchTimeout;
        
        searchInput.addEventListener('input', function() {
            // Clear previous timeout
            clearTimeout(searchTimeout);
            
            // Show/hide clear button
            clearSearchBtn.style.display = this.value ? 'block' : 'none';
            
            // Set timeout for search
            searchTimeout = setTimeout(function() {
                // Get current URL and update search parameter
                const url = new URL(window.location.href);
                if (searchInput.value) {
                    url.searchParams.set('search', searchInput.value);
                } else {
                    url.searchParams.delete('search');
                }
                
                // Navigate to the URL
                window.location.href = url.toString();
            }, 300); // 300ms delay for typing
        });
        
        // Clear search button
        if (clearSearchBtn) {
            clearSearchBtn.addEventListener('click', function() {
                searchInput.value = '';
                this.style.display = 'none';
                
                // Remove search parameter and reload
                const url = new URL(window.location.href);
                url.searchParams.delete('search');
                window.location.href = url.toString();
            });
        }
    }
    
    // Pin/unpin note functionality
    const pinButtons = document.querySelectorAll('.pin-note');
    
    if (pinButtons.length > 0) {
        pinButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                
                const noteId = this.getAttribute('data-id');
                const icon = this.querySelector('i');
                
                // Send AJAX request
                fetch(BASE_URL + '/notes/toggle-pin/' + noteId, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Toggle pinned state visually
                        if (data.is_pinned) {
                            icon.classList.add('pinned');
                            this.setAttribute('title', 'Unpin');
                            
                            // Add pinned class to parent note card/row
                            if (this.closest('.note-card')) {
                                this.closest('.note-card').classList.add('pinned');
                            } else if (this.closest('tr')) {
                                this.closest('tr').classList.add('pinned');
                            }
                        } else {
                            icon.classList.remove('pinned');
                            this.setAttribute('title', 'Pin');
                            
                            // Remove pinned class from parent note card/row
                            if (this.closest('.note-card')) {
                                this.closest('.note-card').classList.remove('pinned');
                            } else if (this.closest('tr')) {
                                this.closest('tr').classList.remove('pinned');
                            }
                        }
                        
                        // Show success message
                        const successMsg = document.createElement('div');
                        successMsg.className = 'alert alert-success';
                        successMsg.setAttribute('data-auto-dismiss', '3000');
                        successMsg.textContent = data.message;
                        
                        // Insert at the top of notes-content
                        const notesContent = document.querySelector('.notes-content');
                        if (notesContent) {
                            notesContent.insertBefore(successMsg, notesContent.firstChild);
                            
                            // Auto dismiss
                            setTimeout(function() {
                                successMsg.style.opacity = '0';
                                setTimeout(function() {
                                    if (successMsg.parentNode) {
                                        successMsg.parentNode.removeChild(successMsg);
                                    }
                                }, 300);
                            }, 3000);
                        }
                    }
                })
                .catch(error => console.error('Error:', error));
            });
        });
    }
    
    // Delete confirmation
    const deleteLinks = document.querySelectorAll('a.delete-note');
    
    if (deleteLinks.length > 0) {
        deleteLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                if (!confirm('Are you sure you want to delete this note? This action cannot be undone.')) {
                    e.preventDefault();
                }
            });
        });
    }
    
    // Delete image
    const deleteImageLinks = document.querySelectorAll('a.delete-image');
    
    if (deleteImageLinks.length > 0) {
        deleteImageLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                
                if (!confirm('Are you sure you want to delete this image?')) {
                    return;
                }
                
                const imageId = this.getAttribute('data-id');
                const imagePreview = this.closest('.image-preview');
                
                // Send AJAX request
                fetch(this.getAttribute('href'), {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Remove the image preview
                        if (imagePreview) {
                            imagePreview.remove();
                        }
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => console.error('Error:', error));
            });
        });
    }
});

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
    
    // File upload elements
    const uploadArea = document.getElementById('upload-area');
    const fileInput = document.getElementById('file-input');
    const previewContainer = document.getElementById('preview-container');
    
    let saveTimeout;
    let lastSavedContent = contentInput?.value || '';
    let lastSavedTitle = titleInput?.value || '';
    let autoSaveEnabled = noteForm?.action.includes('/update/') || false;
    let lastSaveTime = Date.now();
    let files = [];
    
    // Auto-save functionality
    function showSaveStatus(status, message) {
        if (!saveStatus) return;
        
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
    
    // File upload handling
    if (uploadArea && fileInput && previewContainer) {
        // Drag and drop handlers
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            uploadArea.addEventListener(eventName, preventDefaults, false);
        });
        
        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }
        
        ['dragenter', 'dragover'].forEach(eventName => {
            uploadArea.addEventListener(eventName, highlight, false);
        });
        
        ['dragleave', 'drop'].forEach(eventName => {
            uploadArea.addEventListener(eventName, unhighlight, false);
        });
        
        function highlight() {
            uploadArea.classList.add('bg-light');
        }
        
        function unhighlight() {
            uploadArea.classList.remove('bg-light');
        }
        
        // Handle file drops
        uploadArea.addEventListener('drop', handleDrop, false);
        
        function handleDrop(e) {
            const dt = e.dataTransfer;
            const newFiles = [...dt.files];
            handleFiles(newFiles);
        }
        
        // Handle file input changes
        fileInput.addEventListener('change', function() {
            const newFiles = [...this.files];
            handleFiles(newFiles);
        });
        
        function handleFiles(newFiles) {
            files = [...files, ...newFiles];
            updatePreview();
        }
        
        function updatePreview() {
            previewContainer.innerHTML = '';
            
            if (files.length > 0) {
                previewContainer.style.display = 'flex';
                
                files.forEach((file, index) => {
                    const reader = new FileReader();
                    const preview = document.createElement('div');
                    preview.className = 'preview-item card';
                    preview.style.width = '120px';
                    
                    const isImage = file.type.startsWith('image/');
                    
                    // Read file for preview
                    reader.onloadend = function() {
                        preview.innerHTML = `
                            <div class="card-body p-2 text-center">
                                ${isImage ? 
                                    `<div class="image-thumbnail mb-2">
                                        <img src="${reader.result}" alt="${file.name}" class="img-thumbnail" style="max-width: 100px; max-height: 100px;">
                                    </div>` : 
                                    `<div class="file-icon mb-2">
                                        <i class="fas fa-file-alt fa-3x text-secondary"></i>
                                    </div>`
                                }
                                <div class="file-name small text-truncate" style="max-width: 100px;">
                                    ${file.name}
                                </div>
                                <div class="mt-2">
                                    <button type="button" class="btn btn-sm btn-outline-danger remove-file" data-index="${index}">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </div>
                        `;
                        
                        previewContainer.appendChild(preview);
                        
                        // Add event listener for remove button
                        const removeBtn = preview.querySelector('.remove-file');
                        removeBtn.addEventListener('click', function() {
                            const index = parseInt(this.getAttribute('data-index'));
                            files.splice(index, 1);
                            updatePreview();
                        });
                    };
                    
                    if (isImage) {
                        reader.readAsDataURL(file);
                    } else {
                        reader.readAsArrayBuffer(file); // Just to trigger onloadend
                    }
                });
            } else {
                previewContainer.style.display = 'none';
            }
        }
    }
    
    // Delete image functionality
    const deleteImageLinks = document.querySelectorAll('.delete-image');
    if (deleteImageLinks.length > 0) {
        deleteImageLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                
                if (!confirm('Are you sure you want to delete this attachment?')) {
                    return;
                }
                
                const imageId = this.getAttribute('data-id');
                const attachmentPreview = this.closest('.attachment-preview');
                
                // Send AJAX request
                fetch(this.href, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Remove the preview element
                        if (attachmentPreview) {
                            attachmentPreview.remove();
                        }
                        showSaveStatus('saved', 'Attachment deleted');
                    } else {
                        showSaveStatus('error', data.message || 'Error deleting attachment');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showSaveStatus('error', 'Network error');
                });
            });
        });
    }
    
    // Form submission and auto-save
    if (noteForm && titleInput && contentInput) {
        // Handle form submission for new notes with files
        if (createButton) {
            createButton.addEventListener('click', function() {
                if (titleInput.value.trim() === '') {
                    showSaveStatus('error', 'Title is required');
                    titleInput.focus();
                    return;
                }
                
                noteForm.submit();
            });
        }
        
        // Auto-save for existing notes
        function saveChanges(force = false) {
            // Auto-save if content has changed and title is not empty
            if (autoSaveEnabled && 
                (lastSavedContent !== contentInput.value || lastSavedTitle !== titleInput.value) &&
                titleInput.value.trim() !== '') {
                
                const now = Date.now();
                // Skip if we just saved recently, unless force=true
                if (!force && now - lastSaveTime < 1000) {
                    return;
                }
                
                lastSaveTime = now;
                
                // Show saving indicator
                showSaveStatus('saving');
                
                // For autosave we just save the content, not the files
                const formData = new FormData();
                formData.append('title', titleInput.value);
                formData.append('content', contentInput.value);
                
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
            if (!autoSaveEnabled) return;
            
            // Clear any existing timeout
            clearTimeout(saveTimeout);
            
            // Set a new timeout to save after 1 second of inactivity
            saveTimeout = setTimeout(saveChanges, 1000);
        }
        
        // Add event listeners for auto-save
        titleInput.addEventListener('input', autoSave);
        contentInput.addEventListener('input', autoSave);
        
        // Initial save if coming to edit page with existing note
        if (autoSaveEnabled) {
            setTimeout(() => {
                lastSavedContent = contentInput.value;
                lastSavedTitle = titleInput.value;
            }, 500);
            
            // Save periodically
            setInterval(() => {
                if (autoSaveEnabled && titleInput.value.trim() !== '') {
                    saveChanges(true);
                }
            }, 30000);
        }
    }
});