<?php
// Usamos el layout normal, no el de admin
$this->layout('layout/layout', [
    'title' => 'Talent Connect - Editar Oferta',
    'role' => $this->data['role']
]);

$ciclo1_id = $ofertaEdit->ciclos[0] ?? null;
$ciclo2_id = $ofertaEdit->ciclos[1] ?? null;
?>

<?php $this->start('pageContent') ?>

<div class="list-container">
    <div class="header-section">
        <h1>Edición de oferta</h1>
    </div>

    <form action="" method="post" id="editForm">
        <input type="hidden" name="id" value="<?= $ofertaEdit->id ?>">

        <div class="form-grid">

            <div class="form-row-full">
                <div class="form-group-half">
                    <label for="titulo">Título</label>
                    <input type="text" id="titulo" name="titulo" value="<?= $this->e($ofertaEdit->titulo) ?>">
                </div>
                <div class="form-group-half">
                    <label for="fechaFin">Fecha Fin</label>
                    <input type="text" id="fechaFin" name="fechaFin" placeholder="YYYY-MM-DD" value="<?= $this->e($ofertaEdit->fechaFin) ?>">
                </div>
            </div>

            <label for="desc" class="form-span-label">Descripción</label>
            <textarea id="desc" name="descripcion" class="form-span-input" placeholder="Máximo de 250 caracteres"><?= $this->e($ofertaEdit->descripcion) ?></textarea>

            <label for="salario">Salario</label>
            <input type="text" id="salario" name="salario" placeholder="Ej: 25.000€ brutos/año" value="<?= $this->e($ofertaEdit->salario) ?>">
            
            <label class="select-group-label">CICLOS</label>
            <div class="select-group">
                <select name="select1">
                    <option value="">Seleccione el ciclo 1</option>
                    <?php foreach ($ciclos as $ciclo): ?>
                        <option value="<?= $ciclo->id ?>"
                            <?= ($ciclo->id == $ciclo1_id) ? 'selected' : '' ?>
                        >
                            <?= $ciclo->nombre ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                
                <select name="select2">
                    <option value="">Seleccione el ciclo 2 ( * )</option>
                    <?php foreach ($ciclos as $ciclo): ?>
                         <option 
                            value="<?= $ciclo->id ?>"
                            <?= ($ciclo->id == $ciclo2_id) ? 'selected' : '' ?>
                        >
                            <?= $ciclo->nombre ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

        </div> 
        
        <div class="button-container">
            <input type="submit" name="btnAccept" value="Aceptar" id="btnConfirmar">
            <input type="submit" name="btnCancel" value="Cancelar" id="btnCancelar">
        </div>
    </form>

</div>
<?php $this->stop() ?>