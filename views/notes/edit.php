<?php
// This is the edit.php view file for notes

// Make sure we have the note data available
$note = $data['note'] ?? ['title' => '', 'content' => ''];
$errors = $data['errors'] ?? [];
$formAction = isset($note['id']) ? BASE_URL . '/notes/update/' . $note['id'] : BASE_URL . '/notes/store';
?>

<div class="row">
    <div class="col-md-10 mx-auto">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><?= isset($note['id']) ? 'Edit Note' : 'Create Note' ?></h4>
                    <div>
                        <?php if (!isset($note['id'])): ?>
                            <!-- Show Create button only for new notes -->
                            <button type="button" id="create-note-btn" class="btn btn-primary me-2">
                                <i class="fas fa-plus me-1"></i> Create
                            </button>
                        <?php endif; ?>
                        <a href="<?= BASE_URL ?>/notes" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Back to Notes
                        </a>
                    </div>
                </div>
            </div>
            
            <form id="note-form" method="POST" action="<?= $formAction ?>" enctype="multipart/form-data">
                <?php if (!empty($errors['general'])): ?>
                    <div class="alert alert-danger m-3">
                        <?= $errors['general'] ?>
                    </div>
                <?php endif; ?>
                
                <div class="card-body">
                    <!-- Title Field -->
                    <div class="mb-3">
                        <input type="text" name="title" id="note-title" 
                               class="form-control form-control-lg border-0 shadow-none" 
                               placeholder="Note title" 
                               value="<?= htmlspecialchars($note['title'] ?? '') ?>" 
                               required>
                        <?php if (!empty($errors['title'])): ?>
                            <div class="invalid-feedback d-block"><?= $errors['title'] ?></div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Content Field -->
                    <div class="mb-3">
                        <textarea name="content" id="note-content" 
                                  class="form-control border-0 shadow-none" 
                                  placeholder="Note content..." 
                                  rows="12"><?= htmlspecialchars($note['content'] ?? '') ?></textarea>
                    </div>
                    
                    <!-- File Attachment Section -->
                    <div class="mt-4 mb-3">
                        <h5 class="mb-3"><i class="fas fa-paperclip me-2"></i>Attachments</h5>

                        <!-- Current Images/Files Section (if editing) -->
                        <?php if (isset($note['images']) && !empty($note['images'])): ?>
                            <div class="mb-3">
                                <h6>Current Attachments</h6>
                                <div class="d-flex flex-wrap gap-3 mt-3 mb-4">
                                    <?php foreach ($note['images'] as $image): ?>
                                        <div class="attachment-preview card">
                                            <div class="card-body p-2 text-center">
                                                <?php
                                                $ext = pathinfo($image['file_path'], PATHINFO_EXTENSION);
                                                $isImage = in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif']);
                                                ?>
                                                
                                                <?php if ($isImage): ?>
                                                    <div class="image-thumbnail mb-2">
                                                        <img src="<?= UPLOADS_URL . '/' . $image['file_path'] ?>" 
                                                             alt="<?= htmlspecialchars($image['file_name']) ?>"
                                                             class="img-thumbnail" style="max-width: 100px; max-height: 100px;">
                                                    </div>
                                                <?php else: ?>
                                                    <div class="file-icon mb-2">
                                                        <i class="fas fa-file-alt fa-3x text-secondary"></i>
                                                    </div>
                                                <?php endif; ?>
                                                
                                                <div class="file-name small text-truncate" style="max-width: 120px;">
                                                    <?= htmlspecialchars($image['file_name']) ?>
                                                </div>
                                                
                                                <div class="mt-2">
                                                    <a href="<?= BASE_URL ?>/notes/delete-image/<?= $image['id'] ?>" 
                                                       class="btn btn-sm btn-outline-danger delete-image"
                                                       data-id="<?= $image['id'] ?>">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- New File Upload -->
                        <div class="file-upload-container">
                            <div class="custom-file-upload mb-3">
                                <div class="upload-area p-4 rounded border border-dashed text-center" id="upload-area">
                                    <i class="fas fa-cloud-upload-alt fa-3x text-secondary mb-3"></i>
                                    <h5>Drag & Drop Files Here</h5>
                                    <p class="text-muted">or</p>
                                    <label for="file-input" class="btn btn-outline-primary">Browse Files</label>
                                    <input id="file-input" name="images[]" type="file" class="d-none" multiple>
                                    <p class="small text-muted mt-2">Supported formats: Images, PDFs, docs, and other common file formats<br>Max size: 10MB per file</p>
                                </div>
                            </div>

                            <!-- Preview for newly added files -->
                            <div id="preview-container" class="d-flex flex-wrap gap-3 mt-3" style="display: none;"></div>
                        </div>
                    </div>
                    
                    <!-- Submit buttons -->
                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="<?= BASE_URL ?>/notes" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> <?= isset($note['id']) ? 'Save Changes' : 'Create Note' ?>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Auto-save indicator -->
<div id="save-status" class="position-fixed bottom-0 end-0 m-3 p-2 px-3 rounded toast align-items-center" role="alert" aria-live="assertive" aria-atomic="true" style="display: none;">
    <div class="d-flex">
        <div class="toast-body d-flex align-items-center">
            <span id="saving-icon" class="me-2"><i class="fas fa-circle-notch fa-spin"></i></span>
            <span id="saved-icon" class="me-2" style="display: none;"><i class="fas fa-check text-success"></i></span>
            <span id="save-message">Saving...</span>
        </div>
    </div>
</div>