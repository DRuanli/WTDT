<div class="profile-container">
    <div class="profile-header">
        <h2>Edit Profile</h2>
        <a href="<?= BASE_URL ?>/profile" class="btn btn-outline">
            <i class="fas fa-arrow-left"></i> Back to Profile
        </a>
    </div>
    
    <?php if (!empty($data['errors']['general'])): ?>
        <div class="alert alert-danger">
            <?= $data['errors']['general'] ?>
        </div>
    <?php endif; ?>
    
    <div class="profile-card">
        <form method="POST" class="profile-form">
            <div class="form-group">
                <label for="display_name">Display Name</label>
                <input type="text" id="display_name" name="display_name" class="form-control" value="<?= htmlspecialchars($data['user']['display_name']) ?>" required>
                <?php if (!empty($data['errors']['display_name'])): ?>
                    <div class="invalid-feedback"><?= $data['errors']['display_name'] ?></div>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" class="form-control" value="<?= htmlspecialchars($data['user']['email']) ?>" disabled>
                <small class="form-text text-muted">Email cannot be changed.</small>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save Changes
                </button>
                <a href="<?= BASE_URL ?>/profile" class="btn btn-outline">Cancel</a>
            </div>

            <div class="form-group">
                <label>Profile Picture</label>
                <div class="avatar-container">
                    <?php if (isset($data['user']['avatar_path']) && !empty($data['user']['avatar_path'])): ?>
                        <img src="<?= BASE_URL ?>/uploads/avatars/<?= $data['user']['avatar_path'] ?>" alt="Avatar" class="profile-avatar">
                    <?php else: ?>
                        <div class="avatar-placeholder">
                            <i class="fas fa-user"></i>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="avatar-upload">
                    <form method="POST" action="<?= BASE_URL ?>/profile/upload-avatar" enctype="multipart/form-data">
                        <input type="file" name="avatar" id="avatar-input" accept="image/jpeg,image/png">
                        <label for="avatar-input" class="btn btn-outline">Choose New Image</label>
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </form>
                </div>
                
                <?php if (isset($data['user']['avatar_path']) && !empty($data['user']['avatar_path'])): ?>
                    <div class="avatar-remove">
                        <form method="POST" action="<?= BASE_URL ?>/profile/upload-avatar">
                            <input type="hidden" name="remove_avatar" value="1">
                            <button type="submit" class="btn btn-danger">Remove Avatar</button>
                        </form>
                    </div>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>

<style>
.profile-container {
    max-width: 800px;
    margin: 0 auto;
}

.profile-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.profile-card {
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    padding: 2rem;
}

.profile-form .form-group {
    margin-bottom: 1.5rem;
}

.profile-form label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
}

.form-actions {
    display: flex;
    gap: 1rem;
    margin-top: 2rem;
}

.form-text {
    display: block;
    margin-top: 0.25rem;
    font-size: 0.875rem;
}

.text-muted {
    color: #6c757d;
}

@media (max-width: 768px) {
    .profile-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .form-actions {
        flex-direction: column;
    }
}
</style>
