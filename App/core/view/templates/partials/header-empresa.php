<div id="nav">
    <nav class="menu-principal">
        <ul id="navParent" class="menu-lista">
            <li class="menu-item">
                <a href="?menu=mis-ofertas" class="menu-enlace">Mis ofertas</a>
            </li>
            <li class="menu-item">
                <a href="?menu=crear-oferta" class="menu-enlace">Crear oferta</a>
            </li>
        </ul>
    </nav>
</div>
<div id="user-area-empresa" class="user-area user-menu-dropdown">
    <span class="username-display"><?= $this->e($username) ?></span>
    <span id="menuToggle" class="pfp-container">
        <img src="<?= $this->e($pfpPath) ?>" alt="Logo Empresa" class="profile-photo" id="empresaLogo">
    </span>
</div>