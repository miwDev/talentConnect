<?php
$this->layout('layout/adminLayout', [
    'title' => 'Talent Connect - Gestión de Empresas',
    'navItems' => []
]);
?>

<?php $this->start('css') ?>
<link rel="stylesheet" type="text/css" href="/public/assets/css/listadosAdmin.css" />
<?php $this->stop() ?>

<?php $this->start('js') ?>
<script src="/public/assets/js/elements.js"></script>
<script src="/public/assets/js/listaEmpresaLogic.js"></script>
<?php $this->stop() ?>

<?php $this->start('pageContent') ?>
<div class="list-container">
    <div class="header-section">
        <form action="" method="post">
            <h1>Listado de Empresas</h1>
            <div class="search-section">
                <input type="text" name="buscar" id="buscar" placeholder="Buscar empresa...">
                <button type="submit" id="buscar-btn" class="search-btn">Buscar</button>
            </div>
        </form>
    </div>
    <div class="add-button-container">
        <form action="" method="post">
            <input type="submit" id="add" name="btnAdd" value="AÑADIR EMPRESA">
        </form>
    </div>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Telefono</th>
                    <th class="actions-column">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($empresasTotal as $empresa): ?>
                    <tr>
                        <td><?= $empresa->id ?></td>
                        <td><?= $empresa->nombre ?></td>
                        <td><?= $empresa->email ?></td>
                        <td><?= $empresa->telefono ?></td>
                        <td class="actions-column">
                            <form action="" method="post">
                                <button type="submit" class="action-btn edit-btn" name="btnEdit" value="<?= $empresa->id ?>">Editar</button>
                                <button type="submit" class="action-btn delete-btn" name="btnBorrar" value="<?= $empresa->id ?>">Eliminar</button>
                                <button type="submit" class="action-btn edit-btn" name="btnVerFicha" value="<?= $empresa->id ?>">Ver Ficha</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="list-container">
    <div class="header-section">
        <h1 class="titulo-confirmed">Pendiente de confirmación</h1>
    </div>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th class="actionsPending-column">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pendientes as $empresa): ?>
                    <tr>
                        <td><?= $empresa->id ?></td>
                        <td><?= $empresa->nombre ?></td>
                        <td><?= $empresa->username ?></td>
                        <td class="actionsPending-column">
                            <form action="#" method="post">
                                <button type="submit" class="action-btn edit-btn" name="btnConfirmar" value="<?= $empresa->id ?>">Confirmar</button>
                                <button type="submit" class="action-btn delete-btn" name="btnRechazar" value="<?= $empresa->id ?>">Rechazar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $this->stop() ?>