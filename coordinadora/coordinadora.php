
<?php
    $nombre_remitente=$_POST['NOMBRE_REMITENTE'];
    $id_cliente_coor=$_POST['IDCLIENTE'];
    $direccion_remitente=$_POST['DIR_REMITE'];
      $telefono_remitente=$_POST['TELEFONO_REMITENTE'];
    $ciudad_remitente=$_POST['CIUDAD_REMITE'];
    
    $nit_destinatario=$_POST['NIT_DESTINATARIO'];
    $div_destinarario=$_POST['DESC_DESTINATARIO'];
    $nombre_destinatario=$_POST['NOMBRE_DESTINATARIO'];
    $direccion_destinatario=$_POST['DIR_DESTINATARIO'];
    $ciudad_destinatario=$_POST['CIUDAD_DESTINATARIO'];
    $telefono_destinatario=$_POST['TELEFONO_DESTINATARIO'];
    
    $valor_declarado=$_POST['VALOR_DECLARADO'];
    $codigo_cuenta=$_POST['CODIGO_CUENTA'];
    $codigo_producto=$_POST['CODIGO_PRODUCTO'];
    $nivel_servicio=$_POST['NIVEL_SERVICIO'];
    
    $contenido=$_POST['CONTENIDO_PAQUETE'];
    $referencia=$_POST['REFERENCIA'];
    
    $estado=$_POST['ESTADO'];
    $ubl=$_POST['UBL'];
    $alto=$_POST['ALTO'];
    $ancho=$_POST['ANCHO'];
    $largo=$_POST['LARGO'];
    $peso=$_POST['PESO'];
    $unidades=$_POST['UNIDADES'];
    $detalle_referencia=$_POST['DETALLE_REFERENCIA'];
    $nombre_empaque=$_POST['NOMBRE_EMPAQUE'];
    
    $margen_izquierdo=$_POST['MARGEN_IZQUIERDO'];
    $margen_superior=$_POST['MARGEN_SUPERIOR'];
    $formato_impresion=$_POST['FORMATO_IMPRESION'];
    
    $tipo_medio=$_POST['TIPO_MEDIO'];
    $destino_notificacion=$_POST['DESTINO_NOTIFICACION'];
    
    $usuario=$_POST['USUARIO'];
    $clave=$_POST['CLAVE'];


$url = "http://sandbox.coordinadora.com/agw/ws/guias/1.6/server.php";

$input_xml = '<soapenv:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ser="http://sandbox.coordinadora.com/agw/ws/guias/1.6/server.php" xmlns:soapenc="http://schemas.xmlsoap.org/soap/encoding/">
   <soapenv:Header/>
   <soapenv:Body>
      <ser:Guias_generarGuia soapenv:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/">
         <p xsi:type="ser:Agw_typeGenerarGuiaIn">
            <!--You may enter the following 37 items in any order-->
            <codigo_remision xsi:type="xsd:string"/>
            <fecha xsi:type="xsd:string"></fecha>
            <id_cliente xsi:type="xsd:int">'.$id_cliente_coor.'</id_cliente>
            <id_remitente xsi:type="xsd:int"/>

            <nombre_remitente xsi:type="xsd:string">'.$nombre_remitente.'</nombre_remitente>
            <direccion_remitente xsi:type="xsd:string">'.$direccion_remitente.'</direccion_remitente>
            <telefono_remitente xsi:type="xsd:string">'.$telefono_remitente.'</telefono_remitente>
            <ciudad_remitente xsi:type="xsd:string">'.$ciudad_remitente.'</ciudad_remitente>

            <nit_destinatario xsi:type="xsd:string">'.$nit_destinatario.'</nit_destinatario>
            <div_destinatario xsi:type="xsd:string">'.$div_destinarario.'</div_destinatario>
            <nombre_destinatario xsi:type="xsd:string">'.$nombre_destinatario.'</nombre_destinatario>
            <direccion_destinatario xsi:type="xsd:string">'.$direccion_destinatario.'</direccion_destinatario>
            <ciudad_destinatario xsi:type="xsd:string">'.$ciudad_destinatario.'</ciudad_destinatario>
            <telefono_destinatario xsi:type="xsd:string">'.$telefono_destinatario.'</telefono_destinatario>

            <valor_declarado xsi:type="xsd:float">'.$valor_declarado.'</valor_declarado>
            <codigo_cuenta xsi:type="xsd:int">'.$codigo_cuenta.'</codigo_cuenta>
            <codigo_producto xsi:type="xsd:int">'.$codigo_producto.'</codigo_producto>
            <nivel_servicio xsi:type="xsd:int">'.$nivel_servicio.'</nivel_servicio>

            <linea xsi:type="xsd:string"/>
            <contenido xsi:type="xsd:string">'.$contenido.'</contenido>
            <referencia xsi:type="xsd:string">'.$referencia.'</referencia>
            <observaciones xsi:type="xsd:string"/>
            <estado xsi:type="xsd:string">'.$estado.'</estado>
            <detalle xsi:type="ser:ArrayOfAgw_typeGuiaDetalle" soapenc:arrayType="ser:Agw_typeGuiaDetalle[]">
                <item>
                <ubl xsi:type="xsd:int">'.$ubl.'</ubl>
               	<alto xsi:type="xsd:int">'.$alto.'</alto>
               	<ancho xsi:type="xsd:int">'.$ancho.'</ancho>
               	<largo xsi:type="xsd:int">'.$largo.'</largo>
               	<peso xsi:type="xsd:float">'.$peso.'</peso>
               	<unidades xsi:type="xsd:int">'.$unidades.'</unidades>
               	<referencia xsi:type="xsd:string">'.$detalle_referencia.'</referencia>
               	<nombre_empaque xsi:type="xsd:string">'.$nombre_empaque.'</nombre_empaque>
                </item>
            </detalle>

            <cuenta_contable xsi:type="xsd:string"/>
            <centro_costos xsi:type="xsd:string"/>

            <recaudos xsi:type="ser:ArrayOfAgw_typeGuiaDetalleRecaudo" soapenc:arrayType="ser:Agw_typeGuiaDetalleRecaudo[]">
            </recaudos>

             <margen_izquierdo xsi:type="xsd:float">'.$margen_izquierdo.'</margen_izquierdo>
            <margen_superior xsi:type="xsd:float">'.$margen_superior.'</margen_superior>
            <usuario_vmi xsi:type="xsd:string"/>
            <formato_impresion xsi:type="xsd:string">'.$formato_impresion.'</formato_impresion>
            <atributo1_nombre xsi:type="xsd:string"/>
            <atributo1_valor xsi:type="xsd:string"/>

            <notificaciones xsi:type="ser:ArrayOfAgw_typeNotificaciones" soapenc:arrayType="ser:Agw_typeNotificaciones[]">
              <item>
                 <tipo_medio xsi:type="xsd:int">'.$tipo_medio.'</tipo_medio>
                 <destino_notificacion xsi:type="xsd:string">'.$destino_notificacion.'</destino_notificacion>
               </item>
             </notificaciones>

             <atributos_retorno type="ser:Agw_typeAtributosRetorno">
                <nit type="xsd:string"></nit>
                <div type="xsd:string"></div>
                <nombre type="xsd:string"></nombre>
                <direccion type="xsd:string"></direccion>
                <codigo_ciudad type="xsd:string"></codigo_ciudad>
                <telefono type="xsd:string"></telefono>
               </atributos_retorno>

               <nro_doc_radicados type="xsd:string"></nro_doc_radicados>
               <nro_sobre type="xsd:string"></nro_sobre>
            <usuario xsi:type="xsd:string">'.$usuario.'</usuario>
            <clave xsi:type="xsd:string">'.hash('sha256',$clave).'</clave>
                     </p>
      </ser:Guias_generarGuia>
   </soapenv:Body>
</soapenv:Envelope>';



$pdf_content = get_web_page($url,$input_xml);
// Cadena de inicio del PDF
$findme='JVBER';
// cadena de inicio de la url
$findme2='http://sandbox';
// Cadena de Inicio del numero de guia
$findme3='2216';
// posicion de inicio del PDF
$pos1 = stripos($pdf_content, $findme);
$pos3= stripos($pdf_content, $findme3);
// extrae el contenido del pdf con la url
$pdf_content2 = substr($pdf_content,$pos1);

//posicion donde inicia la url vmi de coordinadora
$pos2=stripos($pdf_content2,$findme2);

// longitud del pdf
$longitud=strlen($pdf_content2);
// extrae el codigo de la remision
$codigo_remision=substr($pdf_content,$pos3,11);
// extrae el pdf en codificacion base64
$pdf_content3=substr($pdf_content2,0,$longitud-($longitud-$pos2));

 $dir = '/var/www/html/uploads/';
 $date = date('Y-m-d');
 $random = rand(1000,9999);
 $name = strtolower($date.'-'.$random.'.pdf');
 $file_dir = $dir .$name;//.basename($_FILES['file']['name']);
 $url = 'http://'.$_SERVER['SERVER_ADDR'].':'.$_SERVER['SERVER_PORT'].'/uploads/'.$name;



  $pdf_decoded = base64_decode ($pdf_content3); //Write data back to pdf file
  try {
  $pdf = fopen ($file_dir,'w');
  fwrite ($pdf,$pdf_decoded);
  //close output file
   fclose ($pdf);
  // move_uploaded_file($pdf,$file_dir);
   echo $url.'+++'.$codigo_remision;
}catch (Exception $e) {
    echo 'Excepción capturada: ',  $e->getMessage(), "\n";
}




function get_web_page($url,$input_xml) {
    $options = array(
        CURLOPT_RETURNTRANSFER => true,   // return web page
        CURLOPT_HEADER         => false,  // don't return headers
        CURLOPT_FOLLOWLOCATION => true,   // follow redirects
        CURLOPT_MAXREDIRS      => 10,     // stop after 10 redirects
        CURLOPT_ENCODING       => "",     // handle compressed
        CURLOPT_USERAGENT      => "test", // name of client
        CURLOPT_AUTOREFERER    => true,   // set referrer on redirect
        CURLOPT_CONNECTTIMEOUT => 120,    // time-out on connect
        CURLOPT_TIMEOUT        => 120,    // time-out on response
        CURLINFO_HEADER_OUT  => true,
        CURLOPT_HTTPHEADER    =>Array("Content-Type: text/xml"),
        CURLOPT_POST          =>1,
        CURLOPT_POSTFIELDS     =>$input_xml
    );

    $ch = curl_init($url);
    curl_setopt_array($ch, $options);

    $content  = curl_exec($ch);


    curl_close($ch);

    return $content;
}
