<?php 
// Aseguramos que las variables existan para evitar errores fatales (pantalla blanca)
$candidato = $this->data['candidato'] ?? null;
$solicitud = $this->data['solicitud'] ?? null; // Asumo que pasas 'solicitud' en $this->data
?>

<div class="candidate-wrapper">
    
    <div class="candidate-card">
        
        <div class="candidate-visual">
            <div class="bg-overlay"></div>
            <img src="/public<?php echo $candidato->foto ?? '/assets/images/genericAvatar.svg'; ?>" 
                 alt="Foto Candidato" 
                 class="profile-image">
        </div>

        <div class="candidate-name">
            <h3><?php echo $candidato->nombre ?? 'Nombre no disponible'; ?></h3>
        </div>

        <div class="candidate-details">
            <p class="dni-text">DNI: <?php echo $candidato->dni ?? '---'; ?></p>
            
            <div class="candidate-comment-block">
                <h4 class="comment-title">Comentario de la solicitud</h4>
                <div class="comment-box">
                    <p class="comment-text">
                       <?php echo $solicitud->comentarios ?? 'Sin comentarios adicionales en la solicitud.'; ?>
                    </p>
                </div>
            </div>
        </div>

        <div class="candidate-location">
            <img src="/public/assets/images/location.svg" alt="Ubicación" class="icon-mini">
            <span><?php echo $candidato->localidad ?? 'Ubicación no definida'; ?></span>
        </div>

        <div class="candidate-tags">
                <?php foreach($candidato->estudios as $estudio): ?>
                    <span class="study-tag-candidate"><?php echo $estudio; ?></span>
                <?php endforeach; ?>
        </div>

        <div class="candidate-meta">
            <p class="date-text">
                <img src="/public/assets/images/calendar.svg" alt="Fecha" class="icon-mini">
                <?php echo $solicitud->fechaCreacion ?? date('Y-m-d'); ?>
            </p>
            
            <a href="/public<?php echo $candidato->cv ?? '#'; ?>" target="_blank" class="btn-view-cv">
                Ver CV 
                <img src="/public/assets/images/cv.svg" alt="CV" class="icon-mini-btn">
            </a>
        </div>

    </div> 
    <div class="candidate-actions-group">
        <button type="submit" name="btnReject" value="<?php echo $solicitud->id; ?>"  class="btn-decision reject" title="Rechazar solicitud">
            <img src="/public/assets/images/reject.svg" alt="Rechazar" class="icon-action">
            Rechazar
        </button>
        
        <button type="submit" name="btnHire" value="<?php echo $solicitud->id; ?>" class="btn-decision approve" title="Aprobar solicitud">
            <img src="/public/assets/images/hire.svg" alt="Aprobar" class="icon-action">
            Aprobar
        </button>
    </div>

</div>