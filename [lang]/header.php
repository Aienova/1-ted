<header>
    <nav class="navbar">
        <div class="nav-container">
            <!-- Logo -->
            <a href="/<?php echo $lang; ?>" class="logo">
                <img class="invert" src="/media/image/logo.png" alt="1TED Logo">
            </a>

            <!-- Burger Menu -->
            <button class="burger-menu" id="burgerMenu" aria-label="Menu">
                <span></span>
                <span></span>
                <span></span>
            </button>

            <!-- Navigation Menu -->
            <ul class="nav-menu" id="navMenu">
                <?php foreach ($menuItems as $item): ?>
                    <?php 
                    // Construire l'URL correctement
                    $itemUrl = $item['url'] === '/' ? '' : $item['url'];
                    $fullUrl =  '/' . $lang . $itemUrl;
                    ?>
                    <li class="nav-item <?php echo !empty($item['submenu']) ? 'has-submenu' : ''; ?>">
                        <?php if (!empty($item['submenu'])): ?>
                            <a href="<?php echo $fullUrl; ?>" class="nav-link">
                                <?php echo htmlspecialchars($item['title']); ?>
                                <span class="dropdown-icon">▼</span>
                            </a>
                            <ul class="submenu">
                                <?php foreach ($item['submenu'] as $subitem): ?>
                                    <?php 
                                    $subitemUrl = $subitem['url'] === '/' ? '' : $subitem['url'];
                                    $fullSubUrl =  '/' . $lang . $subitemUrl;
                                    ?>
                                    <li class="submenu-item">
                                        <a href="<?php echo $fullSubUrl; ?>" class="submenu-link">
                                            <?php echo htmlspecialchars($subitem['title']); ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <a href="<?php echo $fullUrl; ?>" class="nav-link">
                                <?php echo htmlspecialchars($item['title']); ?>
                            </a>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>

            <!-- Language Switcher Hiden for now 
            <div class="language-switcher">
                <?php 
                // Trouver la langue actuelle
                $currentLanguage = null;
                foreach ($languages as $language) {
                    if ($language['code'] === $lang) {
                        $currentLanguage = $language;
                        break;
                    }
                }
                ?>
                <button class="current-language" id="langButton" aria-label="Change language">
                    <img src="/media/image/icon/flag/<?php echo $currentLanguage ? $currentLanguage['flag'] : 'uk.jpg'; ?>" alt="<?php echo $currentLanguage ? $currentLanguage['title'] : 'English'; ?>">
                </button>
                <ul class="language-dropdown" id="langDropdown">
                    <?php foreach ($languages as $language): ?>
                        <?php if ($language['code'] !== $lang): ?>
                            <li class="language-item">
                                <a href="#" class="language-link" data-lang="<?php echo $language['code']; ?>">
                                    <img src="/media/image/icon/flag/<?php echo $language['flag']; ?>" alt="<?php echo $language['title']; ?>">
                                    <span><?php echo $language['title']; ?></span>
                                </a>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
            </div> -->

            <!-- Account Button (apparaît si l'utilisateur est connecté) -->
            <?php include 'accountButton.php'; ?>
        </div>
    </nav>
</header>