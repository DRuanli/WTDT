/**
 * Labels JavaScript functionality
 */
document.addEventListener('DOMContentLoaded', function() {
    // Label form handling
    const labelForm = document.getElementById('label-form');
    const labelsList = document.getElementById('labels-list');
    const labelNameInput = document.getElementById('label-name');
    const submitButton = document.getElementById('submit-label');
    const cancelButton = document.getElementById('cancel-label');
    
    let editingLabelId = null;
    
    if (labelForm && labelsList) {
        // Handle form submission
        labelForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const labelName = labelNameInput.value.trim();
            
            if (!labelName) {
                alert('Please enter a label name');
                return;
            }
            
            // Prepare form data
            const formData = new FormData();
            formData.append('name', labelName);
            
            if (editingLabelId) {
                // Updating an existing label
                formData.append('action', 'update');
                formData.append('id', editingLabelId);
            } else {
                // Creating a new label
                formData.append('action', 'create');
            }
            
            // Send AJAX request
            fetch(BASE_URL + '/labels/process', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (editingLabelId) {
                        // Update existing label in the list
                        const labelElement = document.querySelector(`[data-label-id="${editingLabelId}"]`);
                        if (labelElement) {
                            labelElement.querySelector('.label-name').textContent = labelName;
                        }
                        
                        // Reset form
                        resetForm();
                        
                        // Show success message
                        showMessage('Label updated successfully', 'success');
                    } else {
                        // Add new label to the list
                        addLabelToList({
                            id: data.label_id,
                            name: labelName,
                            note_count: 0
                        });
                        
                        // Reset form
                        resetForm();
                        
                        // Show success message
                        showMessage('Label created successfully', 'success');
                    }
                } else {
                    // Show error message
                    showMessage(data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showMessage('An error occurred. Please try again.', 'error');
            });
        });
        
        // Setup edit buttons
        setupEditButtons();
        
        // Setup delete buttons
        setupDeleteButtons();
        
        // Cancel button
        if (cancelButton) {
            cancelButton.addEventListener('click', function(e) {
                e.preventDefault();
                resetForm();
            });
        }
    }
    
    // Add a label to the list
    function addLabelToList(label) {
        const labelItem = document.createElement('div');
        labelItem.className = 'label-item';
        labelItem.setAttribute('data-label-id', label.id);
        
        labelItem.innerHTML = `
            <div class="label-info">
                <span class="label-name">${escapeHtml(label.name)}</span>
                <span class="note-count">${label.note_count} notes</span>
            </div>
            <div class="label-actions">
                <button class="btn-edit" data-id="${label.id}" title="Edit">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn-delete" data-id="${label.id}" title="Delete">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        `;
        
        labelsList.appendChild(labelItem);
        
        // Setup event listeners for the new buttons
        setupEditButtons();
        setupDeleteButtons();
    }
    
    // Setup edit buttons
    function setupEditButtons() {
        const editButtons = document.querySelectorAll('.btn-edit');
        
        editButtons.forEach(button => {
            button.addEventListener('click', function() {
                const labelId = this.getAttribute('data-id');
                const labelItem = document.querySelector(`[data-label-id="${labelId}"]`);
                
                if (labelItem) {
                    const labelName = labelItem.querySelector('.label-name').textContent;
                    
                    // Set form to edit mode
                    labelNameInput.value = labelName;
                    editingLabelId = labelId;
                    submitButton.textContent = 'Update Label';
                    cancelButton.style.display = 'block';
                    
                    // Scroll to form
                    labelForm.scrollIntoView({ behavior: 'smooth' });
                    labelNameInput.focus();
                }
            });
        });
    }
    
    // Setup delete buttons
    function setupDeleteButtons() {
        const deleteButtons = document.querySelectorAll('.btn-delete');
        
        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const labelId = this.getAttribute('data-id');
                const labelItem = document.querySelector(`[data-label-id="${labelId}"]`);
                
                if (labelItem) {
                    const labelName = labelItem.querySelector('.label-name').textContent;
                    
                    if (!confirm(`Are you sure you want to delete the label "${labelName}"?\nThis will not delete notes with this label.`)) {
                        return;
                    }
                    
                    // Prepare form data
                    const formData = new FormData();
                    formData.append('action', 'delete');
                    formData.append('id', labelId);
                    
                    // Send AJAX request
                    fetch(BASE_URL + '/labels/process', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Remove label from the list
                            labelItem.remove();
                            
                            // If we were editing this label, reset the form
                            if (editingLabelId === labelId) {
                                resetForm();
                            }
                            
                            // Show success message
                            showMessage('Label deleted successfully', 'success');
                        } else {
                            // Show error message
                            showMessage(data.message, 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showMessage('An error occurred. Please try again.', 'error');
                    });
                }
            });
        });
    }
    
    // Reset the form
    function resetForm() {
        labelNameInput.value = '';
        editingLabelId = null;
        submitButton.textContent = 'Create Label';
        cancelButton.style.display = 'none';
    }
    
    // Show message
    function showMessage(message, type) {
        const messageContainer = document.getElementById('message-container');
        
        if (!messageContainer) return;
        
        const messageElement = document.createElement('div');
        messageElement.className = `alert alert-${type === 'error' ? 'danger' : 'success'}`;
        messageElement.setAttribute('data-auto-dismiss', '3000');
        messageElement.textContent = message;
        
        messageContainer.innerHTML = '';
        messageContainer.appendChild(messageElement);
        
        // Auto dismiss
        setTimeout(function() {
            messageElement.style.opacity = '0';
            setTimeout(function() {
                if (messageElement.parentNode) {
                    messageElement.parentNode.removeChild(messageElement);
                }
            }, 300);
        }, 3000);
    }
    
    // Helper function to escape HTML
    function escapeHtml(unsafe) {
        return unsafe
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }
});