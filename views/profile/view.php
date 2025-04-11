<div class="row">
    <div class="col-lg-10 mx-auto">
        <div class="d-md-flex justify-content-between align-items-center mb-4">
            <h2 class="h3 mb-3 mb-md-0">
                <i class="fas fa-user-circle me-2 text-primary"></i>My Profile
            </h2>
            <div class="d-flex flex-wrap gap-2">
                <a href="<?= BASE_URL ?>/profile/edit" class="btn btn-primary">
                    <i class="fas fa-edit me-1"></i> Edit Profile
                </a>
                <a href="<?= BASE_URL ?>/profile/preferences" class="btn btn-outline-secondary">
                    <i class="fas fa-cog me-1"></i> Preferences
                </a>
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
                <div class="card shadow-sm h-100">
                    <div class="card-body text-center">
                        <?php if (!empty($data['user']['avatar_path'])): ?>
                            <img src="<?= BASE_URL ?>/uploads/avatars/<?= $data['user']['avatar_path'] ?>" 
                                 alt="Profile Picture" 
                                 class="rounded-circle mb-3" 
                                 style="width: 120px; height: 120px; object-fit: cover;">
                        <?php else: ?>
                            <div class="avatar-placeholder rounded-circle mb-3 d-flex align-items-center justify-content-center bg-light mx-auto" 
                                 style="width: 120px; height: 120px;">
                                <i class="fas fa-user fa-4x text-secondary"></i>
                            </div>
                        <?php endif; ?>
                        
                        <h3 class="h4 mb-1"><?= htmlspecialchars($data['user']['display_name']) ?></h3>
                        <p class="text-muted mb-3"><?= htmlspecialchars($data['user']['email']) ?></p>
                        
                        <?php if ($data['user']['is_activated']): ?>
                            <div class="badge bg-success mb-3">Verified Account</div>
                        <?php else: ?>
                            <div class="badge bg-warning text-dark mb-3">Pending Verification</div>
                        <?php endif; ?>
                        
                        <div class="d-grid gap-2">
                            <a href="<?= BASE_URL ?>/profile/edit" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-pen me-1"></i> Update Profile
                            </a>
                            <a href="<?= BASE_URL ?>/profile/change-password" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-key me-1"></i> Change Password
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- User Details Card -->
            <div class="col-md-8 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-white">
                        <h5 class="card-title mb-0">Account Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Display Name</div>
                            <div class="col-md-8"><?= htmlspecialchars($data['user']['display_name']) ?></div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Email Address</div>
                            <div class="col-md-8"><?= htmlspecialchars($data['user']['email']) ?></div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Account Status</div>
                            <div class="col-md-8">
                                <?php if ($data['user']['is_activated']): ?>
                                    <span class="text-success"><i class="fas fa-check-circle me-1"></i> Verified</span>
                                <?php else: ?>
                                    <span class="text-warning"><i class="fas fa-exclamation-triangle me-1"></i> Pending Verification</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Member Since</div>
                            <div class="col-md-8">
                                <?php 
                                $created_at = new DateTime($data['user']['created_at']);
                                echo $created_at->format('F j, Y');
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Activity Stats Card -->
            <div class="col-12 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="card-title mb-0">Account Statistics</h5>
                    </div>
                    <div class="card-body">
                        <div class="row row-cols-1 row-cols-md-4 g-4 text-center">
                            <div class="col">
                                <div class="p-3 border rounded">
                                    <div class="fs-1 text-primary">
                                        <i class="fas fa-sticky-note"></i>
                                    </div>
                                    <div class="fs-5 mt-2">Total Notes</div>
                                    <div class="fs-2 fw-bold"><?= $data['stats']['total_notes'] ?></div>
                                </div>
                            </div>
                            
                            <div class="col">
                                <div class="p-3 border rounded">
                                    <div class="fs-1 text-success">
                                        <i class="fas fa-tag"></i>
                                    </div>
                                    <div class="fs-5 mt-2">Total Labels</div>
                                    <div class="fs-2 fw-bold"><?= $data['stats']['total_labels'] ?></div>
                                </div>
                            </div>
                            
                            <div class="col">
                                <div class="p-3 border rounded">
                                    <div class="fs-1 text-info">
                                        <i class="fas fa-share-alt"></i>
                                    </div>
                                    <div class="fs-5 mt-2">Shared Notes</div>
                                    <div class="fs-2 fw-bold"><?= $data['stats']['shared_notes'] ?></div>
                                </div>
                            </div>
                            
                            <div class="col">
                                <div class="p-3 border rounded">
                                    <div class="fs-1 text-warning">
                                        <i class="fas fa-image"></i>
                                    </div>
                                    <div class="fs-5 mt-2">Uploaded Images</div>
                                    <div class="fs-2 fw-bold"><?= $data['stats']['uploaded_images'] ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>