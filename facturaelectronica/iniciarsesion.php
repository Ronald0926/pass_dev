
<?php
  
    $usuario=$_POST['USUARIO'];
    $clave=$_POST['CLAVE'];
  //  $url=$_POST['URL'];

$url = "http://test.comfiar.co/ws/WSComfiar.asmx";

$input_xml = '<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
  <soap:Body>
    <IniciarSesion xmlns="http://comfiar.com.ar/webservice/">
      <usuarioId>'.$usuario.'</usuarioId>
      <password>'.$clave.'</password>
    </IniciarSesion>
  </soap:Body>
</soap:Envelope>';



$result = get_web_page($url,$input_xml);


  try {
   echo $result;
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
