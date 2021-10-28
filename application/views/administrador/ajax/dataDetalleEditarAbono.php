
<input value="<?= $data[0]['PK_DETALLE_SOLICITUD'] ?>" name="pkdetalle" hidden>
<input value="<?= $data[0]['PK_CODIGO_SOLICITUD'] ?>" name="pksolicitud" hidden>
<input value="<?= $data[0]['PK_TD_CODIGO'] ?>" name="tipoDoc" hidden>
<input type="text" class="textPatSt"  name="tipoDocNom" value="<?= $data[0]['TIPDOC'] ?>" autocomplete="nope" onselectstart="return false" onpaste="return false;" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false"  required disabled>
<input type="text" class="textPatSt"  name="Documento" value="<?= $data[0]['DOCUMENTO'] ?>" autocomplete="nope" onselectstart="return false" onpaste="return false;" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false"  required disabled>
<input type="text" class="textPatSt" id="pNom" name="producto" value="<?= $data[0]['PRODUCTO'] ?>" autocomplete="nope" onselectstart="return false" onpaste="return false;" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false"  required disabled>
<input value="<?= $data[0]['PK_PRODUCTO_CODIGO'] ?>" name="pkproducto" hidden>
<?php if (!empty($data[0]['IDENTIFICADOR_TARJETA'])) { ?>
    <input type="text" class="textPatSt" name="identificador" placeholder="Identificador" value="<?= $data[0]['IDENTIFICADOR_TARJETA'] ?>" autocomplete="nope" onselectstart="return false" onpaste="return false;" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false"  required>
<?php } ?>
<input type="text" class="textPatSt"   data-type="currency" name="valorAbono" value="<?= $data[0]['MONTO_ABONO'] ?>" placeholder="Segundo Nombre" autocomplete="nope" onselectstart="return false" onpaste="return false;" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false" >
<input value="<?= $data[0]['FECHA_DISPERSION'] ?>" name="fechaDisp" type="date" min="<?= date("Y-m-d") ?>" class="tFecha"  required/>
