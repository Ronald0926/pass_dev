<?php


$numorden = $_POST['NumOrden'];
$valorden = $_POST['ValOrden'];
$nomusuar = $_POST['NomUsuar'];

echo '<input type="hidden" id="codOrden" value="'.$numorden.'" />';

$post["NumOrden"] = $numorden ;

///echo $nomusuar;

$ch = curl_init('http://192.168.10.30:8080/ords/procesoypago/proceso/ordenes');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
$response = curl_exec($ch);
$error = curl_error($ch);
curl_close($ch);
$PRUEBA = explode('"',$response);
$PRUEBA = $PRUEBA[5];
?>
<script type="text/javascript">
    varnumprden =  document.getElementById("codOrden").value; 
    
	 window.open('http://192.168.10.30:8080/ords/f?p=102:35000:::NO:RP,35000:'
	 +'P35000_ORDEN,P35000_PAGAR,P35000_RESPUESTA:'+
	 varnumprden +',NO,<?=$PRUEBA ?>',"_self");
</script>
