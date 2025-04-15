<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-user-edit text-primary me-2"></i>Edit Profile
                    </h4>
                    <a href="<?= BASE_URL ?>/profile" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back to Profile
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
                
                <div class="row">
                    <!-- Profile Photo Section -->
                    <div class="col-md-4 mb-4 mb-md-0">
                        <div class="text-center">
                            <h5 class="mb-3">Profile Photo</h5>
                            
                            <div class="avatar-container mb-3">
                                <?php if (isset($data['user']['avatar_path']) && !empty($data['user']['avatar_path'])): ?>
                                    <img src="<?= BASE_URL ?>/uploads/avatars/<?= $data['user']['avatar_path'] ?>" 
                                         alt="Avatar" class="img-fluid rounded-circle" 
                                         style="width: 150px; height: 150px; object-fit: cover;">
                                <?php else: ?>
                                    <div class="avatar-placeholder rounded-circle mx-auto d-flex align-items-center justify-content-center bg-light" 
                                         style="width: 150px; height: 150px;">
                                        <i class="fas fa-user fa-4x text-secondary"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <form method="POST" action="<?= BASE_URL ?>/profile/upload-avatar" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label for="avatar" class="form-label visually-hidden">Choose Image</label>
                                    <input class="form-control form-control-sm" id="avatar" name="avatar" type="file" accept="image/jpeg,image/png">
                                    <div class="form-text">Max file size: 2MB. JPG or PNG only.</div>
                                </div>
                                
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="fas fa-upload me-1"></i> Upload Photo
                                    </button>
                                    
                                    <?php if (isset($data['user']['avatar_path']) && !empty($data['user']['avatar_path'])): ?>
                                        <button type="submit" class="btn btn-outline-danger btn-sm" name="remove_avatar" value="1">
                                            <i class="fas fa-trash me-1"></i> Remove Photo
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Profile Details Section -->
                    <div class="col-md-8">
                        <h5 class="mb-3">Account Information</h5>
                        
                        <form method="POST" action="<?= BASE_URL ?>/profile/edit">
                            <div class="mb-3">
                                <label for="display_name" class="form-label">Display Name</label>
                                <input type="text" class="form-control <?= !empty($data['errors']['display_name']) ? 'is-invalid' : '' ?>" 
                                       id="display_name" name="display_name" 
                                       value="<?= htmlspecialchars($data['user']['display_name']) ?>" required>
                                <?php if (!empty($data['errors']['display_name'])): ?>
                                    <div class="invalid-feedback"><?= $data['errors']['display_name'] ?></div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control bg-light" id="email" 
                                       value="<?= htmlspecialchars($data['user']['email']) ?>" disabled>
                                <div class="form-text">Email address cannot be changed.</div>
                            </div>
                            
                            <?php if (isset($data['user']['is_activated']) && !$data['user']['is_activated']): ?>
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle me-2"></i>Your account is not verified.
                                    <form action="<?= BASE_URL ?>/resend-activation" method="POST" class="d-inline">
                                        <input type="hidden" name="resend" value="1">
                                        <button type="submit" class="btn btn-link p-0 d-inline">Resend activation email</button>
                                    </form>
                                </div>
                            <?php endif; ?>
                            
                            <div class="d-flex justify-content-between mt-4">
                                <a href="<?= BASE_URL ?>/profile/change-password" class="btn btn-outline-secondary">
                                    <i class="fas fa-key me-1"></i> Change Password
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i> Save Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const avatarInput = document.getElementById('avatar');
    const avatarContainer = document.querySelector('.avatar-container');
    
    if (avatarInput && avatarContainer) {
        avatarInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    // Create or update preview image
                    let avatarPreview = avatarContainer.querySelector('img');
                    
                    if (!avatarPreview) {
                        // Remove placeholder if it exists
                        const placeholder = avatarContainer.querySelector('.avatar-placeholder');
                        if (placeholder) {
                            avatarContainer.removeChild(placeholder);
                        }
                        
                        // Create new image element
                        avatarPreview = document.createElement('img');
                        avatarPreview.className = 'img-fluid rounded-circle';
                        avatarPreview.style.width = '150px';
                        avatarPreview.style.height = '150px';
                        avatarPreview.style.objectFit = 'cover';
                        avatarContainer.appendChild(avatarPreview);
                    }
                    
                    // Set the preview source
                    avatarPreview.src = e.target.result;
                    avatarPreview.alt = 'Avatar Preview';
                };
                
                reader.readAsDataURL(this.files[0]);
            }
        });
    }
});
</script>