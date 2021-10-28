<?php if ($agregar) { ?>
    <a href="/contenido/agregar/<?= $objeto['id_conte_objeto'] ?>/<?= $id_conte_grupo ?>" class="btn btn-primary btn-sm btn-principales"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span>&nbsp;&nbsp; Agregar</a>
<?php } ?>
<h2><?= $objeto['nombre_conte_objeto'] ?></h2>
<hr/>
<br/>
<table class="table table-bordered table-hover daos_datagrid">
    <thead>
        <tr>
            <?php foreach ($campos as $campo) { ?>
                <th><?= $campo['nombre_conte_campo'] ?></th>
            <?php } ?>
            <?php foreach ($hijos as $hijo) { ?>
                <th><?= $hijo['nombre_conte_objeto'] ?></th>
            <?php } ?>
            <th>Modificar</th>
            <th>Eliminar</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($grupos as $item) { ?>
            <tr class="gradeC">
                <?php foreach ($campos as $campo) { ?>
                    <td>
                        <?php if ($campo['id_conte_tipo'] == 1) { ?>
                            <?= $item['contenido_conte_valor_' . $campo['id_conte_campo']] ?>
                        <?php } ?>
                        <?php if ($campo['id_conte_tipo'] == 2) { ?>
                            <?= $item['contenido_conte_valor_' . $campo['id_conte_campo']] ?>
                        <?php } ?>
                        <?php if ($campo['id_conte_tipo'] == 3 && $item['contenido_conte_valor_' . $campo['id_conte_campo']] != "") { ?>
                            <a href="<?= $item['contenido_conte_valor_' . $campo['id_conte_campo']] ?>" target="_blank">Ver</a> 
                        <?php } ?>
                        <?php if ($campo['id_conte_tipo'] == 4 && $item['contenido_conte_valor_' . $campo['id_conte_campo']] != "") { ?>
                            <img src="<?= $item['contenido_conte_valor_' . $campo['id_conte_campo']] ?>" width="200" />
                        <?php } ?>
                        <?php if ($campo['id_conte_tipo'] == 5 && $item['contenido_conte_valor_' . $campo['id_conte_campo']] != "") { ?>
                            <iframe width="300" height="200" src="<?= $item['contenido_conte_valor_' . $campo['id_conte_campo']] ?>" frameborder="0" allowfullscreen></iframe>
                        <?php } ?>
                    </td>
                <?php } ?>
                <?php foreach ($hijos as $hijo) { ?>
                    <td style="width: 100px !important"><a href="/contenido/seccion/<?=$hijo['id_conte_objeto'] ?>/<?= $item['id_conte_grupo'] ?>" class="btn btn-info btn-xs"><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span> &nbsp; Lista</a></td>
                <?php } ?>
                <td><a href="/contenido/agregar/<?= $objeto['id_conte_objeto'] ?>/<?= $id_conte_grupo ?>/<?= $item['id_conte_grupo'] ?>" class="btn btn-warning btn-xs"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span> &nbsp; Modificar</a></td>
                <td style="width: 103px !important"><a href="/contenido/eliminar/<?= $objeto['id_conte_objeto'] ?>/<?= $item['id_conte_grupo'] ?>" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> &nbsp; Eliminar</a></td>
            </tr>
        <?php } ?>
    </tbody>
</table>