<?php
// Esto asume que tienes un layout.php que maneja el esqueleto de la página.
$this->layout('layout/layout', [
    'title' => 'Talent Connect - Inicio',
    'navItems' => [
        '<a id="login" href="?menu=login" class="menu-enlace">Iniciar sesión</a>',
        '<a id="registro" href="?menu=regRedirect" class="menu-enlace">Inscribirse</a>'
    ]
]);
?>

<?php $this->start('pageContent') ?>
<h1>ALUMNO</h1>
<?php $this->stop() ?>