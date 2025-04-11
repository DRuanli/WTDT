</div><!-- /.container -->
    </main>
    
    <footer class="py-4 bg-dark text-white mt-auto">
        <div class="container">
            <div class="d-flex flex-wrap justify-content-between align-items-center">
                <p class="mb-0">&copy; <?= date('Y') ?> <?= APP_NAME ?>. All rights reserved.</p>
                <div>
                    <a href="#" class="text-white me-3"><i class="fab fa-github"></i></a>
                    <a href="#" class="text-white me-3"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="text-white"><i class="fab fa-linkedin"></i></a>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Core JavaScript -->
    <script src="<?= ASSETS_URL ?>/js/main.js"></script>
    
    <!-- Page-specific JavaScript -->
    <?php if (isset($pageScripts) && is_array($pageScripts)): ?>
        <?php foreach ($pageScripts as $script): ?>
            <script src="<?= ASSETS_URL ?>/js/<?= $script ?>.js"></script>
        <?php endforeach; ?>
    <?php endif; ?>
    
    <!-- PWA support -->
    <?php if (defined('ENABLE_OFFLINE_MODE') && ENABLE_OFFLINE_MODE): ?>
    <script>
        // Register service worker for offline capabilities
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('<?= BASE_URL ?>/service-worker.js')
                    .then(function(registration) {
                        console.log('ServiceWorker registration successful with scope: ', registration.scope);
                    })
                    .catch(function(error) {
                        console.log('ServiceWorker registration failed: ', error);
                    });
            });
        }
    </script>
    <?php endif; ?>
    
</body>
</html>