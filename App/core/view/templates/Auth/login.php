<?php
use App\core\data\Authorization;

// 1. Obtener datos de la plantilla
// El AuthController ahora nos pasa 'errors' y el valor de 'username'
$errors = $this->data['errors'] ?? [];
$username_value = $this->data['username'] ?? '';

$this->layout('layout/layout', [
    'title' => 'Talent Connect - Iniciar Sesión',
    'role' => $this->data['role'] ?? 'ROLE_GUEST'
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
                        <!-- 2. Mantener el valor y asegurar la seguridad -->
                        <input 
                            type="text" 
                            id="username" 
                            name="username" 
                            value="<?= htmlspecialchars($username_value) ?>" 
                            required
                        >
                        <!-- 3. Pintar error de campo username -->
                        <span id="error-username" class="input-error-message">
                            <!-- Si existe $errors['username'], lo pintamos, sino queda vacío. -->
                            <?= $errors['username'] ?? '' ?>
                        </span>
                    </div>

                    <div class="form-group">
                        <label for="password">Contraseña</label>
                        <div class="input-wrapper">
                            <input type="password" id="password" name="password" required>
                            <span id="toggle-password">
                            </span>
                        </div>
                        <span id="error-password" class="input-error-message">
                            <?= $errors['password'] ?? '' ?>
                        </span>
                    </div>

                    <div id="login-error-display" class="error-message">
                         <?= $errors['db_auth'] ?? '' ?>
                    </div>

                    <div class="form-actions">
                        <button id="loginSubmit" type="submit" class="login-btn">Entrar</button>
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