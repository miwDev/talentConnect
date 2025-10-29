<?php
$this->layout('layout/layout', [
    'title' => 'Talent Connect - Inicio',
    'navItems' => [
        '<a href="#" class="menu-enlace">HOME</a>',
    ]
]);
?>

<?php $this->start('css') ?>
<link rel="stylesheet" type="text/css" href="/assets/css/listaAlumnos.css" />
<?php $this->stop() ?>

<?php $this->start('js') ?>
<script src="/assets/js/listaAlumnoLogic.js"></script>
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
        <input type="button" id="add" value="Añadir alumno">
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Email</th>
                    <th class="actions-column hidden">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <!-- Las filas se generarán dinámicamente con JavaScript -->
            </tbody>
        </table>
    </div>
</div>
<?php $this->stop() ?>