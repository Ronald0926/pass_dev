
<style>
#inputDepartamento{
    text-align: left;
    border: none;
    border: 1px solid #888;
    padding: 4px 4px 4px 2px;
    border-radius: 15px;
    background-image: url(/static/img/portal/login/select1.jpg);
    background-size: 20px;
    background-position-x: 99%;
    background-position-y: 4px;
    background-repeat: no-repeat;
    padding-right: 10%;
    padding-left: 5px;
    -moz-appearance: none; 
    appearance: none; 
    text-indent: 0.01px; 
    position: initial;

}

#inputCiudad{
    text-align: left;
    border: none;
    border: 1px solid #888;
    padding: 4px 4px 4px 2px;
    border-radius: 15px;
    background-image: url(/static/img/portal/login/select1.jpg);
    background-size: 20px;
    background-position-x: 99%;
    background-position-y: 4px;
    background-repeat: no-repeat;
    padding-right: 10%;
    padding-left: 5px;
    -moz-appearance: none; 
    appearance: none; 
    text-indent: 0.01px; 
    position: initial;

}

</style>


<div class="col-md-2" ></div>
<div  class="col-md-8" style="margin-top: 5px; margin-bottom: 40px">
    <form action="" method="post" enctype="multipart/form-data">
        <div class="row" style="width: 100%; ">
            <div class="col-md-12 col-sm-11">
                <div class="row">

                    <?php
$rol = $_SESSION['rol']; //$this->session->userdata("rol");

$icono = $_SESSION['entidad']; //$this->session->userdata('entidad');
?>
                    <div id="icono" class="col-sm-2 icono-defecto" style="

                         position: relative;
                         background-image: url('<?=$icono['ICONO'] == '' ? '/static/img/portal/iconos/Iconos_Camara_azul.png' : $icono['ICONO']?>');
                         background-size: cover;
                         border-radius: 70px;
                         color: #FFF;
                         ">

                        <div id="iconoHover" style="
                             position: absolute;
                             top: 0px;
                             bottom: 50%;
                             left: 0px;
                             right: 0px;
                             display: none;
                             ">
                            <div style="
                                 position: absolute;
                                 top: 0px;
                                 bottom: 0px;
                                 left: 0px;
                                 right: 0px;
                                 background-color: #5e5e5e;
                                 opacity: 0.6;
                                 border-radius: 70px 70px 0px 0px;
                                 ">

                            </div>
                            <div style="
                                 position: relative;
                                 text-align: center;
                                 margin-top: 20px;
                                 ">
                                Cambiar
                                <br/>
                                foto
                            </div>
                        </div>
                        <input id="logoEntidad" type="file" name="logoEntidad" on  style="
                               position: absolute;
                               top: 0px;
                               bottom: 70px;
                               left: 0px;
                               right: 0px;
                               border: none;
                               cursor: pointer;
                               opacity: 0;
                               ">
                    </div>


                    <div class="col-sm-4 ">
                        <p class="lower titulo-actualizar" ><?=$entidad['NOMBREEMPRESA']?></p>
                        <p class="sub-actualizar" ><?=$entidad['NOMBRE'] . " :  " . $entidad['DOCUMENTO']?> </p>
                    </div>
                </div>
                <br/>
                <?php if (isset($_GET['error'])) {?>

                    <div class="col-sm-6 alert alert-danger ">
                        Error, no se puede cargar la imagen, debe tener maximo 300px de ancho, 300px de alto y no puede
                        pesar mas de 1MB.
                    </div>
                <?php }?>
                <?php if (isset($_GET['ok'])) {?>
                    <div class="col-sm-6 alert alert-success">
                        Se ha completado la acciòn.
                    </div>
                <?php }?>
            </div>

            <div class="col-md-12 col-sm-11">
                <div class="row">
                <?php // print_r($_SESSION)  ?>
                    <div class="col-md-6 col-sm-5 " >

                        <div class="row" style="padding-top: 0px; margin-bottom: 0px">
                        <div class="form-group">
                        <label for="exampleInputEmail1" style="
    color: #888;
">   Telefono:</label>
                            <input type="text" name="telefono" placeholder="telefono" value="<?=$telefono['DATO']?>" class="textPat" <?php if ($rol != 47) {
    echo 'disabled="disabled"';
}
?>><br>
                          </div>
                          <div class="form-group">
                          <label for="exampleInputEmail1" style="
    color: #888;
"> Email:</label>
                            <input type="email" name="correo" placeholder="correo" value="<?=$infoentida['CORREO_ELECTRONICO']?>" class="correoPat" <?php if ($rol != 47) {
    echo 'disabled="disabled"';
}
?>><br>
                             </div>
                             <div class="form-group">
                            <label style="
    color: #888;
"> Direccion:</label>
                            <input type="text" name="direccion" placeholder="Direccion" value="<?=$direccion?>" class="textPat" <?php if ($rol != 47) {
    echo 'disabled="disabled"';
}
?>>
                             </div>
                            <table style="width: 100%">
                                <tr>
                                    <td>
                                    <div class="form-group">
                                    <label style="
    color: #888;
"> Piso:</label>
                                        <input type="text" name="piso" placeholder="Piso" value="<?=$piso?>" class="textPat" <?php if ($rol != 47) {
    echo 'disabled="disabled"';
}
?>>
                </div>
                                    </td>
                                    <td style="padding-left: 5px">
                                    <div class="form-group">
                                    <label style="
    color: #888;
"> Edificio:</label>
                                        <input type="text" name="edificio" placeholder="Edificio" value="<?=$edificio?>" class="textPat" <?php if ($rol != 47) {
    echo 'disabled="disabled"';
}
?>>
                      </div>
                                    </td>
                                </tr>
                            </table>
                            <div class="form-group">
                            <label style="color: #888;"> Barrio:</label>
                            <input type="text" name="barrio" placeholder="Barrio" value="<?=$barrio?>" class="textPat" <?php if ($rol != 47) {
    echo 'disabled="disabled"';
}
?>>
                </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-5 form-group" style="padding-left: 40px ">
                        <div class="select form-group">
                        <label> Departamento:</label>

                        <select name="departamentos" id="inputDepartamento" class="required" <?php if($rol!=47) echo 'disabled="disabled"'?>style="opacity:1">
                                <option value=""> Seleccione Departamento</option>
                                <?php foreach ($departamentos as $key => $value) { ?>
                                    <option value="<?= $value['PK_DEP_CODIGO'] ?>" <?php if ($value['PK_DEP_CODIGO'] == $infoentida['DEPARTAMENTO']) echo 'selected'; ?>> <?= $value['NOMBRE'] ?></option>
                                <?php } ?>
                            </select>
                        

                       
                            
                        </div>
                        <div class="select form-group">
                        <label> Ciudad:</label>
                            <select name="ciudad" id="inputCiudad" class="required" <?php if ($rol != 47) {
    echo 'disabled="disabled"';
}
?> style="opacity:1">
                                <option value=""> Seleccione Ciudad</option>
                            </select>
                            
                        </div>
                        <?php if ($rol == 47) {?>
                        <div class="button" style="text-align: center; margin-top: 5px;">
                            <label class="subirlabel"  id="subir-CC"style="cursor:pointer">Subir Cámara de Comercio</label>
                            <input type="file" class="file" id='file_inputCELegal' style="display:none;" accept="application/pdf" />
                            <label style="padding-left: 10%; color: #1C5394; font-weight: normal; font-size: 10px;">Por favor tenga en cuenta que no debe ser mayor a 60 días</label>
                        </div>
                        <table style="width: 100%">
                            <tr>
                                <td style="width: 49%">

                                        <div class="button"  type="button">
                                            <label class="bajarlabel  icoBajar" id="downloadfuc" style="cursor:pointer;">Descargar FUC</label>
                                        </div>

                                    <input  type='hidden' name='numerocotizacion' class='numero_cotizacion' value='<?php echo $numerocotizacion ?>'>
                                </td>
                                <td style="text-align: center; padding-left: 5px;">
                                <input  type="file" name="FUC" class='fuc-file' id="file_inputfuc"  style="display:none;">
                                <label class="subirlabel icoSub" id="file_inputfucimg" style="cursor:pointer;">Subir FUC</label>
                                                                  </td>
                            </tr>
                        </table>
                         <label style="padding-left: 10%; color: #1C5394; font-weight: normal; font-size: 10px;">Debe tener en cuenta el tamaño del archivo maximo es 12MB</label>
                    <?php }?>
                    </div>
                </div>
            </div>
        </div>
        <?php if ($rol == 47) {?>
        <div class="row">
            <div class="button col-sm-4 col-sm-offset-3">
                <button type="submit">
                    Guardar
                </button>
            </div>
        </div>
        <?php }?>
    </form>
    <?php if ($rol == 47) {?>

     <form method="post" action="<?=base_url() . "portal/entidad/redirecionafuc"?>"method="post">
        <div class="row">
            <div class="button col-sm-4 col-sm-offset-3">
                <button type="button" id="redirecfuc">
                Actualización de información FUC
                </button>
            </div>
        </div>
      </form>
        <?php }?>
</div>

<div class="col-md-2"></div>

<script type="text/javascript">
    $(document).ready(function () {

        
        $.ajax({
            url: "/portal/ajax/ciudad/" + $('#inputDepartamento').val()
        })
                .done(function (msg) {
                    $('#inputCiudad').html(msg)
                    $('#inputCiudad').val(<?= $infoentida['CIUDAD'] ?>)
                });

        $('#inputDepartamento').change(function () {
            $('#inputCiudad').prop("disabled", false );
            $('#divCiudad').text("Seleccione Ciudad");
            $.ajax({
                url: "/portal/ajax/ciudad/" + $('#inputDepartamento').val()
            })
                    .done(function (msg) {

                        $('#inputCiudad').html(msg)
                       if($('#inputCiudad').val()!=''){
                        console.log($('#divCiudad').text());
                       } 
                      
                    });
        })

        //efecto hover para el logo
        $('#logoEntidad').mouseover(function () {
            $('#iconoHover').show(0);
        });
        $('#logoEntidad').mouseout(function () {
            $('#iconoHover').hide(0);
        });

     $("#redirecfuc").click(function(){
        $.post( "<?=base_url() . "portal/entidad/redirecionafuc"?>", )
         .done(function( data ) {
       //alert( "Data Loaded: " + data );
         console.log(data);
        window.open(data, '_blank');
  });

     })


$("#subir-CC").click(function(){

    $("#file_inputCELegal").trigger('click');
})

     $(".subirlabel.icoSub").click(function(){
         //alert('cargando archivo')
        $('.fuc-file').trigger('click');

    })

    $('#file_inputCELegal').change(function () {
        var fileInput = document.getElementById('file_inputCELegal');
        var numero_cotizacion = $('.numero_cotizacion').val();
        var file = fileInput.files[0];
        var fileName = file.name;
        var ext1 = fileName.lastIndexOf(".");
        var extensiones = fileName.substring(ext1).toLowerCase();
        if (extensiones != ".PDF" && extensiones != ".pdf") {
            alert("El formato " + extensiones + " no es valido, ingrese un .pdf");
            return false;
        } else {
            console.log("inicia documento");
            console.log(numero_cotizacion);
            var data = {"numero_cotizacion": numero_cotizacion, "tipo_archivo": '3'};

            idarchivo(data, file, numero_cotizacion);
        }


})

 $("#downloadfuc").click(function(){

  window.open( '<?php base_url()?>/uploads/fuc/fuc.pdf', '_blank');
 })


    $('#file_inputfuc').change(function () {
        var fileInput = document.getElementById('file_inputfuc');
        var numero_cotizacion = $('.numero_cotizacion').val();
        var file = fileInput.files[0];
        var fileName = file.name;
        var ext1 = fileName.lastIndexOf(".");
        var extensiones = fileName.substring(ext1).toLowerCase();
        if (extensiones != ".PDF" && extensiones != ".pdf") {
            alert("El formato " + extensiones + " no es valido, ingrese un .pdf");
            return false;
        } else {
            console.log("inicia documento");
            console.log(numero_cotizacion);
            var data = {"numero_cotizacion": numero_cotizacion, "tipo_archivo": '6'};

            idarchivo(data, file, numero_cotizacion);
        }


})


function idarchivo(data, file, numero_cotizacion) {
   // alert('idarchv');
        fetch('<?php base_url()?>/portal/entidad/enviardoc/', {
            method: 'POST',
            body: JSON.stringify(data),
            headers: {
                'Content-Type': 'application/json'
            }
        })
                .then(function (response) {
                    if (response.ok) {
                        return response.json()
                    } else {
                        throw "Error en la llamada Ajax";
                    }

                })
                .then(function (texto) {
                    //console.log(texto);
                    var formData = new FormData();

                    formData.append("file", file);
                    formData.append("codigo_archivo", texto[0]);
                    formData.append("proceso", numero_cotizacion);
                    var ipserver = texto[1];
                    var url = ipserver + '/wsonline2/updateFileLoaded/actualizar';
                    //console.log(url);
                    upload(url, formData)
                    //location.reload()

                    //proceso
                })
                .catch(function (err) {
                    console.log(err);
                });
    }



    function upload(url, formData) {
        $.ajax({
            url: url,
            type: "post",
            dataType: "html",
            data: formData,
            cache: false,
            contentType: false,
            processData: false
        })
                .done(function (res) {
                    //$("#mensaje").html("Respuesta: " + res);
                    //console.log(res);

                     //console.log("(-1): "     + res.substr(-1));    // '(-3): hij'
                    if(res.substr(-1)=='1'){
                        swal("EL Archivo Se Cargo Con Exito", "", "success");

                    }
                });

    }


    });




</script>