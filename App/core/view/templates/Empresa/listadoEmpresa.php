<?php
$this->layout('layout/adminLayout', [
    'title' => 'Talent Connect - Gestión de Empresas'
]);
?>

<?php $this->start('css') ?>
<link rel="stylesheet" type="text/css" href="/public/assets/css/listadosAdmin.css" />
<?php $this->stop() ?>

<?php $this->start('js') ?>
<script src="/public/assets/js/elements.js"></script>
<?php $this->stop() ?>

<?php $this->start('pageContent') ?>
<div class="list-container">
    
    <div class="header-section">
        <h1>Listado de Empresas</h1>
        
        <div class="admin-toolbar">
            
            <form action="" method="post" class="toolbar-group-left" style="width: auto; flex: 1;">
                
                <button type="submit" id="buscar-btn" class="toolbar-btn btn-secondary">Buscar</button>

                <input type="text" name="buscar" id="buscar" class="toolbar-input" placeholder="Buscar empresa..." style="min-width: 200px;">
                
                <button type="submit" name="btnOrdenar" class="toolbar-btn btn-secondary">Ordenar</button>

                <select name="ordenEmpresa" id="ordenEmpresa" class="toolbar-select">
                    <option value="">Ordenar por...</option>
                    <option value="cif">Cif</option>
                    <option value="nombre">Nombre</option>
                    <option value="email">Email</option>
                    <option value="telefono">Telefono</option>
                </select>

            </form>

            <div class="toolbar-group-right">
                <form action="" method="post">
                    <input type="submit" id="add" name="btnAdd" class="toolbar-btn btn-primary" value="+ AÑADIR EMPRESA">
                </form>
            </div>

        </div>
    </div>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>CIF</th>
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
                        <td><?= $empresa->cif ?></td>
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
        <h1 class="titulo-confirmed" style="font-size: 2em; margin-bottom: 20px;">Pendiente de confirmación</h1>
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