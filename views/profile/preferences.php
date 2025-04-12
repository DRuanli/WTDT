<?php
// Get current preferences
$current_theme = $data['preferences']['theme'] ?? 'light';
$current_font_size = $data['preferences']['font_size'] ?? 'medium';
$current_note_color = $data['preferences']['note_color'] ?? 'white';
?>

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
                                <div class="row row-cols-1 row-cols-md-2 g-3">
                                    <div class="col">
                                        <div class="form-check card">
                                            <div class="card-body">
                                                <input class="form-check-input" type="radio" name="theme" id="theme-light" value="light" 
                                                       <?= $current_theme === 'light' ? 'checked' : '' ?>>
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
                                                <input class="form-check-input" type="radio" name="theme" id="theme-dark" value="dark"
                                                       <?= $current_theme === 'dark' ? 'checked' : '' ?>>
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
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label fw-bold">Font Size</label>
                                <div class="row row-cols-1 row-cols-md-3 g-3">
                                    <div class="col">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="font_size" id="font-small" value="small"
                                                  <?= $current_font_size === 'small' ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="font-small">
                                                <span style="font-size: 0.875rem;">Small</span>
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <div class="col">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="font_size" id="font-medium" value="medium"
                                                  <?= $current_font_size === 'medium' ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="font-medium">
                                                <span style="font-size: 1rem;">Medium</span>
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <div class="col">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="font_size" id="font-large" value="large"
                                                  <?= $current_font_size === 'large' ? 'checked' : '' ?>>
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
                                <label class="form-label fw-bold">Note Color</label>
                                <div class="d-flex flex-wrap gap-3">
                                    <div class="form-check">
                                        <input class="form-check-input visually-hidden" type="radio" name="note_color" id="color-white" value="white"
                                              <?= $current_note_color === 'white' ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="color-white">
                                            <div class="color-swatch bg-white border rounded-circle" style="width: 40px; height: 40px;"></div>
                                        </label>
                                    </div>
                                    
                                    <div class="form-check">
                                        <input class="form-check-input visually-hidden" type="radio" name="note_color" id="color-blue" value="blue"
                                              <?= $current_note_color === 'blue' ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="color-blue">
                                            <div class="color-swatch bg-primary-subtle border rounded-circle" style="width: 40px; height: 40px;"></div>
                                        </label>
                                    </div>
                                    
                                    <div class="form-check">
                                        <input class="form-check-input visually-hidden" type="radio" name="note_color" id="color-green" value="green"
                                              <?= $current_note_color === 'green' ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="color-green">
                                            <div class="color-swatch bg-success-subtle border rounded-circle" style="width: 40px; height: 40px;"></div>
                                        </label>
                                    </div>
                                    
                                    <div class="form-check">
                                        <input class="form-check-input visually-hidden" type="radio" name="note_color" id="color-yellow" value="yellow"
                                              <?= $current_note_color === 'yellow' ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="color-yellow">
                                            <div class="color-swatch bg-warning-subtle border rounded-circle" style="width: 40px; height: 40px;"></div>
                                        </label>
                                    </div>
                                    
                                    <div class="form-check">
                                        <input class="form-check-input visually-hidden" type="radio" name="note_color" id="color-purple" value="purple"
                                              <?= $current_note_color === 'purple' ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="color-purple">
                                            <div class="color-swatch border rounded-circle" style="width: 40px; height: 40px; background-color: #f2e6ff;"></div>
                                        </label>
                                    </div>
                                    
                                    <div class="form-check">
                                        <input class="form-check-input visually-hidden" type="radio" name="note_color" id="color-pink" value="pink"
                                              <?= $current_note_color === 'pink' ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="color-pink">
                                            <div class="color-swatch border rounded-circle" style="width: 40px; height: 40px; background-color: #ffe6f2;"></div>
                                        </label>
                                    </div>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Preview preferences on change
    const themeRadios = document.querySelectorAll('input[name="theme"]');
    const fontSizeRadios = document.querySelectorAll('input[name="font_size"]');
    const noteColorRadios = document.querySelectorAll('input[name="note_color"]');
    
    // Preview theme
    themeRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            document.documentElement.setAttribute('data-bs-theme', this.value);
            document.body.setAttribute('data-bs-theme', this.value);
        });
    });
    
    // Preview font size
    fontSizeRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            // Remove existing font size classes
            document.body.classList.remove('font-size-small', 'font-size-medium', 'font-size-large');
            // Add selected font size class
            document.body.classList.add(`font-size-${this.value}`);
        });
    });
    
    // Preview note color
    noteColorRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            // Remove existing note color classes
            document.body.className = document.body.className.replace(/\bnote-color-\S+/g, '');
            // Add selected note color class
            document.body.classList.add(`note-color-${this.value}`);
        });
    });
});
</script>