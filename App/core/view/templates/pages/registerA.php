<?php
$this->layout('layout/layout', [
    'title' => 'Talent Connect - Registro Alumno',
    'navItems' => [
        '<a href="?menu=home" class="menu-enlace">HOME</a>'
    ]
]);
?>

<?php $this->start('css') ?>
<link rel="stylesheet" type="text/css" href="/public/assets/css/authorization.css" />
<?php $this->stop() ?>

<?php $this->start('js') ?>
<?php $this->stop() ?>

<?php $this->start('pageContent') ?>
<section id="register-alumno-section" class="main-section">
    <div class="login-container">

        <h2 class="h2-title">Registro de <span class="h2-title-alter">Alumno</span></h2>

        <div class="login-contents register-contents">

            <div class="login-form-container register-form-container">
                <form action="/register-alumno" method="POST" enctype="multipart/form-data">

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
                    </div>

                    <div class="form-row">
                        <div class="form-group half-width">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" required>
                            <span id="error-email" class="input-error-message"></span>
                        </div>
                        <div class="form-group half-width">
                            <label for="password">Contraseña</label>
                            <input type="password" id="password" name="password" required>
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

                    <div class="form-row education-row">
                        <div class="form-group half-width">
                            <label for="familia">Familia Profesional</label>
                            <select id="familia" name="familia" class="form-select">
                                <option value="">Selecciona una familia</option>
                                <option value="inf">Informática y Comunicaciones (Mock)</option>
                                <option value="adm">Administración y Gestión (Mock)</option>
                            </select>
                            <span id="error-familia" class="input-error-message"></span>
                        </div>

                        <div class="form-group half-width" id="ciclo-group" style="display: none;">
                            <label for="ciclo">Ciclo Formativo</label>
                            <select id="ciclo" name="ciclo" class="form-select">
                                <option value="">Selecciona un ciclo</option>
                                <option value="dam">Desarrollo de Aplicaciones Multiplataforma (Mock)</option>
                                <option value="daw">Desarrollo de Aplicaciones Web (Mock)</option>
                            </select>
                            <span id="error-ciclo" class="input-error-message"></span>
                        </div>
                    </div>

                    <div class="form-actions">
                        <div id="login-error-display" class="error-message"></div>
                        <button type="submit" class="login-btn">Finalizar Registro</button>
                    </div>
                </form>
            </div>

            <div class="login-info-card register-avatar-card">
                <h3>Tu Imagen y CV</h3>
                <p class="card-subtitle">
                    Sube tu foto de perfil y tu currículum vitae.
                </p>

                <div class="avatar-upload-area">
                    <img src="/public/assets/images/genericAvatar.svg" alt="Avatar genérico" class="generic-avatar">
                </div>

                <div class="avatar-buttons">
                    <button type="button" class="login-btn avatar-btn upload-btn">Subir Foto</button>
                    <button type="button" class="login-btn avatar-btn camera-btn">Acceder a Cámara</button>
                </div>

                <div class="avatar-buttons cv-upload">
                    <button type="button" class="login-btn avatar-btn cv-btn">Subir CV (PDF/DOC)</button>
                </div>


                <p class="back-to-login">
                    ¿Ya tienes cuenta? <a href="?menu=login" class="forgot-password-link">Iniciar Sesión</a>
                </p>
            </div>

        </div>
    </div>
</section>
<?php $this->stop() ?>