<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of facturadorFacturacionElectronica
 *
 * @author ronald.rosas
 */
class FacturadorFacturacionElectronica extends CI_Controller {

    public $iniciLog = '[INFO] ';
    public $logHeader = 'FACTURADOR::::::::: ';
    public $postData = 'POSTDATA::::::::: ';
    public $soapCorrecto = 'CONSUMO SOAP CORRECTO::::::: ';
    public $finFuncion = ' FIN PROCEDIMIENTO::::::: ';
    public $errorFuncion = 'ERROR::::::: ';
    public $errorBD = 'ERROR BASE DE DATOS::::::: ';

    public function __construct() {
        parent::__construct();
        $this->load->helper('log4php');
    }

    public function iniciar_sesion($pk_factura) {

        log_info($this->logHeader . 'INGRESO LIBRERIA FACTURADOR FUNCION INICIAR_SESION Pk_factura_codigo: ' . $pk_factura);
        $usuarioComfiar = $this->retornarValorConfiguracion($pk_factura, 'USUARIO_COMFIAR');
        $passComfiar = $this->retornarValorConfiguracion($pk_factura, 'CONTRASENA_COMFIAR');

        //********Codigo iniciar sesion comfiar */
        if (!empty($usuarioComfiar) && !empty($passComfiar)) {
            $urlWsdl= $this->retornarValorConfiguracion($pk_factura, 'URL_WS_COMFIAR');
            $wsdl = $urlWsdl;
            $options = array(
                'cache_wsdl' => 0,
                'trace' => 1,
                'stream_context' => stream_context_create(array(
                    'ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    )
                ))
            );
            $client = new SoapClient($wsdl, $options);
            // web service input params
            $request_param = array(
                "usuarioId" => $usuarioComfiar, // dato usuario
                "password" => $passComfiar //contraseña inicio sesion
            );
            try {
                $responce_param = $client->IniciarSesion($request_param);
//            var_dump($responce_param);
//            var_dump($responce_param->IniciarSesionResult->SesionId);
                log_info($this->logHeader . $this->soapCorrecto . 'IniciarSesion::IniciarSesion::' . json_encode($responce_param));
                $response = $responce_param;
            } catch (Exception $e) {
//                echo "<h2>Exception Error!</h2>";
//                echo $e->getMessage();
                log_info($this->logHeader . $this->errorFuncion . 'ERROR SOAP ::IniciarSesion::' . $e->getMessage());
                $response = 'Error consumo iniciosesion :' . $e->getMessage();
            }
        } else {
            log_info($this->logHeader . $this->errorFuncion . '::IniciarSesion:: USUARIO o CONTRASEÑA NULOS.');
            $response = 'Datos incorrectos.';
        }


        return $response;
    }

    public function autorizar_Comprobante($sessionId = null, $fechaVen = null, $idFactura = null) {

        log_info($this->logHeader . 'INGRESO LIBRERIA Funcion autorizar_Comprobante');
        log_info($this->logHeader . $this->postData . 'ID_FACTURA= ' . $idFactura);
        //********Codigo autorizar comprobante*/
        if (!empty($sessionId) && !empty($fechaVen) && !empty($idFactura)) {
            $urlWsdl= $this->retornarValorConfiguracion($idFactura, 'URL_WS_COMFIAR');
            $wsdl = $urlWsdl;
            $options = array(
                'cache_wsdl' => 0,
                'trace' => 1,
                'stream_context' => stream_context_create(array(
                    'ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    )
                ))
            );
            $xml = $this->generarXMLFactura($idFactura);
            $client = new SoapClient($wsdl, $options);
            $cuitProcesar = $this->retornarValorConfiguracion($idFactura, 'NIT_CUITID');
            $puntoVentaId = $this->retornarValorConfiguracion($idFactura, 'ID_PUNTO_VENTA_FAC');
            $formatoId = $this->retornarValorConfiguracion($idFactura, 'FORMATO_ID');
            $tipoComprobanteId = $this->retornarValorConfiguracion($idFactura, 'ID_TIPO_COMPROBANTE_FAC');
            // web service input params
            $request_param = array(
                "XML" => $xml, // XML del comprobante a enviar.
                "cuitAProcesar" => $cuitProcesar, // Cuit, RUC o NIT del emisor del comprobante.
                "puntoDeVentaId" => $puntoVentaId, // Número de punto de venta a procesar
                "tipoDeComprobanteId" => $tipoComprobanteId, // Número del tipo de comprobante a procesar. Ejemplo 01: Factura, 04: Nota Crédito, 05: Nota Débito
                "formatoId" => $formatoId, //
                "token" => array(
                    "SesionId" => $sessionId,
                    "FechaVencimiento" => $fechaVen
                )
            );
            try {
                $responce_param = $client->AutorizarComprobantesAsincronico($request_param);
//            var_dump($responce_param);
//            var_dump($responce_param->IniciarSesionResult->SesionId);
//            //se comenta 28 para no guardar en log esa respuesta
               //log_info($this->logHeader . $this->soapCorrecto . 'AutorizarComprobantesAsincronico::' . json_encode($responce_param));
                log_info($this->logHeader . $this->soapCorrecto . ' AutorizarComprobantesAsincronico::');
                $response = $responce_param;
            } catch (Exception $e) {
//                echo "<h2>Exception Error!</h2>";
//                echo $e->getMessage();
                log_info($this->logHeader . $this->errorFuncion . 'ERROR SOAP::' . $e->getMessage());
                $response = 'Error consumo AutorizarComprobantesAsincronico :' . $e->getMessage();
            }
        } else {
            log_info($this->logHeader . $this->errorFuncion . 'SESSIONID , FECHAVEN , IDFACT DATOS NULOS.');
            $response = 'Datos incorrectos.';
        }
        return $response;
    }

    public function generarXMLFactura($idfactura = null) {

        if (!empty($idfactura)) {


            //datos configuracion comfiar
            $SqldatosConfiguracion = $this->db->query("SELECT 
              CONF.AMBIENTE_TRANSMISION,
                CONF.NIT_CUITID NIT,
                CONF.DIGITO_VERIFICACION,
                CONF.RESOLUCION,
                CONF.FECHA_INICIO_RESOL,
                CONF.FECHA_FIN_RESOL,
                CONF.RANGO_INICIO_RESOL,
                CONF.RANGO_FIN_RESOL,
                FAC.IDIOMA_FACTURA,
                CASE FAC.IDIOMA_FACTURA
                WHEN 'E' THEN
                CONF.PREFIJO_FACTURA
                WHEN 'I' THEN  
                CONF.PREFIJO_FACTURA_INT
                END PREFIJO_FACTURA,
                CONF.CORREO_EMISOR_FACTURACION,
                CONF.DIRECCION_EMISOR,
                CONF.TELEFONO_EMISOR,
                TRIB.NOMBRE_TRIBUTO,
                TRIB.CODIGO_TRIBUTO,
                EMI.NOMBRE,
                FAC.NUMERO_FACTURA,
                FAC.OBSER_FACTURA,
                FAC.PK_CLIENTE_CODIGO,
                FAC.SUBTOTAL,
                FAC.VALOR_TRM,
                FAC.FECHA_TRM
                From modfacturador.facturtblfacturacomfiar fac
                JOIN modfacturador.facturtblempemiconfig conf
                ON fac.pk_empresa_emisora = conf.pk_empresa_emisora
                JOIN modfacturador.facturtblempemisora emi
                ON conf.pk_empresa_emisora = emi.pk_empresa_emisora
                JOIN modfacturador.facturtbltributos trib
                ON conf.pk_tributo_codigo = trib.pk_tributo_codigo
                WHERE fac.pk_factura_codigo=$idfactura");
            $datosConfiguracion = $SqldatosConfiguracion->result_array[0];
            $ambienteTxComfiar = $datosConfiguracion['AMBIENTE_TRANSMISION'];
            $nitEmisor = $datosConfiguracion['NIT'];
            $digVerEmisor = $datosConfiguracion['DIGITO_VERIFICACION'];
            $resolucion = $datosConfiguracion['RESOLUCION'];
            $fechaInicioResol = $datosConfiguracion['FECHA_INICIO_RESOL'];
            $fechaFinResol = $datosConfiguracion['FECHA_FIN_RESOL'];
            $inicioRangoResol = $datosConfiguracion['RANGO_INICIO_RESOL'];
            $finRangoResol = $datosConfiguracion['RANGO_FIN_RESOL'];
            $prefijoFac = $datosConfiguracion['PREFIJO_FACTURA'];
            $correoEmisor = $datosConfiguracion['CORREO_EMISOR_FACTURACION'];
            $direccionEmisor = $datosConfiguracion['DIRECCION_EMISOR'];
            $telefonoEmisor = $datosConfiguracion['TELEFONO_EMISOR'];
            $nameTaxScheme = $datosConfiguracion['NOMBRE_TRIBUTO'];
            $idTaxScheme = $datosConfiguracion['CODIGO_TRIBUTO'];
            $emisorRegistrationName = $datosConfiguracion['NOMBRE'];
            $numeroFactura = $datosConfiguracion['NUMERO_FACTURA'];
            $observacionFactura = str_replace("&", "&amp;", $datosConfiguracion['OBSER_FACTURA']);
            $idiomaFactura = $datosConfiguracion['IDIOMA_FACTURA'];
            $valorTRM = $datosConfiguracion['VALOR_TRM'];
            $fechaTRM= $datosConfiguracion['FECHA_TRM'];
            $pkCliente = $datosConfiguracion['PK_CLIENTE_CODIGO'];
            $subtotalFactura = $datosConfiguracion['SUBTOTAL']; //equivale a lineExtensionAmount
            //data para tag AccountingCustomerParty información cliente
            
            $currencyCode='';
            $nota7='';
            $paymentExchangeRate='';
            if($idiomaFactura =='I'){
                $currencyCode ='USD';
                $nota7="After the expiration of this invoice, it will cause interest of the higest rate alloved by the law, the people signing accept have recieved satisfactory all the merchandise and / or services up mentioned; be the legal agent of the company or institution or to be legally allowed to recieve this document, and it is accepted by the buyer who undertake to pay unconditionally to PEOPLE TECH LATIN S.A.S., the price of this invoice and the taxes that it can generate, PEOPLE TECH LATIN S.A.S. reserves the domain in the merchandising up described until this invoice price it's totally paid.
Please sing the check to the name: PEOPLE TECH LATIN S.A.S.";
                $paymentExchangeRate='<cac:PaymentExchangeRate>
					<cbc:SourceCurrencyCode>USD</cbc:SourceCurrencyCode>
					<cbc:SourceCurrencyBaseRate>1.00</cbc:SourceCurrencyBaseRate>
					<cbc:TargetCurrencyCode>COP</cbc:TargetCurrencyCode>
					<cbc:TargetCurrencyBaseRate>1.00</cbc:TargetCurrencyBaseRate>
					<cbc:CalculationRate>'.$valorTRM.'</cbc:CalculationRate>
					<cbc:Date>'.$fechaTRM.'</cbc:Date>
				</cac:PaymentExchangeRate>';
            }else{
                $currencyCode='COP';
                $nota7='Esta factura de venta se asimila en todos sus efectos a una letra de cambio según el Art. 774 del código de comercio. Después de su vencimiento causará intereses a la tasa más alta permitida por la ley, los firmantes en aceptada declaración haber recibido totalmente las mercancías y/o servicios arriba mencionados y de manera satisfactoria, ser el representante legal de la empresa o institución o estar legalmente autorizado para recibir y firmar este documento, por lo tanto se da por aceptado de parte del comprador quien se obliga a pagar incondicionalmente a favor de PEOPLE TECH LATIN S.A.S, el valor de esta factura y los intereses que ella llegue a generar. PEOPLE TECH LATIN S.A.S se reserva el dominio de las mercancías arriba descrita hasta que no haya sido cancelada en su totalidad esta factura.	Favor girar cheque cruzado a nombre de PEOPLE TECH LATIN S.A.S.';
            }
                
                

            $SqlClienteFactura = $this->db->query("SELECT
                NVL(CLIE.RAZON_SOCIAL,CLIE.PRIMER_NOMBRE||' '||CLIE.APELLIDOS_CLIENTE) RAZON_SOCIAL,
                NVL(CLIE.PRIMER_NOMBRE,CLIE.RAZON_SOCIAL) PRIMER_NOMBRE,
                CLIE.SEGUNDO_NOMBRE,
                NVL(CLIE.APELLIDOS_CLIENTE,CLIE.RAZON_SOCIAL) APELLIDOS_CLIENTE,
                CLIE.PK_TIPORG_CODIGO, --1 juridica 2 natural
                CLIE.IDENTIFICACION NIT,
                CLIE.DIGITO_VERIFICACION,
                TIPDOC.CODIGO_COMFIAR,
                CLIE.CORREO_AUTORIZADO,
                CIU.CODIGO_DANE,
                PAIS.NOMBRE PAIS,
                NVl(CIU.NOMBRE,DEP.NOMBRE) CIUDAD,
                DEP.NOMBRE DEPARTAMENTO,
                CLIE.CODIGO_POSTAL,
                CLIE.DIRECCION_CLIENTE,
                RESPFIS.CODIGO RESPFIS,-- TaxLevelCode
                REGFIS.CODIGO TAXNAME,--TaxLevelCode@listName
                TRIB.NOMBRE_TRIBUTO,--TaxScheme @Name
                TRIB.CODIGO_TRIBUTO,--TaxScheme @ID
                CLIE.TELEFONO
                From modfacturador.facturtblclienteempresa clie
                JOIN  modfacturador.facturtbltipdoc tipdoc
                ON clie.pk_tipdoc_codigo =tipdoc.pk_tipdoc_codigo
                LEFT JOIN MODCLIUNI.CLITBLCIUDAD ciu
                ON ciu.pk_ciu_codigo=clie.clitblciudad_pk_ciu_codigo
                LEFT JOIN MODCLIUNI.CLITBLDEPPAI dep
                ON dep.pk_dep_codigo = clie.clitbldeppai_pk_dep_codigo  
                LEFT JOIN MODCLIUNI.clitblpais pais
                ON dep.clitblpais_pk_pais_codigo = pais.pk_pais_codigo
                JOIN modfacturador.facturtblresfiscal respfis
                ON clie.pk_responsabilidad_codigo = respfis.pk_responsabilidad_codigo
                JOIN modfacturador.facturtblregimenfiscal regfis
                ON clie.pk_regimen_codigo = regfis.pk_regimen_codigo
                JOIN modfacturador.facturtbltributos trib
                ON clie.pk_tributo_codigo =trib.pk_tributo_codigo
                Where clie.pk_cliente_codigo=$pkCliente");
            $ClienteFactura = $SqlClienteFactura->result_array[0];
            $nombreCliente = str_replace("&", "&amp;",$ClienteFactura['RAZON_SOCIAL']);
            $primerNombreCliente = str_replace("&", "&amp;",$ClienteFactura['PRIMER_NOMBRE']);
            $segundoNombreCliente = str_replace("&", "&amp;",$ClienteFactura['SEGUNDO_NOMBRE']);
            $apellidosCliente = str_replace("&", "&amp;",$ClienteFactura['APELLIDOS_CLIENTE']);
            $tipoPersona = intval($ClienteFactura['PK_TIPORG_CODIGO']);
            $nitCliente = $ClienteFactura['NIT'];
            $clienteDV = $ClienteFactura['DIGITO_VERIFICACION'];
            $clienteSchemeName = $ClienteFactura['CODIGO_COMFIAR']; //31 Nit 
            $emailCliente = $ClienteFactura['CORREO_AUTORIZADO'];
            $daneCliente = $ClienteFactura['CODIGO_DANE']; //RegistrationAddress @ID
            $clienteCodDep = substr($daneCliente, 0,2);  //CountrySubentityCode
            $pais = $ClienteFactura['PAIS'];
            $ciudadCliente = $ClienteFactura['CIUDAD'];
            $departamentoCliente = $ClienteFactura['DEPARTAMENTO'];
            $codigoPostal = $ClienteFactura['CODIGO_POSTAL'];
            $direccion_cliente = $ClienteFactura['DIRECCION_CLIENTE'];
            $taxLevelCode = $ClienteFactura['RESPFIS'];
            $taxLevelCodeListName = $ClienteFactura['TAXNAME'];
            $taxShemeName = $ClienteFactura['NOMBRE_TRIBUTO'];
            $taxShemeID = $ClienteFactura['CODIGO_TRIBUTO'];
            $telefono_cliente = $ClienteFactura['TELEFONO'];

            $AddicionalAc = '';
            $taxtCustomer = '';
            $AccountID = 0;
            $Person = '';
            if ($tipoPersona == 2) {
                $AccountID = 2;
                $Person = '<cac:Person>
				<cbc:ID>' . $nitCliente . '</cbc:ID>
				<cbc:FirstName>' . $primerNombreCliente . '</cbc:FirstName>
				<cbc:FamilyName>' . $apellidosCliente . '</cbc:FamilyName>
				<cbc:MiddleName>' . $segundoNombreCliente . '</cbc:MiddleName>
                            </cac:Person>';
                $AddicionalAc = '<cac:PartyIdentification>
                            <cbc:ID schemeName="13" schemeAgencyID="195"  schemeAgencyName="CO, DIAN (Dirección de Impuestos y Aduanas Nacionales)">' . $nitCliente . '</cbc:ID>
                        </cac:PartyIdentification>';
                $taxtCustomer = '<cbc:TaxLevelCode listName = "49">R-99-PN</cbc:TaxLevelCode>';
            } else if ($tipoPersona == 1) {
                $AccountID = 1;
                $Person = '';
                $taxtCustomer = '<cbc:TaxLevelCode listName = "' . $taxLevelCodeListName . '">' . $taxLevelCode . '</cbc:TaxLevelCode>';
            } else {
                $AccountID = 1;
                $Person = '';
                $taxtCustomer = '<cbc:TaxLevelCode listName = "49">R-99-PN</cbc:TaxLevelCode>';
            }
            $companyId = '';
            if ($clienteSchemeName == 31 || $clienteSchemeName == 50) {
                $companyId = '<cbc:CompanyID schemeID = "' . $clienteDV . '" schemeName = "' . $clienteSchemeName . '" schemeAgencyID = "195" schemeAgencyName = "CO, DIAN (Dirección de Impuestos y Aduanas Nacionales)">' . $nitCliente . '</cbc:CompanyID>';
            } else {
                $companyId = '<cbc:CompanyID  schemeName = "13" schemeAgencyID = "195" schemeAgencyName = "CO, DIAN (Dirección de Impuestos y Aduanas Nacionales)">' . $nitCliente . '</cbc:CompanyID>';
            }

            $SqlDetalleFactura = $this->db->query("SELECT 
                    PROD.CODIGO_PRODUCTO,
                    CASE
                    WHEN PROD.CODIGO_PRODUCTO =1003
                    THEN
                    DETFAC.DETALLE_OTRO
                    ELSE
                    PROD.NOMBRE_PRODUCTO
                    END NOMBRE_PRODUCTO,
                    DETFAC.VALOR_UNITARIO,
                    DETFAC.CANTIDAD,
                    DETFAC.PORC_IVA,
                    DETFAC.TOTAL_IVA,
                    DETFAC.TOTAL
                    FROM modfacturador.facturtbldetallefactura detfac
                    JOIN modfacturador.facturtblproductos prod
                    ON detfac.pk_producto_codigo = prod.pk_producto_codigo
                    WHERE detfac.pk_factura_codigo =$idfactura");
            $detalleFactura = $SqlDetalleFactura->result_array;

            $CalculoIva = 0;
            $idProduc = 1;
            $InvoiceLine = '';
            $cantInvoice = 0;
            //falta comenzar dibujar linea factura 

            $taxtTotal = '';
            $PricingReference = '';
            foreach ($detalleFactura as $value) {
                if ($value['VALOR_UNITARIO'] == 0) {
                    $PricingReference = '<cac:PricingReference>
                                            <cac:AlternativeConditionPrice>
                                            <!--Falta traer valor producto  -->
                                                <cbc:PriceAmount currencyID="'.$currencyCode.'">1.00</cbc:PriceAmount>
                                                <cbc:PriceTypeCode>01</cbc:PriceTypeCode> 
                                            </cac:AlternativeConditionPrice>
                                          </cac:PricingReference>';
                } else {
                    $PricingReference = '';
                }
                $TotalIva = $value['TOTAL_IVA'];
                $lineExte = $value['TOTAL'];
                $porcIva = $value['PORC_IVA'];

                $taxSubtotalPro = '<cac:TaxSubtotal>
                                    <cbc:TaxableAmount currencyID="'.$currencyCode.'">' . $lineExte . '</cbc:TaxableAmount>
                                    <cbc:TaxAmount currencyID="'.$currencyCode.'">' . $TotalIva . '</cbc:TaxAmount>
                                    <cac:TaxCategory>
                                        <cbc:Percent>' . $porcIva . '</cbc:Percent>
                                        <cac:TaxScheme>
                                            <cbc:ID>01</cbc:ID>
                                            <cbc:Name>IVA</cbc:Name>
                                        </cac:TaxScheme>
                                    </cac:TaxCategory>
                                </cac:TaxSubtotal>';
                $taxtTotal = $taxtTotal . $taxSubtotalPro;
                $InvoiceLine = $InvoiceLine .
                        '<cac:InvoiceLine>
          <cbc:ID>' . $idProduc . '</cbc:ID>
          <cbc:Note>' . date("Y-m-d") . '</cbc:Note>
          <cbc:InvoicedQuantity unitCode="94">' . intval($value['CANTIDAD']) . '</cbc:InvoicedQuantity>
          <cbc:LineExtensionAmount currencyID="'.$currencyCode.'">' . $lineExte . '</cbc:LineExtensionAmount>
              ' . $PricingReference . '
              <cac:TaxTotal>
		<cbc:TaxAmount currencyID="'.$currencyCode.'">' . $TotalIva . '</cbc:TaxAmount>
               ' . $taxSubtotalPro . '
            </cac:TaxTotal>
          <cac:Item>
            <cbc:Description>' . ucfirst($value['NOMBRE_PRODUCTO']) . '</cbc:Description>
            <cac:SellersItemIdentification>
		<cbc:ID>' . $value['CODIGO_PRODUCTO'] . '</cbc:ID>
            </cac:SellersItemIdentification>
            <cac:StandardItemIdentification>
		<cbc:ID schemeName="Estándar de adopción del contribuyente" schemeID="999">' . $value['CODIGO_PRODUCTO'] . '</cbc:ID>
            </cac:StandardItemIdentification>
          </cac:Item>
          <cac:Price>
            <cbc:PriceAmount currencyID="'.$currencyCode.'">' . $value['VALOR_UNITARIO'] . '</cbc:PriceAmount>
            <cbc:BaseQuantity unitCode="94">' . intval($value['CANTIDAD']) . '</cbc:BaseQuantity>
          </cac:Price>
        </cac:InvoiceLine>';

                $idProduc++;
                $cantInvoice++;
            }


            $sqlimpuestos = $this->db->query("select 
						MODFACTURADOR.fcpkgfacturacion.fncconsultarimpuestofactura(parpkfactura=>FAC.pk_factura_codigo,parnombreimpuesto=>'IVA%') IVA,
						MODFACTURADOR.fcpkgfacturacion.fncconsultarimpuestofactura(parpkfactura=>FAC.pk_factura_codigo,parnombreimpuesto=>'RTE FTE%') RTE_FUENTE,
						MODFACTURADOR.fcpkgfacturacion.fncconsultarimpuestofactura(parpkfactura=>FAC.pk_factura_codigo,parnombreimpuesto=>'RTE ICA%') RTE_ICA,
						MODFACTURADOR.fcpkgfacturacion.fncconsultarimpuestofactura(parpkfactura=>FAC.pk_factura_codigo,parnombreimpuesto=>'RTE IVA%') RTE_IVA,
						FAC.SUBTOTAL SUBTOTAL
						from MODFACTURADOR.facturtblfacturacomfiar fac
						where fac.pk_factura_codigo =$idfactura");

            $valorimpuestos = $sqlimpuestos->result_array[0];

            $rte_fuente = $valorimpuestos['RTE_FUENTE'];
            $rte_ica = $valorimpuestos['RTE_ICA'];
            $rte_iva = $valorimpuestos['RTE_IVA'];
            $subtotal_factura = $valorimpuestos['SUBTOTAL'];
            $total_iva = $valorimpuestos['IVA'];
            $total_factura = $subtotal_factura + $total_iva - $rte_fuente - $rte_ica - $rte_iva;
            $totalAllowance = $rte_iva + $rte_ica + $rte_fuente;

            $payableAmount = $total_factura;
            $taxInclusive = $subtotalFactura + $total_iva;
            //GRUPO RETENCIONES
            //RETEIVA
            $sqlRteIva = $this->db->query("SELECT PORCENTAJE, VALOR , IMPUESTO, NOMBRE_COMFIAR,CODIGO_COMFIAR,BASE_CALCULO FROM MODFACTURADOR.FACTURTBLIMPFACTURA
                WHERE PK_FACTURA_CODIGO =  $idfactura
                AND IMPUESTO LIKE 'RTE IVA%'");
            $RteIva = $sqlRteIva->result_array;

            $RETEIVA = '';
            $RETEICA = '';
            $RETEFTE = '';
            $idAllowance = 1;
            $AllowanceCharge = '';
            $totalRteIva = $rte_iva;


            foreach ($RteIva as $value) {
                if ($value['PORCENTAJE'] != 0) {
                    $taxRteIva = $value['BASE_CALCULO'];
                    $porcRteIva = $value['PORCENTAJE'];
                    $codComfiar = $value['CODIGO_COMFIAR'];
                    $nameComfiar = $value['NOMBRE_COMFIAR'];
                    $taxAmount = $value['VALOR'];
                    log_info($this->logHeader . $this->iniciLog . 'TaxScheme@Name = ' . $nameComfiar . ' TaxScheme@ID = ' . $codComfiar . ' TaxAmount = ' . $taxAmount . ' Percent = ' . $porcRteIva . ' TaxableAmount = ' . $taxRteIva);
                    if ($value['CODIGO_COMFIAR'] == '05') {

                        $RETEIVA = $RETEIVA . '<cac:TaxSubtotal>
						<cbc:TaxableAmount currencyID="'.$currencyCode.'">' . $taxRteIva . '</cbc:TaxableAmount>
						<cbc:TaxAmount currencyID="'.$currencyCode.'">' . $taxAmount . '</cbc:TaxAmount>
						<cac:TaxCategory>
							<cbc:Percent>' . $porcRteIva . '</cbc:Percent>
							<cac:TaxScheme>
								<cbc:ID>' . $codComfiar . '</cbc:ID>
								<cbc:Name>' . $nameComfiar . '</cbc:Name>
							</cac:TaxScheme>
						</cac:TaxCategory>
					</cac:TaxSubtotal>
				';
                    }


                    $AllowanceCharge = $AllowanceCharge . '<cac:AllowanceCharge>
					<cbc:ID>' . $idAllowance . '</cbc:ID>
					<cbc:ChargeIndicator>false</cbc:ChargeIndicator>
					<cbc:AllowanceChargeReasonCode>00</cbc:AllowanceChargeReasonCode>
					<cbc:AllowanceChargeReason>Descuento Impuesto</cbc:AllowanceChargeReason>
					<cbc:MultiplierFactorNumeric>' . $porcRteIva . '</cbc:MultiplierFactorNumeric>
					<cbc:Amount currencyID="'.$currencyCode.'">' . $taxAmount . '</cbc:Amount>
					<cbc:BaseAmount currencyID="'.$currencyCode.'">' . $taxRteIva . '</cbc:BaseAmount>
				 </cac:AllowanceCharge>';
                    $idAllowance++;
                }
            }
            //RETEICA
            $sqlRteIca = $this->db->query("SELECT PORCENTAJE, VALOR , IMPUESTO, NOMBRE_COMFIAR,CODIGO_COMFIAR,BASE_CALCULO FROM MODFACTURADOR.FACTURTBLIMPFACTURA
                WHERE PK_FACTURA_CODIGO =  $idfactura
                AND IMPUESTO LIKE  'RTE ICA%'");
            $RteIca = $sqlRteIca->result_array;

            $totalRteIca = $rte_ica;
            foreach ($RteIca as $value) {
                if ($value['PORCENTAJE'] != 0) {
                    $porcRteIca = $value['PORCENTAJE'];
                    $taxRteIca = $value['BASE_CALCULO'];
                    $taxAmountIca = $value['VALOR'];
                    $codComfiarIca = $value['CODIGO_COMFIAR'];
                    $nameComfiarIca = $value['NOMBRE_COMFIAR'];
                    log_info($this->logHeader . $this->iniciLog . 'TaxScheme@Name = ' . $nameComfiarIca . ' TaxScheme@ID = ' . $codComfiarIca . ' TaxAmount = ' . $taxAmountIca . ' Percent = ' . $porcRteIca . ' TaxableAmount = ' . $taxRteIca);
                    if ($value['CODIGO_COMFIAR'] == '07') {
                        $RETEICA = $RETEICA . '<cac:TaxSubtotal>
						<cbc:TaxableAmount currencyID="'.$currencyCode.'">' . $taxRteIca . '</cbc:TaxableAmount>
						<cbc:TaxAmount currencyID="'.$currencyCode.'">' . $taxAmountIca . '</cbc:TaxAmount>
						<cac:TaxCategory>
							<cbc:Percent>' . $porcRteIca . '</cbc:Percent>
							<cac:TaxScheme>
								<cbc:ID>' . $codComfiarIca . '</cbc:ID>
								<cbc:Name>' . $nameComfiarIca . '</cbc:Name>
							</cac:TaxScheme>
						</cac:TaxCategory>
					</cac:TaxSubtotal>
				';
                    }

                    $AllowanceCharge = $AllowanceCharge . '<cac:AllowanceCharge>
					<cbc:ID>' . $idAllowance . '</cbc:ID>
					<cbc:ChargeIndicator>false</cbc:ChargeIndicator>
					<cbc:AllowanceChargeReasonCode>00</cbc:AllowanceChargeReasonCode>
					<cbc:AllowanceChargeReason>Descuento Impuesto</cbc:AllowanceChargeReason>
					<cbc:MultiplierFactorNumeric>' . $porcRteIca . '</cbc:MultiplierFactorNumeric>
					<cbc:Amount currencyID="'.$currencyCode.'">' . $taxAmountIca . '</cbc:Amount>
					<cbc:BaseAmount currencyID="'.$currencyCode.'">' . $taxRteIca . '</cbc:BaseAmount>
				 </cac:AllowanceCharge>';
                    $idAllowance++;
                }
            }
            //RETEFUENTE
            $sqlRteFte = $this->db->query("SELECT PORCENTAJE, VALOR , IMPUESTO, NOMBRE_COMFIAR,CODIGO_COMFIAR,BASE_CALCULO FROM MODFACTURADOR.FACTURTBLIMPFACTURA
                WHERE PK_FACTURA_CODIGO =  $idfactura
                AND IMPUESTO LIKE 'RTE FTE%'");
            $RteFte = $sqlRteFte->result_array;

            $RteFteCom = 0;
            $rteFteServ = 0;
            $totalRteFte = $rte_fuente;
            foreach ($RteFte as $value) {
                if ($value['PORCENTAJE'] != 0) {
                    $porcRteFuente = $value['PORCENTAJE'];
                    $taxRteFte = $value['BASE_CALCULO'];
                    $taxAmountFte = $value['VALOR'];
                    $codComfiarFte = $value['CODIGO_COMFIAR'];
                    $nameComfiarFte = $value['NOMBRE_COMFIAR'];
                    log_info($this->logHeader . $this->iniciLog . 'TaxScheme@Name = ' . $nameComfiarFte . ' TaxScheme@ID = ' . $codComfiarFte . ' TaxAmount = ' . $taxAmountFte . ' Percent = ' . $porcRteFuente . ' TaxableAmount = ' . $taxRteFte);
                    if ($value['CODIGO_COMFIAR'] == '06') {
                        $RETEFTE = $RETEFTE . '<cac:TaxSubtotal>
						<cbc:TaxableAmount currencyID="'.$currencyCode.'">' . $taxRteFte . '</cbc:TaxableAmount>
						<cbc:TaxAmount currencyID="'.$currencyCode.'">' . $taxAmountFte . '</cbc:TaxAmount>
						<cac:TaxCategory>
							<cbc:Percent>' . $porcRteFuente . '</cbc:Percent>
							<cac:TaxScheme>
								<cbc:ID>' . $codComfiarFte . '</cbc:ID>
								<cbc:Name>' . $nameComfiarFte . '</cbc:Name>
							</cac:TaxScheme>
						</cac:TaxCategory>
					</cac:TaxSubtotal>
				';
                    }

                    $AllowanceCharge = $AllowanceCharge . '<cac:AllowanceCharge>
					<cbc:ID>' . $idAllowance . '</cbc:ID>
					<cbc:ChargeIndicator>false</cbc:ChargeIndicator>
					<cbc:AllowanceChargeReasonCode>00</cbc:AllowanceChargeReasonCode>
					<cbc:AllowanceChargeReason>Descuento Impuesto</cbc:AllowanceChargeReason>
					<cbc:MultiplierFactorNumeric>' . $porcRteFuente . '</cbc:MultiplierFactorNumeric>
					<cbc:Amount currencyID="'.$currencyCode.'">' . $taxAmountFte . '</cbc:Amount>
					<cbc:BaseAmount currencyID="'.$currencyCode.'">' . $taxRteFte . '</cbc:BaseAmount>
				 </cac:AllowanceCharge>';
                    $idAllowance++;
                }
            }

            $tagGrupoHoldingRteIva = '';
            $tagGrupoHoldingRteIca = '';
            $tagGrupoHoldingRteFte = '';
            if (!empty($RETEIVA)) {
                $tagGrupoHoldingRteIva = '<cac:WithholdingTaxTotal>
                                            <cbc:TaxAmount currencyID="'.$currencyCode.'">' . $totalRteIva . '</cbc:TaxAmount>'
                        . $RETEIVA .
                        '</cac:WithholdingTaxTotal>';
            }
            if (!empty($RETEICA)) {
                $tagGrupoHoldingRteIca = '<cac:WithholdingTaxTotal>
                                            <cbc:TaxAmount currencyID="'.$currencyCode.'">' . $totalRteIca . '</cbc:TaxAmount>'
                        . $RETEICA .
                        '</cac:WithholdingTaxTotal>';
            }
            if (!empty($RETEFTE)) {
                $tagGrupoHoldingRteFte = '<cac:WithholdingTaxTotal>
                                            <cbc:TaxAmount currencyID="'.$currencyCode.'">' . $totalRteFte . '</cbc:TaxAmount>'
                        . $RETEFTE .
                        '</cac:WithholdingTaxTotal>';
            }
        }
        $fecha_actual = date("Y-m-d");
        $lineExtensionAm = $subtotal_factura;
        $TaxExclusiveAm = $subtotal_factura;
        $dom = new DOMDocument;
        
        $dom->loadXML('<?xml version="1.0" encoding="utf-8"?>
<Comprobantes>
	<Comprobante>
		<informacionOrganismo>
			<Invoice xmlns:clm66411="urn:un:unece:uncefact:codelist:specification:66411:2001" xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2" xmlns:clmIANAMIMEMediaType="urn:un:unece:uncefact:codelist:specification:IANAMIMEMediaType:2003" xmlns:qdt="urn:oasis:names:specification:ubl:schema:xsd:QualifiedDatatypes-2" xmlns:udt="urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2" xmlns:sts="dian:gov:co:facturaelectronica:Structures-2-1" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:ccts="urn:un:unece:uncefact:documentation:2" xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:clm54217="urn:un:unece:uncefact:codelist:specification:54217:2001" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns="urn:oasis:names:specification:ubl:schema:xsd:Invoice-2">
				<ext:UBLExtensions>
					<ext:UBLExtension>
						<ext:ExtensionContent>
							<sts:DianExtensions>
								<sts:InvoiceControl>
									<sts:InvoiceAuthorization>' . $resolucion . '</sts:InvoiceAuthorization>
									<sts:AuthorizationPeriod>
										<cbc:StartDate>' . $fechaInicioResol . '</cbc:StartDate>
										<cbc:EndDate>' . $fechaFinResol . '</cbc:EndDate>
									</sts:AuthorizationPeriod>
									<sts:AuthorizedInvoices>
										<sts:Prefix>' . $prefijoFac . '</sts:Prefix>
										<sts:From>' . $inicioRangoResol . '</sts:From>
										<sts:To>' . $finRangoResol . '</sts:To>
									</sts:AuthorizedInvoices>
								</sts:InvoiceControl>
								<sts:InvoiceSource>
									<cbc:IdentificationCode listAgencyID="6" listAgencyName="United Nations Economic Commission for Europe" listSchemeURI="urn:oasis:names:specification:ubl:codelist:gc:CountryIdentificationCode-2.1">CO</cbc:IdentificationCode>
								</sts:InvoiceSource>
								<sts:SoftwareProvider>
									<sts:SoftwareID schemeAgencyID="195" schemeAgencyName="CO, DIAN (Dirección de Impuestos y Aduanas Nacionales)" />
								</sts:SoftwareProvider>
								<sts:SoftwareSecurityCode schemeAgencyID="195" schemeAgencyName="CO, DIAN (Dirección de Impuestos y Aduanas Nacionales)" />
							</sts:DianExtensions>
						</ext:ExtensionContent>
					</ext:UBLExtension>
				</ext:UBLExtensions>
				<cbc:UBLVersionID>UBL 2.1</cbc:UBLVersionID>
				<cbc:CustomizationID>10</cbc:CustomizationID>
				<cbc:ProfileID>DIAN 2.1</cbc:ProfileID>
				<cbc:ProfileExecutionID>' . $ambienteTxComfiar . '</cbc:ProfileExecutionID>
				<cbc:ID>' . $prefijoFac . $numeroFactura . '</cbc:ID>
				<cbc:UUID schemeID="' . $ambienteTxComfiar . '" schemeName="CUFE-SHA384" />
				<cbc:IssueDate>' . date("Y-m-d") . '</cbc:IssueDate>
				<cbc:IssueTime>' . date("H:i:s") . '-05:00' . '</cbc:IssueTime>
				<cbc:InvoiceTypeCode>01</cbc:InvoiceTypeCode>
				<cbc:Note>' . $total_factura . '</cbc:Note><!-- nota 1 total a pagar-->
				<cbc:Note>0</cbc:Note><!--nota 2 total ingresos propios-->
				<cbc:Note>0</cbc:Note><!-- nota 3 total ingresos terceros-->
				<cbc:Note>Régimen: Impuestos sobre las ventas - IVA 
Persona Jurídica. Actividad económica 8220 Tarifa ICA 9.66x1.000. No Somos Grandes Contribuyentes. No Somos Autorretenedores. Resolución gráfica de la factura electrónica según parágrafo 1 articulo 3 decreto 2242 de 2015.</cbc:Note>
				<cbc:Note>' . $subtotalFactura . '</cbc:Note><!-- Nota 5 subtotal ingresos propios-->
				<cbc:Note>' . $observacionFactura . '</cbc:Note><!-- Nota 6 observaciones -->
				<cbc:Note>'.$nota7.'</cbc:Note>
				<cbc:Note>0</cbc:Note>
				<cbc:Note>VACIO</cbc:Note>
				<cbc:Note>' . $idiomaFactura . '</cbc:Note>
				<cbc:Note>VACIO</cbc:Note>
				<cbc:Note>VACIO</cbc:Note>
				<cbc:Note>VACIO</cbc:Note>
				<cbc:Note>VACIO</cbc:Note>
				<cbc:Note>VACIO</cbc:Note>
				<cbc:Note>VACIO</cbc:Note>
				<cbc:Note>VACIO</cbc:Note>
				<cbc:Note>VACIO</cbc:Note>
				<cbc:Note>VACIO</cbc:Note>
				<cbc:Note>VACIO</cbc:Note>
				<cbc:Note>VACIO</cbc:Note>
				<cbc:DocumentCurrencyCode>'.$currencyCode.'</cbc:DocumentCurrencyCode>
				<cbc:LineCountNumeric>' . $cantInvoice . '</cbc:LineCountNumeric>
				<cac:AccountingSupplierParty>
					<cbc:AdditionalAccountID>1</cbc:AdditionalAccountID>
					<cac:Party>
						<cac:PartyName>
							<cbc:Name>' . $emisorRegistrationName . '</cbc:Name>
						</cac:PartyName>
						<cac:PhysicalLocation>
							<cac:Address>
								<cbc:ID>11001</cbc:ID>
								<cbc:CityName>BOGOTA</cbc:CityName>
								<cbc:PostalZone>110111</cbc:PostalZone>
								<cbc:CountrySubentity>CUNDINAMARCA)               [CO</cbc:CountrySubentity>
								<cbc:CountrySubentityCode>11</cbc:CountrySubentityCode>
								<cac:AddressLine>
									<cbc:Line>' . $direccionEmisor . '– Bogotá, Colombia</cbc:Line>
								</cac:AddressLine>
								<cac:Country>
									<cbc:IdentificationCode>CO</cbc:IdentificationCode>
									<cbc:Name languageID = "es">Colombia</cbc:Name>
								</cac:Country>
							</cac:Address>
						</cac:PhysicalLocation>
						<cac:PartyTaxScheme>
							<cbc:RegistrationName>' . $emisorRegistrationName . '</cbc:RegistrationName>
							<cbc:CompanyID schemeID = "' . $digVerEmisor . '" schemeName = "31" schemeAgencyID = "195" schemeAgencyName = "CO, DIAN (Dirección de Impuestos y Aduanas Nacionales)">' . $nitEmisor . '</cbc:CompanyID>
							<cbc:TaxLevelCode listName = "48">O-23</cbc:TaxLevelCode>
							<cac:RegistrationAddress>
								<cbc:ID>11001</cbc:ID>
								<cbc:CityName>BOGOTA</cbc:CityName>
								<cbc:PostalZone>110111</cbc:PostalZone>
								<cbc:CountrySubentity>CUNDINAMARCA) [CO</cbc:CountrySubentity>
								<cbc:CountrySubentityCode>11</cbc:CountrySubentityCode>
								<cac:AddressLine>
									<cbc:Line>' . $direccionEmisor . '– Bogotá, Colombia</cbc:Line>
								</cac:AddressLine>
								<cac:Country>
									<cbc:IdentificationCode>CO</cbc:IdentificationCode>
									<cbc:Name languageID = "es">Colombia</cbc:Name>
								</cac:Country>
							</cac:RegistrationAddress>
							<cac:TaxScheme>
								<cbc:ID>' . $idTaxScheme . '</cbc:ID>
								<cbc:Name>' . $nameTaxScheme . '</cbc:Name>
							</cac:TaxScheme>
						</cac:PartyTaxScheme>
						<cac:PartyLegalEntity>
							<cbc:RegistrationName>' . $emisorRegistrationName . '</cbc:RegistrationName>
							<cbc:CompanyID schemeID = "' . $digVerEmisor . '" schemeName = "31" schemeAgencyID = "195" schemeAgencyName = "CO, DIAN (Dirección de Impuestos y Aduanas Nacionales)">' . $nitEmisor . '</cbc:CompanyID>
							<cac:CorporateRegistrationScheme>
								<cbc:ID>' . $prefijoFac . '</cbc:ID>
							</cac:CorporateRegistrationScheme>
						</cac:PartyLegalEntity>
						<cac:Contact>
                                                	<cbc:Telephone>' . $telefonoEmisor . '</cbc:Telephone>
							<cbc:Telefax>' . $telefonoEmisor . '</cbc:Telefax>
							<cbc:ElectronicMail>' . $correoEmisor . '</cbc:ElectronicMail>
						</cac:Contact>
					</cac:Party>
				</cac:AccountingSupplierParty>
				<cac:AccountingCustomerParty>
					<cbc:AdditionalAccountID>' . $AccountID . '</cbc:AdditionalAccountID>
					<cac:Party>
                                        ' . $AddicionalAc . '
						<cac:PartyName>
							<cbc:Name>' . $nombreCliente . '</cbc:Name>
						</cac:PartyName>
						<cac:PhysicalLocation>
							<cac:Address>
								<cbc:ID>' . $daneCliente . '</cbc:ID>
								<cbc:CityName>' . $ciudadCliente . '</cbc:CityName>
								<cbc:PostalZone>' . $codigoPostal . '</cbc:PostalZone>
								<cbc:CountrySubentity>' . $departamentoCliente . '</cbc:CountrySubentity>
								<cbc:CountrySubentityCode>' . $clienteCodDep . '</cbc:CountrySubentityCode>
								<cac:AddressLine>
									<cbc:Line>' . $direccion_cliente . '</cbc:Line>
								</cac:AddressLine>
								<cac:Country>
									<cbc:IdentificationCode>CO</cbc:IdentificationCode>
									<cbc:Name languageID = "es">Colombia</cbc:Name>
								</cac:Country>
							</cac:Address>
						</cac:PhysicalLocation>
						<cac:PartyTaxScheme>
							<cbc:RegistrationName>' . $nombreCliente . '</cbc:RegistrationName>
							' . $companyId . '
							' . $taxtCustomer . '
							<cac:TaxScheme>
								<cbc:ID>' . $taxShemeID . '</cbc:ID>
								<cbc:Name>' . $taxShemeName . '</cbc:Name>
							</cac:TaxScheme>
						</cac:PartyTaxScheme>
						<cac:PartyLegalEntity>
							<cbc:RegistrationName>' . $nombreCliente . '</cbc:RegistrationName>
							' . $companyId . '
						</cac:PartyLegalEntity>
						<cac:Contact>
                                                        <cbc:Telephone>' . $telefono_cliente . '</cbc:Telephone>
							<cbc:ElectronicMail>' . $emailCliente . '</cbc:ElectronicMail>
						</cac:Contact>
                                                ' . $Person . '
					</cac:Party>
                                        <cac:AccountingContact>
						<cbc:Telephone>' . $telefono_cliente . '</cbc:Telephone>
					</cac:AccountingContact>
                                         
				</cac:AccountingCustomerParty>
				<!--INFORMACION OBLIGATORIA A PARTIR DEL 5 DE JUNIO DEL 2020 -->
				<cac:Delivery>
					<cbc:ActualDeliveryDate>' . date("Y-m-d", strtotime($fecha_actual . "+ 1 days")) . '</cbc:ActualDeliveryDate>
				</cac:Delivery>
				<cac:PaymentMeans>
					<cbc:ID>1</cbc:ID>
					<cbc:PaymentMeansCode>1</cbc:PaymentMeansCode>
					<cbc:PaymentDueDate>' . date("Y-m-d") . '</cbc:PaymentDueDate>
				</cac:PaymentMeans>
				<cac:PaymentTerms>
					<cbc:Note>Contado</cbc:Note>
				</cac:PaymentTerms>
                                ' . $AllowanceCharge . '
                                '.$paymentExchangeRate.'
				<cac:TaxTotal>
					<cbc:TaxAmount currencyID = "'.$currencyCode.'">' . $total_iva . '</cbc:TaxAmount>
					' . $taxtTotal . '
				</cac:TaxTotal>
                                <!-- grupo de retenciones  -->
                                ' . $tagGrupoHoldingRteIva . '
                                ' . $tagGrupoHoldingRteIca . '
                                ' . $tagGrupoHoldingRteFte . '
				<cac:LegalMonetaryTotal>
					<cbc:LineExtensionAmount currencyID = "'.$currencyCode.'">' . $lineExtensionAm . '</cbc:LineExtensionAmount>
					<cbc:TaxExclusiveAmount currencyID = "'.$currencyCode.'">' . $TaxExclusiveAm . '</cbc:TaxExclusiveAmount>
					<cbc:TaxInclusiveAmount currencyID = "'.$currencyCode.'">' . $taxInclusive . '</cbc:TaxInclusiveAmount>
                                        <cbc:AllowanceTotalAmount currencyID="'.$currencyCode.'">' . $totalAllowance . '</cbc:AllowanceTotalAmount>
					<cbc:PayableAmount currencyID = "'.$currencyCode.'">' . $payableAmount . '</cbc:PayableAmount>
				</cac:LegalMonetaryTotal>
                            ' . $InvoiceLine . '
			</Invoice>
		</informacionOrganismo>
                 <informacionComfiar>
                    <ruc>' . $nitEmisor . '</ruc>
                    <codDoc>01</codDoc>
                    <prefixPtoVenta>' . $prefijoFac . '</prefixPtoVenta>
                    <nroCbte>' . $numeroFactura . '</nroCbte>
                    <Receptores>
                      <Receptor>
                        <Login>TECH' . $nitCliente . '</Login>
                        <TipoUsuario>2</TipoUsuario>
                        <Nombre>' . $nombreCliente . '</Nombre>
                        <Mail>' . $emailCliente . '</Mail>
                        <Idioma>3</Idioma>
                        <Adjunto>ADJUNTO</Adjunto>
                      </Receptor>
                    </Receptores>
                 </informacionComfiar>
	</Comprobante>
</Comprobantes>
        ');
        $s = simplexml_import_dom($dom);
        return $s->asXML();
    }

    public function salida_Transaccion($sessionId = null, $fechaVen = null, $transaccionId = null, $pk_factura = null) {
        log_info($this->logHeader . 'INGRESO LIBRERIA salida_Transaccion');
        log_info($this->logHeader . $this->postData . 'TransaccionId = ' . $transaccionId . ' pk_factura_codigo = ' . $pk_factura);

        if (!empty($sessionId) && !empty($fechaVen) && !empty($transaccionId) && !empty($pk_factura)) {
            $urlWsdl= $this->retornarValorConfiguracion($pk_factura, 'URL_WS_COMFIAR');
            $wsdl = $urlWsdl;
            $options = array(
                'cache_wsdl' => 0,
                'trace' => 1,
                'stream_context' => stream_context_create(array(
                    'ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    )
                ))
            );
            $client = new SoapClient($wsdl, $options);
            $cuitProcesar = $this->retornarValorConfiguracion($pk_factura, 'NIT_CUITID');
            $txId = $transaccionId;
            // web service input params
            $request_param = array(
                "cuitId" => $cuitProcesar, // Cuit, RUC o NIT del emisor del comprobante.
                "transaccionId" => $txId, // Número de transacción otorgado por COMFIAR sobre el cual se procesó el comprobante enviado
                "token" => array(
                    "SesionId" => $sessionId,
                    "FechaVencimiento" => $fechaVen
                )
            );
            try {
                $responce_param = $client->SalidaTransaccion($request_param);
//            var_dump($responce_param);
//            var_dump($responce_param->IniciarSesionResult->SesionId);
                log_info($this->soapCorrecto . 'SalidaTransaccion::' . json_encode($responce_param));
                $response = $responce_param;
            } catch (Exception $e) {
//                echo "<h2>Exception Error!</h2>";
//                echo $e->getMessage();
                log_info($this->errorFuncion . 'ERROR SOAP::' . $e->getMessage());
                $response = 'Error consumo SalidaTransaccion :' . $e->getMessage();
            }
        } else {
            log_info($this->errorFuncion . 'ERROR DATOS NULOS.');
            $response = 'Datos incorrectos.';
        }
        return $response;
    }
    
    //Se encarga de solicitar a comfiar una respuesta sobre el estado gtransaccion eviada
    public function respuesta_Comprobante($sessionId = null, $fechaVen = null, $transaccionId = null, $tipCom = null, $pk_factura=null) {

        log_info($this->logHeader . 'INGRESO LIBRERIA respuesta_Comprobante');
        log_info($this->postData . 'Pk_factura = ' . $pk_factura .' Transaccion_id = '.$transaccionId );

        if (!empty($sessionId) && !empty($fechaVen) && !empty($transaccionId) && !empty($pk_factura)) {
            $urlWsdl= $this->retornarValorConfiguracion($pk_factura, 'URL_WS_COMFIAR');
            $wsdl = $urlWsdl;
            $options = array(
                'cache_wsdl' => 0,
                'trace' => 1,
                'stream_context' => stream_context_create(array(
                    'ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    )
                ))
            );
            $client = new SoapClient($wsdl, $options);
            $cuitProcesar = $this->retornarValorConfiguracion($pk_factura, 'NIT_CUITID');
            if ($tipCom == 1) {
                $puntoVentaId = $this->retornarValorConfiguracion($pk_factura, 'ID_PUNTO_VENTA_FAC');
                $tipoComprobanteId = $this->retornarValorConfiguracion($pk_factura, 'ID_TIPO_COMPROBANTE_FAC');
            } else if ($tipCom == 2) {
                $puntoVentaId = $this->retornarValorConfiguracion($pk_factura, 'ID_PUNTO_VENTA_NTC');
                $tipoComprobanteId = $this->retornarValorConfiguracion($pk_factura, 'ID_TIPO_COMPROBANTE_NTC');
            }

            $nroCbte = $transaccionId;
            // web service input params
            $request_param = array(
                "cuitId" => $cuitProcesar, // Cuit, RUC o NIT del emisor del comprobante.
                "puntoDeVentaId" => $puntoVentaId, // Número de punto de venta a procesar para factura 10002
                "tipoDeComprobanteId" => $tipoComprobanteId, // tipo comprobante factura 01
                "nroCbte" => $nroCbte, // Número de transacción otorgado por COMFIAR sobre el cual se procesó el comprobante enviado
                "token" => array(
                    "SesionId" => $sessionId,
                    "FechaVencimiento" => $fechaVen
                )
            );
            try {
                $responce_param = $client->RespuestaComprobante($request_param);
//            var_dump($responce_param);
//            var_dump($responce_param->IniciarSesionResult->SesionId);
                log_info($this->logHeader.'-'.$this->soapCorrecto . 'RespuestaComprobante::' );
                $response = $responce_param;
            } catch (Exception $e) {
//                echo "<h2>Exception Error!</h2>";
//                echo $e->getMessage();
                log_info($this->errorFuncion . 'ERROR SOAP::' . $e->getMessage());
                $response = 'Error consumo RespuestaComprobante :' . $e->getMessage();
            }
        } else {
            log_info($this->errorFuncion . 'ERROR DATOS NULOS.');
            $response = 'Datos incorrectos.';
        }
        return $response;
    }

    //Funcion consulta tabla modfactur.factblconfcomfiar, Retorna valor parametro para cada pk_conf_codigo
    public function retornarValorConfiguracion($pk_factura, $parametro) {
        log_info($this->logHeader . $this->postData . ' ::retornarValorConfiguracion:: PK_FACTURA= ' . $pk_factura . ' PARAMETRO= ' . $parametro);

        if (!empty($pk_factura) && !empty($parametro)) {
            if ($parametro == 'CONTRASENA_COMFIAR') {
                $sql = "BEGIN modfacturador.PKGMODFACTURADORGENERAL.prcpasscomfiar(
                        parpkfact=>:parpkfact, 
                        parcontrasena=>:parcontrasena, 
                        parrespuesta=>:parrespuesta
                        );
                        END;";

                $conn = $this->db->conn_id;
                $stmt = oci_parse($conn, $sql);
                oci_bind_by_name($stmt, ':parpkfact', $pk_factura, 32);
                oci_bind_by_name($stmt, ':parcontrasena', $contrasena, 32);
                oci_bind_by_name($stmt, ':parrespuesta', $parrespuesta, 32);
                if (!oci_execute($stmt)) {
                    $e = oci_error($stmt);
                    VAR_DUMP($e);
                    log_info($this->logHeader . $this->errorFuncion . 'Error consumiendo PROCEDURE prcpasscomfiar , en funcion -retornarValorConfiguracion-' . e);
                } if ($parrespuesta == 1) {
                    $ValorParametroReturn = $contrasena;
                    log_info($this->logHeader . $this->finFuncion . 'Consumo Correcto!! PROCEDURE prcpasscomfiar , en funcion -retornarValorConfiguracion- ::parrespuesta::' . $parrespuesta);
                }
            } else {
                $sqlconfigComfiar = $this->db->query("SELECT $parametro VALOR_PARAMETRO FROM MODFACTURADOR.FACTURTBLEMPEMICONFIG CONF
                                        JOIN MODFACTURADOR.FACTURTBLFACTURACOMFIAR FAC
                                        ON CONF.PK_EMPRESA_EMISORA= FAC.PK_EMPRESA_EMISORA
                                        WHERE FAC.PK_FACTURA_CODIGO=$pk_factura");

                $valorParametro = $sqlconfigComfiar->result_array[0];
                $ValorParametroReturn = $valorParametro['VALOR_PARAMETRO'];
            }
        }

        return $ValorParametroReturn;
    }
    
    //Consumo SOAP Comfiar retorna pdf, tipo comprobante 01=factura  2=Nota Credito
    public function descarga_pdf($sessionId = null, $fechaVen = null, $transaccionId = null, $nroComprobante = null, $tipCom = null,$pk_factura=null) {
        log_info($this->logHeader . 'INGRESO LIBRERIA descarga_pdf');
        log_info($this->logHeader.$this->postData . 'TransaccionId = ' . $transaccionId .' Factura No = '.$nroComprobante .' Pk_Factura = '.$pk_factura);

        $codRespuesta = 0;
        if (!empty($sessionId) && !empty($fechaVen) && !empty($transaccionId) && !empty($nroComprobante) && !empty($tipCom)&& !empty($pk_factura)) {
            $urlWsdl= $this->retornarValorConfiguracion($pk_factura, 'URL_WS_COMFIAR');
            $wsdl = $urlWsdl;
            $options = array(
                'cache_wsdl' => 0,
                'trace' => 1,
                'stream_context' => stream_context_create(array(
                    'ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    )
                ))
            );
            $client = new SoapClient($wsdl, $options);
            $cuitId = $this->retornarValorConfiguracion($pk_factura, 'NIT_CUITID');
            if ($tipCom == 1) {
                $puntoVentaId = $this->retornarValorConfiguracion($pk_factura, 'ID_PUNTO_VENTA_FAC'); //Número de punto de venta a procesar 01 factura
                $tipoComprobanteId = $this->retornarValorConfiguracion($pk_factura, 'ID_TIPO_COMPROBANTE_FAC'); //Número del tipo de comprobante a procesar. Ejemplo 01:Factura
                $prefijoPeople = $this->retornarValorConfiguracion($pk_factura, 'PREFIJO_FACTURA'); //prefijo usado para factura ej: SETT
                $folderPath=$this->retornarValorConfiguracion($pk_factura, 'URL_ALMACEN_FACTURA');
            } else if ($tipCom == 2) {
                $puntoVentaId = $this->retornarValorConfiguracion($pk_factura, 'ID_PUNTO_VENTA_NTC');
                $tipoComprobanteId = $this->retornarValorConfiguracion($pk_factura, 'ID_TIPO_COMPROBANTE_NTC');
                $prefijoPeople = $this->retornarValorConfiguracion($pk_factura, 'PREFIJO_NOTA_CREDITO');
                $folderPath=$this->retornarValorConfiguracion($pk_factura, 'URL_ALMACEN_NC');
            }
            // web service input params
            $request_param = array(
                "transaccionId" => $transaccionId, //274 id transaccion confiar
                "cuitId" => $cuitId,
                "puntoDeVentaId" => $puntoVentaId,
                "tipoComprobanteId" => $tipoComprobanteId, // tipo comprobante factura 01
                "numeroComprobante" => $nroComprobante, //418 Número de factura enviado SETT418
                "token" => array(
                    "SesionId" => $sessionId,
                    "FechaVencimiento" => $fechaVen
                )
            );
            try {
                $responce_param = $client->DescargarPdf($request_param);
                if (isset($responce_param->DescargarPdfResult)) {
                log_info($this->logHeader.$this->iniciLog . '::folderPath::' . $folderPath);

                    $b64 = $responce_param->DescargarPdfResult;
                    $data = base64_encode($b64);
                    $urlpublica = $this->db->query("select VALOR_PARAMETRO from modgeneri.gentblpargen where pk_pargen_codigo =96");
                    $urlpublica = $urlpublica->result_array[0];
                    //guarda y genera url factura pdf
//                    $folderPath = "uploads/facturacomfiar/";
                    
                    $date = date('Y-m-d');
                    $random = rand(1000, 9999);
                    $fact = strtolower($prefijoPeople) . '-' . $nroComprobante . '-';
                    $name = $fact . strtolower($date . '-' . $random . '.pdf');
                    $file_dir = $folderPath . $name;
                    $url = $urlpublica['VALOR_PARAMETRO'] . '/' . $folderPath . $name;
                    $pdf_decoded = base64_decode($data); //Write data back to pdf file
                    try {
                        $pdf = fopen($file_dir, 'w');
                        fwrite($pdf, $pdf_decoded);
                        //close output file
                        fclose($pdf);
                        $dataReturn = $url;
//                    echo $url . '+++' . $fact;
                    } catch (Exception $e) {
                        $response = 'Excepción capturada: ' . $e->getMessage();
                    }

                    log_info($this->logHeader.$this->soapCorrecto . '::Consumo Correcto soap DescargarPdf::URL PDF COMFIAR::' . $dataReturn);
                    $codRespuesta = 1;
                    $response = $dataReturn;
                } else {
                    $response = 'Error consumo Soap';
                }
            } catch (Exception $e) {
                log_info($this->logHeader.$this->errorFuncion . 'ERROR SOAP::' . $e->getMessage());

                $response = 'Error consumo DescargarPdf :' . $e->getMessage();
            }
        } else {
            log_info($this->logHeader.$this->errorFuncion . 'ERROR DATOS NULOS.');
            $response = 'Datos incorrectos.';
        }
        $objectRespuesta = (object) [
                    'CodRespuesta' => $codRespuesta,
                    'Respuesta' => $response];
        return $objectRespuesta;
    }
    

}
