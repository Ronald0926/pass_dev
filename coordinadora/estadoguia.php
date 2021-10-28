
<?php
    $numguia=$_POST['NUMGUIA'];   
    $usuario=$_POST['USUARIO'];
    $clave=$_POST['CLAVE'];

$url = "http://sandbox.coordinadora.com/agw/ws/guias/1.6/server.php";

$input_xml = '
<soapenv:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ser="http://sandbox.coordinadora.com/agw/ws/guias/1.6/server.php">
   <soapenv:Header/>
   <soapenv:Body>
      <ser:Guias_rastreoSimple soapenv:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/">
         <p xsi:type="ser:Agw_typeRastreoSimpleIn">
            <!--You may enter the following 3 items in any order-->
            <codigos_remision xsi:type="soapenc:Array" xmlns:soapenc="http://schemas.xmlsoap.org/soap/encoding/">
               <!--You may enter ANY elements at this point-->
              <item>'.$numguia.'</item>
            </codigos_remision>
            <usuario xsi:type="xsd:string">'.$usuario.'</usuario>
            <clave xsi:type="xsd:string">'.hash('sha256',$clave).'</clave>
		</p>
      </ser:Guias_rastreoSimple>
   </soapenv:Body>
</soapenv:Envelope>
            
        ';



$pdf_content = get_web_page($url,$input_xml);


   echo $pdf_content;




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
