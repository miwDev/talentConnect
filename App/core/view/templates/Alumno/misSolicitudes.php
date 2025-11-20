<?php
$this->layout('layout/layout', [
    'title' => 'Talent Connect - Dashboard Alumno',
    'role' => $this->data['role'], 
]);
?>
<?php $this->start('js') ?>
<script src="/public/assets/js/validators.js"></script>
<script src="/public/assets/js/misSolicitudesLogic.js"></script>
<script src="/public/assets/js/elements.js" defer></script>
<?php $this->stop() ?>
<?php $this->start('pageContent') ?>
<div class="list-container">
    <div class="header-section">
        <h1>Mis Solicitudes</h1>
    </div>
    <?php foreach ($solicitudesYOfertas as $item): ?>
        <?php
        $this->insert('Alumno/cards/card_misSolicitudes', [
            'oferta' => $item['oferta'],
            'solicitud' => $item['solicitud']
        ]);
        ?>
    <?php endforeach; ?>
</div>
<?php $this->stop() ?>