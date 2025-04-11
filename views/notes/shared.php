<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Share Note</h4>
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
                
                <!-- Share Note Form -->
                <form method="POST" id="shareForm">
                    <div class="mb-3">
                        <label for="recipient_emails" class="form-label">Share with (Email Addresses)</label>
                        <div class="email-tags-container form-control" id="emailTagsContainer">
                            <div id="emailTags" class="d-flex flex-wrap gap-2"></div>
                            <input type="text" id="emailInput" class="border-0 flex-grow-1" placeholder="Enter email and press Enter">
                        </div>
                        <div id="emailErrors" class="invalid-feedback"></div>
                        <div class="form-text">Enter multiple email addresses separated by pressing Enter</div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label">Permissions</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="can_edit" id="permission-read" value="0" checked>
                            <label class="form-check-label" for="permission-read">
                                <i class="fas fa-eye me-1"></i> Read-only (Recipients can view but not edit)
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="can_edit" id="permission-edit" value="1">
                            <label class="form-check-label" for="permission-edit">
                                <i class="fas fa-edit me-1"></i> Can edit (Recipients can view and edit)
                            </label>
                        </div>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-share-alt me-1"></i> Share Note
                        </button>
                    </div>
                </form>
                
                <!-- Current Shares -->
                <?php if (!empty($data['current_shares'])): ?>
                    <hr class="my-4">
                    <h5>Currently Shared With</h5>
                    
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Recipient</th>
                                    <th>Permissions</th>
                                    <th>Shared On</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data['current_shares'] as $share): ?>
                                    <tr>
                                        <td>
                                            <div class="fw-bold"><?= htmlspecialchars($share['recipient_name']) ?></div>
                                            <div class="text-muted small"><?= htmlspecialchars($share['recipient_email']) ?></div>
                                        </td>
                                        <td>
                                            <?php if ($share['can_edit']): ?>
                                                <span class="badge bg-success"><i class="fas fa-edit me-1"></i> Can Edit</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary"><i class="fas fa-eye me-1"></i> Read Only</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php 
                                            $shared_at = new DateTime($share['shared_at']);
                                            echo $shared_at->format('M j, Y g:i A');
                                            ?>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="<?= BASE_URL ?>/notes/update-share/<?= $data['note']['id'] ?>/<?= $share['id'] ?>/<?= $share['can_edit'] ? '0' : '1' ?>" 
                                                   class="btn btn-outline-primary" title="Toggle permissions">
                                                    <i class="fas fa-exchange-alt"></i>
                                                </a>
                                                <a href="<?= BASE_URL ?>/notes/remove-share/<?= $data['note']['id'] ?>/<?= $share['id'] ?>" 
                                                   class="btn btn-outline-danger remove-share" title="Remove sharing">
                                                    <i class="fas fa-times"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
.email-tags-container {
    min-height: 60px;
    padding: 0.5rem;
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    align-items: center;
}

.email-tags-container:focus-within {
    border-color: #86b7fe;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

.email-tag {
    background-color: #e9ecef;
    border-radius: 4px;
    padding: 0.25rem 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.email-tag.invalid {
    background-color: #f8d7da;
    color: #721c24;
}

.remove-tag {
    cursor: pointer;
    color: #6c757d;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 18px;
    height: 18px;
}

.remove-tag:hover {
    color: #dc3545;
}

#emailInput {
    outline: none;
    min-width: 100px;
    flex: 1;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const emailInput = document.getElementById('emailInput');
    const emailTags = document.getElementById('emailTags');
    const emailErrors = document.getElementById('emailErrors');
    const shareForm = document.getElementById('shareForm');
    const emailTagsContainer = document.getElementById('emailTagsContainer');
    
    let emails = [];
    
    // Function to validate email
    function validateEmail(email) {
        const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(String(email).toLowerCase());
    }
    
    // Function to add email tag
    function addEmailTag(email) {
        email = email.trim();
        if (!email) return;
        
        // Check if email already exists
        if (emails.includes(email)) {
            return;
        }
        
        const isValid = validateEmail(email);
        emails.push(email);
        
        const tag = document.createElement('div');
        tag.className = `email-tag ${isValid ? '' : 'invalid'}`;
        tag.dataset.email = email;
        
        tag.innerHTML = `
            <span>${email}</span>
            <span class="remove-tag" data-email="${email}"><i class="fas fa-times"></i></span>
            <input type="hidden" name="recipient_emails[]" value="${email}">
        `;
        
        emailTags.appendChild(tag);
        emailInput.value = '';
        
        // Add event listener to remove button
        tag.querySelector('.remove-tag').addEventListener('click', function() {
            removeEmailTag(this.dataset.email);
        });
    }
    
    // Function to remove email tag
    function removeEmailTag(email) {
        const tags = emailTags.querySelectorAll('.email-tag');
        emails = emails.filter(e => e !== email);
        
        tags.forEach(tag => {
            if (tag.dataset.email === email) {
                tag.remove();
            }
        });
    }
    
    // Event listener for email input
    emailInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' || e.key === ',') {
            e.preventDefault();
            
            const inputValue = this.value.trim();
            if (inputValue) {
                addEmailTag(inputValue);
            }
        } else if (e.key === 'Backspace' && !this.value) {
            // Remove the last tag when pressing backspace with empty input
            if (emails.length > 0) {
                removeEmailTag(emails[emails.length - 1]);
            }
        }
    });
    
    // Allow clicking the container to focus the input
    emailTagsContainer.addEventListener('click', function() {
        emailInput.focus();
    });
    
    // Form submission
    shareForm.addEventListener('submit', function(e) {
        let hasInvalid = false;
        
        // Validate all emails
        const tags = emailTags.querySelectorAll('.email-tag');
        tags.forEach(tag => {
            const email = tag.dataset.email;
            const isValid = validateEmail(email);
            
            if (!isValid) {
                tag.classList.add('invalid');
                hasInvalid = true;
            } else {
                tag.classList.remove('invalid');
            }
        });
        
        // Check if there are any emails
        if (emails.length === 0) {
            emailTagsContainer.classList.add('is-invalid');
            emailErrors.textContent = 'Please enter at least one email address';
            e.preventDefault();
            return;
        }
        
        // Check if there are invalid emails
        if (hasInvalid) {
            emailTagsContainer.classList.add('is-invalid');
            emailErrors.textContent = 'Please correct the invalid email addresses';
            e.preventDefault();
            return;
        }
        
        emailTagsContainer.classList.remove('is-invalid');
    });
    
    // Delete share confirmation
    const removeShareLinks = document.querySelectorAll('.remove-share');
    if (removeShareLinks.length > 0) {
        removeShareLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                if (!confirm('Are you sure you want to remove sharing with this user?')) {
                    e.preventDefault();
                }
            });
        });
    }
});
</script>