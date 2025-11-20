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
        <h1>Ficha de Empresa</h1>
    </div>

    <form action="" method="post" id="verForm">
        <input type="hidden" name="id" value="<?= $empresaVer->id ?>">

        <div class="company-profile-layout">
            
            <div class="profile-left-col">
                <div class="profile-logo-container">
                    <img src="/public<?= $empresaVer->logo ?>" alt="Logo de <?= $empresaVer->nombre ?>" />
                </div>
                
                <div style="margin-top: 20px;">
                    <label class="form-label-styled">CIF</label>
                    <input type="text" name="cif" value="<?= $empresaVer->cif ?>" class="form-styled-input readonly-input" readonly>
                </div>
            </div>

            <div class="profile-right-col">
                <div class="form-grid-styled">
                    
                    <div class="form-field">
                        <label class="form-label-styled">Nombre</label>
                        <input type="text" name="nombre" value="<?= $empresaVer->nombre ?>" class="form-styled-input readonly-input" readonly>
                    </div>

                    <div class="form-field">
                        <label class="form-label-styled">Email (Usuario)</label>
                        <input type="text" name="username" value="<?= $empresaVer->username ?>" class="form-styled-input readonly-input" readonly>
                    </div>

                    <div class="form-field">
                        <label class="form-label-styled">Teléfono Empresa</label>
                        <input type="text" name="telefono" value="<?= $empresaVer->telefono ?>" class="form-styled-input readonly-input" readonly>
                    </div>

                    <div class="form-field">
                        <label class="form-label-styled">Persona Contacto</label>
                        <input type="text" name="nombrePersona" value="<?= $empresaVer->nombrePersona ?>" class="form-styled-input readonly-input" readonly>
                    </div>

                    <div class="form-field">
                        <label class="form-label-styled">Teléfono Contacto</label>
                        <input type="text" name="telPersona" value="<?= $empresaVer->telPersona ?>" class="form-styled-input readonly-input" readonly>
                    </div>

                    <div class="form-field">
                        <label class="form-label-styled">Provincia</label>
                        <input type="text" name="provincia" value="<?= $empresaVer->provincia ?>" class="form-styled-input readonly-input" readonly>
                    </div>
                    
                    <div class="form-field" style="grid-column: span 2;">
                        <label class="form-label-styled">Dirección / Localidad</label>
                        <input type="text" name="direccion_completa" value="<?= $empresaVer->direccion . ', ' . $empresaVer->localidad ?>" class="form-styled-input readonly-input" readonly>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-actions-bar">
            <input type="submit" name="btnCancel" value="Volver al listado" id="btnVolver" class="toolbar-btn btn-secondary">
        </div>
    </form>
</div>
<?php $this->stop() ?>