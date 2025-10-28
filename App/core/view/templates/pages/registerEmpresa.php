<?php 
    $this->layout('layout/layout', [
        'title' => 'Talent Connect - Registro Empresa',
        'navItems' => [
            '<a href="/" class="menu-enlace">HOME</a>'
        ]
    ]);
?>

<?php $this->start('pageContent') ?>
    <section id="register-empresa-section" class="main-section">
        <div class="login-center-box"> 
            <div class="login-box"> 
                <h2 class="h2-login">Registro Empresa</h2>
                
                <!-- Hueco para errores generales del formulario -->
                <div id="registerEmpresaErrors" class="error-message"></div>
                
                <form action="" method="post" class="login-form" enctype="multipart/form-data">
                    
                    <!-- Nombre de la Empresa -->
                    <div class="form-group">
                        <label for="nombreEmpresa">Nombre de la Empresa:</label>
                        <input type="text" name="nombreEmpresa" id="nombreEmpresa" required>
                        <!-- Hueco para error específico del nombre -->
                        <div id="nombreEmpresaError" class="field-error"></div>
                    </div>
                    
                    <!-- Teléfono de la Empresa -->
                    <div class="form-group">
                        <label for="telefonoEmpresa">Teléfono de la Empresa:</label>
                        <input type="text" name="telefonoEmpresa" id="telefonoEmpresa" required>
                        <!-- Hueco para error específico del teléfono -->
                        <div id="telefonoEmpresaError" class="field-error"></div>
                    </div>
                    
                    <!-- Dirección de la Empresa -->
                    <div class="form-group">
                        <label for="direccionEmpresa">Dirección de la Empresa:</label>
                        <input type="text" name="direccionEmpresa" id="direccionEmpresa" required>
                        <!-- Hueco para error específico de la dirección -->
                        <div id="direccionEmpresaError" class="field-error"></div>
                    </div>
                    
                    <!-- Información de Contacto -->
                    <div class="form-group">
                        <label for="personaContacto">Persona de Contacto:</label>
                        <input type="text" name="personaContacto" id="personaContacto" required>
                        <!-- Hueco para error específico de la persona de contacto -->
                        <div id="personaContactoError" class="field-error"></div>
                    </div>
                    
                    <div class="form-group">
                        <label for="telefonoContacto">Teléfono de Contacto:</label>
                        <input type="text" name="telefonoContacto" id="telefonoContacto" required>
                        <!-- Hueco para error específico del teléfono de contacto -->
                        <div id="telefonoContactoError" class="field-error"></div>
                    </div>
                    
                    <!-- Logo -->
                    <div class="form-group">
                        <label for="logo">Logo de la Empresa:</label>
                        <div class="logo-preview">
                            <img class="logo-preview-img" src="/assets/images/logoDark.png" alt="Vista previa del logo" id="logoPreview">
                        </div>
                        <div class="logo-options">
                            <div class="logo-option">
                                <input type="file" name="logo" id="logo" accept="image/*" style="display: none;">
                                <button type="button" class="btn-secondary" onclick="document.getElementById('logo').click()">
                                    Añadir Logo
                                </button>
                            </div>
                        </div>
                        <!-- Hueco para error específico del logo -->
                        <div id="logoError" class="field-error"></div>
                    </div>
                    
                    <input type="submit" name="registerEmpresa" id="registerEmpresa" value="REGISTRAR EMPRESA" class="btn-primary">
                    
                </form>
            </div>
        </div>
    </section>
<?php $this->stop() ?>