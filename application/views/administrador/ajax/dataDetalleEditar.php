<input value="<?= $data[0]['PK_DETALLE_SOLICITUD'] ?>" name="pkdetalle" hidden>
<input value="<?= $data[0]['PK_CODIGO_SOLICITUD'] ?>" name="pksolicitud" hidden>
<input type="text" class="textPatSt" id="tDoc" name="tipoDoc" value="<?= $data[0]['TIPDOC'] ?>" autocomplete="nope" onselectstart="return false" onpaste="return false;" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false"  required disabled>
<input type="text" class="textPatSt" id="nDoc" name="Documento" value="<?= $data[0]['DOCUMENTO'] ?>" autocomplete="nope" onselectstart="return false" onpaste="return false;" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false"  required disabled>
<!--<input type="text" class="textPatSt" id="pNom" name="producto" value="<?= $data[0]['PRODUCTO'] ?>" autocomplete="nope" onselectstart="return false" onpaste="return false;" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false"  required disabled>-->
<div   style="padding: 0px;text-align: left">
    <div class="form-group" style="margin-top: 0px;margin-bottom:0px;">
        <div  style="padding: 10px 0px 0px 0px;" > 
            <select class="sel-dinamico" name="producto" style="border-radius:30px;padding-right: 0px;width: 100%;" required>
                <option value="">Seleccione Producto</option>
                <?php foreach ($productos as $value) { ?> 
                    <option value=<?= $value['PK_PRODUCTO_CODIGO'] ?> <?php if ($data[0]['PK_PRODUC_CODIGO'] == $value['PK_PRODUC_CODIGO']) echo 'selected'; ?> > <?= $value['NOMBRE_PRODUCTO'] ?></option>
                <?php } ?>
            </select> 
        </div>
    </div>
</div>
<input type="text" class="textPatSt" id="pNom" name="primerNombre" placeholder="Primer Nombre" value="<?= $data[0]['PRIMER_NOMBRE'] ?>" autocomplete="nope" onselectstart="return false" onpaste="return false;" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false"  required>
<input type="text" class="textPatSt"  id="sNom" name="segundoNombre" value="<?= $data[0]['SEGUNDO_NOMBRE'] ?>" placeholder="Segundo Nombre" autocomplete="nope" onselectstart="return false" onpaste="return false;" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false" >
<input type="text" class="textPatSt"  id="pApe" name="primerApellido" value="<?= $data[0]['PRIMER_APELLIDO'] ?>" placeholder="Primer Apellido" autocomplete="nope" onselectstart="return false" onpaste="return false;" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false"  required>
<input type="text" class="textPatSt"  id="sApe" name="segundoApellido" value="<?= $data[0]['SEGUNDO_APELLIDO'] ?>" placeholder="Segundo Apellido" autocomplete="nope" onselectstart="return false" onpaste="return false;" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false" >
<input type="text" class="textPatSt"  id="correo" name="correo" value="<?= $data[0]['CORREO_ELECTRONICO'] ?>" placeholder="Correo electrónico" autocomplete="nope" onselectstart="return false" onpaste="return false;" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false" >
<input type="text" class="textPatSt"  id="tel" name="telefono" value="<?= $data[0]['TELEFONO'] ?>" placeholder="Telefono" autocomplete="nope" onselectstart="return false" onpaste="return false;" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false" >
<div  style="padding: 0px;text-align: left;padding-top: 10px">
    <select  class="sel-dinamico" name="custodio" id="custodio" style="width: 100%;"required>
        <option value=""> Asignar custodio</option>
        <?php foreach ($custodios as $key => $value) { ?>
            <option value="<?= $value['CODIGOENTIDA'] ?>" <?php if ($data[0]['PK_ENT_CUSTODIO'] == $value['CODIGOENTIDA']) echo 'selected'; ?>> <?= $value['NOMBRE'] ?></option>
        <?php } ?>
    </select>
</div>
