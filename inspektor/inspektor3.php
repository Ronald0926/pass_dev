<?php 
$servicio= new SoapClient("http://inspektortest.datalaft.com:76/WSInspektor.asmx?wsdl"); //url del servicio
/*$parametros=array(); //parametros de la llamada
$parametros['Numeiden']="1049636770";
$parametros['Nombre']="Manuel";
$parametros['Password']="|kPBDSHv-,0!26u";
$result = $servicio->__soapCall('LoadWSInspektor', $parametros);
//var_dump($result);