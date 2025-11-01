<?php
$this->layout('layout/layout', [
    'title' => 'Talent Connect - Registro',
    'navItems' => [
        '<a href="?menu=home" class="menu-enlace">HOME</a>'
    ]
]);
?>

<?php $this->start('pageContent') ?>
<section>
    <h1>¿Cómo quieres registrarte?</h1>
    <p>Selecciona el tipo de usuario con el que deseas crear tu cuenta.</p>

    <div>
        <a href="?menu=regAlumno">Registrarme como Alumno</a>
        <a href="?menu=regEmpresa">Registrarme como Empresa</a>
    </div>

    <p>
        <a href="?menu=home">Volver al inicio</a>
    </p>
</section>
<?php $this->stop() ?>