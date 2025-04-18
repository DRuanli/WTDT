<?php
// Always declare the namespace aliases at the file level
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function hasPhpMailer() {
    $has_class = class_exists('PHPMailer\PHPMailer\PHPMailer');
    if (defined('MAIL_DEBUG') && MAIL_DEBUG) {
        error_log('PHPMailer class exists: ' . ($has_class ? 'Yes' : 'No'));
    }
    return $has_class;
}

// Send email with PHPMailer if available
function sendEmailWithPhpMailer($to, $subject, $message) {
    try {
        // Make sure autoloader is included
        if (file_exists(ROOT_PATH . '/vendor/autoload.php')) {
            require_once ROOT_PATH . '/vendor/autoload.php';
        } else {
            error_log('Autoloader not found - PHPMailer may not be installed');
            return false;
        }
        
        $mail = new PHPMailer(true);
        
        // Enable debugging if debug mode is on
        if (defined('MAIL_DEBUG') && MAIL_DEBUG) {
            $mail->SMTPDebug = 2; // Detailed debug output
            $mail->Debugoutput = 'error_log'; // Log to PHP error log
        }
        
        // Server settings
        $mail->isSMTP();
        $mail->Host       = MAIL_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = MAIL_USERNAME;
        $mail->Password   = MAIL_PASSWORD;
        $mail->SMTPSecure = MAIL_ENCRYPTION;
        $mail->Port       = MAIL_PORT;
        
        // Recipients
        $mail->setFrom(MAIL_FROM_ADDRESS, MAIL_FROM_NAME);
        $mail->addAddress($to);
        
        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $message;
        $mail->AltBody = strip_tags($message);
        
        $result = $mail->send();
        
        if (defined('MAIL_DEBUG') && MAIL_DEBUG) {
            error_log('Email attempt to ' . $to . ': ' . ($result ? 'Success' : 'Failed'));
        }
        
        return $result;
    } catch (Exception $e) {
        // Log error
        $error_message = 'Mailer Error: ' . $e->getMessage();
        error_log($error_message);
        
        if (defined('MAIL_DEBUG') && MAIL_DEBUG) {
            // In debug mode, we should display the error
            Session::setFlash('error', 'Failed to send email: ' . $error_message);
        }
        
        return false;
    }
}

// Send activation email
function sendActivationEmail($user_email, $user_name, $activation_token) {
    $activation_link = BASE_URL . '/activate?token=' . $activation_token . '&email=' . urlencode($user_email);
    
    $subject = "Activate Your Account - " . APP_NAME;
    
    $message = "
    <html>
    <head>
        <title>Account Activation</title>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .button { display: inline-block; padding: 10px 20px; background-color: #4CAF50; color: white; 
                     text-decoration: none; border-radius: 5px; margin: 20px 0; }
            .footer { margin-top: 30px; font-size: 12px; color: #777; }
        </style>
    </head>
    <body>
        <div class='container'>
            <h2>Welcome to " . APP_NAME . "</h2>
            <p>Hello " . htmlspecialchars($user_name) . ",</p>
            <p>Thank you for registering. Please click the button below to activate your account:</p>
            <p><a href='" . $activation_link . "' class='button'>Activate Your Account</a></p>
            <p>If the button doesn't work, copy and paste this URL into your browser:</p>
            <p>" . $activation_link . "</p>
            <p>This link will expire in " . (ACTIVATION_TOKEN_EXPIRY / 3600) . " hours.</p>
            <div class='footer'>
                <p>Best regards,<br>The " . APP_NAME . " Team</p>
            </div>
        </div>
    </body>
    </html>
    ";
    
    // Use PHPMailer if available, otherwise use default function
    if (hasPhpMailer()) {
        return sendEmailWithPhpMailer($user_email, $subject, $message);
    } else {
        return sendEmail($user_email, $subject, $message);
    }
}

// Send password reset email
function sendPasswordResetEmail($user_email, $user_name, $reset_token) {
    $reset_link = BASE_URL . '/reset-password?token=' . $reset_token . '&email=' . urlencode($user_email);
    
    $subject = "Password Reset - " . APP_NAME;
    
    $message = "
    <html>
    <head>
        <title>Password Reset</title>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .button { display: inline-block; padding: 10px 20px; background-color: #4CAF50; color: white; 
                     text-decoration: none; border-radius: 5px; margin: 20px 0; }
            .footer { margin-top: 30px; font-size: 12px; color: #777; }
            .warning { color: #ff6600; }
        </style>
    </head>
    <body>
        <div class='container'>
            <h2>" . APP_NAME . " - Password Reset</h2>
            <p>Hello " . htmlspecialchars($user_name) . ",</p>
            <p>You requested a password reset. Please click the button below to reset your password:</p>
            <p><a href='" . $reset_link . "' class='button'>Reset Your Password</a></p>
            <p>If the button doesn't work, copy and paste this URL into your browser:</p>
            <p>" . $reset_link . "</p>
            <p>This link will expire in " . (RESET_TOKEN_EXPIRY / 3600) . " hour(s).</p>
            <p class='warning'>If you didn't request this password reset, please ignore this email or contact support if you're concerned.</p>
            <div class='footer'>
                <p>Best regards,<br>The " . APP_NAME . " Team</p>
            </div>
        </div>
    </body>
    </html>
    ";
    
    // Use PHPMailer if available, otherwise use default function
    if (hasPhpMailer()) {
        return sendEmailWithPhpMailer($user_email, $subject, $message);
    } else {
        return sendEmail($user_email, $subject, $message);
    }
}

// Send OTP email for password reset
function sendOTPEmail($user_email, $user_name, $otp) {
    $subject = "Password Reset OTP - " . APP_NAME;
    
    $message = "
    <html>
    <head>
        <title>Password Reset OTP</title>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .otp-code { font-size: 24px; font-weight: bold; letter-spacing: 5px; margin: 20px 0; 
                       padding: 10px; background-color: #f5f5f5; text-align: center; }
            .footer { margin-top: 30px; font-size: 12px; color: #777; }
            .warning { color: #ff6600; }
        </style>
    </head>
    <body>
        <div class='container'>
            <h2>" . APP_NAME . " - Password Reset</h2>
            <p>Hello " . htmlspecialchars($user_name) . ",</p>
            <p>You requested a password reset. Please use the following verification code (OTP):</p>
            <div class='otp-code'>" . $otp . "</div>
            <p>This code will expire in 1 hour.</p>
            <p class='warning'>If you didn't request this password reset, please ignore this email or contact support if you're concerned.</p>
            <div class='footer'>
                <p>Best regards,<br>The " . APP_NAME . " Team</p>
            </div>
        </div>
    </body>
    </html>
    ";
    
    // Use PHPMailer if available, otherwise use default function
    if (hasPhpMailer()) {
        return sendEmailWithPhpMailer($user_email, $subject, $message);
    } else {
        return sendEmail($user_email, $subject, $message);
    }
}

// Send email notification for shared note
function sendNoteSharedEmail($recipient_email, $recipient_name, $owner_name, $note_title, $permission_type) {
    $subject = "Note Shared With You - " . APP_NAME;
    
    $message = "
    <html>
    <head>
        <title>Note Shared With You</title>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .button { display: inline-block; padding: 10px 20px; background-color: #4a89dc; color: white; 
                     text-decoration: none; border-radius: 5px; margin: 20px 0; }
            .footer { margin-top: 30px; font-size: 12px; color: #777; }
            .permission { font-weight: bold; color: " . ($permission_type == 'edit' ? '#4a89dc' : '#6c757d') . "; }
        </style>
    </head>
    <body>
        <div class='container'>
            <h2>Note Shared With You</h2>
            <p>Hello " . htmlspecialchars($recipient_name) . ",</p>
            <p><strong>" . htmlspecialchars($owner_name) . "</strong> has shared a note with you:</p>
            <p style='font-size: 18px; padding: 10px; background-color: #f8f9fa; border-left: 4px solid #4a89dc;'>\"" . htmlspecialchars($note_title) . "\"</p>
            <p>You have <span class='permission'>" . ($permission_type == 'edit' ? 'edit' : 'view-only') . "</span> access to this note.</p>
            <p>Log in to " . APP_NAME . " to access this shared note:</p>
            <p><a href='" . BASE_URL . "/notes/shared' class='button'>View Shared Notes</a></p>
            <div class='footer'>
                <p>Best regards,<br>The " . APP_NAME . " Team</p>
            </div>
        </div>
    </body>
    </html>
    ";
    
    // Use PHPMailer if available, otherwise use default function
    if (hasPhpMailer()) {
        return sendEmailWithPhpMailer($recipient_email, $subject, $message);
    } else {
        return sendEmail($recipient_email, $subject, $message);
    }
}

// Send email notification for share permission changes
function sendSharePermissionChangedEmail($recipient_email, $recipient_name, $owner_name, $note_title, $new_permission) {
    $subject = "Note Sharing Permissions Updated - " . APP_NAME;
    
    $message = "
    <html>
    <head>
        <title>Note Sharing Permissions Updated</title>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .button { display: inline-block; padding: 10px 20px; background-color: #4a89dc; color: white; 
                     text-decoration: none; border-radius: 5px; margin: 20px 0; }
            .footer { margin-top: 30px; font-size: 12px; color: #777; }
            .permission { font-weight: bold; color: " . ($new_permission == 'edit' ? '#4a89dc' : '#6c757d') . "; }
        </style>
    </head>
    <body>
        <div class='container'>
            <h2>Note Sharing Permissions Updated</h2>
            <p>Hello " . htmlspecialchars($recipient_name) . ",</p>
            <p><strong>" . htmlspecialchars($owner_name) . "</strong> has updated your access permissions for the following note:</p>
            <p style='font-size: 18px; padding: 10px; background-color: #f8f9fa; border-left: 4px solid #4a89dc;'>\"" . htmlspecialchars($note_title) . "\"</p>
            <p>Your new permission level is: <span class='permission'>" . ($new_permission == 'edit' ? 'Edit Access' : 'View-Only Access') . "</span></p>
            <p>Log in to " . APP_NAME . " to access this shared note:</p>
            <p><a href='" . BASE_URL . "/notes/shared' class='button'>View Shared Notes</a></p>
            <div class='footer'>
                <p>Best regards,<br>The " . APP_NAME . " Team</p>
            </div>
        </div>
    </body>
    </html>
    ";
    
    // Use PHPMailer if available, otherwise use default function
    if (hasPhpMailer()) {
        return sendEmailWithPhpMailer($recipient_email, $subject, $message);
    } else {
        return sendEmail($recipient_email, $subject, $message);
    }
}

// Send notification when sharing is removed
function sendShareRemovedEmail($recipient_email, $recipient_name, $owner_name, $note_title) {
    $subject = "Note Access Removed - " . APP_NAME;
    
    $message = "
    <html>
    <head>
        <title>Note Access Removed</title>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .footer { margin-top: 30px; font-size: 12px; color: #777; }
        </style>
    </head>
    <body>
        <div class='container'>
            <h2>Note Access Removed</h2>
            <p>Hello " . htmlspecialchars($recipient_name) . ",</p>
            <p><strong>" . htmlspecialchars($owner_name) . "</strong> has removed your access to the following note:</p>
            <p style='font-size: 18px; padding: 10px; background-color: #f8f9fa; border-left: 4px solid #dc3545;'>\"" . htmlspecialchars($note_title) . "\"</p>
            <p>You no longer have access to view or edit this note.</p>
            <div class='footer'>
                <p>Best regards,<br>The " . APP_NAME . " Team</p>
            </div>
        </div>
    </body>
    </html>
    ";
    
    // Use PHPMailer if available, otherwise use default function
    if (hasPhpMailer()) {
        return sendEmailWithPhpMailer($recipient_email, $subject, $message);
    } else {
        return sendEmail($recipient_email, $subject, $message);
    }
}

// Helper function to use plain PHP mail function when PHPMailer is not available
function sendEmail($to, $subject, $message) {
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: " . MAIL_FROM_NAME . " <" . MAIL_FROM_ADDRESS . ">" . "\r\n";
    
    $result = mail($to, $subject, $message, $headers);
    
    if (defined('MAIL_DEBUG') && MAIL_DEBUG) {
        error_log('Fallback mail() to ' . $to . ': ' . ($result ? 'Success' : 'Failed'));
        if (!$result) {
            error_log('Mail error: ' . error_get_last()['message']);
        }
    }
    
    return $result;
}

// Add this to the bottom of utils/Mailer.php
function testEmailConfiguration() {
    $to = MAIL_USERNAME; // Send to yourself for testing
    $subject = 'Test Email from ' . APP_NAME;
    $message = "
    <html>
    <body>
        <h2>Test Email</h2>
        <p>This is a test email to verify your email configuration is working.</p>
        <p>Time sent: " . date('Y-m-d H:i:s') . "</p>
    </body>
    </html>
    ";
    
    // Try with PHPMailer first
    if (hasPhpMailer()) {
        $result = sendEmailWithPhpMailer($to, $subject, $message);
        error_log('PHPMailer test result: ' . ($result ? 'Success' : 'Failed'));
        return $result;
    } else {
        // Fall back to regular mail function
        $result = sendEmail($to, $subject, $message);
        error_log('Regular mail test result: ' . ($result ? 'Success' : 'Failed'));
        return $result;
    }
}