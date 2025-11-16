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
<h1>ALUMNO</h1>
<?php $this->stop() ?>