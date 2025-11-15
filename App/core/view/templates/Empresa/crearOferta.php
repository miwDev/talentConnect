<?php
$this->layout('layout/layout', [
    'title' => 'Talent Connect - Creación de Oferta',
    'role' => $this->data['role'], 
]);
?>

<?php $this->start('pageContent') ?>
    <div class="list-container">
        <div class="header-section">
            <h1>Creación de Oferta</h1>
            <?= var_dump($_POST) ?>
        </div>
        
        <form action="" method="post" id="editForm">
            <div class="form-grid">

                <label for="titulo">Título</label>
                <input type="text" id="titulo" name="titulo">

                <label for="desc">Descripción</label>
                <textarea id="desc" name="desc" placeholder="Máximo de 250 caracteres"></textarea>

                <label for="salario">Salario</label>
                <input type="text" id="salario" name="salario" placeholder="Ej: 25.000€ brutos/año">

                <label for="fechaFin">Fecha de Finalización</label>
                <input type="text" id="fechaFin" name="fechaFin" placeholder="YYYY-MM-DD">

                <label class="select-group-label">CICLOS</label> <div class="select-group">
                    <select name="select1">
                        <option value="">Seleccione el ciclo 1</option>
                        <?php foreach ($ciclos as $ciclo): ?>
                        <option value="<?= $ciclo->id ?>"><?= $ciclo->nombre ?></option>
                        <?php endforeach; ?>
                        </select>
                    <select name="select2">
                         <option value="">Seleccione el ciclo 2 ( * )</option>
                        <?php foreach ($ciclos as $ciclo): ?>
                        <option value="<?= $ciclo->id ?>"><?= $ciclo->nombre ?></option>
                        <?php endforeach; ?>
                        </select>
                </div>

            </div> <div class="button-container">
                <input type="submit" name="btnAddOffer" value="Aceptar" id="btnConfirmar">
                <input type="submit" name="btnCancel" value="Volver" id="btnCancelar">
            </div>
        </form>
    </div>
<?php $this->stop() ?>