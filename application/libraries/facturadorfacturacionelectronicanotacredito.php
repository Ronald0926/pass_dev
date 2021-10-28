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
class FacturadorFacturacionElectronicaNotaCredito extends CI_Controller {

    public $iniciLog = '[INFO] ';
    public $logHeader = 'FACTURADOR::::NC:::: ';
    public $postData = 'POSTDATA::::::::: ';
    public $soapCorrecto = 'CONSUMO SOAP CORRECTO::::::: ';
    public $finFuncion = ' FIN PROCEDIMIENTO::::::: ';
    public $errorFuncion = 'ERROR::::::: ';
    public $errorBD = 'ERROR BASE DE DATOS::::::: ';

    public function __construct() {
        parent::__construct();
        $this->load->helper('log4php');
    }

    public function iniciar_sesion($pk_conf_codigo) {

        log_info($this->logHeader . 'INGRESO LIBRERIA FACTURADOR FUNCION INICIAR_SESION NC Pk_conf_codigo: ' . $pk_conf_codigo);
        $usuarioComfiar = $this->retornarValorConfiguracion($pk_conf_codigo, 'USUARIO_COMFIAR');
        $passComfiar = $this->retornarValorConfiguracion($pk_conf_codigo, 'CONTRASENA_COMFIAR');

        //********Codigo iniciar sesion comfiar */
        if (!empty($usuarioComfiar) && !empty($passComfiar) && !empty($pk_conf_codigo)) {
            $urlWsdl = $this->retornarValorConfiguracion($pk_conf_codigo, 'URL_WS_COMFIAR');
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
                log_info($this->logHeader . $this->soapCorrecto . 'IniciarSesion::IniciarSesion Nota Credito::' . json_encode($responce_param));
                $response = $responce_param;
            } catch (Exception $e) {
//                echo "<h2>Exception Error!</h2>";
//                echo $e->getMessage();
                log_info($this->logHeader . $this->errorFuncion . 'ERROR SOAP ::IniciarSesion:: Nota Credito' . $e->getMessage());
                $response = 'Error consumo iniciosesion :' . $e->getMessage();
            }
        } else {
            log_info($this->logHeader . $this->errorFuncion . '::IniciarSesion:: USUARIO o CONTRASEÑA NULOS.');
            $response = 'Datos incorrectos.';
        }


        return $response;
    }

    public function autorizar_Comprobante($sessionId = null, $fechaVen = null, $pk_conf_codigo = null, $pk_cliente_codigo = null, $pk_empresa_emisora = null, $pk_nota_codigo = null) {

        log_info($this->logHeader . 'INGRESO LIBRERIA Funcion autorizar_Comprobante Nota Credito');
        log_info($this->logHeader . $this->postData . 'PK_CONF_CODIGO = ' . $pk_conf_codigo . ' PK_CLIENTE_CODIGO = ' . $pk_cliente_codigo . ' PK_EMPRESA_EMISORA = ' . $pk_empresa_emisora . ' PK_NOTA_CODIGO = ' . $pk_nota_codigo);
        //********Codigo autorizar comprobante*/
        if (!empty($sessionId) && !empty($fechaVen) && !empty($pk_conf_codigo) && !empty($pk_cliente_codigo) && !empty($pk_empresa_emisora) && !empty($pk_nota_codigo)) {
            $urlWsdl = $this->retornarValorConfiguracion($pk_conf_codigo, 'URL_WS_COMFIAR');
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
            $xml = $this->generarXmlNC($pk_nota_codigo, $pk_cliente_codigo, $pk_empresa_emisora);
            if ($xml != 404) {
                $client = new SoapClient($wsdl, $options);
                $cuitProcesar = $this->retornarValorConfiguracion($pk_conf_codigo, 'NIT_CUITID');
                $puntoVentaId = $this->retornarValorConfiguracion($pk_conf_codigo, 'ID_PUNTO_VENTA_NTC');
                $formatoId = $this->retornarValorConfiguracion($pk_conf_codigo, 'FORMATO_ID');
                $tipoComprobanteId = $this->retornarValorConfiguracion($pk_conf_codigo, 'ID_TIPO_COMPROBANTE_NTC');
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
                $response = 404;
            }
        } else {
            log_info($this->logHeader . $this->errorFuncion . 'SESSIONID , FECHAVEN , IDFACT DATOS NULOS.');
            $response = 'Datos incorrectos.';
        }
        return $response;
    }

    public function generarXmlNC($pk_nota_codigo = null, $pk_cliente_codigo = null, $pk_empresa_emisora = null) {
        log_info($this->logHeader . 'INGRESO FUNCION generarXmlNC ' . $this->postData . ' PK_NOTA_CODIGO = ' . $pk_nota_codigo . ' PK_CLIENTE_CODIGO = ' . $pk_cliente_codigo . ' PK_EMPRESA_EMISORA = ' . $pk_empresa_emisora);

        if (!empty($pk_nota_codigo) && !empty($pk_cliente_codigo) && !empty($pk_empresa_emisora)) {


            //datos configuracion comfiar
            $SqldatosConfiguracion = $this->db->query("SELECT 
                CONF.AMBIENTE_TRANSMISION,
                CONF.NIT_CUITID NIT,
                CONF.DIGITO_VERIFICACION,
                CONF.RESOLUCION,
                CONF.FECHA_INICIO_RESOL,
                CONF.FECHA_FIN_RESOL,
                CONF.RANGO_INICIO_RESOL_NC,
                CONF.RANGO_FIN_RESOL_NC,
                FAC.IDIOMA_FACTURA,
                CASE FAC.IDIOMA_FACTURA
                WHEN 'E' THEN
                CONF.PREFIJO_NOTA_CREDITO
                WHEN 'I' THEN  
                CONF.PREFIJO_NOTA_CREDITO_INT
                END PREFIJO_NC,
                CONF.CORREO_EMISOR_FACTURACION,
                CONF.DIRECCION_EMISOR,
                CONF.TELEFONO_EMISOR,
                TRIB.NOMBRE_TRIBUTO,
                TRIB.CODIGO_TRIBUTO,
                EMI.NOMBRE,
                nota.consecutivo_nota_credito NUMERO_NOTA_CREDITO,
                FAC.VALOR_TRM,
                FAC.FECHA_TRM,
                xmlfac.cufe_factura,
                xmlfac.issuedate,
                xmlfac.id_comprobante
                From modfacturador.facturtblnotacredito nota
                JOIN modfacturador.facturtblfacturacomfiar fac
                ON nota.pk_factura_codigo = fac.pk_factura_codigo
                JOIN modfacturador.facturtblempemiconfig conf
                ON fac.pk_empresa_emisora = conf.pk_empresa_emisora
                JOIN modfacturador.facturtblempemisora emi
                ON conf.pk_empresa_emisora = emi.pk_empresa_emisora
                JOIN modfacturador.facturtbltributos trib
                ON conf.pk_tributo_codigo = trib.pk_tributo_codigo
                JOIN modfacturador.facturtblxmlcomfiar xmlfac
                ON nota.pk_factura_codigo = xmlfac.pk_factura_codigo 
                AND xmlfac.pk_tipo_xml_codigo = 1
                WHERE nota.pk_nota_codigo=$pk_nota_codigo");
            $datosConfiguracion = $SqldatosConfiguracion->result_array[0];
            $ambienteTxComfiar = $datosConfiguracion['AMBIENTE_TRANSMISION'];
            $nitEmisor = $datosConfiguracion['NIT'];
            $digVerEmisor = $datosConfiguracion['DIGITO_VERIFICACION'];
            $resolucion = $datosConfiguracion['RESOLUCION'];
            $fechaInicioResol = $datosConfiguracion['FECHA_INICIO_RESOL'];
            $fechaFinResol = $datosConfiguracion['FECHA_FIN_RESOL'];
            $inicioRangoResol = $datosConfiguracion['RANGO_INICIO_RESOL_NC'];
            $finRangoResol = $datosConfiguracion['RANGO_FIN_RESOL_NC'];
            $prefijoNC = $datosConfiguracion['PREFIJO_NC'];
            $correoEmisor = $datosConfiguracion['CORREO_EMISOR_FACTURACION'];
            $direccionEmisor = $datosConfiguracion['DIRECCION_EMISOR'];
            $telefonoEmisor = $datosConfiguracion['TELEFONO_EMISOR'];
            $nameTaxScheme = $datosConfiguracion['NOMBRE_TRIBUTO'];
            $idTaxScheme = $datosConfiguracion['CODIGO_TRIBUTO'];
            $emisorRegistrationName = $datosConfiguracion['NOMBRE'];
            $numeroNota = $datosConfiguracion['NUMERO_NOTA_CREDITO'];
            $idiomaFactura = $datosConfiguracion['IDIOMA_FACTURA'];
            $valorTRM = $datosConfiguracion['VALOR_TRM'];
            $fechaTRM = $datosConfiguracion['FECHA_TRM'];
            $cufeFactura = $datosConfiguracion['CUFE_FACTURA'];
            $issuedateFactura = $datosConfiguracion['ISSUEDATE'];
            $idComprobanteFactura = $datosConfiguracion['ID_COMPROBANTE'];

            //data para tag AccountingCustomerParty información cliente

            $currencyCode = '';
            $paymentExchangeRate = '';
            if ($idiomaFactura == 'I') {
                $currencyCode = 'USD';
                $paymentExchangeRate = '<cac:PaymentExchangeRate>
					<cbc:SourceCurrencyCode>USD</cbc:SourceCurrencyCode>
					<cbc:SourceCurrencyBaseRate>1.00</cbc:SourceCurrencyBaseRate>
					<cbc:TargetCurrencyCode>COP</cbc:TargetCurrencyCode>
					<cbc:TargetCurrencyBaseRate>1.00</cbc:TargetCurrencyBaseRate>
					<cbc:CalculationRate>' . $valorTRM . '</cbc:CalculationRate>
					<cbc:Date>' . $fechaTRM . '</cbc:Date>
				</cac:PaymentExchangeRate>';
            } else {
                $currencyCode = 'COP';
            }



            $SqlClienteNota = $this->db->query("SELECT
                CLIE.RAZON_SOCIAL,
                CLIE.PRIMER_NOMBRE,
                CLIE.SEGUNDO_NOMBRE,
                CLIE.APELLIDOS_CLIENTE,
                CLIE.PK_TIPORG_CODIGO, --1 juridica 2 natural
                CLIE.IDENTIFICACION NIT,
                CLIE.DIGITO_VERIFICACION,
                TIPDOC.CODIGO_COMFIAR,
                CLIE.CORREO_AUTORIZADO,
                CIU.CODIGO_DANE,
                PAIS.NOMBRE PAIS,
                NVL(CIU.NOMBRE,DEP.NOMBRE) CIUDAD,
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
                Where clie.pk_cliente_codigo=$pk_cliente_codigo");
            $ClienteNota = $SqlClienteNota->result_array[0];
            $nombreCliente = str_replace("&", "&amp;", $ClienteNota['RAZON_SOCIAL']);
            $primerNombreCliente = str_replace("&", "&amp;", $ClienteNota['PRIMER_NOMBRE']);
            $segundoNombreCliente = str_replace("&", "&amp;", $ClienteNota['SEGUNDO_NOMBRE']);
            $apellidosCliente = str_replace("&", "&amp;", $ClienteNota['APELLIDOS_CLIENTE']);
            $tipoPersona = intval($ClienteNota['PK_TIPORG_CODIGO']);
            $nitCliente = $ClienteNota['NIT'];
            $clienteDV = $ClienteNota['DIGITO_VERIFICACION'];
            $clienteSchemeName = $ClienteNota['CODIGO_COMFIAR']; //31 Nit 
            $emailCliente = $ClienteNota['CORREO_AUTORIZADO'];
            $daneCliente = $ClienteNota['CODIGO_DANE']; //RegistrationAddress @ID
            $clienteCodDep = substr($daneCliente, 1);  //CountrySubentityCode
            $pais = $ClienteNota['PAIS'];
            $ciudadCliente = $ClienteNota['CIUDAD'];
            $departamentoCliente = $ClienteNota['DEPARTAMENTO'];
            $codigoPostal = $ClienteNota['CODIGO_POSTAL'];
            $direccion_cliente = $ClienteNota['DIRECCION_CLIENTE'];
            $taxLevelCode = $ClienteNota['RESPFIS'];
            $taxLevelCodeListName = $ClienteNota['TAXNAME'];
            $taxShemeName = $ClienteNota['NOMBRE_TRIBUTO'];
            $taxShemeID = $ClienteNota['CODIGO_TRIBUTO'];
            $telefono_cliente = $ClienteNota['TELEFONO'];

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

            $SqlDetalleNota = $this->db->query(" SELECT UPPER(detnot.detalle) NOMBRE,detnot.pk_producto_codigo, detnot.cantidad ,detnot.valor_unitario valor_unitario, detnot.valor_total, detnot.porcentaje_iva, detnot.total_iva
                    FROM modfacturador.facturtbldetallenotacredito detnot
                    where detnot.pk_nota_codigo=$pk_nota_codigo");
            $DetalleNota = $SqlDetalleNota->result_array;
            $idProduc = 1;
            $cantNote = 0;
            $lineExtensionAm = 0;
            $payableAmount = 0;
            $TaxExclusiveAm = 0;
            $subTotalIngPro = 0;
            $AllowanceTotal = 0;
            $taxInclusive = 0;
            $taxSubtotalPro = '';
            $creditNotLine = '';
            //iva de cada linea
            foreach ($DetalleNota as $value) {
                $porcIva = $value['PORCENTAJE_IVA'];
                $taxSubtotalPro = $taxSubtotalPro . '<cac:TaxSubtotal>
                                    <cbc:TaxableAmount currencyID="' . $currencyCode . '">' . $value['VALOR_TOTAL'] . '</cbc:TaxableAmount>
                                    <cbc:TaxAmount currencyID="' . $currencyCode . '">' . $value['TOTAL_IVA'] . '</cbc:TaxAmount>
                                    <cac:TaxCategory>
                                        <cbc:Percent>' . $porcIva . '</cbc:Percent>
                                        <cac:TaxScheme>
                                            <cbc:ID>01</cbc:ID>
                                            <cbc:Name>IVA</cbc:Name>
                                        </cac:TaxScheme>
                                    </cac:TaxCategory>
                                </cac:TaxSubtotal>';
            }
            foreach ($DetalleNota as $value) {
                $ivaPro = $value['PORCENTAJE_IVA'];
                $valIva = $value['TOTAL_IVA'];
                $lineExte = $value['VALOR_TOTAL'];
                $creditNotLine = $creditNotLine . '<cac:CreditNoteLine>
                    <cbc:ID>' . $idProduc++ . '</cbc:ID>
                    <cbc:UUID schemeAgencyID="195" schemeAgencyName="CO, DIAN (Dirección de Impuestos y Aduanas Nacionales)">' . $cufeFactura . '</cbc:UUID>
                    <cbc:CreditedQuantity unitCode="94">' . $value['CANTIDAD'] . '</cbc:CreditedQuantity>
                    <cbc:LineExtensionAmount currencyID="' . $currencyCode . '">' . $lineExte . '</cbc:LineExtensionAmount>
                    <cac:TaxTotal>
                        <cbc:TaxAmount currencyID="' . $currencyCode . '">' . $valIva . '</cbc:TaxAmount>
                        <cac:TaxSubtotal>
                            <cbc:TaxableAmount currencyID="' . $currencyCode . '">' . $lineExte . '</cbc:TaxableAmount>
                            <cbc:TaxAmount currencyID="' . $currencyCode . '">' . $valIva . '</cbc:TaxAmount>
                            <cac:TaxCategory>
                                <cbc:Percent>' . $ivaPro . '</cbc:Percent>
                                <cac:TaxScheme>
                                    <cbc:ID>01</cbc:ID>
                                    <cbc:Name>IVA</cbc:Name>
                                </cac:TaxScheme>
                            </cac:TaxCategory>
                        </cac:TaxSubtotal>
                    </cac:TaxTotal>
                    <cac:Item>
                        <cbc:Description>' . $value['NOMBRE'] . '</cbc:Description>
                        <cac:SellersItemIdentification>
                            <cbc:ID>' . $value['PK_PRODUCTO_CODIGO'] . '</cbc:ID>
                        </cac:SellersItemIdentification>
                        <cac:StandardItemIdentification>
                            <cbc:ID schemeID="999" >1</cbc:ID>
                        </cac:StandardItemIdentification>
                    </cac:Item>
                    <cac:Price>
                        <cbc:PriceAmount currencyID="' . $currencyCode . '">' . $value['VALOR_UNITARIO'] . '</cbc:PriceAmount>
                        <cbc:BaseQuantity unitCode="94">' . $value['CANTIDAD'] . '</cbc:BaseQuantity>
                        <cbc:PriceTypeCode>' . $currencyCode . '</cbc:PriceTypeCode>
                    </cac:Price>
                </cac:CreditNoteLine>';
                $cantNote++;
            }

            $sqlImpuestosNota = $this->db->query("select  
						NVL(modfacturador.pkgmodfacturadorgeneral.fncconsultarimpuestonotacre(parpknota=>NOTA.pk_nota_codigo,parnombreimpuesto=>'IVA%'),0) IVA,
						NVL(modfacturador.pkgmodfacturadorgeneral.fncconsultarimpuestonotacre(parpknota=>NOTA.pk_nota_codigo,parnombreimpuesto=>'RTE FTE%'),0) RTE_FUENTE,
						NVL(modfacturador.pkgmodfacturadorgeneral.fncconsultarimpuestonotacre(parpknota=>NOTA.pk_nota_codigo,parnombreimpuesto=>'RTE ICA%'),0) RTE_ICA,
						NVL(modfacturador.pkgmodfacturadorgeneral.fncconsultarimpuestonotacre(parpknota=>NOTA.pk_nota_codigo,parnombreimpuesto=>'RTE IVA%'),0) RTE_IVA,
						nota.total_nota,
                                                nota.subtotal_nota
                                                from modfacturador.facturtblnotacredito nota
						where nota.pk_nota_codigo =$pk_nota_codigo");

            $valorimpuestos = $sqlImpuestosNota->result_array[0];
            $totalApagarNota = $valorimpuestos['TOTAL_NOTA']; //equivale a lineExtensionAmount
            $subtotalNota = $valorimpuestos['SUBTOTAL_NOTA']; //equivale a TaxExclusiveAmount
            $ValorRte_fuente = $valorimpuestos['RTE_FUENTE'];
            $ValorRte_ica = $valorimpuestos['RTE_ICA'];
            $ValorRte_iva = $valorimpuestos['RTE_IVA'];
            $Total_iva = $valorimpuestos['IVA'];

            //RETE IVA 
            $sqlrteIva = $this->db->query("SELECT imp.PORCENTAJE,
            imp.base_calculo BASEAMOUNT,
            imp.IMPUESTO ,
            imp.VALOR_DEVUELTO AMOUNT
            FROM MODFACTURADOR.FACTURTBLIMPNOTACREDITO imp
            JOIN MODFACTURADOR.FACTURTBLDETALLENOTACREDITO dnota
            on imp.pk_detnot_codigo = dnota.pk_detnot_codigo
            JOIN MODFACTURADOR.FACTURTBLNOTACREDITO nota
            on dnota.pk_nota_codigo = nota.pk_nota_codigo
            WHERE nota.pk_nota_codigo =  $pk_nota_codigo
            AND imp.IMPUESTO LIKE 'RTE IVA%'");
            $RteIva = $sqlrteIva->result_array;
            $AllowanceCharge = '';
            $idAllowance = 1;
            $AmountRteIva = 0;
            $BaseAmountRteIva = 0;
            $nomImpuesto = '';
            $montoRteIva = 0;
            $porcRteIva = 0;
            foreach ($RteIva as $value) {
                if ($value['PORCENTAJE'] != 0) {
                    $porcRteIva = $value['PORCENTAJE'];
                    $nomImpuesto = $value['IMPUESTO'];
                    $AmountRteIva = $value['AMOUNT'];
                    $BaseAmountRteIva = $value['BASEAMOUNT'];
                    $AllowanceCharge = $AllowanceCharge . '<cac:AllowanceCharge>
					<cbc:ID>' . $idAllowance . '</cbc:ID>
					<cbc:ChargeIndicator>false</cbc:ChargeIndicator>
					<cbc:AllowanceChargeReasonCode>00</cbc:AllowanceChargeReasonCode>
					<cbc:AllowanceChargeReason>Descuento Impuesto ' . $nomImpuesto . '</cbc:AllowanceChargeReason>
					<cbc:MultiplierFactorNumeric>' . $porcRteIva . '</cbc:MultiplierFactorNumeric>
					<cbc:Amount currencyID="' . $currencyCode . '">' . $AmountRteIva . '</cbc:Amount>
					<cbc:BaseAmount currencyID="' . $currencyCode . '">' . $BaseAmountRteIva . '</cbc:BaseAmount>
				 </cac:AllowanceCharge>';
                    $idAllowance++;
                    $montoRteIva += $AmountRteIva;
                }
            }

            //RteFTE
            $sqlrteFte = $this->db->query("SELECT imp.PORCENTAJE,
            imp.base_calculo BASEAMOUNT,
            imp.IMPUESTO ,
            imp.VALOR_DEVUELTO AMOUNT
            FROM MODFACTURADOR.FACTURTBLIMPNOTACREDITO imp
            JOIN MODFACTURADOR.FACTURTBLDETALLENOTACREDITO dnota
            on imp.pk_detnot_codigo = dnota.pk_detnot_codigo
            JOIN MODFACTURADOR.FACTURTBLNOTACREDITO nota
            on dnota.pk_nota_codigo = nota.pk_nota_codigo
            WHERE nota.pk_nota_codigo =  $pk_nota_codigo
            AND imp.IMPUESTO LIKE 'RTE FTE%'");
            $RteFte = $sqlrteFte->result_array;

            $BaseAmountRteFte = 0;
            $nomImpuestoRteFte = '';
            $AmountRteFte = 0;
            $montoRteFte = 0;
            $porcRteFte = 0;
            foreach ($RteFte as $value) {
                if ($value['PORCENTAJE'] != 0) {
                    $porcRteFte = $value['PORCENTAJE'];
                    $nomImpuestoRteFte = $value['IMPUESTO'];
                    $AmountRteFte = $value['AMOUNT'];
                    $BaseAmountRteFte = $value['BASEAMOUNT'];
                    $AllowanceCharge = $AllowanceCharge . '<cac:AllowanceCharge>
					<cbc:ID>' . $idAllowance . '</cbc:ID>
					<cbc:ChargeIndicator>false</cbc:ChargeIndicator>
					<cbc:AllowanceChargeReasonCode>00</cbc:AllowanceChargeReasonCode>
					<cbc:AllowanceChargeReason>Descuento Impuesto ' . $nomImpuestoRteFte . '</cbc:AllowanceChargeReason>
					<cbc:MultiplierFactorNumeric>' . $porcRteFte . '</cbc:MultiplierFactorNumeric>
					<cbc:Amount currencyID="' . $currencyCode . '">' . $AmountRteFte . '</cbc:Amount>
					<cbc:BaseAmount currencyID="' . $currencyCode . '">' . $BaseAmountRteFte . '</cbc:BaseAmount>
				 </cac:AllowanceCharge>';
                    $idAllowance++;
                    $montoRteFte += $AmountRteFte;
                }
            }

            //RteIca
            $sqlrteIca = $this->db->query("SELECT imp.PORCENTAJE,
            imp.base_calculo BASEAMOUNT,
            imp.IMPUESTO ,
            imp.VALOR_DEVUELTO AMOUNT
            FROM MODFACTURADOR.FACTURTBLIMPNOTACREDITO imp
            JOIN MODFACTURADOR.FACTURTBLDETALLENOTACREDITO dnota
            on imp.pk_detnot_codigo = dnota.pk_detnot_codigo
            JOIN MODFACTURADOR.FACTURTBLNOTACREDITO nota
            on dnota.pk_nota_codigo = nota.pk_nota_codigo
            WHERE nota.pk_nota_codigo =  $pk_nota_codigo
            AND imp.IMPUESTO LIKE 'RTE ICA%'");
            $RteIca = $sqlrteIca->result_array;

            $BaseAmountRteIca = 0;
            $nomImpuestoRteIca = '';
            $AmountRteIca = 0;
            $montoRteIca = 0;
            $porcRteIca = 0;
            foreach ($RteIca as $value) {
                if ($value['PORCENTAJE'] != 0) {
                    $porcRteIca = $value['PORCENTAJE'];
                    $nomImpuestoRteIca = $value['IMPUESTO'];
                    $AmountRteIca = $value['AMOUNT'];
                    $BaseAmountRteIca = $value['BASEAMOUNT'];
                    $AllowanceCharge = $AllowanceCharge . '<cac:AllowanceCharge>
					<cbc:ID>' . $idAllowance . '</cbc:ID>
					<cbc:ChargeIndicator>false</cbc:ChargeIndicator>
					<cbc:AllowanceChargeReasonCode>00</cbc:AllowanceChargeReasonCode>
					<cbc:AllowanceChargeReason>Descuento Impuesto ' . $nomImpuestoRteIca . '</cbc:AllowanceChargeReason>
					<cbc:MultiplierFactorNumeric>' . $porcRteIca . '</cbc:MultiplierFactorNumeric>
					<cbc:Amount currencyID="' . $currencyCode . '">' . $AmountRteIca . '</cbc:Amount>
					<cbc:BaseAmount currencyID="' . $currencyCode . '">' . $BaseAmountRteIca . '</cbc:BaseAmount>
				 </cac:AllowanceCharge>';
                    $idAllowance++;
                    $montoRteIca += $AmountRteIca;
                }
            }
            $lineExtensionAm = $subtotalNota;
            $TaxExclusiveAm = $subtotalNota;
            $payableAmount = $totalApagarNota;
            $AllowanceTotal = $ValorRte_fuente + $ValorRte_ica + $ValorRte_iva;
            $taxInclusive = $subtotalNota + $Total_iva;
        }
        $dom = new DOMDocument;
        $dom->loadXML('<?xml version="1.0" encoding="utf-8"?>
<Comprobantes>
	<Comprobante>
		<informacionOrganismo>
			<CreditNote xmlns:clm66411="urn:un:unece:uncefact:codelist:specification:66411:2001" xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2" xmlns:clmIANAMIMEMediaType="urn:un:unece:uncefact:codelist:specification:IANAMIMEMediaType:2003" xmlns:qdt="urn:oasis:names:specification:ubl:schema:xsd:QualifiedDatatypes-2" xmlns:udt="urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2" xmlns:sts="dian:gov:co:facturaelectronica:Structures-2-1" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:ccts="urn:un:unece:uncefact:documentation:2" xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:clm54217="urn:un:unece:uncefact:codelist:specification:54217:2001" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns="urn:oasis:names:specification:ubl:schema:xsd:CreditNote-2">
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
										<sts:Prefix>' . $prefijoNC . '</sts:Prefix>
										<sts:From>' . $inicioRangoResol . '</sts:From>
										<sts:To>' . $finRangoResol . '</sts:To>
									</sts:AuthorizedInvoices>
								</sts:InvoiceControl>
								<sts:InvoiceSource>
									<cbc:IdentificationCode listAgencyID="6" listAgencyName="United Nations Economic Commission for Europe" listSchemeURI="urn:oasis:names:specification:ubl:codelist:gc:CountryIdentificationCode-2.1">CO</cbc:IdentificationCode>
								</sts:InvoiceSource>
								<sts:SoftwareProvider>
									<sts:SoftwareID schemeAgencyID="195" schemeAgencyName="CO, DIAN (Dirección de Impuestos y Aduanas Nacionales)"/>
								</sts:SoftwareProvider>
								<sts:SoftwareSecurityCode schemeAgencyID="195" schemeAgencyName="CO, DIAN (Dirección de Impuestos y Aduanas Nacionales)"/>
							</sts:DianExtensions>
						</ext:ExtensionContent>
					</ext:UBLExtension>
				</ext:UBLExtensions>
				<cbc:UBLVersionID>UBL 2.1</cbc:UBLVersionID>
				<cbc:CustomizationID>20</cbc:CustomizationID>
				<cbc:ProfileID>DIAN 2.1</cbc:ProfileID>
				<cbc:ProfileExecutionID>' . $ambienteTxComfiar . '</cbc:ProfileExecutionID>
				<cbc:ID>' . $prefijoNC . $numeroNota . '</cbc:ID>
				<cbc:UUID schemeID="' . $ambienteTxComfiar . '" schemeName="CUDE-SHA384"/>
				<cbc:IssueDate>' . date("Y-m-d") . '</cbc:IssueDate>
				<cbc:IssueTime>' . date("H:i:s") . '-05:00' . '</cbc:IssueTime>
				<cbc:CreditNoteTypeCode>91</cbc:CreditNoteTypeCode>
				<cbc:Note>' . $totalApagarNota . '</cbc:Note><!-- nota 1 total a pagar-->
				<cbc:Note>0</cbc:Note><!--nota 2 total ingresos propios-->
				<cbc:Note>0</cbc:Note><!-- nota 3 total ingresos terceros-->
				<cbc:Note>Régimen: Impuestos sobre las ventas - IVA
Persona Jurídica. Actividad económica 8220 Tarifa ICA 9.66x1.000. No Somos Grandes Contribuyentes. No Somos Autorretenedores. Resolución gráfica de la factura electrónica según parágrafo 1 articulo 3 decreto 2242 de 2015.</cbc:Note>
				<cbc:Note>' . $subtotalNota . '</cbc:Note><!-- Nota 5 subtotal ingresos propios-->
				<cbc:Note>' . $ValorRte_fuente . '</cbc:Note><!-- Nota 6 Rte fuente -->
				<cbc:Note>' . $ValorRte_ica . '</cbc:Note><!-- Nota 7 Rte ICA -->
				<cbc:Note>' . $ValorRte_iva . '</cbc:Note><!-- Nota 8 Rte IVA -->
				<cbc:Note>' . $porcRteFte . '|' . $porcRteIca . '|' . $porcRteIva . '|19</cbc:Note><!-- Nota 9 Porcentaje -->
				<cbc:Note>'.$idiomaFactura.'</cbc:Note><!--Nota 10  idioma nota -->
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
				<cbc:DocumentCurrencyCode>' . $currencyCode . '</cbc:DocumentCurrencyCode>
				<cbc:LineCountNumeric>' . $cantNote . '</cbc:LineCountNumeric>
				<cac:BillingReference>
					<cac:InvoiceDocumentReference>
						<cbc:ID>' . $idComprobanteFactura . '</cbc:ID>
						<cbc:UUID schemeName="CUFE-SHA384">' . $cufeFactura . '</cbc:UUID>
						<cbc:IssueDate>' . $issuedateFactura . '</cbc:IssueDate>
					</cac:InvoiceDocumentReference>
				</cac:BillingReference>
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
                                ' . $paymentExchangeRate . '
                                ' . $AllowanceCharge . '
				<cac:TaxTotal>
                                        <cbc:TaxAmount currencyID = "' . $currencyCode . '">' . $Total_iva . '</cbc:TaxAmount>
                                        ' . $taxSubtotalPro . '
                                </cac:TaxTotal>
				<cac:LegalMonetaryTotal>
					<cbc:LineExtensionAmount currencyID="' . $currencyCode . '">' . $lineExtensionAm . '</cbc:LineExtensionAmount>
					<cbc:TaxExclusiveAmount currencyID="' . $currencyCode . '">' . $TaxExclusiveAm . '</cbc:TaxExclusiveAmount>
					<cbc:TaxInclusiveAmount currencyID="' . $currencyCode . '">' . $taxInclusive . '</cbc:TaxInclusiveAmount>
                                        <cbc:AllowanceTotalAmount currencyID="' . $currencyCode . '">' . $AllowanceTotal . '</cbc:AllowanceTotalAmount>
					<cbc:PayableAmount currencyID="' . $currencyCode . '">' . $payableAmount . '</cbc:PayableAmount>
				</cac:LegalMonetaryTotal>
				' . $creditNotLine . '
			</CreditNote>
		</informacionOrganismo>
                <informacionComfiar>
                    <ruc>' . $nitEmisor . '</ruc>
                    <codDoc>04</codDoc>
                    <prefixPtoVenta>' . $prefijoNC . '</prefixPtoVenta>
                    <nroCbte>' . $numeroNota . '</nroCbte>
                    <Receptores>
                      <Receptor>
                        <Login>PASS' . $nitCliente . '</Login>
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

    public function salida_Transaccion($sessionId = null, $fechaVen = null, $transaccionId = null, $pk_conf_codigo = null) {
        log_info($this->logHeader . 'INGRESO LIBRERIA salida_Transaccion');
        log_info($this->logHeader . $this->postData . 'TransaccionId = ' . $transaccionId . ' pk_conf_codigo = ' . $pk_conf_codigo);

        if (!empty($sessionId) && !empty($fechaVen) && !empty($transaccionId) && !empty($pk_conf_codigo)) {
            $urlWsdl = $this->retornarValorConfiguracion($pk_conf_codigo, 'URL_WS_COMFIAR');
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
            $cuitProcesar = $this->retornarValorConfiguracion($pk_conf_codigo, 'NIT_CUITID');
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
    public function respuesta_Comprobante($sessionId = null, $fechaVen = null, $transaccionId = null, $pk_conf_codigo = null) {

        log_info($this->logHeader . 'INGRESO LIBRERIA respuesta_Comprobante');
        log_info($this->postData . 'Pk_conf_codigo = ' . $pk_conf_codigo . ' Transaccion_id = ' . $transaccionId);

        if (!empty($sessionId) && !empty($fechaVen) && !empty($transaccionId) && !empty($pk_conf_codigo)) {
            $urlWsdl = $this->retornarValorConfiguracion($pk_conf_codigo, 'URL_WS_COMFIAR');
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
            $cuitProcesar = $this->retornarValorConfiguracion($pk_conf_codigo, 'NIT_CUITID');
            $puntoVentaId = $this->retornarValorConfiguracion($pk_conf_codigo, 'ID_PUNTO_VENTA_NTC');
            $tipoComprobanteId = $this->retornarValorConfiguracion($pk_conf_codigo, 'ID_TIPO_COMPROBANTE_NTC');


            $nroCbte = $transaccionId;
            // web service input params
            $request_param = array(
                "cuitId" => $cuitProcesar, // Cuit, RUC o NIT del emisor del comprobante.
                "puntoDeVentaId" => $puntoVentaId, // Número de punto de venta a procesar para factura 10002
                "tipoDeComprobanteId" => $tipoComprobanteId, // tipo comprobante Nota 04
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
                log_info($this->logHeader . '-' . $this->soapCorrecto . 'RespuestaComprobante::');
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
    public function retornarValorConfiguracion($pk_conf_codigo, $parametro) {
        log_info($this->logHeader . $this->postData . ' ::retornarValorConfiguracion:: PK_CONF_CODIGO= ' . $pk_conf_codigo . ' PARAMETRO= ' . $parametro);

        if (!empty($pk_conf_codigo) && !empty($parametro)) {
            if ($parametro == 'CONTRASENA_COMFIAR') {
                $sqlconfigComfiar = $this->db->query("SELECT  MODGENERI.GENPKGCLAGEN.DECRYPT(CONTRASENA_COMFIAR) VALOR_PARAMETRO FROM MODFACTURADOR.FACTURTBLEMPEMICONFIG CONF
                                        WHERE CONF.PK_CONF_CODIGO=$pk_conf_codigo");
                $valorParametro = $sqlconfigComfiar->result_array[0];
                $ValorParametroReturn = $valorParametro['VALOR_PARAMETRO'];
            } else {
                $sqlconfigComfiar = $this->db->query("SELECT $parametro VALOR_PARAMETRO FROM MODFACTURADOR.FACTURTBLEMPEMICONFIG CONF
                                        WHERE CONF.PK_CONF_CODIGO=$pk_conf_codigo");

                $valorParametro = $sqlconfigComfiar->result_array[0];
                $ValorParametroReturn = $valorParametro['VALOR_PARAMETRO'];
            }
        }

        return $ValorParametroReturn;
    }

    //Consumo SOAP Comfiar retorna pdf, tipo comprobante 01=factura  2=Nota Credito
    public function descarga_pdf($sessionId = null, $fechaVen = null, $transaccionId = null, $nroComprobante = null, $pk_conf_codigo = null) {
        log_info($this->logHeader . 'INGRESO LIBRERIA descarga_pdf');
        log_info($this->logHeader . $this->postData . 'TransaccionId = ' . $transaccionId . ' Nota Crédito No = ' . $nroComprobante . ' Pk_conf_codigo = ' . $pk_conf_codigo);

        $codRespuesta = 0;
        if (!empty($sessionId) && !empty($fechaVen) && !empty($transaccionId) && !empty($nroComprobante) && !empty($pk_conf_codigo)) {
            $urlWsdl = $this->retornarValorConfiguracion($pk_conf_codigo, 'URL_WS_COMFIAR');
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
            $cuitId = $this->retornarValorConfiguracion($pk_conf_codigo, 'NIT_CUITID');
            $puntoVentaId = $this->retornarValorConfiguracion($pk_conf_codigo, 'ID_PUNTO_VENTA_NTC');
            $tipoComprobanteId = $this->retornarValorConfiguracion($pk_conf_codigo, 'ID_TIPO_COMPROBANTE_NTC');
            $prefijoPeople = $this->retornarValorConfiguracion($pk_conf_codigo, 'PREFIJO_NOTA_CREDITO');
            $folderPath = $this->retornarValorConfiguracion($pk_conf_codigo, 'URL_ALMACEN_NC');

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
                    log_info($this->logHeader . $this->iniciLog . '::folderPath::' . $folderPath);

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

                    log_info($this->logHeader . $this->soapCorrecto . '::Consumo Correcto soap DescargarPdf::URL PDF NC COMFIAR::' . $dataReturn);
                    $codRespuesta = 1;
                    $response = $dataReturn;
                } else {
                    $response = 'Error consumo Soap';
                }
            } catch (Exception $e) {
                log_info($this->logHeader . $this->errorFuncion . 'ERROR SOAP::' . $e->getMessage());

                $response = 'Error consumo DescargarPdf :' . $e->getMessage();
            }
        } else {
            log_info($this->logHeader . $this->errorFuncion . 'ERROR DATOS NULOS.');
            $response = 'Datos incorrectos.';
        }
        $objectRespuesta = (object) [
                    'CodRespuesta' => $codRespuesta,
                    'Respuesta' => $response];
        return $objectRespuesta;
    }

}
