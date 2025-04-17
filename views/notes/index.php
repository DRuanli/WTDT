<div class="row mb-4">
    <div class="col">
        <div class="d-sm-flex justify-content-between align-items-center">
            <h2 class="h3 mb-3 mb-sm-0">
                <i class="fas fa-sticky-note me-2 text-primary"></i>My Notes
            </h2>
            <div class="d-flex flex-wrap gap-2">
                <div class="input-group">
                    <input type="text" id="search-input" class="form-control" placeholder="Search notes..." 
                           value="<?= htmlspecialchars($data['search']) ?>">
                    <button id="clear-search" class="btn btn-outline-secondary" type="button" 
                            <?= empty($data['search']) ? 'style="display:none"' : '' ?>>
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="btn-group">
                    <a href="<?= BASE_URL ?>/notes?view=grid<?= isset($_GET['label']) ? '&label=' . $_GET['label'] : '' ?><?= isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?>" 
                       class="btn <?= $data['view'] === 'grid' ? 'btn-primary' : 'btn-outline-primary' ?>">
                        <i class="fas fa-th-large"></i>
                    </a>
                    <a href="<?= BASE_URL ?>/notes?view=list<?= isset($_GET['label']) ? '&label=' . $_GET['label'] : '' ?><?= isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?>" 
                       class="btn <?= $data['view'] === 'list' ? 'btn-primary' : 'btn-outline-primary' ?>">
                        <i class="fas fa-list"></i>
                    </a>
                </div>
                <a href="<?= BASE_URL ?>/notes/create" class="btn btn-success">
                    <i class="fas fa-plus me-1"></i> New Note
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Sidebar with Labels -->
    <div class="col-md-3 mb-4">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center bg-light">
                <h5 class="card-title mb-0">Labels</h5>
                <a href="<?= BASE_URL ?>/labels" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-cog"></i>
                </a>
            </div>
            <div class="list-group list-group-flush">
                <a href="<?= BASE_URL ?>/notes?view=<?= $data['view'] ?><?= isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?>"
                   class="list-group-item list-group-item-action d-flex justify-content-between align-items-center <?= empty($data['current_label']) ? 'active' : '' ?>">
                    <span><i class="fas fa-sticky-note me-2"></i> All Notes</span>
                    <span class="badge bg-primary rounded-pill"><?= count($data['notes']) ?></span>
                </a>
                
                <?php if(isset($data['labels']) && is_array($data['labels'])): ?>
                    <?php foreach ($data['labels'] as $label): ?>
                        <a href="<?= BASE_URL ?>/notes?view=<?= $data['view'] ?>&label=<?= $label['id'] ?><?= isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?>"
                           class="list-group-item list-group-item-action d-flex justify-content-between align-items-center <?= isset($data['current_label']) && $data['current_label'] == $label['id'] ? 'active' : '' ?>">
                            <span><i class="fas fa-tag me-2"></i> <?= htmlspecialchars($label['name']) ?></span>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            
            <?php if(isset($data['shared_notes']) && is_array($data['shared_notes']) && count($data['shared_notes']) > 0): ?>
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">Shared</h5>
                </div>
                <div class="list-group list-group-flush">
                    <a href="<?= BASE_URL ?>/notes/shared"
                       class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-share-alt me-2"></i> Shared with me</span>
                        <span class="badge bg-info rounded-pill"><?= count($data['shared_notes']) ?></span>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Main Notes Content -->
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
                    <?php if (!empty($data['search'])): ?>
                        <div class="display-1 text-muted mb-3"><i class="fas fa-search"></i></div>
                        <h3>No notes found</h3>
                        <p class="text-muted">No notes match your search "<?= htmlspecialchars($data['search']) ?>"</p>
                        <a href="<?= BASE_URL ?>/notes" class="btn btn-primary mt-3">Clear Search</a>
                    <?php elseif (!empty($data['current_label'])): ?>
                        <div class="display-1 text-muted mb-3"><i class="fas fa-tag"></i></div>
                        <h3>No notes with this label</h3>
                        <p class="text-muted">You don't have any notes with this label yet</p>
                        <a href="<?= BASE_URL ?>/notes/create" class="btn btn-primary mt-3">Create a Note</a>
                    <?php else: ?>
                        <div class="display-1 text-muted mb-3"><i class="fas fa-sticky-note"></i></div>
                        <h3>No notes yet</h3>
                        <p class="text-muted">Create your first note to get started</p>
                        <a href="<?= BASE_URL ?>/notes/create" class="btn btn-primary mt-3">Create a Note</a>
                    <?php endif; ?>
                </div>
            </div>
        <?php else: ?>
            <?php if ($data['view'] === 'grid'): ?>
                <!-- Grid View for Notes -->
                <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-4">
                    <?php foreach ($data['notes'] as $note): ?>
                        <div class="col">
                            <div class="card h-100 shadow-sm <?= isset($note['is_pinned']) && $note['is_pinned'] ? 'border-primary' : '' ?>">
                                <?php if (isset($note['is_pinned']) && $note['is_pinned']): ?>
                                    <div class="position-absolute top-0 start-0 translate-middle-y ms-3 badge bg-primary">
                                        <i class="fas fa-thumbtack"></i> Pinned
                                    </div>
                                <?php endif; ?>
                                
                                <!-- Check if note has images and display the first one as thumbnail -->
                                <?php 
                                $hasImages = isset($note['image_count']) && $note['image_count'] > 0; 
                                $isProtected = isset($note['is_password_protected']) && $note['is_password_protected'];
                                
                                // Get the URL for the note - either password verification or direct edit
                                $noteUrl = $isProtected 
                                    ? BASE_URL . '/notes/verify-password/' . $note['id'] 
                                    : BASE_URL . '/notes/edit/' . $note['id'];
                                
                                // Check if the note has images associated with it
                                if ($hasImages && isset($note['images']) && !empty($note['images'])): 
                                    $firstImage = $note['images'][0];
                                ?>
                                <div class="card-img-top position-relative note-thumbnail-container">
                                    <a href="<?= $noteUrl ?>">
                                        <img src="<?= UPLOADS_URL . '/' . $firstImage['file_path'] ?>" 
                                             class="note-thumbnail" alt="Note image">
                                        
                                        <?php if ($note['image_count'] > 1): ?>
                                            <div class="position-absolute bottom-0 end-0 badge bg-dark m-2">
                                                <i class="fas fa-images me-1"></i> <?= $note['image_count'] ?>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <?php if ($isProtected): ?>
                                            <div class="image-lock-overlay">
                                                <i class="fas fa-lock fa-2x"></i>
                                            </div>
                                        <?php endif; ?>
                                    </a>
                                </div>
                                <?php endif; ?>
                                
                                <div class="card-header pb-0 bg-transparent d-flex justify-content-between align-items-center">
                                    <h5 class="card-title mb-0 text-truncate">
                                        <a href="<?= $noteUrl ?>" class="text-decoration-none">
                                            <?php if ($isProtected): ?>
                                                <i class="fas fa-lock me-1 text-warning"></i>
                                            <?php endif; ?>
                                            <?= htmlspecialchars($note['title']) ?>
                                        </a>
                                    </h5>
                                    
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-link text-muted" type="button" data-bs-toggle="dropdown">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
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
                                            echo '<i class="fas fa-lock text-warning mb-2" style="font-size: 2rem;"></i>';
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
                                        <div class="mt-3">
                                            <?php foreach ($note['labels'] as $label): ?>
                                                <span class="badge bg-light text-dark border me-1">
                                                    <?= htmlspecialchars($label['name'] ?? '') ?>
                                                </span>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="card-footer bg-transparent d-flex justify-content-between align-items-center text-muted small">
                                    <div>
                                        <?php if (isset($note['is_pinned']) && $note['is_pinned']): ?>
                                            <span class="me-2 text-primary" title="Pinned">
                                                <i class="fas fa-thumbtack"></i>
                                            </span>
                                        <?php endif; ?>
                                        
                                        <?php if ($isProtected): ?>
                                            <span class="me-2 text-warning" title="Password Protected">
                                                <i class="fas fa-lock"></i>
                                            </span>
                                        <?php endif; ?>
                                        
                                        <?php if (isset($note['is_shared']) && $note['is_shared']): ?>
                                            <span class="me-2 text-info" title="Shared with others">
                                                <i class="fas fa-share-alt"></i>
                                            </span>
                                        <?php endif; ?>
                                        
                                        <?php if ($hasImages): ?>
                                            <span class="me-2" title="<?= $note['image_count'] ?> image(s) attached">
                                                <i class="fas fa-image"></i> <?= $note['image_count'] ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div>
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
            <?php else: ?>
                <!-- List View for Notes -->
                <div class="card shadow-sm">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 50px"></th>
                                    <th>Title</th>
                                    <th>Content</th>
                                    <th>Labels</th>
                                    <th style="width: 180px">Last Modified</th>
                                    <th style="width: 120px">Actions</th>
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
                                    <tr class="<?= isset($note['is_pinned']) && $note['is_pinned'] ? 'table-primary' : '' ?>">
                                        <td class="text-center">
                                            <?php if (isset($note['is_pinned']) && $note['is_pinned']): ?>
                                                <i class="fas fa-thumbtack text-primary me-1" title="Pinned"></i>
                                            <?php endif; ?>
                                            
                                            <?php if ($isProtected): ?>
                                                <i class="fas fa-lock text-warning me-1" title="Password Protected"></i>
                                            <?php endif; ?>
                                            
                                            <?php if (isset($note['is_shared']) && $note['is_shared']): ?>
                                                <i class="fas fa-share-alt text-info me-1" title="Shared with others"></i>
                                            <?php endif; ?>
                                            
                                            <?php if ($hasImages): ?>
                                                <i class="fas fa-image text-secondary me-1" title="<?= $note['image_count'] ?> image(s) attached"></i>
                                            <?php endif; ?>
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
                                                
                                                <strong>
                                                    <a href="<?= $noteUrl ?>" class="text-decoration-none">
                                                        <?= htmlspecialchars($note['title']) ?>
                                                    </a>
                                                </strong>
                                            </div>
                                        </td>
                                        <td class="text-truncate" style="max-width: 250px;">
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
                                        <td>
                                            <?php if (isset($note['labels']) && !empty($note['labels'])): ?>
                                                <?php foreach ($note['labels'] as $label): ?>
                                                    <span class="badge bg-light text-dark border me-1">
                                                        <?= htmlspecialchars($label['name'] ?? '') ?>
                                                    </span>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <small class="text-muted">None</small>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <small>
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
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <button class="btn btn-outline-primary pin-note" data-id="<?= $note['id'] ?>" title="<?= isset($note['is_pinned']) && $note['is_pinned'] ? 'Unpin' : 'Pin' ?>">
                                                    <i class="fas fa-thumbtack <?= isset($note['is_pinned']) && $note['is_pinned'] ? 'text-primary' : '' ?>"></i>
                                                </button>
                                                <a href="<?= $noteUrl ?>" class="btn btn-outline-secondary" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="<?= BASE_URL ?>/notes/share/<?= $note['id'] ?>" class="btn btn-outline-info" title="Share">
                                                    <i class="fas fa-share-alt"></i>
                                                </a>
                                                <a href="<?= BASE_URL ?>/notes/delete/<?= $note['id'] ?>" class="btn btn-outline-danger delete-note" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<!-- Add styles for thumbnails and protected content -->
<style>
/* Note thumbnail styling */
.note-thumbnail-container {
    height: 180px;
    overflow: hidden;
    position: relative;
}

.note-thumbnail {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.note-thumbnail-container:hover .note-thumbnail {
    transform: scale(1.05);
}

/* List view thumbnail */
.list-view-thumbnail {
    position: relative;
    width: 50px;
    height: 50px;
    overflow: hidden;
}

.list-view-thumbnail img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* Password protection styling */
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
}

.protected-content {
    background-color: rgba(255, 193, 7, 0.1);
    border-radius: 5px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Search functionality
    const searchInput = document.getElementById('search-input');
    const clearSearchBtn = document.getElementById('clear-search');
    
    if (searchInput) {
        let searchTimeout;
        
        searchInput.addEventListener('input', function() {
            // Clear previous timeout
            clearTimeout(searchTimeout);
            
            // Show/hide clear button
            if (clearSearchBtn) {
                clearSearchBtn.style.display = this.value ? 'block' : 'none';
            }
            
            // Set timeout for search
            searchTimeout = setTimeout(function() {
                // Get current URL and update search parameter
                const url = new URL(window.location.href);
                if (searchInput.value) {
                    url.searchParams.set('search', searchInput.value);
                } else {
                    url.searchParams.delete('search');
                }
                
                // Navigate to the URL
                window.location.href = url.toString();
            }, 300); // 300ms delay for typing
        });
        
        // Clear search button
        if (clearSearchBtn) {
            clearSearchBtn.addEventListener('click', function() {
                searchInput.value = '';
                this.style.display = 'none';
                
                // Remove search parameter and reload
                const url = new URL(window.location.href);
                url.searchParams.delete('search');
                window.location.href = url.toString();
            });
        }
    }
    
    // Pin/unpin note functionality
    const pinButtons = document.querySelectorAll('.pin-note');
    
    if (pinButtons.length > 0) {
        pinButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                
                const noteId = this.getAttribute('data-id');
                const icon = this.querySelector('i');
                
                // Send AJAX request
                fetch(BASE_URL + '/notes/toggle-pin/' + noteId, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Refresh the page to reflect changes
                        window.location.reload();
                    } else if (data.redirect) {
                        // Redirect to password verification if needed
                        window.location.href = data.redirect;
                    } else {
                        console.error('Error:', data.message);
                    }
                })
                .catch(error => console.error('Error:', error));
            });
        });
    }
    
    // Delete confirmation
    const deleteLinks = document.querySelectorAll('.delete-note');
    
    if (deleteLinks.length > 0) {
        deleteLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                if (!confirm('Are you sure you want to delete this note? This action cannot be undone.')) {
                    e.preventDefault();
                }
            });
        });
    }
});
</script>