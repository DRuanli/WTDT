<div class="row">
    <div class="col-md-8 col-lg-6 mx-auto">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <?php if ($data['action'] === 'enable'): ?>
                            <i class="fas fa-lock text-primary me-2"></i>Add Password Protection
                        <?php else: ?>
                            <i class="fas fa-unlock text-warning me-2"></i>Remove Password Protection
                        <?php endif; ?>
                    </h4>
                    <a href="<?= BASE_URL ?>/notes/edit/<?= $data['note']['id'] ?>" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back to Note
                    </a>
                </div>
            </div>
            
            <div class="card-body">
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
                
                <?php if ($data['action'] === 'enable'): ?>
                    <!-- Enable Password Protection Form -->
                    <form method="POST" class="needs-validation" novalidate>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Adding a password will protect this note. You'll need to enter this password every time you want to view, edit, or delete this note.
                        </div>
                        
                        <div class="form-floating mb-3">
                            <input type="password" class="form-control <?= !empty($data['errors']['password']) ? 'is-invalid' : '' ?>" 
                                   id="password" name="password" placeholder="Password" required>
                            <label for="password">Create Password</label>
                            <?php if (!empty($data['errors']['password'])): ?>
                                <div class="invalid-feedback"><?= $data['errors']['password'] ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="form-floating mb-4">
                            <input type="password" class="form-control <?= !empty($data['errors']['confirm_password']) ? 'is-invalid' : '' ?>" 
                                   id="confirm_password" name="confirm_password" placeholder="Confirm Password" required>
                            <label for="confirm_password">Confirm Password</label>
                            <?php if (!empty($data['errors']['confirm_password'])): ?>
                                <div class="invalid-feedback"><?= $data['errors']['confirm_password'] ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-lock me-1"></i> Enable Password Protection
                            </button>
                        </div>
                    </form>
                <?php else: ?>
                    <!-- Disable Password Protection Form -->
                    <form method="POST" class="needs-validation" novalidate>
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            You are about to remove password protection from this note. Please enter the current password to confirm.
                        </div>
                        
                        <div class="form-floating mb-4">
                            <input type="password" class="form-control <?= !empty($data['errors']['current_password']) ? 'is-invalid' : '' ?>" 
                                   id="current_password" name="current_password" placeholder="Current Password" required>
                            <label for="current_password">Current Password</label>
                            <?php if (!empty($data['errors']['current_password'])): ?>
                                <div class="invalid-feedback"><?= $data['errors']['current_password'] ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-unlock me-1"></i> Remove Password Protection
                            </button>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
// Form validation
(function() {
    'use strict';
    var forms = document.querySelectorAll('.needs-validation');
    Array.prototype.slice.call(forms).forEach(function(form) {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
})();
</script>