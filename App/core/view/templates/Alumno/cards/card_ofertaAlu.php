<?php
$oferta = $this->data['oferta'] ?? null;
?>

<div class="oferta-card">
    
    <div class="card-content">
        <div class="card-CompanyInfo">
            <a href="?menu=ver-empresa&empresa=<?php echo $oferta->empresaId; ?>">
                <span id="idEmpresa"><?= $this->e($oferta->empresaId) ?></span>
                <img src="" alt="picInProg" class="companyPfp">
                <p class="companyName"></p>
            </a>
        </div>
        
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
            <button name="btnApply" value="<?= $oferta->id ?>" class="card-btn btn-apply btnApply">
                Apply
            </button>
        </div>
        <div class="actions-right">
            <button name="btnFav" value="<?= $oferta->id ?>" class="card-btn btn-fav btnFav">
            </button>
        </div>
    </div>
</div>