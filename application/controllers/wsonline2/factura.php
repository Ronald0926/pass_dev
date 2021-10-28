<?php

ini_set("pcre.backtrack_limit", "5000000");
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Factura extends CI_Controller {

    public function __construct() {
        parent::__construct();
//        if ($this->session->userdata('entidad') == NULL) {
//            redirect('/');
//        }
    }

    public function __destruct() {
        $this->db->close();
    }

    public function crear($numeroFactura = 0) {
        require_once 'mpdf/vendor/autoload.php';


        if ($_POST['NUMERO_FACTURA']) {
            $numeroFactura = $_POST['NUMERO_FACTURA'];
        }
        $prefijo = $this->db->query("SELECT VALOR_PARAMETRO 
                                    FROM  MODFACTUR.factblparfac
                                    WHERE pk_parfac_codigo=4");
        $prefijo = $prefijo->result_array[0];
        $dominio = $this->db->query("select VALOR_PARAMETRO from modgeneri.gentblpargen where PK_PARGEN_CODIGO = 96");
        $dominio = $dominio->result_array[0];
        $dominio = $dominio['VALOR_PARAMETRO'];
        // var_dump($numeroFactura);
        $ClienteFactura = $this->db->query("
		select UPPER(CLIENTE_PRIMER_NOMBRE||' '||CLIENTE_SEGUNDO_NOMBRE||' '||CLIENTE_APELLIDO) CLIENTE
		, CLIENTE_NUMERO_DOCUMENTO NIT
		, REPLACE (CLIENTE_DIRECCION,'|',' ') DIRECCION
		, CLIENTE_CIUDAD CIUDAD
		, CLIENTE_TELEFONO TELEFONO
		, TO_CHAR(FECHA_CREACION,'DD/MM/YYYY HH24:MM:SS') FECHA_FACTURA
		,NUMERO_FACTURA
		,NUMERO_RESOLUCION
		,MESES_VIGENCIA_RESOLUCION
		,RANGO_MINIMO_FACTURACION
		,RANGO_MAXIMO_FACTURACION
		,FECHA_RESOLUCION
                ,TO_CHAR(FECHA_CREACION,'DD/MM/YYYY') FECHA_FACT
                ,NVL(OBSER_FACTURA,0) OBSER_FACTURA
                ,PREFIJO_FACTURA
		FROM MODFACTUR.FACTBLFACTUR
		WHERE PK_FACTUR_CODIGO=$numeroFactura");


        $ClienteFactura = $ClienteFactura->result_array[0];
        //var_dump($ClienteFactura);

        $abonos = $this->db->query("select sum(1) CANTIDAD,
				'Abono a tarjetas de Producto '||LOWER(producto.nombre_producto) PRODUCTO,
				sum(detpab.monto) VALOR from
                                MODFACTUR.factblfacord facord
                                JOIN MODPROPAG.ppatblordcom ordcom
                                ON facord.pk_ordcom_codigo=ordcom.pk_ordcom_codigo
                                AND facord.pk_factur_codigo=$numeroFactura
                                JOIN MODPROPAG.ppatblpedabon pedabon 
                                ON pedabon.pk_orden=ordcom.pk_ordcom_codigo
                                JOIN MODPROPAG.ppatbldetpab detpab
                                ON detpab.pk_pedido=pedabon.pk_pedabon_codigo
                                JOIN MODPRODUC.PROTBLPRODUC producto
                                ON producto.pk_produc_codigo=detpab.pk_producto
                                group by detpab.pk_producto, producto.nombre_producto");
        $abonos = $abonos->result_array;

        $abonosllavemaestra = $this->db->query("SELECT  'Abono a tarjetas de Producto '||LOWER(producto.nombre_producto) PRODUCTO,
                                                SUM(CANTIDAD) CANTIDAD,
                                                SUM(MONTO) VALOR
                                                FROM MODFACTUR.factblfacord facord
                                                JOIN MODPROPAG.ppatblordcom ordcom ON facord.pk_ordcom_codigo=ordcom.pk_ordcom_codigo
                                                AND facord.pk_factur_codigo=$numeroFactura
                                                JOIN MODPROPAG.PPATBLDETORD do
                                                ON do.pk_orden_compra=ordcom.pk_ordcom_codigo
                                                 JOIN  MODPRODUC.PROTBLPRODUC producto
                                                ON producto.PK_PRODUC_CODIGO = do.PK_PRODUCTO
                                                left JOIN MODPRODUC.PROTBLPRODUC productoabonado
                                                ON do.PK_PRODUCTO_ABONADO=productoabonado.PK_PRODUC_CODIGO
                                                WHERE  do.indicador_abono=1 and ordcom.facturacion_llave=1 group by productoabonado.PK_PRODUC_CODIGO,producto.nombre_producto");
        $abonosllavemaestra = $abonosllavemaestra->result_array;

        //var_dump($abonos);
        $detallePropios = $this->db->query("SELECT   	
            'Precio Tarjetas '||LOWER(produc.nombre_producto) PRODUCTO,
            sum(valor_unit) VALOR_UNITARIO, 
            sum (cantidad) CANTIDAD ,
            sum (cantidad * valor_unit) TOTAL
			FROM  MODFACTUR.FACTBLFACORD facord
			JOIN MODPROPAG.PPATBLDETORD detord
			ON facord.pk_ordcom_codigo = detord.pk_orden_compra 
			AND facord.pk_factur_codigo=$numeroFactura
			INNER JOIN  MODPRODUC.PROTBLPRODUC produc
			ON produc.PK_PRODUC_CODIGO = detord.PK_PRODUCTO
			and produc.pk_tippro_codigo=1
            AND detord.pk_pedido is not null
            group by detord.PK_PRODUCTO_ABONADO ,'Precio Tarjetas '||LOWER(produc.nombre_producto)
            UNION ALL
            SELECT   	
            LOWER(produc.nombre_producto)||' abono '||LOWER(productoabonado.nombre_producto) PRODUCTO,
            sum(valor_unit) VALOR_UNITARIO, 
            sum (cantidad) CANTIDAD ,
            sum (cantidad * valor_unit ) TOTAL
			FROM  MODFACTUR.FACTBLFACORD facord
			JOIN MODPROPAG.PPATBLDETORD detord
			ON facord.pk_ordcom_codigo = detord.pk_orden_compra 
			AND facord.pk_factur_codigo=$numeroFactura
			INNER JOIN  MODPRODUC.PROTBLPRODUC produc
			ON produc.PK_PRODUC_CODIGO = detord.PK_PRODUCTO
            INNER JOIN MODPRODUC.PROTBLPRODUC productoabonado
            ON detord.PK_PRODUCTO_ABONADO=productoabonado.PK_PRODUC_CODIGO
			and produc.pk_tippro_codigo=3
                        and produc.PK_PRODUC_CODIGO=999999
            group by detord.PK_PRODUCTO_ABONADO, 
            LOWER(produc.nombre_producto)||' abono '||LOWER(productoabonado.nombre_producto)
            UNION ALL 
            SELECT   	
            LOWER(produc.nombre_producto) PRODUCTO,
            valor_unit VALOR_UNITARIO, 
            cantidad CANTIDAD ,
            cantidad * valor_unit TOTAL
			FROM  MODFACTUR.FACTBLFACORD facord
			JOIN MODPROPAG.PPATBLDETORD detord
			ON facord.pk_ordcom_codigo = detord.pk_orden_compra 
			AND facord.pk_factur_codigo=$numeroFactura
			INNER JOIN  MODPRODUC.PROTBLPRODUC produc
			ON produc.PK_PRODUC_CODIGO = detord.PK_PRODUCTO
            AND detord.pk_pedido IS NULL 
            AND detord.pk_pedido_abono  IS NULL 
			and produc.pk_tippro_codigo=3 
            and detord.abono_llave_maestra <>1
            UNION ALL 
             SELECT   	
            LOWER(produc.nombre_producto) PRODUCTO,
            sum(valor_unit) VALOR_UNITARIO, 
            sum(cantidad) CANTIDAD ,
            sum(valor_unit) TOTAL
			FROM  MODFACTUR.FACTBLFACORD facord
			JOIN MODPROPAG.PPATBLDETORD detord
			ON facord.pk_ordcom_codigo = detord.pk_orden_compra 
			AND facord.pk_factur_codigo=$numeroFactura
			INNER JOIN  MODPRODUC.PROTBLPRODUC produc
			ON produc.PK_PRODUC_CODIGO = detord.PK_PRODUCTO
            AND detord.pk_pedido IS NULL 
            AND detord.pk_pedido_abono  IS NULL 
			and produc.pk_tippro_codigo=3
             and produc.PK_PRODUC_CODIGO=999910
             GROUP by LOWER(produc.nombre_producto)");
                
                
//                $this->db->query("
//			SELECT   
//			case when produc.pk_tippro_codigo = 1 then 		
//            'Precio Tarjetas '||LOWER(produc.nombre_producto)
//            else 
//            LOWER(produc.nombre_producto)
//            end PRODUCTO,
//            valor_unit VALOR_UNITARIO, 
//            sum (cantidad) CANTIDAD ,
//            sum (cantidad) * valor_unit TOTAL       
//			FROM  MODFACTUR.FACTBLFACORD facord
//			JOIN MODPROPAG.PPATBLDETORD detord
//			ON facord.pk_ordcom_codigo = detord.pk_orden_compra 
//			AND facord.pk_factur_codigo=$numeroFactura
//			INNER JOIN  MODPRODUC.PROTBLPRODUC produc
//			ON produc.PK_PRODUC_CODIGO = detord.PK_PRODUCTO
//			and produc.pk_tippro_codigo!=2
//			GROUP BY  case when produc.pk_tippro_codigo = 1 then 
//            'Precio Tarjetas '||LOWER(produc.nombre_producto)
//            else 
//            LOWER(produc.nombre_producto)
//            end, valor_unit, monto
//                                ORDER BY 1 ASC");

        $detallePropios = $detallePropios->result_array;

        $valorimpuestos = $this->db->query("select 
						fac.pma INGRESOS_TERCEROS,
						MODFACTUR.facpkgconsultas.fncconsultarimpuestofactura(parpkfactura=>FAC.pk_factur_codigo,parnombreimpuesto=>'IVA%') IVA,
						MODFACTUR.facpkgconsultas.fncconsultarimpuestofactura(parpkfactura=>FAC.pk_factur_codigo,parnombreimpuesto=>'RTE FTE%') RTE_FUENTE,
						MODFACTUR.facpkgconsultas.fncconsultarimpuestofactura(parpkfactura=>FAC.pk_factur_codigo,parnombreimpuesto=>'RTE ICA%') RTE_ICA,
						MODFACTUR.facpkgconsultas.fncconsultarimpuestofactura(parpkfactura=>FAC.pk_factur_codigo,parnombreimpuesto=>'RTE IVA%') RTE_IVA,
						FAC.PCO INGRESOS_PROPIOS,
						FAC.TOTAL TOTAL
						from MODFACTUR.factblfactur fac
						where fac.pk_factur_codigo =$numeroFactura");

        $valorimpuestos = $valorimpuestos->result_array[0];
        $ingresos_propios = $valorimpuestos['INGRESOS_PROPIOS'];
        $ingresos_terceros = $valorimpuestos['INGRESOS_TERCEROS'];
        $iva = $valorimpuestos['IVA'];
        $rte_fuente = $valorimpuestos['RTE_FUENTE'];
        $rte_ica = $valorimpuestos['RTE_ICA'];
        $rte_iva = $valorimpuestos['RTE_IVA'];
        $subtotal_propios = 0;
        //$subtotal_propios = $ingresos_propios - $iva - $rte_fuente - $rte_ica - $rte_iva;

        $total_factura = $valorimpuestos['TOTAL'];
        if ($total_factura == '') {
            $total_factura = 0;
        }
        $valorletras = $this->numeros_letras($total_factura);

        $numabonos = 0;
        //var_dump($detallePropios);
        foreach ($abonos as $value) {
            $abonos = $abonos . '<tr class="spanblue2" >
                        <td style="text-align:center;border-right:2px solid black;" class="spanblue2">' . number_format($value['CANTIDAD']) . '</td>
                        <td style="text-align:left;border-right:2px solid black;" class="spanblue2">' . ucfirst($value['PRODUCTO']) . '</td>
                        <td style="text-align:right;border-right:2px solid black;" class="spanblue2">' . number_format($value['VALOR']) . '</td>
                        <td style="text-align:right" class="spanblue2">' . number_format($value['VALOR']) . '</td>
                        </tr> ';
            $numabonos = $numabonos + 1;
        }
        foreach ($abonosllavemaestra as $value) {
            $abonos = $abonos . '<tr class="spanblue2" >
                        <td style="text-align:center;border-right:2px solid black;" class="spanblue2">' . number_format($value['CANTIDAD']) . '</td>
                        <td style="text-align:left;border-right:2px solid black;" class="spanblue2">' . ucfirst($value['PRODUCTO']) . '</td>
                        <td style="text-align:right;border-right:2px solid black;" class="spanblue2">' . number_format($value['VALOR']) . '</td>
                        <td style="text-align:right" class="spanblue2">' . number_format($value['VALOR']) . '</td>
                        </tr> ';
            $numabonos = $numabonos + 1;
        }

        $numpropios = 0;

        foreach ($detallePropios as $value) {


            $propios = $propios . '<tr >
				<td style="text-align:center;border-right:2px solid black;" class="spanblue2">' . number_format($value['CANTIDAD']) . '</td>
				<td style="text-align:left;border-right:2px solid black;" class="spanblue2">' . ucfirst($value['PRODUCTO']) . '</td>
				<td style="text-align:right;border-right:2px solid black;" class="spanblue2">' . number_format($value['VALOR_UNITARIO']) . '</td>
				<td style="text-align:right" class="spanblue2">' . number_format($value['TOTAL']) . '</td>
			</tr>';
            $subtotal_propios = $subtotal_propios + $value['TOTAL'];
            $numpropios = $numpropios + 1;
        }
        $totalLineas = $numpropios + $numabonos;
        switch ($totalLineas) {
            case 23:
                $tamanoLetra = 11;
                break;
            case 24:
                $tamanoLetra = 10;
                break;
            case 25:
                $tamanoLetra = 9;
                break;
            case 26:
                $tamanoLetra = 9;
                break;
            case 27:
                $tamanoLetra = 8;
                break;
            case 28:
                $tamanoLetra = 7;
                break;
            case 29:
                $tamanoLetra = 7;
                break;
            case 30:
                $tamanoLetra = 6;
                break;
            case 31:
                $tamanoLetra = 6;
                break;
            case 32:
                $tamanoLetra = 5;
                break;
            case 33:
                $tamanoLetra = 5;
                break;
            case 34:
                $tamanoLetra = 5;
                break;
            case 35:
                $tamanoLetra = 4;
                break;
            case 36:
                $tamanoLetra = 4;
                break;
            default:
                $tamanoLetra = 12;
                break;
        }
        $obser='';
        if($ClienteFactura['OBSER_FACTURA']!='0'){  
                    $obser='<tr>
                                <td colspan=4 style="border-top:2px solid black;" class="spanblue">
                                '.$ClienteFactura['OBSER_FACTURA'].'
                                </td>
                            </tr>';
        }


        if ($totalLineas < 22) {
           if($ClienteFactura['OBSER_FACTURA']!='0'){
               $totalLineas=$totalLineas-1;
               $valor = 18 - $totalLineas;
           }else{
               $totalLineas;
               $valor = 20 - $totalLineas;
           }
                   
            
            for ($i = 3; $i < $valor; $i++) {
                $propios = $propios . '<tr >
				<td style="text-align:center;border-right:2px solid black;" class="spanwhite"></td>
				<td style="text-align:left;border-right:2px solid black;" class="spanwhite">T</td>
				<td style="text-align:right;border-right:2px solid black;" class="spanwhite">000</td>
				<td style="text-align:right" class="spanwhite">000</td>
			</tr>';
            }
        }
        $tamanoLetra2=15;
        $caracteresdireccion = strlen($ClienteFactura['DIRECCION']);
        if($caracteresdireccion>41){
            $tamanoLetra2=11;
        }
        $css = "
		body {

			background-size: 50;
			background-position: bottom left;
			background-repeat: no-repeat;
			background-image-resize: 4;
			background-image-resolution: from-image;
			text-align: justify;
			font-size: 13px;
			font-family: Arial;
		}
		.div-principal{
			width: 842px;
			height: 1191px;
		}
		.widthcien{
			width:100%;
		}	
		.tabla-principal{
			margin:5px;
			width:100%;
			border-radius:10px;
			border:2px solid;
		}
		.spanblue{
			color:#39587f;
			font-size: 15px;
		}
		.spanblue2{
			color:#39587f;
			font-size: {$tamanoLetra}px;
		}
                .spanblue3{
			color:#39587f;
			font-size: {$tamanoLetra2}px;
		}
		.spanwhite{
			color:white;
			font-size: 15px;
		}
		td{
			padding-left:5px;
			padding-right:5px;
			padding-top:5px;
			padding-bottom:5px;
		}
		.codeqr{
			-webkit-print-color-adjust: exact;
			background-color:#ceb6b6;
		}
		.impuestos{
                    position: absolute;
                    margin-bottom:0%;
                    left: 0;
                    bottom: 0;
                    width: 90%;
                    text-align: center;
                    margin-right: 34px;
                    margin-left: 38px;
		}
		";

        $header = '
				<div class="widthcien" >
					<div style="width:35%;position:absolute;float:left;margin-bottom:0px" >
						<img width="260" height="100" src="static/img/wsonline2/logo-nit.png">                                             
					</div>
					<div  style="width:64%;position:absolute;border:2px solid black;border-radius:10px;float:right;">
						<div style="width:100%;position:absolute;float:left;margin-left:5px;margin-top:5px">
							Cliente: <span class="spanblue">' . $ClienteFactura['CLIENTE'] . '</span><br>
							NIT: <span class="spanblue">' . $ClienteFactura['NIT'] . '</span><br>
							Direcci&oacute;n: <span class="spanblue3">' . $ClienteFactura['DIRECCION'] . '</span><br>
						</div>
						<div style="width:60%;position:absolute;float:left;margin-left:5px;">
							Tel&eacute;fono: <span class="spanblue">' . $ClienteFactura['TELEFONO'] . '</span><br>
							Ciudad: <span class="spanblue">' . $ClienteFactura['CIUDAD'] . '</span><br>
							Forma de pago: <span class="spanblue">CONTADO</span><br>
						</div>
						<div style="width:30%;position:absolute;float:right;margin-top:5px">
							FACTURA DE VENTA<br>
							<div style="border:2px solid black;border-radius:10px;margin-right:5px;text-align:center">
								<span style="font-size:18px;Color:#39587f;text-align:center">' . $ClienteFactura['PREFIJO_FACTURA'] . ' ' . $ClienteFactura['NUMERO_FACTURA'] . '</span>
							</div>
						</div>
						
					</div>
				</div>
				';



        $impuestos = '
		';



        $contenido = '
            	<div style="width:100%;text-align:right;position:relative;float:right;margin-top:5px;margin-bottom:5px"> Fecha de emisi&oacute;n ' . $ClienteFactura['FECHA_FACTURA'] . '</div>

		<div class="widthcien" >
	
			<div  style="width:100%;position:absolute;border:2px solid black;border-top-left-radius:10px;border-top-right-radius:10px;border-bottom-left-radius:10px;border-bottom-right-radius:10px">
				<table style="width:100%;border-collapse:collapse;" >
					<tr>
						<td style="text-align:center;border-right:2px solid black;padding:0px;width:50px">CANT</td>
						<td style="text-align:center;border-right:2px solid black;padding:0px;">DESCRIPCION</td>
						<td style="text-align:center;border-right:2px solid black;padding:0px;width:120px">VALOR UNITARIO</td>
						<td style="text-align:center;padding:0px;width:70px">TOTAL</td>
					</tr>
					<tr>
						<td colspan=4 style="text-align: center;background-color:#37567E;color:white;font-weight:bold;padding:0px;">
							<span>Exento de impuesto debido a que son ingresos para terceros</span>
						</td>
					</tr>
					' . $abonos . '
					
					<tr style="background-color:#37567E;">
						<td></td>
						<td style="color:white;font-weight:bold;padding:0px;">
							<span>Ingresos Propios</span>
						</td>
						<td></td><td></td>
					</tr>' . $propios . '
                                        '.$obser.' 
					<tr>
                                            <td colspan=4 style="border-top:2px solid black;font-size:7px">
                                             *Esta factura de venta se asimila en todos sus efectos a una letra de cambio y contempla lo contenido en los arts. 772 y siguientes del CCo., y las disposiciones de la ley 1231 de 2008. Despu&eacute;s de su vencimiento y en caso de no pago de las obligaciones aqu&iacute; contenidas, se causar&aacute;n intereses moratorios en los t&eacute;rminos permitidos por la Superintendencia Financiera de Colombia, hasta el d&iacute;a que se verifique su pago.  
                                            </td>
					</tr>
				</table>
			</div>
			' . $impuestos . '
		</div>

		';

        $footer = '
            
            <div class="widthcien impuestos" style="max-height:300px;min-height:300px;height:300px;margin-top:10px" >
					<div style="width:53%;float:left;text-align:justify;">
						<div class="codeqr" style="width:100%;position:absolute;border:2px solid black;
					margin-bottom:10px;margin-top:10px;border-radius:10px;" >
							<div style="width:50%;font-size:10px;border-right:2px solid black;padding:10px;">
								R&eacute;gimen Com&uacute;n Actividad econ&oacute;mica ICA 8299 Tarifa 9,66x1.000
								No somos Grandes Contribuyentes
								 - SOMOS AUTORRETENEDORES DE RENTA  seg&uacute;n Resoluci&oacute;n No 000976 del 12 de Febrero del 2020.
								Factura impresa por computador por PeoplePass S.A con NIT 900.209.956-1
								Autorizaci&oacute;n de facturaci&oacute;n por computador Seg&uacute;n Resoluci&oacute;n 
								DIAN No. ' . $ClienteFactura['NUMERO_RESOLUCION'] . ' 
								Fecha ' . $ClienteFactura['FECHA_RESOLUCION'] . '
								Numeraci&oacute;n ' . $ClienteFactura['PREFIJO_FACTURA']. ' ' . $ClienteFactura['RANGO_MINIMO_FACTURACION'] . '-' . $ClienteFactura['PREFIJO_FACTURA'] . ' ' . $ClienteFactura['RANGO_MAXIMO_FACTURACION'] . '
							</div>													   
						</div>
                                                <br/>
						<div style="width:100%;position:absolute;border:2px solid black;border-radius:10px;padding:15px;" >
							SON:' . $valorletras . ' Pesos M c/te							 
						</div>                            
					</div>
			<div  style="width:45%; border:2px solid black;float:right;margin-top:-2px;border-bottom-right-radius:10px;border-bottom-left-radius:10px;">
				<table style="width:100%;border-collapse:collapse;">
					<tr>
						<td style="border-right:2px solid black;border-bottom:2px solid black;width:50%">SUBTOTAL INGRESOS<BR>PROPIOS</td>
						<td style="border-bottom:2px solid black;text-align:right;width:50%">' . number_format($subtotal_propios) . '</td>
					</tr>
					<tr>
						<td style="border-right:2px solid black;border-bottom:2px solid black">IVA</td>
						<td style="border-bottom:2px solid black;text-align:right;">' . number_format($iva) . '</td>
					</tr>
					<tr>
						<td style="border-right:2px solid black;border-bottom:2px solid black">RTE FUENTE</td>
						<td style="border-bottom:2px solid black;text-align:right;">' . number_format($rte_fuente) . '</td>
					</tr>
					<tr>
						<td style="border-right:2px solid black;border-bottom:2px solid black">RTE ICA</td>
						<td style="border-bottom:2px solid black;text-align:right;">' . number_format($rte_ica) . '</td>
					</tr>
					<tr>
						<td style="border-right:2px solid black;border-bottom:2px solid black">RTE IVA</td>
						<td style="border-bottom:2px solid black;text-align:right;">' . number_format($rte_iva) . '</td>
					</tr>
					<tr>
						<td style="border-right:2px solid black;border-bottom:2px solid black">TOTAL INGRESOS PROPIOS</td>
						<td style="border-bottom:2px solid black;text-align:right;">' . number_format($ingresos_propios) . '</td>
					</tr>
					<tr>
						<td style="border-right:2px solid black;border-bottom:2px solid black">TOTAL INGRESOS PARA<BR>TERCEROS</td>
						<td style="border-bottom:2px solid black;text-align:right;">' . number_format($ingresos_terceros) . '</td>
					</tr>
					<tr>
						<td style="border-right:2px solid black">TOTAL A PAGAR</td>
						<td style="text-align:right;">' . number_format($total_factura) . '</td>
					</tr>
				</table>
			
			</div> 
                        
		</div>
	<div style="width:95%;text-align:justify;position:absolute;margin-top:17px">
		Transversal 55B # 115A 56 - Pbx: 743 47 00 soporte.cliente@peoplepass.com.co - www.peoplepass.com.co Bogot&aacute; D.C. 
	</div> ';

        $dir = 'uploads/facturas/';
        $date = date('Y-m-d');
        $random = rand(1000, 9999);
        $name = strtolower($date . '-' . $random . '.pdf');
        $file_dir = $dir . $name;
        // $url = 'http://' . $_SERVER['SERVER_ADDR'] . '/'.$dir . $name;
        $url = $dominio . '/' . $dir . $name;
        //$url = 'http://' . $_SERVER['SERVER_ADDR'] . ':' . $_SERVER['SERVER_PORT'] . '/uploads/' . $name;
        //$url = 'http://localhost:' . $_SERVER['SERVER_PORT'] . '/uploads/' . $name;
        $nombre = $file_dir;

        $mpdf = new \Mpdf\Mpdf([
            'tempDir' => 'mpdf/tmp',
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_header' => 8,
            'margin_footer' => 10,
            'margin_top' => 52,
            'margin_bottom' => 80,
            'margin_left' => 10,
            'margin_right' => 10,
            'default_font' => 'Calibri'
        ]);
        $html = mb_convert_encoding($contenido, 'UTF-8', 'UTF-8');

        $mpdf->SetHTMLHeader($header);
        $mpdf->SetHTMLFooter($footer);

        $mpdf->AddPage();
        $mpdf->WriteHTML($css, \Mpdf\HTMLParserMode::HEADER_CSS);
        $mpdf->WriteHTML($html);
        $mpdf->Output($nombre, 'F');
        // echo $url;
        $this->output->set_content_type('text/css');
        $this->output->set_output($url);
        return $url;
    }

    public function numeros_letras($valor = 0) {

        $sql = "begin 
             :varvalor:=modgeneri.genpkgutilidades.numero_a_letras($valor);
            end;";

        $conn = $this->db->conn_id;
        $stmt = oci_parse($conn, $sql);
        $valorletras = '';
        oci_bind_by_name($stmt, ':varvalor', $valorletras, 200);

        if (!oci_execute($stmt)) {
            $e = oci_error($stmt);
            $valorletras = '';
            VAR_DUMP($e);
            exit;
        }
        return ucwords($valorletras);
    }

}
