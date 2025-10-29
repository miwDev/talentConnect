<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->e($title) ?></title>
    <link rel="stylesheet" href="https://geistfont.vercel.app/geist.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/style.css" />

    <?= $this->section('css') ?>
    <?= $this->section('js') ?>
</head>

<body>
    <header>
        <div class="header-banner" id="hBanner">
            <div id="logo">
                <img id="logo" src="/assets/images/logoDark.png" alt="logo_placeholder" width="120" height="120">
                <p>TALENT </br> CONNECT</p>
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
                <div id="userPicture">
                    <img class="avatar" src="/assets/images/genericAvatar.svg" alt="avatar" width="75" height="75">
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
                <img class="" src="/assets/images/footerLogo.png" alt="logo_placeholder" width="220" height="220">
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