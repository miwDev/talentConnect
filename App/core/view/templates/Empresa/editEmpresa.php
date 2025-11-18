<?php
$this->layout('layout/adminLayout', [
    'title' => 'Talent Connect - Editar Empresa'
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
        <h1>Edición de Empresa</h1>
    </div>

    <div class="form-content-wrapper">
        <form action="" method="post" id="editForm">
            <input type="hidden" name="id" value="<?= $empresaEdit->id ?>">

            <div class="form-grid-styled">
                <div class="form-field">
                    <label for="cif" class="form-label-styled">CIF</label>
                    <input type="text" name="cif" value="<?= $empresaEdit->cif ?>" class="form-styled-input">
                </div>

                <div class="form-field">
                    <label for="nombre" class="form-label-styled">Nombre</label>
                    <input type="text" name="nombre" value="<?= $empresaEdit->nombre ?>" class="form-styled-input">
                </div>

                <div class="form-field">
                    <label for="email" class="form-label-styled">Email</label>
                    <input type="text" name="email" value="<?= $empresaEdit->email ?>" class="form-styled-input">
                </div>

                <div class="form-field">
                    <label for="telefono" class="form-label-styled">Teléfono</label>
                    <input type="text" name="telefono" value="<?= $empresaEdit->telefono ?>" class="form-styled-input">
                </div>
            </div>

            <div class="form-actions-bar">
                <input type="submit" name="btnCancel" value="Cancelar" id="btnCancelar" class="toolbar-btn btn-secondary">
                <input type="submit" name="btnAccept" value="Guardar Cambios" id="btnConfirmar" class="toolbar-btn btn-primary">
            </div>
        </form>
    </div>
</div>
<?php $this->stop() ?>