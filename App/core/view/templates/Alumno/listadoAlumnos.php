<?php
$this->layout('layout/adminLayout', [
    'title' => 'Talent Connect - Gestión de Alumnos'
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
        <h1>Gestión de Alumnos</h1>
        
        <div class="admin-toolbar">
            <div class="toolbar-group-left">
                <input type="text" name="buscar" id="buscar" class="toolbar-input" placeholder="Buscar por nombre, email...">
            </div>

            <div class="toolbar-group-right">
                <input type="button" id="cargaMasiva" class="toolbar-btn btn-secondary" value="CARGA MASIVA">
                <input type="button" id="add" class="toolbar-btn btn-primary" value="+ AÑADIR ALUMNO">
            </div>
        </div>
    </div>
    <div class="table-container">
        <table id="tablaMain">
            <thead>
                <tr>
                    <th class="numero">ID <span id="ID" class="static-arrow"></span></th>
                    <th class="texto">Nombre <span id="NOMBRE" class="static-arrow"></span></th>
                    <th class="texto">Apellido <span id="APELLIDO" class="static-arrow"></span></th>
                    <th class="texto">Email <span id="EMAIL" class="static-arrow"></span></th>
                    <th class="actions-column">Acciones</th>
                </tr>
            </thead>
            <tbody>
                </tbody>
        </table>
    </div>
</div>
<?php $this->stop() ?>