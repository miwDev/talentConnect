<?php
$this->layout('layout/layout', [
    'title' => 'Talent Connect - Registro Empresa',
    'navItems' => [
        '<a href="?menu=home" class="menu-enlace">HOME</a>'
    ]
]);
?>

<?php $this->start('css') ?>
<link rel="stylesheet" type="text/css" href="/public/assets/css/authorization.css" />
<?php $this->stop() ?>

<?php $this->start('pageContent') ?>
<section id="register-empresa-section" class="main-section">
    <div class="login-container">

        <h2 class="h2-title">Registro de <span class="h2-title-alter">Empresa</span></h2>

        <div class="login-contents register-contents">

            <div class="login-form-container register-form-container">
                <form action="/register-empresa" method="POST" enctype="multipart/form-data">

                    <div class="form-row">
                        <div class="form-group half-width">
                            <label for="nombre_empresa">Nombre de la Empresa</label>
                            <input type="text" id="nombre_empresa" name="nombre_empresa" required>
                            <span id="error-nombre_empresa" class="input-error-message"></span>
                        </div>
                        <div class="form-group half-width">
                            <label for="email">Email Corporativo</label>
                            <input type="email" id="email" name="email" required>
                            <span id="error-email" class="input-error-message"></span>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group half-width">
                            <label for="password">Contraseña</label>
                            <input type="password" id="password" name="password" required>
                            <span id="error-password" class="input-error-message"></span>
                        </div>
                        <div class="form-group half-width">
                            <label for="contacto_persona">Persona de Contacto</label>
                            <input type="text" id="contacto_persona" name="contacto_persona" required>
                            <span id="error-contacto_persona" class="input-error-message"></span>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group half-width">
                            <label for="contacto_telefono">Teléfono de Contacto</label>
                            <input type="tel" id="contacto_telefono" name="contacto_telefono" required>
                            <span id="error-contacto_telefono" class="input-error-message"></span>
                        </div>
                        <div class="form-group half-width">
                            <label for="provincia">Provincia</label>
                            <input type="text" id="provincia" name="provincia" required>
                            <span id="error-provincia" class="input-error-message"></span>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group half-width">
                            <label for="localidad">Localidad</label>
                            <input type="text" id="localidad" name="localidad" required>
                            <span id="error-localidad" class="input-error-message"></span>
                        </div>
                        <div class="form-group half-width">
                            <label for="direccion">Dirección</label>
                            <input type="text" id="direccion" name="direccion" required>
                            <span id="error-direccion" class="input-error-message"></span>
                        </div>
                    </div>

                    <div class="form-actions">
                        <div id="login-error-display" class="error-message"></div>
                        <button type="submit" class="login-btn">Registrar Empresa</button>
                    </div>
                </form>
            </div>

            <div class="login-info-card register-avatar-card">
                <h3>Logo de la Empresa</h3>
                <p class="card-subtitle">
                    Sube el logo que aparecerá en tus ofertas.
                </p>

                <div class="avatar-upload-area">
                    <img src="/public/assets/images/genericCompany.svg" alt="Logo genérico" class="generic-avatar company-logo">
                </div>

                <div class="avatar-buttons">
                    <button type="button" class="login-btn avatar-btn upload-btn">Subir Logo (Elegir Archivo)</button>
                </div>

                <p class="back-to-login">
                    ¿Ya tienes cuenta? <a href="?menu=login" class="forgot-password-link">Iniciar Sesión</a>
                </p>
            </div>

        </div>
    </div>
</section>
<?php $this->stop() ?>