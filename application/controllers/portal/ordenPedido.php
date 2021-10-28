<?php
session_start();
include_once '/wsonline2/factura.php';
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class OrdenPedido extends CI_Controller {

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
        if (( $rol != 45 ) and ( $rol != 47) and ( $rol != 58)) {
            redirect('/portal/principal/pantalla');
        }
    }

    public function crearfactura() {
        require_once("application/controllers/wsonline2/factura.php");

        $libfactura = new Factura();
        $post = $this->input->post();
        $count = count($post['ordenes']);
        $ordenesConsultar = implode(',', $post['ordenes']);
        if (!is_null($ordenesConsultar)) {
            $factura = $this->db->query("SELECT DISTINCT  PK_FACTUR_CODIGO FROM 
                                             MODFACTUR.FACTBLFACORD 
                                            WHERE PK_ORDCOM_CODIGO IN ($ordenesConsultar)");


            $numeroFactura = $factura->result_array;



            $facturas = array();
            for ($i = 0; $i <= $count - 1; $i++) {
//                $url = $libfactura->crear($numeroFactura[$i]['PK_FACTUR_CODIGO']); //comentado 13/07/2020
                //Ronald 13/07/2020 facturacion comfiar
                //consulta tabla en la que se guarda pdf al aneviar factura a comfiar
                $pk_factura = $numeroFactura[$i]['PK_FACTUR_CODIGO'];
                $sqlfacturapdf = $this->db->query("select url_pdf from MODFACTUR.factblxmlcomfiar
                                where pk_factura_codigo = $pk_factura
                                    and pk_tipo_xml_codigo=1");
                $urlFact = $sqlfacturapdf->result_array[0]['URL_PDF'];
                if (empty($urlFact)) {
                    //comentado 8/10/2020
//                    $urlFact = $libfactura->crear($numeroFactura[$i]['PK_FACTUR_CODIGO']);
                    redirect('portal/ordenPedido/lista?sinFact');
                }
//                array_push($facturas, $url); //comentado 13/07/2020
                array_push($facturas, $urlFact);
            }
            //$this->session->set_userdata(array('facturas' => $facturas));
            $_SESSION['facturas']= $facturas;
            //$this->lista(0, 0, $facturas);            
            redirect('portal/ordenPedido/lista');
        } else {
            redirect('portal/ordenPedido/lista?OrderError');
        }
    }

    public function lista($anuladas = null, $error = 0) {
        $this->session->set_userdata(array("pedidoAbono" => null));
        $this->session->set_userdata(array("llavesTemp" => null));
        $this->verificarPerfilCo();

        // $post= $this->input->post();
        //$this->session->set_userdata(array('facturas'=>null));
        //$empresa = $this->session->userdata("entidad");
        $empresa = $_SESSION['entidad'];
        $data['empresa'] = $empresa['NOMBREEMPRESA'];
        //$usuario = $this->session->userdata("usuario");
        $usuario = $_SESSION['usuario'];
        $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
        //$pkEmpresa = $this->session->userdata("pkentidad");
        $pkEmpresa = $_SESSION['pkentidad'];
        // ordenes pendientes por pagar
        $ordenes = $this->db->query("select CODIGOORDEN,NOMBREORDEN,VALOR,NUMERO_FACTURA from (    
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
            ORDER  BY 1 desc )
            where rownum < 51");
        $data['ordenes'] = $ordenes->result_array;

        foreach ($data['ordenes'] as $key => $value) {
            $llaves = $value['CODIGOORDEN'] . ',' . $llaves;
        }
        if (is_null($llaves)) {
            $llaves = '0';
        } else {
            $llaves = substr($llaves, 0, -1);
        }
        //ingresos propios
        $dettar = $this->db->query("select 
                        facord.pk_ordcom_codigo ORDCOM,
                        sum(detpab.monto)  totalterceros
                            from
                                MODFACTUR.factblfacord facord
                                JOIN MODPROPAG.ppatblordcom ordcom
                                ON facord.pk_ordcom_codigo=ordcom.pk_ordcom_codigo
                                AND facord.pk_ordcom_codigo in ({$llaves} )
                                JOIN MODPROPAG.ppatblpedabon pedabon 
                                ON pedabon.pk_orden=ordcom.pk_ordcom_codigo
                                JOIN MODPROPAG.ppatbldetpab detpab
                                ON detpab.pk_pedido=pedabon.pk_pedabon_codigo
                                JOIN MODPRODUC.PROTBLPRODUC producto
                                ON producto.pk_produc_codigo=detpab.pk_producto
                                group by facord.pk_ordcom_codigo");
        $data['ingrePro'] = $dettar->result_array;

        //ingresos terceros
        $ingreTerce = $this->db->query("select sum(te.TOTALPROPIOS) SUBTOTALPROPIOS,te.ORDCOM from( SELECT  
            facord.pk_ordcom_codigo ORDCOM,
            sum (cantidad) * valor_unit TOTALPROPIOS
			FROM  MODFACTUR.FACTBLFACORD facord
			JOIN MODPROPAG.PPATBLDETORD detord
			ON facord.pk_ordcom_codigo = detord.pk_orden_compra 
			AND  facord.pk_ordcom_codigo in ({$llaves} )
			INNER JOIN  MODPRODUC.PROTBLPRODUC produc
			ON produc.PK_PRODUC_CODIGO = detord.PK_PRODUCTO
			and produc.pk_tippro_codigo=1
            AND detord.pk_pedido is not null
            group by facord.pk_ordcom_codigo ,valor_unit
            UNION ALL
            SELECT   	
            facord.pk_ordcom_codigo ORDCOM,
            sum (cantidad) * valor_unit TOTALPROPIOS
			FROM  MODFACTUR.FACTBLFACORD facord
			JOIN MODPROPAG.PPATBLDETORD detord
			ON facord.pk_ordcom_codigo = detord.pk_orden_compra 
			AND facord.pk_ordcom_codigo in ({$llaves} )
			INNER JOIN  MODPRODUC.PROTBLPRODUC produc
			ON produc.PK_PRODUC_CODIGO = detord.PK_PRODUCTO
            INNER JOIN MODPRODUC.PROTBLPRODUC productoabonado
            ON detord.PK_PRODUCTO_ABONADO=productoabonado.PK_PRODUC_CODIGO
			and produc.pk_tippro_codigo=3
            group by  facord.pk_ordcom_codigo ,valor_unit)te GROUP BY te.ORDCOM");
        $data['ingreTer'] = $ingreTerce->result_array;

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
        //$ultimaconexion = $this->session->userdata("ultimaconexion");
        $ultimaconexion = $_SESSION['ultimaconexion'];
        $modelopago = $this->db->query("select clipar.dato
                                    from modcliuni.clitblclipar clipar
                                    join modcliuni.clitblparame par 
                                    on par.pk_parame_codigo = clipar.pk_parame_codigo 
                                    where clipar.pk_ent_codigo =$pkEmpresa
                                    and clipar.pk_parame_codigo = 1");

        $modelopago = $modelopago->result_array[0];
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

        $data['modelopago'] = $modelopago;
        $data['ultimaconexion'] = $ultimaconexion;
        $data['impues'] = $impues->result_array;
        $data['anuladas'] = $anuladas;
        $data['menu'] = "pagos";
        $data['error'] = $error;


        //$facturas = $this->session->userdata('facturas');
        $facturas = $_SESSION['facturas'];
        $data['facturas'] = $facturas;
        $this->load->view('portal/templates/header2', $data);
        $this->load->view('portal/ordenPedido/lista', $data);
        $this->load->view('portal/templates/footer', $data);
    }

    public function anularOrden($pedido) {
        $this->verificarPerfilCo();
        $post = $this->input->post();
        if ($post) {
            $ordenes = $post['ordenes'];
            $ordenesanuladas = '';
            if (!is_null($ordenes)) {
                foreach ($ordenes as $value) {

                    $sql = "BEGIN  MODPROPAG.PPAPKGPEDIDOSABONO.prcanularorden
                                (:parcodorden
                                ,:parusuario
                                ,:parrespuesta );
                                END;";

                    $conn = $this->db->conn_id;
                    $stmt = oci_parse($conn, $sql);
                    //$usuario = $this->session->userdata('usuario')['USUARIO_ACCESO'];
                    $usuario = $_SESSION['usuario']['USUARIO_ACCESO'];
                    //var_dump($usuario);
                    //exit();
                    //TIPO NUMBER INPUT
                    oci_bind_by_name($stmt, ':parcodorden', $value, 32);
                    //TIPO NUMBER INPUT
                    oci_bind_by_name($stmt, ':parusuario', $usuario, 32);
                    //TIPO VARCHAR2 OUTPUT
                    oci_bind_by_name($stmt, ':parrespuesta', $parrespuesta, 32);
                    if (!@oci_execute($stmt)) {
                        $e = oci_error($stmt);
                        var_dump("{$e['message']}");
                        $data['error'] = 4;
                        $data['respues'] = 'No se puede crear el Pedido en estos momentos. ';
                    }
                    if ($parrespuesta == 1) {
                        $ordenesanuladas = $ordenesanuladas . ' #' . $value;
                    }
                }
            }
        }
        $this->lista($ordenesanuladas);
    }

}
