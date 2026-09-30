<?php $currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
?>
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
            <li><a href="/" <?php echo $currentPath === '/' ? 'class="active"' : ''; ?>>Home</a></li>
            <li><a href="/about" <?php echo $currentPath === '/about' ? 'class="active"' : ''; ?>>Über uns</a></li>
            <li><a href="/news" <?php echo $currentPath === '/news' ? 'class="active"' : ''; ?>>News</a></li>
            <li><a href="/photogalerie" <?php echo $currentPath === '/photogalerie' ? 'class="active"' : ''; ?>>Fotogalerie</a></li>
            <li><a href="/contacts" <?php echo $currentPath === '/contacts' ? 'class="active"' : ''; ?>>Kontakt</a></li>
        </ul>
    </nav>
</header>
