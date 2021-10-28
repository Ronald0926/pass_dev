<style type="text/css">
    .divContainer {
        height: 38px;  
    }

    .tableContainer {
        height:38px;  
    }
    .manu { 
        font-family: Arial,Helvetica Neue,Helvetica,sans-serif; 
        border-spacing:0;
        font-size: 14px;
    }
</style>
<html>
    <div> 
        <div style="margin:10px" class="">
            <table style="" class="table table-bordered manu" >
                
                <tr>
                    <td style="width: 38px"></td>
                    <td style="width: 38px"></td>
                    <td style="width: 38px"></td>
                    <td style="width: 38px"></td>
                    <td style="width: 38px"></td>
                    <td style="width: 38px"></td>
                    <td style="width: 38px"></td>
                    <td style="width: 38px"></td>
                    <td style="width: 38px"></td>
                    <td style="width: 38px"></td>
                    <td style="width: 38px"></td>
                    <td style="width: 38px"></td>
                    <td style="width: 38px"></td>
                    <td style="width: 38px"></td>
                    <td style="width: 38px"></td>
                    <td style="width: 38px"></td>
                    <td style="width: 38px"></td>
                    <td style="width: 38px"></td>
                    <td style="width: 38px"></td>
                    <td style="width: 38px"></td>
                    <td style="width: 38px"></td>
                    <td style="width: 38px"></td>
                    <td style="width: 38px"></td>
                    <td style="width: 38px"></td>
                    <td style="width: 38px"></td>
                    <td style="width: 38px"></td>
                    <td style="width: 38px"></td>
                    <td style="width: 38px"></td>
                </tr>
                <tr >
                    <td colspan="5" rowspan="4" style="border: 1px solid;margin-left: 15px">
                        <img src="/static/img/portal/LogoInterno01.png" width="160px">                                             
                    </td>
                    <td colspan="17" rowspan="4" style="font-weight: bold;text-align: center;border: 1px solid;font-size: 18px">
                        FORMATO &Uacute;NICO DE CONOCIMIENTO DEL CLIENTE <!--PERSONA JUR&Iacute;DICA-->
                    </td>
                    <td colspan="6" style="text-align: center;border: 1px solid">
                        FO-RC-01 
                    </td>
                </tr>
                <tr>
                    <td colspan="6" style="text-align: center;border: 1px solid">Versi&oacute;n: 3.2</td>
                </tr>
                <tr>
                    <td colspan="6" style="text-align: center; border-right: 1px solid;">Fecha de Edici&oacute;n:</td>
                </tr>
                <tr>
                    <td colspan="6" style="text-align: center;border-right: 1px solid;border-bottom: 1px solid;">17-Septiembre-2020</td>
                </tr>
                <tr>
                    <td colspan="28" style="font-size: 12px">Para nuestra compa&ntilde;a es muy importante contar con la informac&oacute;n completa, por lo anterior le solicitamos diligenciar 
                        este formato de manera clara y precisa, sin tachones, ni enmendaduras.</td>
                </tr>
                <tr>
                    <td colspan="6" style="font-weight: bold;border: 1px solid gray;">FECHA DE DILIGENCIAMIENTO </td>
                    <td colspan="1" style="border: 1px solid gray;">DIA</td>
                    <td colspan="1" style="border: 1px solid gray;"><?=$dia?></td>
                    <td colspan="1" style="border: 1px solid gray;">MES</td>
                    <td colspan="1" style="border: 1px solid gray;"><?=$mes?></td>
                    <td colspan="1" style="border: 1px solid gray;">A&Ntilde;O</td>
                    <td colspan="2" style="border: 1px solid gray;"><?=$ano?></td>
                    <td colspan="8" style="font-weight: bold;border: 1px solid gray;">N&Uacute;MERO DE OFERTA COMERCIAL</td>
                    <td colspan="7" style="border: 1px solid gray;"><?=$proceso?></td>
                </tr>
                <tr>
                    <td colspan="10" style="font-weight: bold;border: 1px solid gray">NOMBRE EJECUTIVO COMERCIAL</td>
                    <td colspan="18" style="border: 1px solid gray"><?=$comercial?></td>
                </tr>
                <tr>
                    <td colspan="28"style="border: 1px solid gray;"></td>
                </tr>
                <tr>
                    <td style="color:White;background-color:#3f51b5;font-weight: bold;text-align: center" colspan="28">DATOS SOLICITANTES PERSONA JURÍDICA</td>
                </tr>
                <tr>
                    <td style="color:White;background-color:#3f51b5;font-weight: bold;text-align: center" colspan="28">INFORMACI&Oacute;N GENERAL</td>
                </tr>
                <tr>
                    <td colspan="28" style="border: 1px solid gray;"></td>
                </tr>
                <tr>
                    <td colspan="22" style="font-weight: bold;border: 1px solid gray;text-align: center">RAZ&Oacute;N SOCIAL</td>
                    <td colspan="6" style="font-weight: bold;border: 1px solid gray;text-align: center">NIT</td>
                </tr>
                <tr>
                    <td colspan="22" style="border: 1px solid gray;"><?=$empresa['RAZON_SOCIAL']?></td>
                    <td colspan="6" style="border: 1px solid gray;"><?=$empresa['DOCUMENTO']?></td>
                </tr>
                <tr>
                    <td colspan="9" style="font-weight: bold;border: 1px solid gray;">DIRECCI&Oacute;N EMPRESA OFICINA PRINCIPAL</td>
                    <td colspan="6" style="font-weight: bold;border: 1px solid gray;">TEL&Eacute;FONO/FAX</td>
                    <td colspan="6" style="font-weight: bold;border: 1px solid gray;">CIUDAD/MUNICIPIO</td>
                    <td colspan="7" style="font-weight: bold;border: 1px solid gray;">DEPARTAMENTO</td>
                </tr>
                <tr>
                    <td colspan="9" style="border: 1px solid gray;"><?=$consultaDireccion['DATO']?></td>
                    <td colspan="6" style="border: 1px solid gray;"><?=$telefonooficina['DATO']?></td>
                    <td colspan="6" style="border: 1px solid gray;"><?=$consultaDireccion['CIUDAD']?></td>
                    <td colspan="7" style="border: 1px solid gray;"><?=$consultaDireccion['DEPARTAMENTO']?></td>
                </tr>
                <tr>
                    <td colspan="9" style="font-weight: bold;border: 1px solid gray;">CORREO ELECTR&Oacute;NICO PARA FACTURA ELECTRONICA</td>
                    <td colspan="19" style="border: 1px solid gray;"><?=$empresa['CORREO_ELECTRONICO']?></td>
                </tr>
                <tr>
                    <td colspan="10" style="font-weight: bold;border: 1px solid gray;text-align: center">TIPO DE EMPRESA</td>
                    <td colspan="18" style="font-weight: bold;border: 1px solid gray;text-align: center">TIPO DE SOCIEDAD</td>
                </tr>
                <tr>
                    <td colspan="10"  style="border: 1px solid gray;border-bottom: 0px">
                        <label>PRIVADA <?php if($datosjuridicos['CLITBLTIPEMP_PK_TIPEMP_CODIGO']==45) echo '<img src="/static/img/portal/check.png" width="12px">';else echo'<input type="checkbox">' ;?> </label>
                        <label>MIXTA <?php if($datosjuridicos['CLITBLTIPEMP_PK_TIPEMP_CODIGO']==46) echo '<img src="/static/img/portal/check.png" width="12px">';else echo'<input type="checkbox">' ;?> </label>
                        <label>P&Uacute;BLICA <?php if($datosjuridicos['CLITBLTIPEMP_PK_TIPEMP_CODIGO']==47) echo '<img src="/static/img/portal/check.png" width="12px">';else echo'<input type="checkbox">' ;?> </label>
                        <label>OTRO <?php if($datosjuridicos['CLITBLTIPEMP_PK_TIPEMP_CODIGO']==48) echo '<img src="/static/img/portal/check.png" width="12px">';else echo'<input type="checkbox">' ;?> </label>
                        
                    </td>
                    <td colspan="18"   style="border: 1px solid gray;border-bottom: 0px">
                        <label>ANONIMA <?php if($datosjuridicos['CLITBLTIPSOC_PK_TIPSOC_CODIGO']==78) echo '<img src="/static/img/portal/check.png" width="12px">';else echo'<input type="checkbox">' ;?> </label>
                        <label>SOCIEDAD COLECTIVA <?php if($datosjuridicos['CLITBLTIPSOC_PK_TIPSOC_CODIGO']==79) echo '<img src="/static/img/portal/check.png" width="12px">';else echo'<input type="checkbox">' ;?> </label>
                        <label>S.A.S <?php if($datosjuridicos['CLITBLTIPSOC_PK_TIPSOC_CODIGO']==80) echo '<img src="/static/img/portal/check.png" width="12px">';else echo'<input type="checkbox">' ;?> </label>
                        <label>LIMITADA <?php if($datosjuridicos['CLITBLTIPSOC_PK_TIPSOC_CODIGO']==81) echo '<img src="/static/img/portal/check.png" width="12px">';else echo'<input type="checkbox">' ;?> </label>
                        <label>SOC.COMANDITA SIMPLE <?php if($datosjuridicos['CLITBLTIPSOC_PK_TIPSOC_CODIGO']==82) echo '<img src="/static/img/portal/check.png" width="12px">';else echo'<input type="checkbox">' ;?> </label>
                        
                    </td>
                </tr>
                <tr>
                    <td colspan="10" style="border: 1px solid gray;border-top: 0px;">
                        CUAL  <?php if($datosjuridicos['CLITBLTIPEMP_PK_TIPEMP_CODIGO']==48) echo $datosjuridicos['TIPEMP_OTRO'];?> 
                    </td>
                    <td colspan="18" style="border: 1px solid gray;border-top: 0px;">
                        <label>SOC.COMANDITA ACCIONES <?php if($datosjuridicos['CLITBLTIPSOC_PK_TIPSOC_CODIGO']==83) echo '<img src="/static/img/portal/check.png" width="12px">';else echo'<input type="checkbox">' ;?> </label>
                        <label>ESAL/OTRA <?php if($datosjuridicos['CLITBLTIPSOC_PK_TIPSOC_CODIGO']==84) echo '<img src="/static/img/portal/check.png" width="12px">';else echo'<input type="checkbox">' ;?></label>
                        <label>CUAL  <?php if($datosjuridicos['CLITBLTIPSOC_PK_TIPSOC_CODIGO']==84) echo $datosjuridicos['TIPSOC_OTRO'];?></label>
                    </td>
                </tr>
                 <tr>
                    <td colspan="4" style="font-weight: bold;border: 1px solid gray;text-align: center;">TIPO R&Eacute;GIMEN </td>
                    <td colspan="24" style="border: 1px solid gray;">
                        <label>GRAN CONTRIBUYENTE <?php if($regimen['PK_TIPREG_CODIGO']==1) echo '<img src="/static/img/portal/check.png" width="12px">';else echo'<input type="checkbox">' ;?></label> 
                        <label>R&Eacute;GIMEN COM&Uacute;N <?php if($regimen['PK_TIPREG_CODIGO']==2) echo '<img src="/static/img/portal/check.png" width="12px">';else echo'<input type="checkbox">' ;?></label> 
                        <label>R&Eacute;GIMEN SIMPLIFICADO <?php if($regimen['PK_TIPREG_CODIGO']==3) echo '<img src="/static/img/portal/check.png" width="12px">';else echo'<input type="checkbox">' ;?></label> 
                        <label>ENTIDAD SIN ANIMO DE LUCRO <?php if($regimen['PK_TIPREG_CODIGO']==4) echo '<img src="/static/img/portal/check.png" width="12px">';else echo'<input type="checkbox">' ;?></label> 
                        <label>PERSONA NATURAL <?php if($regimen['PK_TIPREG_CODIGO']==5) echo '<img src="/static/img/portal/check.png" width="12px">';else echo'<input type="checkbox">' ;?></label> 
                    </td>    
                </tr>
                <tr>
                    <td colspan="6" style="font-weight: bold;border: 1px solid gray;text-align: center;">ACTIVIDAD ECON&Oacute;MICA</td>
                    <td colspan="18" style="border: 1px solid gray;text-align: center;"> <?=$ciiu['ACTIVIDAD_ECONIMICA']?></td>
                    <td colspan="3" style="font-weight: bold;border: 1px solid gray;text-align: center;">C&Oacute;DIGO CIIU </td>
                    <td colspan="1" style="border: 1px solid gray;text-align: center;"><?=$ciiu['CODIGO_CIIU']?></td>
                </tr>
               
<!--                <tr>
                    <td colspan="24" style="border: 1px solid gray;">
                        <label>INDUSTRIAL<input type="checkbox" value=""> </label>
                        <label>COMERCIAL<input type="checkbox"  value=""> </label>
                        <label>TRANSPORTE<input type="checkbox"  value=""> </label>
                        <label>CONSTRUCCI&Oacute;N<input type="checkbox"  value=""> </label>
                        <label>AGRICOLA<input type="checkbox"  value=""> </label>
                        <label>EDUCACI&Oacute;N<input type="checkbox"  value=""> </label>
                        <label>SERV. FINANCIEROS<input type="checkbox"  value=""> </label>
                    </td>
                    <td colspan="4" rowspan="2" style="border: 1px solid gray;"></td>
                </tr>-->
<!--                <tr>
                    <td colspan="24" style="border: 1px solid gray;">
                        <label>OTRO<input type="checkbox" value=""> </label>CUAL</td>
                </tr>-->
                <tr>
                    <td colspan="4" rowspan="9" style="font-weight: bold;border: 1px solid gray;text-align: center">
                        INFORMACI&Oacute;N<br/>REPRESENTANTE<br/>LEGAL
                    </td>
                    <td colspan="6" style="font-weight: bold;border: 1px solid gray;text-align: center;">
                        PRIMER APELLIDO
                    </td>
                    <td colspan="6" style="font-weight: bold;border: 1px solid gray;text-align: center;">
                        SEGUNDO APELLIDO
                    </td>
                    <td colspan="6" style="font-weight: bold;border: 1px solid gray;text-align: center;">
                        PRIMER NOMBRE
                    </td>
                    <td colspan="6" style="font-weight: bold;border: 1px solid gray;text-align: center;">
                        SEGUNDO NOMBRE
                    </td>
                </tr>
                <tr>
                    <td colspan="6" style="border: 1px solid gray;">
                        <?=$rep['PRIMER_APELLIDO']?>
                    </td>
                    <td colspan="6" style="border: 1px solid gray;">
                        <?=$rep['SEGUNDO_APELLIDO']?>
                    </td>
                    <td colspan="6" style="border: 1px solid gray;">
                        <?=$rep['PRIMER_NOMBRE']?>
                    </td>
                    <td colspan="6" style="border: 1px solid gray;">
                        <?=$rep['SEGUNDO_NOMBRE']?>
                    </td>
                </tr>
                <tr>
                    <td colspan="9" style="font-weight: bold;border: 1px solid gray;text-align: center;">
                        TIPO DE DOCUMENTO
                    </td>
                    <td colspan="5" style="font-weight: bold;border: 1px solid gray;text-align: center;">
                        N&Uacute;MERO
                    </td>
                    <td colspan="10" style="font-weight: bold;border: 1px solid gray;text-align: center;">
                        CORREO ELECTR&Oacute;NICO
                    </td>
                </tr>
                <tr>
                    <td colspan="9" style="border: 1px solid gray;">
                        <label>CC <?php if($rep2['PKTIPODOC']==68) echo '<img src="/static/img/portal/check.png" width="12px">';else echo'<input type="checkbox">' ;?> </label>
                        <label>C.E <?php if($rep2['PKTIPODOC']==70) echo '<img src="/static/img/portal/check.png" width="12px">';else echo'<input type="checkbox">' ;?> </label>
                        <label>PASAPORTE <?php if($rep2['PKTIPODOC']==69) echo '<img src="/static/img/portal/check.png" width="12px">';else echo'<input type="checkbox">' ;?> </label>
                    </td>
                    <td colspan="5" style="border: 1px solid gray;">
                        <?=$rep2['DOCUMENTO']?>
                    </td>
                    <td colspan="10" style="border: 1px solid gray;">
                        <?=$rep2['CORREO_ELECTRONICO']?>
                    </td>
                </tr>
                <tr>
<!--                    <td colspan="7" style="font-weight: bold;border: 1px solid gray;text-align: center;">
                        NACIONALIDAD
                    </td>-->
                    <td colspan="17" style="font-weight: bold;border: 1px solid gray;text-align: center;">
                        DIRECCI&Oacute;N DE RESIDENCIA
                    </td>
                    <td colspan="7" style="font-weight: bold;border: 1px solid gray;text-align: center;">
                        TEL&Eacute;FONO DE CONTACTO
                    </td>
                </tr>
                <tr>
<!--                    <td colspan="7" style="border: 1px solid gray;">
                        
                    </td>-->
                    <td colspan="17" style="border: 1px solid gray;">
                        <?=$consultaDireccionRep['DATO']?>
                    </td>
                    <td colspan="7" style="border: 1px solid gray;">
                        <?=$consultaMovilRep['DATO']?>
                    </td>
                </tr>
                <tr>
<!--                    <td colspan="9" style="font-weight: bold;border: 1px solid gray;text-align: center;">
                        CORREO ELECTR&Oacute;NICO
                    </td>-->
                    <td colspan="8" style="font-weight: bold;border: 1px solid gray;text-align: center;">
                        PA&Iacute;S DE RESIDENCIA
                    </td>
                    <td colspan="8" style="font-weight: bold;border: 1px solid gray;text-align: center;">
                        DEPARTAMENTO DE RESIDENCIA
                    </td>
                    <td colspan="8" style="font-weight: bold;border: 1px solid gray;text-align: center;">
                        CIUDAD DE RESIDENCIA
                    </td>
                </tr>
                <tr>
<!--                    <td colspan="9" style="border: 1px solid gray;">
                        <?=$rep2['CORREO_ELECTRONICO']?>
                    </td>-->
                    <td colspan="8" style="border: 1px solid gray;">
                        <?=$rep2['PAIS']?>
                    </td>
                    <td colspan="8" style="border: 1px solid gray;">
                        <?=$rep2['DEPARTAMENTO']?>
                    </td>
                    <td colspan="8" style="border: 1px solid gray;">
                        <?=$rep2['LUGAREXP']?>
                    </td>
                </tr>
<!--                <tr>
                    <td colspan="11" style="border-top: 1px solid gray;border-bottom: 1px solid gray;border-left: 1px solid gray;">
                        &iquest;Por su cargo o actividad maneja recursos p&uacute;blicos?
                    </td>
                    <td colspan="13" style="border-top: 1px solid gray;border-bottom: 1px solid gray;border-right:  1px solid gray;">
                        <label>SI <?php if($preguntasRep[0]['RESPUESTA']=='SI') echo '<img src="/static/img/portal/check.png" width="12px">';else echo'<input type="checkbox">' ;?> </label>
                        <label>NO <?php if($preguntasRep[0]['RESPUESTA']!='SI') echo '<img src="/static/img/portal/check.png" width="12px">';else echo'<input type="checkbox">' ;?> </label>
                    </td>
                </tr>
                <tr>
                    <td colspan="11" style="border-top: 1px solid gray;border-bottom: 1px solid gray;border-left: 1px solid gray;">
                        &iquest;Por su cargo o actividad ejerce alg&uacute;n grado de poder p&uacute;blico?
                    </td>
                    <td colspan="13" style="border-top: 1px solid gray;border-bottom: 1px solid gray;border-right:  1px solid gray;">
                        <label>SI <?php if($preguntasRep[1]['RESPUESTA']=='SI') echo '<img src="/static/img/portal/check.png" width="12px">';else echo'<input type="checkbox">' ;?> </label>
                        <label>NO <?php if($preguntasRep[1]['RESPUESTA']!='SI') echo '<img src="/static/img/portal/check.png" width="12px">';else echo'<input type="checkbox">' ;?> </label>
                    </td>
                </tr>
                <tr>
                    <td colspan="13" style="border-top: 1px solid gray;border-bottom: 1px solid gray;border-left: 1px solid gray;">
                        &iquest;Por su actividad u oficio,goza usted de reconocimiento p&uacute;blico general?
                    </td>
                    <td colspan="11" style="border-top: 1px solid gray;border-bottom: 1px solid gray;border-right:  1px solid gray;">
                        <label>SI <?php if($preguntasRep[2]['RESPUESTA']=='SI') echo '<img src="/static/img/portal/check.png" width="12px">';else echo'<input type="checkbox">' ;?> </label>
                        <label>NO <?php if($preguntasRep[2]['RESPUESTA']!='SI') echo '<img src="/static/img/portal/check.png" width="12px">';else echo'<input type="checkbox">' ;?> </label>
                    </td>
                </tr>
                <tr>
                    <td colspan="14" style="border-top: 1px solid gray;border-bottom: 1px solid gray;border-left: 1px solid gray;">
                        &iquest;Existe alg&uacute;n v&iacute;nculo entre usted y una persona considera p&uacute;blicamente expuesta?
                    </td>
                    <td colspan="10" style="border-top: 1px solid gray;border-bottom: 1px solid gray;border-right:  1px solid gray;">
                        <label>SI <?php if($preguntasRep[3]['RESPUESTA']=='SI') echo '<img src="/static/img/portal/check.png" width="12px">';else echo'<input type="checkbox">' ;?> </label>
                        <label>NO <?php if($preguntasRep[3]['RESPUESTA']!='SI') echo '<img src="/static/img/portal/check.png" width="12px">';else echo'<input type="checkbox">' ;?> Indique: <?=$preguntasRep[3]['INDICAR']?></label>
                    </td>
                </tr>
                <tr>
                    <td colspan="13" style="border-top: 1px solid gray;border-bottom: 1px solid gray;border-left: 1px solid gray;">
                        &iquest;Es usted sujeto de obligaciones tributarias en otro pa&iacute;s o grupo de pa&iacute;ses?
                    </td>
                    <td colspan="11" style="border-top: 1px solid gray;border-bottom: 1px solid gray;border-right:  1px solid gray;">
                        <label>SI <?php if($preguntasRep[4]['RESPUESTA']=='SI') echo '<img src="/static/img/portal/check.png" width="12px">';else echo'<input type="checkbox">' ;?> </label>
                        <label>NO <?php if($preguntasRep[4]['RESPUESTA']!='SI') echo '<img src="/static/img/portal/check.png" width="12px">';else echo'<input type="checkbox">' ;?> Indique: <?=$preguntasRep[4]['INDICAR']?></label>
                    </td>
                </tr>-->
                <tr>
                    <td colspan="28">

                    </td>
                </tr>
                <tr>
                    <td colspan="28" style="color:White;background-color:#3f51b5;font-weight: bold;text-align: center">
                        IDENTIFICACI&Oacute;N DE LOS ACCIONISTAS O SOCIOS QUE TENGAN PARTICIPACI&Oacute;N MAYOR O IGUAL AL 5% DEL CAPITAL O APORTE (en caso de requerirse m&aacute;s espacio debe anexarse la relaci&oacute;n)
                    </td>
                </tr>
                <tr>
                    <td colspan="28">

                    </td>
                </tr>
                <tr>
                    <td colspan="1" style="font-weight: bold;border: 1px solid gray;text-align: center;">N&deg;</td>
                    <td colspan="4" style="font-weight: bold;border: 1px solid gray;text-align: center;">TIPO DOCUMENTO</td>
                    <td colspan="4" style="font-weight: bold;border: 1px solid gray;text-align: center;">N&Uacute;MERO</td>
                    <td colspan="9" style="font-weight: bold;border: 1px solid gray;text-align: center;">NOMBRES Y APELLIDOS</td>
                    <td colspan="5" style="font-weight: bold;border: 1px solid gray;text-align: center;">PARTICIPACI&Oacute;N</td>
                    <td colspan="5" style="font-weight: bold;border: 1px solid gray;text-align: center;">NACIONALIDAD</td>
                </tr>
                <tr>
                    <td colspan="1" style="font-weight: bold;border: 1px solid gray;text-align: center;">1</td>
                    <td colspan="4" style="border: 1px solid gray;"><?=$consultaAccionistas[0]['TIPODOCUMENTO']?></td>
                    <td colspan="4" style="border: 1px solid gray;"><?=$consultaAccionistas[0]['DOCUMENTO']?></td>
                    <td colspan="9" style="border: 1px solid gray;"><?=$consultaAccionistas[0]['NOMBRE']?> <?=$consultaAccionistas[0]['APELLIDOS']?></td>
                    <td colspan="5" style="border: 1px solid gray;"><?=$consultaAccionistas[0]['PORCENTAJE_PARTICIPACION']?></td>
                    <td colspan="5" style="border: 1px solid gray;"><?=$consultaAccionistas[0]['NACIONALIDAD']?></td>
                </tr>
<!--                <tr>
                    <td colspan="5" style="border: 1px solid gray;text-align: center">Maneja recursos p&uacute;blicos</td>
                    <td colspan="7" style="border: 1px solid gray;text-align: center">Ejerce alg&uacute;n grado de poder p&uacute;blico</td>
                    <td colspan="6" style="border: 1px solid gray;text-align: center">Tiene reconocimiento p&uacute;blico</td>
                    <td colspan="10" style="border: 1px solid gray;text-align: center">Tiene obligaciones tributarias en otro pais</td>
                </tr>-->
                <?php 
                    foreach ($consultaPreguntasAccionistas as $consultaPreguntasAccionistasItem){
                        if($consultaAccionistas[0]['PK_DATNAT_CODIGO']==$consultaPreguntasAccionistasItem['CLITBLDATNAT_PK_DATNAT_CODIGO']){
                            if($consultaPreguntasAccionistasItem['PK_PREGVER_CODIGO']==165){
                                $rtaAccionista11 = $consultaPreguntasAccionistasItem['RESPUESTA'];
                            }
                            if($consultaPreguntasAccionistasItem['PK_PREGVER_CODIGO']==166){
                                $rtaAccionista12 = $consultaPreguntasAccionistasItem['RESPUESTA'];
                            }
                            if($consultaPreguntasAccionistasItem['PK_PREGVER_CODIGO']==173){
                                $rtaAccionista13 = $consultaPreguntasAccionistasItem['RESPUESTA'];
                            }
                            if($consultaPreguntasAccionistasItem['PK_PREGVER_CODIGO']==167){
                                $rtaAccionista14 = $consultaPreguntasAccionistasItem['RESPUESTA'];
                                $rtaAccionista14Ind = $consultaPreguntasAccionistasItem['INDICAR'];
                            }
                        }
                        if($consultaAccionistas[1]['PK_DATNAT_CODIGO']==$consultaPreguntasAccionistasItem['CLITBLDATNAT_PK_DATNAT_CODIGO']){
                            if($consultaPreguntasAccionistasItem['PK_PREGVER_CODIGO']==165){
                                $rtaAccionista21 = $consultaPreguntasAccionistasItem['RESPUESTA'];
                            }
                            if($consultaPreguntasAccionistasItem['PK_PREGVER_CODIGO']==166){
                                $rtaAccionista22 = $consultaPreguntasAccionistasItem['RESPUESTA'];
                            }
                            if($consultaPreguntasAccionistasItem['PK_PREGVER_CODIGO']==173){
                                $rtaAccionista23 = $consultaPreguntasAccionistasItem['RESPUESTA'];
                            }
                            if($consultaPreguntasAccionistasItem['PK_PREGVER_CODIGO']==167){
                                $rtaAccionista24 = $consultaPreguntasAccionistasItem['RESPUESTA'];
                                $rtaAccionista24Ind = $consultaPreguntasAccionistasItem['INDICAR'];
                            }
                        }
                        if($consultaAccionistas[2]['PK_DATNAT_CODIGO']==$consultaPreguntasAccionistasItem['CLITBLDATNAT_PK_DATNAT_CODIGO']){
                            if($consultaPreguntasAccionistasItem['PK_PREGVER_CODIGO']==165){
                                $rtaAccionista31 = $consultaPreguntasAccionistasItem['RESPUESTA'];
                            }
                            if($consultaPreguntasAccionistasItem['PK_PREGVER_CODIGO']==166){
                                $rtaAccionista32 = $consultaPreguntasAccionistasItem['RESPUESTA'];
                            }
                            if($consultaPreguntasAccionistasItem['PK_PREGVER_CODIGO']==173){
                                $rtaAccionista33 = $consultaPreguntasAccionistasItem['RESPUESTA'];
                            }
                            if($consultaPreguntasAccionistasItem['PK_PREGVER_CODIGO']==167){
                                $rtaAccionista34 = $consultaPreguntasAccionistasItem['RESPUESTA'];
                                $rtaAccionista34Ind = $consultaPreguntasAccionistasItem['INDICAR'];
                            }
                        }
                        if($consultaAccionistas[3]['PK_DATNAT_CODIGO']==$consultaPreguntasAccionistasItem['CLITBLDATNAT_PK_DATNAT_CODIGO']){
                            if($consultaPreguntasAccionistasItem['PK_PREGVER_CODIGO']==165){
                                $rtaAccionista41 = $consultaPreguntasAccionistasItem['RESPUESTA'];
                            }
                            if($consultaPreguntasAccionistasItem['PK_PREGVER_CODIGO']==166){
                                $rtaAccionista42 = $consultaPreguntasAccionistasItem['RESPUESTA'];
                            }
                            if($consultaPreguntasAccionistasItem['PK_PREGVER_CODIGO']==173){
                                $rtaAccionista43 = $consultaPreguntasAccionistasItem['RESPUESTA'];
                            }
                            if($consultaPreguntasAccionistasItem['PK_PREGVER_CODIGO']==167){
                                $rtaAccionista44 = $consultaPreguntasAccionistasItem['RESPUESTA'];
                                $rtaAccionista44Ind = $consultaPreguntasAccionistasItem['INDICAR'];
                            }
                        }
                    }
                ?>
<!--                <tr>
                    <td colspan="1" style="border-bottom: 1px solid gray;border-top: 1px solid gray;border-left: 1px solid gray;"></td>
                    <td colspan="4" style="border-bottom: 1px solid gray;border-top: 1px solid gray;border-right: 1px solid gray;">
                        <label>SI <?php if($rtaAccionista11=="S") echo '<img src="/static/img/portal/check.png" width="12px">';else echo'<input type="checkbox">' ;?> </label>
                        <label>NO <?php if($rtaAccionista11=="N") echo '<img src="/static/img/portal/check.png" width="12px">';else echo'<input type="checkbox">' ;?> </label>
                    </td>
                    <td colspan="7" style="border: 1px solid gray;">
                        <label>SI <?php if($rtaAccionista12=="S") echo '<img src="/static/img/portal/check.png" width="12px">';else echo'<input type="checkbox">' ;?> </label>
                        <label>NO <?php if($rtaAccionista12=="N") echo '<img src="/static/img/portal/check.png" width="12px">';else echo'<input type="checkbox">' ;?> </label>
                    </td>
                    <td colspan="6" style="border: 1px solid gray;">
                        <label>SI <?php if($rtaAccionista13=="S") echo '<img src="/static/img/portal/check.png" width="12px">';else echo'<input type="checkbox">' ;?> </label>
                        <label>NO <?php if($rtaAccionista13=="N") echo '<img src="/static/img/portal/check.png" width="12px">';else echo'<input type="checkbox">' ;?> </label>
                    </td>
                    <td colspan="10" style="border: 1px solid gray;">
                        <label>SI <?php if($rtaAccionista14=="S") echo '<img src="/static/img/portal/check.png" width="12px">';else echo'<input type="checkbox">' ;?> </label>
                        <label>NO <?php if($rtaAccionista14=="N") echo '<img src="/static/img/portal/check.png" width="12px">';else echo'<input type="checkbox">' ;?> Indique: <?=$rtaAccionista14Ind?></label>
                    </td>
                </tr>-->
                <tr>
                    <td colspan="1" style="font-weight: bold;border: 1px solid gray;text-align: center;">N&deg;</td>
                    <td colspan="4" style="font-weight: bold;border: 1px solid gray;text-align: center;">TIPO DOCUMENTO</td>
                    <td colspan="4" style="font-weight: bold;border: 1px solid gray;text-align: center;">N&Uacute;MERO</td>
                    <td colspan="9" style="font-weight: bold;border: 1px solid gray;text-align: center;">NOMBRES Y APELLIDOS</td>
                    <td colspan="5" style="font-weight: bold;border: 1px solid gray;text-align: center;">PARTICIPACI&Oacute;N</td>
                    <td colspan="5" style="font-weight: bold;border: 1px solid gray;text-align: center;">NACIONALIDAD</td>
                </tr>
                <tr>
                    <td colspan="1" style="font-weight: bold;border: 1px solid gray;text-align: center;">2</td>
                    <td colspan="4" style="border: 1px solid gray;"><?=$consultaAccionistas[1]['TIPODOCUMENTO']?></td>
                    <td colspan="4" style="border: 1px solid gray;"><?=$consultaAccionistas[1]['DOCUMENTO']?></td>
                    <td colspan="9" style="border: 1px solid gray;"><?=$consultaAccionistas[1]['NOMBRE']?> <?=$consultaAccionistas[1]['APELLIDOS']?></td>
                    <td colspan="5" style="border: 1px solid gray;"><?=$consultaAccionistas[1]['PORCENTAJE_PARTICIPACION']?></td>
                    <td colspan="5" style="border: 1px solid gray;"><?=$consultaAccionistas[1]['NACIONALIDAD']?></td>
                </tr>
<!--                <tr>
                    <td colspan="5" style="border: 1px solid gray;text-align: center">Maneja recursos p&uacute;blicos</td>
                    <td colspan="7" style="border: 1px solid gray;text-align: center">Ejerce alg&uacute;n grado de poder p&uacute;blico</td>
                    <td colspan="6" style="border: 1px solid gray;text-align: center">Tiene reconocimiento p&uacute;blico</td>
                    <td colspan="10" style="border: 1px solid gray;text-align: center">Tiene obligaciones tributarias en otro pais</td>
                </tr>-->
<!--                <tr>
                    <td colspan="1" style="border-bottom: 1px solid gray;border-top: 1px solid gray;border-left: 1px solid gray;"></td>
                    <td colspan="4" style="border-bottom: 1px solid gray;border-top: 1px solid gray;border-right: 1px solid gray;">
                        <label>SI <?php if($rtaAccionista21=="S") echo '<img src="/static/img/portal/check.png" width="12px">';else echo'<input type="checkbox">' ;?> </label>
                        <label>NO <?php if($rtaAccionista21=="N") echo '<img src="/static/img/portal/check.png" width="12px">';else echo'<input type="checkbox">' ;?> </label>
                    </td>
                    <td colspan="7" style="border: 1px solid gray;">
                        <label>SI <?php if($rtaAccionista22=="S") echo '<img src="/static/img/portal/check.png" width="12px">';else echo'<input type="checkbox">' ;?> </label>
                        <label>NO <?php if($rtaAccionista22=="N") echo '<img src="/static/img/portal/check.png" width="12px">';else echo'<input type="checkbox">' ;?> </label>
                    </td>
                    <td colspan="6" style="border: 1px solid gray;">
                        <label>SI <?php if($rtaAccionista23=="S") echo '<img src="/static/img/portal/check.png" width="12px">';else echo'<input type="checkbox">' ;?> </label>
                        <label>NO <?php if($rtaAccionista23=="N") echo '<img src="/static/img/portal/check.png" width="12px">';else echo'<input type="checkbox">' ;?> </label>
                    </td>
                    <td colspan="10" style="border: 1px solid gray;">
                        <label>SI <?php if($rtaAccionista24=="S") echo '<img src="/static/img/portal/check.png" width="12px">';else echo'<input type="checkbox">' ;?> </label>
                        <label>NO <?php if($rtaAccionista24=="N") echo '<img src="/static/img/portal/check.png" width="12px">';else echo'<input type="checkbox">' ;?> Indique: <?=$rtaAccionista24Ind?></label>
                    </td>
                </tr>-->
                <tr>
                    <td colspan="1" style="font-weight: bold;border: 1px solid gray;text-align: center;">N&deg;</td>
                    <td colspan="4" style="font-weight: bold;border: 1px solid gray;text-align: center;">TIPO DOCUMENTO</td>
                    <td colspan="4" style="font-weight: bold;border: 1px solid gray;text-align: center;">N&Uacute;MERO</td>
                    <td colspan="9" style="font-weight: bold;border: 1px solid gray;text-align: center;">NOMBRES Y APELLIDOS</td>
                    <td colspan="5" style="font-weight: bold;border: 1px solid gray;text-align: center;">PARTICIPACI&Oacute;N</td>
                    <td colspan="5" style="font-weight: bold;border: 1px solid gray;text-align: center;">NACIONALIDAD</td>
                </tr>
                <tr>
                    <td colspan="1" style="font-weight: bold;border: 1px solid gray;text-align: center;">3</td>
                    <td colspan="4" style="border: 1px solid gray;"><?=$consultaAccionistas[2]['TIPODOCUMENTO']?></td>
                    <td colspan="4" style="border: 1px solid gray;"><?=$consultaAccionistas[2]['DOCUMENTO']?></td>
                    <td colspan="9" style="border: 1px solid gray;"><?=$consultaAccionistas[2]['NOMBRE']?> <?=$consultaAccionistas[2]['APELLIDOS']?></td>
                    <td colspan="5" style="border: 1px solid gray;"><?=$consultaAccionistas[2]['PORCENTAJE_PARTICIPACION']?></td>
                    <td colspan="5" style="border: 1px solid gray;"><?=$consultaAccionistas[2]['NACIONALIDAD']?></td>
                </tr>
<!--                <tr>
                    <td colspan="5" style="border: 1px solid gray;text-align: center">Maneja recursos p&uacute;blicos</td>
                    <td colspan="7" style="border: 1px solid gray;text-align: center">Ejerce alg&uacute;n grado de poder p&uacute;blico</td>
                    <td colspan="6" style="border: 1px solid gray;text-align: center">Tiene reconocimiento p&uacute;blico</td>
                    <td colspan="10" style="border: 1px solid gray;text-align: center">Tiene obligaciones tributarias en otro pais</td>
                </tr>
                <tr>
                    <td colspan="1" style="border-bottom: 1px solid gray;border-top: 1px solid gray;border-left: 1px solid gray;"></td>
                    <td colspan="4" style="border-bottom: 1px solid gray;border-top: 1px solid gray;border-right: 1px solid gray;">
                        <label>SI <?php if($rtaAccionista31=="S") echo '<img src="/static/img/portal/check.png" width="12px">';else echo'<input type="checkbox">' ;?> </label>
                        <label>NO <?php if($rtaAccionista31=="N") echo '<img src="/static/img/portal/check.png" width="12px">';else echo'<input type="checkbox">' ;?> </label>
                    </td>
                    <td colspan="7" style="border: 1px solid gray;">
                        <label>SI <?php if($rtaAccionista32=="S") echo '<img src="/static/img/portal/check.png" width="12px">';else echo'<input type="checkbox">' ;?> </label>
                        <label>NO <?php if($rtaAccionista32=="N") echo '<img src="/static/img/portal/check.png" width="12px">';else echo'<input type="checkbox">' ;?> </label>
                    </td>
                    <td colspan="6" style="border: 1px solid gray;">
                        <label>SI <?php if($rtaAccionista33=="S") echo '<img src="/static/img/portal/check.png" width="12px">';else echo'<input type="checkbox">' ;?> </label>
                        <label>NO <?php if($rtaAccionista33=="N") echo '<img src="/static/img/portal/check.png" width="12px">';else echo'<input type="checkbox">' ;?> </label>
                    </td>
                    <td colspan="10" style="border: 1px solid gray;">
                        <label>SI <?php if($rtaAccionista34=="S") echo '<img src="/static/img/portal/check.png" width="12px">';else echo'<input type="checkbox">' ;?> </label>
                        <label>NO <?php if($rtaAccionista34=="N") echo '<img src="/static/img/portal/check.png" width="12px">';else echo'<input type="checkbox">' ;?> Indique: <?=$rtaAccionista34Ind?></label>
                    </td>
                </tr>-->
                <tr>
                    <td colspan="1" style="font-weight: bold;border: 1px solid gray;text-align: center;">N&deg;</td>
                    <td colspan="4" style="font-weight: bold;border: 1px solid gray;text-align: center;">TIPO DOCUMENTO</td>
                    <td colspan="4" style="font-weight: bold;border: 1px solid gray;text-align: center;">N&Uacute;MERO</td>
                    <td colspan="9" style="font-weight: bold;border: 1px solid gray;text-align: center;">NOMBRES Y APELLIDOS</td>
                    <td colspan="5" style="font-weight: bold;border: 1px solid gray;text-align: center;">PARTICIPACI&Oacute;N</td>
                    <td colspan="5" style="font-weight: bold;border: 1px solid gray;text-align: center;">NACIONALIDAD</td>
                </tr>
                <tr>
                    <td colspan="1" style="font-weight: bold;border: 1px solid gray;text-align: center;">4</td>
                    <td colspan="4" style="border: 1px solid gray;"><?=$consultaAccionistas[3]['TIPODOCUMENTO']?></td>
                    <td colspan="4" style="border: 1px solid gray;"><?=$consultaAccionistas[3]['DOCUMENTO']?></td>
                    <td colspan="9" style="border: 1px solid gray;"><?=$consultaAccionistas[3]['NOMBRE']?> <?=$consultaAccionistas[3]['APELLIDOS']?></td>
                    <td colspan="5" style="border: 1px solid gray;"><?=$consultaAccionistas[3]['PORCENTAJE_PARTICIPACION']?></td>
                    <td colspan="5" style="border: 1px solid gray;"><?=$consultaAccionistas[3]['NACIONALIDAD']?></td>
                </tr>
<!--                <tr>
                    <td colspan="5" style="border: 1px solid gray;text-align: center">Maneja recursos p&uacute;blicos</td>
                    <td colspan="7" style="border: 1px solid gray;text-align: center">Ejerce alg&uacute;n grado de poder p&uacute;blico</td>
                    <td colspan="6" style="border: 1px solid gray;text-align: center">Tiene reconocimiento p&uacute;blico</td>
                    <td colspan="10" style="border: 1px solid gray;text-align: center">Tiene obligaciones tributarias en otro pais</td>
                </tr>
                <tr>
                    <td colspan="1" style="border-bottom: 1px solid gray;border-top: 1px solid gray;border-left: 1px solid gray;"></td>
                    <td colspan="4" style="border-bottom: 1px solid gray;border-top: 1px solid gray;border-right: 1px solid gray;">
                        <label>SI <?php if($rtaAccionista41=="S") echo '<img src="/static/img/portal/check.png" width="12px">';else echo'<input type="checkbox">' ;?> </label>
                        <label>NO <?php if($rtaAccionista41=="N") echo '<img src="/static/img/portal/check.png" width="12px">';else echo'<input type="checkbox">' ;?> </label>
                    </td>
                    <td colspan="7" style="border: 1px solid gray;">
                        <label>SI <?php if($rtaAccionista42=="S") echo '<img src="/static/img/portal/check.png" width="12px">';else echo'<input type="checkbox">' ;?> </label>
                        <label>NO <?php if($rtaAccionista42=="N") echo '<img src="/static/img/portal/check.png" width="12px">';else echo'<input type="checkbox">' ;?> </label>
                    </td>
                    <td colspan="6" style="border: 1px solid gray;">
                        <label>SI <?php if($rtaAccionista43=="S") echo '<img src="/static/img/portal/check.png" width="12px">';else echo'<input type="checkbox">' ;?> </label>
                        <label>NO <?php if($rtaAccionista43=="N") echo '<img src="/static/img/portal/check.png" width="12px">';else echo'<input type="checkbox">' ;?> </label>
                    </td>
                    <td colspan="10" style="border: 1px solid gray;">
                        <label>SI <?php if($rtaAccionista44=="S") echo '<img src="/static/img/portal/check.png" width="12px">';else echo'<input type="checkbox">' ;?> </label>
                        <label>NO <?php if($rtaAccionista44=="N") echo '<img src="/static/img/portal/check.png" width="12px">';else echo'<input type="checkbox">' ;?> Indique: <?=$rtaAccionista44Ind?></label>
                    </td>
                </tr>-->
                <tr>
                    <td colspan="28" style="border: 1px solid gray;">
                    </td>
                </tr>
                <tr>
                    <td style="color:White;background-color:#3f51b5;font-weight: bold;text-align: center" colspan="28">REFERENCIAS COMERCIALES</td>
                </tr>
                <tr>
                    <td colspan="28" style="border: 1px solid gray;">
                    </td>
                </tr>
                <tr>
                    <td colspan="9" style="font-weight: bold;border: 1px solid gray;text-align: center">
                        RAZ&Oacute;N SOCIAL
                    </td>
                    <td colspan="3" style="font-weight: bold;border: 1px solid gray;text-align: center">
                        NIT
                    </td>
                    <td colspan="3" style="font-weight: bold;border: 1px solid gray;text-align: center">
                        TEL
                    </td>
                    <td colspan="6" style="font-weight: bold;border: 1px solid gray;text-align: center">
                        DIRECCI&Oacute;N
                    </td>
                    <td colspan="7" style="font-weight: bold;border: 1px solid gray;text-align: center">
                        CONTACTO
                    </td>
                </tr>
                <tr>
                    <td colspan="9" style="border: 1px solid gray;text-align: center;"><?=$referenciaComercial[0]['RAZONSOCIAL']?>&nbsp;</td>
                    <td colspan="3" style="border: 1px solid gray;text-align: center;"><?=$referenciaComercial[0]['NIT']?>&nbsp;</td>
                    <td colspan="3" style="border: 1px solid gray;text-align: center;"><?=$referenciaComercial[0]['TELEFONO']?>&nbsp;</td>
                    <td colspan="6" style="border: 1px solid gray;text-align: center;"><?=$referenciaComercial[0]['DIRECCION']?>&nbsp;</td>
                    <td colspan="7" style="border: 1px solid gray;text-align: center;"><?=$referenciaComercial[0]['CONTACTO']?>&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="9" style="border: 1px solid gray;text-align: center;"><?=$referenciaComercial[1]['RAZONSOCIAL']?>&nbsp; </td>
                    <td colspan="3" style="border: 1px solid gray;text-align: center;"><?=$referenciaComercial[1]['NIT']?>&nbsp; </td>
                    <td colspan="3" style="border: 1px solid gray;text-align: center;"><?=$referenciaComercial[1]['TELEFONO']?>&nbsp; </td>
                    <td colspan="6" style="border: 1px solid gray;text-align: center;"><?=$referenciaComercial[1]['DIRECCION']?>&nbsp; </td>
                    <td colspan="7" style="border: 1px solid gray;text-align: center;"><?=$referenciaComercial[1]['CONTACTO']?>&nbsp; </td>
                </tr>
                <tr>
                    <td colspan="28" style="border: 1px solid gray;">
                    </td>
                </tr>
                <tr>
                    <td style="color:White;background-color:#3f51b5;font-weight: bold;text-align: center" colspan="28">REFERENCIAS BANCARIAS</td>
                </tr>
                <tr>
                    <td colspan="28" style="border: 1px solid gray;">
                    </td>
                </tr>
                <tr>
                    <td colspan="9" style="font-weight: bold;border: 1px solid gray;text-align: center">
                        BANCO
                    </td>
                    <td colspan="4" style="font-weight: bold;border: 1px solid gray;text-align: center">
                        TIPO DE CUENTA
                    </td>
                    <td colspan="6" style="font-weight: bold;border: 1px solid gray;text-align: center">
                        N&Uacute;MERO DE CUENTA
                    </td>
                    <td colspan="9" style="font-weight: bold;border: 1px solid gray;text-align: center">
                        NOMBRE DE LA CUENTA
                    </td>
                </tr>
                <tr>
                    <td colspan="9" style="border: 1px solid gray;text-align: center;"> <?=$referenciaBancaria[0]['BANCO']?></td>
                    <td colspan="4" style="border: 1px solid gray;text-align: center;"><?=$referenciaBancaria[0]['TIPO_CUENTA']?></td>
                    <td colspan="6" style="border: 1px solid gray;text-align: center;"><?=$referenciaBancaria[0]['NUMERO_CUENTA']?></td>
                    <td colspan="9" style="border: 1px solid gray;text-align: center;"><?=$referenciaBancaria[0]['NOMBRE_CUENTA']?></td>
                </tr>
                <tr>
                    <td colspan="9" style="border: 1px solid gray;text-align: center;"> <?=$referenciaBancaria[1]['BANCO']?></td>
                    <td colspan="4" style="border: 1px solid gray;text-align: center;"><?=$referenciaBancaria[1]['TIPO_CUENTA']?></td>
                    <td colspan="6" style="border: 1px solid gray;text-align: center;"><?=$referenciaBancaria[1]['NUMERO_CUENTA']?></td>
                    <td colspan="9" style="border: 1px solid gray;text-align: center;"><?=$referenciaBancaria[1]['NOMBRE_CUENTA']?></td>
                </tr>
                <tr>
                    <td colspan="28" style="border: 1px solid gray;"></td>
                </tr>
                <tr>
                    <td style="color:White;background-color:#3f51b5;font-weight: bold;text-align: center" colspan="28">INFORMACI&Oacute;N FINANCIERA (ANUAL)</td>
                </tr>
                <tr>
                    <td colspan="28" style="border: 1px solid gray;">
                    </td>
                </tr>
                <tr>
                    <td colspan="7" style="font-weight: bold;border: 1px solid gray">PERIODOS DECLARADOS</td>
                    <td colspan="21" style="font-weight: bold;border: 1px solid gray"><?= $datosjuridicos['PERIODOS_DECLARADOS']?></td>
                </tr>
                <tr>
                    <td colspan="7" style="font-weight: bold;border: 1px solid gray">INGRESO A&Ntilde;O ACTUAL</td>
                    <td colspan="7" style="font-weight: bold;border: 1px solid gray">OTROS INGRESOS A&Ntilde;O ACTUAL</td>
                    <td colspan="7" style="font-weight: bold;border: 1px solid gray">TOTAL INGRESOS A&Ntilde;O ACTUAL</td>
                    <td colspan="7" style="font-weight: bold;border: 1px solid gray">TOTAL EGRESOS A&Ntilde;O ACTUAL</td>
                </tr>
                <tr>
                    <td colspan="7" style="font-weight: bold;border: 1px solid gray">$ <?= number_format($datosjuridicos['INGRESO_MENSUAL'])?></td>
                    <td colspan="7" style="font-weight: bold;border: 1px solid gray">$ <?= number_format($datosjuridicos['OTROS_INGRESOS_M'])?></td>
                    <td colspan="7" style="font-weight: bold;border: 1px solid gray">$ <?= number_format($datosjuridicos['TOTAL_INGRESOS'])?></td>
                    <td colspan="7" style="font-weight: bold;border: 1px solid gray">$ <?= number_format($datosjuridicos['EGRESOS_MENSUALES'])?></td>
                </tr>
                <tr>
                    <td colspan="9" style="font-weight: bold;border: 1px solid gray">TOTAL ACTIVOS A&Ntilde;O ACTUAL</td>
                    <td colspan="9" style="font-weight: bold;border: 1px solid gray">TOTAL PASIVOS A&Ntilde;O ACTUAL</td>
                    <td colspan="10" style="font-weight: bold;border: 1px solid gray">TOTAL PATRIMONIO A&Ntilde;O ACTUAL</td>
                </tr>
                <tr>
                    <td colspan="9" style="font-weight: bold;border: 1px solid gray">$ <?= number_format($datosjuridicos['TOTAL_ACTIVOS'])?></td>
                    <td colspan="9" style="font-weight: bold;border: 1px solid gray">$ <?= number_format($datosjuridicos['TOTAL_PASIVOS'])?></td>
                    <td colspan="10" style="font-weight: bold;border: 1px solid gray">$ <?= number_format($datosjuridicos['TOTAL_PATRIMONIO'])?></td>
                </tr>
                <tr>
                    <td colspan="9" style="font-weight: bold;border: 1px solid gray">INDIQUE EL CONCEPTO DE OTROS INGRESOS</td>
                    <td colspan="19" style="font-weight: bold;border: 1px solid gray"><?= $datosjuridicos['CONCEPTO_INGRESOS']?></td>
                </tr>
                <tr>
                    <td colspan="7" style="font-weight: bold;border: 1px solid gray">INGRESO A&Ntilde;O ANTERIOR</td>
                    <td colspan="7" style="font-weight: bold;border: 1px solid gray">OTROS INGRESOS A&Ntilde;O ANTERIOR</td>
                    <td colspan="7" style="font-weight: bold;border: 1px solid gray">TOTAL INGRESOS A&Ntilde;O ANTERIOR</td>
                    <td colspan="7" style="font-weight: bold;border: 1px solid gray">TOTAL EGRESOS A&Ntilde;O ANTERIOR</td>
                </tr>
                <tr>
                    <td colspan="7" style="font-weight: bold;border: 1px solid gray">$ <?= number_format($datosjuridicos['INGRESO_A_ANTERIOR'])?></td>
                    <td colspan="7" style="font-weight: bold;border: 1px solid gray">$ <?= number_format($datosjuridicos['OTRO_INGRESO_A_ANTERIOR'])?></td>
                    <td colspan="7" style="font-weight: bold;border: 1px solid gray">$ <?= number_format($datosjuridicos['TOTAL_INGRESO_A_ANTERIOR'])?></td>
                    <td colspan="7" style="font-weight: bold;border: 1px solid gray">$ <?= number_format($datosjuridicos['EGRESOS_A_ANTERIOR'])?></td>
                </tr>
                <tr>
                    <td colspan="9" style="font-weight: bold;border: 1px solid gray">TOTAL ACTIVOS A&Ntilde;O ANTERIOR</td>
                    <td colspan="9" style="font-weight: bold;border: 1px solid gray">TOTAL PASIVOS A&Ntilde;O ANTERIOR</td>
                    <td colspan="10" style="font-weight: bold;border: 1px solid gray">TOTAL PATRIMONIO A&Ntilde;O ANTERIOR</td>
                </tr>
                <tr>
                    <td colspan="9" style="font-weight: bold;border: 1px solid gray">$ <?= number_format($datosjuridicos['TOTAL_ACTIVOS_A_ANTERIOR'])?></td>
                    <td colspan="9" style="font-weight: bold;border: 1px solid gray">$ <?= number_format($datosjuridicos['TOTAL_PASIVOS_A_ANTERIOR'])?></td>
                    <td colspan="10" style="font-weight: bold;border: 1px solid gray">$ <?= number_format($datosjuridicos['TOTAL_PATRIMONIO_A_ANTERIOR'])?></td>
                </tr>
                <tr>
                    <td colspan="9" style="font-weight: bold;border: 1px solid gray">INDIQUE EL CONCEPTO DE OTROS INGRESOS</td>
                    <td colspan="19" style="font-weight: bold;border: 1px solid gray"><?= $datosjuridicos['CONCEPTO_INGRESOS_A_ANTERIOR']?></td>
                </tr>
                <tr>
                    <td colspan="28" style="border: 1px solid gray"></td>
                </tr>
                <tr>
                    <td style="color:White;background-color:#3f51b5;font-weight: bold;text-align: center" colspan="28">ACTIVIDAD EN OPERACIONES INTERNACIONALES </td>
                </tr>
                <tr>
                    <td colspan="28" style="border: 1px solid gray"></td>
                </tr>
                <tr>
                    <td colspan="8" style="border-top: 1px solid gray;border-bottom: 1px solid gray;border-left: 1px solid gray;">Realiza transacciones en moneda extranjera?</td>
                    <?php
                    
                    foreach($preguntasOperacionesInt as $itempreguntasOperacionesInt){
                        if($itempreguntasOperacionesInt['PK_PREGVER_CODIGO']==152){
                            if($itempreguntasOperacionesInt['RESPUESTA']=="S"){
                                $monedaextrangera = 1;
                            }
                            if($itempreguntasOperacionesInt['RESPUESTA']=="N"){
                                $monedaextrangera = 0;
                            }
                        }
                        if($itempreguntasOperacionesInt['PK_PREGVER_CODIGO']==151){
                            if($itempreguntasOperacionesInt['RESPUESTA']=="S"){
                                $obligacioneseeuu = 1;
                            }
                            if($itempreguntasOperacionesInt['RESPUESTA']=="N"){
                                $obligacioneseeuu = 0;
                            }
                        }
                        if($itempreguntasOperacionesInt['PK_PREGVER_CODIGO']>=153&&$itempreguntasOperacionesInt['PK_PREGVER_CODIGO']<=159){
                            $monedaextrangeraRTA = $itempreguntasOperacionesInt['PK_PREGVER_CODIGO'];
                            $monedaextrangeraRTAIndicar = $itempreguntasOperacionesInt['INDICAR'];
                        }
                    }
                    ?>
                    <td colspan="3" style="border-top: 1px solid gray;border-bottom: 1px solid gray;">
                        <label>SI <?php if($monedaextrangera==1) echo '<img src="/static/img/portal/check.png" width="12px">';else echo'<input type="checkbox">' ;?> </label>
                        <label>NO <?php if($monedaextrangera==0) echo '<img src="/static/img/portal/check.png" width="12px">';else echo'<input type="checkbox">' ;?> </label>
                    </td>
                    <td colspan="11" style="border-top: 1px solid gray;border-bottom: 1px solid gray;border-right: 1px solid gray;"></td>
                    <td colspan="6" rowspan="3"  style="border: 1px solid gray">
                        &iquest;Es usted sujeto de<br/>
                        obligaciones tributarias en los Estados Unidos?<br/>
                        <label>SI <?php if($obligacioneseeuu==1) echo '<img src="/static/img/portal/check.png" width="12px">';else echo'<input type="checkbox">' ;?> </label>
                        <label>NO <?php if($obligacioneseeuu==0) echo '<img src="/static/img/portal/check.png" width="12px">';else echo'<input type="checkbox">' ;?> </label>    
                    </td>
                </tr>
                <tr>
                    <td colspan="22"  style="border: 1px solid gray">
                        <label>Importaciones <?php if($monedaextrangeraRTA==153) echo '<img src="/static/img/portal/check.png" width="12px">';else echo'<input type="checkbox">' ;?> </label>
                        <label>Exportaciones <?php if($monedaextrangeraRTA==154) echo '<img src="/static/img/portal/check.png" width="12px">';else echo'<input type="checkbox">' ;?> </label>
                        <label>Inversiones <?php if($monedaextrangeraRTA==155) echo '<img src="/static/img/portal/check.png" width="12px">';else echo'<input type="checkbox">' ;?> </label>
                        <label>Transferencias <?php if($monedaextrangeraRTA==156) echo '<img src="/static/img/portal/check.png" width="12px">';else echo'<input type="checkbox">' ;?> </label>
                        <label>Pr&eacute;stamos en moneda extranjera <?php if($monedaextrangeraRTA==157) echo '<img src="/static/img/portal/check.png" width="12px">';else echo'<input type="checkbox">' ;?> </label>
                    </td>
                </tr>
                <tr>
                    <td colspan="22" style="border: 1px solid gray">
                        <label>Pago de servicios <?php if($monedaextrangeraRTA==158) echo '<img src="/static/img/portal/check.png" width="12px">';else echo'<input type="checkbox">' ;?> </label>
                        <label>Otro <?php if($monedaextrangeraRTA==159) echo '<img src="/static/img/portal/check.png" width="12px">';else echo'<input type="checkbox">' ;?> </label>
                        <label>Detalle <?=$monedaextrangeraRTAIndicar?></label>
                    </td>
                </tr>
                <tr>
                    <td colspan="28"  style="border: 1px solid gray"></td>
                </tr>
                <tr>
                    <td style="color:White;background-color:#3f51b5;font-weight: bold;text-align: center" colspan="28">PERFIL DE PLATAFORMA-ESTRUCTURA DE CAMPA&Ntilde;A </td>
                </tr>
                <tr>
                    <td colspan="28"  style="border: 1px solid gray"></td>
                </tr>
                <tr>
                    <td colspan="5" style="font-weight: bold;border: 1px solid gray">NOMBRE DE CAMPA&Ntilde;A</td>
                    <td colspan="23" style="border: 1px solid gray"><?=$perfilesplataforma[0]['CAMPANA']?></td>
                </tr>
                <tr>
                    <td colspan="1" style="font-weight: bold;border: 1px solid gray;text-align: center">1</td>
                    <td colspan="8" style="font-weight: bold;border: 1px solid gray;text-align: center">Nombre y Apellidos</td>
                    <td colspan="4" style="font-weight: bold;border: 1px solid gray;text-align: center">Tipo. Doc</td>
                    <td colspan="6" style="font-weight: bold;border: 1px solid gray;text-align: center">N&uacute;mero de </br>documento</td>
                    <td colspan="9" style="font-weight: bold;border: 1px solid gray;text-align: center">Direcci&oacute;n</td>
                </tr>
                <tr>
                    <td colspan="1" style="font-weight: bold;border: 1px solid gray;"></td>
                    <td colspan="7" style="border: 1px solid gray;"><?=$perfilesplataforma[0]['PRIMER_NOMBRE']?> <?=$perfilesplataforma[0]['SEGUNDO_NOMBRE']?> <?=$perfilesplataforma[0]['PRIMER_APELLIDO']?> <?=$perfilesplataforma[0]['SEGUNDO_APELLIDO']?></td>
                    <td colspan="8" style="border: 1px solid gray;">
                        <label><?php if($perfilesplataforma[0]['TIPDOC']==68) echo 'C.C.';?> </label>
                        <label><?php if($perfilesplataforma[0]['TIPDOC']==70) echo 'C.E';?> </label>
                        <label><?php if($perfilesplataforma[0]['TIPDOC']==69) echo 'Pasaporte';?> </label>
                    </td>
                    <td colspan="5" style="border: 1px solid gray;"><?=$perfilesplataforma[0]['DOCUMENTO']?></td>
                    <td colspan="7" style="border: 1px solid gray;"><?=$perfilesplataforma[0]['DIRECCION']?></td>
                </tr>
                <tr>
                    <td colspan="1" style="font-weight: bold;border: 1px solid gray;text-align: center"></td>
                    <td colspan="5" style="font-weight: bold;border: 1px solid gray;text-align: center">Tel&eacute;fono contacto</td> 
                    <td colspan="7" style="font-weight: bold;border: 1px solid gray;text-align: center">Correo Electr&oacute;nico</td>
                    <td colspan="5" style="font-weight: bold;border: 1px solid gray;text-align: center">Pa&iacute;s</td>
                    <td colspan="5" style="font-weight: bold;border: 1px solid gray;text-align: center">Departamento</td>
                    <td colspan="5" style="font-weight: bold;border: 1px solid gray;text-align: center">Ciudad</td>
                </tr>
                <tr>
                    <td colspan="1" style="font-weight: bold;border: 1px solid gray"> </td>
                    <td colspan="5" style="border: 1px solid gray"><?=$perfilesplataforma[0]['TELEFONO']?></td>
                    <td colspan="7" style="border: 1px solid gray"><?=$perfilesplataforma[0]['CORREO_ELECTRONICO']?></td>
                    <td colspan="5" style="border: 1px solid gray"><?=$perfilesplataforma[0]['PAIS']?></td>
                    <td colspan="5" style="border: 1px solid gray"><?=$perfilesplataforma[0]['DEPARTAMENTO']?></td>
                    <td colspan="5" style="border: 1px solid gray"><?=$perfilesplataforma[0]['CIUDAD']?></td>
                </tr>
                <tr>
                    <td colspan="6" style="font-weight: bold;border: 1px solid gray">PERFIL DE PLATAFORMA</td>
                    <td colspan="22" style="border: 1px solid gray">
                        <label>Coordinador 
                            <?php 
                                $roles = 0;
                                foreach ($rolesPlataforma as $rolesPlataformaItem) {
                                    if($perfilesplataforma[0]['DOCUMENTO'] ==$rolesPlataformaItem['DOCUMENTO']AND $rolesPlataformaItem['ROLID']==45){
                                        echo '<img src="/static/img/portal/check.png" width="12px">';
                                        $roles = 1;
                                    }
                                }
                                if ($roles == 0){
                                    echo'<input type="checkbox">' ;
                                }
                            ?>
                        </label>
                        <label>Custodio 
                            <?php 
                                $roles = 0;
                                foreach ($rolesPlataforma as $rolesPlataformaItem) {
                                    if($perfilesplataforma[0]['DOCUMENTO'] ==$rolesPlataformaItem['DOCUMENTO']AND $rolesPlataformaItem['ROLID']==46){
                                        echo '<img src="/static/img/portal/check.png" width="12px">';
                                        $roles = 1;
                                    }
                                }
                                if ($roles == 0){
                                    echo'<input type="checkbox">' ;
                                }
                            ?>
                        </label>
                        <label>Monitor
                            <?php 
                                $roles = 0;
                                foreach ($rolesPlataforma as $rolesPlataformaItem) {
                                    if($perfilesplataforma[0]['DOCUMENTO'] ==$rolesPlataformaItem['DOCUMENTO']AND $rolesPlataformaItem['ROLID']==56){
                                        echo '<img src="/static/img/portal/check.png" width="12px">';
                                        $roles = 1;
                                    }
                                }
                                if ($roles == 0){
                                    echo'<input type="checkbox">' ;
                                }
                            ?>
                        </label>
                        <label>Administrador 
                            <?php 
                                $roles = 0;
                                foreach ($rolesPlataforma as $rolesPlataformaItem) {
                                    if($perfilesplataforma[0]['DOCUMENTO'] ==$rolesPlataformaItem['DOCUMENTO']AND $rolesPlataformaItem['ROLID']==47){
                                        echo '<img src="/static/img/portal/check.png" width="12px">';
                                        $roles = 1;
                                    }
                                }
                                if ($roles == 0){
                                    echo'<input type="checkbox">' ;
                                }
                            ?>
                        </label>
                    </td>
                </tr> 
                <tr>
                    <td colspan="5" style="font-weight: bold;border: 1px solid gray">NOMBRE DE CAMPA&Ntilde;A</td>
                    <td colspan="23" style="border: 1px solid gray"><?=$perfilesplataforma[1]['CAMPANA']?></td>
                </tr>
                <tr>
                    <td colspan="1" style="font-weight: bold;border: 1px solid gray;text-align: center">2</td>
                    <td colspan="8" style="font-weight: bold;border: 1px solid gray;text-align: center">Nombre y Apellidos</td>
                    <td colspan="4" style="font-weight: bold;border: 1px solid gray;text-align: center">Tipo. Doc</td>
                    <td colspan="6" style="font-weight: bold;border: 1px solid gray;text-align: center">N&uacute;mero de </br>documento</td>
                    <td colspan="9" style="font-weight: bold;border: 1px solid gray;text-align: center">Direcci&oacute;n</td>
                </tr>
                <tr>
                    <td colspan="1" style="font-weight: bold;border: 1px solid gray;"></td>
                    <td colspan="7" style="border: 1px solid gray;"><?=$perfilesplataforma[1]['PRIMER_NOMBRE']?> <?=$perfilesplataforma[1]['SEGUNDO_NOMBRE']?> <?=$perfilesplataforma[1]['PRIMER_APELLIDO']?> <?=$perfilesplataforma[1]['SEGUNDO_APELLIDO']?></td>
                    <td colspan="8" style="border: 1px solid gray;">
                        <label>C.C. <?php if($perfilesplataforma[1]['TIPDOC']==68) echo '<img src="/static/img/portal/check.png" width="12px">';else echo'<input type="checkbox">' ;?> </label>
                        <label>C.E <?php if($perfilesplataforma[1]['TIPDOC']==70) echo '<img src="/static/img/portal/check.png" width="12px">';else echo'<input type="checkbox">' ;?> </label>
                        <label>Pasaporte <?php if($perfilesplataforma[1]['TIPDOC']==69) echo '<img src="/static/img/portal/check.png" width="12px">';else echo'<input type="checkbox">' ;?> </label>
                    </td>
                    <td colspan="5" style="border: 1px solid gray;"><?=$perfilesplataforma[1]['DOCUMENTO']?></td>
                    <td colspan="7" style="border: 1px solid gray;"><?=$perfilesplataforma[1]['DIRECCION']?></td>
                </tr>
                <tr>
                    <td colspan="1" style="font-weight: bold;border: 1px solid gray;text-align: center"></td>
                    <td colspan="5" style="font-weight: bold;border: 1px solid gray;text-align: center">Tel&eacute;fono contacto</td>
                    <td colspan="7" style="font-weight: bold;border: 1px solid gray;text-align: center">Correo Electr&oacute;nico</td>
                    <td colspan="5" style="font-weight: bold;border: 1px solid gray;text-align: center">Pa&iacute;s</td>
                    <td colspan="5" style="font-weight: bold;border: 1px solid gray;text-align: center">Departamento</td>
                    <td colspan="5" style="font-weight: bold;border: 1px solid gray;text-align: center">Ciudad</td>
                </tr>
                <tr>
                    <td colspan="1" style="font-weight: bold;border: 1px solid gray"> </td>
                    <td colspan="5" style="border: 1px solid gray"><?=$perfilesplataforma[1]['TELEFONO']?></td>
                    <td colspan="7" style="border: 1px solid gray"><?=$perfilesplataforma[1]['CORREO_ELECTRONICO']?></td>
                    <td colspan="5" style="border: 1px solid gray"><?=$perfilesplataforma[1]['PAIS']?></td>
                    <td colspan="5" style="border: 1px solid gray"><?=$perfilesplataforma[1]['DEPARTAMENTO']?></td>
                    <td colspan="5" style="border: 1px solid gray"><?=$perfilesplataforma[1]['CIUDAD']?></td>
                </tr>
                <tr>
                    <td colspan="6" style="font-weight: bold;border: 1px solid gray">PERFIL DE PLATAFORMA</td>
                    <td colspan="22" style="border: 1px solid gray">
                        <label>Coordinador 
                            <?php 
                                $roles = 0;
                                foreach ($rolesPlataforma as $rolesPlataformaItem) {
                                    if($perfilesplataforma[1]['DOCUMENTO'] ==$rolesPlataformaItem['DOCUMENTO']AND $rolesPlataformaItem['ROLID']==45){
                                        echo '<img src="/static/img/portal/check.png" width="12px">';
                                        $roles = 1;
                                    }
                                }
                                if ($roles == 0){
                                    echo'<input type="checkbox">' ;
                                }
                            ?>
                        </label>
                        <label>Custodio 
                            <?php 
                                $roles = 0;
                                foreach ($rolesPlataforma as $rolesPlataformaItem) {
                                    if($perfilesplataforma[1]['DOCUMENTO'] ==$rolesPlataformaItem['DOCUMENTO']AND $rolesPlataformaItem['ROLID']==46){
                                        echo '<img src="/static/img/portal/check.png" width="12px">';
                                        $roles = 1;
                                    }
                                }
                                if ($roles == 0){
                                    echo'<input type="checkbox">' ;
                                }
                            ?>
                        </label>
                        <label>Monitor
                            <?php 
                                $roles = 0;
                                foreach ($rolesPlataforma as $rolesPlataformaItem) {
                                    if($perfilesplataforma[1]['DOCUMENTO'] ==$rolesPlataformaItem['DOCUMENTO']AND $rolesPlataformaItem['ROLID']==56){
                                        echo '<img src="/static/img/portal/check.png" width="12px">';
                                        $roles = 1;
                                    }
                                }
                                if ($roles == 0){
                                    echo'<input type="checkbox">' ;
                                }
                            ?>
                        </label>
                        <label>Administrador 
                            <?php 
                                $roles = 0;
                                foreach ($rolesPlataforma as $rolesPlataformaItem) {
                                    if($perfilesplataforma[1]['DOCUMENTO'] ==$rolesPlataformaItem['DOCUMENTO']AND $rolesPlataformaItem['ROLID']==47){
                                        echo '<img src="/static/img/portal/check.png" width="12px">';
                                        $roles = 1;
                                    }
                                }
                                if ($roles == 0){
                                    echo'<input type="checkbox">' ;
                                }
                            ?>
                        </label>
                    </td>
                </tr>
                <tr>
                    <td colspan="5" style="font-weight: bold;border: 1px solid gray">NOMBRE DE CAMPA&Ntilde;A</td>
                    <td colspan="23" style="border: 1px solid gray"><?=$perfilesplataforma[2]['CAMPANA']?></td>
                </tr>
                <tr>
                    <td colspan="1" style="font-weight: bold;border: 1px solid gray;text-align: center">3</td>
                    <td colspan="8" style="font-weight: bold;border: 1px solid gray;text-align: center">Nombre y Apellidos</td>
                    <td colspan="4" style="font-weight: bold;border: 1px solid gray;text-align: center">Tipo. Doc</td>
                    <td colspan="6" style="font-weight: bold;border: 1px solid gray;text-align: center">N&uacute;mero de </br>documento</td>
                    <td colspan="9" style="font-weight: bold;border: 1px solid gray;text-align: center">Direcci&oacute;n</td>
                </tr>
                <tr>
                    <td colspan="1" style="font-weight: bold;border: 1px solid gray;"></td>
                    <td colspan="7" style="border: 1px solid gray;"><?=$perfilesplataforma[2]['PRIMER_NOMBRE']?> <?=$perfilesplataforma[2]['SEGUNDO_NOMBRE']?> <?=$perfilesplataforma[2]['PRIMER_APELLIDO']?> <?=$perfilesplataforma[2]['SEGUNDO_APELLIDO']?></td>
                    <td colspan="8" style="border: 1px solid gray;">
                        <label>C.C. <?php if($perfilesplataforma[2]['TIPDOC']==68) echo '<img src="/static/img/portal/check.png" width="12px">';else echo'<input type="checkbox">' ;?> </label>
                        <label>C.E <?php if($perfilesplataforma[2]['TIPDOC']==70) echo '<img src="/static/img/portal/check.png" width="12px">';else echo'<input type="checkbox">' ;?> </label>
                        <label>Pasaporte <?php if($perfilesplataforma[2]['TIPDOC']==69) echo '<img src="/static/img/portal/check.png" width="12px">';else echo'<input type="checkbox">' ;?> </label>
                    </td>
                    <td colspan="5" style="border: 1px solid gray;"><?=$perfilesplataforma[2]['DOCUMENTO']?></td>
                    <td colspan="7" style="border: 1px solid gray;"><?=$perfilesplataforma[2]['DIRECCION']?></td>
                </tr>
                <tr>
                    <td colspan="1" style="font-weight: bold;border: 1px solid gray;text-align: center"></td>
                    <td colspan="5" style="font-weight: bold;border: 1px solid gray;text-align: center">Tel&eacute;fono contacto</td>
                    <td colspan="7" style="font-weight: bold;border: 1px solid gray;text-align: center">Correo Electr&oacute;nico</td>
                    <td colspan="5" style="font-weight: bold;border: 1px solid gray;text-align: center">Pa&iacute;s</td>
                    <td colspan="5" style="font-weight: bold;border: 1px solid gray;text-align: center">Departamento</td>
                    <td colspan="5" style="font-weight: bold;border: 1px solid gray;text-align: center">Ciudad</td>
                </tr>
                <tr>
                    <td colspan="1" style="font-weight: bold;border: 1px solid gray"> </td>
                    <td colspan="5" style="border: 1px solid gray"><?=$perfilesplataforma[2]['TELEFONO']?></td>
                    <td colspan="7" style="border: 1px solid gray"><?=$perfilesplataforma[2]['CORREO_ELECTRONICO']?></td>
                    <td colspan="5" style="border: 1px solid gray"><?=$perfilesplataforma[2]['PAIS']?></td>
                    <td colspan="5" style="border: 1px solid gray"><?=$perfilesplataforma[2]['DEPARTAMENTO']?></td>
                    <td colspan="5" style="border: 1px solid gray"><?=$perfilesplataforma[2]['CIUDAD']?></td>
                </tr>
                <tr>
                    <td colspan="6" style="font-weight: bold;border: 1px solid gray">PERFIL DE PLATAFORMA</td>
                    <td colspan="22" style="border: 1px solid gray">
                        <label>Coordinador 
                            <?php 
                                $roles = 0;
                                foreach ($rolesPlataforma as $rolesPlataformaItem) {
                                    if($perfilesplataforma[2]['DOCUMENTO'] ==$rolesPlataformaItem['DOCUMENTO']AND $rolesPlataformaItem['ROLID']==45){
                                        echo '<img src="/static/img/portal/check.png" width="12px">';
                                        $roles = 1;
                                    }
                                }
                                if ($roles == 0){
                                    echo'<input type="checkbox">' ;
                                }
                            ?>
                        </label>
                        <label>Custodio 
                            <?php 
                                $roles = 0;
                                foreach ($rolesPlataforma as $rolesPlataformaItem) {
                                    if($perfilesplataforma[2]['DOCUMENTO'] ==$rolesPlataformaItem['DOCUMENTO']AND $rolesPlataformaItem['ROLID']==46){
                                        echo '<img src="/static/img/portal/check.png" width="12px">';
                                        $roles = 1;
                                    }
                                }
                                if ($roles == 0){
                                    echo'<input type="checkbox">' ;
                                }
                            ?>
                        </label>
                        <label>Monitor
                            <?php 
                                $roles = 0;
                                foreach ($rolesPlataforma as $rolesPlataformaItem) {
                                    if($perfilesplataforma[2]['DOCUMENTO'] ==$rolesPlataformaItem['DOCUMENTO']AND $rolesPlataformaItem['ROLID']==56){
                                        echo '<img src="/static/img/portal/check.png" width="12px">';
                                        $roles = 1;
                                    }
                                }
                                if ($roles == 0){
                                    echo'<input type="checkbox">' ;
                                }
                            ?>
                        </label>
                        <label>Administrador 
                            <?php 
                                $roles = 0;
                                foreach ($rolesPlataforma as $rolesPlataformaItem) {
                                    if($perfilesplataforma[2]['DOCUMENTO'] ==$rolesPlataformaItem['DOCUMENTO']AND $rolesPlataformaItem['ROLID']==47){
                                        echo '<img src="/static/img/portal/check.png" width="12px">';
                                        $roles = 1;
                                    }
                                }
                                if ($roles == 0){
                                    echo'<input type="checkbox">' ;
                                }
                            ?>
                        </label>
                    </td>
                </tr>
                <tr>
                    <td colspan="5" style="font-weight: bold;border: 1px solid gray">NOMBRE DE CAMPA&Ntilde;A</td>
                    <td colspan="23" style="border: 1px solid gray"><?=$perfilesplataforma[3]['CAMPANA']?></td>
                </tr>
                <tr>
                    <td colspan="1" style="font-weight: bold;border: 1px solid gray;text-align: center">4</td>
                    <td colspan="8" style="font-weight: bold;border: 1px solid gray;text-align: center">Nombre y Apellidos</td>
                    <td colspan="4" style="font-weight: bold;border: 1px solid gray;text-align: center">Tipo. Doc</td>
                    <td colspan="6" style="font-weight: bold;border: 1px solid gray;text-align: center">N&uacute;mero de </br>documento</td>
                    <td colspan="9" style="font-weight: bold;border: 1px solid gray;text-align: center">Direcci&oacute;n</td>
                </tr>
                <tr>
                    <td colspan="1" style="font-weight: bold;border: 1px solid gray;"></td>
                    <td colspan="7" style="border: 1px solid gray;"><?=$perfilesplataforma[3]['PRIMER_NOMBRE']?> <?=$perfilesplataforma[3]['SEGUNDO_NOMBRE']?> <?=$perfilesplataforma[3]['PRIMER_APELLIDO']?> <?=$perfilesplataforma[3]['SEGUNDO_APELLIDO']?></td>
                    <td colspan="8" style="border: 1px solid gray;">
                        <label>C.C. <?php if($perfilesplataforma[3]['TIPDOC']==68) echo '<img src="/static/img/portal/check.png" width="12px">';else echo'<input type="checkbox">' ;?> </label>
                        <label>C.E <?php if($perfilesplataforma[3]['TIPDOC']==70) echo '<img src="/static/img/portal/check.png" width="12px">';else echo'<input type="checkbox">' ;?> </label>
                        <label>Pasaporte <?php if($perfilesplataforma[3]['TIPDOC']==69) echo '<img src="/static/img/portal/check.png" width="12px">';else echo'<input type="checkbox">' ;?> </label>
                    </td>
                    <td colspan="5" style="border: 1px solid gray;"><?=$perfilesplataforma[3]['DOCUMENTO']?></td>
                    <td colspan="7" style="border: 1px solid gray;"><?=$perfilesplataforma[3]['DIRECCION']?></td>
                </tr>
                <tr>
                    <td colspan="1" style="font-weight: bold;border: 1px solid gray;text-align: center"></td>
                    <td colspan="5" style="font-weight: bold;border: 1px solid gray;text-align: center">Tel&eacute;fono contacto</td>
                    <td colspan="7" style="font-weight: bold;border: 1px solid gray;text-align: center">Correo Electr&oacute;nico</td>
                    <td colspan="5" style="font-weight: bold;border: 1px solid gray;text-align: center">Pa&iacute;s</td>
                    <td colspan="5" style="font-weight: bold;border: 1px solid gray;text-align: center">Departamento</td>
                    <td colspan="5" style="font-weight: bold;border: 1px solid gray;text-align: center">Ciudad</td>
                </tr>
                <tr>
                    <td colspan="1" style="border: 1px solid gray"> </td>
                    <td colspan="5" style="border: 1px solid gray"><?=$perfilesplataforma[3]['TELEFONO']?></td>
                    <td colspan="7" style="border: 1px solid gray"><?=$perfilesplataforma[3]['CORREO_ELECTRONICO']?></td>
                    <td colspan="5" style="border: 1px solid gray"><?=$perfilesplataforma[3]['PAIS']?></td>
                    <td colspan="5" style="border: 1px solid gray"><?=$perfilesplataforma[3]['DEPARTAMENTO']?></td>
                    <td colspan="5" style="border: 1px solid gray"><?=$perfilesplataforma[3]['CIUDAD']?></td>
                </tr>
                <tr>
                    <td colspan="6" style="font-weight: bold;border: 1px solid gray">PERFIL DE PLATAFORMA</td>
                    <td colspan="22" style="border: 1px solid gray">
                        <label>Coordinador 
                            <?php 
                                $roles = 0;
                                foreach ($rolesPlataforma as $rolesPlataformaItem) {
                                    if($perfilesplataforma[3]['DOCUMENTO'] ==$rolesPlataformaItem['DOCUMENTO']AND $rolesPlataformaItem['ROLID']==45){
                                        echo '<img src="/static/img/portal/check.png" width="12px">';
                                        $roles = 1;
                                    }
                                }
                                if ($roles == 0){
                                    echo'<input type="checkbox">' ;
                                }
                            ?>
                        </label>
                        <label>Custodio 
                            <?php 
                                $roles = 0;
                                foreach ($rolesPlataforma as $rolesPlataformaItem) {
                                    if($perfilesplataforma[3]['DOCUMENTO'] ==$rolesPlataformaItem['DOCUMENTO']AND $rolesPlataformaItem['ROLID']==46){
                                        echo '<img src="/static/img/portal/check.png" width="12px">';
                                        $roles = 1;
                                    }
                                }
                                if ($roles == 0){
                                    echo'<input type="checkbox">' ;
                                }
                            ?>
                        </label>
                        <label>Monitor
                            <?php 
                                $roles = 0;
                                foreach ($rolesPlataforma as $rolesPlataformaItem) {
                                    if($perfilesplataforma[3]['DOCUMENTO'] ==$rolesPlataformaItem['DOCUMENTO']AND $rolesPlataformaItem['ROLID']==56){
                                        echo '<img src="/static/img/portal/check.png" width="12px">';
                                        $roles = 1;
                                    }
                                }
                                if ($roles == 0){
                                    echo'<input type="checkbox">' ;
                                }
                            ?>
                        </label>
                        <label>Administrador 
                            <?php 
                                $roles = 0;
                                foreach ($rolesPlataforma as $rolesPlataformaItem) {
                                    if($perfilesplataforma[3]['DOCUMENTO'] ==$rolesPlataformaItem['DOCUMENTO']AND $rolesPlataformaItem['ROLID']==47){
                                        echo '<img src="/static/img/portal/check.png" width="12px">';
                                        $roles = 1;
                                    }
                                }
                                if ($roles == 0){
                                    echo'<input type="checkbox">' ;
                                }
                            ?>
                        </label>
                    </td>
                </tr>
                <tr>
                    <td colspan="28" style="border: 1px solid gray"></td>
                </tr>
                <tr>
                    <td colspan="28" style="font-size: 14px">
                        Con este documento usted autoriza a <b>People Pass S.A</b> para asignar permisos a funcionarios de su compa&ntilde;&iacute;a en consultas, 
                        generaci&oacute;n de facturas y pagos.  Cualquier modificaci&oacute;n en los permisos debe ser solicitada por escrito al &aacute;rea de 
                        Soporte al Cliente.
                    </td>
                </tr>
                <tr>
                    <td colspan="28" style="border: 1px solid gray"></td>
                </tr>
                <tr>
                    <td style="color:White;background-color:#3f51b5;font-weight: bold;text-align: center" colspan="28">DECLARACI&Oacute;N ORIGEN DE FONDOS </td>
                </tr>
                <tr>
                    <td colspan="28" style="border: 1px solid gray"></td>
                </tr>
                <tr>
                    <td colspan="28"  style="border: 1px solid gray;text-align: justif;font-size: 10px">
                        En cumplimiento de la Pol&iacute;tica para la Prevenci&oacute;n del Lavado de Activos y Financiaci&oacute;n del Terrorismo implementada por People Pass S.A. y sus filiales; obrando en nombre propio y/o 
                        representaci&oacute;n de la compa&ntilde;&iacute;a <?=$empresa['RAZON_SOCIAL']?>, identificada con NIT/CC <?=$empresa['DOCUMENTO']?> 
                        realizo la siguiente declaraci&oacute;n de origen y
                        destinaci&oacute;n de fondos bajo la gravedad de juramento, de manera libre y voluntaria, certificando que todo lo aqui consignado es cierto: 1. Declaro que mis recursos y/o los recursos de la 
                        empresa que represento, provienen de actividades l&iacute;citas y est&aacute;n relacionados con el desarrollo de mis/las actividades comerciales. Los mismos no provienen ni financian ninguna actividad 
                        il&iacute;cita y no he/hemos efectuado transacciones u operaciones destinadas a la ejecuci&oacute;n de actividades il&iacute;citas contempladas en el C&oacute;digo Penal Colombiano y/o en las normas que lo 
                        adicionen, sustituyan o complementen. 
                        2. Que, en el desarrollo de las actividades comerciales con People Pass S.A., me abstendr&eacute; o la empresa que represento se abstendr&aacute; de adquirir bienes o contratar servicios con 
                        personas de las que tenga o haya podido tener conocimiento que pueden estar vinculadas con actividades il&iacute;citas contempladas en el C&oacute;digo Penal Colombiano y/o en las normas que lo 
                        adicionen, sustituyan o complementen o con personas que se encuentren en las listas vinculantes para Colombia. 3. Que no admitir&eacute; que terceros efect&uacute;en dep&oacute;sitos en mis cuentas o en 
                        las cuentas de la empresa que represento, de fondos provenientes de las actividades il&iacute;citas contempladas en el C&oacute;digo Penal Colombiano y/o en las normas que lo adicionen, sustituyan o 
                        complementen; ni efectuar&eacute; transacciones destinadas a tales actividades o a favor de personas relacionadas con &eacute;stas. 4. Que, ni yo, ni la empresa que represento, accionistas, socios, 
                        directivos o administradores se encuentran en listas internacionales vinculantes para Colombia, o en las listas de la ONU o la OFAC. Autorizo expresamente a People Pass S.A.  para que 
                        lleve a cabo las verificaciones a que haya lugar y para dar por terminada cualquier relacion comercial o contractual en el evento en el que se me vincule o a la empresa que represento, en 
                        cualquiera de estas listas, sin que esto conlleve al pago de indemnizaciones. 5. Eximo de toda responsabilidad a People Pass S.A. derivada de la informaci&oacute;n err&oacute;nea, falsa o inexacta 
                        que yo o la empresa a la que represento, hubiese consignado en la totalidad del presente documento.
                    </td>
                </tr>
                <tr>
                    <td colspan="28" style="border: 1px solid gray"></td>
                </tr>
                <tr>
                    <td style="color:White;background-color:#3f51b5;font-weight: bold;text-align: center" colspan="28">AUTORIZACI&Oacute;N PARA EL TRATAMIENTO DE DATOS </td>
                </tr>
                <tr>
                    <td colspan="28" style="border: 1px solid gray"></td>
                </tr>
                <tr>
                    <td colspan="28" style="border: 1px solid gray;text-align: justify;font-size:10px">
                        En mi calidad de titular de la informaci&oacute;n o como representante legal debidamente autorizado, actuando de manera libre y voluntaria, autorizo de manera expresa e inequivoca a PEOPLE 
                        PASS S.A. o a quien represente sus derechos para que consulte, solicite, suministre, reporte, procese, obtenga, recolecte, compile, confirme, intercambie, modifique, emplee, analice, 
                        estudie, conserve, reciba y env&iacute;e toda la informaci&oacute;n que se refiera al comportamiento de la empresa, sus administradores y socios, respecto de actuaciones crediticias, financieras, 
                        comerciales, de servicios y las que provienen de terceros pa&iacute;ses de la misma naturaleza, a cualquier operador de informaci&oacute;n debidamente constituido o entidad que maneje o administre 
                        bases de datos con fines similares a los de tales operadores, dentro y fuera del territorio nacional, incluyendo las empresas dedicadas a estudios de cr&eacute;dito, informaci&oacute;n empresarial y 
                        gesti&oacute;n de cartera comercial.Conozco que el alcance de esta autorizaci&oacute;n implica que el comportamiento frente a mis obligaciones ser&aacute; registrado con el objeto de suministrar informaci&oacute;n 
                        suficiente y adecuada al mercado sobre el estado de mis obligaciones financieras, comerciales, crediticias, de servicios y la que proviene de terceros paises de la misma naturaleza, asi 
                        como de servir de fuente para efectos estad&iacute;sticos. En consecuencia, el manejo de la informaci&oacute;n se realizar&aacute; de conformidad con la legislaci&oacute;n y jurisprudencia aplicable relacionada con 
                        el h&aacute;beas data, por lo tanto los derechos y obligaciones, as&iacute; como la permanencia de la informaci&oacute;n en las bases de datos corresponden a lo determinado por el ordenamiento jur&iacute;dico 
                        aplicable del cual estoy enterado por ser de car&aacute;cter p&uacute;blico.En caso de que el autorizado realice una venta de cartera o cesi&oacute;n de obligaciones a mi cargo a cualquier titulo en favor de un 
                        tercero, los efectos de esta autorizacion se extender&aacute;n a &eacute;ste en los mismos t&eacute;rminos y condiciones. Asi mismo, autorizo a los operadores de informaci&oacute;n para que pongan mi informaci&oacute;n a 
                        disposici&oacute;n de otros operadores nacionales y extranjeros, en los t&eacute;rminos que establece la ley. Cuando se encuentre evidencia de que el titular de la informaci&oacute;n aqu&iacute; contenida se 
                        encuentra registrado en una lista restrictiva, PEOPLE PASS S.A. se reserva el derecho de dar por terminado con justa causa las relaciones con dicho titular, sin que ello implique el pago 
                        de sanciones o indemnizaciones. Certifico que conozco la Pol&iacute;tica de Tratamiento de Datos Personales de PEOPLE PASS S.A., la cual se encuentra en la p&aacute;gina web 
                        www.peoplepass.com.co.<br/>
                    </td>
                </tr>
                <tr>
                    <td colspan="28" style="border: 1px solid gray"></td>
                </tr>
                <tr>
                    <td style="color:White;background-color:#3f51b5;font-weight: bold;text-align: center" colspan="28">DECLARACI&Oacute;N DE VERACIDAD DE LA INFORMACI&Oacute;N </td>
                </tr>
                <tr>
                    <td colspan="28" style="border: 1px solid gray"></td>
                </tr>
                <tr>
                    <td colspan="28" style="border: 1px solid gray;text-align: justify;font-size: 10px">
                        Declaro que este formulario ha sido completado por mi o en mi presencia y que la informaci&oacute;n
                            provista en el mismo es fiel y verdadera, por tanto, acepto que cualquier omisi&oacute;n o falsedad es
                            responsabilidad de la empresa que represento y eximo a People Pass S.A de la informaci&oacute;n
                            contenida en este formulario, as&iacute; mismo dar&aacute; derecho a People Pass S.A. a cancelar los productos
                            o servicios que se fundamenten en este documento. 
                    </td>
                </tr>
                <tr>
                    <td style="color:White;background-color:#3f51b5;font-weight: bold;text-align: center" colspan="28">CLAUSULA DE FIRMA </td>
                </tr>
                <tr>
                    <td colspan="28" style="border: 1px solid gray"></td>
                </tr>
                <tr>
                    <td colspan="28" style="border: 1px solid gray;text-align: justify;font-size: 10px">
                        La firma del presente documento se har&aacute; por medio de firma electr&oacute;nica a trav&eacute;s de la
                        herramienta tecnol&oacute;gica designada por People Pass S.A, conforme a lo contenido en la ley 527 de
                        1999 y Decreto 1747 de 2000, excepcionalmente y mediando autorizaci&oacute;n previa por parte de
                        People Pass S.A. la firma de este de documento podr&aacute; hacerse de manera manuscrita.

                    </td>
                </tr>
<!--                <tr>
                    <td colspan="21" ></td>
                    <td colspan="7" style="text-align: center;font-weight: bold;" ></td>
                </tr>-->
                <tr>
                    <td colspan="28" style="height: 140px" ></td>
                </tr>
                <tr>
                    <td colspan="1"></td>
                    <td colspan="11">_____________________________________________________</td>
                    <td colspan="1"></td>
                    <td colspan="11">_____________________________________________________</td>
                </tr>
                <tr>
                    <td colspan="1"></td>
                    <td colspan="11">NOMBRES Y APELLIDOS DE REPRESENTANTE LEGAL</td>
                    <td colspan="1"></td>
                    <td colspan="11">NUMERO DE DOCUMENTO DEL REPRESENTANTE LEGAL</td>
                </tr>
<!--                <tr>
                    <td colspan="12"></td>
                    <td colspan="1" style="font-weight: bold">C.C.</td>
                    <td colspan="17"></td>
                </tr>-->
                <tr>
                    <td colspan="28" style="border: 1px solid gray;text-align: center">Nota: Este documento NO ser&aacute; valido sin firma del representante legal.</td>
                </tr>
                <tr>
                    <td colspan="28"></td>
                </tr>
            </table>
        </div>
    </div>
</html>