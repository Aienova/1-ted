<menu class="menu-user-space">

       <!-- Logo -->
        <div class="user-space-logo">
            <a href="<?php echo $baseUrl; ?>/<?php echo $lang; ?>">
                <img src="<?php echo $baseUrl; ?>/media/image/logo.png" alt="1-TED Logo">
            </a>
        </div>

        <div class="user-info">
        <div class="user-photo">
            <img src="<?php echo $baseUrl; ?>/media/image/icon/avatar/default.png" alt="User Avatar">
        </div>
        <div class="user-text-info">
            <span><?php echo htmlspecialchars($userName); ?> : <?php echo htmlspecialchars($userRole); ?></span><span>Credits : 0 </span>
        </div>
        </div>

        <!-- Menu latéral -->
        <aside class="user-space-menu">
            <nav>
                <ul>
                    <?php foreach ($menuPages as $menuPage): ?>
                        <?php 
                        $isActive = ($menuPage['name'] === $page) ? 'active' : '';
                        $menuLink = $baseUrl . '/' . $lang . $menuPage['link'];
                        ?>
                        <li class="<?php echo $isActive; ?>">
                            <a href="<?php echo $menuLink; ?>">
                                <?php echo htmlspecialchars($menuPage['label']); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                    <li class="menu-logout">
                        <a href="#" id="logoutBtn">Déconnexion</a>
                    </li>
                </ul>
            </nav>
        </aside>
</menu>