<div class="labels-container">
    <div class="labels-header">
        <h2>Manage Labels</h2>
        <a href="<?= BASE_URL ?>/notes" class="btn btn-outline">
            <i class="fas fa-arrow-left"></i> Back to Notes
        </a>
    </div>
    
    <div id="message-container"></div>
    
    <div class="labels-content">
        <div class="labels-list-container">
            <h3>Your Labels</h3>
            
            <div id="labels-list">
                <?php if (empty($data['labels'])): ?>
                    <div class="empty-labels">
                        <p>You don't have any labels yet. Create your first label using the form on the right.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($data['labels'] as $label): ?>
                        <div class="label-item" data-label-id="<?= $label['id'] ?>">
                            <div class="label-info">
                                <span class="label-name"><?= htmlspecialchars($label['name']) ?></span>
                                <span class="note-count"><?= $label['note_count'] ?> notes</span>
                            </div>
                            <div class="label-actions">
                                <button class="btn-edit" data-id="<?= $label['id'] ?>" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn-delete" data-id="<?= $label['id'] ?>" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="label-form-container">
            <h3>Create New Label</h3>
            
            <form id="label-form" method="POST" action="<?= BASE_URL ?>/labels/create">
                <div class="form-group">
                    <label for="label-name">Label Name</label>
                    <input type="text" id="label-name" name="name" class="form-control" required>
                </div>
                
                <div class="form-actions">
                    <button type="submit" id="submit-label" class="btn btn-primary">Create Label</button>
                    <button type="button" id="cancel-label" class="btn btn-outline" style="display:none;">Cancel</button>
                </div>
            </form>
            
            <div class="label-tips">
                <h4>Tips</h4>
                <ul>
                    <li>Use labels to organize your notes by topic, project, or priority</li>
                    <li>You can add multiple labels to a single note</li>
                    <li>Click on a label in the sidebar to filter your notes</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<style>
.labels-container {
    max-width: 1000px;
    margin: 0 auto;
}

.labels-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.labels-content {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
}

.labels-list-container,
.label-form-container {
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    padding: 1.5rem;
}

.labels-list-container h3,
.label-form-container h3 {
    margin-bottom: 1rem;
    font-size: 1.2rem;
}

.label-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem;
    border-bottom: 1px solid #eee;
}

.label-item:last-child {
    border-bottom: none;
}

.label-info {
    display: flex;
    align-items: center;
}

.label-name {
    font-weight: 500;
    margin-right: 0.75rem;
}

.note-count {
    font-size: 0.8rem;
    color: #777;
    background: #f5f5f5;
    padding: 0.2rem 0.5rem;
    border-radius: 10px;
}

.label-actions {
    display: flex;
    gap: 0.5rem;
}

.btn-edit,
.btn-delete {
    background: none;
    border: none;
    color: #777;
    cursor: pointer;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 4px;
}

.btn-edit:hover {
    background: #f0f5ff;
    color: #4a89dc;
}

.btn-delete:hover {
    background: #fff0f0;
    color: #dc3545;
}

.empty-labels {
    padding: 2rem 1rem;
    text-align: center;
    color: #777;
}

.form-actions {
    display: flex;
    gap: 0.5rem;
    margin-top: 1rem;
}

.label-tips {
    margin-top: 2rem;
    padding-top: 1.5rem;
    border-top: 1px solid #eee;
}

.label-tips h4 {
    font-size: 1rem;
    margin-bottom: 0.75rem;
}

.label-tips ul {
    padding-left: 1.5rem;
    color: #555;
}

.label-tips li {
    margin-bottom: 0.5rem;
}

@media (max-width: 768px) {
    .labels-content {
        grid-template-columns: 1fr;
    }
    
    .label-form-container {
        order: -1;
    }
}
</style>