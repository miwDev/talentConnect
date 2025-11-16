<?php
$this->layout('layout/layout', [
    'title' => 'Talent Connect - Dashboard Alumno',
    'role' => $this->data['role'], 
]);
?>

<?php $this->start('js') ?>
<script src="/public/assets/js/panelAlumnoLogic.js"></script>
<script src="/public/assets/js/elements.js" defer></script>
<script src="/public/assets/js/addons.js" defer></script>
<?php $this->stop() ?>

<?php $this->start('pageContent') ?>
    <div class="list-container">
        <div class="header-section">
            <h1>Mis Solicitudes</h1>
            <?= var_dump($_POST) ?>
        </div>
        <?php foreach ($solicitudes as $solicitud): ?>
            <?php
            $this->insert('Alumno/cards/card_misSolicitudes', [
                'oferta' => $oferta,
                'solicitudes' => $solicitudes
            ]);
            ?>
        <?php endforeach; ?>
    </div>
<?php $this->stop() ?>