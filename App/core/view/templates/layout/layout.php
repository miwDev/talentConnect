<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->e($title) ?></title>
    <link rel="icon" type="image/png" href="/public/assets/images/logoDark.png" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville:wght@400;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="/public/assets/css/style.css" />

    <?= $this->section('css') ?>
    <?= $this->section('js') ?>
</head>

<body>
    <header>
        <div class="header-banner" id="hBanner">
            <div id="logo">
                <a href="?menu=home">
                    <img id="logo" src="/public/assets/images/logoDark.svg" alt="logo_placeholder" width="120" height="120">
                    <p>TALENT </br> CONNECT</p>
                </a>
            </div>
            <div id="nav-and-user">
                <div id="nav">
                    <nav class="menu-principal">
                        <ul id="navParent" class="menu-lista">
                            <?php foreach ($navItems as $item): ?>
                                <li class="menu-item">
                                    <?php echo $item; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </header>

    <main>
        <?php echo $this->section('pageContent'); ?>
    </main>

    <footer>
        <div class="footer-container">
            <div class="footer-logo">
                <img class="footer-logo" src="/public/assets/images/logoClear.svg" width="200px" height="200px">
            </div>
            <div class="footer-items">
                <div class="footer-social">
                    <div class="footer-title">
                        Social Media
                    </div>

                    <ul class="social-links">
                        <li>
                            <a href="https://www.instagram.com" target="_blank" class="social-link">Instagram</a>
                        </li>
                        <li>
                            <a href="https://www.twitter.com" target="_blank" class="social-link">X</a>
                        </li>
                        <li>
                            <a href="https://www.facebook.com" target="_blank" class="social-link">Facebook</a>
                        </li>
                    </ul>
                </div>
                <div class="footer-about">
                    <div class="footer-title">
                        About
                    </div>

                    <ul class="about-links">
                        <li>
                            <a href="https://www.instagram.com" target="_blank" class="about-link">Contact</a>
                        </li>
                        <li>
                            <a href="https://www.twitter.com" target="_blank" class="about-link">Chat</a>
                        </li>
                        <li>
                            <a href="https://www.facebook.com" target="_blank" class="about-link">Email</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div>
            <p>
                © 2025 msd.dev. All Rights Reserved.
            </p>
        </div>
    </footer>
</body>

</html>