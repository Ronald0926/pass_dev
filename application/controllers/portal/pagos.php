<?php
session_start();
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pagos extends CI_Controller {

    public function __construct() {
        parent::__construct();
        try {
            $this->load->helper('log4php');
        } catch (Exception $ex) {
            
        }
        //if ($this->session->userdata('entidad') == NULL) {
        if ($_SESSION['entidad'] == NULL) {
            redirect('/');
        }
    }

    public function __destruct() {
        $this->db->close();
    }

    public function verificarPerfilCo() {
        //$rol = $this->session->userdata("rol");
        $rol = $_SESSION['rol'];
        if (($rol != 45) and ( $rol != 47) and ( $rol != 58)) {
            redirect('/portal/principal/pantalla');
        }
    }

    public function pago($ordenes = 0) {
        $this->verificarPerfilCo();
        //$rol = $this->session->userdata("rol");
        $rol = $_SESSION['rol'];
        $post = $this->input->post();
        if ($post) {


            $ordenes = $post['ordenes']; // $this->session->userdata('ordenes');
            //$empresa = $this->session->userdata("entidad");
            $empresa = $_SESSION['entidad'];
            $data['empresa'] = $empresa['NOMBREEMPRESA'];
            //$usuario = $this->session->userdata("usuario");
            $usuario = $_SESSION['usuario'];
            $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];

            $lista = '';
            //$pkEmpresa = $this->session->userdata("pkentidad");
            $pkEmpresa = $_SESSION['pkentidad'];
            // ordenes pendientes por pagar
            foreach ($ordenes as $key => $value) {
                $lista = $value . ',' . $lista;
            }
            if (is_null($lista)) {
                $lista = '0';
            } else {
                $lista = substr($lista, 0, -1);
            }

            $ordenes = $this->db->query("   
            SELECT o.pk_ordcom_codigo CODIGOORDEN,
           nvl(o.nombre_orden,' #'||o.pk_ordcom_codigo) NOMBREORDEN,
            modpropag.ppapkgconsultas.fncconmontoorden(o.pk_ordcom_codigo) VALOR
            ,factura.prefijo_factura||factura.numero_factura NUMERO_FACTURA
            FROM MODPROPAG.ppatblordcom o
            JOIN MODFACTUR.FACTBLFACORD factord
            ON o.PK_ORDCOM_CODIGO=factord.pk_ordcom_codigo
            JOIN MODFACTUR.FACTBLFACTUR factura
            ON factura.pk_factur_codigo=factord.pk_factur_codigo
            INNER JOIN MODCLIUNI.clitblentida e
            ON o.pk_cliente = e.pk_ent_codigo
            and e.pk_ent_codigo = $pkEmpresa
            LEFT JOIN MODCLIUNI.clitblentida v
            ON o.pk_vendedor = v.pk_ent_codigo
            WHERE  o.pk_estado=4
            and o.pk_ordcom_codigo in($lista)
            ORDER  BY 1 desc ");

            $data['ordenes'] = $ordenes->result_array;

            $VALORPAGAR = 0;
            $PCO = 0;

            foreach ($data['ordenes'] as $key => $value) {
                $llaves = $value['CODIGOORDEN'] . ',' . $llaves;
                // var_dump($value['VALOR']);
                $VALORPAGAR = $VALORPAGAR + $value['VALOR'];
                $PCO = $PCO + $value['PCO'];
            }
            if (is_null($llaves)) {
                $llaves = '0';
            } else {
                $llaves = substr($llaves, 0, -1);
            }
            $dettar = $this->db->query("SELECT     
            p.nombre_producto PRODUCTO,
            valor_unit valor_unitario, 
            PK_ORDEN_COMPRA ORDCOM,
            sum (cantidad) cantidad ,
            sum (cantidad) * valor_unit sub_total
       
            FROM MODPROPAG.PPATBLDETORD do
            INNER JOIN  MODPRODUC.PROTBLPRODUC p
            ON p.PK_PRODUC_CODIGO = do.PK_PRODUCTO
            and p.PK_TIPPRO_CODIGO=1
            WHERE PK_ORDEN_COMPRA in ({$llaves})
            GROUP BY  p.nombre_producto, valor_unit, monto,PK_ORDEN_COMPRA
            ORDER BY 1 ASC");
            $data['dettar'] = $dettar->result_array;

            $detord = $this->db->query("SELECT
                do.pk_orden_compra   ordcom,
                p.nombre_producto    nompro,
                valor_unit           valuni,
                monto                monto,
                SUM(cantidad) cantid,
                SUM(cantidad) * valor_unit subtot
            FROM
                modpropag.ppatbldetord   do
                INNER JOIN modproduc.protblproduc   p ON p.pk_produc_codigo = do.pk_producto
                                                       AND p.pk_tippro_codigo = 3
            WHERE
                pk_orden_compra = SOME ({$llaves})
            GROUP BY
                do.pk_orden_compra,
                p.nombre_producto,
                valor_unit,
                monto
            UNION
            SELECT
                abono.PK_ORDEN   ordcom
                ,'ABONO A TARJETAS '||p.nombre_producto nompro
                ,avg (detalle.monto) valuni
                ,sum(detalle.monto)   monto
                ,count(p.nombre_producto) cantidad
                ,count(p.nombre_producto)*avg (detalle.monto) subtot
            FROM
                modpropag.ppatblpedabon   abono 
                INNER JOIN modpropag.ppatbldetpab    detalle ON abono.pk_pedabon_codigo = detalle.pk_pedido
                INNER JOIN modproduc.protblproduc    p
                ON p.pk_produc_codigo = detalle.pk_producto
            WHERE
                abono.PK_ORDEN = SOME ({$llaves})
            GROUP BY
                abono.PK_ORDEN,
                p.nombre_producto
            ORDER BY
                2 ASC");
            $data['detord'] = $detord->result_array;
            $impues = $this->db->query("Select 
            PK_ORDEN ORDCOM,
            PORCENTAJE||'% ' ||IMPUESTO||' '||tipimp.nombre NOMIMP , 
            VALOR||'' VALIMP,
            imp.PK_TIPIMP_CODIGO TIPIMP,
            PK_VISIMP_CODIGO VISIMP,
            PK_NATURA_CODIGO NATIMP
            FROM MODPROPAG.PPATBLIMPORD imp,
            MODPROPAG.PPATBLTIPIMP tipimp
            WHERE imp.pk_tipimp_codigo=tipimp.pk_tipimp_codigo 
            and PK_ORDEN = some({$llaves})
            and imp.pk_visimp_codigo = 1
            order by 3");
            $data['impues'] = $impues->result_array;
            $data['valortotal'] = $VALORPAGAR;
            $data['pco'] = $PCO;
            $PMA = $VALORPAGAR - $PCO;
            $data['pma'] = $PMA;
        }
        $cuenta = $this->db->query("SELECT VALOR_PARAMETRO FROM MODGENERI.CUENTA_BANCO_PEOPLE WHERE PK_PARGEN_CODIGO=74");
        $data['cuenta'] = $cuenta->result_array;

        $medio = $this->db->query("SELECT PK_MEDPAG_CODIGO, NOMBRE FROM MODPROPAG.PPATBLMEDPAG");
        $data['medioPago'] = $medio->result_array;

        $data['rol'] = $rol;
        //$empresa = $this->session->userdata("entidad");
        $empresa = $_SESSION['entidad'];
        $pkempresa = $empresa['PK_ENT_CODIGO'];

        $pagoanticipo = $this->db->query("SELECT 
                                        anticipo.pk_ent_codigo
                                        from modcomerc.comtblanticipo anticipo
                                         JOIN modcliuni.clitblentida entidad
                                         ON anticipo.pk_ent_codigo=entidad.pk_ent_codigo
                                         AND anticipo.solicitud_anticipo = 1
                                            AND anticipo.gestionado = 2
                                            and anticipo.aprobado=1
                                            and anticipo.pk_estant_codigo=1
                                            AND entidad.pk_ent_codigo= $pkempresa");
        $pagoanticipo = $pagoanticipo->result_array[0];
        if (is_null($pagoanticipo)) {
            $pagoanticipo = 0;
        } else {
            $pagoanticipo = 1;
        }

        //si es perfil autorizador de pagos
        //$rol = $this->session->userdata("rol");
        $rol = $_SESSION['rol'];
        if ($rol == 58) {
            $sqlAnticipo = $this->db->query("select distinct
                ent.pk_ent_codigo CODIGO_ENTIDAD,
                CASE
                    WHEN proc.aprobado = 1 THEN 'SI'
                    ELSE 'NO'
                END AS ANTICIPO,
                bol.DIAS_MAXIMO DIAS_ANTICIPO,
                to_char(MODGENERI.GENPKGCLAGEN.DECRYPT(bol.tope_maximo),'FML999G999G999G999G990D00') CUPO_ANTICIPO,
                to_char(MODGENERI.GENPKGCLAGEN.DECRYPT(bol.saldo),'FML999G999G999G999G990D00') CUPO_USADO,
                to_char((MODGENERI.GENPKGCLAGEN.DECRYPT(bol.monto_temporal) ),'FML999G999G999G999G990D00') EXTRACUPO
                ,to_char((MODGENERI.GENPKGCLAGEN.DECRYPT(bol.tope_maximo) + MODGENERI.GENPKGCLAGEN.DECRYPT(bol.saldo)+(MODGENERI.GENPKGCLAGEN.DECRYPT(bol.monto_temporal))),'FML999G999G999G999G990D00') CUPO_DISPONIBLE

            from 
                MODCLIUNI.clitblentida ent
                join modcliuni.clitblvincul vin on vin.clitblentida_pk_ent_codigo = ent.pk_ent_codigo
                join modcliuni.clitbltipent tipent on tipent.pk_tipent_codigo = ent.clitbltipent_pk_tipent_codigo
                join modcliuni.clitblestent estent on estent.pk_est_codigo = ent.clitblestent_pk_est_codigo
                join modcliuni.clitblestusu estusu on estusu.pk_estusu_codigo = ent.clitblestusu_pk_estusu_codigo
                                                    AND vin.clitbltipvin_pk_tipvin_codigo = 50
                left join modcomerc.comtblcotiza coti on coti.pk_entida_cliente = ent.pk_ent_codigo 
                                                    AND coti.pk_estado_codigo = 1
                left join MODCOMERC.comtblproces proc on proc.pk_cotiza_codigo = coti.pk_cotiza_codigo 
                                                    AND proc.pk_estado_codigo = 1
                join MODSALDOS.saltblbolsil bol on bol.pk_ent_codigo = ent.pk_ent_codigo 
                                                    AND bol.pk_tipbol_codigo = 3
                WHERE ent.pk_ent_codigo={$empresa['PK_ENT_CODIGO']}");
            $data['dataAnticipo'] = $sqlAnticipo->result_array[0];
        }


        $data['pagoanticipo'] = $pagoanticipo;
        //$ultimaconexion = $this->session->userdata("ultimaconexion");
        $ultimaconexion = $_SESSION['ultimaconexion'];
        $data['ultimaconexion'] = $ultimaconexion;
        $data['menu'] = "pagos";
        $this->load->view('portal/templates/header2', $data);
        $this->load->view('portal/pagos/pago', $data);
        $this->load->view('portal/templates/footer', $data);
    }

    public function retornopago() {
        $post = $this->input->post();
        $referenciaPago = $post["referenciaPago"];
        $codigoComercio = $post["idComercio"];
        //********Codigo consulta estado de transaccion multipay */
        $wsdl = "https://pagosvirtuales.multipay.com.co/WebServiceTransacciones/WebServicesTransacciones.asmx?wsdl";
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
            "IdComercioElectronico" => $codigoComercio, // dato preferiblemente parametrizado
            "Factura" => $referenciaPago, //Codigo de referencia de pago
        );
        try {
            $responce_param = $client->ConsultarTransaccion($request_param);
            // $responce_param =  $client->call("ConsultarTransaccion", $request_param); // Alternative way to call soap method
            var_dump($responce_param);
            if ($responce_param == "00;Aprobada") {
                $ordenes = $this->db->query("select pk_proceso ORDEN
                                     from modpropag.ppatblrefpag 
                                     where pk_referencia_pago=$referenciaPago");
                $ordenes = $ordenes->result_array;
                foreach ($ordenes as $orden) {
                    $globales = $globales . $orden['ORDEN'] . ',';
                }
                $codordenes = substr($globales, 0, strlen($globales) - 1);
                $resPagarPse = $this->pagarPSE($codordenes);
                echo $resPagarPse;
            } else if ($responce_param == "98  ;Transaccion en proceso") {
                echo "Transaccion en proceso";
            } else {
                ECHO ("Transaccion rechazada");
            }
        } catch (Exception $e) {
            echo "<h2>Exception Error!</h2>";
            echo $e->getMessage();
        }
        //****************************************************** */
    }

    public function pagar() {

        $this->verificarPerfilCo();
        $post = $this->input->post();
        //$ultimaconexion = $this->session->userdata("ultimaconexion");
        $ultimaconexion = $_SESSION['ultimaconexion'];
        $data['ultimaconexion'] = $ultimaconexion;
        $data['menu'] = "pagos";
        //$empresa = $this->session->userdata("entidad");
        $empresa = $_SESSION['entidad'];
        $data['empresa'] = $empresa['NOMBREEMPRESA'];
        //$usuario = $this->session->userdata("usuario");
        $usuario = $_SESSION['usuario'];
        $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
        $ordenes = $post['ordenes'];

        if ($ordenes) {
            $this->verificarPerfilCo();
            // exit();
            $ordenes = $post['ordenes'];

            //$usuario = $this->session->userdata('usuario');
            $usuario = $_SESSION['usuario'];
            //$empresa = $this->session->userdata('entidad');
            $empresa = $_SESSION['entidad'];
            //$entidad = $this->session->userdata('pkentidad');
            $entidad = $_SESSION['pkentidad'];
            $globalOrdenes = "";
            $totalOrden = count($ordenes) - 1;
            for ($i = 0; $i < count($ordenes); $i++) {
                $globalOrdenes = $globalOrdenes . $ordenes[$i];
                if ($i < $totalOrden) {
                    $globalOrdenes = $globalOrdenes . ",";
                }
            }

            $totalPago = $this->db->query("
            select sum (valor)  from (    
                SELECT o.pk_ordcom_codigo CODIGOORDEN,
           nvl(o.nombre_orden,' #'||o.pk_ordcom_codigo) NOMBREORDEN,
            modpropag.ppapkgconsultas.fncconmontoorden(o.pk_ordcom_codigo) VALOR
            ,factura.prefijo_factura||factura.numero_factura NUMERO_FACTURA
            FROM MODPROPAG.ppatblordcom o
            JOIN MODFACTUR.FACTBLFACORD factord
            ON o.PK_ORDCOM_CODIGO=factord.pk_ordcom_codigo
            JOIN MODFACTUR.FACTBLFACTUR factura
            ON factura.pk_factur_codigo=factord.pk_factur_codigo
            INNER JOIN MODCLIUNI.clitblentida e
            ON o.pk_cliente = e.pk_ent_codigo
            and e.pk_ent_codigo = $entidad
            LEFT JOIN MODCLIUNI.clitblentida v
            ON o.pk_vendedor = v.pk_ent_codigo
            WHERE  o.pk_estado=4
                AND o.pk_ordcom_codigo in (" . $globalOrdenes . ")
                ORDER  BY 1 desc ) 
                where rownum < 51");
            $totalPago = $totalPago->result_array[0]['SUM(VALOR)'];
            $ordenes = $globalOrdenes;
            $impuestos = $this->calcularimpuesto($globalOrdenes);
            $subtotal = $totalPago - $impuestos;

            //Consultar el total a partir de las ordenes seleccionadas el total a enviar
            //Consultar la informacion del cliente
            $dataUser = $this->db->query("select
                                        entida.NOMBRE NOMBRE,
                                        entida.APELLIDO APELLIDO,
                                        entida.DOCUMENTO DOCUMENTO,
                                        nvl(entida.CORREO_ELECTRONICO,entida.CORREO_PERSONAL) CORREO_ELECTRONICO, 
                                        tipdoc.CODIGO_PASARELA TIPODOCUMENTO 
                                        from MODCLIUNI.CLITBLENTIDA entida 
                                        JOIN MODCLIUNI.CLITBLTIPDOC tipdoc 
                                        ON entida.clitbltipdoc_pk_td_codigo=tipdoc.pk_td_codigo
                                        where pk_ent_codigo={$usuario['PK_ENT_CODIGO']}");

            $data = $dataUser->result_array[0];
            $apiKey = $this->db->query("SELECT VALOR_PARAMETRO FROM MODGENERI.GENTBLPARGEN WHERE pk_pargen_codigo=77");
            $urlRetorno = $this->db->query("SELECT VALOR_PARAMETRO FROM MODGENERI.GENTBLPARGEN WHERE pk_pargen_codigo=79");
            $codigoComercio = $this->db->query("SELECT VALOR_PARAMETRO FROM MODGENERI.GENTBLPARGEN WHERE pk_pargen_codigo=78");
            $codigoComercio = $codigoComercio->result_array[0];
            $apiKey = $apiKey->result_array[0];
            $urlRetorno = $urlRetorno->result_array[0];
            $urlpasarela = $this->db->query("SELECT VALOR_PARAMETRO FROM MODGENERI.GENTBLPARGEN WHERE pk_pargen_codigo=80");
            $urlpasarela = $urlpasarela->result_array[0];
            $referenciapago = $this->generarreferenciapago($globalOrdenes, 1);
            $referenciaComercio = $this->db->query("SELECT VALOR_PARAMETRO FROM MODGENERI.GENTBLPARGEN WHERE pk_pargen_codigo=90");
            $referenciaComercio = $referenciaComercio->result_array[0];

            if ($referenciapago !== 0) {
                try {
                    $htmlPay = '                
                        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
                        <form class="hidden" method="post" action="' . $urlpasarela['VALOR_PARAMETRO'] . '" target="_blank" >
                                    <input type="hidden" name="Id" id="Id" value="' . $codigoComercio['VALOR_PARAMETRO'] . '"/>
                                    <input type="hidden" name="Clave" id="Clave" value="' . $apiKey['VALOR_PARAMETRO'] . '"/>
                                    <input type="hidden" name="CompraNeta" id="CompraNeta" value="' . $totalPago . '"/>
                                    <input type="hidden" name="Iva" id="Iva" value="0"/>
                                    <input type="hidden" name="BaseDevolucion" id="BaseDevolucion" value="0"/>
                                    <input type="hidden" name="NombreProducto" id="NombreProducto" value="' . $referenciapago . '"/>
                                    <input type="hidden" name="Referencia" id="Referencia" value="' . $referenciaComercio['VALOR_PARAMETRO'] . '"/>
                                    <input type="hidden" name="ValorTotal" id="ValorTotal" value="' . $totalPago . '"/>
                                    <input type="hidden" name="Factura" id="Factura" value="' . $referenciapago . '"/>
                                    <input type="hidden" name="IdCiudad" id="IdCiudad" value="9"/>
                                    <input type="hidden" name="IdPais" id="IdPais" value="170"/>
                                    <input type="hidden" name="Franquicia" id="Franquicia" value=""/>
                                    <input TYPE="hidden" name="PrimerNombre"  value="' . $data['NOMBRE'] . '"><br>
                                    <input TYPE="hidden" name="PrimerApellido"  value="' . $data['APELLIDO'] . '"><br>   
                                    <input TYPE="hidden" required name="Correo" id="Correo" value="' . $data['CORREO_ELECTRONICO'] . '"/>
                                    <input TYPE="hidden" required name="NumeroDocumento" id="NumeroDocumento" value="' . $data['DOCUMENTO'] . '"/>
                                    <input  name="Submit" type="submit" value="" id="SendPeoplePay" hidden>
                        </form>            
                        <script>
                            $( document ).ready(function() {
                                document.getElementById("SendPeoplePay").click();
                            });
                        </script>
                    ';
                    $data['htmlPay'] = $htmlPay;
                    $data['totalPago'] = $totalPago;
                    $data['referenciapago'] = $referenciapago;

                    //DATOS DE SESION
                    //$empresa = $this->session->userdata("entidad");
                    $empresa = $_SESSION['entidad'];
                    $data['empresa'] = $empresa['NOMBREEMPRESA'];
                    //$usuario = $this->session->userdata("usuario");
                    $usuario = $_SESSION['usuario'];
                    $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
                    //    
                    $this->load->view('portal/templates/header2', $data);
                    $this->load->view('portal/pagos/pagar', $data);
                    $this->load->view('portal/templates/footer', $data);
                } catch (Exception $e) {
                    var_dump($e);
                    $this->load->view('portal/templates/header2', $data);
                    $this->load->view('portal/pagos/pago', $data);
                    $this->load->view('portal/templates/footer', $data);
                }
            } else {
                $this->load->view('portal/templates/header2', $data);
                $this->load->view('portal/pagos/pago', $data);
                $this->load->view('portal/templates/footer', $data);
            }
        } else {
            redirect('/portal/ordenPedido/lista/0/2');
        }
    }

    function calcularimpuesto($codordenes) {
        $sql = "BEGIN :valimpuestos:=modpropag.ppapkgconsultas.fncmontoimpuestosordenes (
              :parordenes); END;";

        $conn = $this->db->conn_id;
        $stmt = oci_parse($conn, $sql);
        $parordenes = $codordenes;

        //TIPO NUMBER INPUT
        oci_bind_by_name($stmt, ':parordenes', $parordenes, 32);
        //TIPO NUMBER INPUT
        oci_bind_by_name($stmt, ':valimpuestos', $valimpuestos, 32);

        if (!@oci_execute($stmt)) {
            $e = oci_error($stmt);
            var_dump($e);
            $valimpuestos = 0;
        }
        return $valimpuestos;
    }

    public function pagarPSE($codordenes = null, $referenciapago = null) {
        if (!is_null($codordenes)) {
            $sql = "BEGIN modpropag.ppapkgactualizaciones.prcpacpagopse (
              :parreferenciapago,
              :parcodord,
              :parresmul ,
              :parespues
              );
              END;";

            $conn = $this->db->conn_id;
            $stmt = oci_parse($conn, $sql);
            $parcodord = $codordenes;
            $parreferenciapago = $referenciapago;
            $parresmul = 'Se pago exitosamente.';
            $parespues = '';
            //TIPO NUMBER INPUT
            oci_bind_by_name($stmt, ':parreferenciapago', $parreferenciapago, 32);
            //TIPO NUMBER INPUT
            oci_bind_by_name($stmt, ':parcodord', $parcodord, 1000);
            //TIPO NUMBER INPUT
            oci_bind_by_name($stmt, ':parresmul', $parresmul, 32);
            //TIPO NUMBER OUTPUT
            oci_bind_by_name($stmt, ':parespues', $parespues, 32);

            if (!@oci_execute($stmt)) {
                $e = oci_error($stmt);
                var_dump($e);
            }
            if ($parespues == 1) {
                array_push($pagas, $value);
            } else {
                array_push($fallopago, $value);
            }
        }
        if (is_null($fallopago)) {
            $this->pago($fallopago);
            return 'Fallo pago';
        } else {
            return 'Pago Ok';
        }
    }

    public function pagarant() {
        $this->verificarPerfilCo();
        $post = $this->input->post();
        if ($post) {
            $ordenes = $post['ordenes'];
            $ordenespagas = '';
            if (!is_null($ordenes)) {
                // se busca el bolsillo de credito a usar
                $sql = "BEGIN 
                       MODGENERI.GENPKGWEBSERVICE.PRCBOLCREDPRED
                                (:parentidad
                                ,:parbolsillo
                                ,:parcampana
                                ,:parrespuesta);
                                END;";
                $conn = $this->db->conn_id;
                $stmt = oci_parse($conn, $sql);
                //$parentidad = $this->session->userdata("pkentidad");
                $parentidad = $_SESSION['pkentidad'];
                oci_bind_by_name($stmt, ':parentidad', $parentidad, 32);
                //TIPO NUMBER INPUT
                oci_bind_by_name($stmt, ':parbolsillo', $parbolsillo, 32);
                //TIPO VARCHAR2 OUTPUT
                oci_bind_by_name($stmt, ':parcampana', $parcampana, 32);

                oci_bind_by_name($stmt, ':parrespuesta', $parrespuesta, 32);

                if (!@oci_execute($stmt)) {
                    $e = oci_error($stmt);
                    var_dump("{$e['message']}");
                    $data['error'] = 4;
                    $data['respues'] = 'El pago con Anticipo no esta disponible en estos momentos.';
                }

                if ($parrespuesta == 1) {
                    $globalOrdenes = "";
                    $totalOrden = count($ordenes) - 1;
                    for ($i = 0; $i < count($ordenes); $i++) {
                        $globalOrdenes = $globalOrdenes . $ordenes[$i];
                        if ($i < $totalOrden) {
                            $globalOrdenes = $globalOrdenes . ",";
                        }
                    }


                    $referenciapago = $this->generarreferenciapago($globalOrdenes, 0);


                    $sql = "BEGIN  modpropag.ppapkgactualizaciones.prcpaabolsillo
                                (parreferenciapago=>:parreferenciapago
                                ,parbolsillo=>:parbolsillo
                                ,parentida=>:parentida
                                ,parcodorden=>:parcodorden
                                ,parusuariocreacion=>:parusuariocreacion
                                ,parrespuest=>:parrespuest );
                                END;";

                    $conn = $this->db->conn_id;
                    $stmt = oci_parse($conn, $sql);
                    //$usuario = $this->session->userdata('usuario')['USUARIO_ACCESO'];
                    $usuario = $_SESSION['usuario']['USUARIO_ACCESO'];
                    //$parentidad = $this->session->userdata("pkentidad");
                    $parentidad = $_SESSION['pkentidad'];

                    oci_bind_by_name($stmt, ':parreferenciapago', $referenciapago, 32);
                    //TIPO NUMBER INPUT
                    oci_bind_by_name($stmt, ':parbolsillo', $parbolsillo, 32);
                    //TIPO NUMBER INPUT
                    oci_bind_by_name($stmt, ':parentida', $parentidad, 30);
                    oci_bind_by_name($stmt, ':parcodorden', $globalOrdenes, 1000);
                    //TIPO VARCHAR2 OUTPUT
                    oci_bind_by_name($stmt, ':parrespuest', $parrespuesta, 32);
                    oci_bind_by_name($stmt, ':parusuariocreacion', $usuario, 32);

                    if (!@oci_execute($stmt)) {
                        $e = oci_error($stmt);

                        $data['error'] = 4;
                        $data['respues'] = 'No se puede crear el Pedido en estos momentos. ';
                        var_dump("{$e['message']}");
                        var_dump("pago");
                    }
                    if ($parrespuesta == 1) {
                        array_push($pagas, $value);
                    } else {
                        array_push($fallopago, $value);
                    }
                    /* 6040 NO TIENE SALDO SUFICIENTE PARA PAGAR
                      6041 NO SE PUEDE PAGAR UN ANTICIPO CON EL BOLSILLO ANTICIPO
                      6043 ESTA BLOQUEADO POR MORA PARA REALIZAR EL PAGO DE FACTURAS PENDIENTES
                      7013 NO SE SELECCIONO LA EMPRESA PARA REALIZAR EL PAGO
                      20020 ACTUALMENTE SE TIENE UNA DEUDA CON LA ENITDAD
                     */
                }
            }
        }

        if (!is_null($fallopago)) {
            $this->pago($fallopago);
        } else {
            // $data['success']=1;
            redirect('portal/ordenPedido/lista/' . $parrespuesta);
        }
    }

    /* recibe por parametro las ordenes de pago para generar la referencia de pago */

    public function generarreferenciapago($ordenes, $pse = 0) {
        $sql = " BEGIN 
            modpropag.ppapkgactualizaciones.prcconsumirreferenciapago
            (parreferenciapago=>:parreferenciapago
             ,parordenes=>:parordenes 
             ,paractivarpse=>:paractivarpse); END;";


        $conn = $this->db->conn_id;
        $stmt = oci_parse($conn, $sql);
        $parordenes = $ordenes;

        if ($pse = 1) {
            $paractivarpse = $pse;
        } else {
            $paractivarpse = 0;
        }

        //TIPO NUMBER INPUT
        oci_bind_by_name($stmt, ':parreferenciapago', $parreferenciapago, 100);
        //TIPO NUMBER INPUT
        oci_bind_by_name($stmt, ':parordenes', $parordenes, 4000);
        //TIPO VARCHAR2 INTPUT
        oci_bind_by_name($stmt, ':paractivarpse', $paractivarpse, 4000);
        if (!@oci_execute($stmt)) {
            $e = oci_error($stmt);
            var_dump("{$e['message']}");
            //exit();
            return 0;
        } else {
            return $parreferenciapago;
        }
    }

}
