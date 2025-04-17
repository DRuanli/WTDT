<div class="row">
    <div class="col-lg-10 mx-auto">
        <div class="d-md-flex justify-content-between align-items-center mb-4">
            <h2 class="h3 mb-3 mb-md-0">
                <i class="fas fa-tags me-2 text-primary"></i>Manage Labels
            </h2>
            <a href="<?= BASE_URL ?>/notes" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back to Notes
            </a>
        </div>
        
        <?php if (Session::hasFlash('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= Session::getFlash('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        
        <?php if (Session::hasFlash('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= Session::getFlash('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        
        <div id="message-container"></div>
        
        <div class="row">
            <!-- Label Form -->
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-white">
                        <h5 class="card-title mb-0" id="form-title">Create New Label</h5>
                    </div>
                    <div class="card-body">
                        <form id="label-form" method="POST" action="<?= BASE_URL ?>/labels/process">
                            <div class="mb-3">
                                <label for="label-name" class="form-label">Label Name</label>
                                <input type="text" id="label-name" name="name" class="form-control" required>
                                <div class="invalid-feedback">Please provide a label name.</div>
                            </div>
                            
                            <input type="hidden" name="action" value="create" id="form-action">
                            <input type="hidden" name="id" value="" id="label-id">
                            
                            <div class="d-flex mt-3">
                                <button type="submit" id="submit-label" class="btn btn-primary me-2">
                                    <i class="fas fa-save me-1"></i> Create Label
                                </button>
                                <button type="button" id="cancel-label" class="btn btn-outline-secondary" style="display:none;">
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer bg-white border-top">
                        <h6 class="mb-2">Tips:</h6>
                        <ul class="small text-muted mb-0">
                            <li>Use labels to organize your notes</li>
                            <li>You can add multiple labels to a single note</li>
                            <li>Filter notes by label from the sidebar</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <!-- Labels List -->
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Your Labels</h5>
                        <span class="badge bg-primary rounded-pill"><?= count($data['labels']) ?> labels</span>
                    </div>
                    
                    <?php if (empty($data['labels'])): ?>
                        <div class="card-body text-center py-5">
                            <div class="display-1 text-muted mb-3"><i class="fas fa-tags"></i></div>
                            <h3>No labels yet</h3>
                            <p class="text-muted">Create your first label to organize your notes.</p>
                        </div>
                    <?php else: ?>
                        <div id="labels-list" class="list-group list-group-flush">
                            <?php foreach ($data['labels'] as $label): ?>
                                <div class="list-group-item d-md-flex justify-content-between align-items-center py-3" data-label-id="<?= $label['id'] ?>">
                                    <div class="d-flex align-items-center mb-2 mb-md-0">
                                        <div class="me-3">
                                            <span class="badge rounded-pill bg-light text-dark border px-3 py-2">
                                                <i class="fas fa-tag me-1 text-primary"></i>
                                                <span class="label-name"><?= htmlspecialchars($label['name']) ?></span>
                                            </span>
                                        </div>
                                        <span class="badge bg-secondary rounded-pill note-count">
                                            <?= $label['note_count'] ?> note<?= $label['note_count'] !== 1 ? 's' : '' ?>
                                        </span>
                                    </div>
                                    <div class="label-actions">
                                        <button class="btn btn-sm btn-outline-primary me-1 btn-edit" data-id="<?= $label['id'] ?>" title="Edit">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger btn-delete" data-id="<?= $label['id'] ?>" data-name="<?= htmlspecialchars($label['name']) ?>" title="Delete">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Confirmation Modal -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the label "<span id="label-to-delete"></span>"?</p>
                <p class="text-muted">This will not delete notes with this label.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirm-delete">Delete Label</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Elements
    const labelForm = document.getElementById('label-form');
    const labelsList = document.getElementById('labels-list');
    const labelNameInput = document.getElementById('label-name');
    const formAction = document.getElementById('form-action');
    const labelId = document.getElementById('label-id');
    const submitButton = document.getElementById('submit-label');
    const cancelButton = document.getElementById('cancel-label');
    const formTitle = document.getElementById('form-title');
    const messageContainer = document.getElementById('message-container');
    const confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));
    const confirmDeleteBtn = document.getElementById('confirm-delete');
    const labelToDelete = document.getElementById('label-to-delete');
    
    let currentLabelToDelete = null;
    
    if (labelForm && labelsList) {
        // Handle form submission
        labelForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const labelName = labelNameInput.value.trim();
            
            if (!labelName) {
                labelNameInput.classList.add('is-invalid');
                return;
            }
            
            labelNameInput.classList.remove('is-invalid');
            
            // Disable submit button to prevent double submission
            submitButton.disabled = true;
            submitButton.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Saving...';
            
            // Prepare form data
            const formData = new FormData(labelForm);
            
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
                // Re-enable submit button
                submitButton.disabled = false;
                submitButton.innerHTML = formAction.value === 'create' ? '<i class="fas fa-save me-1"></i> Create Label' : '<i class="fas fa-save me-1"></i> Update Label';
                
                if (data.success) {
                    if (formAction.value === 'update') {
                        // Update existing label in the list
                        const labelElement = document.querySelector(`[data-label-id="${labelId.value}"]`);
                        if (labelElement) {
                            labelElement.querySelector('.label-name').textContent = labelName;
                        }
                        
                        // Reset form
                        resetForm();
                        
                        // Show success message
                        showMessage('Label updated successfully', 'success');
                    } else {
                        // Add new label to the list or refresh page
                        window.location.reload();
                    }
                } else {
                    // Show error message
                    showMessage(data.message || 'Error saving label', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showMessage('An error occurred. Please try again.', 'error');
                
                // Re-enable submit button
                submitButton.disabled = false;
                submitButton.innerHTML = formAction.value === 'create' ? '<i class="fas fa-save me-1"></i> Create Label' : '<i class="fas fa-save me-1"></i> Update Label';
            });
        });
        
        // Handle edit buttons
        document.querySelectorAll('.btn-edit').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const labelItem = document.querySelector(`[data-label-id="${id}"]`);
                
                if (labelItem) {
                    const name = labelItem.querySelector('.label-name').textContent;
                    
                    // Set form to edit mode
                    labelNameInput.value = name;
                    formAction.value = 'update';
                    labelId.value = id;
                    formTitle.textContent = 'Edit Label';
                    submitButton.innerHTML = '<i class="fas fa-save me-1"></i> Update Label';
                    cancelButton.style.display = 'block';
                    
                    // Scroll to form on mobile
                    if (window.innerWidth < 768) {
                        labelForm.scrollIntoView({ behavior: 'smooth' });
                    }
                    
                    // Focus the input
                    labelNameInput.focus();
                }
            });
        });
        
        // Handle delete buttons
        document.querySelectorAll('.btn-delete').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                
                // Set the label name in the modal
                labelToDelete.textContent = name;
                currentLabelToDelete = id;
                
                // Show the confirmation modal
                confirmModal.show();
            });
        });
        
        // Handle confirm delete
        confirmDeleteBtn.addEventListener('click', function() {
            if (currentLabelToDelete) {
                // Disable the button
                confirmDeleteBtn.disabled = true;
                confirmDeleteBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Deleting...';
                
                // Prepare form data
                const formData = new FormData();
                formData.append('action', 'delete');
                formData.append('id', currentLabelToDelete);
                
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
                    // Hide the modal
                    confirmModal.hide();
                    
                    // Reset button
                    confirmDeleteBtn.disabled = false;
                    confirmDeleteBtn.innerHTML = 'Delete Label';
                    
                    if (data.success) {
                        // Remove the label from the list or refresh
                        window.location.reload();
                    } else {
                        // Show error message
                        showMessage(data.message || 'Error deleting label', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showMessage('An error occurred. Please try again.', 'error');
                    
                    // Hide the modal
                    confirmModal.hide();
                    
                    // Reset button
                    confirmDeleteBtn.disabled = false;
                    confirmDeleteBtn.innerHTML = 'Delete Label';
                });
            }
        });
        
        // Cancel button
        if (cancelButton) {
            cancelButton.addEventListener('click', function(e) {
                e.preventDefault();
                resetForm();
            });
        }
        
        // Input validation
        labelNameInput.addEventListener('input', function() {
            if (this.value.trim()) {
                this.classList.remove('is-invalid');
            }
        });
    }
    
    // Reset form to create mode
    function resetForm() {
        labelNameInput.value = '';
        formAction.value = 'create';
        labelId.value = '';
        formTitle.textContent = 'Create New Label';
        submitButton.innerHTML = '<i class="fas fa-save me-1"></i> Create Label';
        cancelButton.style.display = 'none';
        labelNameInput.classList.remove('is-invalid');
    }
    
    // Show message
    function showMessage(message, type) {
        messageContainer.innerHTML = `
            <div class="alert alert-${type === 'error' ? 'danger' : 'success'} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
        
        // Auto dismiss after 5 seconds
        setTimeout(function() {
            const alert = messageContainer.querySelector('.alert');
            if (alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }
        }, 5000);
    }
});
</script>