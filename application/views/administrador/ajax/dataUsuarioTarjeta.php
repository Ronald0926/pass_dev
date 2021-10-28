<?php
//var_dump($data) ;
$porcionNom = explode(" ", $data[0]['NOMBRE']);
$porcionApe = explode(" ", $data[0]['APELLIDO']);
?>
<input type="text" class="textPatSt" id="pNom" name="primerNombre" placeholder="Primer Nombre" value="<?= $porcionNom[0] ?>" autocomplete="nope" onselectstart="return false" onpaste="return false;" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false"  required>

<input type="text" class="textPatSt"  id="sNom" name="segundoNombre" value="<?= $porcionNom[1] ?>" placeholder="Segundo Nombre" autocomplete="nope" onselectstart="return false" onpaste="return false;" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false" >

<input type="text" class="textPatSt"  id="pApe" name="primerApellido" value="<?= $porcionApe[0] ?>" placeholder="Primer Apellido" autocomplete="nope" onselectstart="return false" onpaste="return false;" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false"  required>

<input type="text" class="textPatSt"  id="sApe" name="segundoApellido" value="<?= $porcionApe[1] ?>" placeholder="Segundo Apellido" autocomplete="nope" onselectstart="return false" onpaste="return false;" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false" >

<br> 

<input type="email" class="correoPat" name="correo" value="<?= $data[0]['CORREO_ELECTRONICO'] ?>" id="correo" placeholder="Correo Electrónico" autocomplete="nope" onselectstart="return false" onpaste="return false;" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false"  required>
<br>
<br>
<!-- <input type="date" name="fechaNacimiento" placeholder="Fecha de Nacimiento" required>
<br>
<br>
<div class="select" hidden>
    <select name="genero" id="genero" class="required" required>
        <option value=""> Selecione Genero</option>
<?php foreach ($generos as $key => $value) { ?>
                                                                                                <option value="<?= $value['PK_GEN_CODIGO'] ?>"> <?= $value['NOMBRE'] ?></option>
<?php } ?>
    </select>
    <div>Selecione Genero</div>
</div>
<br>-->
<div class="select" id="sDepar">
    <select name="departamentos" id="inputDepartamento" class="required" required>
        <option value=""> Seleccione Departamento</option>
        <?php foreach ($departamentos as $key => $value) { ?>
            <option value="<?= $value['PK_DEP_CODIGO'] ?>" <?php if ($value['PK_DEP_CODIGO'] == $data[0]['DEPARTAMENTO']) echo 'selected'; ?>> <?= $value['NOMBRE'] ?></option>
        <?php } ?>
    </select>
    <div><?php echo $depentidad != "" ? $depentidad : 'Seleccione Departamento' ?></div>
</div>
<br>
<div class="select" id="sCiu" >
    <select name="ciudad" id="inputCiudad" class="required" required>
        <option value=""> Seleccione Ciudad</option>
        <option value="<?= $data[0]['CIUDAD'] ?>" <?php if ($data[0]['CIUDAD'] != "") echo 'selected'; ?>> <?= $ciuentidad ?></option>
    </select>
    <div> <?php echo $ciuentidad != "" ? $ciuentidad : 'Seleccione Ciudad' ?></div>
</div>
<br>
<input type="text" class="textPatSt"  id="identificador" name="identificador" placeholder="Identificador tarjeta" autocomplete="nope" onselectstart="return false" onpaste="return false;" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false" hidden required>

<!--<div class="select">
    <select name="custodio" id="custodio" class="required" required>
        <option value=""> Asignar custodio</option>
<?php foreach ($custodios as $key => $value) { ?>
                <option value="<?= $value['CODIGOENTIDA'] ?>"> <?= $value['NOMBRE'] ?></option>
<?php } ?>
    </select>
    <div>Asignar custodio</div>
</div> -->
