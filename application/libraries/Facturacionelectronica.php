<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class FacturacionElectronica extends CI_Controller {

    public $iniciLog = '[INFO] ';
    public $logHeader = 'APOLOINFO::::::::: ';
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

    public function iniciar_sesion() {

        log_info($this->logHeader . 'INGRESO LIBRERIA iniciar_sesion');
        $usuarioComfiar = $this->retornarValorConfiguracion(16);
        $passComfiar = $this->retornarValorConfiguracion(17);

        //********Codigo iniciar sesion comfiar */
        if (!empty($usuarioComfiar) && !empty($passComfiar)) {
            $urlWsdl = $this->retornarValorConfiguracion(30);
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
                log_info($this->soapCorrecto . 'IniciarSesion::IniciarSesion::' . json_encode($responce_param));
                $response = $responce_param;
            } catch (Exception $e) {
//                echo "<h2>Exception Error!</h2>";
//                echo $e->getMessage();
                log_info($this->errorFuncion . 'ERROR SOAP ::IniciarSesion::' . $e->getMessage());
                $response = 'Error consumo iniciosesion :' . $e->getMessage();
            }
        } else {
            log_info($this->errorFuncion . '::IniciarSesion:: USUARIO o CONTRASEÑA NULOS.');
            $response = 'Datos incorrectos.';
        }


        return $response;
    }

    public function autorizador($param) {
        
    }

    public function autorizar_Comprobante($sessionId = null, $fechaVen = null, $idFactura = null) {

        log_info($this->logHeader . 'INGRESO LIBRERIA autorizar_Comprobante');
        log_info($this->postData . 'ID_FACTURA' . $idFactura);
        //********Codigo autorizar comprobante*/
        if (!empty($sessionId) && !empty($fechaVen) && !empty($idFactura)) {
            $urlWsdl = $this->retornarValorConfiguracion(30);
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
            $xml = $this->generarXML($idFactura);
            //Se valida que la estructura del xml sea correcta de lo contrario se actualiza factura a erronea
            if ($xml != 404) {
                $client = new SoapClient($wsdl, $options);
                $cuitProcesar = $this->retornarValorConfiguracion(3);
                $puntoVentaId = $this->retornarValorConfiguracion(5); //$this->puntoVentaIdFAC;
                $formatoId = $this->retornarValorConfiguracion(4); // $this->formatoId;
                $tipoComprobanteId = $this->retornarValorConfiguracion(6); //$this->puntoVentaIdFAC;
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

    //cambiar por una sola funcion
    public function autorizar_ComprobanteNC($sessionId = null, $fechaVen = null, $idNC = null) {

        log_info($this->logHeader . 'INGRESO LIBRERIA autorizar_ComprobanteNC');
        log_info($this->postData . 'ID_NOTA_CODIGO ' . $idNC);
        //********Codigo autorizar comprobante*/
        if (!empty($sessionId) && !empty($fechaVen) && !empty($idNC)) {
//            $wsdl = "http://test.comfiar.co/ws/WSComfiar.asmx?wsdl";
            $urlWsdl = $this->retornarValorConfiguracion(30);
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
            $xml = $this->generarXmlNC($idNC);
            $client = new SoapClient($wsdl, $options);
            $cuitProcesar = $this->retornarValorConfiguracion(3);
            $puntoVentaId = $this->retornarValorConfiguracion(7); //nota credito
            $formatoId = $this->retornarValorConfiguracion(4);
            $tipoComprobanteId = $this->retornarValorConfiguracion(8); //nota credito
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
                log_info($this->logHeader . $this->soapCorrecto . ' AutorizarComprobantesAsincronico::');
                $response = $responce_param;
            } catch (Exception $e) {
//                echo "<h2>Exception Error!</h2>";
//                echo $e->getMessage();
                log_info($this->errorFuncion . 'ERROR SOAP::' . $e->getMessage());
                $response = 'Error consumo AutorizarComprobantesAsincronicoNC :' . $e->getMessage();
            }
        } else {
            log_info($this->errorFuncion . 'SESSIONID , FECHAVEN , IDFACT DATOS NULOS.');
            $response = 'Datos incorrectos.';
        }
        return $response;
    }

    public function generarXML($idfactura = null) {

        if (!empty($idfactura)) {

            //busca direccion peoplepass
            $sqldirPeople = $this->db->query("select VALOR_PARAMETRO from modgeneri.gentblpargen where PK_PARGEN_CODIGO = 59");
            $direccion = $sqldirPeople->result_array[0];
            $direccionPeople = $direccion['VALOR_PARAMETRO'];
            $sqltelPeople = $this->db->query("select VALOR_PARAMETRO from modgeneri.gentblpargen where PK_PARGEN_CODIGO = 50");
            $telefonoEmisor = $sqltelPeople->result_array[0]['VALOR_PARAMETRO'];


            //data para tag AccountingCustomerParty información cliente

            $ClienteFactura = $this->db->query("
		select UPPER(FAC.CLIENTE_PRIMER_NOMBRE||' '||FAC.CLIENTE_SEGUNDO_NOMBRE||' '||FAC.CLIENTE_APELLIDO) CLIENTE
                ,FAC.CLIENTE_PRIMER_NOMBRE 
                ,FAC.CLIENTE_SEGUNDO_NOMBRE
                ,FAC.CLIENTE_APELLIDO
		,FAC.CLIENTE_NUMERO_DOCUMENTO NIT
                ,FAC.CLIENTE_TIPO_DOCUMENTO
		,REPLACE (FAC.CLIENTE_DIRECCION,'|',' ') DIRECCION
		,NVL(FAC.CLIENTE_CIUDAD,'BOGOTA') CIUDAD
                ,NVL(FAC.CLIENTE_DEPARTAMENTO,'BOGOTA, D.C') DEPARTAMENTO
                ,FAC.CLIENTE_TIPO_PERSONA
		,FAC.CLIENTE_TELEFONO TELEFONO
                ,FAC.CLIENTE_EMAIL
		,TO_CHAR(FAC.FECHA_CREACION,'HH24:MI:SS') HORA_FACTURA
                ,TO_CHAR(FAC.FECHA_CREACION,'YYYY-MM-DD') FECHA_FACTURA
		,FAC.NUMERO_FACTURA
		,FAC.NUMERO_RESOLUCION
		,FAC.MESES_VIGENCIA_RESOLUCION
		,FAC.RANGO_MINIMO_FACTURACION
		,FAC.RANGO_MAXIMO_FACTURACION
		,FAC.FECHA_RESOLUCION
                ,FAC.PK_ENT_CODIGO
                ,NVL(FAC.OBSER_FACTURA,' ') OBSER_FACTURA
                ,SUBSTR(NVL(CIU.CODIGO_DANE,11001), 1,2) CountrySubentityCode
                ,NVL(CIU.CODIGO_DANE,11001) AddressID,
                pais.codigo_isis,
                pais.nombre pais,
                pais.CODIGO_ALFA_2
                    FROM MODFACTUR.FACTBLFACTUR FAC
                    JOIN MODCLIUNI.CLITBLENTIDA ENT
                    ON FAC.PK_ENT_CODIGO = ENT.PK_ENT_CODIGO
                    JOIN MODCLIUNI.CLITBLCIUDAD CIU
                    ON ENT.CLITBLCIUDAD_PK_CIU_CODIGO = CIU.PK_CIU_CODIGO
                    JOIN MODCLIUNI.CLITBLDEPPAI dep 
                    ON dep.PK_DEP_CODIGO = CIU.CLITBLDEPPAI_PK_DEP_CODIGO
                    JOIN MODCLIUNI.CLITBLPAIS pais 
                    ON pais.PK_PAIS_CODIGO = dep.CLITBLPAIS_PK_PAIS_CODIGO
                WHERE PK_FACTUR_CODIGO= $idfactura");
            $ClienteFactura = $ClienteFactura->result_array[0];
            $pk_entidad = $ClienteFactura['PK_ENT_CODIGO'];
            $nombre_cliente = str_replace("&", "&amp;", $ClienteFactura['CLIENTE']);
//            $nombre_cliente = str_replace("&", "&amp;", $ClienteFactura['CLIENTE']);

            $nombre_pais = $ClienteFactura['PAIS'];
            $codigo_isis = $ClienteFactura['CODIGO_ISIS'];
            $hora_factura =$ClienteFactura['HORA_FACTURA'];
            $fecha_factura =$ClienteFactura['FECHA_FACTURA'];
            
            $cod_alfa_pais_cli = $ClienteFactura['CODIGO_ALFA_2'];
            $CountrySubentityCode = $ClienteFactura['COUNTRYSUBENTITYCODE'];
            $Address_ID = $ClienteFactura['ADDRESSID'];
            $nro_factura = $ClienteFactura['NUMERO_FACTURA'];
            $tipo_persona = $ClienteFactura['CLIENTE_TIPO_PERSONA'];
            $nit_cliente = $ClienteFactura['NIT'];
            $tipoDoc_Cliente = $ClienteFactura['CLIENTE_TIPO_DOCUMENTO'];
            $email_cliente = $ClienteFactura['CLIENTE_EMAIL'];
            $direccion_cliente = $ClienteFactura['DIRECCION'];
            $ciudad_cliente = $ClienteFactura['CIUDAD'];
            $departamento = $ClienteFactura['DEPARTAMENTO'];
            $telefono_cliente = $ClienteFactura['TELEFONO'];
//            $primer_nombre_cliente = $ClienteFactura['CLIENTE_PRIMER_NOMBRE'];
            $observacion_factura = str_replace("&", "&amp;", $ClienteFactura['OBSER_FACTURA']);
            $primer_nombre_cliente = str_replace("&", "&amp;", $ClienteFactura['CLIENTE_PRIMER_NOMBRE']);
            $segundo_nombre_cliente = $ClienteFactura['CLIENTE_SEGUNDO_NOMBRE'];
            $apellido_cliente = $ClienteFactura['CLIENTE_APELLIDO'];

            log_info($this->logHeader . $this->postData . 'Pk_entidad: ' . $pk_entidad .
                    ' Nombre_Cliente: ' . $nombre_cliente .
                    ' Primer_Nombre_Cliente: ' . $primer_nombre_cliente .
                    ' $segundo_nombre_cliente: ' . $segundo_nombre_cliente .
                    ' No_factura: ' . $nro_factura .
                    ' Tipo_persona: ' . $tipo_persona .
                    ' Nit_cliente: ' . $nit_cliente .
                    ' Tipo_documento: ' . $tipoDoc_Cliente .
                    ' Email_cliente: ' . $email_cliente .
                    ' Direccion_cliente: ' . $direccion_cliente .
                    ' Ciudad_Cliente: ' . $ciudad_cliente .
                    ' Telefono_cliente: ' . $telefono_cliente);
            //datos conf COMFIAR
            $resolPeoople = $this->retornarValorConfiguracion(19);
            $inicioResol = $this->retornarValorConfiguracion(20);
            $finResol = $this->retornarValorConfiguracion(21);
            $rangoInicio = $this->retornarValorConfiguracion(22);
            $rangoFin = $this->retornarValorConfiguracion(23);
            $PrefijoPeople = $this->retornarValorConfiguracion(24);
            $CorreoUsuarioComfiar = $this->retornarValorConfiguracion(26);
            $correoClienteFacturacion = empty($email_cliente) ? $CorreoUsuarioComfiar : $email_cliente;
            //cambiar a 2 en produiccion
            $ambienteComfiar = $this->retornarValorConfiguracion(1); //$this->ambienteComfiar;
            $porcenIva = $this->retornarValorConfiguracion(15); //$this->porcIva;
            $schemeNameCliente = 0;
            if ($tipoDoc_Cliente == 'NIT JURIDICO') {
                $schemeNameCliente = 31;
            } else if ($tipoDoc_Cliente == 'TARJETA IDENTIDAD') {
                $schemeNameCliente = 12;
            } else if ($tipoDoc_Cliente == 'CEDULA DE CIUDADANIA') {
                $schemeNameCliente = 13;
            } else if ($tipoDoc_Cliente == 'PASAPORTE') {
                $schemeNameCliente = 41;
            } else if ($tipoDoc_Cliente == 'CEDULA EXTRANJERIA') {
                $schemeNameCliente = 22;
            } else if ($tipoDoc_Cliente == 'NIT NATURAL') {
                $schemeNameCliente = 31;
            } else if ($tipoDoc_Cliente == 'REGISTRO CIVIL') {
                $schemeNameCliente = 11;
            }
            //OJO para pais del extranjero se debe colocar el tipo documento 42 documento de identificacion extranjero
            //como no se tiene en nuestro sistema lo que se hace es que si el codigo isis del pasi es diferente a 170 
            //se cambia este tipo documento y adicional el tag InvoiceTypeCode se modifica a 02 factura electronica de venta-exportación
            //se envian vacios los campos $CountrySubentityCode $Address_ID, com es una factura fuera del pais estos campos no se validan 
            // 07/05/2021 
            $BrandName = '';
            if ($codigo_isis != '170') {
                $schemeNameCliente = 42;
                $InvoiceTypeCode = '01'; // anteriormente Yeferson dijo que enviaramos 02 factura de exportacion para error en validacion de RegistrationAddress y Address, se actualiza 27 julio 2021 a 01 y aque enviando cbc:IdentificationCode <> CO no valida items 
                $BrandName = '<cbc:BrandName>PeplePass</cbc:BrandName>';
                $CountrySubentityCode = '';
                $Address_ID = '';
            } else {
                $InvoiceTypeCode = '01';
            }
            //traer digito verificacion cliente
            $sqlDigitoVer = $this->db->query(" SELECT NVL(ent.digito_verificacion,0) DIGITO_VERIFICACION FROM
            modcliuni.clitblentida ent
            WHERE ent.pk_ent_codigo = $pk_entidad");

            $DigitoVer = $sqlDigitoVer->result_array[0]['DIGITO_VERIFICACION'];

            $AddicionalAc = '';
            $taxtCustomer = '';
            if ($tipo_persona == 'NATURAL') {
                $AccountID = 2;
                $Person = '<cac:Person>
				<cbc:ID>' . $nit_cliente . '</cbc:ID>
				<cbc:FirstName>' . $primer_nombre_cliente . '</cbc:FirstName>
				<cbc:FamilyName>' . $apellido_cliente . '</cbc:FamilyName>
				<cbc:MiddleName>' . $segundo_nombre_cliente . '</cbc:MiddleName>
                            </cac:Person>';
                $AddicionalAc = '<cac:PartyIdentification>
                            <cbc:ID schemeName="13" schemeAgencyID="195"  schemeAgencyName="CO, DIAN (Dirección de Impuestos y Aduanas Nacionales)">' . $nit_cliente . '</cbc:ID>
                        </cac:PartyIdentification>';
//                $taxtCustomer = '<cbc:TaxLevelCode>R-99-PN</cbc:TaxLevelCode>';
                $taxtCustomer = '<cbc:TaxLevelCode listName = "49">R-99-PN</cbc:TaxLevelCode>';
            } else if ($tipo_persona == 'JURIDICA') {
                $AccountID = 1;
                $Person = '';
//                $taxtCustomer = '<cbc:TaxLevelCode>O-47</cbc:TaxLevelCode>';
                $taxtCustomer = '<cbc:TaxLevelCode listName = "48">O-47</cbc:TaxLevelCode>';
            } else {
                $AccountID = 1;
                $Person = '';
//                $taxtCustomer = '<cbc:TaxLevelCode>R-99-PN</cbc:TaxLevelCode>';
                $taxtCustomer = '<cbc:TaxLevelCode listName = "49">R-99-PN</cbc:TaxLevelCode>';
            }
            $companyId = '';
            $PartyIdenTerceros = '';
            if ($schemeNameCliente == 31 || $schemeNameCliente == 42) {
                $companyId = '<cbc:CompanyID schemeID = "' . $DigitoVer . '" schemeName = "' . $schemeNameCliente . '" schemeAgencyID = "195" schemeAgencyName = "CO, DIAN (Dirección de Impuestos y Aduanas Nacionales)">' . $nit_cliente . '</cbc:CompanyID>';
                $PartyIdenTerceros = '<cbc:ID schemeID="' . $DigitoVer . '" schemeName="' . $schemeNameCliente . '" schemeAgencyID="195" schemeAgencyName="CO, DIAN (Dirección de Impuestos y Aduanas Nacionales)">' . $nit_cliente . '</cbc:ID>';
            } else {
                $companyId = '<cbc:CompanyID  schemeName = "13" schemeAgencyID = "195" schemeAgencyName = "CO, DIAN (Dirección de Impuestos y Aduanas Nacionales)">' . $nit_cliente . '</cbc:CompanyID>';
                $PartyIdenTerceros = '<cbc:ID schemeID="' . $DigitoVer . '" schemeName="' . $schemeNameCliente . '" schemeAgencyID="195" schemeAgencyName="CO, DIAN (Dirección de Impuestos y Aduanas Nacionales)">' . $nit_cliente . '</cbc:ID>';
            }

            $abonos = $this->db->query("select '1' CANTIDAD,
				'Abono a tarjetas de Producto '||LOWER(producto.nombre_producto) PRODUCTO,
                                detpab.pk_producto pk_produc_codigo,
				sum(detpab.monto) VALOR from
                                MODFACTUR.factblfacord facord
                                JOIN MODPROPAG.ppatblordcom ordcom
                                ON facord.pk_ordcom_codigo=ordcom.pk_ordcom_codigo
                                AND facord.pk_factur_codigo=$idfactura
                                JOIN MODPROPAG.ppatblpedabon pedabon 
                                ON pedabon.pk_orden=ordcom.pk_ordcom_codigo
                                JOIN MODPROPAG.ppatbldetpab detpab
                                ON detpab.pk_pedido=pedabon.pk_pedabon_codigo
                                JOIN MODPRODUC.PROTBLPRODUC producto
                                ON producto.pk_produc_codigo=detpab.pk_producto
                                group by detpab.pk_producto, producto.nombre_producto");
            $abonos = $abonos->result_array;


            $abonosllavemaestra = $this->db->query("SELECT  '1' CANTIDAD,
                                                'Abono a tarjetas de Producto '||LOWER(producto.nombre_producto) PRODUCTO,
                                                producto.PK_PRODUC_CODIGO,
                                                SUM(MONTO) VALOR
                                                FROM MODFACTUR.factblfacord facord
                                                JOIN MODPROPAG.ppatblordcom ordcom ON facord.pk_ordcom_codigo=ordcom.pk_ordcom_codigo
                                                AND facord.pk_factur_codigo=$idfactura
                                                JOIN MODPROPAG.PPATBLDETORD do
                                                ON do.pk_orden_compra=ordcom.pk_ordcom_codigo
                                                 JOIN  MODPRODUC.PROTBLPRODUC producto
                                                ON producto.PK_PRODUC_CODIGO = do.PK_PRODUCTO
                                                left JOIN MODPRODUC.PROTBLPRODUC productoabonado
                                                ON do.PK_PRODUCTO_ABONADO=productoabonado.PK_PRODUC_CODIGO
                                                WHERE  do.indicador_abono=1 and ordcom.facturacion_llave=1 
                                                group by producto.PK_PRODUC_CODIGO,productoabonado.PK_PRODUC_CODIGO,producto.nombre_producto");
            $abonosllavemaestra = $abonosllavemaestra->result_array;


            //para concatenar en invoice ingresos terceros
            $cantAbono = 0;
            $idProduc = 1;
            $lineExtensionAm = 0;
            $taxInclusiveAm = 0;
            $TaxExclusiveAm = 0;
            $Total_Monto_Sin_Iva = 0;
            foreach ($abonos as $value) {
                $taxSubtotalTer = '<cac:TaxSubtotal>
                            <cbc:TaxableAmount currencyID="COP">' . $value['VALOR'] . '</cbc:TaxableAmount>
                            <cbc:TaxAmount currencyID="COP">0</cbc:TaxAmount>
                            <cac:TaxCategory>
                                <cbc:Percent>0.00</cbc:Percent>
                                <cac:TaxScheme>
                                    <cbc:ID>01</cbc:ID>
                                    <cbc:Name>IVA</cbc:Name>
                                </cac:TaxScheme>
                            </cac:TaxCategory>
                        </cac:TaxSubtotal>';
                $taxtTotal = $taxtTotal . $taxSubtotalTer;
                $InvoiceAbonos = $InvoiceAbonos .
                        '<cac:InvoiceLine>
          <!-- Ingreso terceros Abonos -->
          <cbc:ID schemeID="1">' . $idProduc++ . '</cbc:ID>
          <cbc:Note>' .$fecha_factura. '</cbc:Note>
          <cbc:InvoicedQuantity unitCode="94">' . intval($value['CANTIDAD']) . '</cbc:InvoicedQuantity>
          <cbc:LineExtensionAmount currencyID="COP">' . $value['VALOR'] . '</cbc:LineExtensionAmount>
               <cac:TaxTotal>
                    <cbc:TaxAmount currencyID="COP">0</cbc:TaxAmount>
                    <cbc:RoundingAmount currencyID="COP">0</cbc:RoundingAmount>
                ' . $taxSubtotalTer . '
                </cac:TaxTotal>
          <cac:Item>
            <cbc:Description>' . ucfirst($value['PRODUCTO']) . '</cbc:Description>
                ' . $BrandName . '
                <cbc:ModelName>1</cbc:ModelName>
                <!-- codigo del producto o servicio -->
		<cac:SellersItemIdentification>
                    <cbc:ID>' . $value['PK_PRODUC_CODIGO'] . '</cbc:ID>
		</cac:SellersItemIdentification>
		<cac:StandardItemIdentification>
                    <cbc:ID schemeName="Estándar de adopción del contribuyente" schemeID="999">' . $value['PK_PRODUC_CODIGO'] . '</cbc:ID>
		</cac:StandardItemIdentification>
                <cac:InformationContentProviderParty>
			<cac:PowerOfAttorney>
				<cac:AgentParty>
					<!--identificacion del tercero-->
					<cac:PartyIdentification>
						' . $PartyIdenTerceros . '
					</cac:PartyIdentification>
				</cac:AgentParty>
				</cac:PowerOfAttorney>
		</cac:InformationContentProviderParty>
          </cac:Item>
          <cac:Price>
            <cbc:PriceAmount currencyID="COP">' . $value['VALOR'] . '</cbc:PriceAmount>
            <cbc:BaseQuantity unitCode="94">' . intval($value['CANTIDAD']) . '</cbc:BaseQuantity>
          </cac:Price>
        </cac:InvoiceLine>';
                $cantAbono++;
                $lineExtensionAm += $value['VALOR'];
                $TaxExclusiveAm += $value['VALOR'];
                $Total_Monto_Sin_Iva += $value['VALOR'];
            }

            //abonos llave maestra
            foreach ($abonosllavemaestra as $value) {
                $taxSubtotalTer = '<cac:TaxSubtotal>
                            <cbc:TaxableAmount currencyID="COP">' . $value['VALOR'] . '</cbc:TaxableAmount>
                            <cbc:TaxAmount currencyID="COP">0</cbc:TaxAmount>
                            <cac:TaxCategory>
                                <cbc:Percent>0.00</cbc:Percent>
                                <cac:TaxScheme>
                                    <cbc:ID>01</cbc:ID>
                                    <cbc:Name>IVA</cbc:Name>
                                </cac:TaxScheme>
                            </cac:TaxCategory>
                        </cac:TaxSubtotal>';
                $taxtTotal = $taxtTotal . $taxSubtotalTer;
                $InvoiceAbonos = $InvoiceAbonos .
                        '<cac:InvoiceLine>
          <!-- Ingreso terceros Abonos -->
          <cbc:ID schemeID="1">' . $idProduc++ . '</cbc:ID>
          <cbc:Note>' . $fecha_factura. '</cbc:Note>
          <cbc:InvoicedQuantity unitCode="94">' . intval($value['CANTIDAD']) . '</cbc:InvoicedQuantity>
          <cbc:LineExtensionAmount currencyID="COP">' . $value['VALOR'] . '</cbc:LineExtensionAmount>
               <cac:TaxTotal>
                    <cbc:TaxAmount currencyID="COP">0</cbc:TaxAmount>
                    <cbc:RoundingAmount currencyID="COP">0</cbc:RoundingAmount>
                ' . $taxSubtotalTer . '
                </cac:TaxTotal>
          <cac:Item>
            <cbc:Description>' . ucfirst($value['PRODUCTO']) . '</cbc:Description>
                ' . $BrandName . '
                <cbc:ModelName>1</cbc:ModelName>
                <!-- codigo del producto o servicio -->
		<cac:SellersItemIdentification>
                    <cbc:ID>' . $value['PK_PRODUC_CODIGO'] . '</cbc:ID>
		</cac:SellersItemIdentification>
		<cac:StandardItemIdentification>
                    <cbc:ID schemeName="Estándar de adopción del contribuyente" schemeID="999">' . $value['PK_PRODUC_CODIGO'] . '</cbc:ID>
		</cac:StandardItemIdentification>
                <cac:InformationContentProviderParty>
			<cac:PowerOfAttorney>
				<cac:AgentParty>
					<!--identificacion del tercero-->
					<cac:PartyIdentification>
						' . $PartyIdenTerceros . '
					</cac:PartyIdentification>
				</cac:AgentParty>
				</cac:PowerOfAttorney>
		</cac:InformationContentProviderParty>
          </cac:Item>
          <cac:Price>
            <cbc:PriceAmount currencyID="COP">' . $value['VALOR'] . '</cbc:PriceAmount>
            <cbc:BaseQuantity unitCode="94">' . intval($value['CANTIDAD']) . '</cbc:BaseQuantity>
          </cac:Price>
        </cac:InvoiceLine>';
                $cantAbono++;
                $lineExtensionAm += $value['VALOR'];
                $TaxExclusiveAm += $value['VALOR'];
                $Total_Monto_Sin_Iva += $value['VALOR'];
            }


            //actualizacion 11 febrero 2021 Ronald
            //Sesolicita en ticket que el costo de tarjetas salga 
            $Sqldetalletarjetas = $this->db->query("SELECT   	
            'Precio Tarjetas '||LOWER(produc.nombre_producto) PRODUCTO,
            valor_unit VALOR_UNITARIO, 
            sum (cantidad) CANTIDAD ,
            sum (cantidad) * valor_unit TOTAL,
            produc.pk_tippro_codigo,
            produc.PK_PRODUC_CODIGO,
            detord.iva,
            sum(detord.VALOR_OBSEQUIO) VALOR_OBSEQUIO
			FROM  MODFACTUR.FACTBLFACORD facord
			JOIN MODPROPAG.PPATBLDETORD detord
			ON facord.pk_ordcom_codigo = detord.pk_orden_compra 
			AND facord.pk_factur_codigo=$idfactura
			INNER JOIN  MODPRODUC.PROTBLPRODUC produc
			ON produc.PK_PRODUC_CODIGO = detord.PK_PRODUCTO
			and produc.pk_tippro_codigo=1
            AND detord.pk_pedido is not null
            group by 'Precio Tarjetas '||LOWER(produc.nombre_producto) ,
            valor_unit,produc.pk_tippro_codigo,produc.PK_PRODUC_CODIGO,detord.iva");

            $detalleTarjetas = $Sqldetalletarjetas->result_array;

            $numpropiosTar = 0;
            $porc_iva_TaxScheme = 0;
            $Taxable_tarjetas = 0;
            foreach ($detalleTarjetas as $value) {
                if ($value['VALOR_UNITARIO'] == 0) {
                    $costoRegalo = $value['VALOR_OBSEQUIO'] / $value['CANTIDAD'];
                    $pk_produc_codigo = $value['PK_PRODUC_CODIGO'];

                    $costoRegalo = empty($costoRegalo) ? 1 : $costoRegalo;


                    $PricingReferenceTar = '<cac:PricingReference>
                                            <cac:AlternativeConditionPrice>
                                            <!--Falta traer valor producto  -->
                                                <cbc:PriceAmount currencyID="COP">' . $costoRegalo . '</cbc:PriceAmount>
                                                <cbc:PriceTypeCode>01</cbc:PriceTypeCode> 
                                            </cac:AlternativeConditionPrice>
                                          </cac:PricingReference>';
                } else {
                    $PricingReferenceTar = '';
                }
                $ivaProTar = empty($value['IVA']) ? 0 : $value['IVA'];
                $valIvaTarj = round(($value['TOTAL'] * ($ivaProTar / 100)), 2);
                $lineExtetarj = $value['TOTAL'];
               // agrgado 20 agosto 2021 Ronald para facturas con items valor unitario cero he iva 19 %
                if ($value['VALOR_UNITARIO'] == 0 && $ivaProTar==19){
                    $ivaProTar=0;
                }

                $taxSubtotalProTar = '<cac:TaxSubtotal>
                                    <cbc:TaxableAmount currencyID="COP">' . $value['TOTAL'] . '</cbc:TaxableAmount>
                                    <cbc:TaxAmount currencyID="COP">' . $valIvaTarj . '</cbc:TaxAmount>
                                    <cac:TaxCategory>
                                        <cbc:Percent>' . $ivaProTar . '.00</cbc:Percent>
                                        <cac:TaxScheme>
                                            <cbc:ID>01</cbc:ID>
                                            <cbc:Name>IVA</cbc:Name>
                                        </cac:TaxScheme>
                                    </cac:TaxCategory>
                                </cac:TaxSubtotal>';
                $taxtTotal = $taxtTotal . $taxSubtotalProTar;
                $InvoicePropios = $InvoicePropios .
                        '<cac:InvoiceLine>
              <!-- Ingresos propios -->
          <cbc:ID schemeID="0">' . $idProduc++ . '</cbc:ID>
          <cbc:Note>' . $fecha_factura. '</cbc:Note>
          <cbc:InvoicedQuantity unitCode="94">' . intval($value['CANTIDAD']) . '</cbc:InvoicedQuantity>
          <cbc:LineExtensionAmount currencyID="COP">' . $lineExtetarj . '</cbc:LineExtensionAmount>
              ' . $PricingReferenceTar . '
              <cac:TaxTotal>
		<cbc:TaxAmount currencyID="COP">' . $valIvaTarj . '</cbc:TaxAmount>
                <cbc:RoundingAmount currencyID="COP">0</cbc:RoundingAmount>
               ' . $taxSubtotalProTar . '
            </cac:TaxTotal>
          <cac:Item>
            <cbc:Description>' . ucfirst($value['PRODUCTO']) . '</cbc:Description>
            ' . $BrandName . '
            <cbc:ModelName>2</cbc:ModelName>
            <!-- codigo del producto o servicio -->
            <cac:SellersItemIdentification>
		<cbc:ID>' . $value['PK_PRODUC_CODIGO'] . '</cbc:ID>
            </cac:SellersItemIdentification>
            <cac:StandardItemIdentification>
		<cbc:ID schemeName="Estándar de adopción del contribuyente" schemeID="999">' . $value['PK_PRODUC_CODIGO'] . '</cbc:ID>
            </cac:StandardItemIdentification>
          </cac:Item>
          <cac:Price>
            <cbc:PriceAmount currencyID="COP">' . $value['VALOR_UNITARIO'] . '</cbc:PriceAmount>
            <cbc:BaseQuantity unitCode="94">' . intval($value['CANTIDAD']) . '</cbc:BaseQuantity>
          </cac:Price>
        </cac:InvoiceLine>';
                $numpropiosTar++;
                $CalculoIvatarj += $valIvaTarj;
                $lineExtensionAmTarj += $lineExtetarj;
                $TaxExclusiveAmTarj += $lineExtetarj;
                $taxInclusiveAmtarj += $valIvaTarj;
                $porc_iva_TaxScheme = $ivaProTar;
                $Taxable_Tarjetas += $value['TOTAL'];
                if ($ivaProTar == 0) {
                    $Total_Monto_Sin_Iva += $value['TOTAL'];
                }
            }
            //ingresos propios
            $detallePropios = $this->db->query("/*SELECT   	
            'Precio Tarjetas '||LOWER(produc.nombre_producto) PRODUCTO,
            sum(valor_unit) VALOR_UNITARIO, 
            '1' CANTIDAD ,
            sum(valor_unit)  TOTAL,
            produc.pk_tippro_codigo,
            produc.PK_PRODUC_CODIGO,
            detord.iva,
            sum(detord.VALOR_OBSEQUIO) VALOR_OBSEQUIO
			FROM  MODFACTUR.FACTBLFACORD facord
			JOIN MODPROPAG.PPATBLDETORD detord
			ON facord.pk_ordcom_codigo = detord.pk_orden_compra 
			AND facord.pk_factur_codigo=$idfactura
			INNER JOIN  MODPRODUC.PROTBLPRODUC produc
			ON produc.PK_PRODUC_CODIGO = detord.PK_PRODUCTO
			and produc.pk_tippro_codigo=1
            AND detord.pk_pedido is not null
            group by 'Precio Tarjetas '||LOWER(produc.nombre_producto) ,
            produc.pk_tippro_codigo,produc.PK_PRODUC_CODIGO,detord.iva
            UNION ALL */
            SELECT   	
            LOWER(produc.nombre_producto)||' abono '||LOWER(productoabonado.nombre_producto) PRODUCTO,
            sum (valor_unit) VALOR_UNITARIO, 
           '1' CANTIDAD  ,
            sum (valor_unit) TOTAL,
            produc.pk_tippro_codigo,
            produc.PK_PRODUC_CODIGO,
            detord.iva,
            sum(detord.VALOR_OBSEQUIO) VALOR_OBSEQUIO
			FROM  MODFACTUR.FACTBLFACORD facord
			JOIN MODPROPAG.PPATBLDETORD detord
			ON facord.pk_ordcom_codigo = detord.pk_orden_compra 
			AND facord.pk_factur_codigo=$idfactura
			INNER JOIN  MODPRODUC.PROTBLPRODUC produc
			ON produc.PK_PRODUC_CODIGO = detord.PK_PRODUCTO
            INNER JOIN MODPRODUC.PROTBLPRODUC productoabonado
            ON detord.PK_PRODUCTO_ABONADO=productoabonado.PK_PRODUC_CODIGO
			and produc.pk_tippro_codigo=3
            group by  LOWER(produc.nombre_producto)||' abono '||LOWER(productoabonado.nombre_producto) ,
            produc.pk_tippro_codigo,produc.PK_PRODUC_CODIGO,detord.iva
            UNION ALL  	
            SELECT   	
            LOWER(produc.nombre_producto) PRODUCTO,
            sum (valor_unit)  VALOR_UNITARIO, 
            '1' CANTIDAD ,
            sum (valor_unit) TOTAL,
            produc.pk_tippro_codigo,
            produc.PK_PRODUC_CODIGO,
            detord.iva,
             sum(detord.VALOR_OBSEQUIO) VALOR_OBSEQUIO
			FROM  MODFACTUR.FACTBLFACORD facord
			JOIN MODPROPAG.PPATBLORDCOM ORDEN 
            ON orden.pk_ordcom_codigo=facord.pk_ordcom_codigo
			AND facord.pk_factur_codigo=$idfactura
            JOIN MODPROPAG.PPATBLDETORD detord
			ON facord.pk_ordcom_codigo = detord.pk_orden_compra 
            and orden.facturacion_llave!=1
			INNER JOIN  MODPRODUC.PROTBLPRODUC produc
			ON produc.PK_PRODUC_CODIGO = detord.PK_PRODUCTO
            AND detord.pk_pedido IS NULL 
            AND detord.pk_pedido_abono  IS NULL 
            and produc.pk_tippro_codigo=3 
            group by LOWER(produc.nombre_producto),produc.pk_tippro_codigo,produc.PK_PRODUC_CODIGO,detord.iva");



            $numpropios = 0;
            $CalculoIva = 0;
            $Compras = 0;
            $Servicios = 0;
            $Taxable_Propios = 0;

            $detallePropios = $detallePropios->result_array;
            foreach ($detallePropios as $value) {
                if ($value['PK_TIPPRO_CODIGO'] == 1) {
                    $Compras += $value['TOTAL'];
                }
                if ($value['PK_TIPPRO_CODIGO'] == 3) {
                    $Servicios += $value['TOTAL'];
                }
                if ($value['VALOR_UNITARIO'] == 0) {
                    $costoRegalo = $value['VALOR_OBSEQUIO'] / $value['CANTIDAD'];
                    $pk_produc_codigo = $value['PK_PRODUC_CODIGO'];

                    $costoRegalo = empty($costoRegalo) ? 1 : $costoRegalo;


                    $PricingReference = '<cac:PricingReference>
                                            <cac:AlternativeConditionPrice>
                                            <!--Falta traer valor producto  -->
                                                <cbc:PriceAmount currencyID="COP">' . $costoRegalo . '</cbc:PriceAmount>
                                                <cbc:PriceTypeCode>03</cbc:PriceTypeCode> 
                                            </cac:AlternativeConditionPrice>
                                          </cac:PricingReference>';
                } else {
                    $PricingReference = '';
                }
                $ivaPro = empty($value['IVA']) ? 0 : $value['IVA'];
                $valIva = round(($value['TOTAL'] * ($ivaPro / 100)), 2);
                $lineExte = $value['TOTAL'];
                
                if ($value['VALOR_UNITARIO'] == 0 && $ivaPro==19){
                    $ivaPro=0;
                }
                
                $taxSubtotalPro = '<cac:TaxSubtotal>
                                    <cbc:TaxableAmount currencyID="COP">' . $value['TOTAL'] . '</cbc:TaxableAmount>
                                    <cbc:TaxAmount currencyID="COP">' . $valIva . '</cbc:TaxAmount>
                                    <cac:TaxCategory>
                                        <cbc:Percent>' . $ivaPro . '.00</cbc:Percent>
                                        <cac:TaxScheme>
                                            <cbc:ID>01</cbc:ID>
                                            <cbc:Name>IVA</cbc:Name>
                                        </cac:TaxScheme>
                                    </cac:TaxCategory>
                                </cac:TaxSubtotal>';
                $taxtTotal = $taxtTotal . $taxSubtotalPro;
                $InvoicePropios = $InvoicePropios .
                        '<cac:InvoiceLine>
              <!-- Ingresos propios -->
          <cbc:ID schemeID="0">' . $idProduc++ . '</cbc:ID>
          <cbc:Note>' . $fecha_factura. '</cbc:Note>
          <cbc:InvoicedQuantity unitCode="94">' . intval($value['CANTIDAD']) . '</cbc:InvoicedQuantity>
          <cbc:LineExtensionAmount currencyID="COP">' . $lineExte . '</cbc:LineExtensionAmount>
              ' . $PricingReference . '
              <cac:TaxTotal>
		<cbc:TaxAmount currencyID="COP">' . $valIva . '</cbc:TaxAmount>
                <cbc:RoundingAmount currencyID="COP">0</cbc:RoundingAmount><!--validar ya que se trata de enviar 0.50 y quitarlo al taxAmount pero genera error cufe-->
               ' . $taxSubtotalPro . '
            </cac:TaxTotal>
          <cac:Item>
            <cbc:Description>' . ucfirst($value['PRODUCTO']) . '</cbc:Description>
            ' . $BrandName . '
            <cbc:ModelName>2</cbc:ModelName>
            <cac:SellersItemIdentification>
		<cbc:ID>' . $value['PK_PRODUC_CODIGO'] . '</cbc:ID>
            </cac:SellersItemIdentification>
            <cac:StandardItemIdentification>
		<cbc:ID schemeName="Estándar de adopción del contribuyente" schemeID="999">' . $value['PK_PRODUC_CODIGO'] . '</cbc:ID>
            </cac:StandardItemIdentification>
          </cac:Item>
          <cac:Price>
            <cbc:PriceAmount currencyID="COP">' . $value['VALOR_UNITARIO'] . '</cbc:PriceAmount>
            <cbc:BaseQuantity unitCode="94">' . intval($value['CANTIDAD']) . '</cbc:BaseQuantity>
          </cac:Price>
        </cac:InvoiceLine>';
                $numpropios++;
                $CalculoIva += $valIva;
                $lineExtensionAm += $lineExte;
                $TaxExclusiveAm += $lineExte;
                $taxInclusiveAm += $valIva;
                $porc_iva_TaxScheme = $ivaPro;
                $Taxable_Propios += $value['TOTAL'];
                if ($ivaPro == 0) {
                    $Total_Monto_Sin_Iva += $value['TOTAL'];
                }
            }



            $CalculoIva = $CalculoIva + $CalculoIvatarj;
            $lineExtensionAm = $lineExtensionAm + $lineExtensionAmTarj;
            $TaxExclusiveAm = $TaxExclusiveAm + $TaxExclusiveAmTarj;
            $taxInclusiveAm = $taxInclusiveAm + $taxInclusiveAmtarj;
            $cantInvoice = $cantAbono + $numpropios + $numpropiosTar;

            //trae valor iva 17 agosto 2021 Ronald
      
            $sql_porc_iva = $this->db->query("select modpropag.ppapkgactualizaciones.fncretornaimpuestoiva($pk_entidad,1) PORC_IVA from dual");
            $porc_iva_bd = $sql_porc_iva->result_array[0]['PORC_IVA'];




            $Total_Monto_Con_Iva = $CalculoIva;
            $TaxableAmountIva = $Taxable_Tarjetas + $Taxable_Propios;
            //TaxSubTotal Con iva
            $TaxTotalConIva = '';
            if ($Total_Monto_Con_Iva != 0) {
                $TaxTotalConIva = '<cac:TaxSubtotal>
                            <cbc:TaxableAmount currencyID="COP">' . $TaxableAmountIva . '</cbc:TaxableAmount>
                            <cbc:TaxAmount currencyID="COP">' . $Total_Monto_Con_Iva . '</cbc:TaxAmount>
                            <cac:TaxCategory>
                                <cbc:Percent>' . $porc_iva_bd . '.00</cbc:Percent>
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
            } elseif ($Total_Monto_Sin_Iva == 0 && $costoRegalo != 0) {
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

            log_info($this->logHeader . 'TOTAL SIN IVA = ' . $Total_Monto_Sin_Iva);

            $valorimpuestos = $this->db->query("select 
						fac.pma INGRESOS_TERCEROS,
						MODFACTUR.facpkgconsultas.fncconsultarimpuestofactura(parpkfactura=>FAC.pk_factur_codigo,parnombreimpuesto=>'IVA%') IVA,
						MODFACTUR.facpkgconsultas.fncconsultarimpuestofactura(parpkfactura=>FAC.pk_factur_codigo,parnombreimpuesto=>'RTE FTE%') RTE_FUENTE,
						MODFACTUR.facpkgconsultas.fncconsultarimpuestofactura(parpkfactura=>FAC.pk_factur_codigo,parnombreimpuesto=>'RTE ICA%') RTE_ICA,
						MODFACTUR.facpkgconsultas.fncconsultarimpuestofactura(parpkfactura=>FAC.pk_factur_codigo,parnombreimpuesto=>'RTE IVA%') RTE_IVA,
						FAC.PCO INGRESOS_PROPIOS,
						FAC.TOTAL TOTAL
						from MODFACTUR.factblfactur fac
						where fac.pk_factur_codigo =$idfactura");

            $valorimpuestos = $valorimpuestos->result_array[0];
            $ingresos_propios = $valorimpuestos['INGRESOS_PROPIOS'];
            $ingresos_terceros = empty($valorimpuestos['INGRESOS_TERCEROS']) ? 0 : $valorimpuestos['INGRESOS_TERCEROS'];
//            $iva = $valorimpuestos['IVA'];
            $iva = $CalculoIva;

            $rte_fuente = empty($valorimpuestos['RTE_FUENTE']) ? 0 : $valorimpuestos['RTE_FUENTE'];
            $rte_ica = empty($valorimpuestos['RTE_ICA']) ? 0 : $valorimpuestos['RTE_ICA'];
            $rte_iva = empty($valorimpuestos['RTE_IVA']) ? 0 : $valorimpuestos['RTE_IVA'];
            $total_factura = $valorimpuestos['TOTAL'];
//            $payableAmount = $total_factura + $rte_fuente + $rte_ica + $rte_iva;
            $payableAmount = $lineExtensionAm + $CalculoIva;
            $taxInclusive = $lineExtensionAm + $CalculoIva;
            //Comento Ronald por error centavos en subtotalpropios  
//            $subtotalPropios = ($ingresos_propios - $iva) + $rte_fuente + $rte_ica + $rte_iva;
            $subtotalPropios = $TaxableAmountIva;
//            var_dump('reteFuente-'.$rte_fuente.' '.'rte_Ica-'.$rte_ica.' '.'rteIva'.$rte_iva);
//            var_dump('TotalFact-'.$total_factura.' '.'subtotalPropios-'.$subtotalPropios);
//            exit();
//            if($CalculoIva!=0){
//                $rounding=$iva-$CalculoIva;
//            }
////            var_dump($rounding.'-'.$iva.'-'.$CalculoIva);exit();
//            $roundingAmount=isset($rounding)?$rounding:0;   
//            $iva=number_format($roundingAmount+$iva, 2, '.', '');
            //GRUPO RETENCIONES
            //RETEIVA
            $sqlRteIva = $this->db->query("SELECT PORCENTAJE, VALOR , IMPUESTO FROM MODFACTUR.FACTBLIMPFAC 
                WHERE PK_FACTURA =  $idfactura
                AND IMPUESTO LIKE 'RTE IVA%'");
            $RteIva = $sqlRteIva->result_array;
//            $porcRteIva = $this->retornarValorConfiguracion(12);
//            $porcenRteIva = ($porcRteIva) / 100;
//            $valorRteIvaCompras = $Compras * ($porcenIva / 100);
//            $valorRteIvaServicios = $Servicios * ($porcenIva / 100);
            $RteIvaCom = 0;
            $RteIvaServ = 0;
            $RETEIVA = '';
            $RETEICA = '';
            $RETEFTE = '';
            $idAllowance = 1;
            $AllowanceCharge = '';
            $totalRteIva = 0;
            $taxRteIvaCom = 0;
            $taxRteIvaSer = 0;
            $taxTotalRteIva = 0;


            foreach ($RteIva as $value) {
                if ($value['PORCENTAJE'] != 0) {
//                    $taxRteIva = round(((100 * $value['VALOR']) / ($value['PORCENTAJE'])), 2);
                    $porcRteIva = $value['PORCENTAJE'];
                    $porcenRteIva = ($porcRteIva) / 100;
                    if ($value['IMPUESTO'] == 'RTE IVA COMPRAS 15') {
                        $taxRteIvaCom = round(((100 * $value['VALOR']) / ($value['PORCENTAJE'])), 2);
                        /* $RETEIVA = $RETEIVA . '<cac:TaxSubtotal>
                          <cbc:TaxableAmount currencyID="COP">' . $taxRteIva . '</cbc:TaxableAmount>
                          <cbc:TaxAmount currencyID="COP">' . round(($taxRteIva * $porcenRteIva), 2) . '</cbc:TaxAmount>
                          <cac:TaxCategory>
                          <cbc:Percent>' . $porcRteIva . '</cbc:Percent>
                          <cac:TaxScheme>
                          <cbc:ID>05</cbc:ID>
                          <cbc:Name>RETEIVA</cbc:Name>
                          </cac:TaxScheme>
                          </cac:TaxCategory>
                          </cac:TaxSubtotal>
                          '; */
                        $RteIvaCom = round(($taxRteIvaCom * $porcenRteIva), 2);
                    }
                    if ($value['IMPUESTO'] == 'RTE IVA SERVICIOS') {
                        $taxRteIvaSer = round(((100 * $value['VALOR']) / ($value['PORCENTAJE'])), 2);
                        /* $RETEIVA = $RETEIVA . '<cac:TaxSubtotal>
                          <cbc:TaxableAmount currencyID="COP">' . $taxRteIva . '</cbc:TaxableAmount>
                          <cbc:TaxAmount currencyID="COP">' . round(($taxRteIva * $porcenRteIva), 2) . '</cbc:TaxAmount>
                          <cac:TaxCategory>
                          <cbc:Percent>' . $porcRteIva . '</cbc:Percent>
                          <cac:TaxScheme>
                          <cbc:ID>05</cbc:ID>
                          <cbc:Name>RETEIVA</cbc:Name>
                          </cac:TaxScheme>
                          </cac:TaxCategory>
                          </cac:TaxSubtotal>'; */
                        $RteIvaServ = round(($taxRteIvaSer * $porcenRteIva), 2);
                    }
                    $totalRteIva = $RteIvaCom + $RteIvaServ;

                    /* $AllowanceCharge = $AllowanceCharge . '<cac:AllowanceCharge>
                      <cbc:ID>' . $idAllowance . '</cbc:ID>
                      <cbc:ChargeIndicator>false</cbc:ChargeIndicator>
                      <cbc:AllowanceChargeReasonCode>01</cbc:AllowanceChargeReasonCode>
                      <cbc:AllowanceChargeReason>Descuento Impuesto</cbc:AllowanceChargeReason>
                      <cbc:MultiplierFactorNumeric>' . $porcRteIva . '</cbc:MultiplierFactorNumeric>
                      <cbc:Amount currencyID="COP">' . round(($taxRteIva * $porcenRteIva), 2) . '</cbc:Amount>
                      <cbc:BaseAmount currencyID="COP">' . $taxRteIva . '</cbc:BaseAmount>
                      </cac:AllowanceCharge>';
                      $idAllowance++; */
                }
            }

            $taxTotalRteIva = $taxRteIvaCom + $taxRteIvaSer;
            $idAllowance = $idAllowance + 1;
            if ($taxTotalRteIva != 0) {
                $RETEIVA = '<cac:TaxSubtotal>
						<cbc:TaxableAmount currencyID="COP">' . $taxTotalRteIva . '</cbc:TaxableAmount>
						<cbc:TaxAmount currencyID="COP">' . $totalRteIva . '</cbc:TaxAmount>
						<cac:TaxCategory>
							<cbc:Percent>' . $porcRteIva . '</cbc:Percent>
							<cac:TaxScheme>
								<cbc:ID>05</cbc:ID>
								<cbc:Name>RETEIVA</cbc:Name>
							</cac:TaxScheme>
						</cac:TaxCategory>
					</cac:TaxSubtotal>
				';


                $AllowanceCharge = $AllowanceCharge . '<cac:AllowanceCharge>
					<cbc:ID>' . $idAllowance . '</cbc:ID>
					<cbc:ChargeIndicator>false</cbc:ChargeIndicator>
					<cbc:AllowanceChargeReasonCode>01</cbc:AllowanceChargeReasonCode>
					<cbc:AllowanceChargeReason>Descuento Impuesto</cbc:AllowanceChargeReason>
					<cbc:MultiplierFactorNumeric>' . $porcRteIva . '</cbc:MultiplierFactorNumeric>
					<cbc:Amount currencyID="COP">' . $totalRteIva . '</cbc:Amount>
					<cbc:BaseAmount currencyID="COP">' . $taxTotalRteIva . '</cbc:BaseAmount>
				 </cac:AllowanceCharge>';
            }



            //RETEICA
            $sqlRteIca = $this->db->query("SELECT PORCENTAJE, VALOR , IMPUESTO FROM MODFACTUR.FACTBLIMPFAC 
                WHERE PK_FACTURA =  $idfactura
                AND IMPUESTO LIKE 'RTE ICA%'");
            $RteIca = $sqlRteIca->result_array;
//            $porcRteIcaServicios = $this->retornarValorConfiguracion(11);
//            $porcenRteIcaServicios = ($porcRteIcaServicios) / 1000;
//            $porcRteIcaCompras = $this->retornarValorConfiguracion(10);
//            $porcenRteIcaCompras = ($porcRteIcaCompras) / 1000;
            $valorRteIcaCompras = $Compras;
            $valorRteIcaServicios = $Servicios;
            $RteIcaCom = 0;
            $RteIcaServ = 0;
            $totalRteIca = 0;
            foreach ($RteIca as $value) {
                if ($value['PORCENTAJE'] != 0) {
                    $porcRteIca = $value['PORCENTAJE'];
                    $taxRteIca = round(((100 * $value['VALOR']) / ($porcRteIca)), 2);
                    $porcenRteIcaCompras = ($porcRteIca) / 100;
                    log_info($this->errorFuncion . '$porcenRteIcaCompras=' . $value['PORCENTAJE'] . '$valuevalor' . $value['VALOR'] . '$porcRteIca= ' . $porcRteIca . '$taxRteIca= ' . $taxRteIca . ' $porcenRteIcaCompras=' . $porcenRteIcaCompras);
                    if ($value['IMPUESTO'] == 'RTE ICA COMPRAS 4') {
                        $RETEICA = $RETEICA . '<cac:TaxSubtotal>
						<cbc:TaxableAmount currencyID="COP">' . $taxRteIca . '</cbc:TaxableAmount>
						<cbc:TaxAmount currencyID="COP">' . round(($taxRteIca * $porcenRteIcaCompras), 2) . '</cbc:TaxAmount>
						<cac:TaxCategory>
							<cbc:Percent>' . $porcRteIca . '</cbc:Percent>
							<cac:TaxScheme>
								<cbc:ID>07</cbc:ID>
								<cbc:Name>RETEICA</cbc:Name>
							</cac:TaxScheme>
						</cac:TaxCategory>
					</cac:TaxSubtotal>
				';
                        $RteIcaCom = round(($taxRteIca * $porcenRteIcaCompras), 2);
                    }
                    if ($value['IMPUESTO'] == 'RTE ICA SERVICIOS') {
                        $RETEICA = $RETEICA . '<cac:TaxSubtotal>
						<cbc:TaxableAmount currencyID="COP">' . $taxRteIca . '</cbc:TaxableAmount>
						<cbc:TaxAmount currencyID="COP">' . round(($taxRteIca * $porcenRteIcaCompras), 2) . '</cbc:TaxAmount>
						<cac:TaxCategory>
							<cbc:Percent>' . $porcRteIca . '</cbc:Percent>
							<cac:TaxScheme>
								<cbc:ID>07</cbc:ID>
								<cbc:Name>RETEICA</cbc:Name>
							</cac:TaxScheme>
						</cac:TaxCategory>
					</cac:TaxSubtotal>';
                        $RteIcaServ = round(($taxRteIca * $porcenRteIcaCompras), 2);
                    }
                    $totalRteIca = $RteIcaCom + $RteIcaServ;
                    $AllowanceCharge = $AllowanceCharge . '<cac:AllowanceCharge>
					<cbc:ID>' . $idAllowance . '</cbc:ID>
					<cbc:ChargeIndicator>false</cbc:ChargeIndicator>
					<cbc:AllowanceChargeReasonCode>01</cbc:AllowanceChargeReasonCode>
					<cbc:AllowanceChargeReason>Descuento Impuesto</cbc:AllowanceChargeReason>
					<cbc:MultiplierFactorNumeric>' . $porcRteIca . '</cbc:MultiplierFactorNumeric>
					<cbc:Amount currencyID="COP">' . round(($taxRteIca * $porcenRteIcaCompras), 2) . '</cbc:Amount>
					<cbc:BaseAmount currencyID="COP">' . $taxRteIca . '</cbc:BaseAmount>
				 </cac:AllowanceCharge>';
                    $idAllowance++;
                }
            }
            //RETEFUENTE
            $sqlRteFte = $this->db->query("SELECT PORCENTAJE, VALOR , IMPUESTO FROM MODFACTUR.FACTBLIMPFAC 
                WHERE PK_FACTURA =  $idfactura
                AND IMPUESTO LIKE 'RTE FTE%'");
            $RteFte = $sqlRteFte->result_array;
//            $porcRteFuenteServicios = $this->retornarValorConfiguracion(14);
//            $porcenRteFteServicios = ($porcRteFuenteServicios) / 100;
//            $porcRteFuenteCompras = $this->retornarValorConfiguracion(13);
//            $porcenRteFteCompras = ($porcRteFuenteCompras) / 100;
            $valorRteFteCompras = $Compras;
            $valorRteFteServicios = $Servicios;
            $RteFteCom = 0;
            $rteFteServ = 0;
            $totalRteFte = 0;
            foreach ($RteFte as $value) {
                if ($value['PORCENTAJE'] != 0) {
                    $porcRteFuente = $value['PORCENTAJE'];
                    $taxRteFte = round(((100 * $value['VALOR']) / ($porcRteFuente)), 2);
                    $porcenRteFte = ($porcRteFuente) / 100;
                    if ($value['IMPUESTO'] == 'RTE FTE COMPRAS 4') {
                        $RETEFTE = $RETEFTE . '<cac:TaxSubtotal>
						<cbc:TaxableAmount currencyID="COP">' . $taxRteFte . '</cbc:TaxableAmount>
						<cbc:TaxAmount currencyID="COP">' . round(($taxRteFte * $porcenRteFte), 2) . '</cbc:TaxAmount>
						<cac:TaxCategory>
							<cbc:Percent>' . $porcRteFuente . '</cbc:Percent>
							<cac:TaxScheme>
								<cbc:ID>06</cbc:ID>
								<cbc:Name>RETEFUENTE</cbc:Name>
							</cac:TaxScheme>
						</cac:TaxCategory>
					</cac:TaxSubtotal>
				';
                        $RteFteCom = round(($taxRteFte * $porcenRteFte), 2);
                    }
                    if ($value['IMPUESTO'] == 'RTE FTE SERVICIOS 4') {
                        $RETEFTE = $RETEFTE . '<cac:TaxSubtotal>
						<cbc:TaxableAmount currencyID="COP">' . $taxRteFte . '</cbc:TaxableAmount>
						<cbc:TaxAmount currencyID="COP">' . round(($taxRteFte * $porcenRteFte), 2) . '</cbc:TaxAmount>
						<cac:TaxCategory>
							<cbc:Percent>' . $porcRteFuente . '</cbc:Percent>
							<cac:TaxScheme>
								<cbc:ID>06</cbc:ID>
								<cbc:Name>RETEFUENTE</cbc:Name>
							</cac:TaxScheme>
						</cac:TaxCategory>
					</cac:TaxSubtotal>';
                        $rteFteServ = round(($taxRteFte * $porcenRteFte), 2);
                    }
                    $totalRteFte = $RteFteCom + $rteFteServ;
                    $AllowanceCharge = $AllowanceCharge . '<cac:AllowanceCharge>
					<cbc:ID>' . $idAllowance . '</cbc:ID>
					<cbc:ChargeIndicator>false</cbc:ChargeIndicator>
					<cbc:AllowanceChargeReasonCode>01</cbc:AllowanceChargeReasonCode>
					<cbc:AllowanceChargeReason>Descuento Impuesto</cbc:AllowanceChargeReason>
					<cbc:MultiplierFactorNumeric>' . $porcRteFuente . '</cbc:MultiplierFactorNumeric>
					<cbc:Amount currencyID="COP">' . round(($taxRteFte * $porcenRteFte), 2) . '</cbc:Amount>
					<cbc:BaseAmount currencyID="COP">' . $taxRteFte . '</cbc:BaseAmount>
				 </cac:AllowanceCharge>';
                    $idAllowance++;
                }
            }

            if (!empty($RETEIVA)) {
                $tagGrupoHoldingRteIva = '<cac:WithholdingTaxTotal>
                                            <cbc:TaxAmount currencyID="COP">' . $totalRteIva . '</cbc:TaxAmount>'
                        . $RETEIVA .
                        '</cac:WithholdingTaxTotal>';
            }
            if (!empty($RETEICA)) {
                $tagGrupoHoldingRteIca = '<cac:WithholdingTaxTotal>
                                            <cbc:TaxAmount currencyID="COP">' . $totalRteIca . '</cbc:TaxAmount>'
                        . $RETEICA .
                        '</cac:WithholdingTaxTotal>';
            }
            if (!empty($RETEFTE)) {
                $tagGrupoHoldingRteFte = '<cac:WithholdingTaxTotal>
                                            <cbc:TaxAmount currencyID="COP">' . $totalRteFte . '</cbc:TaxAmount>'
                        . $RETEFTE .
                        '</cac:WithholdingTaxTotal>';
            }
            $totalAllowance = $totalRteIva + $totalRteIca + $totalRteFte;
            $payableAmount = $payableAmount - $totalAllowance;
        }
//        var_dump($taxtTotal);exit();
        try {
            $dom = new DOMDocument;
            $dom->preserveWhiteSpace = false;
            $dom->formatOutput = true;
            $xmlValid = $dom->loadXML('<?xml version="1.0" encoding="utf-8"?>
<Comprobantes>
	<Comprobante>
		<informacionOrganismo>
			<Invoice xmlns:clm66411="urn:un:unece:uncefact:codelist:specification:66411:2001" xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2" xmlns:clmIANAMIMEMediaType="urn:un:unece:uncefact:codelist:specification:IANAMIMEMediaType:2003" xmlns:qdt="urn:oasis:names:specification:ubl:schema:xsd:QualifiedDatatypes-2" xmlns:udt="urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2" xmlns:sts="dian:gov:co:facturaelectronica:Structures-2-1" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:ccts="urn:un:unece:uncefact:documentation:2" xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:clm54217="urn:un:unece:uncefact:codelist:specification:54217:2001" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns="urn:oasis:names:specification:ubl:schema:xsd:Invoice-2">
				<ext:UBLExtensions>
					<ext:UBLExtension>
						<ext:ExtensionContent>
							<sts:DianExtensions>
								<sts:InvoiceControl>
									<sts:InvoiceAuthorization>' . $resolPeoople . '</sts:InvoiceAuthorization>
									<sts:AuthorizationPeriod>
										<cbc:StartDate>' . $inicioResol . '</cbc:StartDate>
										<cbc:EndDate>' . $finResol . '</cbc:EndDate>
									</sts:AuthorizationPeriod>
									<sts:AuthorizedInvoices>
										<sts:Prefix>' . $PrefijoPeople . '</sts:Prefix>
										<sts:From>' . $rangoInicio . '</sts:From>
										<sts:To>' . $rangoFin . '</sts:To>
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
				<cbc:ProfileExecutionID>' . $ambienteComfiar . '</cbc:ProfileExecutionID>
				<cbc:ID>' . $PrefijoPeople . $nro_factura . '</cbc:ID>
				<cbc:UUID schemeID="' . $ambienteComfiar . '" schemeName="CUFE-SHA384" />
				<cbc:IssueDate>' . $fecha_factura. '</cbc:IssueDate>
				<cbc:IssueTime>' . $hora_factura. '-05:00' . '</cbc:IssueTime>
				<cbc:InvoiceTypeCode>' . $InvoiceTypeCode . '</cbc:InvoiceTypeCode>
				<cbc:Note>' . $total_factura . '</cbc:Note>
				<cbc:Note>' . $ingresos_propios . '</cbc:Note>
				<cbc:Note>' . $ingresos_terceros . '</cbc:Note>
				<cbc:Note>Impuestos sobre las ventas - IVA 
Persona Jurídica. Actividad económica 8220 Tarifa ICA 9.66x1.000. No somos Grandes Contribuyentes. SOMOS AUTORRETENEDORES DE RENTA según Resolución No 000976 del 12 de febrero del 2020. Resolución gráfica de la factura electrónica según parágrafo 1 articulo 3 decreto 2242 de 2015.


Vigencia 12 Meses.</cbc:Note>
				<cbc:Note>' . $subtotalPropios . '</cbc:Note>
				<cbc:Note>' . $observacion_factura . '</cbc:Note>
				<cbc:Note>VACIO</cbc:Note>
				<cbc:Note/>
				<cbc:Note></cbc:Note>
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
				<cbc:DocumentCurrencyCode>COP</cbc:DocumentCurrencyCode>
				<cbc:LineCountNumeric>' . $cantInvoice . '</cbc:LineCountNumeric>
				<cac:AccountingSupplierParty>
					<cbc:AdditionalAccountID>1</cbc:AdditionalAccountID>
					<cac:Party>
						<cac:PartyName>
							<cbc:Name>PEOPLEPASS S.A</cbc:Name>
						</cac:PartyName>
						<cac:PhysicalLocation>
							<cac:Address>
								<cbc:ID>11001</cbc:ID>
								<cbc:CityName>BOGOTA</cbc:CityName>
								<cbc:PostalZone>110111</cbc:PostalZone>
								<cbc:CountrySubentity>CUNDINAMARCA</cbc:CountrySubentity>
								<cbc:CountrySubentityCode>11</cbc:CountrySubentityCode>
								<cac:AddressLine>
									<cbc:Line>' . $direccionPeople . '– Bogotá, Colombia</cbc:Line>
								</cac:AddressLine>
								<cac:Country>
									<cbc:IdentificationCode>CO</cbc:IdentificationCode>
									<cbc:Name languageID = "es">Colombia</cbc:Name>
								</cac:Country>
							</cac:Address>
						</cac:PhysicalLocation>
						<cac:PartyTaxScheme>
							<cbc:RegistrationName>PEOPLE PASS S.A</cbc:RegistrationName>
							<cbc:CompanyID schemeID = "1" schemeName = "31" schemeAgencyID = "195" schemeAgencyName = "CO, DIAN (Dirección de Impuestos y Aduanas Nacionales)">900209956</cbc:CompanyID>
							<!--<cbc:TaxLevelCode >O-15</cbc:TaxLevelCode>Remplazar Si DIAN ajusta en PROD-->
                                                        <cbc:TaxLevelCode listName="48">O-23</cbc:TaxLevelCode> 
							<cac:RegistrationAddress>
								<cbc:ID>11001</cbc:ID>
								<cbc:CityName>BOGOTA</cbc:CityName>
								<cbc:PostalZone>110111</cbc:PostalZone>
								<cbc:CountrySubentity>CUNDINAMARCA</cbc:CountrySubentity>
								<cbc:CountrySubentityCode>11</cbc:CountrySubentityCode>
								<cac:AddressLine>
									<cbc:Line>' . $direccionPeople . '– Bogotá, Colombia</cbc:Line>
								</cac:AddressLine>
								<cac:Country>
									<cbc:IdentificationCode>CO</cbc:IdentificationCode>
									<cbc:Name languageID = "es">Colombia</cbc:Name>
								</cac:Country>
							</cac:RegistrationAddress>
							<cac:TaxScheme>
								<cbc:ID>01</cbc:ID>
								<cbc:Name>IVA</cbc:Name>
							</cac:TaxScheme>
						</cac:PartyTaxScheme>
						<cac:PartyLegalEntity>
							<cbc:RegistrationName>PEOPLE PASS S.A</cbc:RegistrationName>
							<cbc:CompanyID schemeID = "1" schemeName = "31" schemeAgencyID = "195" schemeAgencyName = "CO, DIAN (Dirección de Impuestos y Aduanas Nacionales)">900209956</cbc:CompanyID>
							<cac:CorporateRegistrationScheme>
								<cbc:ID>' . $PrefijoPeople . '</cbc:ID>
							</cac:CorporateRegistrationScheme>
						</cac:PartyLegalEntity>
						<cac:Contact>
                                                	<cbc:Telephone>' . $telefonoEmisor . '</cbc:Telephone>
							<cbc:ElectronicMail>' . $CorreoUsuarioComfiar . '</cbc:ElectronicMail>
						</cac:Contact>
					</cac:Party>
				</cac:AccountingSupplierParty>
				<cac:AccountingCustomerParty>
					<cbc:AdditionalAccountID>' . $AccountID . '</cbc:AdditionalAccountID>
					<cac:Party>
                                        ' . $AddicionalAc . '
						<cac:PartyName>
							<cbc:Name>' . $nombre_cliente . '</cbc:Name>
						</cac:PartyName>
						<cac:PhysicalLocation>
							<cac:Address>
								<cbc:ID>' . $Address_ID . '</cbc:ID>
								<cbc:CityName>' . $ciudad_cliente . '</cbc:CityName>
								<cbc:PostalZone>110111</cbc:PostalZone>
								<cbc:CountrySubentity>' . $departamento . '</cbc:CountrySubentity>
								<cbc:CountrySubentityCode>' . $CountrySubentityCode . '</cbc:CountrySubentityCode>
								<cac:AddressLine>
									<cbc:Line>' . $direccion_cliente . '</cbc:Line>
								</cac:AddressLine>
								<cac:Country>
									<cbc:IdentificationCode>' . $cod_alfa_pais_cli . '</cbc:IdentificationCode>
									<cbc:Name languageID = "es">' . $nombre_pais . '</cbc:Name>
								</cac:Country>
							</cac:Address>
						</cac:PhysicalLocation>
						<cac:PartyTaxScheme>
							<cbc:RegistrationName>' . $nombre_cliente . '</cbc:RegistrationName>
							' . $companyId . '
							' . $taxtCustomer . '
                                                        <cac:RegistrationAddress>
								<cbc:ID>' . $Address_ID . '</cbc:ID>
								<cbc:CityName>' . $ciudad_cliente . '</cbc:CityName>
								<cbc:PostalZone>110111</cbc:PostalZone>
								<cbc:CountrySubentity>' . $departamento . '</cbc:CountrySubentity>
								<cbc:CountrySubentityCode>' . $CountrySubentityCode . '</cbc:CountrySubentityCode>
								<cac:AddressLine>
									<cbc:Line>' . $direccion_cliente . '</cbc:Line>
								</cac:AddressLine>
								<cac:Country>
									<cbc:IdentificationCode>' . $cod_alfa_pais_cli . '</cbc:IdentificationCode>
									<cbc:Name languageID="es">' . $nombre_pais . '</cbc:Name>
								</cac:Country>
							</cac:RegistrationAddress> 
							<cac:TaxScheme>
								<cbc:ID>01</cbc:ID>
								<cbc:Name>IVA</cbc:Name>
							</cac:TaxScheme>
						</cac:PartyTaxScheme>
						<cac:PartyLegalEntity>
							<cbc:RegistrationName>' . $nombre_cliente . '</cbc:RegistrationName>
							' . $companyId . '
						</cac:PartyLegalEntity>
						<cac:Contact>
                                                        <cbc:Telephone>' . $telefono_cliente . '</cbc:Telephone>
							<cbc:ElectronicMail>' . $email_cliente . '</cbc:ElectronicMail>
						</cac:Contact>
                                                ' . $Person . '
					</cac:Party>
                                        <cac:AccountingContact>
						<cbc:Telephone>' . $telefono_cliente . '</cbc:Telephone>
					</cac:AccountingContact>
				</cac:AccountingCustomerParty>
				<!--<cac:Delivery>
					<cbc:ActualDeliveryDate>' . date("Y-m-d", strtotime($fecha_actual . "+ 1 days")) . '</cbc:ActualDeliveryDate>
				</cac:Delivery> -->
				<cac:PaymentMeans>
					<cbc:ID>1</cbc:ID>
					<cbc:PaymentMeansCode>1</cbc:PaymentMeansCode>
				</cac:PaymentMeans>
				<!--<cac:PaymentTerms>
					<cbc:Note>Contado</cbc:Note>
				</cac:PaymentTerms>-->
                                ' . $AllowanceCharge . '
				<cac:TaxTotal>
					<cbc:TaxAmount currencyID = "COP">' . $iva . '</cbc:TaxAmount>
                                        <cbc:RoundingAmount currencyID="COP">0</cbc:RoundingAmount><!--validar ya que se trata de enviar 0.50 y quitarlo al taxAmount pero genera error cufe-->    
					' . $TaxTotalSinIva . '
					' . $TaxTotalConIva . '
				</cac:TaxTotal>
                                <!-- grupo de retenciones  -->
                                ' . $tagGrupoHoldingRteIva . '
                                ' . $tagGrupoHoldingRteIca . '
                                ' . $tagGrupoHoldingRteFte . '
				<cac:LegalMonetaryTotal>
					<cbc:LineExtensionAmount currencyID = "COP">' . $lineExtensionAm . '</cbc:LineExtensionAmount>
					<cbc:TaxExclusiveAmount currencyID = "COP">' . $TaxExclusiveAm . '</cbc:TaxExclusiveAmount>
					<cbc:TaxInclusiveAmount currencyID = "COP">' . $taxInclusive . '</cbc:TaxInclusiveAmount>
                                        <cbc:AllowanceTotalAmount currencyID="COP">' . $totalAllowance . '</cbc:AllowanceTotalAmount>
					<cbc:PayableRoundingAmount currencyID = "COP">0</cbc:PayableRoundingAmount>
					<cbc:PayableAmount currencyID = "COP">' . $payableAmount . '</cbc:PayableAmount>
				</cac:LegalMonetaryTotal>
                            ' . $InvoiceAbonos . '
                            ' . $InvoicePropios . '
			</Invoice>
		</informacionOrganismo>
                 <informacionComfiar>
                    <ruc>900209956</ruc>
                    <codDoc>01</codDoc>
                    <prefixPtoVenta>' . $PrefijoPeople . '</prefixPtoVenta>
                    <nroCbte>' . $nro_factura . '</nroCbte>
                    <Receptores>
                      <Receptor>
                        <Login>PASS' . $nit_cliente . '</Login>
                        <TipoUsuario>2</TipoUsuario>
                        <Nombre>' . $nombre_cliente . '</Nombre>
                        <Mail>' . $correoClienteFacturacion . '</Mail>
                        <Idioma>3</Idioma>
                        <Adjunto>ADJUNTO</Adjunto>
                      </Receptor>
                    </Receptores>
                 </informacionComfiar>
	</Comprobante>
</Comprobantes>
        ');

            if ($xmlValid) {
                $s = simplexml_import_dom($dom);
                $respuesta = $s->asXML();
            } else {
                log_info($this->logHeader . '-' . $this->errorFuncion . 'ERROR CONSTRUYENDO XML FACTURA:: Estructura invalida');
                $respuesta = 404;
            }
        } catch (Exception $e) {
            log_info($this->logHeader . '-' . $this->errorFuncion . 'ERROR CONSTRUYENDO XML FACTURA::' . $e->getMessage());
            $respuesta = 404;
        }
        return $respuesta;
    }

    public function salida_Transaccion($sessionId = null, $fechaVen = null, $transaccionId = null) {
        log_info($this->logHeader . 'INGRESO LIBRERIA salida_Transaccion');
        log_info($this->postData . 'TransaccionId = ' . $transaccionId);

        if (!empty($sessionId) && !empty($fechaVen) && !empty($transaccionId)) {
            $urlWsdl = $this->retornarValorConfiguracion(30);
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
            $cuitProcesar = $this->retornarValorConfiguracion(3);
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

    public function respuesta_Comprobante($sessionId = null, $fechaVen = null, $transaccionId = null, $tipCom = null) {

        log_info($this->logHeader . $this->postData . 'INGRESO LIBRERIA respuesta_Comprobante PK_FACTURA_CODIGO= ' . $transaccionId);


        if (!empty($sessionId) && !empty($fechaVen) && !empty($transaccionId)) {
            $urlWsdl = $this->retornarValorConfiguracion(30);
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
            $cuitProcesar = $this->retornarValorConfiguracion(3);
            if ($tipCom == 1) {
                $puntoVentaId = $this->retornarValorConfiguracion(5);
                $tipoComprobanteId = $this->retornarValorConfiguracion(6);
            } else if ($tipCom == 2) {
                $puntoVentaId = $this->retornarValorConfiguracion(7);
                $tipoComprobanteId = $this->retornarValorConfiguracion(8);
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
                log_info($this->logHeader . $this->soapCorrecto . 'Respuesta_Comprobante::');
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

    //tipo comprobante 01=factura 
    public function descarga_pdf($sessionId = null, $fechaVen = null, $transaccionId = null, $nroComprobante = null, $tipCom = null) {
        log_info($this->logHeader . 'INGRESO LIBRERIA descarga_pdf');
        log_info($this->postData . 'TransaccionId = ' . $transaccionId);

        $codRespuesta = 0;
        if (!empty($sessionId) && !empty($fechaVen) && !empty($transaccionId) && !empty($nroComprobante) && !empty($tipCom)) {
            $urlWsdl = $this->retornarValorConfiguracion(30);
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
            $cuitId = $this->retornarValorConfiguracion(3); //Cuit, RUC o NIT del emisor del comprobante. 
            if ($tipCom == 1) {
                $puntoVentaId = $this->retornarValorConfiguracion(5); //Número de punto de venta a procesar 01 factura
                $tipoComprobanteId = $this->retornarValorConfiguracion(6); //Número del tipo de comprobante a procesar. Ejemplo 01:Factura
                $prefijoPeople = $this->retornarValorConfiguracion(24);
            } else if ($tipCom == 2) {
                $prefijoPeople = $this->retornarValorConfiguracion(25);
                $puntoVentaId = $this->retornarValorConfiguracion(7);
                $tipoComprobanteId = $this->retornarValorConfiguracion(8);
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


            log_info($this->logHeader . $this->postData . 'Id_Tx_Comfiar: ' . $transaccionId .
                    ' cuitId: ' . $cuitId .
                    ' puntoDeVentaId: ' . $puntoVentaId .
                    ' tipoComprobanteId: ' . $tipoComprobanteId .
                    ' numeroComprobante: ' . $nroComprobante .
                    ' SesionId: ' . $sessionId .
                    ' FechaVencimiento: ' . $fechaVen);
            try {
                $responce_param = $client->DescargarPdf($request_param);
                log_info($this->postData . 'RESPUESTA COMSUMO DESCARGARPDF::' . json_encode($responce_param));
                if (isset($responce_param->DescargarPdfResult)) {


                    $b64 = $responce_param->DescargarPdfResult;
                    $data = base64_encode($b64);
                    $urlpublica = $this->db->query("select VALOR_PARAMETRO from modgeneri.gentblpargen where pk_pargen_codigo =96");
                    $urlpublica = $urlpublica->result_array[0];
                    //guarda y genera url factura pdf
                    $folderPath = "uploads/facturacomfiar/";
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

                    log_info($this->soapCorrecto . '::Consumo Correcto soap DescargarPdf::URL PDF COMFIAR::' . $dataReturn);
                    $codRespuesta = 1;
                    $response = $dataReturn;
                } else {
                    $response = 'Error consumo Soap';
                }
            } catch (Exception $e) {
                log_info($this->errorFuncion . 'ERROR SOAP::' . $e->getMessage());

                $response = 'Error consumo DescargarPdf :' . $e->getMessage();
            }
        } else {
            log_info($this->errorFuncion . 'ERROR DATOS NULOS.');
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
            $sqlClienteNota = $this->db->query("SELECT UPPER(factur.CLIENTE_PRIMER_NOMBRE||' '||factur.CLIENTE_SEGUNDO_NOMBRE||' '||factur.CLIENTE_APELLIDO) CLIENTE,
                factur.CLIENTE_PRIMER_NOMBRE
                ,factur.CLIENTE_SEGUNDO_NOMBRE
                ,factur.CLIENTE_APELLIDO
                ,factur.CLIENTE_NUMERO_DOCUMENTO NIT
                ,REPLACE (factur.CLIENTE_DIRECCION,'|',' ') DIRECCION
                ,NVL(factur.CLIENTE_CIUDAD,'BOGOTA') CIUDAD
                ,NVL(factur.CLIENTE_DEPARTAMENTO,'BOGOTA, D.C') DEPARTAMENTO
                ,factur.CLIENTE_TIPO_PERSONA
                ,factur.CLIENTE_TELEFONO TELEFONO
                ,factur.pk_factur_codigo
                ,factur.CLIENTE_TIPO_DOCUMENTO
                ,TO_CHAR(factur.FECHA_CREACION,'DD/MM/YYYY HH24:MM:SS') FECHA_FACTURA
                ,factur.NUMERO_FACTURA
                ,factur.PK_ENT_CODIGO
                ,TO_CHAR(factur.FECHA_CREACION,'DD/MM/YYYY') FECHA_FACT
                ,xmlcom.issuedate
                ,xmlcom.id_comprobante
                ,xmlcom.cufe_factura
                ,factur.CLIENTE_EMAIL
                ,SUBSTR(NVL(CIU.CODIGO_DANE,11001), 1,2) CountrySubentityCode
                ,NVL(CIU.CODIGO_DANE,11001) AddressID,
                pais.codigo_isis,
                pais.nombre pais,
                pais.CODIGO_ALFA_2
                ,TO_CHAR(nota.FECHA_CREACION,'HH24:MI:SS') HORA_NOTA
                ,TO_CHAR(nota.FECHA_CREACION,'YYYY-MM-DD') FECHA_NOTA
                        FROM MODFACTUR.FACTBLNOTA nota
                JOIN MODFACTUR.FACTBLFACTUR factur
                ON nota.pk_factur_codigo=factur.pk_factur_codigo
                JOIN MODCLIUNI.CLITBLENTIDA ENT
                ON factur.PK_ENT_CODIGO = ENT.PK_ENT_CODIGO
                JOIN MODCLIUNI.CLITBLCIUDAD CIU
                ON ENT.CLITBLCIUDAD_PK_CIU_CODIGO = CIU.PK_CIU_CODIGO
                JOIN MODCLIUNI.CLITBLDEPPAI dep 
                ON dep.PK_DEP_CODIGO = CIU.CLITBLDEPPAI_PK_DEP_CODIGO
                JOIN MODCLIUNI.CLITBLPAIS pais 
                ON pais.PK_PAIS_CODIGO = dep.CLITBLPAIS_PK_PAIS_CODIGO
                JOIN MODFACTUR.factblxmlcomfiar xmlcom 
                ON factur.pk_factur_codigo =xmlcom.pk_factura_codigo
                and nota.pk_nota_codigo= $pk_nota_codigo and xmlcom.PK_TIPO_XML_CODIGO =1");
            $ClienteNota = $sqlClienteNota->result_array[0];
            $nombre_cliente = str_replace("&", "&amp;", $ClienteNota['CLIENTE']);
            $tipo_persona = $ClienteNota['CLIENTE_TIPO_PERSONA'];
            $nit_cliente = $ClienteNota['NIT'];
            $tipoDoc_Cliente = $ClienteNota['CLIENTE_TIPO_DOCUMENTO'];
            $direccion_cliente = $ClienteNota['DIRECCION'];
            $ciudad_cliente = $ClienteNota['CIUDAD'];
            $departamento_cliente = $ClienteNota['DEPARTAMENTO'];
            $telefono_cliente = $ClienteNota['TELEFONO'];
            $issudateFact = $ClienteNota['ISSUEDATE'];
            $idComprobanteCom = $ClienteNota['ID_COMPROBANTE'];
            $cufe = $ClienteNota['CUFE_FACTURA'];
            $primer_nombre_cliente = str_replace("&", "&amp;", $ClienteNota['CLIENTE_PRIMER_NOMBRE']);
            $segundo_nombre_cliente = str_replace("&", "&amp;", $ClienteNota['CLIENTE_SEGUNDO_NOMBRE']);
            $apellido_cliente = $ClienteNota['CLIENTE_APELLIDO'];
            $pk_entidad = $ClienteNota['PK_ENT_CODIGO'];
            $email_cliente = $ClienteNota['CLIENTE_EMAIL'];
            $NotaCountrySubentityCode = $ClienteNota['COUNTRYSUBENTITYCODE'];
            $NotaAddress_ID = $ClienteNota['ADDRESSID'];
            $codigo_isis_nota = $ClienteNota['CODIGO_ISIS'];
            $pais_nota = $ClienteNota['PAIS'];
            $fecha_nota = $ClienteNota['FECHA_NOTA'];
            $hora_nota = $ClienteNota['HORA_NOTA'];

            $sqldirPeople = $this->db->query("select VALOR_PARAMETRO from modgeneri.gentblpargen where PK_PARGEN_CODIGO = 59");
            $direccionPeople = $sqldirPeople->result_array[0]['VALOR_PARAMETRO'];
            $sqltelPeople = $this->db->query("select VALOR_PARAMETRO from modgeneri.gentblpargen where PK_PARGEN_CODIGO = 50");
            $telefonoEmisor = $sqltelPeople->result_array[0]['VALOR_PARAMETRO'];

            $cod_alfa_pais_cli = $ClienteNota['CODIGO_ALFA_2'];
            
            $sqldetallenota = $this->db->query(" SELECT UPPER(conc.nombre||' - '||detnot.detalle) NOMBRE,detnot.pk_produc_codigo, detnot.cantidad ,detnot.valor_unitario total, porcentaje_iva, indicador_abono
                    FROM modfactur.factbldetnot detnot
                    JOIN modfactur.factblconnot conc
                    ON detnot.pk_concepto_codigo = conc.pk_connot_codigo
                    where detnot.pk_nota_codigo=$pk_nota_codigo order by 1 asc");
            $DetalleNota = $sqldetallenota->result_array;

            //datos conf COMFIAR
            $resolPeoople = $this->retornarValorConfiguracion(19);
            $inicioResol = $this->retornarValorConfiguracion(20);
            $finResol = $this->retornarValorConfiguracion(21);
            $rangoInicio = $this->retornarValorConfiguracion(22);
            $rangoFin = $this->retornarValorConfiguracion(23);
            $PrefijoPeopleNC = $this->retornarValorConfiguracion(25);
            $ambienteComfiar = $this->retornarValorConfiguracion(1);
            $CorreoUsuarioComfiar = $this->retornarValorConfiguracion(26);
            //$porcIva = $this->retornarValorConfiguracion(15);

            $correoClienteFacturacion = empty($email_cliente) ? $CorreoUsuarioComfiar : $email_cliente;


            $schemeNameCliente = 0;
            if ($tipoDoc_Cliente == 'NIT JURIDICO') {
                $schemeNameCliente = 31;
            } else if ($tipoDoc_Cliente == 'TARJETA IDENTIDAD') {
                $schemeNameCliente = 12;
            } else if ($tipoDoc_Cliente == 'CEDULA DE CIUDADANIA') {
                $schemeNameCliente = 13;
            } else if ($tipoDoc_Cliente == 'PASAPORTE') {
                $schemeNameCliente = 41;
            } else if ($tipoDoc_Cliente == 'CEDULA EXTRANJERIA') {
                $schemeNameCliente = 22;
            } else if ($tipoDoc_Cliente == 'NIT NATURAL') {
                $schemeNameCliente = 31;
            } else if ($tipoDoc_Cliente == 'REGISTRO CIVIL') {
                $schemeNameCliente = 11;
            }
            //traer digito verificacion cliente
            $sqlDigitoVer = $this->db->query(" SELECT NVL(ent.digito_verificacion,0) DIGITO_VERIFICACION FROM
            modcliuni.clitblentida ent
            WHERE ent.pk_ent_codigo = $pk_entidad");

            $DigitoVer = $sqlDigitoVer->result_array[0]['DIGITO_VERIFICACION'];


            if ($codigo_isis_nota != '170') {
                $schemeNameCliente = 42;
                $NotaCountrySubentityCode = '';
                $NotaAddress_ID = '';
            }

            $AddicionalAc = '';
            $taxtCustomer = '';
            $Person = '';
            if ($tipo_persona == 'NATURAL') {
                $AccountID = 2;
                $Person = '<cac:Person>
				<cbc:ID>' . $nit_cliente . '</cbc:ID>
				<cbc:FirstName>' . $primer_nombre_cliente . '</cbc:FirstName>
				<cbc:FamilyName>' . $apellido_cliente . '</cbc:FamilyName>
				<cbc:MiddleName>' . $segundo_nombre_cliente . '</cbc:MiddleName>
                            </cac:Person>';
                $AddicionalAc = '<cac:PartyIdentification>
                            <cbc:ID schemeName="13" schemeAgencyID="195"  schemeAgencyName="CO, DIAN (Dirección de Impuestos y Aduanas Nacionales)">' . $nit_cliente . '</cbc:ID>
                        </cac:PartyIdentification>';
//                $taxtCustomer = '<cbc:TaxLevelCode listName="No aplica">R-99-PN</cbc:TaxLevelCode>';
                $taxtCustomer = '<cbc:TaxLevelCode listName = "49">R-99-PN</cbc:TaxLevelCode>';
            } else if ($tipo_persona == 'JURIDICA') {
                $AccountID = 1;
                $Person = '';
//                $taxtCustomer = '<cbc:TaxLevelCode listName="No aplica">O-47</cbc:TaxLevelCode>';
                $taxtCustomer = '<cbc:TaxLevelCode listName = "48">O-47</cbc:TaxLevelCode>';
            } else {
                $AccountID = 1;
                $Person = '';
//                $taxtCustomer = '<cbc:TaxLevelCode listName="No aplica">R-99-PN</cbc:TaxLevelCode>';
                $taxtCustomer = '<cbc:TaxLevelCode listName = "49">R-99-PN</cbc:TaxLevelCode>';
            }
            $companyId = '';
            if ($schemeNameCliente == 31 || $schemeNameCliente == 42) {
                $companyId = '<cbc:CompanyID schemeID = "' . $DigitoVer . '" schemeName = "' . $schemeNameCliente . '" schemeAgencyID = "195" schemeAgencyName = "CO, DIAN (Dirección de Impuestos y Aduanas Nacionales)">' . $nit_cliente . '</cbc:CompanyID>';
            } else {
                $companyId = '<cbc:CompanyID  schemeName = "13" schemeAgencyID = "195" schemeAgencyName = "CO, DIAN (Dirección de Impuestos y Aduanas Nacionales)">' . $nit_cliente . '</cbc:CompanyID>';
            }


            $idProduc = 1;
            $cantNote = 0;
            $CalculoIva = 0;
            $lineExtensionAm = 0;
            $payableAmount = 0;
            $TaxExclusiveAm = 0;
            $subTotalIngPro = 0;
            $taxSubtotalPro = '';
            $creditNotLine = '';
//            $totalSinIva = 0;
            $totalConIva = 0;
            $taxSubtotalSinIva = '';
            $taxSubtotalConIva = '';

//        $creditNotLine;
            foreach ($DetalleNota as $value) {
                $porcIva = $value['PORCENTAJE_IVA'];
                if ($porcIva == '0') {
                    $TaxableSinIva += $value['TOTAL'] * $value['CANTIDAD'];
                } else if ($porcIva == '19') {
                    $TaxableConIva += $value['TOTAL'] * $value['CANTIDAD'];
                    $calculoConIva = round(($value['TOTAL'] * $value['CANTIDAD']) * ($porcIva / 100), 2);
                    $totalConIva += $calculoConIva;
                    $porIva = $porcIva;
                }
            }
            if ($TaxableSinIva != 0) {
                $taxSubtotalSinIva = '<cac:TaxSubtotal>
                                    <cbc:TaxableAmount currencyID="COP">' . $TaxableSinIva . '</cbc:TaxableAmount>
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

            if ($totalConIva != 0) {
                $taxSubtotalConIva = '<cac:TaxSubtotal>
                                    <cbc:TaxableAmount currencyID="COP">' . $TaxableConIva . '</cbc:TaxableAmount>
                                    <cbc:TaxAmount currencyID="COP">' . $totalConIva . '</cbc:TaxAmount>
                                    <cac:TaxCategory>
                                        <cbc:Percent>' . $porIva . '.00</cbc:Percent>
                                        <cac:TaxScheme>
                                            <cbc:ID>01</cbc:ID>
                                            <cbc:Name>IVA</cbc:Name>
                                        </cac:TaxScheme>
                                    </cac:TaxCategory>
                                </cac:TaxSubtotal>';
            }



            $subtotalTerceros = 0;
            $totalPropios = 0;
            $subTotalIngrePropios = 0;
            foreach ($DetalleNota as $value) {
                if ($value['TOTAL'] == 0) {

                    $costoRegalo = 1;


                    $PricingReference = '<cac:PricingReference>
                                            <cac:AlternativeConditionPrice>
                                            <!--Falta traer valor producto  -->
                                                <cbc:PriceAmount currencyID="COP">' . $costoRegalo . '</cbc:PriceAmount>
                                                <cbc:PriceTypeCode>01</cbc:PriceTypeCode> 
                                            </cac:AlternativeConditionPrice>
                                          </cac:PricingReference>';
                } else {
                    $PricingReference = '';
                }


                $indicador_abono = $value['INDICADOR_ABONO'];
                $modelName = $indicador_abono == 1 ? 1 : 2;
                $ivaPro = $value['PORCENTAJE_IVA'];
                $valIva = round(($value['TOTAL'] * $value['CANTIDAD']) * ($ivaPro / 100), 2);
                $lineExte = $value['TOTAL'] * $value['CANTIDAD'];
                $creditNotLine = $creditNotLine . '<cac:CreditNoteLine>
                    <cbc:ID>' . $idProduc++ . '</cbc:ID>
                    <cbc:UUID schemeAgencyID="195" schemeAgencyName="CO, DIAN (Dirección de Impuestos y Aduanas Nacionales)">' . $cufe . '</cbc:UUID>
                    <cbc:CreditedQuantity unitCode="94">' . $value['CANTIDAD'] . '</cbc:CreditedQuantity>
                    <cbc:LineExtensionAmount currencyID="COP">' . $lineExte . '</cbc:LineExtensionAmount>
                    ' . $PricingReference . '
                    <cac:TaxTotal>
                        <cbc:TaxAmount currencyID="COP">' . $valIva . '</cbc:TaxAmount>
                        <cbc:RoundingAmount currencyID="COP">0</cbc:RoundingAmount><!--validar ya que se trata de enviar 0.50 y quitarlo al taxAmount pero genera error cufe-->
                        <cac:TaxSubtotal>
                            <cbc:TaxableAmount currencyID="COP">' . $value['TOTAL'] * $value['CANTIDAD'] . '</cbc:TaxableAmount>
                            <cbc:TaxAmount currencyID="COP">' . $valIva . '</cbc:TaxAmount>
                            <cac:TaxCategory>
                                <cbc:Percent>' . $ivaPro . '.00</cbc:Percent>
                                <cac:TaxScheme>
                                    <cbc:ID>01</cbc:ID>
                                    <cbc:Name>IVA</cbc:Name>
                                </cac:TaxScheme>
                            </cac:TaxCategory>
                        </cac:TaxSubtotal>
                    </cac:TaxTotal>
                    <cac:Item>
                        <cbc:Description>' . $value['NOMBRE'] . '</cbc:Description>
                        <cbc:ModelName>' . $modelName . '</cbc:ModelName>
                        <cac:SellersItemIdentification>
                            <cbc:ID>' . $value['PK_PRODUC_CODIGO'] . '</cbc:ID>
                        </cac:SellersItemIdentification>
                    </cac:Item>
                    <cac:Price>
                        <cbc:PriceAmount currencyID="COP">' . $value['TOTAL'] . '</cbc:PriceAmount>
                        <cbc:BaseQuantity unitCode="94">' . $value['CANTIDAD'] . '</cbc:BaseQuantity>
                        <cbc:PriceTypeCode>COP</cbc:PriceTypeCode>
                    </cac:Price>
                </cac:CreditNoteLine>';
                $cantNote++;
                if ($modelName == 1) {
                    $subtotalTerceros += $lineExte;
                } elseif ($modelName == 2) {
                    $totalPropios += $lineExte + $valIva;
                    $subTotalIngrePropios += $lineExte;
                }
                $CalculoIva += ($value['TOTAL'] * $value['CANTIDAD']) * ($ivaPro / 100);
                $lineExtensionAm += $lineExte;
                $TaxExclusiveAm += $lineExte;
                $subTotalIngPro += $lineExte;
            }
            //Impuestos Nota credito
            $sqlImpuestosNota = $this->db->query("select 
						MODFACTUR.facpkgconsultas.fncconsultarimpuestonotacre(parpknota=>NOTA.pk_nota_codigo,parnombreimpuesto=>'IVA%') IVA,
						MODFACTUR.facpkgconsultas.fncconsultarimpuestonotacre(parpknota=>NOTA.pk_nota_codigo,parnombreimpuesto=>'RTE FTE%') RTE_FUENTE,
						MODFACTUR.facpkgconsultas.fncconsultarimpuestonotacre(parpknota=>NOTA.pk_nota_codigo,parnombreimpuesto=>'RTE ICA%') RTE_ICA,
						MODFACTUR.facpkgconsultas.fncconsultarimpuestonotacre(parpknota=>NOTA.pk_nota_codigo,parnombreimpuesto=>'RTE IVA%') RTE_IVA
						from MODFACTUR.factblnota nota
						where nota.pk_nota_codigo =$pk_nota_codigo");
            $valorimpuestos = $sqlImpuestosNota->result_array[0];

            $ValorRte_fuente = empty($valorimpuestos['RTE_FUENTE']) ? 0 : $valorimpuestos['RTE_FUENTE'];
            $ValorRte_ica = empty($valorimpuestos['RTE_ICA']) ? 0 : $valorimpuestos['RTE_ICA'];
            $ValorRte_iva = empty($valorimpuestos['RTE_IVA']) ? 0 : $valorimpuestos['RTE_IVA'];

            //RETE IVA 
            $sqlrteIva = $this->db->query("SELECT imp.PROCENTAJE,
            case imp.PROCENTAJE 
            WHEN 0 then
            0
            else
            round(((imp.VALOR_DEVUELTO*100)/imp.PROCENTAJE),5) 
            end BASEAMOUNT,
            imp.IMPUESTO ,
            imp.VALOR_DEVUELTO AMOUNT
            FROM MODFACTUR.FACTBLIMPNOTCRE imp
            JOIN modfactur.factbldetnot dnota
            on imp.pk_detnot_codigo = dnota.pk_detnot_codigo
            JOIN modfactur.factblnota nota
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
            foreach ($RteIva as $value) {
                if ($value['PROCENTAJE'] != 0) {
                    $porcRteIva = $value['PROCENTAJE'];
                    $nomImpuesto = $value['IMPUESTO'];
                    $AmountRteIva = $value['AMOUNT'];
                    $BaseAmountRteIva = $value['BASEAMOUNT'];
                    $AllowanceCharge = $AllowanceCharge . '<cac:AllowanceCharge>
					<cbc:ID>' . $idAllowance . '</cbc:ID>
					<cbc:ChargeIndicator>false</cbc:ChargeIndicator>
					<cbc:AllowanceChargeReasonCode>01</cbc:AllowanceChargeReasonCode>
					<cbc:AllowanceChargeReason>Descuento Impuesto ' . $nomImpuesto . '</cbc:AllowanceChargeReason>
					<cbc:MultiplierFactorNumeric>' . $porcRteIva . '</cbc:MultiplierFactorNumeric>
					<cbc:Amount currencyID="COP">' . $AmountRteIva . '</cbc:Amount>
					<cbc:BaseAmount currencyID="COP">' . $BaseAmountRteIva . '</cbc:BaseAmount>
				 </cac:AllowanceCharge>';
                    $idAllowance++;
                    $montoRteIva += $AmountRteIva;
                }
            }

            //RteFTE
            $sqlrteFte = $this->db->query("SELECT imp.PROCENTAJE,
            case imp.PROCENTAJE 
            WHEN 0 then
            0
            else
            round(((imp.VALOR_DEVUELTO*100)/imp.PROCENTAJE),5) 
            end BASEAMOUNT, 
            imp.IMPUESTO ,
            imp.VALOR_DEVUELTO AMOUNT
            FROM MODFACTUR.FACTBLIMPNOTCRE imp
            JOIN modfactur.factbldetnot dnota
            on imp.pk_detnot_codigo = dnota.pk_detnot_codigo
            JOIN modfactur.factblnota nota
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
                if ($value['PROCENTAJE'] != 0) {
                    $porcRteFte = $value['PROCENTAJE'];
                    $nomImpuestoRteFte = $value['IMPUESTO'];
                    $AmountRteFte = $value['AMOUNT'];
                    $BaseAmountRteFte = $value['BASEAMOUNT'];
                    $AllowanceCharge = $AllowanceCharge . '<cac:AllowanceCharge>
					<cbc:ID>' . $idAllowance . '</cbc:ID>
					<cbc:ChargeIndicator>false</cbc:ChargeIndicator>
					<cbc:AllowanceChargeReasonCode>01</cbc:AllowanceChargeReasonCode>
					<cbc:AllowanceChargeReason>Descuento Impuesto ' . $nomImpuestoRteFte . '</cbc:AllowanceChargeReason>
					<cbc:MultiplierFactorNumeric>' . $porcRteFte . '</cbc:MultiplierFactorNumeric>
					<cbc:Amount currencyID="COP">' . $AmountRteFte . '</cbc:Amount>
					<cbc:BaseAmount currencyID="COP">' . $BaseAmountRteFte . '</cbc:BaseAmount>
				 </cac:AllowanceCharge>';
                    $idAllowance++;
                    $montoRteFte += $AmountRteFte;
                }
            }
            //RteIca
            $sqlrteIca = $this->db->query("SELECT imp.PROCENTAJE,
            case imp.PROCENTAJE 
            WHEN 0 then
            0
            else
            round(((imp.VALOR_DEVUELTO*100)/imp.PROCENTAJE),5) 
            end BASEAMOUNT,
            imp.IMPUESTO ,
            imp.VALOR_DEVUELTO AMOUNT
            FROM MODFACTUR.FACTBLIMPNOTCRE imp
            JOIN modfactur.factbldetnot dnota
            on imp.pk_detnot_codigo = dnota.pk_detnot_codigo
            JOIN modfactur.factblnota nota
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
                if ($value['PROCENTAJE'] != 0) {
                    $porcRteIca = $value['PROCENTAJE'];
                    $nomImpuestoRteIca = $value['IMPUESTO'];
                    $AmountRteIca = $value['AMOUNT'];
                    $BaseAmountRteIca = $value['BASEAMOUNT'];
                    $AllowanceCharge = $AllowanceCharge . '<cac:AllowanceCharge>
					<cbc:ID>' . $idAllowance . '</cbc:ID>
					<cbc:ChargeIndicator>false</cbc:ChargeIndicator>
					<cbc:AllowanceChargeReasonCode>01</cbc:AllowanceChargeReasonCode>
					<cbc:AllowanceChargeReason>Descuento Impuesto ' . $nomImpuestoRteIca . '</cbc:AllowanceChargeReason>
					<cbc:MultiplierFactorNumeric>' . $porcRteIca . '</cbc:MultiplierFactorNumeric>
					<cbc:Amount currencyID="COP">' . $AmountRteIca . '</cbc:Amount>
					<cbc:BaseAmount currencyID="COP">' . $BaseAmountRteIca . '</cbc:BaseAmount>
				 </cac:AllowanceCharge>';
                    $idAllowance++;
                    $montoRteIca += $AmountRteIca;
                }
            }
        }
        $totalIngresosPropios = $subTotalIngPro + $CalculoIva;

        $payableAmount = $lineExtensionAm + $CalculoIva;
        $taxInclusive = $lineExtensionAm + $CalculoIva;
        $totalAllowance = $montoRteIva + $montoRteFte + $montoRteIca;
        $payableAmount = $payableAmount - $totalAllowance;
        $totalApagar = $totalIngresosPropios - $totalAllowance;
        $totalPropios = $totalPropios - $totalAllowance;
        $dom = new DOMDocument;
        $dom->preserveWhiteSpace = false;
//        $dom->formatOutput = true;
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
                                    <sts:InvoiceAuthorization>' . $resolPeoople . '</sts:InvoiceAuthorization>
                                    <sts:AuthorizationPeriod>
                                        <cbc:StartDate>' . $inicioResol . '</cbc:StartDate>
                                        <cbc:EndDate>' . $finResol . '</cbc:EndDate>
                                    </sts:AuthorizationPeriod>
                                    <sts:AuthorizedInvoices>
                                        <sts:Prefix>' . $PrefijoPeopleNC . '</sts:Prefix>
                                        <sts:From>' . $rangoInicio . '</sts:From>
                                        <sts:To>' . $rangoFin . '</sts:To>
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
                <cbc:ProfileExecutionID>' . $ambienteComfiar . '</cbc:ProfileExecutionID>
                <cbc:ID>' . $PrefijoPeopleNC . $pk_nota_codigo . '</cbc:ID>
                <cbc:UUID schemeID="' . $ambienteComfiar . '" schemeName="CUDE-SHA384" />
                <cbc:IssueDate>' . $fecha_nota. '</cbc:IssueDate>
                <cbc:IssueTime>' .$hora_nota. '-05:00' . '</cbc:IssueTime>
                <cbc:CreditNoteTypeCode>91</cbc:CreditNoteTypeCode>
                <cbc:Note>' . round($totalApagar) . '</cbc:Note><!-- Nota 1 total a pagar-->
                <cbc:Note>' . round($totalPropios) . '</cbc:Note><!-- Nota 2 Total Ingresos propios-->
                <cbc:Note>' . round($subtotalTerceros) . '</cbc:Note><!-- Nota 3 Total Ingresos tercerros-->
                <cbc:Note>Impuestos sobre las ventas - IVA 
Persona Jurídica. Actividad económica 8220 Tarifa ICA 9.66x1.000. No somos Grandes Contribuyentes. SOMOS AUTORRETENEDORES DE RENTA según Resolución No 000976 del 12 de febrero del 2020. Resolución gráfica de la factura electrónica según parágrafo 1 articulo 3 decreto 2242 de 2015.


Vigencia 12 Meses.</cbc:Note>
                <cbc:Note>' . round($subTotalIngrePropios) . '</cbc:Note><!-- Nota 5 Subtotal ingresos propios-->
                <cbc:Note>' . $ValorRte_fuente . '</cbc:Note><!-- Nota 6 Rete FTE-->
                <cbc:Note>' . $ValorRte_ica . '</cbc:Note><!-- Nota 7 Rete ICA-->
                <cbc:Note>' . $ValorRte_iva . '</cbc:Note><!-- Nota 8 Rete IVA-->
                <cbc:DocumentCurrencyCode>COP</cbc:DocumentCurrencyCode>
                <cbc:LineCountNumeric>' . $cantNote . '</cbc:LineCountNumeric>
                <cac:BillingReference>
                    <cac:InvoiceDocumentReference>
                        <cbc:ID>' . $idComprobanteCom . '</cbc:ID>
                        <cbc:UUID schemeName="CUFE-SHA384">' . $cufe . '</cbc:UUID>
                        <cbc:IssueDate>' . $issudateFact . '</cbc:IssueDate>
                    </cac:InvoiceDocumentReference>
                </cac:BillingReference>
                <cac:AccountingSupplierParty>
                    <cbc:AdditionalAccountID>1</cbc:AdditionalAccountID>
                    <cac:Party>
                        <cac:PartyName>
                            <cbc:Name>PEOPLEPASS S.A</cbc:Name>
                        </cac:PartyName>
                        <cac:PhysicalLocation>
                            <cac:Address>
                                <cbc:ID>11001</cbc:ID>
                                <cbc:CityName>BOGOTA</cbc:CityName>
                                <cbc:PostalZone>110111</cbc:PostalZone>
                                <cbc:CountrySubentity>CUNDINAMARCA</cbc:CountrySubentity>
                                <cbc:CountrySubentityCode>11</cbc:CountrySubentityCode>
                                <cac:AddressLine>
                                    <cbc:Line>' . $direccionPeople . '– Bogotá, Colombia</cbc:Line>
                                </cac:AddressLine>
                                <cac:Country>
                                    <cbc:IdentificationCode>CO</cbc:IdentificationCode>
                                    <cbc:Name languageID="es">Colombia</cbc:Name>
                                </cac:Country>
                            </cac:Address>
                        </cac:PhysicalLocation>
                        <cac:PartyTaxScheme>
                            <cbc:RegistrationName>PEOPLE PASS S.A</cbc:RegistrationName>
                            <cbc:CompanyID schemeID="1" schemeName="31" schemeAgencyID="195" schemeAgencyName="CO, DIAN (Dirección de Impuestos y Aduanas Nacionales)">900209956</cbc:CompanyID>
                            <!--<cbc:TaxLevelCode listName="No aplica">O-15</cbc:TaxLevelCode> Remplazar cuando la DIAN ya tenga ajuste en PROD -->
                            <cbc:TaxLevelCode listName="48">O-23</cbc:TaxLevelCode>
                            <cac:RegistrationAddress>
                                <cbc:ID>11001</cbc:ID>
                                <cbc:CityName>BOGOTA</cbc:CityName>
                                <cbc:PostalZone>110111</cbc:PostalZone>
                                <cbc:CountrySubentity>CUNDINAMARCA</cbc:CountrySubentity>
                                <cbc:CountrySubentityCode>11</cbc:CountrySubentityCode>
                                <cac:AddressLine>
                                    <cbc:Line>' . $direccionPeople . '– Bogotá, Colombia</cbc:Line>
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
                            <cbc:RegistrationName>PEOPLE PASS S.A</cbc:RegistrationName>
                            <cbc:CompanyID schemeID="1" schemeName="31" schemeAgencyID="195" schemeAgencyName="CO, DIAN (Dirección de Impuestos y Aduanas Nacionales)">900209956</cbc:CompanyID>
                            <cac:CorporateRegistrationScheme>
                                <cbc:ID>' . $PrefijoPeopleNC . '</cbc:ID>
                            </cac:CorporateRegistrationScheme>
                        </cac:PartyLegalEntity>
                        <cac:Contact>
                            <cbc:Telephone>' . $telefonoEmisor . '</cbc:Telephone>
                            <cbc:ElectronicMail>' . $CorreoUsuarioComfiar . '</cbc:ElectronicMail>
                        </cac:Contact>
                    </cac:Party>
                </cac:AccountingSupplierParty>
                <cac:AccountingCustomerParty>
                    <cbc:AdditionalAccountID>' . $AccountID . '</cbc:AdditionalAccountID>
                    <cac:Party>
                     ' . $AddicionalAc . '
                        <cac:PartyName>
                            <cbc:Name>' . $nombre_cliente . '</cbc:Name>
                        </cac:PartyName>
                        <cac:PhysicalLocation>
                            <cac:Address>
                                <cbc:ID>' . $NotaAddress_ID . '</cbc:ID>
                                <cbc:CityName>' . $ciudad_cliente . '</cbc:CityName>
                                <cbc:PostalZone>110111</cbc:PostalZone>
                                <cbc:CountrySubentity>' . $departamento_cliente . '</cbc:CountrySubentity>
                                <cbc:CountrySubentityCode>' . $NotaCountrySubentityCode . '</cbc:CountrySubentityCode>
                                <cac:AddressLine>
                                    <cbc:Line>' . $direccion_cliente . '</cbc:Line>
                                </cac:AddressLine>
                                <cac:Country>
                                    <cbc:IdentificationCode>' . $cod_alfa_pais_cli . '</cbc:IdentificationCode>
                                    <cbc:Name languageID="es">' . $pais_nota . '</cbc:Name>
                                </cac:Country>
                            </cac:Address>
                        </cac:PhysicalLocation>
                        <cac:PartyTaxScheme>
                            <cbc:RegistrationName>' . $nombre_cliente . '</cbc:RegistrationName>
                           ' . $companyId . '
			   ' . $taxtCustomer . '
               <cac:RegistrationAddress>
			<cbc:ID>' . $NotaAddress_ID . '</cbc:ID>
                            <cbc:CityName>' . $ciudad_cliente . '</cbc:CityName>
                            <cbc:PostalZone>110111</cbc:PostalZone>
                            <cbc:CountrySubentity>' . $departamento_cliente . '</cbc:CountrySubentity>
                            <cbc:CountrySubentityCode>' . $NotaCountrySubentityCode . '</cbc:CountrySubentityCode>
				<cac:AddressLine>
                                    <cbc:Line>' . $direccion_cliente . '</cbc:Line>
				</cac:AddressLine>
			<cac:Country>
				<cbc:IdentificationCode>' . $cod_alfa_pais_cli . '</cbc:IdentificationCode>
				<cbc:Name languageID="es">' . $pais_nota . '</cbc:Name>
			</cac:Country>
		</cac:RegistrationAddress>
                            <cac:TaxScheme>
                                <cbc:ID>01</cbc:ID>
                                <cbc:Name>IVA</cbc:Name>
                            </cac:TaxScheme>
                        </cac:PartyTaxScheme>
                        <cac:PartyLegalEntity>
                            <cbc:RegistrationName>' . $nombre_cliente . '</cbc:RegistrationName>
                                ' . $companyId . '
                             <cac:CorporateRegistrationScheme>
                                <cbc:Name />
                            </cac:CorporateRegistrationScheme>
                        </cac:PartyLegalEntity>
                        <cac:Contact>
                            <cbc:Telephone>' . $telefono_cliente . '</cbc:Telephone>
                            <cbc:ElectronicMail />
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
                    <cbc:PaymentDueDate>' . date("Y-m-d") . '</cbc:PaymentDueDate>
		</cac:PaymentMeans>
                ' . $AllowanceCharge . '
                <cac:TaxTotal>
			<cbc:TaxAmount currencyID = "COP">' . $CalculoIva . '</cbc:TaxAmount>
                        <cbc:RoundingAmount currencyID="COP">0</cbc:RoundingAmount><!--validar ya que se trata de enviar 0.50 y quitarlo al taxAmount pero genera error cufe-->
			' . $taxSubtotalSinIva . '
			' . $taxSubtotalConIva . '
		</cac:TaxTotal>
                <cac:LegalMonetaryTotal>
                    <cbc:LineExtensionAmount currencyID="COP">' . $lineExtensionAm . '</cbc:LineExtensionAmount>
                    <cbc:TaxExclusiveAmount currencyID="COP">' . $TaxExclusiveAm . '</cbc:TaxExclusiveAmount>
                    <cbc:TaxInclusiveAmount currencyID="COP">' . $taxInclusive . '</cbc:TaxInclusiveAmount>
                    <cbc:AllowanceTotalAmount currencyID="COP">' . $totalAllowance . '</cbc:AllowanceTotalAmount>
                    <cbc:PayableAmount currencyID="COP">' . $payableAmount . '</cbc:PayableAmount>
                </cac:LegalMonetaryTotal>
                ' . $creditNotLine . '
            </CreditNote>
        </informacionOrganismo>
        <informacionComfiar>
            <ruc>900209956</ruc>
            <codDoc>04</codDoc>
            <prefixPtoVenta>' . $PrefijoPeopleNC . '</prefixPtoVenta>
            <nroCbte>' . $pk_nota_codigo . '</nroCbte>
            <Receptores>
              <Receptor>
                <Login>PASS' . $nit_cliente . '</Login>
                <TipoUsuario>2</TipoUsuario>
                <Nombre>' . $nombre_cliente . '</Nombre>
                <Mail>' . $correoClienteFacturacion . '</Mail>
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
    public function retornarValorConfiguracion($pk_conf_codigo) {
        log_info($this->logHeader . $this->postData . 'ENTRA retornarValorConfiguracion PK_CONF_CODIGO= ' . $pk_conf_codigo);
        if (!empty($pk_conf_codigo)) {
            if ($pk_conf_codigo == 17) {
                $sql = "BEGIN modfactur.facpkgdatacomfiar.prcpasscomfiar(
                        parpkconfcomfiar=>:parpkconfcomfiar, 
                        parcontrasena=>:parcontrasena, 
                        parrespuesta=>:parrespuesta
                        );
                        END;";

                $conn = $this->db->conn_id;
                $stmt = oci_parse($conn, $sql);
                oci_bind_by_name($stmt, ':parpkconfcomfiar', $pk_conf_codigo, 32);
                oci_bind_by_name($stmt, ':parcontrasena', $contrasena, 32);
                oci_bind_by_name($stmt, ':parrespuesta', $parrespuesta, 32);
                if (!oci_execute($stmt)) {
                    $e = oci_error($stmt);
                    VAR_DUMP($e);
                    log_info($this->errorFuncion . 'Error consumiendo PROCEDURE prcpasscomfiar , en funcion -retornarValorConfiguracion-' . e);
                } if ($parrespuesta == 1) {
                    $ValorParametroReturn = $contrasena;
                    log_info($this->logHeader . 'Consumo Correcto!! PROCEDURE prcpasscomfiar , en funcion -retornarValorConfiguracion-');
                }
            } else {
                $sqlconfigComfiar = $this->db->query("select VALOR_PARAMETRO from MODFACTUR.FACTBLCONFCOMFIAR where pk_conf_codigo = $pk_conf_codigo");
                $valorParametro = $sqlconfigComfiar->result_array[0];
                $ValorParametroReturn = $valorParametro['VALOR_PARAMETRO'];
            }
        }
        return $ValorParametroReturn;
    }

}
