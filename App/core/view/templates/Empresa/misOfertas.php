<?php
$this->layout('layout/layout', [
    'title' => 'Talent Connect - Mis Ofertas',
    'role' => $this->data['role']
]);
?>

<?php $this->start('pageContent') ?>
    <div class="list-container">
        <div class="header-section">
            <h1>Mis ofertas</h1>
            <?= var_dump($_POST) ?>
        </div>
        
        <form action="" method="post" id="editForm">
            <div class="search-bar-container">
                <input type="text" name="busqueda" placeholder="Buscar Oferta..." class="search-input">
                <img src="/public/assets/images/lupa.svg" alt="Buscar" class="search-icon">
                <button type="submit" name="btnBuscar" id="hiddenBuscar"></button>
            </div>
            <?php foreach ($ofertas as $oferta): ?>
                <?php
                $this->insert('Empresa/cards/card_misOfertas', [
                    'oferta' => $oferta
                ]);
                ?>
            <?php endforeach; ?>
        </form>
    </div>
<?php $this->stop() ?>