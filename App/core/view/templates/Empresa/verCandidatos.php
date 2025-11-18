<?php
use App\core\data\SolicitudRepo;
?>
<?php
$this->layout('layout/layout', [
    'title' => 'Talent Connect - Mis Ofertas',
    'role' => $this->data['role'],
    'candidatos' => $this->data['candidatos'] ?? null,
    'oferta' => $this->data['oferta'] ?? null
]);
?>

<?php $this->start('pageContent') ?>
    <div class="list-container">
        <div class="header-section">
            <h1>Candidatos para: <?php echo $oferta->titulo; ?></h1>
            <?php var_dump($_POST) ?>
        </div>
        
        <form action="" method="post" id="editForm">
            <div class="search-bar-container">
                <button type="input" name="btnVolver" value="Volver" class="btn-volver">
                    <img src="/public/assets/images/volver.svg" alt="Volver" class="btn-volver-icon">
                    <span>Volver</span>
                </button>
            </div>
            <?php foreach ($candidatos as $candidato): ?>
                <?php
                $solicitud = SolicitudRepo::findByOfertaAndAlumno($oferta->id, $candidato->id);
                $this->insert('Empresa/cards/card_candidate', [
                    'candidato' => $candidato,
                    'solicitud' => $solicitud
                ]);
                ?>
            <?php endforeach; ?>
        </form>
    </div>
<?php $this->stop() ?>