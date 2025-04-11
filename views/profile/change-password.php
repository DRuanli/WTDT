<div class="profile-container">
    <div class="profile-header">
        <h2>Change Password</h2>
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
                <label for="current_password">Current Password</label>
                <div class="input-group">
                    <input type="password" id="current_password" name="current_password" class="form-control" required>
                    <span class="toggle-password" data-target="current_password">
                        <i class="fas fa-eye"></i>
                    </span>
                </div>
                <?php if (!empty($data['errors']['current_password'])): ?>
                    <div class="invalid-feedback"><?= $data['errors']['current_password'] ?></div>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label for="new_password">New Password</label>
                <div class="input-group">
                    <input type="password" id="new_password" name="new_password" class="form-control" required minlength="8">
                    <span class="toggle-password" data-target="new_password">
                        <i class="fas fa-eye"></i>
                    </span>
                </div>
                <?php if (!empty($data['errors']['new_password'])): ?>
                    <div class="invalid-feedback"><?= $data['errors']['new_password'] ?></div>
                <?php endif; ?>
                <div class="password-strength" id="password-strength"></div>
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Confirm New Password</label>
                <div class="input-group">
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control" required minlength="8">
                    <span class="toggle-password" data-target="confirm_password">
                        <i class="fas fa-eye"></i>
                    </span>
                </div>
                <?php if (!empty($data['errors']['confirm_password'])): ?>
                    <div class="invalid-feedback"><?= $data['errors']['confirm_password'] ?></div>
                <?php endif; ?>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Change Password
                </button>
                <a href="<?= BASE_URL ?>/profile" class="btn btn-outline">Cancel</a>
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

.input-group {
    position: relative;
    display: flex;
    align-items: center;
}

.toggle-password {
    position: absolute;
    right: 10px;
    color: #777;
    cursor: pointer;
    padding: 5px;
}

.toggle-password:hover {
    color: #333;
}

.password-strength {
    display: flex;
    margin-top: 8px;
    height: 5px;
    gap: 3px;
}

.strength-bar {
    flex: 1;
    height: 100%;
    background-color: #ecf0f1;
    border-radius: 2px;
}

.strength-bar.weak {
    background-color: #e74c3c;
}

.strength-bar.medium {
    background-color: #f39c12;
}

.strength-bar.strong {
    background-color: #2ecc71;
}

.form-actions {
    display: flex;
    gap: 1rem;
    margin-top: 2rem;
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Password toggle functionality
    const toggleButtons = document.querySelectorAll('.toggle-password');
    
    toggleButtons.forEach(button => {
        button.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const passwordInput = document.getElementById(targetId);
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                this.innerHTML = '<i class="fas fa-eye-slash"></i>';
            } else {
                passwordInput.type = 'password';
                this.innerHTML = '<i class="fas fa-eye"></i>';
            }
        });
    });
    
    // Password strength meter
    const passwordInput = document.getElementById('new_password');
    const strengthMeter = document.getElementById('password-strength');
    
    if (passwordInput && strengthMeter) {
        passwordInput.addEventListener('input', function() {
            const value = passwordInput.value;
            let strength = 0;
            
            // Length check
            if (value.length >= 8) strength += 1;
            if (value.length >= 12) strength += 1;
            
            // Character type checks
            if (/[0-9]/.test(value)) strength += 1;
            if (/[a-z]/.test(value)) strength += 1;
            if (/[A-Z]/.test(value)) strength += 1;
            if (/[^A-Za-z0-9]/.test(value)) strength += 1;
            
            // Update the strength meter
            strengthMeter.innerHTML = '';
            for (let i = 0; i < 5; i++) {
                const bar = document.createElement('div');
                bar.className = 'strength-bar';
                if (i < strength) {
                    if (strength <= 2) {
                        bar.classList.add('weak');
                    } else if (strength <= 4) {
                        bar.classList.add('medium');
                    } else {
                        bar.classList.add('strong');
                    }
                }
                strengthMeter.appendChild(bar);
            }
        });
    }
});
</script>