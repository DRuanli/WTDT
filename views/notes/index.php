<div class="container-fluid py-4 notes-dashboard">
    <div class="row g-4">
        <!-- Sidebar with Labels -->
        <div class="col-lg-3 col-md-4">
            <div class="sidebar-wrapper sticky-top" style="top: 90px;">
                <div class="card shadow-sm border-0 rounded-4 sidebar-card">
                    <!-- Labels Section -->
                    <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center py-3">
                        <h5 class="card-title mb-0 fw-bold">
                            <i class="fas fa-tags text-primary me-2"></i>Labels
                        </h5>
                        <a href="<?= BASE_URL ?>/labels" class="btn btn-sm btn-light rounded-circle" data-bs-toggle="tooltip" title="Manage Labels">
                            <i class="fas fa-cog"></i>
                        </a>
                    </div>
                    
                    <div class="list-group list-group-flush labels-list">
                        <a href="<?= BASE_URL ?>/notes?view=<?= $data['view'] ?><?= isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?>"
                           class="list-group-item list-group-item-action d-flex justify-content-between align-items-center border-0 <?= empty($data['current_label']) ? 'active' : '' ?>">
                            <div class="d-flex align-items-center">
                                <div class="label-icon all-notes">
                                    <i class="fas fa-sticky-note"></i>
                                </div>
                                <span>All Notes</span>
                            </div>
                            <span class="badge bg-primary rounded-pill"><?= count($data['notes']) ?></span>
                        </a>
                        
                        <?php if(isset($data['labels']) && is_array($data['labels'])): ?>
                            <?php foreach ($data['labels'] as $label): ?>
                                <a href="<?= BASE_URL ?>/notes?view=<?= $data['view'] ?>&label=<?= $label['id'] ?><?= isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?>"
                                   class="list-group-item list-group-item-action d-flex justify-content-between align-items-center border-0 <?= isset($data['current_label']) && $data['current_label'] == $label['id'] ? 'active' : '' ?>">
                                    <div class="d-flex align-items-center">
                                        <div class="label-icon">
                                            <i class="fas fa-tag"></i>
                                        </div>
                                        <span class="label-name"><?= htmlspecialchars($label['name']) ?></span>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    
                    <?php if(isset($data['shared_notes']) && is_array($data['shared_notes']) && count($data['shared_notes']) > 0): ?>
                        <div class="card-header bg-white border-top border-bottom-0 d-flex justify-content-between align-items-center py-3">
                            <h5 class="card-title mb-0 fw-bold">
                                <i class="fas fa-share-alt text-info me-2"></i>Shared
                            </h5>
                        </div>
                        <div class="list-group list-group-flush">
                            <a href="<?= BASE_URL ?>/notes/shared"
                               class="list-group-item list-group-item-action d-flex justify-content-between align-items-center border-0">
                                <div class="d-flex align-items-center">
                                    <div class="label-icon shared-notes">
                                        <i class="fas fa-share-alt"></i>
                                    </div>
                                    <span>Shared with me</span>
                                </div>
                                <span class="badge bg-info rounded-pill"><?= count($data['shared_notes']) ?></span>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
                
                
            </div>
        </div>
        
        <!-- Main Content Area -->
        <div class="col-lg-9 col-md-8">
            <div class="content-wrapper">
                <!-- Header with actions -->
                <div class="d-md-flex justify-content-between align-items-center mb-4">
                    <div class="header-left mb-3 mb-md-0">
                        <h2 class="h3 mb-0 d-flex align-items-center">
                            <i class="fas fa-sticky-note me-2 text-primary"></i>
                            <?php if(isset($data['current_label'])): ?>
                                <?php 
                                $currentLabelName = 'Notes';
                                foreach($data['labels'] as $label) {
                                    if($label['id'] == $data['current_label']) {
                                        $currentLabelName = $label['name'];
                                        break;
                                    }
                                }
                                ?>
                                <span><?= htmlspecialchars($currentLabelName) ?></span>
                            <?php elseif(!empty($data['search'])): ?>
                                <span>Search Results</span>
                            <?php else: ?>
                                <span>My Notes</span>
                            <?php endif; ?>
                        </h2>
                    </div>
                    <div class="header-right d-flex flex-wrap gap-2">
                        <div class="search-container">
                            <div class="input-group">
                                <input type="text" id="search-input" class="form-control" placeholder="Search notes..." 
                                       value="<?= htmlspecialchars($data['search']) ?>">
                                <button id="search-btn" class="btn btn-primary" type="button">
                                    <i class="fas fa-search"></i>
                                </button>
                                <button id="clear-search" class="btn btn-outline-secondary" type="button" 
                                        <?= empty($data['search']) ? 'style="display:none"' : '' ?>>
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="btn-group view-toggle shadow-sm">
                            <a href="<?= BASE_URL ?>/notes?view=grid<?= isset($_GET['label']) ? '&label=' . $_GET['label'] : '' ?><?= isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?>" 
                               class="btn <?= $data['view'] === 'grid' ? 'btn-primary' : 'btn-light' ?>">
                                <i class="fas fa-th-large"></i>
                            </a>
                            <a href="<?= BASE_URL ?>/notes?view=list<?= isset($_GET['label']) ? '&label=' . $_GET['label'] : '' ?><?= isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?>" 
                               class="btn <?= $data['view'] === 'list' ? 'btn-primary' : 'btn-light' ?>">
                                <i class="fas fa-list"></i>
                            </a>
                        </div>
                        <a href="<?= BASE_URL ?>/notes/create" class="btn btn-success new-note-btn">
                            <i class="fas fa-plus me-2"></i>New Note
                        </a>
                    </div>
                </div>
                
                <?php if (Session::hasFlash('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                        <div class="d-flex align-items-center">
                            <div class="alert-icon me-3">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div><?= Session::getFlash('success') ?></div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                
                <?php if (Session::hasFlash('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                        <div class="d-flex align-items-center">
                            <div class="alert-icon me-3">
                                <i class="fas fa-exclamation-circle"></i>
                            </div>
                            <div><?= Session::getFlash('error') ?></div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                
                <!-- Notes Display Area -->
                <?php if (empty($data['notes'])): ?>
                    <div class="card shadow-sm border-0 rounded-4">
                        <div class="card-body text-center py-5">
                            <?php if (!empty($data['search'])): ?>
                                <div class="empty-state">
                                    <div class="empty-state-icon mb-3">
                                        <i class="fas fa-search"></i>
                                    </div>
                                    <h3 class="fw-bold mb-2">No notes found</h3>
                                    <p class="text-muted mb-4">No notes match your search "<?= htmlspecialchars($data['search']) ?>"</p>
                                    <a href="<?= BASE_URL ?>/notes" class="btn btn-primary px-4">Clear Search</a>
                                </div>
                            <?php elseif (!empty($data['current_label'])): ?>
                                <div class="empty-state">
                                    <div class="empty-state-icon mb-3">
                                        <i class="fas fa-tag"></i>
                                    </div>
                                    <h3 class="fw-bold mb-2">No notes with this label</h3>
                                    <p class="text-muted mb-4">You don't have any notes with this label yet</p>
                                    <a href="<?= BASE_URL ?>/notes/create" class="btn btn-primary px-4">Create a Note</a>
                                </div>
                            <?php else: ?>
                                <div class="empty-state">
                                    <div class="empty-state-icon mb-3">
                                        <i class="fas fa-sticky-note"></i>
                                    </div>
                                    <h3 class="fw-bold mb-2">No notes yet</h3>
                                    <p class="text-muted mb-4">Create your first note to get started</p>
                                    <a href="<?= BASE_URL ?>/notes/create" class="btn btn-primary px-4">Create Your First Note</a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php else: ?>
                    <?php if ($data['view'] === 'grid'): ?>
                        <!-- Grid View -->
                        <div class="notes-grid">
                            <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-4">
                                <?php foreach ($data['notes'] as $note): ?>
                                    <?php 
                                    $hasImages = isset($note['image_count']) && $note['image_count'] > 0; 
                                    $isProtected = isset($note['is_password_protected']) && $note['is_password_protected'];
                                    
                                    // Get the URL for the note - either password verification or direct edit
                                    $noteUrl = $isProtected 
                                        ? BASE_URL . '/notes/verify-password/' . $note['id'] 
                                        : BASE_URL . '/notes/edit/' . $note['id'];
                                    ?>
                                    <div class="col note-wrapper">
                                        <div class="card h-100 note-card border-0 shadow-sm rounded-4 <?= isset($note['is_pinned']) && $note['is_pinned'] ? 'pinned' : '' ?>">
                                            <?php if (isset($note['is_pinned']) && $note['is_pinned']): ?>
                                                <div class="pin-badge">
                                                    <i class="fas fa-thumbtack"></i>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <!-- Note image thumbnail if available -->
                                            <?php if ($hasImages && isset($note['images']) && !empty($note['images'])): 
                                                $firstImage = $note['images'][0];
                                            ?>
                                            <div class="note-thumbnail-container rounded-top-4 position-relative overflow-hidden">
                                                <a href="<?= $noteUrl ?>" class="d-block">
                                                    <img src="<?= UPLOADS_URL . '/' . $firstImage['file_path'] ?>" 
                                                         class="note-thumbnail" alt="Note image">
                                                    
                                                    <?php if ($note['image_count'] > 1): ?>
                                                        <div class="image-count-badge">
                                                            <i class="fas fa-images me-1"></i> <?= $note['image_count'] ?>
                                                        </div>
                                                    <?php endif; ?>
                                                    
                                                    <?php if ($isProtected): ?>
                                                        <div class="image-lock-overlay">
                                                            <i class="fas fa-lock"></i>
                                                        </div>
                                                    <?php endif; ?>
                                                </a>
                                            </div>
                                            <?php endif; ?>
                                            
                                            <div class="card-header border-0 pb-0 bg-transparent d-flex justify-content-between align-items-start pt-3">
                                                <h5 class="card-title mb-0 note-title">
                                                    <a href="<?= $noteUrl ?>" class="note-title-link d-block text-truncate">
                                                        <?php if ($isProtected): ?>
                                                            <i class="fas fa-lock me-1 text-warning"></i>
                                                        <?php endif; ?>
                                                        <?= htmlspecialchars($note['title']) ?>
                                                    </a>
                                                </h5>
                                                
                                                <div class="dropdown note-actions">
                                                    <button class="btn btn-sm btn-icon" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                                                        <li>
                                                            <button class="dropdown-item pin-note" data-id="<?= $note['id'] ?>">
                                                                <i class="fas fa-thumbtack me-2 <?= isset($note['is_pinned']) && $note['is_pinned'] ? 'text-primary' : '' ?>"></i>
                                                                <?= isset($note['is_pinned']) && $note['is_pinned'] ? 'Unpin' : 'Pin' ?>
                                                            </button>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="<?= $noteUrl ?>">
                                                                <i class="fas fa-edit me-2"></i> Edit
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="<?= BASE_URL ?>/notes/share/<?= $note['id'] ?>">
                                                                <i class="fas fa-share-alt me-2"></i> Share
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="<?= BASE_URL ?>/notes/toggle-password/<?= $note['id'] ?>">
                                                                <?php if ($isProtected): ?>
                                                                    <i class="fas fa-unlock me-2"></i> Remove Password
                                                                <?php else: ?>
                                                                    <i class="fas fa-lock me-2"></i> Add Password
                                                                <?php endif; ?>
                                                            </a>
                                                        </li>
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li>
                                                            <a class="dropdown-item text-danger delete-note" href="<?= BASE_URL ?>/notes/delete/<?= $note['id'] ?>">
                                                                <i class="fas fa-trash me-2"></i> Delete
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            
                                            <div class="card-body">
                                                <div class="card-text note-content">
                                                    <?php 
                                                    if ($isProtected) {
                                                        // Show protected content placeholder
                                                        echo '<div class="protected-content text-center p-3">';
                                                        echo '<i class="fas fa-lock text-warning mb-2 fs-4"></i>';
                                                        echo '<p class="mb-0">This note is password protected</p>';
                                                        echo '<a href="' . $noteUrl . '" class="btn btn-sm btn-outline-warning mt-2">Unlock</a>';
                                                        echo '</div>';
                                                    } else {
                                                        // Show actual content preview
                                                        $content = isset($note['content']) ? $note['content'] : '';
                                                        $preview = strip_tags($content);
                                                        $preview = substr($preview, 0, 150);
                                                        if (strlen($content) > 150) $preview .= '...';
                                                        echo nl2br(htmlspecialchars($preview));
                                                    }
                                                    ?>
                                                </div>
                                                
                                                <?php if (isset($note['labels']) && !empty($note['labels'])): ?>
                                                    <div class="note-labels mt-3">
                                                        <?php foreach ($note['labels'] as $label): ?>
                                                            <span class="note-label">
                                                                <?= htmlspecialchars($label['name'] ?? '') ?>
                                                            </span>
                                                        <?php endforeach; ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <div class="card-footer bg-transparent d-flex justify-content-between align-items-center border-0">
                                                <div class="note-indicators">
                                                    <?php if (isset($note['is_pinned']) && $note['is_pinned']): ?>
                                                        <span class="note-indicator" title="Pinned">
                                                            <i class="fas fa-thumbtack"></i>
                                                        </span>
                                                    <?php endif; ?>
                                                    
                                                    <?php if ($isProtected): ?>
                                                        <span class="note-indicator text-warning" title="Password Protected">
                                                            <i class="fas fa-lock"></i>
                                                        </span>
                                                    <?php endif; ?>
                                                    
                                                    <?php if (isset($note['is_shared']) && $note['is_shared']): ?>
                                                        <span class="note-indicator text-info" title="Shared with others">
                                                            <i class="fas fa-share-alt"></i>
                                                        </span>
                                                    <?php endif; ?>
                                                    
                                                    <?php if ($hasImages): ?>
                                                        <span class="note-indicator" title="<?= $note['image_count'] ?> image(s) attached">
                                                            <i class="fas fa-image"></i> <?= $note['image_count'] ?>
                                                        </span>
                                                    <?php endif; ?>
                                                </div>
                                                
                                                <div class="note-date">
                                                    <?php 
                                                    if (isset($note['updated_at'])) {
                                                        $updated = new DateTime($note['updated_at']);
                                                        
                                                        if ($updated->format('Y-m-d') === date('Y-m-d')) {
                                                            // Today, show time
                                                            echo 'Today at ' . $updated->format('g:i A');
                                                        } else if ($updated->format('Y-m-d') === date('Y-m-d', strtotime('-1 day'))) {
                                                            // Yesterday
                                                            echo 'Yesterday at ' . $updated->format('g:i A');
                                                        } else {
                                                            // Another day
                                                            echo $updated->format('M j, Y');
                                                        }
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <!-- List View -->
                        <div class="notes-list-view">
                            <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th style="width: 50px" class="border-0"></th>
                                                <th class="border-0">Title</th>
                                                <th class="border-0 d-none d-lg-table-cell">Content</th>
                                                <th class="border-0 d-none d-md-table-cell">Labels</th>
                                                <th class="border-0" style="width: 180px">Last Modified</th>
                                                <th class="border-0 text-end" style="width: 120px">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($data['notes'] as $note): ?>
                                                <?php 
                                                $isProtected = isset($note['is_password_protected']) && $note['is_password_protected'];
                                                $hasImages = isset($note['image_count']) && $note['image_count'] > 0;
                                                
                                                // Get the URL for the note - either password verification or direct edit
                                                $noteUrl = $isProtected 
                                                    ? BASE_URL . '/notes/verify-password/' . $note['id'] 
                                                    : BASE_URL . '/notes/edit/' . $note['id'];
                                                ?>
                                                <tr class="note-list-item <?= isset($note['is_pinned']) && $note['is_pinned'] ? 'table-pinned' : '' ?>">
                                                    <td class="text-center note-list-icons">
                                                        <div class="d-flex flex-column align-items-center gap-1">
                                                            <?php if (isset($note['is_pinned']) && $note['is_pinned']): ?>
                                                                <i class="fas fa-thumbtack text-primary" title="Pinned"></i>
                                                            <?php endif; ?>
                                                            
                                                            <?php if ($isProtected): ?>
                                                                <i class="fas fa-lock text-warning" title="Password Protected"></i>
                                                            <?php endif; ?>
                                                            
                                                            <?php if (isset($note['is_shared']) && $note['is_shared']): ?>
                                                                <i class="fas fa-share-alt text-info" title="Shared with others"></i>
                                                            <?php endif; ?>
                                                            
                                                            <?php if ($hasImages): ?>
                                                                <i class="fas fa-image text-secondary" title="<?= $note['image_count'] ?> image(s) attached"></i>
                                                            <?php endif; ?>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <?php if ($hasImages && isset($note['images']) && !empty($note['images'])): ?>
                                                            <div class="list-view-thumbnail me-2">
                                                                <a href="<?= $noteUrl ?>">
                                                                    <img src="<?= UPLOADS_URL . '/' . $note['images'][0]['file_path'] ?>" 
                                                                         alt="Note thumbnail" class="rounded">
                                                                    <?php if ($isProtected): ?>
                                                                        <div class="image-lock-overlay-small">
                                                                            <i class="fas fa-lock"></i>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                </a>
                                                            </div>
                                                            <?php endif; ?>
                                                            
                                                            <a href="<?= $noteUrl ?>" class="fw-medium note-title-link">
                                                                <?= htmlspecialchars($note['title']) ?>
                                                            </a>
                                                        </div>
                                                    </td>
                                                    <td class="text-truncate d-none d-lg-table-cell" style="max-width: 250px;">
                                                        <?php 
                                                        if ($isProtected) {
                                                            echo '<span class="text-warning"><i class="fas fa-lock me-1"></i>Protected content</span>';
                                                        } else {
                                                            $content = isset($note['content']) ? $note['content'] : '';
                                                            $preview = strip_tags($content);
                                                            $preview = substr($preview, 0, 100);
                                                            if (strlen($content) > 100) $preview .= '...';
                                                            echo htmlspecialchars($preview);
                                                        }
                                                        ?>
                                                    </td>
                                                    <td class="d-none d-md-table-cell">
                                                        <?php if (isset($note['labels']) && !empty($note['labels'])): ?>
                                                            <div class="note-labels-list d-flex flex-wrap gap-1">
                                                                <?php foreach ($note['labels'] as $label): ?>
                                                                    <span class="badge bg-light text-dark border">
                                                                        <?= htmlspecialchars($label['name'] ?? '') ?>
                                                                    </span>
                                                                <?php endforeach; ?>
                                                            </div>
                                                        <?php else: ?>
                                                            <small class="text-muted">None</small>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <small class="note-date">
                                                            <?php 
                                                            if (isset($note['updated_at'])) {
                                                                $updated = new DateTime($note['updated_at']);
                                                                
                                                                if ($updated->format('Y-m-d') === date('Y-m-d')) {
                                                                    // Today, show time
                                                                    echo 'Today at ' . $updated->format('g:i A');
                                                                } else if ($updated->format('Y-m-d') === date('Y-m-d', strtotime('-1 day'))) {
                                                                    // Yesterday
                                                                    echo 'Yesterday at ' . $updated->format('g:i A');
                                                                } else {
                                                                    // Another day
                                                                    echo $updated->format('M j, Y g:i A');
                                                                }
                                                            }
                                                            ?>
                                                        </small>
                                                    </td>
                                                    <td class="text-end">
                                                        <div class="note-actions d-flex gap-1 justify-content-end">
                                                            <button class="btn btn-sm btn-icon pin-note" data-id="<?= $note['id'] ?>" title="<?= isset($note['is_pinned']) && $note['is_pinned'] ? 'Unpin' : 'Pin' ?>">
                                                                <i class="fas fa-thumbtack <?= isset($note['is_pinned']) && $note['is_pinned'] ? 'text-primary' : '' ?>"></i>
                                                            </button>
                                                            <a href="<?= $noteUrl ?>" class="btn btn-sm btn-icon" title="Edit">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                            <div class="dropdown d-inline-block">
                                                                <button class="btn btn-sm btn-icon" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                                    <i class="fas fa-ellipsis-v"></i>
                                                                </button>
                                                                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                                                                    <li>
                                                                        <a class="dropdown-item" href="<?= BASE_URL ?>/notes/share/<?= $note['id'] ?>">
                                                                            <i class="fas fa-share-alt me-2"></i> Share
                                                                        </a>
                                                                    </li>
                                                                    <li>
                                                                        <a class="dropdown-item" href="<?= BASE_URL ?>/notes/toggle-password/<?= $note['id'] ?>">
                                                                            <?php if ($isProtected): ?>
                                                                                <i class="fas fa-unlock me-2"></i> Remove Password
                                                                            <?php else: ?>
                                                                                <i class="fas fa-lock me-2"></i> Add Password
                                                                            <?php endif; ?>
                                                                        </a>
                                                                    </li>
                                                                    <li><hr class="dropdown-divider"></li>
                                                                    <li>
                                                                        <a class="dropdown-item text-danger delete-note" href="<?= BASE_URL ?>/notes/delete/<?= $note['id'] ?>">
                                                                            <i class="fas fa-trash me-2"></i> Delete
                                                                        </a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Confirmation Modal for Delete -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="deleteConfirmModalLabel">Delete Note</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="modal-icon mb-3 text-danger">
                    <i class="fas fa-trash-alt"></i>
                </div>
                <h4 class="mb-2">Are you sure?</h4>
                <p class="mb-0">Do you really want to delete "<span id="delete-note-title"></span>"?</p>
                <p class="text-danger mb-0 mt-2">This action cannot be undone.</p>
            </div>
            <div class="modal-footer border-0 justify-content-center pb-4">
                <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Cancel</button>
                <a href="#" id="confirm-delete-link" class="btn btn-danger px-4">
                    <i class="fas fa-trash-alt me-2"></i> Delete
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Export Options Modal -->
<div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="exportModalLabel">Export Notes</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4">
                <p class="text-muted">Select a format to export your notes:</p>
                <div class="export-options">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="export-option-card">
                                <input type="radio" name="export-format" id="export-pdf" value="pdf" class="export-radio">
                                <label for="export-pdf" class="export-option-label">
                                    <div class="export-icon">
                                        <i class="fas fa-file-pdf"></i>
                                    </div>
                                    <div class="export-details">
                                        <div class="export-title">PDF Document</div>
                                        <div class="export-desc">Export as a PDF document</div>
                                    </div>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="export-option-card">
                                <input type="radio" name="export-format" id="export-docx" value="docx" class="export-radio">
                                <label for="export-docx" class="export-option-label">
                                    <div class="export-icon">
                                        <i class="fas fa-file-word"></i>
                                    </div>
                                    <div class="export-details">
                                        <div class="export-title">Word Document</div>
                                        <div class="export-desc">Export as a DOCX file</div>
                                    </div>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="export-option-card">
                                <input type="radio" name="export-format" id="export-txt" value="txt" class="export-radio" checked>
                                <label for="export-txt" class="export-option-label">
                                    <div class="export-icon">
                                        <i class="fas fa-file-alt"></i>
                                    </div>
                                    <div class="export-details">
                                        <div class="export-title">Text File</div>
                                        <div class="export-desc">Export as plain text</div>
                                    </div>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="export-option-card">
                                <input type="radio" name="export-format" id="export-json" value="json" class="export-radio">
                                <label for="export-json" class="export-option-label">
                                    <div class="export-icon">
                                        <i class="fas fa-file-code"></i>
                                    </div>
                                    <div class="export-details">
                                        <div class="export-title">JSON File</div>
                                        <div class="export-desc">Export as structured data</div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="include-images" checked>
                            <label class="form-check-label" for="include-images">
                                Include attached images
                            </label>
                        </div>
                        
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="include-password-protected" checked>
                            <label class="form-check-label" for="include-password-protected">
                                Include password-protected notes
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 justify-content-center pb-4">
                <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Cancel</button>
                <button type="button" id="start-export" class="btn btn-primary px-4">
                    <i class="fas fa-file-export me-2"></i> Export
                </button>
            </div>
        </div>
    </div>
</div>

<style>
:root {
    --primary-color: #4a89dc;
    --primary-hover: #3a77c5;
    --success-color: #28a745;
    --danger-color: #dc3545;
    --warning-color: #ffc107;
    --info-color: #17a2b8;
    --secondary-color: #6c757d;
    --light-bg: #f8f9fa;
    --border-radius: 16px;
    --card-radius: 12px;
    --small-radius: 8px;
    --box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    --transition: all 0.3s ease;
}

/* Sidebar styling */
.sidebar-wrapper {
    position: sticky;
    top: 90px;
    max-height: calc(100vh - 120px);
    overflow-y: auto;
    transition: var(--transition);
    scrollbar-width: thin;
}

.sidebar-wrapper::-webkit-scrollbar {
    width: 5px;
}

.sidebar-wrapper::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.sidebar-wrapper::-webkit-scrollbar-thumb {
    background: #ccc;
    border-radius: 10px;
}

.sidebar-wrapper::-webkit-scrollbar-thumb:hover {
    background: #aaa;
}

.sidebar-card {
    border-radius: var(--card-radius) !important;
    overflow: hidden;
    transition: var(--transition);
    border: none !important;
    margin-bottom: 1rem;
}

.sidebar-card:hover {
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1) !important;
}

.list-group-flush .list-group-item {
    border-left: 3px solid transparent;
    transition: var(--transition);
    padding: 0.8rem 1rem;
    border-radius: 0 !important;
}

.list-group-flush .list-group-item:hover {
    background-color: rgba(0, 0, 0, 0.02);
    transform: translateX(5px);
}

.list-group-flush .list-group-item.active {
    background-color: rgba(74, 137, 220, 0.1);
    color: var(--primary-color);
    font-weight: 500;
    border-left-color: var(--primary-color);
}

.label-icon {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(74, 137, 220, 0.1);
    color: var(--primary-color);
    margin-right: 12px;
    transition: var(--transition);
}

.list-group-item:hover .label-icon {
    transform: scale(1.1);
}

.label-icon.all-notes {
    background: rgba(74, 137, 220, 0.15);
}

.label-icon.shared-notes {
    background: rgba(23, 162, 184, 0.1);
    color: var(--info-color);
}

/* Quick actions card */
.action-card {
    border-radius: var(--card-radius) !important;
    transition: var(--transition);
}

.action-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1) !important;
}

/* Header styling */
.header-left h2 {
    font-weight: 600;
}

.search-container {
    position: relative;
    min-width: 250px;
}

.search-container .input-group {
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    border-radius: var(--small-radius);
    transition: var(--transition);
}

.search-container .input-group:focus-within {
    box-shadow: 0 5px 15px rgba(74, 137, 220, 0.2);
}

.search-container .form-control {
    border-radius: var(--small-radius) 0 0 var(--small-radius);
    border: 1px solid rgba(0, 0, 0, 0.1);
    border-right: none;
    padding: 0.6rem 1rem;
}

.search-container .form-control:focus {
    border-color: var(--primary-color);
    box-shadow: none;
}

.search-container .btn {
    border-radius: 0 var(--small-radius) var(--small-radius) 0;
    border: 1px solid var(--primary-color);
}

.view-toggle .btn {
    padding: 0.6rem 0.8rem;
    border-radius: 0;
}

.view-toggle .btn:first-child {
    border-radius: var(--small-radius) 0 0 var(--small-radius);
}

.view-toggle .btn:last-child {
    border-radius: 0 var(--small-radius) var(--small-radius) 0;
}

.new-note-btn {
    border-radius: var(--small-radius);
    padding: 0.6rem 1.25rem;
    transition: var(--transition);
    font-weight: 500;
    box-shadow: 0 4px 10px rgba(40, 167, 69, 0.2);
    background: linear-gradient(45deg, #28a745, #34ce57);
    border: none;
}

.new-note-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 15px rgba(40, 167, 69, 0.3);
    background: linear-gradient(45deg, #218838, #2aba4e);
}

/* Empty state */
.empty-state {
    padding: 3rem 1rem;
}

.empty-state-icon {
    width: 100px;
    height: 100px;
    font-size: 3rem;
    background-color: var(--light-bg);
    color: #dee2e6;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    margin: 0 auto;
    transition: var(--transition);
}

/* Grid View Styling */
.note-card {
    border-radius: var(--card-radius) !important;
    transition: var(--transition);
    position: relative;
    overflow: hidden;
}

.note-wrapper {
    transition: var(--transition);
}

.note-wrapper:hover {
    transform: translateY(-8px);
}

.pin-badge {
    position: absolute;
    top: 0;
    right: 15px;
    width: 30px;
    height: 30px;
    background-color: var(--primary-color);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 0 0 15px 15px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transition: var(--transition);
    z-index: 10;
}

.note-card:hover .pin-badge {
    height: 35px;
}

.note-thumbnail-container {
    height: 180px;
    overflow: hidden;
}

.note-thumbnail {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: var(--transition);
}

.note-thumbnail-container:hover .note-thumbnail {
    transform: scale(1.05);
}

.image-count-badge {
    position: absolute;
    bottom: 10px;
    right: 10px;
    background-color: rgba(0, 0, 0, 0.6);
    color: white;
    padding: 3px 8px;
    border-radius: 20px;
    font-size: 0.75rem;
    transition: var(--transition);
    z-index: 2;
}

.note-thumbnail-container:hover .image-count-badge {
    background-color: var(--primary-color);
}

.image-lock-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 2rem;
    transition: var(--transition);
    z-index: 1;
}

.image-lock-overlay-small {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 0.8rem;
    transition: var(--transition);
}

.note-title {
    max-width: calc(100% - 40px);
    font-weight: 600;
}

.note-title-link {
    color: inherit;
    text-decoration: none;
    transition: var(--transition);
}

.note-title-link:hover {
    color: var(--primary-color);
}

.note-content {
    font-size: 0.9rem;
    color: #495057;
    max-height: 100px;
    overflow: hidden;
    position: relative;
}

.note-content::after {
    content: "";
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 30px;
    background: linear-gradient(to bottom, rgba(255,255,255,0), rgba(255,255,255,1));
    pointer-events: none;
}

.protected-content {
    background-color: rgba(255, 193, 7, 0.05);
    border-radius: 8px;
}

.note-labels {
    display: flex;
    flex-wrap: wrap;
    gap: 5px;
}

.note-label {
    background-color: rgba(74, 137, 220, 0.1);
    color: var(--primary-color);
    border-radius: 20px;
    padding: 2px 10px;
    font-size: 0.75rem;
    transition: var(--transition);
}

.note-label:hover {
    background-color: rgba(74, 137, 220, 0.2);
    transform: translateY(-2px);
}

.note-indicators {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}

.note-indicator {
    color: #6c757d;
    font-size: 0.8rem;
}

.note-date {
    color: #6c757d;
    font-size: 0.8rem;
}

.note-actions {
    position: relative;
}

.btn-icon {
    width: 32px;
    height: 32px;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    background-color: transparent;
    color: #6c757d;
    border: none;
    transition: var(--transition);
}

.btn-icon:hover {
    background-color: rgba(0, 0, 0, 0.05);
    color: var(--primary-color);
    transform: translateY(-3px);
}

/* List View Styling */
.notes-list-view {
    transition: var(--transition);
}

.notes-list-view .table {
    margin-bottom: 0;
}

.notes-list-view .table th {
    font-weight: 600;
    color: #495057;
    padding: 15px;
}

.notes-list-view .table td {
    padding: 15px;
    vertical-align: middle;
}

.note-list-item {
    transition: var(--transition);
}

.note-list-item:hover {
    background-color: rgba(74, 137, 220, 0.05);
}

.note-list-item.table-pinned {
    background-color: rgba(74, 137, 220, 0.05);
}

.list-view-thumbnail {
    position: relative;
    width: 50px;
    height: 50px;
    overflow: hidden;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    transition: var(--transition);
}

.list-view-thumbnail:hover {
    transform: scale(1.05);
    box-shadow: 0 5px 10px rgba(0, 0, 0, 0.15);
}

.list-view-thumbnail img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* Alert styling */
.alert {
    border-radius: var(--small-radius);
}

.alert-success {
    background-color: rgba(40, 167, 69, 0.1);
    color: #155724;
}

.alert-danger {
    background-color: rgba(220, 53, 69, 0.1);
    color: #721c24;
}

.alert-icon {
    font-size: 1.5rem;
}

/* Modal styling */
.modal-content {
    border-radius: var(--border-radius) !important;
    overflow: hidden;
}

.modal-icon {
    width: 80px;
    height: 80px;
    font-size: 2.5rem;
    margin: 0 auto;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background-color: rgba(220, 53, 69, 0.1);
}

/* Export options styling */
.export-options {
    margin-top: 1rem;
}

.export-option-card {
    position: relative;
    height: 100%;
}

.export-radio {
    position: absolute;
    opacity: 0;
    width: 0;
    height: 0;
}

.export-option-label {
    display: flex;
    align-items: center;
    border: 1px solid rgba(0, 0, 0, 0.1);
    border-radius: var(--small-radius);
    padding: 15px;
    cursor: pointer;
    transition: var(--transition);
    height: 100%;
}

.export-option-label:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
}

.export-radio:checked + .export-option-label {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 2px rgba(74, 137, 220, 0.2);
}

.export-icon {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: rgba(74, 137, 220, 0.1);
    color: var(--primary-color);
    margin-right: 12px;
    font-size: 1.25rem;
    transition: var(--transition);
}

.export-radio:checked + .export-option-label .export-icon {
    background-color: var(--primary-color);
    color: white;
}

.export-option-label:hover .export-icon {
    transform: scale(1.1);
}

.export-details {
    flex: 1;
}

.export-title {
    font-weight: 600;
    margin-bottom: 2px;
}

.export-desc {
    font-size: 0.8rem;
    color: #6c757d;
}

/* Button styling */
.btn {
    border-radius: var(--small-radius);
    padding: 0.6rem 1.25rem;
    font-weight: 500;
    transition: var(--transition);
    position: relative;
    overflow: hidden;
}

.btn::after {
    content: '';
    position: absolute;
    height: 100%;
    width: 100%;
    top: 0;
    left: -100%;
    background: linear-gradient(90deg, rgba(255,255,255,0.2), transparent);
    transition: 0.3s;
}

.btn:hover::after {
    left: 100%;
}

.btn-primary {
    background: linear-gradient(45deg, #4a89dc, #6ea8fe);
    border: none;
}

.btn-primary:hover {
    background: linear-gradient(45deg, #3a77c5, #4a89dc);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(74, 137, 220, 0.3);
}

/* Animation for empty state */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.empty-state {
    animation: fadeInUp 0.5s ease-out;
}

/* Responsive styling */
@media (max-width: 991.98px) {
    .sidebar-wrapper {
        position: relative;
        top: 0;
        max-height: none;
        margin-bottom: 2rem;
    }
    
    .header-right {
        flex-wrap: wrap;
        justify-content: flex-end;
    }
    
    .search-container {
        flex: 1 1 100%;
        order: 3;
        margin-top: 1rem;
    }
    
    .list-view-thumbnail {
        margin-right: 0.5rem;
    }
}

@media (max-width: 767.98px) {
    .note-wrapper {
        margin-bottom: 1rem;
    }
    
    .note-list-item td {
        padding: 10px;
    }
    
    .note-labels {
        display: none;
    }
    
    .note-date {
        font-size: 0.7rem;
    }
    
    .header-right {
        flex-direction: column;
        align-items: stretch;
        width: 100%;
    }
    
    .header-right > * {
        margin-bottom: 0.5rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    if (tooltipTriggerList.length > 0) {
        [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
    }
    
    // Search functionality
    const searchInput = document.getElementById('search-input');
    const searchBtn = document.getElementById('search-btn');
    const clearSearchBtn = document.getElementById('clear-search');
    
    if (searchInput && searchBtn) {
        // Search button click
        searchBtn.addEventListener('click', function() {
            if (searchInput.value.trim()) {
                window.location.href = `${BASE_URL}/notes?view=${getUrlParameter('view') || 'grid'}&search=${encodeURIComponent(searchInput.value.trim())}${getUrlParameter('label') ? '&label=' + getUrlParameter('label') : ''}`;
            }
        });
        
        // Enter key in search input
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                searchBtn.click();
            }
        });
        
        // Clear search button
        if (clearSearchBtn) {
            clearSearchBtn.addEventListener('click', function() {
                window.location.href = `${BASE_URL}/notes?view=${getUrlParameter('view') || 'grid'}${getUrlParameter('label') ? '&label=' + getUrlParameter('label') : ''}`;
            });
            
            // Show/hide clear button based on input
            searchInput.addEventListener('input', function() {
                clearSearchBtn.style.display = this.value ? 'block' : 'none';
            });
        }
    }
    
    // Pin/unpin functionality
    const pinButtons = document.querySelectorAll('.pin-note');
    
    pinButtons.forEach(button => {
        button.addEventListener('click', function() {
            const noteId = this.getAttribute('data-id');
            const icon = this.querySelector('i');
            
            // Add loading animation
            const originalClass = icon.className;
            icon.className = 'fas fa-spinner fa-spin';
            
            // Send AJAX request
            fetch(`${BASE_URL}/notes/toggle-pin/${noteId}`, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success and reload page
                    setTimeout(() => {
                        window.location.reload();
                    }, 300);
                } else if (data.redirect) {
                    // Redirect to password verification
                    window.location.href = data.redirect;
                } else {
                    // Restore original icon on error
                    icon.className = originalClass;
                    console.error('Error:', data.message);
                    
                    // Show error toast
                    showToast('error', data.message || 'An error occurred');
                }
            })
            .catch(error => {
                // Restore original icon on error
                icon.className = originalClass;
                console.error('Error:', error);
                
                // Show error toast
                showToast('error', 'Network error occurred');
            });
        });
    });
    
    // Delete note confirmation
    const deleteLinks = document.querySelectorAll('.delete-note');
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
    const confirmDeleteLink = document.getElementById('confirm-delete-link');
    const deleteNoteTitle = document.getElementById('delete-note-title');
    
    deleteLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Get the note title
            const noteCard = this.closest('.note-card');
            const noteListItem = this.closest('.note-list-item');
            let noteTitle;
            
            if (noteCard) {
                noteTitle = noteCard.querySelector('.note-title-link').textContent.trim();
            } else if (noteListItem) {
                noteTitle = noteListItem.querySelector('.note-title-link').textContent.trim();
            } else {
                noteTitle = "this note";
            }
            
            // Set the title in the modal
            deleteNoteTitle.textContent = noteTitle;
            
            // Set the confirmation link URL
            confirmDeleteLink.href = this.href;
            
            // Show the modal
            deleteModal.show();
        });
    });
    
    // Add loading state to confirm delete button
    if (confirmDeleteLink) {
        confirmDeleteLink.addEventListener('click', function() {
            this.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Deleting...';
            this.classList.add('disabled');
        });
    }
    
    // Export notes functionality
    const exportBtn = document.getElementById('export-notes');
    const exportModal = new bootstrap.Modal(document.getElementById('exportModal'));
    const startExportBtn = document.getElementById('start-export');
    
    if (exportBtn) {
        exportBtn.addEventListener('click', function(e) {
            e.preventDefault();
            exportModal.show();
        });
    }
    
    if (startExportBtn) {
        startExportBtn.addEventListener('click', function() {
            const format = document.querySelector('input[name="export-format"]:checked').value;
            const includeImages = document.getElementById('include-images').checked;
            const includeProtected = document.getElementById('include-password-protected').checked;
            
            // Show loading state
            this.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Exporting...';
            this.disabled = true;
            
            // Simulate export process (in a real app, you would have an actual endpoint)
            setTimeout(() => {
                // Reset button
                this.innerHTML = '<i class="fas fa-file-export me-2"></i> Export';
                this.disabled = false;
                
                // Hide modal
                exportModal.hide();
                
                // Show success toast
                showToast('success', `Notes successfully exported as ${format.toUpperCase()}`);
                
                // In a real app, you would trigger a download here
            }, 1500);
        });
    }
    
    // Helper function to get URL parameters
    function getUrlParameter(name) {
        name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
        const regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
        const results = regex.exec(location.search);
        return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
    }
    
    // Toast notification function
    function showToast(type, message) {
        // Create toast container if it doesn't exist
        let toastContainer = document.querySelector('.toast-container');
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.className = 'toast-container position-fixed bottom-0 end-0 p-3';
            document.body.appendChild(toastContainer);
        }
        
        // Create toast element
        const toastEl = document.createElement('div');
        toastEl.className = `toast align-items-center text-white bg-${type === 'success' ? 'success' : 'danger'} border-0`;
        toastEl.setAttribute('role', 'alert');
        toastEl.setAttribute('aria-live', 'assertive');
        toastEl.setAttribute('aria-atomic', 'true');
        
        toastEl.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        `;
        
        toastContainer.appendChild(toastEl);
        
        const toast = new bootstrap.Toast(toastEl, {
            autohide: true,
            delay: 3000
        });
        
        toast.show();
        
        // Remove toast after it's hidden
        toastEl.addEventListener('hidden.bs.toast', function() {
            this.remove();
        });
    }
    
    // Animate cards on page load
    const noteCards = document.querySelectorAll('.note-card');
    noteCards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            card.style.transition = 'all 0.5s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, 50 + (index * 50));
    });
});
</script>