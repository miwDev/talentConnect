<?php
$this->layout('layout/adminLayout', [
    'title' => 'Talent Connect - Ficha de Empresa'
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
        <h1>Ficha de empresa</h1>
    </div>
    <form action="" method="post" id="verForm">
        <input type="hidden" name="id" value="<?= $empresaVer->id ?>">
        <div class="form-grid">
            <div class="form-field">
                <div>
                    <img src="/public<?= $empresaVer->logo ?>" alt="logo_image" />
                </div>
                <div>
                    <label for="cif">CIF</label>
                    <input type="text" name="cif" value="<?= $empresaVer->cif ?>" readonly>
                </div>
                <div>
                    <label for="nombre">NOMBRE</label>
                    <input type="text" name="nombre" value="<?= $empresaVer->nombre ?>" readonly>
                </div>
                <div class="form-field">
                    <label for="telefono">EMAIL</label>
                    <input type="text" name="telefono" value="<?= $empresaVer->username ?>" readonly>
                </div>
            </div>
            <div class="form-field">
                <label for="nombre">TELEFONO</label>
                <input type="text" name="nombre" value="<?= $empresaVer->telefono ?>" readonly>
            </div>
            <div class="form-field">
                <label for="email">PERSONA C</label>
                <input type="text" name="email" value="<?= $empresaVer->nombrePersona ?>" readonly>
            </div>
            <div class="form-field">
                <label for="telefono">TELÉFONO P</label>
                <input type="text" name="telefono" value="<?= $empresaVer->telPersona ?>" readonly>
            </div>
            <div class="form-field">
                <label for="telefono">PROVINCIA</label>
                <input type="text" name="telefono" value="<?= $empresaVer->provincia ?>" readonly>
            </div>
            <div class="form-field">
                <label for="telefono">LOCALIDAD</label>
                <input type="text" name="telefono" value="<?= $empresaVer->localidad ?>" readonly>
            </div>
            <div class="form-field">
                <label for="telefono">DIRECCION</label>
                <input type="text" name="telefono" value="<?= $empresaVer->direccion ?>" readonly>
            </div>
            <div class="form-field"></div>
        </div>
        <div class="button-container">
            <input type="submit" name="btnCancel" value="Volver" id="btnVolver">
        </div>
    </form>
</div>
<?php $this->stop() ?>