<?php
// This is the grid view template for displaying notes

// Make sure we have the notes array available
$notes = $data['notes'] ?? [];
?>

<div class="notes-grid">
    <?php foreach ($notes as $note): ?>
        <div class="note-card <?= isset($note['is_pinned']) && $note['is_pinned'] ? 'pinned' : '' ?>">
            <div class="note-actions">
                <button class="pin-note" data-id="<?= $note['id'] ?>" title="<?= isset($note['is_pinned']) && $note['is_pinned'] ? 'Unpin' : 'Pin' ?>">
                    <i class="fas fa-thumbtack <?= isset($note['is_pinned']) && $note['is_pinned'] ? 'pinned' : '' ?>"></i>
                </button>
                <div class="dropdown">
                    <button class="dropdown-toggle">
                        <i class="fas fa-ellipsis-v"></i>
                    </button>
                    <div class="dropdown-menu">
                        <a href="<?= BASE_URL ?>/notes/edit/<?= $note['id'] ?>">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="<?= BASE_URL ?>/notes/share/<?= $note['id'] ?>">
                            <i class="fas fa-share-alt"></i> Share
                        </a>
                        <a href="<?= BASE_URL ?>/notes/toggle-password/<?= $note['id'] ?>">
                            <?php if (isset($note['is_password_protected']) && $note['is_password_protected']): ?>
                                <i class="fas fa-unlock"></i> Remove Password
                            <?php else: ?>
                                <i class="fas fa-lock"></i> Add Password
                            <?php endif; ?>
                        </a>
                        <a href="<?= BASE_URL ?>/notes/delete/<?= $note['id'] ?>" class="delete-note text-danger">
                            <i class="fas fa-trash"></i> Delete
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="note-content">
                <h3 class="note-title">
                    <?php if (isset($note['is_password_protected']) && $note['is_password_protected']): ?>
                        <a href="<?= BASE_URL ?>/notes/verify-password/<?= $note['id'] ?>">
                            <?= htmlspecialchars($note['title']) ?>
                        </a>
                    <?php else: ?>
                        <a href="<?= BASE_URL ?>/notes/edit/<?= $note['id'] ?>">
                            <?= htmlspecialchars($note['title']) ?>
                        </a>
                    <?php endif; ?>
                </h3>
                
                <?php 
                // Display attachments if available
                if (isset($note['images']) && !empty($note['images'])): 
                    $displayLimit = 3; // Maximum number of images to display
                    $count = 0;
                ?>
                <div class="note-attachments mb-2">
                    <div class="attachment-previews d-flex gap-2 flex-wrap">
                        <?php foreach ($note['images'] as $attachment): 
                            if ($count >= $displayLimit) break;
                            
                            $ext = pathinfo($attachment['file_path'], PATHINFO_EXTENSION);
                            $isImage = in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif']);
                            $count++;
                        ?>
                            <?php if ($isImage): ?>
                                <div class="attachment-thumbnail">
                                    <img src="<?= UPLOADS_URL . '/' . $attachment['file_path'] ?>" 
                                         alt="<?= htmlspecialchars($attachment['file_name']) ?>"
                                         class="img-thumbnail" style="width: 60px; height: 60px; object-fit: cover;">
                                </div>
                            <?php else: ?>
                                <div class="file-icon-small rounded p-1 bg-light text-center" style="width: 60px; height: 60px;">
                                    <i class="fas fa-file fa-2x text-secondary mt-1"></i>
                                    <div class="file-ext small">.<?= strtoupper($ext) ?></div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        
                        <?php if (count($note['images']) > $displayLimit): ?>
                            <div class="more-attachments rounded-circle bg-light d-flex align-items-center justify-content-center" 
                                style="width: 60px; height: 60px;">
                                <span class="text-secondary">+<?= count($note['images']) - $displayLimit ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <div class="note-body">
                    <?php 
                    $content = isset($note['content']) ? $note['content'] : '';
                    $preview = strip_tags($content);
                    $preview = substr($preview, 0, 150);
                    if (strlen($content) > 150) $preview .= '...';
                    echo nl2br(htmlspecialchars($preview));
                    ?>
                </div>
                
                <?php if (isset($note['labels']) && !empty($note['labels'])): ?>
                    <div class="note-labels">
                        <?php foreach ($note['labels'] as $label): ?>
                            <span class="label">
                                <?= htmlspecialchars($label['name'] ?? '') ?>
                            </span>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                
                <div class="note-footer">
                    <div class="note-indicators">
                        <?php if (isset($note['is_pinned']) && $note['is_pinned']): ?>
                            <span class="indicator pinned" title="Pinned"><i class="fas fa-thumbtack"></i></span>
                        <?php endif; ?>
                        
                        <?php if (isset($note['is_password_protected']) && $note['is_password_protected']): ?>
                            <span class="indicator locked" title="Password Protected"><i class="fas fa-lock"></i></span>
                        <?php endif; ?>
                        
                        <?php if (isset($note['is_shared']) && $note['is_shared']): ?>
                            <span class="indicator shared" title="Shared with others"><i class="fas fa-share-alt"></i></span>
                        <?php endif; ?>
                        
                        <?php if (isset($note['image_count']) && $note['image_count'] > 0): ?>
                            <span class="indicator" title="<?= $note['image_count'] ?> attachment(s)">
                                <i class="fas fa-paperclip"></i> <?= $note['image_count'] ?>
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