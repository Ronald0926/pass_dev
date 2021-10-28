<?php
header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=envioUnoaUno.xls");  //File name extension was wrong
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private", false);
?>
<html>
    <head>
        <meta charset="UTF-8">
    </head>
    <body>
        <table >
            <thead>
                <tr>
                    <th>Destinatario</th>
                    <th>Direccion</th>
                    <th>Tel&eacute;fono</th>
                    <th>Ciudad</th>
                    <th>Unidades</th>
                    <th>Peso</th>
                    <th>Observaciones</th>
                    <th>Documento</th>
                    <th>Contenido</th>
                    <th>vrDeclarado</th>
                    <th>Notas</th>
                    <th>Guia</th>
                    <th>Transportadora</th>
                    <th>Id_Destinatario</th>
                    <th>TipoIdDest</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($unoauno as $value) { ?>
                    <tr>
                        <td><?= $value['DESTINATARIO'] ?></td>
                        <td><?= $value['DIRECCION'] ?></td>
                        <td><?= $value['TELEFONO'] ?></td>
                        <td><?= $value['CIUDAD'] ?></td>
                        <td><?= $value['UNIDADES'] ?></td>
                        <td><?= $value['PESO'] ?></td>
                        <td><?= $value['OBSERVACIONES'] ?></td>
                        <td><?= $value['DOCUMENTO'] ?></td>
                        <td><?= $value['CONTENIDO'] ?></td>
                        <td><?= $value['VRDECLARADO'] ?></td>
                        <td><?= $value['NOTAS'] ?></td>
                        <td><?= $value['NGUIA'] ?></td>
                        <td><?= $value['TRANSPORTADORA'] ?></td>
                        <td><?= $value['ID_DESTINATARIO'] ?></td>
                        <td><?= $value['TIPOIDDEST'] ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </body>
</html>