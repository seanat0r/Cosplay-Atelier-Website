<?php $currentPage = basename($_SERVER['PHP_SELF']); ?>
<header class="main-header">
    <div class="logo-container">
        <!-- Logo-->
        <img src="/assets/img/logo.svg" alt="Cosplay-Atelier Logo" class="logo-icon">
    </div>

    <!-- Mobile Burger Menu -->
    <button class="burger-menu-toggle" aria-label="Menü öffnen">
        <span class="burger-line"></span>
        <span class="burger-line"></span>
        <span class="burger-line"></span>
    </button>

    <nav class="main-nav">
        <button class="close-menu-btn" aria-label="Menü schliessen">X</button>
        <ul>
            <li><a href="/index.php" <?php echo $currentPage === 'index.php' ? 'class="active"' : ''; ?>>Home</a></li>
            <li><a href="/about_us.php" <?php echo $currentPage === 'about_us.php' ? 'class="active"' : ''; ?>>Über uns</a></li>
            <li><a href="/news.php" <?php echo $currentPage === 'news.php' ? 'class="active"' : ''; ?>>News</a></li>
            <li><a href="/photogalerie.php" <?php echo $currentPage === 'photogalerie.php' ? 'class="active"' : ''; ?>>Fotogalerie</a></li>
            <li><a href="/contacts.php" <?php echo $currentPage === 'contacts.php' ? 'class="active"' : ''; ?>>Kontakt</a></li>
        </ul>
    </nav>
</header>