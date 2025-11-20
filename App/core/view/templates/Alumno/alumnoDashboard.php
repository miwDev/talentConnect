<?php
$this->layout('layout/layout', [
    'title' => 'Talent Connect - Dashboard Alumno',
    'role' => $this->data['role'], 
]);
?>

<?php $this->start('js') ?>
<script src="/public/assets/js/verOfertasLogic.js"></script>
<script src="/public/assets/js/elements.js" defer></script>
<?php $this->stop() ?>

<?php $this->start('pageContent') ?>
<div class="list-container dashboard-alumno-container">
    
    <div class="header-section">
        <h1 class="student-panel-title">Panel Alumno</h1>
        <p class="dashboard-subtitle">Bienvenido a tu panel de control. Explora las últimas ofertas y empresas de tu interés.</p>
    </div>

    <div class="section-empresas">
        <h2 class="section-title">Empresas Relacionadas</h2>
        <div class="companies-container">
            <div class="logo-slider" id="empresasCarrusel">
                <?php 
                    foreach ($empresas as $empresa): ?>
                    <?php
                    $this->insert('Empresa/cards/card_logoFunctional', [
                        'empresa' => $empresa
                    ]);
                    ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    
    <hr>
    
    <div class="section-ofertas">
        <h2 class="section-title">Últimas Ofertas para Ti</h2>
        <div class="ofertas-scroll-container" id="ultimasOfertasContainer">
            <?php 
            foreach ($ofertas as $oferta): ?>
                <?php
                $this->insert('Alumno/cards/card_ofertaAlu', [
                    'oferta' => $oferta
                ]);
                ?>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php $this->stop() ?>