<?php
// This is the list view template for displaying notes

// Make sure we have the notes array available
$notes = $data['notes'] ?? [];
?>

<div class="notes-list">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th width="60"></th>
                    <th>Title</th>
                    <th>Content</th>
                    <th>Attachments</th>
                    <th>Labels</th>
                    <th width="180">Last Modified</th>
                    <th width="120">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($notes as $note): ?>
                    <tr class="<?= isset($note['is_pinned']) && $note['is_pinned'] ? 'pinned' : '' ?>">
                        <td class="note-status">
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
                            </div>
                        </td>
                        <td class="note-title">
                            <?php if (isset($note['is_password_protected']) && $note['is_password_protected']): ?>
                                <a href="<?= BASE_URL ?>/notes/verify-password/<?= $note['id'] ?>">
                                    <?= htmlspecialchars($note['title']) ?>
                                </a>
                            <?php else: ?>
                                <a href="<?= BASE_URL ?>/notes/edit/<?= $note['id'] ?>">
                                    <?= htmlspecialchars($note['title']) ?>
                                </a>
                            <?php endif; ?>
                        </td>
                        <td class="note-content">
                            <?php 
                            $content = isset($note['content']) ? $note['content'] : '';
                            $preview = strip_tags($content);
                            $preview = substr($preview, 0, 100);
                            if (strlen($content) > 100) $preview .= '...';
                            echo htmlspecialchars($preview);
                            ?>
                        </td>
                        <td class="note-attachments">
                            <?php if (isset($note['images']) && !empty($note['images'])): ?>
                                <div class="d-flex flex-wrap gap-1">
                                    <?php 
                                    $displayLimit = 2;
                                    $count = 0;
                                    foreach ($note['images'] as $attachment): 
                                        if ($count >= $displayLimit) break;
                                        
                                        $ext = pathinfo($attachment['file_path'], PATHINFO_EXTENSION);
                                        $isImage = in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif']);
                                        $count++;
                                    ?>
                                        <?php if ($isImage): ?>
                                            <div class="attachment-thumbnail-small">
                                                <img src="<?= UPLOADS_URL . '/' . $attachment['file_path'] ?>" 
                                                     alt="<?= htmlspecialchars($attachment['file_name']) ?>"
                                                     class="img-thumbnail" style="width: 40px; height: 40px; object-fit: cover;">
                                            </div>
                                        <?php else: ?>
                                            <div class="file-icon-xs d-flex align-items-center justify-content-center rounded" 
                                                 style="width: 40px; height: 40px; background-color: #f8f9fa;">
                                                <span class="small">.<?= strtoupper($ext) ?></span>
                                            </div>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                    
                                    <?php if (count($note['images']) > $displayLimit): ?>
                                        <div class="more-attachments-small rounded d-flex align-items-center justify-content-center bg-light" 
                                             style="width: 40px; height: 40px;">
                                            <span class="small text-secondary">+<?= count($note['images']) - $displayLimit ?></span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php else: ?>
                                <span class="text-muted">None</span>
                            <?php endif; ?>
                        </td>
                        <td class="note-labels">
                            <?php if (isset($note['labels']) && !empty($note['labels'])): ?>
                                <?php foreach ($note['labels'] as $label): ?>
                                    <span class="label">
                                        <?= htmlspecialchars($label['name'] ?? '') ?>
                                    </span>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <span class="no-labels">None</span>
                            <?php endif; ?>
                        </td>
                        <td class="note-date">
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
                        </td>
                        <td class="note-actions">
                            <div class="action-buttons">
                                <button class="pin-note" data-id="<?= $note['id'] ?>" title="<?= isset($note['is_pinned']) && $note['is_pinned'] ? 'Unpin' : 'Pin' ?>">
                                    <i class="fas fa-thumbtack <?= isset($note['is_pinned']) && $note['is_pinned'] ? 'pinned' : '' ?>"></i>
                                </button>
                                <a href="<?= BASE_URL ?>/notes/edit/<?= $note['id'] ?>" class="edit-note" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="<?= BASE_URL ?>/notes/share/<?= $note['id'] ?>" class="share-note" title="Share">
                                    <i class="fas fa-share-alt"></i>
                                </a>
                                <a href="<?= BASE_URL ?>/notes/delete/<?= $note['id'] ?>" class="delete-note" title="Delete">
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