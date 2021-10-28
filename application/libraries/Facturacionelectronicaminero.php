<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class FacturacionElectronicaMinero extends CI_Controller {

    public $iniciLog = '[INFO] ';
    public $logHeader = '[INFO] APOLO_TALOS_INFO::::::::: ';
    public $postData = 'POSTDATA::::::::: ';
    public $soapCorrecto = 'CONSUMO SOAP CORRECTO::::::: ';
    public $finFuncion = ' FIN PROCEDIMIENTO::::::: ';
    public $errorFuncion = 'ERROR::::::: ';
    public $errorBD = 'ERROR BASE DE DATOS::::::: ';

    public function __construct() {
        parent::__construct();
        $this->load->helper('log4php');
    }

    public function __destruct() {
        $this->db->close();
    }

    public function iniciar_sesion($pk_factura) {

        log_info($this->logHeader . 'INGRESO LIBRERIA FACTURADOR FUNCION INICIAR_SESION Pk_factura_codigo: ' . $pk_factura);
        $usuarioComfiar = $this->retornarValorConfiguracion($pk_factura, 'USUARIO_COMFIAR');
        $passComfiar = $this->retornarValorConfiguracion($pk_factura, 'CONTRASENA_COMFIAR');

        //********Codigo iniciar sesion comfiar */
        if (!empty($usuarioComfiar) && !empty($passComfiar)) {
            $urlWsdl = $this->retornarValorConfiguracion($pk_factura, 'URL_WS_COMFIAR');
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

    public function autorizar_Comprobante($sessionId = null, $fechaVen = null, $pk_factura = null) {

        log_info($this->logHeader . 'INGRESO LIBRERIA autorizar_Comprobante');
        log_info($this->postData . 'PK_FACTURA' . $pk_factura);
        //********Codigo autorizar comprobante*/
        if (!empty($sessionId) && !empty($fechaVen) && !empty($pk_factura)) {
            $urlWsdl = $this->retornarValorConfiguracion($pk_factura, 'URL_WS_COMFIAR');
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
            $xml = $this->generarXML($pk_factura);
            //Se valida que la $pk_factura del xml sea correcta de lo contrario se actualiza factura a erronea
            if ($xml != 404) {
                $client = new SoapClient($wsdl, $options);
                $cuitProcesar = $this->retornarValorConfiguracion($pk_factura, 'NIT_CUITID');
                $puntoVentaId = $this->retornarValorConfiguracion($pk_factura, 'ID_PUNTO_VENTA_FAC');
                $formatoId = $this->retornarValorConfiguracion($pk_factura, 'FORMATO_ID');
                $tipoComprobanteId = $this->retornarValorConfiguracion($pk_factura, 'ID_TIPO_COMPROBANTE_FAC');

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
                    log_info($this->soapCorrecto . 'AutorizarComprobantesAsincronico::' . json_encode($responce_param));

                    $response = $responce_param;
                } catch (Exception $e) {
//                echo "<h2>Exception Error!</h2>";
//                echo $e->getMessage();
                    log_info($this->errorFuncion . 'ERROR SOAP::' . $e->getMessage());
                    $response = 'Error consumo AutorizarComprobantesAsincronico :' . $e->getMessage();
                }
            } else {
                $response = 404;
            }
        } else {
            log_info($this->errorFuncion . 'SESSIONID , FECHAVEN , IDFACT DATOS NULOS.');
            $response = 'Datos incorrectos.';
        }
        return $response;
    }

    //Funcion usar autorizar comprobante nota credito minero
    public function autorizar_Comprobante_Nc_Minero($sessionId = null, $fechaVen = null, $pk_factura = null, $pk_nota_minero = null) {

        log_info($this->logHeader . 'INGRESO LIBRERIA autorizar_Comprobante_Nc_Minero');
        log_info($this->postData . 'PK_FACTURA AFCETADA: ' . $pk_factura . ' Pk_NOTA: ' . $pk_nota_minero);
        //********Codigo autorizar comprobante*/
        if (!empty($sessionId) && !empty($fechaVen) && !empty($pk_factura) && !empty($pk_nota_minero)) {
            $urlWsdl = $this->retornarValorConfiguracion($pk_factura, 'URL_WS_COMFIAR');
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
            $xml = $this->generarXmlNC($pk_nota_minero);
            //Se valida que la $pk_factura del xml sea correcta de lo contrario se actualiza factura a erronea
            if ($xml != 404) {
                $client = new SoapClient($wsdl, $options);
                $cuitProcesar = $this->retornarValorConfiguracion($pk_factura, 'NIT_CUITID');
                $puntoVentaId = $this->retornarValorConfiguracion($pk_factura, 'ID_PUNTO_VENTA_NTC');
                $formatoId = $this->retornarValorConfiguracion($pk_factura, 'FORMATO_ID');
                $tipoComprobanteId = $this->retornarValorConfiguracion($pk_factura, 'ID_TIPO_COMPROBANTE_NTC');

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
                    log_info($this->soapCorrecto . 'AutorizarComprobantesAsincronico::' . json_encode($responce_param));

                    $response = $responce_param;
                } catch (Exception $e) {
//                echo "<h2>Exception Error!</h2>";
//                echo $e->getMessage();
                    log_info($this->errorFuncion . 'ERROR SOAP::' . $e->getMessage());
                    $response = 'Error consumo AutorizarComprobantesAsincronico :' . $e->getMessage();
                }
            } else {
                $response = 404;
            }
        } else {
            log_info($this->errorFuncion . 'SESSIONID , FECHAVEN , IDFACT DATOS NULOS.');
            $response = 'Datos incorrectos.';
        }
        return $response;
    }

    public function generarXML($pk_factura_talos = null) {

        if (!empty($pk_factura_talos)) {

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
                CONF.PREFIJO_FACTURA,
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
                FAC.NOMBRE_PRODUCTO,
                FAC.CONTRATO,
                FAC.FECHA_LIMITE_PAGO,
                FAC.SALDO_ANTERIOR,
                FAC.ABONO_CAPITAL,
                FAC.INGRESOS_GRAVADOS,
                FAC.INGRESOS_NO_GRAVADOS,
                FAC.TOTAL_IVA,
                FAC.TOTAL_OPE_PROPIA,
                FAC.TOTAL_TERCEROS,
                FAC.SUBTOTAL_PERIODO,
                FAC.PAGO_MINIMO,
                TO_CHAR(FAC.FECHA_EMISION,'HH24:MI:SS') HORA_FACTURA,
                TO_CHAR(FAC.FECHA_EMISION,'YYYY-MM-DD') FECHA_FACTURA
                From modfacturador.facturtblfacturacomfiar fac
                JOIN modfacturador.facturtblempemiconfig conf
                ON fac.pk_empresa_emisora = conf.pk_empresa_emisora
                JOIN modfacturador.facturtblempemisora emi
                ON conf.pk_empresa_emisora = emi.pk_empresa_emisora
                JOIN modfacturador.facturtbltributos trib
                ON conf.pk_tributo_codigo = trib.pk_tributo_codigo
                WHERE fac.pk_factura_codigo=$pk_factura_talos");
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
            $pkCliente = $datosConfiguracion['PK_CLIENTE_CODIGO'];
            $subtotalFactura = $datosConfiguracion['SUBTOTAL']; //equivale a lineExtensionAmount
            $fechaLimitePago = $datosConfiguracion['FECHA_LIMITE_PAGO'];
            $nombreProducto = $datosConfiguracion['NOMBRE_PRODUCTO'];
            $contrato = $datosConfiguracion['CONTRATO'];
            $saldoAnterior = $datosConfiguracion['SALDO_ANTERIOR'];
            $abonoCapital = $datosConfiguracion['ABONO_CAPITAL'];
            $ingresosGravados = $datosConfiguracion['INGRESOS_GRAVADOS'];
            $ingresosNoGravados = $datosConfiguracion['INGRESOS_NO_GRAVADOS'];
            $totalIva = $datosConfiguracion['TOTAL_IVA'];
            $totalOpePropia = $datosConfiguracion['TOTAL_OPE_PROPIA'];
            $totalTerceros = $datosConfiguracion['TOTAL_TERCEROS'];
            $subtotalPeriodo = $datosConfiguracion['SUBTOTAL_PERIODO'];
            $pagoMinimo = $datosConfiguracion['PAGO_MINIMO'];
            $hora_factura =$datosConfiguracion['HORA_FACTURA'];
            $fecha_factura =$datosConfiguracion['FECHA_FACTURA'];

            log_info($this->logHeader . $this->postData . 'ambienteTxComfiar: ' . $ambienteTxComfiar .
                    ' NitEmisor: ' . $nitEmisor .
                    ' DigVerEmisor: ' . $digVerEmisor .
                    ' Resolucion: ' . $resolucion .
                    ' FechaInicioResol: ' . $fechaInicioResol .
                    ' FechaFinResol: ' . $fechaFinResol .
                    ' InicioRangoResol: ' . $inicioRangoResol .
                    ' FinRangoResol: ' . $finRangoResol .
                    ' PrefijoFac: ' . $prefijoFac .
                    ' CorreoEmisor: ' . $correoEmisor .
                    ' DireccionEmisor: ' . $direccionEmisor .
                    ' TelefonoEmisor: ' . $telefonoEmisor .
                    ' NameTaxScheme: ' . $nameTaxScheme .
                    ' IdTaxScheme: ' . $idTaxScheme .
                    ' EmisorRegistrationName: ' . $emisorRegistrationName .
                    ' NumeroFactura: ' . $numeroFactura .
                    ' ObservacionFactura: ' . $observacionFactura .
                    ' PkCliente: ' . $pkCliente .
                    ' SubtotalFactura: ' . $subtotalFactura
            );
            // Datos Cliente
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
            $nombreCliente = str_replace("&", "&amp;", $ClienteFactura['RAZON_SOCIAL']);
            $primerNombreCliente = str_replace("&", "&amp;", $ClienteFactura['PRIMER_NOMBRE']);
            $segundoNombreCliente = str_replace("&", "&amp;", $ClienteFactura['SEGUNDO_NOMBRE']);
            $apellidosCliente = str_replace("&", "&amp;", $ClienteFactura['APELLIDOS_CLIENTE']);
            $tipoPersona = intval($ClienteFactura['PK_TIPORG_CODIGO']);
            $nitCliente = $ClienteFactura['NIT'];
            $clienteDV = $ClienteFactura['DIGITO_VERIFICACION'];
            $clienteSchemeName = $ClienteFactura['CODIGO_COMFIAR']; //31 Nit 
            $emailCliente = $ClienteFactura['CORREO_AUTORIZADO'];
            $daneCliente = $ClienteFactura['CODIGO_DANE']; //RegistrationAddress @ID
            $clienteCodDep = substr($daneCliente, 0, 2);  //CountrySubentityCode
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

            log_info($this->logHeader . $this->postData . 'NombreCliente: ' . $nombreCliente .
                    ' PrimerNombreCliente: ' . $primerNombreCliente .
                    ' SegundoNombreCliente: ' . $segundoNombreCliente .
                    ' ApellidosCliente: ' . $apellidosCliente .
                    ' TipoPersona: ' . $tipoPersona .
                    ' NitCliente: ' . $nitCliente .
                    ' ClienteDV: ' . $clienteDV .
                    ' ClienteSchemeName: ' . $clienteSchemeName .
                    ' EmailCliente: ' . $emailCliente .
                    ' DaneCliente: ' . $daneCliente .
                    ' ClienteCodDep: ' . $clienteCodDep .
                    ' Pais: ' . $pais .
                    ' CiudadCliente: ' . $ciudadCliente .
                    ' PepartamentoCliente: ' . $departamentoCliente .
                    ' CodigoPostal: ' . $codigoPostal .
                    ' Direccion_cliente: ' . $direccion_cliente .
                    ' TaxLevelCode: ' . $taxLevelCode .
                    ' taxLevelCodeListName: ' . $taxLevelCodeListName .
                    ' TaxShemeName: ' . $taxShemeName .
                    ' TaxShemeID: ' . $taxShemeID .
                    ' Telefono_cliente: ' . $telefono_cliente
            );

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

            $SqlDetalleFactura = $this->db->query("SELECT              DETFAC.CODIGO_PRODUCTO,
                    DETFAC.NOMBRE_PRODUCTO,
                    DETFAC.CANTIDAD,
                    DETFAC.VALOR_UNITARIO,
                    DETFAC.PORCENTAJE_IVA,
                    DETFAC.TOTAL_IVA,
                    DETFAC.TOTAL,
                    DETFAC.VALOR_OBSEQUIO
                    FROM modfacturador.facturtbldetallefacturatalos detfac
                    WHERE detfac.pk_factura_codigo =$pk_factura_talos");
            $detalleFactura = $SqlDetalleFactura->result_array;

            $CalculoIva = 0;
            $idProduc = 1;
            $InvoiceLine = '';
            $cantInvoice = 0;
            $Total_Monto_Sin_Iva = 0;
            $Total_Monto_Con_Iva = 0;
            $Total_Gravado = 0;
            $Total_Sin_Gravar = 0;
            $porc_iva_TaxScheme = 0;
            //falta comenzar dibujar linea factura 

            $taxtTotal = '';
            $PricingReference = '';
            $valor_obsequios=0;
            foreach ($detalleFactura as $value) {
                if ($value['VALOR_UNITARIO'] == 0) {
                    $valor_obsequios+=$value['VALOR_OBSEQUIO'];
                    $PricingReference = '<cac:PricingReference>
                                            <cac:AlternativeConditionPrice>
                                            <!--Falta traer valor producto  -->
                                                <cbc:PriceAmount currencyID="COP">' . $value['VALOR_OBSEQUIO'] . '</cbc:PriceAmount>
                                                <cbc:PriceTypeCode>01</cbc:PriceTypeCode> 
                                            </cac:AlternativeConditionPrice>
                                          </cac:PricingReference>';
                } else {
                    $PricingReference = '';
                }
                $TotalIva = $value['TOTAL_IVA'];
                $lineExte = $value['TOTAL'];
                $porcIva = $value['PORCENTAJE_IVA'];

                $taxSubtotalPro = '<cac:TaxSubtotal>
                                    <cbc:TaxableAmount currencyID="COP">' . $lineExte . '</cbc:TaxableAmount>
                                    <cbc:TaxAmount currencyID="COP">' . $TotalIva . '</cbc:TaxAmount>
                                    <cac:TaxCategory>
                                        <cbc:Percent>' . $porcIva . '.00</cbc:Percent>
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
          <cbc:LineExtensionAmount currencyID="COP">' . $lineExte . '</cbc:LineExtensionAmount>
              ' . $PricingReference . '
              <cac:TaxTotal>
		<cbc:TaxAmount currencyID="COP">' . $TotalIva . '</cbc:TaxAmount>
                <cbc:RoundingAmount currencyID="COP">0</cbc:RoundingAmount>
               ' . $taxSubtotalPro . '
            </cac:TaxTotal>
          <cac:Item>
            <cbc:Description>' . ucfirst($value['NOMBRE_PRODUCTO']) . '</cbc:Description>
            <cac:SellersItemIdentification>
		<cbc:ID>' . str_pad($value['CODIGO_PRODUCTO'], 3, "0", STR_PAD_LEFT) . '</cbc:ID>
            </cac:SellersItemIdentification>
            <cac:StandardItemIdentification>
		<cbc:ID schemeName="Estándar de adopción del contribuyente" schemeID="999">' . str_pad($value['CODIGO_PRODUCTO'], 3, "0", STR_PAD_LEFT) . '</cbc:ID>
            </cac:StandardItemIdentification>
          </cac:Item>
          <cac:Price>
            <cbc:PriceAmount currencyID="COP">' . $value['VALOR_UNITARIO'] . '</cbc:PriceAmount>
            <cbc:BaseQuantity unitCode="94">' . intval($value['CANTIDAD']) . '</cbc:BaseQuantity>
          </cac:Price>
        </cac:InvoiceLine>';

                $idProduc++;
                $cantInvoice++;

                if ($porcIva == 19) {
                    $Total_Gravado += $TotalIva;
                    $Total_Monto_Con_Iva += $lineExte;
                    $porc_iva_TaxScheme = $porcIva;
                } elseif ($porcIva == 0) {
                    $Total_Monto_Sin_Iva += $lineExte;
                }
            }
            //TaxSubTotal Con iva
            $TaxTotalConIva = '';
            if ($Total_Gravado != 0) {
                $TaxTotalConIva = '<cac:TaxSubtotal>
                            <cbc:TaxableAmount currencyID="COP">' . $Total_Monto_Con_Iva . '</cbc:TaxableAmount>
                            <cbc:TaxAmount currencyID="COP">' . $Total_Gravado . '</cbc:TaxAmount>
                            <cac:TaxCategory>
                                <cbc:Percent>' . $porc_iva_TaxScheme . '.00</cbc:Percent>
                                <cac:TaxScheme>
                                    <cbc:ID>01</cbc:ID>
                                    <cbc:Name>IVA</cbc:Name>
                                </cac:TaxScheme>
                            </cac:TaxCategory>
                        </cac:TaxSubtotal>';
            }

            $TaxTotalSinIva = '';
            if ($Total_Monto_Sin_Iva != 0 || ($Total_Monto_Sin_Iva==0 && $valor_obsequios!=0)) {
                $TaxTotalSinIva = '<cac:TaxSubtotal>
                            <cbc:TaxableAmount currencyID="COP">' . $Total_Monto_Sin_Iva . '</cbc:TaxableAmount>
                            <cbc:TaxAmount currencyID="COP">0</cbc:TaxAmount>
                            <cac:TaxCategory>
                                <cbc:Percent>0.00</cbc:Percent>
                                <cac:TaxScheme>
                                    <cbc:ID>01</cbc:ID>
                                    <cbc:Name>IVA</cbc:Name>
                                </cac:TaxScheme>
                            </cac:TaxCategory>
                        </cac:TaxSubtotal>';
            }
        }
        $fecha_actual = date("Y-m-d");
        $LineExtensionAm = $subtotalFactura;
        $TaxExclusiveAm = $subtotalFactura;
        $TaxInclusive = $subtotalFactura + $totalIva;
        $PayableAmount = $pagoMinimo;
        $TotalCodBarras = round($PayableAmount); //explode(".", round($PayableAmount));//trim(trim($PayableAmount,0), '.');
        $TotalCodBarras = str_pad($TotalCodBarras, 10, "0", STR_PAD_LEFT);
        $contrato = str_pad($contrato, 10, "0", STR_PAD_LEFT);
        $FechaLimite = date("Ymd", strtotime($fechaLimitePago));
        $fechaLimitePago = date("Y-m-d", strtotime($fechaLimitePago));

        $nota7 = 'Esta factura de venta se asimila en todos sus efectos a una letra de cambio (arts. 772 a 774 CCo.) Después de su vencimiento y en caso de no pago de las obligaciones aquí contenidas, se causarán intereses moratorios en los términos permitidos por la Superintendencia Financiera de Colombia, hasta el día que se verifique su pago.';
        $dom = new DOMDocument;
        $dom->preserveWhiteSpace = false;
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
				<cbc:ProfileID>DIAN 2.1: Factura Electrónica de Venta</cbc:ProfileID>
				<cbc:ProfileExecutionID>' . $ambienteTxComfiar . '</cbc:ProfileExecutionID>
				<cbc:ID>' . $prefijoFac . $numeroFactura . '</cbc:ID>
				<cbc:UUID schemeID="' . $ambienteTxComfiar . '" schemeName="CUFE-SHA384" />
				<cbc:IssueDate>' . $fecha_factura. '</cbc:IssueDate>
				<cbc:IssueTime>' . $hora_factura . '-05:00' . '</cbc:IssueTime>
				<cbc:InvoiceTypeCode>01</cbc:InvoiceTypeCode>
				<cbc:Note>' . number_format($saldoAnterior, 0, ',', '') . '</cbc:Note><!-- Nota 1 total a pagar - a. Saldo Anterior-->
				<cbc:Note>' . number_format($abonoCapital, 0, ',', '') . '</cbc:Note><!--Nota 2  - b.Abonos a capital-->
				<cbc:Note>' . number_format($ingresosGravados, 0, ',', '') . '</cbc:Note><!-- Nota 3 - c.Ingresos gravados-->
				<cbc:Note>Régimen: Impuestos sobre las ventas - IVA 
Persona Jurídica. Actividad económica 6619 Tarifa ICA 11.04/1.000. No Somos Grandes Contribuyentes. Resolución gráfica de la factura electrónica según parágrafo 1 articulo 3 decreto 2242 de 2015. Vigencia 12 meses.</cbc:Note>
				<cbc:Note>' . number_format($ingresosNoGravados, 0, ',', '') . '</cbc:Note><!-- Nota 5 - d.Ingresos no gravados  -->
				<cbc:Note>' . $observacionFactura . '</cbc:Note><!-- Nota 6 observaciones -->
				<cbc:Note>' . $nota7 . '</cbc:Note>
				<cbc:Note>' . number_format($totalOpePropia, 0, ',', '') . '</cbc:Note><!-- Nota 8 -f.Total operacion propia -->
				<cbc:Note>' . number_format($totalTerceros, 0, ',', '') . '</cbc:Note><!-- Nota 9 -g. Subtotal ingresos terceros -->
				<cbc:Note>(415)7709998342811(8020)' . $contrato . '(3900)' . $TotalCodBarras . '(96)' . $FechaLimite . '</cbc:Note><!-- Nota 10 Codigo Barras -->
				<cbc:Note>' . $contrato . '</cbc:Note><!-- Nota 11 -Contrato -->
				<cbc:Note>' . number_format($subtotalPeriodo, 0, ',', '') . '</cbc:Note><!-- Nota 12 -h. subTotal del periodo -->
				<cbc:Note>' . $fechaLimitePago . '</cbc:Note><!-- Nota 13 -Fecha vencimiento-->
				<cbc:Note>' . $nombreProducto . '</cbc:Note><!-- Nota 14 -Nombre producto -->
				<cbc:Note>VACIO</cbc:Note>
				<cbc:Note>VACIO</cbc:Note>
				<cbc:Note>VACIO</cbc:Note>
				<cbc:Note>VACIO</cbc:Note>
				<cbc:Note>VACIO</cbc:Note>
				<cbc:Note>VACIO</cbc:Note>
				<cbc:Note>VACIO</cbc:Note>
				<cbc:DocumentCurrencyCode>COP</cbc:DocumentCurrencyCode>
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
									<cbc:Line>' . $direccionEmisor . ' – Bogotá, Colombia</cbc:Line>
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
									<cbc:Line>' . $direccionEmisor . ' – Bogotá, Colombia</cbc:Line>
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
							<cbc:RegistrationName>consumidor final</cbc:RegistrationName>
							' . $companyId . '
							' . $taxtCustomer . '
                                                        <cac:RegistrationAddress>
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
									<cbc:Name languageID="es">Colombia</cbc:Name>
								</cac:Country>
							</cac:RegistrationAddress>    
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
				<!--INFORMACION OBLIGATORIA A PARTIR DEL 5 DE JUNIO DEL 2020 
				<cac:Delivery>
					<cbc:ActualDeliveryDate>' . date("Y-m-d", strtotime($fecha_actual . "+ 1 days")) . '</cbc:ActualDeliveryDate>
				</cac:Delivery>-->
				<cac:PaymentMeans>
					<cbc:ID>1</cbc:ID>
					<cbc:PaymentMeansCode>1</cbc:PaymentMeansCode>
				</cac:PaymentMeans>
				<cac:PaymentTerms>
					<cbc:Note>Contado</cbc:Note>
				</cac:PaymentTerms>
				<cac:TaxTotal>
					<cbc:TaxAmount currencyID = "COP">' . $totalIva . '</cbc:TaxAmount>
                                        <cbc:RoundingAmount currencyID="COP">0</cbc:RoundingAmount>
					' . $TaxTotalSinIva . '
					' . $TaxTotalConIva . '
				</cac:TaxTotal>
				<cac:LegalMonetaryTotal>
					<cbc:LineExtensionAmount currencyID = "COP">' . $LineExtensionAm . '</cbc:LineExtensionAmount>
					<cbc:TaxExclusiveAmount currencyID = "COP">' . $TaxExclusiveAm . '</cbc:TaxExclusiveAmount>
					<cbc:TaxInclusiveAmount currencyID = "COP">' . $TaxInclusive . '</cbc:TaxInclusiveAmount>
                                        <cbc:AllowanceTotalAmount currencyID="COP">0.00</cbc:AllowanceTotalAmount>
					<cbc:PayableAmount currencyID = "COP">' . $PayableAmount . '</cbc:PayableAmount>
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
                        <Login>MINERO' . $nitCliente . '</Login>
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

    public function salida_Transaccion($sessionId = null, $fechaVen = null, $transaccionId = null, $pk_factura_codigo = null) {
        log_info($this->logHeader . 'INGRESO LIBRERIA salida_Transaccion');
        log_info($this->postData . 'TransaccionId = ' . $transaccionId . ' PK_FACTURA_TALOS = ' . $pk_factura_codigo);

        if (!empty($sessionId) && !empty($fechaVen) && !empty($transaccionId) && !empty($pk_factura_codigo)) {
            $urlWsdl = $this->retornarValorConfiguracion($pk_factura_codigo, 'URL_WS_COMFIAR');
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
            $cuitProcesar = $this->retornarValorConfiguracion($pk_factura_codigo, 'NIT_CUITID');
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
//            Descomentar si se quiere visualizar el XML respuesta Comfiar
//                log_info($this->soapCorrecto . 'SalidaTransaccion::' . json_encode($responce_param));
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

    //Se encarga de solicitar a comfiar una respuesta sobre el estado de transaccion eviada
    public function respuesta_Comprobante($sessionId = null, $fechaVen = null, $transaccionId = null, $tipCom = null, $pk_factura = null) {

        log_info($this->logHeader . 'INGRESO LIBRERIA respuesta_Comprobante');
        log_info($this->postData . 'Pk_factura = ' . $pk_factura . ' nroCbte = ' . $transaccionId . ' TipoCbte = ' . $tipCom . 'Pk_factura = ' . $pk_factura);

        if (!empty($sessionId) && !empty($fechaVen) && !empty($transaccionId) && !empty($pk_factura)) {
            $urlWsdl = $this->retornarValorConfiguracion($pk_factura, 'URL_WS_COMFIAR');
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
            log_info($this->logHeader . ' - ' . ' URL_WS_COMFIAR: ' . $wsdl . ' CuitId: ' . $cuitProcesar . ' PuntoDeVentaId: ' . $puntoVentaId . ' TipoDeComprobanteId: ' . $tipoComprobanteId . ' NroCbte: ' . $transaccionId . ' SesionId: ' . $sessionId . ' FechaVencimiento: ' . $fechaVen);
            $nroCbte = $transaccionId;
            // web service input params
            $request_param = array(
                "cuitId" => $cuitProcesar, // Cuit, RUC o NIT del emisor del comprobante.
                "puntoDeVentaId" => $puntoVentaId, // Número de punto de venta a procesar para factura 10002
                "tipoDeComprobanteId" => $tipoComprobanteId, // tipo comprobante factura 01
                "nroCbte" => $nroCbte, // Numero/consecutivo del comprobante para el cual se realizará la consulta.
                "token" => array(
                    "SesionId" => $sessionId,
                    "FechaVencimiento" => $fechaVen
                )
            );
            try {
                $responce_param = $client->RespuestaComprobante($request_param);
//            var_dump($responce_param);
//            var_dump($responce_param->IniciarSesionResult->SesionId);
                //Descomentar si se quiere ver xml respuesta en logs
//                log_info($this->logHeader . '-' . $this->soapCorrecto . 'RespuestaComprobante:: ' . json_encode($responce_param));
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

    //Consumo SOAP Comfiar retorna pdf, tipo comprobante 01=factura  2=Nota Credito
    public function descarga_pdf($sessionId = null, $fechaVen = null, $transaccionId = null, $nroComprobante = null, $tipCom = null, $pk_factura = null) {
        log_info($this->logHeader . 'INGRESO LIBRERIA descarga_pdf');
        log_info($this->logHeader . $this->postData . 'TransaccionId = ' . $transaccionId . ' Nro comprobante = ' . $nroComprobante . ' Tipo_Compronbante = ' . $tipCom . ' Pk_Factura = ' . $pk_factura);

        $codRespuesta = 0;
        if (!empty($sessionId) && !empty($fechaVen) && !empty($transaccionId) && !empty($nroComprobante) && !empty($tipCom) && !empty($pk_factura)) {
            $urlWsdl = $this->retornarValorConfiguracion($pk_factura, 'URL_WS_COMFIAR');
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
                $prefijoMinero = $this->retornarValorConfiguracion($pk_factura, 'PREFIJO_FACTURA'); //prefijo usado para factura ej: SETT
                $folderPath = $this->retornarValorConfiguracion($pk_factura, 'URL_ALMACEN_FACTURA');
            } else if ($tipCom == 2) {
                $puntoVentaId = $this->retornarValorConfiguracion($pk_factura, 'ID_PUNTO_VENTA_NTC');
                $tipoComprobanteId = $this->retornarValorConfiguracion($pk_factura, 'ID_TIPO_COMPROBANTE_NTC');
                $prefijoMinero = $this->retornarValorConfiguracion($pk_factura, 'PREFIJO_NOTA_CREDITO');
                $folderPath = $this->retornarValorConfiguracion($pk_factura, 'URL_ALMACEN_NC');
            }
            log_info($this->logHeader . ' - DESCARGAR_PDF - ' . ' URL_WS_COMFIAR: ' . $wsdl . ' CuitId: ' . $cuitId . ' PuntoDeVentaId: ' . $puntoVentaId . ' TipoDeComprobanteId: ' . $tipoComprobanteId . ' NroCbte: ' . $nroComprobante . ' transaccionId: ' . $transaccionId);

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
                log_info($this->logHeader . '-' . $this->soapCorrecto . 'Respuesta DescargarPdf:: ' . json_encode($responce_param));
                if (isset($responce_param->DescargarPdfResult)) {
                    log_info($this->logHeader . $this->iniciLog . '::folderPath::' . $folderPath);

                    $b64 = $responce_param->DescargarPdfResult;
                    $data = base64_encode($b64);
                    $urlpublica = $this->retornarValorConfiguracion($pk_factura, 'DOMINIO');
                    //guarda y genera url factura pdf
//                    $folderPath = "uploads/facturacomfiar/";
//                    $date = date('Y-m-d');
//                    $random = rand(1000, 9999);
//                    $fact = strtolower($prefijoMinero) . '-' . $nroComprobante . '-';
//                    $name = $fact . strtolower($date . '-' . $random . '.pdf');
//                    $file_dir = $folderPath . $name;
//                    $url = $urlpublica . '/' . $folderPath . $name;
//                    $pdf_decoded = base64_decode($data); //Write data back to pdf file
//                    try {
//                        $pdf = fopen($file_dir, 'w');
//                        fwrite($pdf, $pdf_decoded);
//                        //close output file
//                        fclose($pdf);
//                        $dataReturn = $url;
////                    echo $url . '+++' . $fact;
//                    } catch (Exception $e) {
//                        $response = 'Excepción capturada: ' . $e->getMessage();
//                    }
                    //Ajuste enviar factura servidor talos-minero
//                    $url_ws_guardar_pdf ='http://192.168.10.130:80/wstalos/almacenarPdfFacturador/guardar_pdf_facturador';
//                    $url_ws_guardar_pdf ='https://qatalos.minero.com.co/almacenPdfMinero';
                    $url_ws_guardar_pdf =$folderPath;
                    $datajson= [
                        "pdf_base64" => $data,
                        "tipo_archivo" => $tipCom,
                        "prefijo_factura" => $prefijoMinero,
                        "numero_factura" => $nroComprobante
                    ];
                    $datosCodificados = json_encode($datajson);
                    $ch = curl_init($url_ws_guardar_pdf);
                    curl_setopt_array($ch, array(
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_POSTFIELDS => $datosCodificados,
                        CURLOPT_HTTPHEADER => array(
                            'Content-Type: application/json',
                            'Content-Length: ' . strlen($datosCodificados), // Abajo podríamos agregar más encabezados
                        ),
                        # indicar que regrese los datos, no que los imprima directamente
                        CURLOPT_RETURNTRANSFER => true,
                    ));
                    $resultadoGuardarPdf = curl_exec($ch);
                    $codigoRespuestaws = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                    log_info($this->logHeader . ' Json Response Ws guardar_pdf_facturador :' . $resultadoGuardarPdf);
                    if ($codigoRespuestaws === 200) {
                        # Decodificar JSON porque esa es la respuesta
                        $respuestaDecodificada = json_decode($resultadoGuardarPdf);
                        $url_pdf= $respuestaDecodificada->Url_pdf;
                        $mensaje_respuesta = $respuestaDecodificada->mensaje_respuesta;
                        $dataReturn=$url_pdf;
                        log_info($this->iniciLog . $class . $this->logHeader . ' GUARDAR PDF:::: URL_WS: ' . $url_ws_guardar_pdf . ' Mensaje respuesta: ' . $mensaje_respuesta .' URL_PDF: '.$url_pdf);
                    }
                    
                    log_info($this->logHeader . $this->soapCorrecto . '::Consumo Correcto soap DescargarPdf::URL PDF COMFIAR::' . $dataReturn);
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

    public function generarXmlNC($pk_nota_codigo = null) {
        if (!empty($pk_nota_codigo)) {
            log_info($this->iniciLog . $this->postData . 'NOTA_CREDITO generarXmlNC PK_NOTA_CODIGO= ' . $pk_nota_codigo);


            //data para tag AccountingCustomerParty información cliente
            $sqlDataNota = $this->db->query("SELECT 
                CONF.AMBIENTE_TRANSMISION,
                CONF.NIT_CUITID NIT,
                CONF.DIGITO_VERIFICACION,
                CONF.RESOLUCION,
                CONF.FECHA_INICIO_RESOL,
                CONF.FECHA_FIN_RESOL,
                CONF.RANGO_INICIO_RESOL_NC,
                CONF.RANGO_FIN_RESOL_NC,
                CONF.PREFIJO_NOTA_CREDITO PREFIJO_NC,
                CONF.CORREO_EMISOR_FACTURACION,
                CONF.DIRECCION_EMISOR,
                CONF.TELEFONO_EMISOR,
                TRIB.NOMBRE_TRIBUTO,
                TRIB.CODIGO_TRIBUTO,
                EMI.NOMBRE,
                nota.consecutivo_nota_credito NUMERO_NOTA_CREDITO,
                xmlfac.cufe_factura,
                xmlfac.issuedate,
                xmlfac.id_comprobante,
                TO_CHAR(nota.fecha_emision,'HH24:MI:SS') HORA_NOTA,
                TO_CHAR(nota.fecha_emision,'YYYY-MM-DD') FECHA_NOTA,
                NOTA.PK_CLIENTE_CODIGO,
                NOTA.SUBTOTAL_NOTA,
                FAC.NOMBRE_PRODUCTO,
                FAC.CONTRATO,
                FAC.FECHA_LIMITE_PAGO,
                NOTA.SALDO_ANTERIOR,
                NOTA.ABONO_CAPITAL,
                NOTA.INGRESOS_GRAVADOS,
                NOTA.INGRESOS_NO_GRAVADOS,
                NOTA.TOTAL_IVA,
                NOTA.TOTAL_OPE_PROPIA,
                NOTA.TOTAL_TERCEROS,
                NOTA.SUBTOTAL_PERIODO,
                NOTA.PAGO_MINIMO
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
                WHERE nota.pk_nota_codigo= $pk_nota_codigo");
            $datosConfiguracion = $sqlDataNota->result_array[0];
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
            $cufeFactura = $datosConfiguracion['CUFE_FACTURA'];
            $issuedateFactura = $datosConfiguracion['ISSUEDATE'];
            $idComprobanteFactura = $datosConfiguracion['ID_COMPROBANTE'];
            $IssueTime = $datosConfiguracion['HORA_NOTA'];
            $IssueDate = $datosConfiguracion['FECHA_NOTA'];
            $pkClienteNota = $datosConfiguracion['PK_CLIENTE_CODIGO'];
            $subTotalNota = $datosConfiguracion['SUBTOTAL_NOTA'];
            $nombreProducto = $datosConfiguracion['NOMBRE_PRODUCTO'];
            $contrato = $datosConfiguracion['CONTRATO'];
            $fechaLimitePago = $datosConfiguracion['FECHA_LIMITE_PAGO'];
            $saldoAnterior = $datosConfiguracion['SALDO_ANTERIOR'];
            $abonoCapital = $datosConfiguracion['ABONO_CAPITAL'];
            $ingresosGravados = $datosConfiguracion['INGRESOS_GRAVADOS'];
            $ingresosNoGravados = $datosConfiguracion['INGRESOS_NO_GRAVADOS'];
            $totalIva = $datosConfiguracion['TOTAL_IVA'];
            $totalOpePropia = $datosConfiguracion['TOTAL_OPE_PROPIA'];
            $totalTerceros = $datosConfiguracion['TOTAL_TERCEROS'];
            $subtotalPeriodo = $datosConfiguracion['SUBTOTAL_PERIODO'];
            $pagoMinimo = $datosConfiguracion['PAGO_MINIMO'];

            log_info($this->logHeader . $this->postData . 'ambienteTxComfiar: ' . $ambienteTxComfiar .
                    ' NitEmisor: ' . $nitEmisor .
                    ' DigVerEmisor: ' . $digVerEmisor .
                    ' Resolucion: ' . $resolucion .
                    ' FechaInicioResol: ' . $fechaInicioResol .
                    ' FechaFinResol: ' . $fechaFinResol .
                    ' InicioRangoResol: ' . $inicioRangoResol .
                    ' FinRangoResol: ' . $finRangoResol .
                    ' PrefijoFac: ' . $prefijoNC .
                    ' CorreoEmisor: ' . $correoEmisor .
                    ' DireccionEmisor: ' . $direccionEmisor .
                    ' TelefonoEmisor: ' . $telefonoEmisor .
                    ' NameTaxScheme: ' . $nameTaxScheme .
                    ' IdTaxScheme: ' . $idTaxScheme .
                    ' EmisorRegistrationName: ' . $emisorRegistrationName .
                    ' NumeroFactura: ' . $numeroNota .
                    ' PkCliente: ' . $pkClienteNota .
                    ' PagoMinimoNota: ' . $pagoMinimo
            );


            // Datos Cliente
            $SqlClienteNota = $this->db->query("SELECT
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
                CLIE.TELEFONO,
                pais.codigo_isis,
                pais.CODIGO_ALFA_2
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
                Where clie.pk_cliente_codigo=$pkClienteNota");
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
            $clienteCodDep = substr($daneCliente, 0, 2);  //CountrySubentityCode
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
            $cod_alfa_pais_cli = $ClienteNota['CODIGO_ALFA_2'];
            $codigo_isis = $ClienteNota ['CODIGO_ISIS'];


            log_info($this->logHeader . $this->postData . 'NombreCliente: ' . $nombreCliente .
                    ' PrimerNombreCliente: ' . $primerNombreCliente .
                    ' SegundoNombreCliente: ' . $segundoNombreCliente .
                    ' ApellidosCliente: ' . $apellidosCliente .
                    ' TipoPersona: ' . $tipoPersona .
                    ' NitCliente: ' . $nitCliente .
                    ' ClienteDV: ' . $clienteDV .
                    ' ClienteSchemeName: ' . $clienteSchemeName .
                    ' EmailCliente: ' . $emailCliente .
                    ' DaneCliente: ' . $daneCliente .
                    ' ClienteCodDep: ' . $clienteCodDep .
                    ' Pais: ' . $pais .
                    ' CiudadCliente: ' . $ciudadCliente .
                    ' PepartamentoCliente: ' . $departamentoCliente .
                    ' CodigoPostal: ' . $codigoPostal .
                    ' Direccion_cliente: ' . $direccion_cliente .
                    ' TaxLevelCode: ' . $taxLevelCode .
                    ' taxLevelCodeListName: ' . $taxLevelCodeListName .
                    ' TaxShemeName: ' . $taxShemeName .
                    ' TaxShemeID: ' . $taxShemeID .
                    ' TelefonoCliente: ' . $telefono_cliente .
                    ' CodigoAlfaPais: ' . $cod_alfa_pais_cli .
                    ' CodigoIsis: ' . $codigo_isis
            );


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
                $companyId = '<cbc:CompanyID  schemeName = "' . $clienteSchemeName . '" schemeAgencyID = "195" schemeAgencyName = "CO, DIAN (Dirección de Impuestos y Aduanas Nacionales)">' . $nitCliente . '</cbc:CompanyID>';
            }



            $SqlDetalleNota = $this->db->query("SELECT prod.CODIGO_PRODUCTO,
                    detnota.detalle NOMBRE_PRODUCTO,
                    detnota.CANTIDAD,
                    detnota.VALOR_UNITARIO,
                    detnota.PORCENTAJE_IVA,
                    detnota.TOTAL_IVA,
                    detnota.valor_total TOTAL,
                    detnota.VALOR_OBSEQUIO
                    FROM modfacturador.facturtbldetallenotacredito detnota
                    JOIN modfacturador.facturtblproductos prod
                    ON detnota.pk_producto_codigo = prod.pk_producto_codigo
                    WHERE detnota.pk_nota_codigo =$pk_nota_codigo");
            $detalleNota = $SqlDetalleNota->result_array;


            $idProduc = 1;
            $cantNote = 0;
            $Total_Monto_Sin_Iva = 0;
            $Total_Monto_Con_Iva = 0;
            $Total_Gravado = 0;
            $porc_iva_TaxScheme = 0;

            foreach ($detalleNota as $value) {
                if ($value['VALOR_UNITARIO'] == 0) {
                    $PricingReference = '<cac:PricingReference>
                                            <cac:AlternativeConditionPrice>
                                            <!--Falta traer valor producto  -->
                                                <cbc:PriceAmount currencyID="COP">' . $value['VALOR_OBSEQUIO'] . '</cbc:PriceAmount>
                                                <cbc:PriceTypeCode>01</cbc:PriceTypeCode> 
                                            </cac:AlternativeConditionPrice>
                                          </cac:PricingReference>';
                } else {
                    $PricingReference = '';
                }
                $lineExte = $value['TOTAL'];
                $valIva = $value['TOTAL_IVA'];
                $porcIva = $value['PORCENTAJE_IVA'];
                $creditNotLine = $creditNotLine . '<cac:CreditNoteLine>
                    <cbc:ID>' . $idProduc++ . '</cbc:ID>
                    <cbc:UUID schemeAgencyID="195" schemeAgencyName="CO, DIAN (Dirección de Impuestos y Aduanas Nacionales)">' . $cufeFactura . '</cbc:UUID>
                    <cbc:CreditedQuantity unitCode="94">' . $value['CANTIDAD'] . '</cbc:CreditedQuantity>
                    <cbc:LineExtensionAmount currencyID="COP">' . $lineExte . '</cbc:LineExtensionAmount>
                    ' . $PricingReference . '
                    <cac:TaxTotal>
                        <cbc:TaxAmount currencyID="COP">' . $valIva . '</cbc:TaxAmount>
                        <cbc:RoundingAmount currencyID="COP">0</cbc:RoundingAmount><!--validar ya que se trata de enviar 0.50 y quitarlo al taxAmount pero genera error cufe-->
                        <cac:TaxSubtotal>
                            <cbc:TaxableAmount currencyID="COP">' . $lineExte . '</cbc:TaxableAmount>
                            <cbc:TaxAmount currencyID="COP">' . $valIva . '</cbc:TaxAmount>
                            <cac:TaxCategory>
                                <cbc:Percent>' . $porcIva . '.00</cbc:Percent>
                                <cac:TaxScheme>
                                    <cbc:ID>01</cbc:ID>
                                    <cbc:Name>IVA</cbc:Name>
                                </cac:TaxScheme>
                            </cac:TaxCategory>
                        </cac:TaxSubtotal>
                    </cac:TaxTotal>
                    <cac:Item>
                        <cbc:Description>' . $value['NOMBRE_PRODUCTO'] . '</cbc:Description>
                        <cac:SellersItemIdentification>
                            <cbc:ID>' . $value['CODIGO_PRODUCTO'] . '</cbc:ID>
                        </cac:SellersItemIdentification>
                    </cac:Item>
                    <cac:Price>
                        <cbc:PriceAmount currencyID="COP">' . $value['VALOR_UNITARIO'] . '</cbc:PriceAmount>
                        <cbc:BaseQuantity unitCode="94">' . $value['CANTIDAD'] . '</cbc:BaseQuantity>
                        <cbc:PriceTypeCode>COP</cbc:PriceTypeCode>
                    </cac:Price>
                </cac:CreditNoteLine>';
                if ($porcIva == 19) {
                    $Total_Gravado += $valIva;
                    $Total_Monto_Con_Iva += $lineExte;
                    $porc_iva_TaxScheme = $porcIva;
                } elseif ($porcIva == 0) {
                    $Total_Monto_Sin_Iva += $lineExte;
                }

                $cantNote++;
            }


            $TaxTotalConIva = '';
            if ($Total_Gravado != 0) {
                $TaxTotalConIva = '<cac:TaxSubtotal>
                            <cbc:TaxableAmount currencyID="COP">' . $Total_Monto_Con_Iva . '</cbc:TaxableAmount>
                            <cbc:TaxAmount currencyID="COP">' . $Total_Gravado . '</cbc:TaxAmount>
                            <cac:TaxCategory>
                                <cbc:Percent>' . $porc_iva_TaxScheme . '.00</cbc:Percent>
                                <cac:TaxScheme>
                                    <cbc:ID>01</cbc:ID>
                                    <cbc:Name>IVA</cbc:Name>
                                </cac:TaxScheme>
                            </cac:TaxCategory>
                        </cac:TaxSubtotal>';
            }

            $TaxTotalSinIva = '';
            if ($Total_Monto_Sin_Iva != 0) {
                $TaxTotalSinIva = '<cac:TaxSubtotal>
                            <cbc:TaxableAmount currencyID="COP">' . $Total_Monto_Sin_Iva . '</cbc:TaxableAmount>
                            <cbc:TaxAmount currencyID="COP">0</cbc:TaxAmount>
                            <cac:TaxCategory>
                                <cbc:Percent>0.00</cbc:Percent>
                                <cac:TaxScheme>
                                    <cbc:ID>01</cbc:ID>
                                    <cbc:Name>IVA</cbc:Name>
                                </cac:TaxScheme>
                            </cac:TaxCategory>
                        </cac:TaxSubtotal>';
            }
        }


        $FechaLimite = date("Ymd", strtotime($fechaLimitePago));
        $fechaLimitePago = date("Y-m-d", strtotime($fechaLimitePago));
        $PayableAmount = $pagoMinimo;
        $TotalCodBarras = round($PayableAmount); //explode(".", round($PayableAmount));//trim(trim($PayableAmount,0), '.');
        $TotalCodBarras = str_pad($TotalCodBarras, 10, "0", STR_PAD_LEFT);
        $LineExtensionAm = $subTotalNota;
        $TaxExclusiveAm = $subTotalNota;
        $TaxInclusive = $subTotalNota + $totalIva;
//        $PayableAmount = $pagoMinimo;


        $dom = new DOMDocument;
        $dom->preserveWhiteSpace = false;
        $dom->loadXML('<?xml version = "1.0" encoding = "utf-8"
        ?>
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
                                    <sts:SoftwareID schemeAgencyID="195" schemeAgencyName="CO, DIAN (Dirección de Impuestos y Aduanas Nacionales)" />
                                </sts:SoftwareProvider>
                                <sts:SoftwareSecurityCode schemeAgencyID="195" schemeAgencyName="CO, DIAN (Dirección de Impuestos y Aduanas Nacionales)" />
                            </sts:DianExtensions>
                        </ext:ExtensionContent>
                    </ext:UBLExtension>
                </ext:UBLExtensions>
                <cbc:UBLVersionID>UBL 2.1</cbc:UBLVersionID>
                <cbc:CustomizationID>20</cbc:CustomizationID>
                <cbc:ProfileID>DIAN 2.1: Nota Crédito de Factura Electrónica de Venta</cbc:ProfileID>
                <cbc:ProfileExecutionID>' . $ambienteTxComfiar . '</cbc:ProfileExecutionID>
                <cbc:ID>' . $prefijoNC . $numeroNota . '</cbc:ID>
                <cbc:UUID schemeID="' . $ambienteTxComfiar . '" schemeName="CUDE-SHA384" />
                <cbc:IssueDate>' . $IssueDate . '</cbc:IssueDate>
                <cbc:IssueTime>' . $IssueTime . '-05:00' . '</cbc:IssueTime>
                <cbc:CreditNoteTypeCode>91</cbc:CreditNoteTypeCode>
                <cbc:Note>' . number_format($saldoAnterior, 0, ',', '') . '</cbc:Note><!-- Nota 1 total a pagar - a. Saldo Anterior-->
                <cbc:Note>' . number_format($abonoCapital, 0, ',', '') . '</cbc:Note><!--Nota 2  - b.Abonos a capital-->
                <cbc:Note>' . number_format($ingresosGravados, 0, ',', '') . '</cbc:Note><!-- Nota 3 - c.Ingresos gravados-->
                <cbc:Note>Régimen: Impuestos sobre las ventas - IVA
Persona Jurídica. Actividad económica 8220 Tarifa ICA 9.66x1.000. No Somos Grandes Contribuyentes. No Somos Autorretenedores. Resolución gráfica de la factura electrónica según parágrafo 1 articulo 3 decreto 2242 de 2015.</cbc:Note>
                <cbc:Note>' . number_format($ingresosNoGravados, 0, ',', '') . '</cbc:Note><!-- Nota 5 - d.Ingresos no gravados  -->
                <cbc:Note>' . number_format($totalOpePropia, 0, ',', '') . '</cbc:Note><!-- Nota 8 -f.Total operacion propia -->
                <cbc:Note>' . number_format($totalTerceros, 0, ',', '') . '</cbc:Note><!-- Nota 9 -g. Subtotal ingresos terceros -->
                <cbc:Note>' . number_format($totalTerceros, 0, ',', '') . '</cbc:Note><!-- Nota 8 Rete IVA esperar que actualicen rg-->
                <cbc:Note>' . number_format($totalTerceros, 0, ',', '') . '</cbc:Note><!-- Nota 8 Rete IVA esperar que actualicen rg-->
                <cbc:Note>(415)7709998342811(8020)' . $contrato . '(3900)' . $TotalCodBarras . '(96)' . $FechaLimite . '</cbc:Note><!-- Nota 8 Rete IVA-->
                <cbc:Note>' . $contrato . '</cbc:Note><!-- Nota 8 Rete IVA-->
                <cbc:Note>VACIO</cbc:Note>
		<cbc:Note>VACIO</cbc:Note>
		<cbc:Note>VACIO</cbc:Note>
		<cbc:Note>VACIO</cbc:Note>
                <cbc:DocumentCurrencyCode>COP</cbc:DocumentCurrencyCode>
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
                                <cbc:CountrySubentity>CUNDINAMARCA</cbc:CountrySubentity>
                                <cbc:CountrySubentityCode>11</cbc:CountrySubentityCode>
                                <cac:AddressLine>
                                    <cbc:Line>' . $direccionEmisor . ' – Bogotá, Colombia</cbc:Line>
                                </cac:AddressLine>
                                <cac:Country>
                                    <cbc:IdentificationCode>CO</cbc:IdentificationCode>
                                    <cbc:Name languageID="es">Colombia</cbc:Name>
                                </cac:Country>
                            </cac:Address>
                        </cac:PhysicalLocation>
                        <cac:PartyTaxScheme>
                            <cbc:RegistrationName>' . $emisorRegistrationName . '</cbc:RegistrationName>
                            <cbc:CompanyID schemeID="' . $digVerEmisor . '" schemeName="31" schemeAgencyID="195" schemeAgencyName="CO, DIAN (Dirección de Impuestos y Aduanas Nacionales)">' . $nitEmisor . '</cbc:CompanyID>
                            <!--<cbc:TaxLevelCode listName="No aplica">O-15</cbc:TaxLevelCode> Remplazar cuando la DIAN ya tenga ajuste en PROD -->
                            <cbc:TaxLevelCode listName="48">O-23</cbc:TaxLevelCode>
                            <cac:RegistrationAddress>
                                <cbc:ID>11001</cbc:ID>
                                <cbc:CityName>BOGOTA</cbc:CityName>
                                <cbc:PostalZone>110111</cbc:PostalZone>
                                <cbc:CountrySubentity>CUNDINAMARCA</cbc:CountrySubentity>
                                <cbc:CountrySubentityCode>11</cbc:CountrySubentityCode>
                                <cac:AddressLine>
                                    <cbc:Line>' . $direccionEmisor . ' – Bogotá, Colombia</cbc:Line>
                                </cac:AddressLine>
                                <cac:Country>
                                    <cbc:IdentificationCode>CO</cbc:IdentificationCode>
                                    <cbc:Name languageID="es">Colombia</cbc:Name>
                                </cac:Country>
                            </cac:RegistrationAddress>
                            <cac:TaxScheme>
                                <cbc:ID>01</cbc:ID>
                                <cbc:Name>IVA</cbc:Name>
                            </cac:TaxScheme>
                        </cac:PartyTaxScheme>
                        <cac:PartyLegalEntity>
                            <cbc:RegistrationName>' . $emisorRegistrationName . '</cbc:RegistrationName>
                            <cbc:CompanyID schemeID="' . $digVerEmisor . '" schemeName="31" schemeAgencyID="195" schemeAgencyName="CO, DIAN (Dirección de Impuestos y Aduanas Nacionales)">' . $nitEmisor . '</cbc:CompanyID>
                            <cac:CorporateRegistrationScheme>
                                <cbc:ID>' . $prefijoNC . '</cbc:ID>
                            </cac:CorporateRegistrationScheme>
                        </cac:PartyLegalEntity>
                        <cac:Contact>
                            <cbc:Telephone>' . $telefonoEmisor . '</cbc:Telephone>
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
                                    <cbc:IdentificationCode>' . $cod_alfa_pais_cli . '</cbc:IdentificationCode>
                                    <cbc:Name languageID="es">' . $pais . '</cbc:Name>
                                </cac:Country>
                            </cac:Address>
                        </cac:PhysicalLocation>
                        <cac:PartyTaxScheme>
                            <cbc:RegistrationName>' . $nombreCliente . '</cbc:RegistrationName>
                           ' . $companyId . '
			   ' . $taxtCustomer . '
                        <cac:RegistrationAddress>
				<cbc:ID>' . $daneCliente . '</cbc:ID>
				<cbc:CityName>' . $ciudadCliente . '</cbc:CityName>
				<cbc:PostalZone>' . $codigoPostal . '</cbc:PostalZone>
				<cbc:CountrySubentity>' . $departamentoCliente . '</cbc:CountrySubentity>
				<cbc:CountrySubentityCode>' . $clienteCodDep . '</cbc:CountrySubentityCode>
				<cac:AddressLine>
                                    <cbc:Line>' . $direccion_cliente . '</cbc:Line>
				</cac:AddressLine>
				<cac:Country>
                                    <cbc:IdentificationCode>' . $cod_alfa_pais_cli . '</cbc:IdentificationCode>
                                    <cbc:Name languageID="es">' . $pais . '</cbc:Name>
				</cac:Country>
			</cac:RegistrationAddress>
                            <cac:TaxScheme>
                                <cbc:ID>01</cbc:ID>
                                <cbc:Name>IVA</cbc:Name>
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
                <cac:PaymentMeans>
                    <cbc:ID>1</cbc:ID>
                    <cbc:PaymentMeansCode>1</cbc:PaymentMeansCode>
		</cac:PaymentMeans>
                <cac:TaxTotal>
			<cbc:TaxAmount currencyID = "COP">' . $totalIva . '</cbc:TaxAmount>
                        <cbc:RoundingAmount currencyID="COP">0</cbc:RoundingAmount><!--validar ya que se trata de enviar 0.50 y quitarlo al taxAmount pero genera error cufe-->
			' . $TaxTotalSinIva . '
			' . $TaxTotalConIva . '
		</cac:TaxTotal>
                <cac:LegalMonetaryTotal>
                    <cbc:LineExtensionAmount currencyID="COP">' . $LineExtensionAm . '</cbc:LineExtensionAmount>
                    <cbc:TaxExclusiveAmount currencyID="COP">' . $TaxExclusiveAm . '</cbc:TaxExclusiveAmount>
                    <cbc:TaxInclusiveAmount currencyID="COP">' . $TaxInclusive . '</cbc:TaxInclusiveAmount>
                    <cbc:AllowanceTotalAmount currencyID="COP">0.00</cbc:AllowanceTotalAmount>
                    <cbc:PayableAmount currencyID="COP">' . $PayableAmount . '</cbc:PayableAmount>
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
                <Login>MINERO' . $nitCliente . '</Login>
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

//Funcion consulta tabla modfactur.factblconfcomfiar, Retorna valor parametro para cada pk_conf_codigo
    public function retornarValorConfiguracion($pk_factura, $parametro) {
        log_info($this->logHeader . $this->postData . 'ENTRA retornarValorConfiguracion PK_FACTURA= ' . $pk_factura . ' PARAMETRO= ' . $parametro);
        if (!empty($pk_factura)) {
            if ($parametro == 'CONTRASENA_COMFIAR') {
                $sqlConsulta = $this->db->query("select MODFACTURADOR.PKGMODFACTURADORTALOS.fncconsultarcontrasenacomfiar(pk_factura=>$pk_factura) CONTRASENA
						from dual");
                $valorParametro = $sqlConsulta->result_array[0];
                $ValorParametroReturn = $valorParametro['CONTRASENA'];
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

}
