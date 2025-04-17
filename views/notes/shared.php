<div class="row">
    <div class="col-md-3 mb-4">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center bg-light">
                <h5 class="card-title mb-0">Shared Notes</h5>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="badge bg-primary rounded-pill me-2">
                        <?= count($data['notes']) ?>
                    </div>
                    <span>Notes shared with you</span>
                </div>
                <div class="text-muted small">
                    <p>This page shows notes that other users have shared with you. 
                    Notes are organized by most recently shared.</p>
                    <p class="mb-0"><i class="fas fa-info-circle me-1"></i> Notes with <span class="badge bg-success">Can Edit</span> support real-time collaboration.</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-9">
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
        
        <?php if (empty($data['notes'])): ?>
            <div class="card shadow-sm">
                <div class="card-body text-center py-5">
                    <div class="display-1 text-muted mb-3"><i class="fas fa-share-alt"></i></div>
                    <h3>No shared notes yet</h3>
                    <p class="text-muted">No one has shared any notes with you yet.</p>
                    <p class="text-muted">When someone shares a note with you, it will appear here.</p>
                </div>
            </div>
        <?php else: ?>
            <div class="row row-cols-1 row-cols-md-2 g-4">
                <?php foreach ($data['notes'] as $note): ?>
                    <div class="col">
                        <div class="card h-100 shadow-sm">
                            <div class="card-header bg-white pb-0 d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0 text-truncate">
                                    <?php if (isset($note['is_password_protected']) && $note['is_password_protected']): ?>
                                        <a href="<?= BASE_URL ?>/notes/verify-password/<?= $note['id'] ?>" class="text-decoration-none">
                                            <i class="fas fa-lock me-1 text-warning"></i><?= htmlspecialchars($note['title']) ?>
                                        </a>
                                    <?php else: ?>
                                        <a href="<?= BASE_URL ?>/notes/edit/<?= $note['id'] ?>" class="text-decoration-none">
                                            <?= htmlspecialchars($note['title']) ?>
                                        </a>
                                    <?php endif; ?>
                                </h5>
                                
                                <?php if (isset($note['can_edit']) && $note['can_edit']): ?>
                                    <span class="badge bg-success">Can Edit</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Read Only</span>
                                <?php endif; ?>
                            </div>
                            
                            <div class="card-body">
                                <div class="card-text note-content">
                                    <?php 
                                    $content = isset($note['content']) ? $note['content'] : '';
                                    $preview = strip_tags($content);
                                    $preview = substr($preview, 0, 150);
                                    if (strlen($content) > 150) $preview .= '...';
                                    echo nl2br(htmlspecialchars($preview));
                                    ?>
                                </div>
                                
                                <div class="mt-3">
                                    <div class="d-flex align-items-center text-muted">
                                        <i class="fas fa-user me-2"></i>
                                        <span>Shared by: <strong><?= htmlspecialchars($note['owner_name']) ?></strong></span>
                                    </div>
                                    <div class="d-flex align-items-center text-muted mt-2">
                                        <i class="fas fa-clock me-2"></i>
                                        <span>Shared on: 
                                            <?php 
                                            $shared_at = new DateTime($note['shared_at']);
                                            echo $shared_at->format('M j, Y g:i A');
                                            ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card-footer bg-transparent d-flex justify-content-between align-items-center">
                                <div>
                                    <?php if (isset($note['is_password_protected']) && $note['is_password_protected']): ?>
                                        <span class="me-2 text-warning" title="Password Protected">
                                            <i class="fas fa-lock"></i>
                                        </span>
                                    <?php endif; ?>
                                    
                                    <?php if (isset($note['can_edit']) && $note['can_edit']): ?>
                                        <span class="me-2 text-success" title="Real-time collaboration enabled">
                                            <i class="fas fa-users"></i>
                                        </span>
                                    <?php endif; ?>
                                </div>
                                
                                <?php if (isset($note['can_edit']) && $note['can_edit']): ?>
                                    <a href="<?= BASE_URL ?>/notes/edit/<?= $note['id'] ?>" class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit me-1"></i> Edit
                                    </a>
                                <?php else: ?>
                                    <a href="<?= BASE_URL ?>/notes/view/<?= $note['id'] ?>" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye me-1"></i> View
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Real-time collaboration notification toast -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1050;">
    <div id="collaborationToast" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <i class="fas fa-users me-2 text-primary"></i>
            <strong class="me-auto">Collaboration</strong>
            <small>Just now</small>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body" id="collaborationToastBody">
            Someone is editing this note.
        </div>
    </div>
</div>

<!-- For remote cursors when collaborating -->
<style>
.remote-cursor {
    position: absolute;
    z-index: 100;
    pointer-events: none;
}

.remote-cursor-label {
    position: absolute;
    top: -20px;
    left: 0;
    background-color: #3498db;
    color: white;
    padding: 2px 5px;
    border-radius: 3px;
    font-size: 12px;
    white-space: nowrap;
}

.remote-cursor-caret {
    width: 2px;
    height: 20px;
    background-color: #3498db;
    animation: blink 1s infinite;
}

@keyframes blink {
    0%, 100% { opacity: 1; }
    50% { opacity: 0; }
}
</style>