<?php

use App\core\data\Authorization;

$this->layout('layout/layout', [
    'title' => 'Talent Connect - Iniciar Sesión',
    'navItems' => [
        '<a href="?menu=home" class="menu-enlace">HOME</a>'
    ]
]);
?>

<?php $this->start('css') ?>
<link rel="stylesheet" type="text/css" href="/public/assets/css/authorization.css" />
<?php $this->stop() ?>

<?php $this->start('js') ?>
<script src="/public/assets/js/login.js"></script>
<?php $this->stop() ?>

<?php $this->start('pageContent') ?>
<section id="login-section" class="main-section">
    <div class="login-container">
        <div>
            <h2 class="h2-title">Iniciar <span class="h2-title-alter">Sesión</span></h2>
        </div>
        <div class="login-contents">
            <div class="login-form-container">
                <form action="" method="POST">
                    <div class="form-group">
                        <label for="username">Email</label>
                        <input type="text" id="username" name="username" required>
                    </div>

                    <div class="form-group">
                        <label for="password">Contraseña</label>
                        <div class="input-wrapper">
                            <input type="password" id="password" name="password" required>
                            <span id="toggle-password">
                            </span>
                        </div>
                        <span id="error-password" class="input-error-message"></span>
                    </div>

                    <div id="login-error-display" class="error-message">
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="login-btn">Entrar</button>
                        <a href="#" class="forgot-password-link">¿Has olvidado tu contraseña?</a>
                    </div>
                </form>
            </div>
            <div class="login-info-card container">
                <h3>Accede a todas las ventajas</h3>

                <p class="card-subtitle">
                    Al crear tu cuenta y loguearte, desbloquearás herramientas esenciales para impulsar tu carrera profesional:
                </p>

                <ul class="advantages-list">
                    <li>
                        <p>Acceso a Ofertas Exclusivas</p>
                    </li>
                    <li>
                        <p>Gestión Centralizada de Solicitudes</p>
                    </li>
                    <li>
                        <p>Notificaciones en Tiempo Real</p>
                    </li>
                    <li>
                        <p>Perfiles Prioritarios</p>
                    </li>
                </ul>

                <a href="?menu=regRedirect" class="btn-register-link a-section3">¡Regístrate Ahora!</a>
            </div>
        </div>
    </div>
</section>
<?php $this->stop() ?>