<?php
$oferta = $this->data['oferta'] ?? null;
$solicitud = $this->data['solicitud'] ?? null; 
?>

<div class="solicitudes-grid"> 
    
    <div class="misSolicitudes-card oferta-card">
        
        <div class="card-content">
            
            <div class="card-CompanyInfo">
                <span id="idEmpresa"><?= $this->e($oferta->empresaId) ?></span>
                <img src="" alt="Logo Empresa" class="companyPfp">
                <p class="companyName"></p> 
            </div>
            
            <div class="card-header">
                <h3 class="card-titulo"><?= $this->e($oferta->titulo) ?></h3>
                <span class="card-fecha-solicitud">Solicitado: <?= $this->e($solicitud->fechaCreacion) ?></span>
            </div>

            <div class="card-body">
                <p class="card-descripcion card-comentarios"><?= $this->e($solicitud->comentarios) ?></p>
            </div>
        </div>

        <div class="card-actions">
            <div class="actions-group">
                <button name="btnEdit" value="<?= $solicitud->id ?>" class="card-btn btn-apply btn-edit">
                    Edit
                </button>
                <button name="btnDelete" value="<?= $solicitud->id ?>" class="card-btn btn-delete">
                    Delete
                </button>
            </div>
        </div>
    </div>
    
    </div>