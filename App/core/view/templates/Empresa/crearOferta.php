<?php
// Obtener datos y errores pasados por el controlador
$errors = $this->data['errors'] ?? [];
$postData = $this->data['postData'] ?? [];
$ciclos = $this->data['ciclos'] ?? [];

// Función auxiliar para recuperar el valor del campo y limpiarlo para HTML
$getValue = function($key) use ($postData) {
    // Usamos $postData que viene limpio del controlador, no el $_POST original
    return htmlspecialchars($postData[$key] ?? '');
};

$this->layout('layout/layout', [
    'title' => 'Talent Connect - Creación de Oferta',
    'role' => $this->data['role'], 
]);
?>

<?php $this->start('pageContent') ?>
    <div class="list-container">
        <div class="header-section">
            <h1>Creación de Oferta</h1>
            <div id="general-error-display" class="error-message">
                <?= $errors['general_error'] ?? '' ?>
            </div>
        </div>
        
        <form action="" method="post" id="editForm">
            <div class="form-grid">

                <label for="titulo">Título</label>
                <div>
                    <input type="text" id="titulo" name="titulo" value="<?= $getValue('titulo') ?>">
                    <span id="error-titulo" class="input-error-message"><?= $errors['titulo'] ?? '' ?></span>
                </div>
                
                <label for="desc">Descripción</label>
                <div>
                    <!-- El textarea retiene el valor entre las etiquetas, no en el atributo value -->
                    <textarea id="desc" name="desc" placeholder="Máximo de 250 caracteres"><?= $getValue('desc') ?></textarea>
                    <!-- Usamos la clave 'descripcion' para el error de longitud/desc -->
                    <span id="error-desc" class="input-error-message"><?= $errors['descripcion'] ?? '' ?></span>
                </div>

                <label for="salario">Salario</label>
                <div>
                    <input type="text" id="salario" name="salario" value="<?= $getValue('salario') ?>" placeholder="Ej: 25.000€ brutos/año">
                    <span id="error-salario" class="input-error-message"><?= $errors['salario'] ?? '' ?></span>
                </div>

                <label for="fechaFin">Fecha de Finalización</label>
                <div>
                    <input type="text" id="fechaFin" name="fechaFin" value="<?= $getValue('fechaFin') ?>" placeholder="YYYY-MM-DD">
                    <span id="error-fechaFin" class="input-error-message"><?= $errors['fechaFin'] ?? '' ?></span>
                </div>

                <label class="select-group-label">CICLOS</label> 
                <div class="select-group">
                    <div>
                        <select name="select1" id="select1">
                            <option value="">Seleccione el ciclo 1</option>
                            <?php foreach ($ciclos as $ciclo): ?>
                            <option 
                                value="<?= $ciclo->id ?>"
                                <?= ($getValue('select1') == $ciclo->id) ? 'selected' : '' ?>
                            >
                                <?= $ciclo->nombre ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                        <span id="error-select1" class="input-error-message"><?= $errors['select1'] ?? '' ?></span>
                    </div>

                    <div>
                        <select name="select2" id="select2">
                            <option value="">Seleccione el ciclo 2 ( * )</option>
                            <?php foreach ($ciclos as $ciclo): ?>
                            <option 
                                value="<?= $ciclo->id ?>"
                                <?= ($getValue('select2') == $ciclo->id) ? 'selected' : '' ?>
                            >
                                <?= $ciclo->nombre ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                        <span id="error-select2" class="input-error-message"><?= $errors['select2'] ?? '' ?></span>
                    </div>
                </div>

            </div> 
            
            <div class="button-container">
                <input type="submit" name="btnAddOffer" value="Aceptar" id="btnConfirmar">
                <input type="submit" name="btnCancel" value="Volver" id="btnCancelar">
            </div>
        </form>
    </div>
<?php $this->stop() ?>