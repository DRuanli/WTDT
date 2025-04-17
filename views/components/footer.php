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
        
        // Check if we can install this as an app
        let deferredPrompt;
        const addBtn = document.createElement('button');
        addBtn.id = 'install-button';
        addBtn.className = 'btn btn-success btn-sm position-fixed bottom-0 end-0 m-3';
        addBtn.innerHTML = '<i class="fas fa-download me-1"></i> Install App';
        addBtn.style.display = 'none';
        addBtn.style.zIndex = '1000';
        document.body.appendChild(addBtn);

        window.addEventListener('beforeinstallprompt', (e) => {
            // Prevent Chrome 67 and earlier from automatically showing the prompt
            e.preventDefault();
            // Stash the event so it can be triggered later
            deferredPrompt = e;
            // Update UI to notify the user they can add to home screen
            addBtn.style.display = 'block';

            addBtn.addEventListener('click', () => {
                // Hide our user interface that shows our install button
                addBtn.style.display = 'none';
                // Show the install prompt
                deferredPrompt.prompt();
                // Wait for the user to respond to the prompt
                deferredPrompt.userChoice.then((choiceResult) => {
                    if (choiceResult.outcome === 'accepted') {
                        console.log('User accepted the install prompt');
                    } else {
                        console.log('User dismissed the install prompt');
                    }
                    deferredPrompt = null;
                });
            });
        });
    </script>
    <?php endif; ?>
    
</body>
</html>