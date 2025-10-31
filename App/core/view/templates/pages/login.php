<?php
$this->layout('layout/layout', [
    'title' => 'Talent Connect - Iniciar Sesión',
    'navItems' => [
        '<a href="?menu=home" class="menu-enlace">HOME</a>'
    ]
]);
?>

<?php $this->start('pageContent') ?>
<section id="login-section" class="main-section">
    <div class="login-center-box">
        <div class="login-box">
            <h2 class="h2-login">Iniciar Sesión</h2>

            <!-- Hueco para errores generales del formulario -->
            <div id="loginErrors" class="error-message"></div>

            <form action="" method="post" class="login-form">

                <div class="form-group">
                    <label for="user">Usuario:</label>
                    <input type="text" name="user" id="user" required>
                    <!-- Hueco para error específico del usuario -->
                    <div id="userError" class="field-error"></div>
                </div>

                <div class="form-group">
                    <label for="pass">Contraseña:</label>
                    <input type="password" name="pass" id="pass" required>
                    <!-- Hueco para error específico de la contraseña -->
                    <div id="passError" class="field-error"></div>
                </div>

                <div class="form-remember">
                    <input type="checkbox" name="remember" id="remember">
                    <label for="remember">Recordarme</label>
                </div>

                <input type="submit" name="signIn" id="signIn" value="ACCEDER" class="btn-primary">

                <a href="#" class="register-link-login">¿Olvidaste tu contraseña?</a>
            </form>
        </div>
    </div>
</section>
<?php $this->stop() ?>