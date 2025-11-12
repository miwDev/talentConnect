<?php
$currentMenu = $_GET['menu'] ?? 'home';
?>

<aside id="admin-sidebar">
    <div class="sidebar-header">
        <h3>Panel de Administración</h3>
    </div>
    <nav class="sidebar-nav">
        <ul>
            <li class="nav-item <?= ($currentMenu === 'admin-dashboard') ? 'active' : '' ?>">
                <a href="?menu=admin-dashboard">
                    Estadisticas
                </a>
            </li>
            <li class="nav-item <?= ($currentMenu === 'admin-alumnos') ? 'active' : '' ?>">
                <a href="?menu=admin-alumnos">
                    Gestión de Alumnos
                </a>
            </li>
            <li class="nav-item <?= ($currentMenu === 'admin-empresas' || $currentMenu === 'admin-empresas-add' || $currentMenu === 'admin-empresas-edit' || $currentMenu === 'admin-empresas-ver') ? 'active' : '' ?>">
                <a href="?menu=admin-empresas">
                    Gestión de Empresas
                </a>
            </li>
        </ul>
    </nav>
</aside>