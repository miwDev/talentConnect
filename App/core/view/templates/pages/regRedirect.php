<?php
$this->layout('layout/layout', [
    'title' => 'Talent Connect - Registro',
    'navItems' => [
        '<a href="?menu=home" class="menu-enlace">HOME</a>'
    ]
]);
?>

<?php $this->start('css') ?>
<link rel="stylesheet" type="text/css" href="/public/assets/css/authorization.css" />
<?php $this->stop() ?>

<?php $this->start('pageContent') ?>
<section id="registration-type-section">
    <div class="login-container register-redirect-container">

        <div class="registration-header">
            <h2 class="h2-title">Registro de <span class="h2-title-alter">Usuarios</span></h2>
            <p class="registration-subtitle">Selecciona el tipo de usuario con el que deseas crear tu cuenta:</p>
        </div>

        <div class="registration-choices">
            <a href="?menu=regAlumno" class="choice-btn login-btn">Registrarme como Alumno</a>
            <a href="?menu=regEmpresa" class="choice-btn login-btn">Registrarme como Empresa</a>
        </div>

        <p class="back-link">
            <a href="?menu=home" class="forgot-password-link">Volver al inicio</a>
        </p>
    </div>
</section>
<?php $this->stop() ?>