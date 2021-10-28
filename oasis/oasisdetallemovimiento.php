<?php

 /*RECIBE LOS PARAMETROS POST PARA ENVIAR A OASIS*/
$pempresa=$_POST['PEMPRESA'];
$pconsecutivo=$_POST['PCONSECUTIVO'];
$pcodigo_cuenta=$_POST['PCODIGO_CUENTA'];
$pcentro_costo=$_POST['PCENTRO_COSTO'];
$pvalor=$_POST['PVALOR'];
$pbase_retencion=$_POST['PBASE_RETENCION'];
$pretencion=$_POST['PRETENCION'];
$pdocumento_ref=$_POST['PDOCUMENTO_REF'];
$ptercero=$_POST['PTERCERO'];
$ipoasis=$_POST['PIPOASIS'];
$instancia=$_POST['PINSTANCIAS'];
$puerto=$_POST['PPUERTO'];
$dboasis=$_POST['PBDOASIS'];
$puid=$_POST['PUID'];
$pwd=$_POST['PPWD'];



$serverName = $ipoasis.'\\'.$instancia.','.$puerto;
$connectionInfo = array( "Database"=>$dboasis, "UID"=>$puid, "PWD"=>$pwd);


$conn = sqlsrv_connect( $serverName, $connectionInfo );
if( $conn === false ) {
    $err=sqlsrv_errors();
   $men = '';
			foreach( $err as $det ) {
				$men = "SQLSTATE: ".$det['SQLSTATE'];
				$men.= " code: ".$det['code'];
				$men.= " message: ".$det['message'];
			}
		
     	$log =  $men ."\r\n";
      echo $log ;
   exit();
}
else{
echo 'coneccion correcta';
}

$stmt = "{ CALL 	p_inserta_movimiento_detalle_people (?,?,?,?,?,?,?,?,?)}"; //calling stored procedure with single input parameter and 1 output parameter



$params = array(
                 array($pempresa, SQLSRV_PARAM_IN),
                 array($pconsecutivo, SQLSRV_PARAM_IN),
                 array($pcodigo_cuenta, SQLSRV_PARAM_IN),
                 array($pcentro_costo, SQLSRV_PARAM_IN),
                 array($pvalor, SQLSRV_PARAM_IN),
                 array($pbase_retencion, SQLSRV_PARAM_IN),
                 array($pretencion, SQLSRV_PARAM_IN),
                 array($pdocumento_ref, SQLSRV_PARAM_IN),
                 array($ptercero, SQLSRV_PARAM_IN)
               );

$result = sqlsrv_query( $conn, $stmt,$params);

if( $result === false ) 
	{ 
		echo "Error\n";
		if( ($err = sqlsrv_errors() ) != null) 
		{
			$men = '';
			foreach( $err as $det ) {
			//	$men = "SQLSTATE: ".$det['SQLSTATE'];
				$men.= "code: ".$det['code'];
				$men.= "message: ".$det['message'];
			}
				//	$log = date("H:i:s")." | ".$row["documento"]." | llamada al procedimiento p_inserta_movimiento_people|". $men ."\r\n";
     	$log =  $men ."\r\n";
      echo $log ;
		}	
	}
	else
	{
		//echo "procedimiento_oasis_detalle".$pconsecutivo;
		//sqlsrv_next_result($result); 
		//procedimiento_oasis_detalle($row,$pconsecutivo,$obs,$codigo,$grupo);
		echo "ok"; 
		sqlsrv_free_stmt( $stmt);
    sqlsrv_close( $conn);
	}
 
?>
