<div class="row">
    <div class="col-md-10 mx-auto">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-share-alt text-primary me-2"></i>Share Note
                    </h4>
                    <a href="<?= BASE_URL ?>/notes/edit/<?= $data['note']['id'] ?>" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back to Note
                    </a>
                </div>
            </div>
            
            <div class="card-body">
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
                
                <?php if (!empty($data['errors']['general'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= $data['errors']['general'] ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                
                <div class="note-summary mb-4 p-3 bg-light rounded">
                    <div class="fw-bold mb-2">Note: <?= htmlspecialchars($data['note']['title']) ?></div>
                    <div class="text-muted small">
                        Last updated: 
                        <?php 
                        $updated = new DateTime($data['note']['updated_at']);
                        echo $updated->format('M j, Y g:i A');
                        ?>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <h5 class="mb-3">Share with Others</h5>
                        
                        <form method="POST" class="share-form">
                            <div class="mb-3">
                                <label for="recipient_emails" class="form-label">Recipient Email Addresses</label>
                                <div class="form-text mb-2">Enter one or more email addresses of registered users (one per line or comma-separated)</div>
                                <textarea class="form-control <?= !empty($data['errors']['recipient_emails']) ? 'is-invalid' : '' ?>" 
                                        id="recipient_emails" name="recipient_emails" rows="3" 
                                        placeholder="user@example.com&#10;another@example.com"></textarea>
                                <?php if (!empty($data['errors']['recipient_emails'])): ?>
                                    <div class="invalid-feedback"><?= $data['errors']['recipient_emails'] ?></div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Permission Level</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="can_edit" id="permission_read" value="0" checked>
                                    <label class="form-check-label" for="permission_read">
                                        <i class="fas fa-eye me-1"></i> Read only
                                        <div class="form-text">Recipients can only view the note</div>
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="can_edit" id="permission_edit" value="1">
                                    <label class="form-check-label" for="permission_edit">
                                        <i class="fas fa-edit me-1"></i> Can edit
                                        <div class="form-text">Recipients can view and edit the note</div>
                                    </label>
                                </div>
                            </div>
                            
                            <div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-share-alt me-1"></i> Share Note
                                </button>
                            </div>
                        </form>
                    </div>
                    
                    <div class="col-md-6">
                        <h5 class="mb-3">
                            Current Shares
                            <span class="badge bg-secondary ms-2"><?= count($data['current_shares']) ?></span>
                        </h5>
                        
                        <?php if (empty($data['current_shares'])): ?>
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                This note hasn't been shared with anyone yet.
                            </div>
                        <?php else: ?>
                            <div class="list-group">
                                <?php foreach ($data['current_shares'] as $share): ?>
                                    <div class="list-group-item">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <div class="fw-bold"><?= htmlspecialchars($share['recipient_name']) ?></div>
                                                <div class="text-muted small"><?= htmlspecialchars($share['recipient_email']) ?></div>
                                                <div class="mt-1">
                                                    <?php if ($share['can_edit']): ?>
                                                        <span class="badge bg-success">Can Edit</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-secondary">Read Only</span>
                                                    <?php endif; ?>
                                                    <span class="badge bg-light text-dark">
                                                        Shared: <?= (new DateTime($share['shared_at']))->format('M j, Y') ?>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="btn-group">
                                                <?php if ($share['can_edit']): ?>
                                                    <a href="<?= BASE_URL ?>/notes/update-share/<?= $data['note']['id'] ?>/<?= $share['id'] ?>/0" 
                                                       class="btn btn-sm btn-outline-secondary" title="Change to read-only">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                <?php else: ?>
                                                    <a href="<?= BASE_URL ?>/notes/update-share/<?= $data['note']['id'] ?>/<?= $share['id'] ?>/1" 
                                                       class="btn btn-sm btn-outline-primary" title="Allow editing">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                <?php endif; ?>
                                                <a href="<?= BASE_URL ?>/notes/remove-share/<?= $data['note']['id'] ?>/<?= $share['id'] ?>" 
                                                   class="btn btn-sm btn-outline-danger" 
                                                   onclick="return confirm('Are you sure you want to remove sharing with this user?');" 
                                                   title="Remove share">
                                                    <i class="fas fa-times"></i>
                                                </a>
                                            </div>
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
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle textarea input for emails
    const recipientTextarea = document.getElementById('recipient_emails');
    const shareForm = document.querySelector('.share-form');
    
    if (recipientTextarea && shareForm) {
        // Format emails when pasting
        recipientTextarea.addEventListener('paste', function(e) {
            // Get pasted data
            let paste = (e.clipboardData || window.clipboardData).getData('text');
            
            // Process the pasted content
            paste = paste.replace(/[,;]/g, '\n');  // Replace commas and semicolons with newlines
            paste = paste.replace(/\s+/g, ' ');    // Replace multiple spaces with a single space
            paste = paste.replace(/\s*\n\s*/g, '\n'); // Clean up spaces around newlines
            
            // Insert at cursor position
            const start = this.selectionStart;
            const end = this.selectionEnd;
            const text = this.value;
            this.value = text.substring(0, start) + paste + text.substring(end);
            
            // Set cursor position after pasted content
            this.selectionStart = this.selectionEnd = start + paste.length;
            
            // Prevent default paste
            e.preventDefault();
        });
        
        // Process form submission to prepare email addresses
        shareForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get and process email addresses
            let emailText = recipientTextarea.value.trim();
            
            // Split by newlines or commas
            let emails = emailText.split(/[\n,;]+/).map(email => email.trim()).filter(email => email);
            
            if (emails.length === 0) {
                // Show error
                recipientTextarea.classList.add('is-invalid');
                if (!recipientTextarea.nextElementSibling || !recipientTextarea.nextElementSibling.classList.contains('invalid-feedback')) {
                    const errorElement = document.createElement('div');
                    errorElement.className = 'invalid-feedback';
                    errorElement.textContent = 'At least one email address is required';
                    recipientTextarea.parentNode.appendChild(errorElement);
                }
                return;
            }
            
            // Clear any existing input elements
            const existingInputs = shareForm.querySelectorAll('input[name="recipient_emails[]"]');
            existingInputs.forEach(input => input.remove());
            
            // Create hidden input for each email
            emails.forEach(email => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'recipient_emails[]';
                input.value = email;
                shareForm.appendChild(input);
            });
            
            // Submit the form
            shareForm.submit();
        });
    }
});
</script>