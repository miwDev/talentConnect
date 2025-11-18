<?php
$this->layout('layout/adminLayout', [
    'title' => 'Talent Connect - Añadir Empresa'
]);
?>

<?php $this->start('css') ?>
<link rel="stylesheet" type="text/css" href="/public/assets/css/listadosAdmin.css" />
<?php $this->stop() ?>

<?php $this->start('js') ?>
<script src="/public/assets/js/elements.js"></script>
<?php $this->stop() ?>

<?php $this->start('pageContent') ?>
<div class="list-container">
    <div class="header-section">
        <h1>Añadir Empresa</h1>
    </div>
    
    <div class="form-content-wrapper">
        <form action="" method="post" id="editForm"> <div class="form-grid-styled">
                <div class="form-field">
                    <label for="cif" class="form-label-styled">CIF</label>
                    <input type="text" name="cif" class="form-styled-input" placeholder="Ej: B12345678" required>
                </div>
                
                <div class="form-field">
                    <label for="nombre" class="form-label-styled">Nombre Fiscal</label>
                    <input type="text" name="nombre" class="form-styled-input" placeholder="Nombre de la empresa" required>
                </div>
                
                <div class="form-field">
                    <label for="email" class="form-label-styled">Email Corporativo</label>
                    <input type="text" name="email" class="form-styled-input" placeholder="contacto@empresa.com" required>
                </div>
                
                <div class="form-field">
                    <label for="telefono" class="form-label-styled">Teléfono</label>
                    <input type="tel" name="telefono" class="form-styled-input" placeholder="912 345 678">
                </div>
            </div>

            <div class="form-actions-bar">
                <input type="submit" name="btnCancel" value="Cancelar" id="btnCancelar" class="toolbar-btn btn-secondary">
                <input type="submit" name="btnAddCompany" value="Guardar Empresa" id="btnConfirmar" class="toolbar-btn btn-primary">
            </div>
        </form>
    </div>
</div>
<?php $this->stop() ?>