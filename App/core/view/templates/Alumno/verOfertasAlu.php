<?php
$this->layout('layout/layout', [
    'title' => 'Talent Connect - Dashboard Alumno',
    'role' => $this->data['role'], 
]);
?>

<?php $this->start('js') ?>
<script src="/public/assets/js/verOfertasLogic.js"></script>
<script src="/public/assets/js/elements.js" defer></script>
<script src="/public/assets/js/addons.js" defer></script>
<?php $this->stop() ?>

<?php $this->start('pageContent') ?>
    <div class="list-container">
        <div class="header-section">
            <h1>Ofertas</h1>
        </div>
        <div class="search-bar-container">
            <input type="text" name="busqueda" placeholder="Buscar Oferta..." class="search-input">
            <img src="/public/assets/images/lupa.svg" alt="Buscar" class="search-icon">
            <button type="submit" name="btnBuscar" id="hiddenBuscar"></button>
        </div>
        <?php foreach ($ofertas as $oferta): ?>
            <?php
            $this->insert('Alumno/cards/card_ofertaAlu', [
                'oferta' => $oferta
            ]);
            ?>
        <?php endforeach; ?>
    </div>
<?php $this->stop() ?>