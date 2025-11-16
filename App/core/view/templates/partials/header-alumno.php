<?php
// view/templates/partials/header-alumno.php (MODIFICADO)
?>
<div id="nav">
    <nav class="menu-principal">
        <ul id="navParent" class="menu-lista">
            <li class="menu-item">
                <a href="?menu=ofertas-alumno" class="menu-enlace">Ver ofertas</a
            </li>
            <li class="menu-item">
                <a href="?menu=misSolicitudes-alumno" class="menu-enlace">Mis solicitudes</a>
            </li>
            <li class="menu-item">
                <a href="?menu=alumno-dashboard" class="menu-enlace">Dashboard</a>
            </li>
        </ul>
    </nav>
</div>
<div id="user-area-alumno" class="user-area user-menu-dropdown">
    <div class="pfp-username">
        <span class="username-display" id="user-area-username"></span>
        <span id="menuToggle" class="pfp-container">
            <img src="" alt="Alumno PFP" class="profile-photo" id="alumnoPfp">
        </span>
    </div>

    <div class="dropdown-content"> 
        <a id="verPerfil" href="#">Ver Perfil</a>
        <a id="cerrarSesion" href="#">Cerrar Sesión</a> 
    </div>
</div>