<?php

/* RECIBE LOS PARAMETROS POST PARA ENVIAR A OASIS*/
$pempresa=$_POST['PEMPRESA'];
$pcodigo_doc=$_POST['PCODIGO_DOC'];
$pdocumento_ref=$_POST['PDOCUMENTO_REF'];
$ptercero=$_POST['PTERCERO'];
$pobservacion=$_POST['POBSERVACION'];
$pfecha=$_POST['PFECHA'];
$pconsecutivo=0;

$ipoasis=$_POST['PIPOASIS'];
$instancia=$_POST['PINSTANCIAS'];
$puerto=$_POST['PPUERTO'];
$dboasis=$_POST['PBDOASIS'];
$puid=$_POST['PUID'];
$pwd=$_POST['PPWD'];

$stmt = "{ CALL p_inserta_movimiento_people (?,?,?,?,?,?,?)}"; //calling stored procedure with single input parameter and 1 output parameter


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

//CONVERT(varchar,$pfecha, 101)



$params = array(
                 array($pempresa, SQLSRV_PARAM_IN),
                 array($pcodigo_doc, SQLSRV_PARAM_IN),
                 array($pdocumento_ref, SQLSRV_PARAM_IN),
                 array($ptercero, SQLSRV_PARAM_IN),
                 array($pobservacion, SQLSRV_PARAM_IN),
                 array($pfecha, SQLSRV_PARAM_IN),
                 array(&$pconsecutivo, SQLSRV_PARAM_INOUT)
               );
                      
$result = sqlsrv_query( $conn, $stmt,$params);

if( $result === false ) 
	{ 
		echo "Error\n";
		if( ($err = sqlsrv_errors() ) != null) 
		{
			$men = '';
			foreach( $err as $det ) {
				$men = "SQLSTATE: ".$det['SQLSTATE'];
				$men.= " code: ".$det['code'];
				$men.= " message: ".$det['message'];
			}
		//	$log = date("H:i:s")." | ".$row["documento"]." | llamada al procedimiento p_inserta_movimiento_people|". $men ."\r\n";
     	$log =  $men ."\r\n";
      echo $log ; 
		}	
	}
	else
	{
		//echo "procedimiento_oasis_detalle".$pconsecutivo;
		sqlsrv_next_result($result); 
		//procedimiento_oasis_detalle($row,$pconsecutivo,$obs,$codigo,$grupo);
		echo " Consecutivo:".$pconsecutivo; 
		sqlsrv_free_stmt( $stmt);
    sqlsrv_close( $conn);
	}

?>
