<?php 
//$rol = $this->session->userdata("rol"); 
$rol = $_SESSION['rol']; 
?>
<div class="margin-with-title">

    <div class="grid" style="margin-left:  8%;">
        <h2 class="titulo-iz" style="/*color: #1b5e98; margin: 0px*/">
            Administración de Usuarios
        </h2>
    </div>

    <div class="grid gridWithoutLength" style="margin: 0 8% 0% 8%;">
        <table class="table table-hover daos_datagrid">
            <thead>
                <tr>
                    <th> Nombre </th>
                    <th> Documento </th>
                    <th> Campaña </th>
                    <th> Rol </th>
                    <th> Estado </th>
                    <th> Permisos </th>
                    <th> Límite de uso </th>
                    <th style="width: 78px">&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $value) { ?>
                    <tr class="gradeC">
                        <td><?= $value['USUARIO'] ?></td>
                        <td><?= number_format($value['DOCUMENTO']) ?></td>
                        <td><?= $value['NOMBRECAMPANA'] ?></td>
                        <td><?= $value['ROL'] ?></td>
                        <td><?= $value['ESTADO'] ?></td>
                        <td><?= $value['PERMISOS'] ?></td>
                        <td>$<?= number_format($value['LIMITE_GASTO']) ?></td>
                        <td style="width: 78px!important">                      
                        <?php  if ($value['CODIGO']!= 47 ){?>
                            <a href="/index.php/portal/usuariosCreacion/actualizarUsuarios/<?= $value['PK_ENT_CODIGO'] ?>/<?= $value['IDCAMPANA'] ?>/<?= $value['CODIGO'] ?>/<?= $value['PK_VINCUL_CODIGO'] ?>">
                                <img class="grid-icon" src="/static/img/portal/iconos/editar.png">
                                </img>
                            </a>
                            <?php } ?>
                                                        
                            &nbsp;
                            <?php 
                            if ($rol == 45 && $value['CODIGO'] == 46) { ?>
                                <a data-toggle="modal" data-target="#<?= $value['PK_VINCUL_CODIGO'] ?>">
                                    <img class="grid-icon" src="/static/img/portal/iconos/eliminar.png">
                                </a>
                            <?php } elseif ($rol == 47 && $value['CODIGO'] != 47)  { ?>
                                <a data-toggle="modal" data-target="#<?= $value['PK_VINCUL_CODIGO'] ?>">
                                    <img class="grid-icon" src="/static/img/portal/iconos/eliminar.png">
                                </a>
                            <?php }?>
                            
                        </td>
                    </tr>
                <div class="modal fade" id="<?= $value['PK_VINCUL_CODIGO'] ?>" role="dialog" style="    margin-top: 15%;">
                    <div class="modal-dialog">

                        <!-- Modal content-->
                        <div class="modal-content" style="border-radius:35px">
                            <!--  <div class="modal-header">
                                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                                  </div>-->
                            <div class="modal-body" style="text-align: center;height: 150px;">


                                <p style="font-size:18px;color:#888686;font-weight: bold">¿Desea continuar la desvinculación?</p>

                                <div style="">
                                    <div class="button col-sm-6">
                                        <button type="button" onclick="
                                                $(location).attr('href', '/index.php/portal/usuariosCreacion/desvinculuser/<?= $value['PK_ENT_CODIGO'] ?>/<?= $value['IDCAMPANA'] ?>/<?= $value['CODIGO'] ?>/<?= $value['PK_VINCUL_CODIGO'] ?>');
                                                " name="ACEPTAR" value="1" class="btn btn-default spacing">ACEPTAR</button>
                                    </div>
                                    <div class="button col-sm-6">
                                        <button type="button" name="CANCELAR" class="btn btn-default spacing" data-dismiss="modal">CANCELAR</button>
                                    </div>
                                </div>
                                <br>
                            </div>
                            <!--   <div class="modal-footer">
                                      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                  </div>-->
                        </div>
                    </div>
                </div>
            <?php } ?>
            </tbody>

        </table>
    </div>
    <div class="col-sm-2 col-sm-push-5">
        <div class="row linkgenerico spacing" style=" ">
            <a  href="/index.php/portal/usuariosCreacion/crear">AGREGAR   NUEVO</a>
        </div>
    </div>
</div>
<!--Creacion usuario exitosa -->
<?php if (isset($_GET['ok'])) { ?>
    <!-- Modal confirmacion recarga-->
    <div class="modal fade" id="Modalcreacion" role="dialog" style="margin-top: 15%;"  data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content" style="border-radius:35px">

                <div class="modal-body" style="text-align: center;height: 240px;">
                    <div class="modal-header">
                        <h5 style="color: #366199;font-size: 20px;font-weight: bold; ">Usuario creado exitosamente</h5>
                    </div>
                    <p  style="font-size:15px;color:#888686;font-weight: bold;padding-top: 5px">Hemos enviado un correo electrónico con las credenciales de acceso a la plataforma.
                    </p>
                    <div style="">
                        <div class="button col-sm-6 col-sm-push-3" >
                            <button name="aceptar" data-dismiss="modal" class="btn btn-default spacing">ACEPTAR</button>
                        </div>
                    </div>
                    <br>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
    <!--Creacion usuario exitosa -->
<?php if (isset($_GET['ActOk'])) { ?>
    <!-- Modal confirmacion recarga-->
    <div class="modal fade" id="ModalActu" role="dialog" style="margin-top: 15%;"  data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content" style="border-radius:35px">

                <div class="modal-body" style="text-align: center;height: 210px;">
                    <div class="modal-header">
                        <h5 style="color: #366199;font-size: 20px;font-weight: bold; ">Usuario creado exitosamente</h5>
                    </div>
                    <p  style="font-size:15px;color:#888686;font-weight: bold;padding-top: 5px">El usuario se ha creado con un nuevo tipo de vinculación.
                    </p>
                    <div> 
                        
                        <div class="button col-sm-6 col-sm-push-3" >
                            <button name="aceptar" data-dismiss="modal" class="btn btn-default spacing">ACEPTAR</button>
                        </div>
                    </div>
                    <br>
                </div>
            </div>
        </div>
    </div>
<?php } ?>


<script type="text/javascript">
       var ActuOK = <?php if (isset($_GET['ActOk'])) {
    echo "1;";
} else {
    echo "0;";
} ?>
    var creaOK = <?php if (isset($_GET['ok'])) {
    echo "1;";
} else {
    echo "0;";
} ?>
    if (creaOK == 1) {
        $('#Modalcreacion').modal('show');
    } 
    
    if (ActuOK == 1) {
        $('#ModalActu').modal('show');
    }
</script>