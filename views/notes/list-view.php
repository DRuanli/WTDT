<div class="notes-list">
    <table class="table">
        <thead>
            <tr>
                <th></th>
                <th>Title</th>
                <th>Content</th>
                <th>Labels</th>
                <th>Last Modified</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data['notes'] as $note): ?>
                <tr class="<?= $note['is_pinned'] ? 'pinned' : '' ?>">
                    <td class="note-status">
                        <div class="note-indicators">
                            <?php if ($note['is_pinned']): ?>
                                <span class="indicator" title="Pinned"><i class="fas fa-thumbtack"></i></span>
                            <?php endif; ?>
                            
                            <?php if ($note['is_password_protected']): ?>
                                <span class="indicator" title="Password Protected"><i class="fas fa-lock"></i></span>
                            <?php endif; ?>
                            
                            <?php if ($note['image_count'] > 0): ?>
                                <span class="indicator" title="<?= $note['image_count'] ?> image(s) attached">
                                    <i class="fas fa-image"></i>
                                </span>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td class="note-title">
                        <?php if ($note['is_password_protected']): ?>
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
                        $preview = strip_tags($note['content']);
                        $preview = substr($preview, 0, 100);
                        if (strlen($note['content']) > 100) $preview .= '...';
                        echo htmlspecialchars($preview);
                        ?>
                    </td>
                    <td class="note-labels">
                        <?php if (!empty($note['labels'])): ?>
                            <?php foreach ($note['labels'] as $label): ?>
                                <span class="label">
                                    <?= htmlspecialchars($label['name']) ?>
                                </span>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <span class="no-labels">None</span>
                        <?php endif; ?>
                    </td>
                    <td class="note-date">
                        <?php 
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
                        ?>
                    </td>
                    <td class="note-actions">
                        <div class="action-buttons">
                            <button class="pin-note" data-id="<?= $note['id'] ?>" title="<?= $note['is_pinned'] ? 'Unpin' : 'Pin' ?>">
                                <i class="fas fa-thumbtack <?= $note['is_pinned'] ? 'pinned' : '' ?>"></i>
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