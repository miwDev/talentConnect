<?php
$this->layout('layout/layout', [
    'title' => 'Talent Connect - Inicio',
    'navItems' => [
        '<a href="?menu=home" class="menu-enlace">HOME</a>',
    ]
]);
?>

<?php $this->start('css') ?>
<link rel="stylesheet" type="text/css" href="/public/assets/css/listadosAdmin.css" />
<?php $this->stop() ?>

<?php $this->start('js') ?>
<script src="/public/assets/js/listaAlumnoLogic.js"></script>
<script src="/public/assets/js/elements.js" defer></script>
<script src="/public/assets/js/addons.js" defer></script>
<?php $this->stop() ?>

<?php $this->start('pageContent') ?>
<div class="list-container">
    <div class="header-section">
        <h1>Tabla de alumnos</h1>
        <div class="search-section">
            <input type="text" name="buscar" id="buscar" placeholder="Buscar alumno...">
            <button type="button" id="buscar-btn" class="search-btn">Buscar</button>
        </div>
    </div>

    <div class="add-button-container">
        <input type="button" id="add" value="AÑADIR ALUMNO">
    </div>

    <div class="add-button-container">
        <input type="button" id="cargaMasiva" value="CARGA MASIVA">
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID <span class="static-arrow"></span></th>
                    <th>Nombre <span class="static-arrow"></span></th>
                    <th>Apellido <span class="static-arrow"></span></th>
                    <th>Email <span class="static-arrow"></span></th>
                    <th class="actions-column">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <!-- Las filas se generarán dinámicamente con JavaScript -->
            </tbody>
        </table>
    </div>
</div>
<?php $this->stop() ?>