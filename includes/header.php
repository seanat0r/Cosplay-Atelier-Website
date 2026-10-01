<?php $currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
?>
<header class="main-header">
    <div class="logo-container">
        <!-- Logo-->
        <img src="/assets/img/logo.svg" alt="Cosplay-Atelier Logo" class="logo-icon">
    </div>

    <!-- Mobile Burger Menu -->
    <button class="burger-menu-toggle" type="button" aria-label="Menü öffnen" aria-controls="main-navigation" aria-expanded="false">
        <span class="burger-line"></span>
        <span class="burger-line"></span>
        <span class="burger-line"></span>
    </button>

    <nav class="main-nav" id="main-navigation" aria-label="Hauptnavigation">
        <ul>
            <li><a href="/" <?php echo $currentPath === '/' ? 'class="active" aria-current="page"' : ''; ?>>Home</a></li>
            <li><a href="/about" <?php echo $currentPath === '/about' ? 'class="active" aria-current="page"' : ''; ?>>Über uns</a></li>
            <li><a href="/chibicon" <?php echo $currentPath === '/chibicon' ? 'class="active" aria-current="page"' : ''; ?>>Chibicon</a></li>
            <li><a href="/news" <?php echo $currentPath === '/news' ? 'class="active" aria-current="page"' : ''; ?>>News</a></li>
            <li><a href="/photogalerie" <?php echo $currentPath === '/photogalerie' ? 'class="active" aria-current="page"' : ''; ?>>Fotogalerie</a></li>
            <li><a href="/contacts" <?php echo $currentPath === '/contacts' ? 'class="active" aria-current="page"' : ''; ?>>Kontakt</a></li>
        </ul>
    </nav>
</header>
