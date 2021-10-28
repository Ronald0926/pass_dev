--OLVIDO DE CONTRASEÑAS
update modsegcom.segtblmensaj
set mensaje = '<style type="text/css">
  @font-face {
    font-family: "calibrib";
    src: url("$Parametro1/wbcorreo/fonts/calibrib.ttf") format("ttf"),
    url("$Parametro1/wbcorreo/fonts/calibrib.ttf") format("ttf");
};
@font-face {
  font-family: "calibribl";
  src: url("$Parametro1/wbcorreo/fonts/calibribl.ttf") format("ttf"),
  url("$Parametro1/wbcorreo/fonts/calibribl.ttf") format("ttf");
};
</style>
<table >
  <tr>
    <td colspan="3" align="center" WIDTH="501px" HEIGHT="100px">
      <img src="$Parametro1/wbcorreo/img/correo-header.png" colspan="3" WIDTH="350px" HEIGHT="100px" alt="">
    </td>
  </tr>
  <tr>
    <td style="width:501px;height:500px"
     colspan="3" >
     <div style="position:relative; overflow: auto; height: 100%;  overflow-y: hidden;">
       <img src="$Parametro1/wbcorreo/img/correo-fondo.png"style="width:100%;height:100%" alt="">
       <div style="position:absolute;top:10px;left:20px"class="">
         <p style="font-family:calibrib;font-size:20pt;color:#085690;">Señor(a)<br>
           <span style="font-family:calibril;font-size:17pt;color:#1b2a4a;">$Parametro2</span></p>

         <p style="font-family:calibril;font-size:14pt;color:#1b2a4a;">Se ha procesado exitosamente su solicitud de restauraci'||'&'||'oacute;n
          de contrase'||'&'||'ntilde;a, su nueva contrase'||'&'||'ntilde;a es,
          <span style="font-family:calibril;font-size:14pt;color:#085690;">$Parametro3</span><br><br>
          Cualquier inquietud por favor comun'||'&'||'iacute;quese con nuestra
           linea de atenci'||'&'||'#243;n 7434700 opci'||'&'||'#243;n 01<br><br>
           Gracias por utilizar los sistemas de atenci&#243;n,
            nuestro compromiso es brindarle un servicio de calidad.</p>
       </div>

     </div>
    </td>
  </tr>
  <tr>
    <td colspan="3" align="center" WIDTH="500px" HEIGHT="100px">
      <img src="$Parametro1/wbcorreo/img/correo-footer-0.png" colspan="3" WIDTH="500px" HEIGHT="100px" alt="">
    </td>
  </tr>
  <tr>
    <td colspan="1" align="center" WIDTH="167px" HEIGHT="100px">
      <img src="$Parametro1/wbcorreo/img/correo-footer-1.png" WIDTH="167px" HEIGHT="45px" alt="">
    </td>
    <td colspan="1" align="center" WIDTH="167px" HEIGHT="100px">
      <img src="$Parametro1/wbcorreo/img/correo-footer-2.png" WIDTH="167px" HEIGHT="45px" alt="">
    </td>
    <td colspan="1" align="center" WIDTH="167px" HEIGHT="100px">
      <img src="$Parametro1/wbcorreo/img/correo-footer-3.png" WIDTH="167px" HEIGHT="45px" alt="">
    </td>
  </tr>
</table>
'
where codigo = 'CODGENOLVIDOCONTRA'
and PK_MEN_COD = 3;
--ENVIO COTIZACION
update modsegcom.segtblmensaj
set mensaje = '<style type="text/css">
  @font-face {
    font-family: "calibrib";
    src: url("$Parametro1/wbcorreo/fonts/calibrib.ttf") format("ttf"),
    url("$Parametro1/wbcorreo/fonts/calibrib.ttf") format("ttf");
};
@font-face {
  font-family: "calibribl";
  src: url("$Parametro1/wbcorreo/fonts/calibribl.ttf") format("ttf"),
  url("$Parametro1/wbcorreo/fonts/calibribl.ttf") format("ttf");
};
</style>
<table >
  <tr>
    <td colspan="3" align="center" WIDTH="501px" HEIGHT="100px"
  //  style="background-image: url($Parametro1/wbcorreo/img/correo-header.png);
    style="background-image: url(https://www.peoplepass.com.co/static/img/Peoplepass_Logo.png);background-size: contain; background-position: center;background-repeat: no-repeat;">

    </td>
  </tr>
  <tr>
    <td style="width:501px;height:480px" >
        <div style="background-image: url($Parametro1/wbcorreo/img/correo-fondo.png);
          background-size: contain; background-position: center;">
            <div style="">
                <p style="font-family:calibrib;font-size:20pt;color:#085690;">
                Señores<br>
                <span style="font-family:calibril;font-size:14pt;color:#1b2a4a;">
                $Parametro2,</span></p>

                <p style="font-family:calibril;font-size:14pt;color:#1b2a4a;float:left">
                Se ha procesado exitosamente su requerimiento bajo la cotizaci'||'&'||'oacute;n
                #<span style="font-family:calibril;font-size:14pt;color:#085690;">$Parametro3</span>,
                en la siguiente direcci'||'&'||'oacute;n podr'||'&'||'aacute; consultarla y descargarla:
                <span style="font-family:calibril;font-size:14pt;color:#085690;">Link=$$Parametro4$=Link</span><br><br>
                Para continuar con el proceso y empezar a disfrutar de los beneficios que Peoplepass trae
                para usted ingrese a la siguiente direcci'||'&'||'oacute;n
                <span style="font-family:calibril;font-size:14pt;color:#085690;">Link=$$Parametro5$=Link</span>
                y llene la informaci'||'&'||'oacute;n solicitada<br><br>
                Gracias por utilizar los sistemas de atenci'||'&'||'oacute;n, nuestro compromiso es brindarle un servicio de calidad.</p>
                <p style="font-family:calibrib;font-size:20pt;color:#085690;">Cualquier inquietud por favor comun'||'&'||'iacute;quese con su asesor comercial</p>
            </div>
        </div>
    </td>
  </tr>
  <tr>
    <td colspan="3" align="center" WIDTH="500px" HEIGHT="100px">
      <img src="$Parametro1/wbcorreo/img/correo-footer-0.png" colspan="3" WIDTH="500px" HEIGHT="100px" alt="">
    </td>
  </tr>
  <tr>
    <td colspan="1" align="center" WIDTH="167px" HEIGHT="100px">
      <img src="$Parametro1/wbcorreo/img/correo-footer-1.png" WIDTH="167px" HEIGHT="45px" alt="">
    </td>
    <td colspan="1" align="center" WIDTH="167px" HEIGHT="100px">
      <img src="$Parametro1/wbcorreo/img/correo-footer-2.png" WIDTH="167px" HEIGHT="45px" alt="">
    </td>
    <td colspan="1" align="center" WIDTH="167px" HEIGHT="100px">
      <img src="$Parametro1/wbcorreo/img/correo-footer-3.png" WIDTH="167px" HEIGHT="45px" alt="">
    </td>
  </tr>
</table>
'
where codigo = 'CODCOMENVICOTI'
and pk_men_cod = 11;

--CREACION USUARIO
update modsegcom.segtblmensaj
set mensaje ='<style type="text/css">
  @font-face {
    font-family: "calibrib";
    src: url("$Parametro1/wbcorreo/fonts/calibrib.ttf") format("ttf"),
    url("$Parametro1/wbcorreo/fonts/calibrib.ttf") format("ttf");
};
@font-face {
  font-family: "calibribl";
  src: url("$Parametro1/wbcorreo/fonts/calibribl.ttf") format("ttf"),
  url("$Parametro1/wbcorreo/fonts/calibribl.ttf") format("ttf");
};
</style>
<table >
  <tr>
    <td colspan="3" align="center" WIDTH="501px" HEIGHT="100px">
      <img src="$Parametro1/wbcorreo/img/correo-header.png" colspan="3" WIDTH="350px" HEIGHT="100px" alt="">
    </td>
  </tr>
  <tr>
    <td style="width:501px;height:480px"
     colspan="3" >
     <div style="position:relative; overflow: auto; height: 100%; overflow-y: hidden;">
       <img src="$Parametro1/wbcorreo/img/correo-fondo.png"style="width:100%;height:100%" alt="">
       <div style="position:absolute;top:10px;left:20px"class="">
         <p style="font-family:calibrib;font-size:20pt;color:#085690;font-weight: bold;">Señor(a)<br>
           <span style="font-family:calibril;font-size:17pt;color:#1b2a4a;font-weight: normal;">$Parametro2</span></p>
         <p style="font-family:calibril;font-size:14pt;color:#1b2a4a;float:left">
          Su proceso de registro al portal PASSONLINE se ha procesado satisfactoriamente, y podr'||'&'||'aacute;
           acceder ahora mismo.<br><br>
         Usuario: $Parametro3<br>
         Contraseña: $Parametro4<br><br>
         Gracias por utilizar los sistemas de atenci'||'&'||'oacute;n, nuestro compromiso es brindarle un servicio de calidad.
        </p>
          <p style="font-family:calibrib;font-size:20pt;color:#085690;">Cualquier inquietud por favor comun'||'&'||'iacute;quese con su asesor comercial</p>
       </div>
     </div>
    </td>
  </tr>
  <tr>
    <td colspan="3" align="center" WIDTH="500px" HEIGHT="100px">
      <img src="$Parametro1/wbcorreo/img/correo-footer-0.png" colspan="3" WIDTH="500px" HEIGHT="100px" alt="">
    </td>
  </tr>
  <tr>
    <td colspan="1" align="center" WIDTH="167px" HEIGHT="100px">
      <img src="$Parametro1/wbcorreo/img/correo-footer-1.png" WIDTH="167px" HEIGHT="45px" alt="">
    </td>
    <td colspan="1" align="center" WIDTH="167px" HEIGHT="100px">
      <img src="$Parametro1/wbcorreo/img/correo-footer-2.png" WIDTH="167px" HEIGHT="45px" alt="">
    </td>
    <td colspan="1" align="center" WIDTH="167px" HEIGHT="100px">
      <img src="$Parametro1/wbcorreo/img/correo-footer-3.png" WIDTH="167px" HEIGHT="45px" alt="">
    </td>
  </tr>
</table>
'
where codigo = 'CODNOTREPCRE'
and pk_men_cod = 23;
