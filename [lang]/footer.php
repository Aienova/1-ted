<footer>
    <div class="footer-container">
        <!-- Footer Logo -->
        <div class="footer-logo-section">
            <div class="footer-logo">
                <img src="/media/image/logo.png" alt="1TED Logo" class="invert">
            </div>
        </div>

        <!-- Footer Menu -->
        <nav class="footer-nav">
            <ul class="footer-menu">
                <?php if (isset($footerData['menu'])): ?>
                    <?php foreach ($footerData['menu'] as $item): ?>
                        <?php 
                        $itemUrl = $item['url'] === '/' ? '' : $item['url'];
                        $fullUrl = '/' . $lang . $itemUrl;
                        ?>
                        <li class="footer-menu-item">
                            <a href="<?php echo $fullUrl; ?>" class="footer-link">
                                <?php echo htmlspecialchars($item['title']); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
        </nav>

        <!-- Social Media -->
        <div class="footer-social">
            <?php if (isset($footerData['followUs'])): ?>
                <h3 class="footer-title"><?php echo htmlspecialchars($footerData['followUs']); ?></h3>
            <?php endif; ?>
            <div class="social-links">
                <?php if (isset($footerData['socialMedia'])): ?>
                    <?php foreach ($footerData['socialMedia'] as $social): ?>
                        <a href="<?php echo htmlspecialchars($social['url']); ?>" 
                           target="_blank" 
                           rel="noopener noreferrer" 
                           class="social-link"
                           title="<?php echo htmlspecialchars($social['title']); ?>">
                            <img src="/media/image/icon/<?php echo $social['name']; ?>.png" 
                                 alt="<?php echo htmlspecialchars($social['title']); ?>" 
                                 class="social-icon invert">
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Footer Bottom -->
    <div class="footer-bottom">
        <div class="footer-powered">
            <p>Powered by</p>
            <img src="/media/image/aienova.png" alt="AIEE NOVA Logo" class="powered-logo invert">
        </div>
        <?php if (isset($footerData['copyright'])): ?>
            <p class="footer-copyright"><?php echo htmlspecialchars($footerData['copyright']); ?></p>
        <?php endif; ?>
    </div>
</footer>

<?php include "GDPR.php"; ?>

<script src="/script/mobile-menu.js"></script>
<script src="/script/languageSwitcher.js"></script>
<script src="/script/search-engine.js"></script>
<script src="/script/editor.js"></script>
<script src="/script/viewer.js"></script>
<script src="/script/insertTestData.js"></script>
<script src="/script/selected-job.js"></script>
<script src="/script/insertTestDataJob.js"></script>
<script src="/script/GDPR.js"></script>