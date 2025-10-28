<?php 
    $this->layout('layout/layout', [
        'title' => 'Talent Connect - Registro Alumno',
        'navItems' => [
            '<a href="/" class="menu-enlace">HOME</a>'
        ]
    ]);
?>

<?php $this->start('pageContent') ?>
    <section id="register-section" class="main-section">
        <div class="login-center-box"> 
            <div class="login-box"> 
                <h2 class="h2-login">Registro Alumno</h2>
                
                <!-- Hueco para errores generales del formulario -->
                <div id="registerErrors" class="error-message"></div>
                
                <form action="" method="post" class="login-form" enctype="multipart/form-data">
                    
                    <!-- Nombre -->
                    <div class="form-group">
                        <label for="nombre">Nombre:</label>
                        <input type="text" name="nombre" id="nombre" required>
                        <!-- Hueco para error específico del nombre -->
                        <div id="nombreError" class="field-error"></div>
                    </div>
                    
                    <!-- Apellido -->
                    <div class="form-group">
                        <label for="apellido">Apellido:</label>
                        <input type="text" name="apellido" id="apellido" required>
                        <!-- Hueco para error específico del apellido -->
                        <div id="apellidoError" class="field-error"></div>
                    </div>
                    
                    <!-- Teléfono -->
                    <div class="form-group">
                        <label for="telefono">Teléfono:</label>
                        <input type="text" name="telefono" id="telefono" required>
                        <!-- Hueco para error específico del teléfono -->
                        <div id="telefonoError" class="field-error"></div>
                    </div>
                    
                    <!-- Dirección -->
                    <div class="form-group">
                        <label for="direccion">Dirección:</label>
                        <input type="text" name="direccion" id="direccion" required>
                        <!-- Hueco para error específico de la dirección -->
                        <div id="direccionError" class="field-error"></div>
                    </div>
                    
                    <!-- Foto -->
                    <div class="form-group">
                        <label for="foto">Foto:</label>
                        <div class="photo-preview">
                            <img class="photo-preview-img" src="/assets/images/genericAvatar.svg" alt="Vista previa" id="vistaPrevia">
                        </div>
                        <div class="photo-options">
                            <!-- Botón para subir archivo -->
                            <div class="photo-option">
                                <input type="file" name="foto" id="foto" accept="image/*" style="display: none;">
                                <button type="button" class="btn-secondary" onclick="document.getElementById('foto').click()">
                                    Añadir Archivo
                                </button>
                            </div>
                            
                            <!-- Botón para abrir cámara -->
                            <div class="photo-option">
                                <button type="button" class="btn-secondary" id="openCamera">
                                    Abrir Cámara
                                </button>
                            </div>
                        </div>
                        <!-- Hueco para error específico de la foto -->
                        <div id="fotoError" class="field-error"></div>
                    </div>
                    
                    <!-- CV -->
                    <div class="form-group">
                        <label for="cv">Curriculum Vitae (CV):</label>
                        <div class="cv-upload">
                            <input type="file" name="cv" id="cv" accept=".pdf,.doc,.docx" style="display: none;">
                            <button type="button" class="btn-secondary" onclick="document.getElementById('cv').click()">
                                Subir CV
                            </button>
                            <span class="cv-filename" id="cvFilename">No se ha seleccionado ningún archivo</span>
                        </div>
                        <!-- Hueco para error específico del CV -->
                        <div id="cvError" class="field-error"></div>
                    </div>
                    
                    <input type="submit" name="register" id="register" value="REGISTRAR" class="btn-primary">
                    
                </form>
            </div>
        </div>
    </section>
<?php $this->stop() ?>