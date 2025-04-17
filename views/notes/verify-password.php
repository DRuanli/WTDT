<div class="row">
    <div class="col-md-6 col-lg-4 mx-auto">
        <div class="card shadow-sm">
            <div class="card-header bg-white text-center py-4">
                <div class="display-6 text-warning mb-3">
                    <i class="fas fa-lock"></i>
                </div>
                <h4 class="card-title mb-0">Password Protected Note</h4>
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
                    
                    <?php 
                    // Show some note details to help user identify the note
                    if (isset($data['note']['image_count']) && $data['note']['image_count'] > 0) {
                        echo '<div class="mt-2"><i class="fas fa-image me-1"></i> ' . $data['note']['image_count'] . ' image(s) attached</div>';
                    }
                    
                    // Show if note is shared
                    if (isset($data['note']['is_shared']) && $data['note']['is_shared']) {
                        echo '<div class="mt-1"><i class="fas fa-share-alt me-1"></i> Shared with others</div>';
                    }
                    ?>
                </div>
                
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    This note is password protected. Please enter the password to continue.
                </div>
                
                <form method="POST" class="needs-validation" novalidate>
                    <input type="hidden" name="redirect" value="<?= htmlspecialchars($data['redirect']) ?>">
                    
                    <div class="form-floating mb-4">
                        <input type="password" class="form-control <?= !empty($data['errors']['password']) ? 'is-invalid' : '' ?>" 
                               id="password" name="password" placeholder="Password" required autofocus>
                        <label for="password">Password</label>
                        <?php if (!empty($data['errors']['password'])): ?>
                            <div class="invalid-feedback"><?= $data['errors']['password'] ?></div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-unlock-alt me-1"></i> Verify Password
                        </button>
                    </div>
                    
                    <div class="text-center">
                        <a href="<?= BASE_URL ?>/notes" class="btn btn-link">Cancel and go back</a>
                    </div>
                </form>
                
                <div class="text-center mt-4">
                    <small class="text-muted">
                        <i class="fas fa-shield-alt me-1"></i>
                        The contents of this note are encrypted for your security
                    </small>
                </div>
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
    
    // Auto-focus on password field
    window.addEventListener('DOMContentLoaded', function() {
        document.getElementById('password').focus();
    });
})();
</script>

<style>
/* Add animation to the lock icon */
.display-6 .fas.fa-lock {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
}
</style>