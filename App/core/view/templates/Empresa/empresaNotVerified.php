<?php
// Asume que esta plantilla se renderiza dentro de tu 'layout/layout.php'
$this->layout('layout/layout', [
    'title' => 'Talent Connect - Pendiente de Aprobación',
    'role' => $role ?? 'ROLE_GUEST' 
]);
?>

<?php $this->start('css') ?>
<link rel="stylesheet" type="text/css" href="/public/assets/css/authorization.css" />
<?php $this->stop() ?>

<?php $this->start('pageContent') ?>

<div class="pending-container">
    
    <div class="pending-box">
        
        <h1>Su empresa está pendiente de confirmación</h1>

        <p>
            Nuestro equipo de administración está revisando tu solicitud.
            Recibirás un correo electrónico de confirmación una vez que tu
            empresa sea aprobada para ganar acceso a la plataforma.
        </p>

        <div>
            <a href="?menu=home" class="pending-button">
                Volver al Inicio
            </a>
        </div>
    </div>
</div>

<?php $this->stop() ?>