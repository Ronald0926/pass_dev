<?php

require_once  '../mpdf/vendor/autoload.php';


$idproceso =$_POST['PROCESO'];
$cliente=$_POST['CLIENTE'];
$contacto=$_POST['CONTACTO'];
$datos= $_POST['DETALLE'];
$servicios=$_POST['SERVICIOS'];
$abono=$_POST['ABONO'];
$tasa=$_POST['TASA'];
$iva=0.19;

$tasa = str_replace(",",".",$tasa);

$detalles=explode(";",$datos);

$tTarjetas = 0;
$tTarjetasV = 0;
$tAbono = 0;
$tAdmin = 0;
$tIva = 0;

foreach ($detalles as $x => $x_value){
$dats= $x_value;
$content = explode(",", $dats);

	if($content[0] != ""){
	
		  $data = $data."
      <style>
      body { 
    font-family: Calibri;  
  }
  </style>
		  <tr>
				<td>" . $content[0] . "</td>
				<td> " . number_format($content[1]) . "</td>
				<td>$ " . number_format($content[2]) . "</td>
				<td>$ " . number_format($content[3]) . "</td>
				<td>$ " . number_format($content[4]) . "</td>";
				
                  IF($tasa>100){
                      $data = $data . "<td>$" . $tasa . " por Cada Dispersi&oacute;n</td>
				<td> No Disponible</td>
				<td> No Disponible</td>";
                  } else {
                      $data = $data . "<td>" . $tasa . "%</td>
				<td>$ " . number_format(($content[4]* $tasa) / 100) . "</td>
				<td>$ " . number_format(($content[3]+($content[4]* $tasa / 100)) * $iva,0) . "</td>";
                  }
                  
		  $data = $data . "</tr>";
		  
		  $tTarjetas += $content[1];
		  $tTarjetasV += $content[3];
		  $tAbono += $content[4];
          IF($tasa>100){
		  $tAdmin += (0);
		  $tIva += (0);
          }else{
          $tAdmin += ($content[4]* $tasa / 100);
		  $tIva += (($content[3]+($content[4]* $tasa / 100)) * $iva);
                   }
		
	}

}
  $data = $data."
  <tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
  </tr>";
  $data = $data."
  <tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
  </tr>";
  $data = $data."
  <tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
  </tr>";
  $data = $data."
  <tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
  </tr>";
  $data = $data."
  <tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
  </tr>";
  
  $data = $data."
  <tr>
		<th>SUBTOTAL</th>
		<td style='font-weight: bold;'>" . number_format($tTarjetas,0) . "</td>
		<td style='background-color: #777'></td>
		<td style='font-weight: bold;'>$ " . number_format($tTarjetasV,0) . "</td>
		<td style='font-weight: bold;'>$ " . number_format($tAbono,0) . "</td>
		<td style='background-color: #777'></td>
		<td style='font-weight: bold;'>$ " . number_format($tAdmin,0) . "</td>
		<td style='font-weight: bold;'>$ " . number_format($tIva,0) . "</td>
  </tr>";
  $data = $data."
  <tr>
		<th>TOTAL GENERAL</th>
		<td colspan='7'  style='font-weight: bold;'>$ " . number_format(($tTarjetasV + $tAbono + $tAdmin + $tIva),0) . "</td>
  </tr>";

$var='<table cellspacing="0" class="table-border-1" style="text-align: center; font-size: 10px"  >
                            <tr>
                                <th>Producto </th>
                                <th>Cant. tarjetas</th>
                                <th>Costo tarjeta</th>
                                <th>Valor total tarjetas</th>
								<th>Abono a realizar</th>
								<th>% Serv. Admin.</th>
								<th>Valor Serv. Admin.</th>
								<th>Valor IVA 19%</th>
                            </tr>';
      
 $css = "
 @page{
 }
 body {
	 background-image: url(Imagenes/marca.jpg); 
	 background-size: 50;
	 background-position: bottom left;
     background-repeat: no-repeat;
     background-image-resize: 4;
     background-image-resolution: from-image;
	 text-align: justify;
	 font-size: 13px;
	 font-family: century gothic;
 }
 .firmaH4{
	 margin-top: -25px;
	 padding: 0px;
 }
 .table-border-0{
	 width: 100%;
	 border:0px;
	 margin-left: -3px;
	 padding: 0px;
 }
 .table-border-0 tr td{
	 border:0px;
	 margin: 0px;
	 padding: 0px;
 }
  .table-border-1 tr td{
	 border:1px;
	 margin: 0px;
	 padding: 0px; 
 }
  .table-border-1 tr td, .table-border-1 tr th{
	 padding: 0;
	 border: 1px solid #000;
	 vertical-align: middle;
 }
 .text-10px{
	 text-size: 10px
 }
 ";
 
 $contenido='<style>
			
				th{
					background-color:#44546a;
					color: #FFF;
					font-weight: bold;
					}
					.otros {
					border: 0px solid white;
					}
					.otros td{
					border: 0px solid white;
					}
				</style>
				<div class="marcaAgua"> 
				
				<table class="table-border-0">
					<tr>
						<td>
							Bogot&aacute; D.C.,'.date('Y-m-d-H-i-s').'
						</td>
						<td style="width: 235">
							FO-GC-03 21/09/17 V2.0
						</td>
					</tr>
				</table>
				
				<br/>
				<br/>
				<br/>
				
				<p>
					Se&ntilde;ores:
					<br/>
					'.$cliente.'
					<br/>
					<br/>
					Sr:
					<br/>
					'.$contacto.'
				</p>
				
				<br/>
				<br/>
              
				<div style="text-align: center;">
					<h3>Ref.: OFERTA COMERCIAL DE MEDIOS DE PAGO PEOPLEPASS '.$idproceso.'</h3>
				</div>
				
				<div>
					<p>Reciba un cordial saludo, </p>
                
					<p>People Pass S.A. se esfuerza en establecer relaciones comerciales personalizadas y de largo plazo,
                  mediante la innovaci&oacute;n en soluciones tecnol&oacute;gicas y operativas integrales para contribuir con la motivaci&oacute;n
                  y el desarrollo de la calidad de vida de las personas.</p>
                
					<p> En este contexto, nos permitimos presentar nuestra oferta comercial sobre el servicio y bondades de las tarjetas peoplepass,
                   las cuales est&aacute;n dise&ntilde;adas para apoyar con eficiencia los procesos de compensaci&oacute;n, administrativos y de marketing,
                   entre otros. Estas tarjetas le permitir&aacute;n entregar a sus funcionarios y a terceros, bonos y vales electr&oacute;nicos.
					</p>
                
					<p> Nuestros productos le ayudar&aacute;n a cumplir con los criterios de seguridad, calidad, eficiencia,
                   costo y oportunidad que su compa&ntilde;&iacute;a necesita o desea mejorar, con la garant&iacute;a de contar con los mayores
                    est&aacute;ndares de seguridad de la industria.Estamos seguros que podremos apoyar y
                    solventar de una forma contundente sus requerimientos. </p>
					
					<br/>
					<br/>
				
					<p>Le agradecemos su atenci&oacute;n.<P>
                    
					<p>  Cordialmente,</p>
					<br/>
					<br/>
                    
					<div>   
						<img src="Imagenes/Firma.png" width="200"/>                   
						<h4 class="firmaH4">SANDRA GONZALEZ</h4>
						<h6>Gerente Comercial</h6>
						
					</div>
                </div> <!-- Portada -->
				
				
				<div><!--pagina PRODUCTOS  -->
                  <h4> NUESTROS PRODUCTOS</h4>
                  <p>Nuestra experiencia de m&aacute;s de 11 a&ntilde;os y la confianza de nuestros clientes nos convierten en un proveedor
                     de soluciones con un servicio altamente eficaz y oportuno, respaldado por la mejor tecnolog&iacute;a que nos
                     permite dise&ntilde;ar los siguientes productos:</p>
                     <div> <!--linea beneflex  -->
					 
					 <br/>
					 <br/>
					 
                     <table class="table-border-0">
                       <tr>
                        <td style="width: 120px;"><img src="Imagenes/beneflex.png" width="110"></td>
                        <td>Nuestra l&iacute;nea de productos Beneflex est&aacute; especialmente dise&ntilde;ada para asistirle y brindarle una efectiva y
                           eficiente soluci&oacute;n para cada una de las necesidades de la empresa,
                           permitiendo enriquecer sus ofertas laborales con planes de beneficios para sus colaboradores</td>
                      </tr>
                     </table>
					 
					 <br/>
					 <br/>
                     
					 <p style="margin-left: 50px">
                       - &nbsp; Tarjeta Market
					   <br/>
                       - &nbsp; Tarjeta Bienestar
					   <br/>
                       - &nbsp; Tarjeta Bienestar Salud
					   <br/>
                       - &nbsp; Tarjeta Combustible
					   <br/>
                       - &nbsp; Tarjeta Vestuario
                     </p>
                     </div>
                     <div><!--linea business  -->
					 <br/>
					 <br/>
                     <table class="table-border-0">
                       <tr>
						<td style="width: 120px;"><img src="Imagenes/business.png" width="110"></td>
                        <td>Es la &uacute;nica l&iacute;nea de productos creada para desnaturalizar los gastos corporativos,
                          evitando hacer pagos o giros a las cuentas de sus colaboradores,
                           facilitando la justificaci&oacute;n y administraci&oacute;n de los recursos entregados para el cumplimiento de sus labores.</td>
                      </tr>
                     </table>
					 
					 <br/>
					 <br/>
                     
					 <p>
                       - &nbsp; Tarjeta Medios de Transporte
					   <br/>
                       - &nbsp; Tarjeta Gastos de Viaje
					   <br/>
                       - &nbsp; Tarjeta Gastos de Representaci&oacute;n
					   <br/>
                       - &nbsp; Tarjeta Caja Menor
					   <br/>
                       - &nbsp; Tarjeta Business Car ? Gasolina
                     </p>
                     </div>

                     <div><!--linea pasarela  -->
					 <br/>
					 <br/>
                     <table class="table-border-0">
                       <tr>
                        <td style="width: 120px;"><img src="Imagenes/pasarela.png" width="110"></td>
                        <td>La mejor opci&oacute;n en la administraci&oacute;n de pagos por incentivos, premios y bonificaciones con tarjetas recargables
                           y de una sola carga que facilitan la operaci&oacute;n y brindan control y seguridad para su organizaci&oacute;n. Con los productos
                           de la l&iacute;nea Pasarela premie y reconozca la labor de todos los impulsos comerciales que le permiten
                          motivar e incentivar su productividad.</td>
                      </tr>
                     </table>
					 
					 <br/>
					 <br/>
					 
                     <p>
                       - &nbsp; Tarjeta Premio
					   <br/>
                       - &nbsp; Tarjeta Premio Plus
					   <br/>
                       - &nbsp; Tarjeta Zafiro
					   <br/>
                       - &nbsp; Tarjeta Zafiro Plus
                     </p>
                     </div>

                  </div> <!--pagina PRODUCTOS  -->


                  <div> <!--pagina caracteristicas medios de pago  -->
                  <h3>CARACTER&Iacute;STICAS DE LOS MEDIOS DE PAGO  </h3>
                  <ul>
                    <li>
						
					
					    El cliente tiene a disposici&oacute;n una plataforma en l&iacute;nea
						<img src="Imagenes/passonline.png" style="margin-bottom: -17px; width:90px">
						para la solicitud de tarjetas y solicitud de abonos a las mismas.
					<li>
                    La entrega de las tarjetas ser&aacute; en 5 d&iacute;as h&aacute;biles despu&eacute;s de solicitadas en PassOnline.</li>
                    <li>
                    Podr&aacute; hacer pago de los productos mediante los siguientes medios: Punto PSE en PassOnline,
                     transferencia electr&oacute;nica, consignaci&oacute;n bancaria  y/o cheque en consignaci&oacute;n, los cuales son administrados
                     por una entidad vigilada por la Superintendencia Financiera de Colombia.</li>
                    <li>
                    Los abonos tendr&aacute;n un tiempo m&aacute;ximo de 24 horas para ser aplicados debido a los tiempos de identificaci&oacute;n de los pagos.</li>
                    <li>
                    Los medios de pago son emitidos por una entidad vigilada por la Superintendencia Financiera de Colombia</li>
                    <li>Los medios de pago pueden ser recargables o no recargables de acuerdo a las caracter&iacute;sticas de cada producto</li>
                    <li>Los medios de pago tienen aceptaci&oacute;n total a nivel nacional e internacional en los establecimientos con datafono
                      vinculado a la franquicia VISA y en la red de cajeros automaticos, seg&uacute;n los par&aacute;metros del producto.</>
                    <li>Los medios de pago permiten acumular saldos y estos no se vencen</li>
                    <li>Los medios de pago admiten pagos exactos </li>
                    <li>Es posible la reexpedici&oacute;n o reposici&oacute;n de tarjetas, conservando su saldo.<li>
                    <li>Los medios de pago tendr&aacute;n acceso a las promociones que disponga la franquicia a la cual pertenece la tarjeta</li>
                    <li>Los tarjeta-habientes de los medios de pago cuentan con los siguientes canales de atenci&oacute;n: Portal web y correo
                       electr&oacute;nico a trav&eacute;s del sitio web <a href="http://www.peoplepass.com.co">www.peoplepass.com.co </a> o telef&oacute;nicamente a
                       trav&eacute;s de nuestra l&iacute;nea de atenci&oacute;n 7 x 24 en Bogot&aacute; en el 329 75 00 y a nivel nacional en el 01 8000 127 771. </li>
                    <li>Para usar los medios de pago en dat&aacute;fonos y cajeros autom&aacute;ticos se debe seleccionar la opci&oacute;n de tarjeta d&eacute;bito y cuenta de ahorros.</li>
                    <li>El primer env&iacute;o de tarjetas del mes a un &uacute;nico destino es gratuito.</li>
                    <li>El monto m&aacute;ximo de retiro diario en la red de cajeros automaticos es de $1.200.000.</li>
                    <li>El monto m&aacute;ximo de compras diarias en la red de dat&aacute;fonos es de $2.000.000.</li>
                  </ul>
                  </div> <!--pagina caracteristicas medios de pago  -->

				<br/>
				<br/>
				<br/>
				<br/>
				<br/>
				<br/>
				<br/>
				<br/>
				<br/>
				<br/>
				<br/>
				<br/>
				<br/>
				<br/>
				<br/>
				<br/>
				<br/>
				<br/>
					
                <div> <!-- modelo funcional -->
                  <h3>MODELO FUNCIONAL PEOPLEPASS</h3>
				  <br/>
				  <br/>
                  <p>peoplepass, como parte de su propuesta de valor, ha dise&ntilde;ado un modelo funcional de medios
                  de pago que le permitir&aacute; realizar transacciones a la empresa y sus colaboradores, con el fin
                  de apoyar los procesos de administraci&oacute;n, compensaci&oacute;n y motivaci&oacute;n de la compa&ntilde;&iacute;a:</P>
				  
				  <br/>
				  <br/>
				  <br/>
				  <br/>
				  
				  <div style="text-align: center;">
						<img src="Imagenes/empresa.png" width="80%">
				  </div>
				
                </div> <!-- modelo funcional -->
				
				<br/>
				<br/>
				<br/>
				<br/>
				<br/>
				<br/>
				<br/>
				<br/>
				<br/>
				<br/>
				<br/>
				<br/>
				<br/>
				<br/>
				<br/> 
				<div> <!-- valores de la cotizacion  -->
                  <h3>VALOR OFERTA COMERCIAL '.$idproceso.'</h3>
                  <p>El valor de los productos descritos a continuaci&oacute;n esta determinado por la cantidad de productos solicitados,
                   la cantidad de tarjetas y valores requeridos por el cliente; por lo cual, este valor est&aacute; sujeto a cambios</p>
				   
				  <br/>
				  <br/>
				  
                   '.$var.$data."</table>".'
				<br/>
				  <br/>
				  
                   <p class="text-10px">
                       * El valor de IVA resulta de multiplicar la Cantidad de Tarjetas por el Costo de la Tarjeta m&aacute;s el Valor del Servicio de Administraci&oacute;n.
					   <br/>
                       * La vigencia de la oferta de es de 90 d&iacute;as a partir de la fecha, una vez pasado este tiempo se evaluar&aacute; la posibilidad de mantener o actualizar los valores.
					   <br/>
                       * peoplepass se reserva el derecho de realizar cambios al valor de la presente oferta una vez se compruebe
                       que las anteriores proyecciones propuestas por el cliente, no han sido cumplidas.
                   </p>
                  
                </div> <!-- valores de la cotizacion  -->

				<br/>
				<br/>
				<br/>
				<br/>
				<br/>
				<br/>
				<br/>
				<br/>
				<br/>
				<br/>
				<br/>
				<br/>
				<br/>
				<br/>
				<br/>
				<br/>
				<br/>
				<br/>
				<br/>
				<br/>
				<br/>
				<br/>
				<br/>
				<br/>
        <br/>
				

                <div > <!-- Otros costos asociados-->
					
					<h4>  OTROS COSTOS ASOCIADOS </h4>
					
					<p>Los costos relacionados a continuaci&oacute;n no est&aacute;n incluidos en la comisi&oacute;n de servicio
					de administraci&oacute;n y en caso de causarse, estar&aacute;n a cargo del cliente o el beneficiario
					dependiendo del caso, as&iacute;:</p>
					<table>
             <tr>
               <td width="87%">
                   <li>	Env&iacute;os adicionales (Urbanos y rurales) - Cliente</li>
               </td> 
               <td width="13%">
               $ 10.500
               </td> 
             </tr>
             <tr>
               <td>
                  <li>	Reexpedici&oacute;n de tarjeta al tarjeta-habiente</li>
               </td>
               <td>
               $ 12.000
               </td>  
             </tr>
             <tr>
               <td>
                  <li>Cambio de PIN (clave) en cajero Servibanca</li>
               </td> 
               <td>
               $   2.200
               </td> 
             </tr>
             <tr>
               <td>
                  <li>Costos por retiro de efectivo en cajeros automaticos tarjeta-habientes *</li>
               </td> 
               <td>
               $   3.540<sup>nac</sup>
               </td> 
             </tr>
             <tr>
               <td></td> 
               <td>$ 12.000<sup>inter</sup></td> 
             </tr>
             <tr>
               <td>
                  <li>Costos por consultas de saldo en cajeros automaticos tarjeta-habientes*</li>
               </td>  
               <td>
               $   3.540<sup>nac</sup>
               </td> 
             </tr>
             <tr>
               <td></td> 
               <td>$ 12.000<sup>inter</sup></td> 
             </tr>
             <tr>
               <td>
                   <li>Transacci&oacute;n no exitosa (Fondos insuficientes - Pin Errado)*     </li>
               </td>
               <td>
               $   3.540
               </td>   
             </tr>
          </table>
					 

                <p>*Estos valores son establecidos por la red de cajeros, que ser&aacute; descontado del saldo de la tarjeta para cada
                   transacci&oacute;n procesada. Este servicio aplica para los bonos Bienestar, Zafiro plus, Premio Plus, Medios de
                   Transporte, Gastos de Representaci&oacute;n, Gastos de Viaje y Caja Menor.
                  Los anteriores valores podr&aacute;n ser actualizados por peoplepass en cualquier momento durante la prestaci&oacute;n
                  del servicio. Esta notificaci&oacute;n de aumento o disminuci&oacute;n de las tarifas, estar&aacute; disponible en el portal
                   web del tarjeta-habiente. Estas modificaciones obedecen a la variaci&oacute;n de las condiciones econ&oacute;micas
                    del mercado que impactan la operaci&oacute;n. </p>

                    <h4>VALORES AGREGADOS DE LA OFERTA</h4>

                    <ul>
                      <li>
                        Administraci&oacute;n sin costo del portal empresa 	
                        <img src="Imagenes/passonline.png" style="margin-bottom: -17px; width:90px"></li>
                        <br>
                        <li>Consulta de saldos sin costo, v&iacute;a telef&oacute;nica y a trav&eacute;s del portal web. </li>
                    <li>	Los medios de pago no tiene cuotas de manejo.</li>
                    </ul>
                    
                    <p>
                    Con el fin de apoyar su gesti&oacute;n contable, las facturas que recibir&aacute; manejaran dos cuerpos,
                    en una se discrimina el valor de sus abonos y en la otra se encuentra la comisi&oacute;n del servicio
                     de administraci&oacute;n, los env&iacute;os adicionales, el valor de la generaci&oacute;n de las tarjetas y los costos
                      adicionales (si aplica) junto con el correspondiente IVA.
                    Los impuestos y retenciones derivados de la generaci&oacute;n de los medios de pago son administrados y
                    ejecutados por el cliente.
                    </p>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <p>
                    Derechos Reservador 2011, PEOPLEPASS S.A.</br>
                    No est&aacute; permitida la reproducci&oacute;n total o parcial de este documento, ni su tratamiento inform&aacute;tico, ni la transmisi&oacute;n de ninguna forma o por cualquier medio, ya sea electr&aacute;nico o mec&aacute;nico, por fotocopia, por registro u otros m&eacute;todos, sin el permiso previo y por escrito de los titulares.
                    
                    <p style="text-align:right;">People Pass S.A<br>
                    Bogot&aacute;, Colombia, Sur Am&eacute;rica<br>
                    Tel&eacute;fono +571-7434700
                    </p>
                    
                </div>
				
			</div>';
 
    $dir = '/var/www/html/uploads/';
    $date = date('Y-m-d');
    $random = rand(1000,9999);
    $name = strtolower($date.'-'.$random.'.pdf');
    $file_dir = $dir .$name;
    //$url = 'http://'.$_SERVER['SERVER_ADDR'].':'.$_SERVER['SERVER_PORT'].'/uploads/'.$name;
    $url = 'https://www.peoplepassonline.co:8090/uploads/'.$name;
    $nombre=$file_dir;

$mpdf = new \Mpdf\Mpdf([
	'tempDir' => __DIR__ . '/tmp',
	'mode' => 'utf-8', 
	'format' => 'A4', 
	'margin_header' => 5,
	'margin_top' => 25,
	'margin_bottom' => 40,
	'margin_left' => 30,
	'margin_right' => 20,
  'default_font' => 'Calibri'
	]);
$html = mb_convert_encoding($contenido, 'UTF-8', 'UTF-8');

$mpdf->SetHTMLHeader ("
<div style='text-align: right;'>
<img src='Imagenes/logo.png' width='250' >
</div>
");

$mpdf->AddPage();
$mpdf->WriteHTML($css,\Mpdf\HTMLParserMode::HEADER_CSS);
$mpdf->WriteHTML($html);
$mpdf->Output($nombre, 'F');
echo $url;
?>
