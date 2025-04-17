<div class="container py-4">
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <!-- Profile Navigation Tabs -->
            <div class="card shadow-sm mb-4">
                <div class="card-body p-0">
                    <nav class="profile-nav">
                        <div class="nav nav-tabs nav-fill" id="profile-tabs">
                            <a class="nav-item nav-link active" href="<?= BASE_URL ?>/profile">
                                <i class="fas fa-user-circle me-2"></i>Profile
                            </a>
                            <a class="nav-item nav-link" href="<?= BASE_URL ?>/profile/edit">
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
            
            <div class="row">
                <!-- User Summary Card -->
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm h-100 profile-card">
                        <div class="card-body text-center">
                            <div class="avatar-container mb-3">
                                <?php if (!empty($data['user']['avatar_path'])): ?>
                                    <img src="<?= BASE_URL ?>/uploads/avatars/<?= $data['user']['avatar_path'] ?>" 
                                         alt="Profile Picture" 
                                         class="rounded-circle profile-avatar">
                                <?php else: ?>
                                    <div class="avatar-placeholder rounded-circle d-flex align-items-center justify-content-center bg-light mx-auto">
                                        <i class="fas fa-user fa-4x text-secondary"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <h3 class="h4 mb-1"><?= htmlspecialchars($data['user']['display_name']) ?></h3>
                            <p class="text-muted mb-3"><?= htmlspecialchars($data['user']['email']) ?></p>
                            
                            <?php if ($data['user']['is_activated']): ?>
                                <div class="badge bg-success-subtle text-success mb-3 p-2">
                                    <i class="fas fa-check-circle me-1"></i> Verified Account
                                </div>
                            <?php else: ?>
                                <div class="badge bg-warning-subtle text-warning mb-3 p-2">
                                    <i class="fas fa-exclamation-triangle me-1"></i> Pending Verification
                                </div>
                            <?php endif; ?>
                            
                            <div class="d-grid gap-2">
                                <a href="<?= BASE_URL ?>/profile/edit" class="btn btn-primary">
                                    <i class="fas fa-pen me-1"></i> Update Profile
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- User Details Card -->
                <div class="col-md-8">
                    <div class="card shadow-sm h-100 profile-card">
                        <div class="card-header bg-transparent border-bottom-0">
                            <h5 class="card-title mb-0">Account Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="info-list">
                                <div class="info-item">
                                    <div class="info-label">Display Name</div>
                                    <div class="info-value"><?= htmlspecialchars($data['user']['display_name']) ?></div>
                                </div>
                                
                                <div class="info-item">
                                    <div class="info-label">Email Address</div>
                                    <div class="info-value"><?= htmlspecialchars($data['user']['email']) ?></div>
                                </div>
                                
                                <div class="info-item">
                                    <div class="info-label">Account Status</div>
                                    <div class="info-value">
                                        <?php if ($data['user']['is_activated']): ?>
                                            <span class="text-success"><i class="fas fa-check-circle me-1"></i> Verified</span>
                                        <?php else: ?>
                                            <span class="text-warning">
                                                <i class="fas fa-exclamation-triangle me-1"></i> Pending Verification
                                                <form action="<?= BASE_URL ?>/resend-activation" method="POST" class="d-inline">
                                                    <input type="hidden" name="resend" value="1">
                                                    <button type="submit" class="btn btn-link text-warning p-0 d-inline text-decoration-underline">
                                                        Resend activation email
                                                    </button>
                                                </form>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <div class="info-item">
                                    <div class="info-label">Member Since</div>
                                    <div class="info-value">
                                        <?php 
                                        $created_at = new DateTime($data['user']['created_at']);
                                        echo $created_at->format('F j, Y');
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Activity Stats Card -->
                <div class="col-12 mt-4">
                    <div class="card shadow-sm profile-card stats-card">
                        <div class="card-header bg-transparent border-bottom-0">
                            <h5 class="card-title mb-0">Account Statistics</h5>
                        </div>
                        <div class="card-body">
                            <div class="row row-cols-1 row-cols-md-4 g-4">
                                <div class="col">
                                    <div class="stat-box">
                                        <div class="stat-icon">
                                            <i class="fas fa-sticky-note"></i>
                                        </div>
                                        <div class="stat-details">
                                            <div class="stat-value"><?= $data['stats']['total_notes'] ?></div>
                                            <div class="stat-label">Total Notes</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col">
                                    <div class="stat-box">
                                        <div class="stat-icon">
                                            <i class="fas fa-tag"></i>
                                        </div>
                                        <div class="stat-details">
                                            <div class="stat-value"><?= $data['stats']['total_labels'] ?></div>
                                            <div class="stat-label">Total Labels</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col">
                                    <div class="stat-box">
                                        <div class="stat-icon">
                                            <i class="fas fa-share-alt"></i>
                                        </div>
                                        <div class="stat-details">
                                            <div class="stat-value"><?= $data['stats']['shared_notes'] ?></div>
                                            <div class="stat-label">Shared Notes</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col">
                                    <div class="stat-box">
                                        <div class="stat-icon">
                                            <i class="fas fa-image"></i>
                                        </div>
                                        <div class="stat-details">
                                            <div class="stat-value"><?= $data['stats']['uploaded_images'] ?></div>
                                            <div class="stat-label">Uploaded Images</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Global CSS for Profile Pages -->
<style>
/* Profile Page Styles */
.profile-nav .nav-link {
    border-radius: 0;
    padding: 1rem;
    font-weight: 500;
    color: #6c757d;
    transition: all 0.2s ease;
}

.profile-nav .nav-link.active {
    color: #4a89dc;
    border-bottom: 2px solid #4a89dc;
    background-color: rgba(74, 137, 220, 0.05);
}

.profile-nav .nav-link:hover:not(.active) {
    background-color: rgba(0, 0, 0, 0.03);
}

.profile-card {
    border: none;
    border-radius: 10px;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.profile-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
}

.avatar-container {
    position: relative;
}

.profile-avatar, .avatar-placeholder {
    width: 130px;
    height: 130px;
    object-fit: cover;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    border: 3px solid #fff;
}

.info-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.info-item {
    padding-bottom: 1rem;
    border-bottom: 1px solid #f0f0f0;
    display: flex;
    flex-wrap: wrap;
}

.info-label {
    flex: 0 0 150px;
    font-weight: 600;
    color: #495057;
}

.info-value {
    flex: 1;
    color: #212529;
}

.stat-box {
    display: flex;
    align-items: center;
    background-color: #f8f9fa;
    border-radius: 10px;
    padding: 1.25rem;
    height: 100%;
    transition: all 0.2s ease;
}

.stat-box:hover {
    background-color: #fff;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    transform: translateY(-3px);
}

.stat-icon {
    font-size: 2.5rem;
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    margin-right: 1rem;
    color: #fff;
}

.stat-details {
    flex: 1;
}

.stat-value {
    font-size: 1.75rem;
    font-weight: 700;
    line-height: 1;
    margin-bottom: 0.25rem;
}

.stat-label {
    font-size: 0.875rem;
    color: #6c757d;
}

/* Individual stat styling */
.col:nth-child(1) .stat-icon {
    background-color: rgba(74, 137, 220, 0.15);
    color: #4a89dc;
}

.col:nth-child(2) .stat-icon {
    background-color: rgba(40, 167, 69, 0.15);
    color: #28a745;
}

.col:nth-child(3) .stat-icon {
    background-color: rgba(23, 162, 184, 0.15);
    color: #17a2b8;
}

.col:nth-child(4) .stat-icon {
    background-color: rgba(255, 193, 7, 0.15);
    color: #ffc107;
}

/* Responsive adjustments */
@media (max-width: 767.98px) {
    .info-label {
        flex: 0 0 100%;
        margin-bottom: 0.25rem;
    }
    
    .info-value {
        flex: 0 0 100%;
    }
    
    .stat-box {
        flex-direction: column;
        text-align: center;
        padding: 1rem;
    }
    
    .stat-icon {
        margin-right: 0;
        margin-bottom: 0.75rem;
    }
}

/* Custom form styling */
.form-control {
    border-radius: 0.375rem;
    padding: 0.625rem 0.75rem;
    border-color: #ced4da;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

.form-control:focus {
    border-color: #4a89dc;
    box-shadow: 0 0 0 0.25rem rgba(74, 137, 220, 0.25);
}

.btn-primary {
    background-color: #4a89dc;
    border-color: #4a89dc;
}

.btn-primary:hover {
    background-color: #3a77c5;
    border-color: #3a77c5;
}

.btn-outline-secondary {
    color: #6c757d;
    border-color: #6c757d;
}

.btn-outline-secondary:hover {
    background-color: #f8f9fa;
    color: #495057;
}
</style>