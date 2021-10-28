<?php

/* RECIBE LOS PARAMETROS POST PARA ENVIAR A OASIS*/
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
    die( print_r( sqlsrv_errors(), true));
}
else{
echo 'coneccion correcta';
}

//CONVERT(varchar,$pfecha, 101)

$stmt = "select * from kppm_people"; //calling stored procedure with single input parameter and 1 output parameter
                     
$result = sqlsrv_query( $conn, $stmt);

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
			$log = date("H:i:s")." | ".$row["documento"]." | llamada al procedimiento p_inserta_movimiento_people|". $men ."\r\n";
      echo $log.'----'.$pfecha ; 
		}	
	}
	else
	{
		//echo "procedimiento_oasis_detalle".$pconsecutivo;
		sqlsrv_next_result($result); 
		//procedimiento_oasis_detalle($row,$pconsecutivo,$obs,$codigo,$grupo);
		echo " Consecutivo:".$result; 
		sqlsrv_free_stmt( $stmt);
    sqlsrv_close( $conn);
	}

?>
