<?php
$this->layout('layout/layout', [
    'title' => 'Talent Connect - Inicio',
    'navItems' => [
        '<a href="#" class="menu-enlace">HOME</a>',
    ]
]);
?>

<?php $this->start('css') ?>
<link rel="stylesheet" type="text/css" href="/assets/css/listaAlumnos.css" />"
<?php $this->stop() ?>

<?php $this->start('js') ?>
<script src="/assets/js/listaAlumnoLogic.js"></script>
<?php $this->stop() ?>

<?php $this->start('pageContent') ?>
<div class="list-container">
    <h1>tabla de alumnos</h1>
    <div class="table-filters">
        <label for="">Filtro</label>
        <input type="text" name="filtro" id="filtro">
        <label for="">Buscar</label>
        <input type="text" name="buscar" id="buscar">
        <input type="button" id="borrar" value="borrar">
        <input type="button" id="add" value="add">
    </div>
    <div class="table-container">
        <table>
            <thead>
                <th>
                    id
                </th>
                <th>
                    nombre
                </th>
                <th>
                    apellido
                </th>
                <th>
                    email
                </th>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
</div>
<?php $this->stop() ?>