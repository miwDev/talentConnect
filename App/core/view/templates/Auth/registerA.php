<?php
$this->layout('layout/layout', [
    'title' => 'Talent Connect - Registro Alumno',
    'role' => $this->data['role'] ?? 'ROLE_GUEST'
]);
?>

<?php $this->start('css') ?>
<link rel="stylesheet" type="text/css" href="/public/assets/css/authorization.css" />
<?php $this->stop() ?>

<?php $this->start('js') ?>
<script src="/public/assets/js/addons.js" defer></script>
<script src="/public/assets/js/FormHandler.js" defer></script>
<script src="/public/assets/js/elements.js" defer></script>
<script src="/public/assets/js/authorization.js" defer></script>
<?php $this->stop() ?>

<?php $this->start('pageContent') ?>
<section id="register-alumno-section" class="main-section">
    <div class="login-container">

        <h2 class="h2-title">Registro de <span class="h2-title-alter">Alumno</span></h2>

        <div class="login-contents register-contents">

            <div class="login-form-container register-form-container">
                <form action="?menu=alumno-dashboard" method="POST" id="formulario" enctype="multipart/form-data">

                    <div class="form-row">
                        <div class="form-group half-width">
                            <label for="nombre">Nombre</label>
                            <input type="text" id="nombre" name="nombre" required>
                            <span id="error-nombre" class="input-error-message"></span>
                        </div>
                        <div class="form-group half-width">
                            <label for="apellido">Apellido</label>
                            <input type="text" id="apellido" name="apellido" required>
                            <span id="error-apellido" class="input-error-message"></span>
                        </div>
                        <div class="form-group half-width">
                            <label for="nombre">DNI</label>
                            <input type="text" id="dni" name="dni" required>
                            <span id="error-dni" class="input-error-message"></span>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group half-width">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" required>
                            <span id="error-email" class="input-error-message"></span>
                        </div>
                        <div class="form-group half-width">
                            <label for="password">Contraseña</label>
                            <div class="input-wrapper">
                                <input type="password" id="password" name="password" required placeholder="Mínimo 8 caracteres">
                                <span id="toggle-password">
                                </span>
                            </div>
                            <span id="error-password" class="input-error-message"></span>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group half-width">
                            <label for="telefono">Teléfono</label>
                            <input type="tel" id="telefono" name="telefono" required>
                            <span id="error-telefono" class="input-error-message"></span>
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

                    <div class="form-row education-colum" id="divEducacion">
                        <div class="form-group half-width">
                            <label for="familia">Familia Profesional</label>
                            <select id="familia" name="familia" class="form-select">
                            </select>
                            <span id="error-familia" class="input-error-message"></span>
                        </div>
                    </div>

                    <div class="form-actions">
                        <div id="login-error-display" class="error-message"></div>
                        <button type="submit" id="envioRegistro" class="login-btn">Finalizar Registro</button>
                    </div>

                    <input type="hidden" id="hiddenFotoPerfil" name="fotoPerfil">


                </form>
            </div>

            <div class="login-info-card register-avatar-card">
                <h3>Tu Imagen y CV</h3>
                <p class="card-subtitle">
                    Sube tu foto de perfil y tu currículum vitae.
                </p>

                <div class="avatar-upload-area">
                    <img src="/public/assets/images/genericAvatar.svg" alt="Avatar genérico" class="generic-avatar" id="avatarId">
                </div>

                <div class="avatar-buttons">
                    <button type="button" class="login-btn avatar-btn upload-btn" id="uploadPicture">Subir Foto</button>
                    <button type="button" class="login-btn avatar-btn camera-btn" id="cameraModal">Acceder a Cámara</button>
                </div>

                <div class="avatar-buttons cv-upload">
                    <button type="button" class="login-btn avatar-btn cv-btn" id="uploadCV">Subir CV (PDF)</button>
                </div>

                <p class="back-to-login">
                    ¿Ya tienes cuenta? <a href="?menu=login" class="forgot-password-link">Iniciar Sesión</a>
                </p>
            </div>

        </div>
    </div>
</section>
<?php $this->stop() ?>