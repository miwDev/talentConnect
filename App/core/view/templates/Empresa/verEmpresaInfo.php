<?php
use App\core\data\SolicitudRepo;
?>
<?php
$this->layout('layout/layout', [
    'title' => 'Talent Connect - Mis Ofertas',
    'role' => $this->data['role'],
]);
?>

<?php
$currentFilter = $_POST['ofertasFilter'] ?? 'active'; 
?>

<?php $this->start('js') ?>
<script src="/public/assets/js/verOfertasEmp.js"></script>
<script src="/public/assets/js/verEmpresa.js"></script>
<script src="/public/assets/js/elements.js" defer></script>
<script src="/public/assets/js/addons.js" defer></script>
<?php $this->stop() ?>

<?php $this->start('pageContent') ?>
    
    <div class="list-container ficha-empresa"> 
        
        <div class="header-section">
            <div class="logo-container">
                <img src="/public<?php echo $empresa->logo ?? '/assets/images/genericAvatar.svg'; ?>" 
                     alt="Foto Empresa" 
                     class="companyPfp">
            </div>
            
            <div class="name-container">
                <p class="companyName">
                    <?php echo $empresa->nombre ?>
                </p>
            </div>
        </div>
        
        <div class="main-content-wrapper">
            
            <div class="content-section">
                
                <div>
                    <h2>Contacto</h2>
                    <p class="companyName">
                        <strong>Teléfono Empresa:</strong> <span><?php echo $empresa->telefono ?></span>
                    </p>
                    <p class="companyName">
                        <strong>Persona de Contacto:</strong> <span><?php echo $empresa->nombrePersona ?></span>
                    </p>
                    <p class="companyName">
                        <strong>Teléfono Personal:</strong> <span><?php echo $empresa->telPersona ?></span>
                    </p>
                </div>
                
                <div class="address-section">
                    <h2>Ubicación</h2>
                    <p class="companyName">
                        <strong>Provincia:</strong> <span><?php echo $empresa->provincia ?></span>
                    </p>
                    <p class="companyName">
                        <strong>Localidad:</strong> <span><?php echo $empresa->localidad ?></span>
                    </p>
                    <p class="companyName">
                        <strong>Dirección:</strong> <span><?php echo $empresa->direccion ?></span>
                    </p>
                </div>
                
            </div>
            
        </div> </div>

    <div class="list-container ofertas-filtro">
    
    <form method="POST" action=""> 
        
        <div class="button-group">
                <button 
                    name="ofertasFilter" 
                    value="active" 
                    type="submit" 
                    id="active"
                    class="<?php echo $currentFilter === 'active' ? 'active' : ''; ?>"
                >
                    Ofertas Activas
                </button>
                <button 
                    name="ofertasFilter" 
                    value="expired" 
                    type="submit" 
                    id="finalized"
                    class="<?php echo $currentFilter === 'expired' ? 'active' : ''; ?>"
                >
                    Ofertas Finalizadas
                </button>
            </div>

        <?php foreach ($ofertas as $oferta): ?>
                <?php
                $this->insert('Empresa/cards/card_ofertaInfo', [
                    'oferta' => $oferta
                ]);
                ?>
            <?php endforeach; ?>

    </form>

</div>
<?php $this->stop() ?>