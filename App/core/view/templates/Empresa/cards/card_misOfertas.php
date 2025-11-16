<?php
$oferta = $this->data['oferta'] ?? null;
?>

<div class="oferta-card">
    
    <div class="card-content">
        
        <div class="card-header">
            <h3 class="card-titulo"><?= $this->e($oferta->titulo) ?></h3>
            <span class="card-fecha-fin">Fecha de Finalización: <?= $this->e($oferta->fechaFin) ?></span>
        </div>

        <div class="card-body">
            <p class="card-descripcion"><?= $this->e($oferta->descripcion) ?></p>
        </div>

        <div class="card-footer">
            <span class="card-salario"><?= $this->e($oferta->salario) ?> €/Año</span>
            <div class="card-ciclos">
                <?php if (!empty($oferta->ciclos[0])): ?>
                    <span class="ciclo-tag"><?= $this->e($oferta->ciclos[0]) ?></span>
                <?php endif; ?>
                <?php if (!empty($oferta->ciclos[1])): ?>
                    <span class="ciclo-tag"><?= $this->e($oferta->ciclos[1]) ?></span>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="card-actions">
        <div class="actions-left">
            <button type="submit" name="btnEdit" value="<?= $oferta->id ?>" class="card-btn btn-edit">
                Edit
            </button>
            <button type="submit" name="btnDelete" value="<?= $oferta->id ?>" class="card-btn btn-delete">
                Delete
            </button>
        </div>
        <div class="actions-right">
            <?php
            $numSolicitudes = 0;
            if (property_exists($oferta, 'solicitudes') && is_array($oferta->solicitudes)) {
                $numSolicitudes = count($oferta->solicitudes);
            }
            ?>
            <button type="submit" name="btnCandidatos" value="<?= $oferta->id ?>" class="card-btn btn-candidatos">
                Ver Candidatos (<?= $numSolicitudes ?>)
            </button>
        </div>
    </div>
</div>