<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ProcesamientoTalos
 *
 * @author ronald.rosas
 */
class ProcesamientoTalos extends CI_Controller {

    public $iniciLog = '[INFO] ';
    public $logHeader = 'APOLO_TALOS_INFO::::::::: ';
    public $postData = 'POSTDATA::::::::: ';
    public $queryData = 'QUERYDATA::::::: ';
    public $errorGeneral = 'ERROR_GENERAL::::::: ';
    public $finFuncion = ' FIN PROCEDIMIENTO::::::: ';

    public function __construct() {
        parent::__construct();
        $this->load->helper('log4php');
    }

    public function validar_th() {
        $datosRecibidos = file_get_contents("php://input");


        $tarjetahabiente = json_decode($datosRecibidos);

        $tipo_documento = $tarjetahabiente->tipo_documento;
        $documento = $tarjetahabiente->documento;
        log_info($this->iniciLog . $this->logHeader . 'TIPO_DOCUMENTO = ' . $tipo_documento . ' DOCUMENTO = ' . $documento);
        $sql = "BEGIN MODFACTURADOR.PKGMODFACTURADORTALOS.PRC_VALIDAR_CLIENTE_TALOS(
                    partipodocumento=>:partipodocumento,
                    paridentificacion=>:paridentificacion,
                    parrespuesta=>:parrespuesta,
                    parmensajerespuesta=>:parmensajerespuesta);
                    END;";
        $conn = $this->db->conn_id;
        $stmt = oci_parse($conn, $sql);

        oci_bind_by_name($stmt, ':partipodocumento', $tipo_documento, 25);
        oci_bind_by_name($stmt, ':paridentificacion', $documento, 32);
        oci_bind_by_name($stmt, ':parrespuesta', $parrespuesta, 32);
        oci_bind_by_name($stmt, ':parmensajerespuesta', $parmensajerespuesta, 500);
        if (!@oci_execute($stmt)) {
            $e = oci_error($stmt);
            var_dump($e);
            log_info($this->logHeader . $this->errorGeneral . '::MODFACTURADOR.PKGMODFACTURADORTALOS:: ' . $e);
        }
        $respuesta = [
            "codigo_respuesta" => $parrespuesta,
            "mensaje_respuesta" => $parmensajerespuesta,
        ];

        $respuestaCodificada = json_encode($respuesta);
        echo $respuestaCodificada;
    }

    public function crear_th() {
        $datosRecibidos = file_get_contents("php://input");


        $tarjetahabiente = json_decode($datosRecibidos);

        $razon_social;
        $primer_nombre = $tarjetahabiente->primer_nombre;
        $segundo_nombre = $tarjetahabiente->segundo_nombre;
        $apellidos = $tarjetahabiente->apellidos;
        $tipo_organizacion = $tarjetahabiente->tipo_org; // 2 - persona natural
        $documento = $tarjetahabiente->documento;
        $digito_verificacion;
        $tipo_documento = $tarjetahabiente->tipo_documento; //1 cedula
        $correo = $tarjetahabiente->correo;
        $ciudad = $tarjetahabiente->ciudad;
        $departamento = $tarjetahabiente->departamento;
        $cod_postal = '11111';
        $direccion = $tarjetahabiente->direccion;
        $resp_fiscal = $tarjetahabiente->responsabilidad_fiscal; //5 R-99-PN No responsable
        $regimen = $tarjetahabiente->regimen; //2 no responsable de iva 
        $tipo_regimen = $tarjetahabiente->tipo_regimen; //3 no responsable de iva
        $telefono = $tarjetahabiente->telefono; //3 no responsable de iva
        $tributo = $tarjetahabiente->tributo; //1 IVA se debe revisar doc y actualizar
        $empresa_emisora = $tarjetahabiente->empresa_emisora; //3 MINERO crear empresa emisora 
        $sql = "BEGIN MODFACTURADOR.PKGMODFACTURADORGENERAL.PRCCREAACTUACLIENTEFACTURADOR(
                    parrazonsocial =>:parrazonsocial,
                    parprimernombre =>:parprimernombre,
                    parsegundonombre =>:parsegundonombre,
                    parapellidoscliente =>:parapellidoscliente,
                    parpktipoorg =>:parpktipoorg,
                    paridentificacion =>:paridentificacion,
                    pardigitoveri  =>:pardigitoveri,
                    parpktipdoc =>:parpktipdoc,
                    parccorreo =>:parccorreo,
                    parpkciudad =>:parpkciudad,
                    parpkdepartamento =>:parpkdepartamento,
                    parcodpostal =>:parcodpostal,
                    pardireccion =>:pardireccion,
                    parpkresponsabilidad =>:parpkresponsabilidad,
                    parpkregimen =>:parpkregimen,
                    parpktipregimen =>:parpktipregimen,
                    partelefono =>:partelefono,
                    parpktributo =>:parpktributo,
                    parpkempresaemisora =>:parpkempresaemisora,
                    parrespuesta =>:parrespuesta,
                    parmensajerespuesta =>:parmensajerespuesta);
                    END;";


        $conn = $this->db->conn_id;
        $stmt = oci_parse($conn, $sql);

        oci_bind_by_name($stmt, ':parrazonsocial', $razon_social, 25);
        oci_bind_by_name($stmt, ':parprimernombre', $primer_nombre, 45);
        oci_bind_by_name($stmt, ':parsegundonombre', $segundo_nombre, 45);
        oci_bind_by_name($stmt, ':parapellidoscliente', $apellidos, 150);
        oci_bind_by_name($stmt, ':parpktipoorg', $tipo_organizacion, 32);
        oci_bind_by_name($stmt, ':paridentificacion', $documento, 32);
        oci_bind_by_name($stmt, ':pardigitoveri', $digito_verificacion, 2);
        oci_bind_by_name($stmt, ':parpktipdoc', $tipo_documento, 2);
        oci_bind_by_name($stmt, ':parccorreo', $correo, 100);
        oci_bind_by_name($stmt, ':parpkciudad', $ciudad, 3);
        oci_bind_by_name($stmt, ':parpkdepartamento', $departamento, 3);
        oci_bind_by_name($stmt, ':parcodpostal', $cod_postal, 32);
        oci_bind_by_name($stmt, ':pardireccion', $direccion, 350);
        oci_bind_by_name($stmt, ':parpkresponsabilidad', $resp_fiscal, 5);
        oci_bind_by_name($stmt, ':parpkregimen', $regimen, 5);
        oci_bind_by_name($stmt, ':parpktipregimen', $tipo_regimen, 5);
        oci_bind_by_name($stmt, ':partelefono', $telefono, 32);
        oci_bind_by_name($stmt, ':parpktributo', $tributo, 32);
        oci_bind_by_name($stmt, ':parpkempresaemisora', $empresa_emisora, 5);
        oci_bind_by_name($stmt, ':parrespuesta', $parrespuesta, 32);
        oci_bind_by_name($stmt, ':parmensajerespuesta', $parmensajerespuesta, 500);
        if (!@oci_execute($stmt)) {
            $e = oci_error($stmt);
            var_dump($e);
        }
        $respuesta = [
            "codigo_respuesta" => $parrespuesta,
            "mensaje_respuesta" => $parmensajerespuesta,
        ];

        $respuestaCodificada = json_encode($respuesta);
        echo $respuestaCodificada;
    }

    public function retornar_ciudad_departamento() {

        log_info($this->iniciLog . $this->logHeader . ' RETRONAR CODIGO CIUDAD Y DEPARTAMENTO::::');

        $datosRecibidos = file_get_contents("php://input");


        $data_recibida = json_decode($datosRecibidos);
        $pk_codigo_ciudad = 0;
        $pk_codigo_departamento = 0;
        $codigo_dane = $data_recibida->codigo_dane;
        if (!empty($codigo_dane)) {
            log_info($this->iniciLog . $this->logHeader . ' CODIGO_DANE = ' . $codigo_dane);
            $sqlconsulta = $this->db->query("SELECT pk_ciu_codigo ,clitbldeppai_pk_dep_codigo pk_dep_codigo from modcliuni.clitblciudad
                where codigo_dane = $codigo_dane");
            $pk_codigo_ciudad = $sqlconsulta->result_array[0]['PK_CIU_CODIGO'];
            $pk_codigo_departamento = $sqlconsulta->result_array[0]['PK_DEP_CODIGO'];
            if (!empty($pk_codigo_ciudad) && !empty($pk_codigo_departamento)) {
                $parrespuesta = 1;
                $parmensajerespuesta = 'Consulta exitosa';
            } else {
                $parrespuesta = 2;
                $parmensajerespuesta = 'Ninguna coinciencia para el codigo DANE:' . $codigo_dane;
            }
        } else {
            $parrespuesta = 26;
            $parmensajerespuesta = 'Codigo DANE es nulo.';
        }
        $respuesta = ["respuesta" => [
                "codigo_respuesta" => $parrespuesta,
                "mensaje_respuesta" => $parmensajerespuesta,
            ],
            "pk_ciudad_codigo" => $pk_codigo_ciudad,
            "pk_departamento_codigo" => $pk_codigo_departamento,
        ];

        $respuestaCodificada = json_encode($respuesta);
        echo $respuestaCodificada;
    }

    public function crear_factura_minero() {
        require_once("application/controllers/wstalosfacturador/facturaMinero.php");
        log_info($this->iniciLog . $this->logHeader . ' CREAR FACTURA MINERO::::');

        $datosRecibidos = file_get_contents("php://input");
        log_info($this->iniciLog . $this->logHeader . 'DATA RECIBIDA = ' . $datosRecibidos);

        $libEjecutarFacturaMinero = new facturaMinero();
        $data_recibida = json_decode($datosRecibidos);
        $pk_codigo_ciudad = 0;
        $pk_codigo_departamento = 0;
        $envio_comfiar = 0;
        $tipoDocumento = $data_recibida->DatosTarjetahabiente->TipoDocumento;
        $Documento = $data_recibida->DatosTarjetahabiente->Documento;

        $NombreProducto = $data_recibida->Totales->NombreProducto;
        $Contrato = $data_recibida->Totales->Contrato;
        $FechaLimitePago = $data_recibida->Totales->FechaLimitePago;
        $FechaEmisionFactura = $data_recibida->Totales->FechaFacturacion;
        $SaldoAnterior = $data_recibida->Totales->SaldoAnterior;
        $AbonosCapital = $data_recibida->Totales->AbonosCapital;
        $IngresosGravados = $data_recibida->Totales->IngresosGravados;
        $IngresosNoGravados = $data_recibida->Totales->IngresosNoGravados;
        $Iva = $data_recibida->Totales->Iva;
        $TotalOperacion = $data_recibida->Totales->TotalOperacion;
        $IngresosTerceros = $data_recibida->Totales->IngresosTerceros;
        $SubtotalPeriodo = $data_recibida->Totales->SubtotalPeriodo;
        $PagoMinimo = $data_recibida->Totales->PagoMinimo;
        $SubTotal = $data_recibida->Totales->SubTotal;

        /* Pasos
         * 1- Crear factura y detalle factura
         * 2- Enviar comfiar
         * 3- Guardar respuesta comfiar
         * 4- Retornar respuesta talos
         */
        log_info($this->iniciLog . $this->logHeader . 'TIPO_DOC = ' . $tipoDocumento . ' DOCUMENTO = ' . $Documento . ' FECHA_EMISION: ' . $FechaEmisionFactura);


        if (!empty($tipoDocumento) && !empty($Documento)) {

            $sqlconsulta = $this->db->query("SELECT PK_CLIENTE_CODIGO   from modfacturador.facturtblclienteempresa
                where identificacion =$Documento and pk_tipdoc_codigo = $tipoDocumento and pk_empresa_emisora=2");
            $pk_cliente_codigo = $sqlconsulta->result_array[0]['PK_CLIENTE_CODIGO'];
            $pk_empresa_emisora = 2; // se quema ya que los datos semilla del facturador la pk de minero es 2

            log_info($this->iniciLog . $this->logHeader . 'TIPO_DOC = ' . $tipoDocumento . ' DOCUMENTO = ' . $Documento . ' PK_CLIENTE = ' . $pk_cliente_codigo);
            $IdTxComfiar = 00;
            $urlPdf = '';
            $sql = "BEGIN MODFACTURADOR.PKGMODFACTURADORTALOS.PRCCREARFACTURATALOSCOMFIAR(
                 parpkclientecodigo =>:parpkclientecodigo,
                 parpkempresaemisora =>:parpkempresaemisora,
                 parpkfactura =>:parpkfactura,
                 parnombreproducto =>:parnombreproducto,
                 parcontrato =>:parcontrato,
                 parfechalimite =>:parfechalimite,
                 parfechaemision =>:parfechaemision,
                 parsaldoanterior =>:parsaldoanterior,
                 parabonocapital =>:parabonocapital,
                 paringresogravado =>:paringresogravado,
                 paringresonogravado =>:paringresonogravado,
                 pariva =>:pariva,
                 partotaloperacion =>:partotaloperacion,
                 partotalterceros =>:partotalterceros,
                 parsubtotalperiodo =>:parsubtotalperiodo,
                 parsubtotal =>:parsubtotal,
                 parpagominimo =>:parpagominimo,
                 parnumerofactura =>:parnumerofactura,
                 parcodigorespuesta =>:parcodigorespuesta);
                END;";
            $conn = $this->db->conn_id;
            $stmt = oci_parse($conn, $sql);
            oci_bind_by_name($stmt, ':parpkclientecodigo', $pk_cliente_codigo, 32);
            oci_bind_by_name($stmt, ':parpkempresaemisora', $pk_empresa_emisora, 32);
            oci_bind_by_name($stmt, ':parpkfactura', $parpkfactura, 32);
            oci_bind_by_name($stmt, ':parnombreproducto', $NombreProducto, 250);
            oci_bind_by_name($stmt, ':parcontrato', $Contrato, 32);
            oci_bind_by_name($stmt, ':parfechalimite', $FechaLimitePago, 32);
            oci_bind_by_name($stmt, ':parfechaemision', $FechaEmisionFactura, 32);
            oci_bind_by_name($stmt, ':parsaldoanterior', $SaldoAnterior, 32);
            oci_bind_by_name($stmt, ':parabonocapital', $AbonosCapital, 32);
            oci_bind_by_name($stmt, ':paringresogravado', $IngresosGravados, 32);
            oci_bind_by_name($stmt, ':paringresonogravado', $IngresosNoGravados, 32);
            oci_bind_by_name($stmt, ':pariva', $Iva, 32);
            oci_bind_by_name($stmt, ':partotaloperacion', $TotalOperacion, 32);
            oci_bind_by_name($stmt, ':partotalterceros', $IngresosTerceros, 32);
            oci_bind_by_name($stmt, ':parsubtotalperiodo', $SubtotalPeriodo, 32);
            oci_bind_by_name($stmt, ':parsubtotal', $SubTotal, 32);
            oci_bind_by_name($stmt, ':parpagominimo', $PagoMinimo, 32);
            oci_bind_by_name($stmt, ':parnumerofactura', $numeroFactura, 32);
            oci_bind_by_name($stmt, ':parcodigorespuesta', $parcodigorespuesta, 32);
            if (!oci_execute($stmt)) {
                $e = oci_error($stmt);
                VAR_DUMP($e);
                log_info($this->iniciLog . '-' . $this->logHeader . ' ERROR CREANDO FACTURA PRCCREARFACTURACOMFIAR' . $e['message'] . ' ID_CLIENTE=' . $pk_cliente_codigo);
            }
            if ($parcodigorespuesta == 1) {
                log_info($this->iniciLog . $this->logHeader . 'PK_FACTURA = ' . $parpkfactura . ' NUMERO_FACTURA = ' . $numeroFactura . ' PARRESPUESTA = ' . $parcodigorespuesta);

                // Loop JSON objects
                $json_items = $data_recibida->Items;

                $cantidadItems = 0;
                $cantidadItemsGuardados = 0;
                foreach ($json_items as $item) {
                    $CodigoTransaccion = $item->CodigoProducto;
                    $NombreTransaccion = $item->NombreTransaccion;
                    $Cantidad = $item->Cantidad;
                    $ValorUnitario = $item->ValorUnitario;
                    $Total = $item->ValorTotal;
                    $PorcentajeIva = $item->PorcentajeIva;
                    $TotalIva = $item->TotalIva;
                    $ValorObsequio = $item->ValorObsequio;
                    log_info($this->logHeader . $this->iniciLog . $this->postData . ' CodigoTransaccion: ' . $CodigoTransaccion
                            . ' NombreTransaccion: ' . $NombreTransaccion
                            . ' Cantidad: ' . $Cantidad
                            . ' ValorUnitario: ' . $ValorUnitario
                            . ' Total: ' . $Total
                            . ' PorcentajeIva: ' . $PorcentajeIva
                            . ' TotalIva: ' . $TotalIva
                            . ' ValorObsequio: ' . $ValorObsequio
                    );



                    $sql = "BEGIN MODFACTURADOR.PKGMODFACTURADORTALOS.PRCCREARDETALEFACTURAMINERO(
                        parpkfacturacodigo =>:parpkfacturacodigo,
                        parcodigoproducto =>:parcodigoproducto,
                        parnombreproducto =>:parnombreproducto,
                        parcantidad =>:parcantidad,
                        parvalorunit =>:parvalorunit,
                        partotal =>:partotal,
                        parporcentajeiva =>:parporcentajeiva,
                        partotaliva =>:partotaliva,
                        parvalorobsequio =>:parvalorobsequio,
                        parrespuesta =>:parrespuesta,
                        parmensajerespuesta =>:parmensajerespuesta);
                       END;";
                    $conn = $this->db->conn_id;
                    $stmt = oci_parse($conn, $sql);
                    oci_bind_by_name($stmt, ':parpkfacturacodigo', $parpkfactura, 32);
                    oci_bind_by_name($stmt, ':parcodigoproducto', $CodigoTransaccion, 32);
                    oci_bind_by_name($stmt, ':parnombreproducto', $NombreTransaccion, 350);
                    oci_bind_by_name($stmt, ':parcantidad', $Cantidad, 32);
                    oci_bind_by_name($stmt, ':parvalorunit', $ValorUnitario, 32);
                    oci_bind_by_name($stmt, ':partotal', $Total, 32);
                    oci_bind_by_name($stmt, ':parporcentajeiva', $PorcentajeIva, 32);
                    oci_bind_by_name($stmt, ':partotaliva', $TotalIva, 32);
                    oci_bind_by_name($stmt, ':parvalorobsequio', $ValorObsequio, 32);
                    oci_bind_by_name($stmt, ':parrespuesta', $parrespuesta, 150);
                    oci_bind_by_name($stmt, ':parmensajerespuesta', $parmensajerespuesta, 250);
                    if (!oci_execute($stmt)) {
                        $e = oci_error($stmt);
                        VAR_DUMP($e);
                        log_info($this->iniciLog . '-' . $this->logHeader . ' ERROR CREANDO FACTURA PRCCREARFACTURACOMFIAR' . $e['message'] . ' ID_CLIENTE=' . $pk_cliente_codigo);
                    }

                    if ($parrespuesta == 1) {
                        $cantidadItemsGuardados++;
                        log_info($this->iniciLog . '-' . $this->logHeader . ' PRCCREARDETALEFACTURAMINERO Respueta = ' . $parrespuesta . ' Codigo_respuesta = ' . $parmensajerespuesta);
                    }
                    $cantidadItems++;
                }
                log_info($this->iniciLog . ' cantidadItems = ' . $cantidadItems . ' cantidadItemsGuardados = ' . $cantidadItemsGuardados);
                if ($cantidadItemsGuardados === $cantidadItems) {
                    //se llama controlador encargado de transmitir factura a comfiar
                    $respuestaTxfacturaComfiar = $libEjecutarFacturaMinero->transmitirComfiar($parpkfactura);
                    $codigoRespuesta = $respuestaTxfacturaComfiar->CodRespuesta;
                    $urlPdf = $respuestaTxfacturaComfiar->UrlPdf;
                    $IdTxComfiar = $respuestaTxfacturaComfiar->IdTxComfiar;
                    log_info($this->iniciLog . '-' . $this->logHeader . ' respuestaTxfacturaComfiar = ' . $codigoRespuesta . ' UrlPdf: ' . $urlPdf . ' IdTxComfiar: ' . $IdTxComfiar);
                    $parrespuesta = $codigoRespuesta;
                    $envio_comfiar = $codigoRespuesta;
                    if ($codigoRespuesta == 1) {
                        $parmensajerespuesta = 'PK_FACTURA_CODIGO: ' . $parpkfactura . ' NUMERO_FACTURA: ' . $numeroFactura . ' transmitida correctamente';
                    } else {
                        $parmensajerespuesta = 'PK_FACTURA_CODIGO: ' . $parpkfactura . ' NUMERO_FACTURA: ' . $numeroFactura . ' ' . $urlPdf;
                    }
                } else {
                    log_info($this->iniciLog . ' cantidadItems = ' . $cantidadItems . ' cantidadItemsGuardados = ' . $cantidadItemsGuardados);
                    log_info($this->iniciLog . '-' . $this->errorGeneral . ' ERROR GUARDANDO DETALLE FACTURA PRCCREARDETALEFACTURAMINERO');
                }
            } else if ($parcodigorespuesta == 2) {
                // Existe una factura exitosa para la combinacion cliente empresa emisora y fecha emision
                // Se realiza consultas para retornar data en respuesta
                $sqlconsulta = $this->db->query("select x.id_transaccion_comfiar, x.url_pdf , f.envio_comfiar
                    FROM modfacturador.facturtblfacturacomfiar f
                    JOIN modfacturador.facturtblxmlcomfiar x ON f.pk_factura_codigo = x.pk_factura_codigo 
                    WHERE x.pk_factura_codigo =$parpkfactura and x.pk_tipo_xml_codigo = 1");
                $urlPdf = $sqlconsulta->result_array[0]['URL_PDF'];
                $IdTxComfiar = $sqlconsulta->result_array[0]['ID_TRANSACCION_COMFIAR'];
                $envio_comfiar = $sqlconsulta->result_array[0]['ENVIO_COMFIAR'];

                log_info($this->iniciLog . $this->logHeader . ' CLIENTE YA CUENTA CON FACTURACION PARA LA FECHA: ' . $FechaEmisionFactura . ' PK_FACTURA = ' . $parpkfactura . ' NUMERO_FACTURA = ' . $numeroFactura . ' PARRESPUESTA = ' . $parcodigorespuesta);
                $parrespuesta = $parcodigorespuesta;
                
                $parmensajerespuesta = 'PK_FACTURA_CODIGO: ' . $parpkfactura . ' NUMERO_FACTURA: ' . $numeroFactura . ' CLIENTE YA CUENTA CON FACTURACION PARA LA FECHA: ' . $FechaEmisionFactura;
            } else {
                $parrespuesta = $parcodigorespuesta;
                $parmensajerespuesta = 'Factura '.$numeroFactura.' contiene errores.';
                log_info($this->iniciLog . '-' . $this->logHeader . ' ERROR CREANDO FACTURA PRCCREARFACTURACOMFIAR' . $parcodigorespuesta);
            }
        } else {
            $parrespuesta = 26;
            $parmensajerespuesta = 'Datos tarjetahabiente vacios.';
        }
        $respuesta = ["respuesta" => [
                "codigo_respuesta" => $parrespuesta,
                "mensaje_respuesta" => $parmensajerespuesta,
                "idTxComfiar" => $IdTxComfiar
            ],
            "Contrato" => $Contrato,
            "EnvioComfiar" => $envio_comfiar,
            "MontoTotal" => $PagoMinimo,
            "FechaLimitePago" => $FechaLimitePago,
            "NumeroFactura" => $numeroFactura,
            "PkFactura" => $parpkfactura,
            "UrlPdf" => $urlPdf
        ];

        $respuestaCodificada = json_encode($respuesta);
        echo $respuestaCodificada;
    }

}
