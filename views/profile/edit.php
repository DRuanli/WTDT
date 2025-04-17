<div class="container py-4">
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <!-- Profile Navigation Tabs -->
            <div class="card shadow-sm mb-4">
                <div class="card-body p-0">
                    <nav class="profile-nav">
                        <div class="nav nav-tabs nav-fill" id="profile-tabs">
                            <a class="nav-item nav-link" href="<?= BASE_URL ?>/profile">
                                <i class="fas fa-user-circle me-2"></i>Profile
                            </a>
                            <a class="nav-item nav-link active" href="<?= BASE_URL ?>/profile/edit">
                                <i class="fas fa-edit me-2"></i>Edit Profile
                            </a>
                            <a class="nav-item nav-link" href="<?= BASE_URL ?>/profile/change-password">
                                <i class="fas fa-key me-2"></i>Security
                            </a>
                            <a class="nav-item nav-link" href="<?= BASE_URL ?>/profile/preferences">
                                <i class="fas fa-cog me-2"></i>Preferences
                            </a>
                        </div>
                    </nav>
                </div>
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
            
            <div class="card shadow-sm profile-card">
                <div class="card-body p-0">
                    <div class="row g-0">
                        <!-- Profile Photo Section -->
                        <div class="col-md-4 border-end">
                            <div class="p-4 text-center">
                                <h5 class="mb-4">Profile Photo</h5>
                                
                                <div class="avatar-container mb-4 position-relative mx-auto">
                                    <?php if (isset($data['user']['avatar_path']) && !empty($data['user']['avatar_path'])): ?>
                                        <img src="<?= BASE_URL ?>/uploads/avatars/<?= $data['user']['avatar_path'] ?>" 
                                             alt="Avatar" class="profile-avatar" id="avatar-preview">
                                    <?php else: ?>
                                        <div class="avatar-placeholder rounded-circle mx-auto d-flex align-items-center justify-content-center bg-light" id="avatar-placeholder">
                                            <i class="fas fa-user fa-4x text-secondary"></i>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="avatar-overlay">
                                        <label for="avatar" class="avatar-edit-btn">
                                            <i class="fas fa-camera"></i>
                                        </label>
                                    </div>
                                </div>
                                
                                <form method="POST" action="<?= BASE_URL ?>/profile/upload-avatar" enctype="multipart/form-data" id="avatar-form">
                                    <div class="mb-3">
                                        <input class="form-control form-control-sm visually-hidden" id="avatar" name="avatar" type="file" accept="image/jpeg,image/png">
                                        <div class="form-text">Max file size: 2MB. JPG or PNG only.</div>
                                    </div>
                                    
                                    <div class="d-grid gap-2">
                                        <button type="submit" class="btn btn-primary btn-sm" id="upload-avatar-btn" disabled>
                                            <i class="fas fa-upload me-1"></i> Update Photo
                                        </button>
                                        
                                        <?php if (isset($data['user']['avatar_path']) && !empty($data['user']['avatar_path'])): ?>
                                            <button type="submit" class="btn btn-outline-danger btn-sm" name="remove_avatar" value="1" id="remove-avatar-btn">
                                                <i class="fas fa-trash me-1"></i> Remove Photo
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </form>
                            </div>
                        </div>
                        
                        <!-- Profile Details Section -->
                        <div class="col-md-8">
                            <div class="p-4">
                                <h5 class="mb-4">Account Information</h5>
                                
                                <form method="POST" action="<?= BASE_URL ?>/profile/edit" id="profile-form">
                                    <div class="mb-4">
                                        <label for="display_name" class="form-label">Display Name</label>
                                        <input type="text" class="form-control <?= !empty($data['errors']['display_name']) ? 'is-invalid' : '' ?>" 
                                               id="display_name" name="display_name" 
                                               value="<?= htmlspecialchars($data['user']['display_name']) ?>" required>
                                        <?php if (!empty($data['errors']['display_name'])): ?>
                                            <div class="invalid-feedback"><?= $data['errors']['display_name'] ?></div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for="email" class="form-label">Email Address</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light">
                                                <i class="fas fa-envelope text-muted"></i>
                                            </span>
                                            <input type="email" class="form-control bg-light" id="email" 
                                                   value="<?= htmlspecialchars($data['user']['email']) ?>" disabled>
                                        </div>
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
                                    
                                    <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                                        <a href="<?= BASE_URL ?>/profile" class="btn btn-outline-secondary">
                                            <i class="fas fa-times me-1"></i> Cancel
                                        </a>
                                        <button type="submit" class="btn btn-primary" id="save-profile-btn">
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
    </div>
</div>

<!-- Additional CSS for Edit Profile -->
<style>
.avatar-container {
    position: relative;
    width: 150px;
    height: 150px;
    border-radius: 50%;
    overflow: hidden;
}

.avatar-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background-color: rgba(0, 0, 0, 0.5);
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.avatar-container:hover .avatar-overlay {
    opacity: 1;
}

.avatar-edit-btn {
    color: white;
    cursor: pointer;
    font-size: 1.25rem;
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.avatar-edit-btn:hover {
    color: #4a89dc;
}

.input-group-text {
    border-right: 0;
}

.input-group .form-control:disabled {
    border-left: 0;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Avatar functionality
    const avatarInput = document.getElementById('avatar');
    const avatarContainer = document.querySelector('.avatar-container');
    const avatarForm = document.getElementById('avatar-form');
    const uploadAvatarBtn = document.getElementById('upload-avatar-btn');
    const profileForm = document.getElementById('profile-form');
    const saveProfileBtn = document.getElementById('save-profile-btn');
    
    // Handle avatar file selection
    if (avatarInput && avatarContainer) {
        avatarInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const file = this.files[0];
                
                // Validate file size
                if (file.size > 2 * 1024 * 1024) {
                    alert('File is too large. Maximum size is 2MB.');
                    this.value = ''; // Clear the input
                    return;
                }
                
                // Validate file type
                if (!['image/jpeg', 'image/jpg', 'image/png'].includes(file.type)) {
                    alert('Invalid file type. Only JPG and PNG are allowed.');
                    this.value = ''; // Clear the input
                    return;
                }
                
                // Enable the upload button
                uploadAvatarBtn.disabled = false;
                
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    // Create or update preview image
                    let avatarPreview = document.getElementById('avatar-preview');
                    const placeholder = document.getElementById('avatar-placeholder');
                    
                    if (!avatarPreview) {
                        // Remove placeholder if it exists
                        if (placeholder) {
                            placeholder.style.display = 'none';
                        }
                        
                        // Create new image element
                        avatarPreview = document.createElement('img');
                        avatarPreview.id = 'avatar-preview';
                        avatarPreview.className = 'profile-avatar';
                        avatarContainer.insertBefore(avatarPreview, avatarContainer.firstChild);
                    } else if (placeholder) {
                        placeholder.style.display = 'none';
                    }
                    
                    // Set the preview source
                    avatarPreview.src = e.target.result;
                    avatarPreview.alt = 'Avatar Preview';
                    avatarPreview.style.display = 'block';
                };
                
                reader.readAsDataURL(file);
            }
        });
    }
    
    // Add loading state to buttons when clicked
    if (uploadAvatarBtn) {
        avatarForm.addEventListener('submit', function() {
            if (avatarInput.files.length > 0 || document.querySelector('button[name="remove_avatar"]')) {
                uploadAvatarBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Uploading...';
                uploadAvatarBtn.disabled = true;
            }
        });
    }
    
    if (saveProfileBtn) {
        profileForm.addEventListener('submit', function() {
            saveProfileBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Saving...';
            saveProfileBtn.disabled = true;
        });
    }
    
    // Make clicking on avatar open file dialog
    const avatarEditBtn = document.querySelector('.avatar-edit-btn');
    if (avatarEditBtn) {
        avatarEditBtn.addEventListener('click', function() {
            avatarInput.click();
        });
    }
});
</script>