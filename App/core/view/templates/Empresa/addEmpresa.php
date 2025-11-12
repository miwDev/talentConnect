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
    <form action="" method="post" id="editForm">
        <div class="form-grid">
            <div class="form-field">
                <label for="cif">CIF</label>
                <input type="text" name="cif">
            </div>
            <div class="form-field">
                <label for="nombre">NOMBRE</label>
                <input type="text" name="nombre">
            </div>
            <div class="form-field">
                <label for="email">EMAIL</label>
                <input type="text" name="email">
            </div>
            <div class="form-field">
                <label for="telefono">TELÉFONO</label>
                <input type="tel" name="telefono">
            </div>
            <div class="form-field"></div>
        </div>
        <div class="button-container">
            <input type="submit" name="btnAddCompany" value="Aceptar" id="btnConfirmar">
            <input type="submit" name="btnCancel" value="Cancelar" id="btnCancelar">
        </div>
    </form>
</div>
<?php $this->stop() ?>