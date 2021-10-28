<?php

$documento =$_POST['DOCUMENTO'];
$nombre=$_POST['NOMBRE'];
$auxurl=$_POST['URL']; //https://inspektortest.datalaft.com:76/WSInspektor.asmx
$pass=$_POST['PASS']; //|kPBDSHv-,0!26u

//$url='https://inspektortest.datalaft.com:76/WSInspektor.asmx/LoadWSInspektor?Numeiden='.$documento.'&Nombre='.$nombre.'&Password=|kPBDSHv-,0!26u';
$url=$auxurl.'/LoadWSInspektor?Numeiden='.$documento.'&Nombre='.$nombre.'&Password='.$pass;
$url=str_replace(' ', '%20', $url);
// echo($url);
 //Get cURL resource
$curl = curl_init();
// Set some options - we are passing in a useragent too here
curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => $url,
    CURLOPT_USERAGENT => 'Inspektor',
    CURLOPT_TIMEOUT => 1000
));
// Send the request & save response to $resp
$resp = curl_exec($curl);
if (curl_errno($curl)) { 
   print curl_error($curl);
}


// Close request to clear up some resources
curl_close($curl);

ECHO($resp);
