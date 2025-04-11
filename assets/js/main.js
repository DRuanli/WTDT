/**
 * Main JavaScript file
 */
 
document.addEventListener('DOMContentLoaded', function() {
    // Password visibility toggle for password fields
    const passwordToggles = document.querySelectorAll('.password-toggle');
    
    passwordToggles.forEach(function(toggle) {
        toggle.addEventListener('click', function() {
            const passwordField = document.getElementById(this.dataset.target);
            
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                this.innerHTML = '<i class="fa fa-eye-slash"></i>';
            } else {
                passwordField.type = 'password';
                this.innerHTML = '<i class="fa fa-eye"></i>';
            }
        });
    });
    
    // Auto-dismiss alerts
    const autoDismissAlerts = document.querySelectorAll('.alert[data-auto-dismiss]');
    
    autoDismissAlerts.forEach(function(alert) {
        const dismissTime = parseInt(alert.dataset.autoDismiss) || 5000;
        
        setTimeout(function() {
            alert.style.opacity = '0';
            setTimeout(function() {
                alert.style.display = 'none';
            }, 300);
        }, dismissTime);
    });
});