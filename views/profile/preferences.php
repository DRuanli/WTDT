<div class="row">
    <div class="col-lg-10 mx-auto">
        <div class="d-md-flex justify-content-between align-items-center mb-4">
            <h2 class="h3 mb-3 mb-md-0">
                <i class="fas fa-cog me-2 text-primary"></i>User Preferences
            </h2>
            <a href="<?= BASE_URL ?>/profile" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back to Profile
            </a>
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
        
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <ul class="nav nav-tabs card-header-tabs" id="preferencesTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="appearance-tab" data-bs-toggle="tab" data-bs-target="#appearance" 
                                type="button" role="tab" aria-controls="appearance" aria-selected="true">
                            <i class="fas fa-palette me-2"></i>Appearance
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="notes-tab" data-bs-toggle="tab" data-bs-target="#notes" 
                                type="button" role="tab" aria-controls="notes" aria-selected="false">
                            <i class="fas fa-sticky-note me-2"></i>Notes Settings
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="notifications-tab" data-bs-toggle="tab" data-bs-target="#notifications" 
                                type="button" role="tab" aria-controls="notifications" aria-selected="false">
                            <i class="fas fa-bell me-2"></i>Notifications
                        </button>
                    </li>
                </ul>
            </div>
            
            <div class="card-body">
                <form method="POST" action="<?= BASE_URL ?>/profile/save-preferences" class="preferences-form">
                    <div class="tab-content" id="preferencesTabContent">
                        <!-- Appearance Tab -->
                        <div class="tab-pane fade show active" id="appearance" role="tabpanel" aria-labelledby="appearance-tab">
                            <h5 class="card-title mb-4">Appearance Settings</h5>
                            
                            <div class="mb-4">
                                <label class="form-label fw-bold">Theme</label>
                                <div class="row row-cols-1 row-cols-md-3 g-3">
                                    <div class="col">
                                        <div class="form-check card">
                                            <div class="card-body">
                                                <input class="form-check-input" type="radio" name="theme" id="theme-light" value="light" checked>
                                                <label class="form-check-label w-100" for="theme-light">
                                                    <div class="d-flex align-items-center mb-2">
                                                        <i class="fas fa-sun me-2 text-warning"></i>
                                                        <strong>Light Theme</strong>
                                                    </div>
                                                    <div class="theme-preview bg-light border p-2 text-center rounded">
                                                        <div class="bg-white border mb-1 p-1">Light Mode</div>
                                                        <small class="text-dark">Default bright theme</small>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col">
                                        <div class="form-check card">
                                            <div class="card-body">
                                                <input class="form-check-input" type="radio" name="theme" id="theme-dark" value="dark">
                                                <label class="form-check-label w-100" for="theme-dark">
                                                    <div class="d-flex align-items-center mb-2">
                                                        <i class="fas fa-moon me-2 text-primary"></i>
                                                        <strong>Dark Theme</strong>
                                                    </div>
                                                    <div class="theme-preview bg-dark border p-2 text-center rounded">
                                                        <div class="bg-secondary border mb-1 p-1 text-white">Dark Mode</div>
                                                        <small class="text-white">Easier on the eyes at night</small>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col">
                                        <div class="form-check card">
                                            <div class="card-body">
                                                <input class="form-check-input" type="radio" name="theme" id="theme-system" value="system">
                                                <label class="form-check-label w-100" for="theme-system">
                                                    <div class="d-flex align-items-center mb-2">
                                                        <i class="fas fa-laptop me-2 text-info"></i>
                                                        <strong>System Default</strong>
                                                    </div>
                                                    <div class="theme-preview bg-light border p-2 text-center rounded">
                                                        <div class="bg-white border mb-1 p-1">Auto</div>
                                                        <small class="text-dark">Follow system settings</small>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label fw-bold">Font Size</label>
                                <div class="row row-cols-1 row-cols-md-3 g-3">
                                    <div class="col">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="font_size" id="font-small" value="small">
                                            <label class="form-check-label" for="font-small">
                                                <span style="font-size: 0.875rem;">Small</span>
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <div class="col">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="font_size" id="font-medium" value="medium" checked>
                                            <label class="form-check-label" for="font-medium">
                                                <span style="font-size: 1rem;">Medium</span>
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <div class="col">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="font_size" id="font-large" value="large">
                                            <label class="form-check-label" for="font-large">
                                                <span style="font-size: 1.125rem;">Large</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Notes Settings Tab -->
                        <div class="tab-pane fade" id="notes" role="tabpanel" aria-labelledby="notes-tab">
                            <h5 class="card-title mb-4">Notes Display Settings</h5>
                            
                            <div class="mb-4">
                                <label class="form-label fw-bold">Default Note View</label>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="form-check card">
                                            <div class="card-body">
                                                <input class="form-check-input" type="radio" name="default_view" id="view-grid" value="grid" checked>
                                                <label class="form-check-label w-100" for="view-grid">
                                                    <div class="d-flex align-items-center">
                                                        <i class="fas fa-th-large me-2 text-primary"></i>
                                                        <strong>Grid View</strong>
                                                    </div>
                                                    <small class="text-muted d-block mt-2">Display notes in a grid layout like cards</small>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-check card">
                                            <div class="card-body">
                                                <input class="form-check-input" type="radio" name="default_view" id="view-list" value="list">
                                                <label class="form-check-label w-100" for="view-list">
                                                    <div class="d-flex align-items-center">
                                                        <i class="fas fa-list me-2 text-primary"></i>
                                                        <strong>List View</strong>
                                                    </div>
                                                    <small class="text-muted d-block mt-2">Display notes in a compact list format</small>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label fw-bold">Note Color</label>
                                <div class="d-flex flex-wrap gap-3">
                                    <div class="form-check">
                                        <input class="form-check-input visually-hidden" type="radio" name="note_color" id="color-white" value="white" checked>
                                        <label class="form-check-label" for="color-white">
                                            <div class="color-swatch bg-white border rounded-circle" style="width: 40px; height: 40px;"></div>
                                        </label>
                                    </div>
                                    
                                    <div class="form-check">
                                        <input class="form-check-input visually-hidden" type="radio" name="note_color" id="color-blue" value="blue">
                                        <label class="form-check-label" for="color-blue">
                                            <div class="color-swatch bg-primary-subtle border rounded-circle" style="width: 40px; height: 40px;"></div>
                                        </label>
                                    </div>
                                    
                                    <div class="form-check">
                                        <input class="form-check-input visually-hidden" type="radio" name="note_color" id="color-green" value="green">
                                        <label class="form-check-label" for="color-green">
                                            <div class="color-swatch bg-success-subtle border rounded-circle" style="width: 40px; height: 40px;"></div>
                                        </label>
                                    </div>
                                    
                                    <div class="form-check">
                                        <input class="form-check-input visually-hidden" type="radio" name="note_color" id="color-yellow" value="yellow">
                                        <label class="form-check-label" for="color-yellow">
                                            <div class="color-swatch bg-warning-subtle border rounded-circle" style="width: 40px; height: 40px;"></div>
                                        </label>
                                    </div>
                                    
                                    <div class="form-check">
                                        <input class="form-check-input visually-hidden" type="radio" name="note_color" id="color-purple" value="purple">
                                        <label class="form-check-label" for="color-purple">
                                            <div class="color-swatch bg-purple border rounded-circle" style="width: 40px; height: 40px; background-color: #f2e6ff;"></div>
                                        </label>
                                    </div>
                                    
                                    <div class="form-check">
                                        <input class="form-check-input visually-hidden" type="radio" name="note_color" id="color-pink" value="pink">
                                        <label class="form-check-label" for="color-pink">
                                            <div class="color-swatch bg-pink border rounded-circle" style="width: 40px; height: 40px; background-color: #ffe6f2;"></div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="auto-save" name="auto_save" value="1" checked>
                                    <label class="form-check-label" for="auto-save">Enable auto-save when editing notes</label>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Notifications Tab -->
                        <div class="tab-pane fade" id="notifications" role="tabpanel" aria-labelledby="notifications-tab">
                            <h5 class="card-title mb-4">Notification Preferences</h5>
                            
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="email-notifications" name="email_notifications" value="1" checked>
                                    <label class="form-check-label" for="email-notifications">Receive email notifications when notes are shared with me</label>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="browser-notifications" name="browser_notifications" value="1" checked>
                                    <label class="form-check-label" for="browser-notifications">Show browser notifications for real-time updates</label>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="collaboration-notifications" name="collaboration_notifications" value="1" checked>
                                    <label class="form-check-label" for="collaboration-notifications">Get notifications about changes to shared notes</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                        <button type="reset" class="btn btn-outline-secondary">
                            <i class="fas fa-undo me-1"></i> Reset to Defaults
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Save Preferences
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.color-swatch {
    cursor: pointer;
    transition: transform 0.2s;
    position: relative;
}

.color-swatch:hover {
    transform: scale(1.1);
}

input[type="radio"]:checked + label .color-swatch::after {
    content: '\f00c';
    font-family: 'Font Awesome 5 Free';
    font-weight: 900;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    color: #333;
}

/* Show check on dark swatches with light color */
input#color-purple:checked + label .color-swatch::after,
input#color-blue:checked + label .color-swatch::after {
    color: white;
}

.theme-preview {
    transition: all 0.2s;
}

input[type="radio"]:checked + label .theme-preview {
    box-shadow: 0 0 0 2px #4a89dc;
}

.form-check-input + label {
    cursor: pointer;
}
</style>