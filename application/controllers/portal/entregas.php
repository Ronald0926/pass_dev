<?php
session_start();
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Entregas extends CI_Controller {

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

    public function verificarPerfilMo() {
        //$rol = $this->session->userdata("rol");
        $rol = $_SESSION['rol'];
        if (($rol != 45) and ( $rol != 47) and ( $rol != 46)) {
            redirect('/portal/principal/pantalla');
        }
    }

    public function lista($pantalla = 0) {
        $this->session->set_userdata(array("pedidoAbono" => null));
        $this->session->set_userdata(array("llavesTemp" => null));

        $this->verificarPerfilMo();
        //$empresa = $this->session->userdata("entidad");
        $empresa = $_SESSION['entidad'];
        $data['empresa'] = $empresa['NOMBREEMPRESA'];
        //$usuario = $this->session->userdata("usuario");
        $usuario = $_SESSION['usuario'];
        $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
        $pkcustodio = $usuario['PK_ENT_CODIGO'];
        //$campana = $this->session->userdata("campana");
        $campana = $_SESSION['campana'];
        $pedidos = $this->db->query("select PK_PEDIDO_CODIGO NUMEROPEDIDO,
        cantidad.cantidad CANTIDADTARJETAS,
        nvl(cantactivas.cantactivas,0) CANTTARACTIVAS,
        pedidos.fecha_creacion FECHASOLICITUD,
        envios.fechaentregado FECHARECIBIDO,
        nvl(envios.nomestado,estped.nombre) ESTADOPEDIDO
        ,envios.estado CODESTADOENVIO
        ,envios.pk_envio_codigo CODIGOENVIO
        from modalista.alitblpedido PEDIDOS
        join modalista.alitblestped estped
        ON pedidos.ALITBLESTPED_PK_ESTPED_CODIGO=estped.pk_estped_codigo
        join (select pedid.pk_pedido_codigo pedido,count(1) cantidad
            from MODALISTA.alitblpedido pedid
            join MODALISTA.alitbldetped detped
            on detped.pk_pedido=pedid.pk_pedido_codigo
            group by pedid.pk_pedido_codigo) cantidad
        ON cantidad.pedido=pedidos.pk_pedido_codigo
         left join (select pedido.pk_pedido_codigo pedactiva
         , count(1) cantactivas from MODTARHAB.TARTBLTARJET tarjeta
             JOIN MODALISTA.ALITBLDETPED detped 
              ON tarjeta.pk_detped_codigo=detped.pk_detped_codigo
            JOIN MODALISTA.ALITBLPEDIDO pedido 
              ON detped.pk_pedido= pedido.pk_pedido_codigo
            JOIN MODALISTA.ALITBLDESDET desdet ON desdet.alitbldetped_pk_detped_codigo=detped.pk_detped_codigo
            and desdet.alitblestdet_pk_estped_codigo=9
                where PK_PEDIDO_CODIGO=PK_PEDIDO_CODIGO 
         group by pedido.pk_pedido_codigo) cantactivas
        ON cantactivas.pedactiva =pedidos.pk_pedido_codigo
        left join (
            SELECT PK_PEDIDO PEDIDO, PK_ESTADO ESTADO,
                estenv.nombre nomestado,
                env.fecha_actualizacion actualizacion,
                env.fecha_entregado fechaentregado,
                env.pk_envio_codigo pk_envio_codigo
            FROM modalista.alitbldetenv detenv
            JOIN modalista.alitblenvio env
            ON  detenv.pk_envio=env.pk_envio_codigo
            JOIN modalista.alitblestenv estenv
            ON env.pk_estado=estenv.pk_estenv_codigo ) envios
        ON pedidos.pk_pedido_codigo=envios.pedido
                WHERE PK_EMPRESA={$empresa ['PK_ENT_CODIGO']}
                 and pedidos.pk_campan_codigo={$campana}
                 order by PK_PEDIDO_CODIGO desc");
        $data['pedidos'] = $pedidos->result_array;
        //$ultimaconexion = $this->session->userdata("ultimaconexion");
        $ultimaconexion = $_SESSION['ultimaconexion'];
        $data['ultimaconexion'] = $ultimaconexion;
        $data['menu'] = "entregas";
        // var_dump($data);
        $this->load->view('portal/templates/header2', $data);
        $this->load->view('portal/entregas/lista', $data);
        $this->load->view('portal/templates/footer', $data);
    }

    public function confirmarEntrega($numeroPedido = 0) {
        $this->verificarPerfilMo();
        $post = $this->input->post();
        if ($post) {
            $detped = $post['checks'];
            foreach ($detped as $key => $value) {

                $sql = "BEGIN MODGENERI.GENPKGWEBSERVICE.PRCACTIVARTARJETAS (
                        :pardetped,
                        :parrespuest ,
                        :pardetrespuest
                       );
                    END;";

                $conn = $this->db->conn_id;
                $stmt = oci_parse($conn, $sql);
                $pardetped = $value;
                $parespues = '';
                $parrespuesdet = '';
                //TIPO NUMBER INPUT
                oci_bind_by_name($stmt, ':pardetped', $pardetped, 32);
                //TIPO NUMBER INPUT
                oci_bind_by_name($stmt, ':parrespuest', $parespues, 32);
                //TIPO NUMBER OUTPUT
                oci_bind_by_name($stmt, ':pardetrespuest', $parrespuesdet, 32);

                if (!@oci_execute($stmt)) {
                    $e = oci_error($stmt);
                    var_dump($e);
                } else {
                    $data['error'] = $parespues;
                }
            }

            //var_dump($post);
            // exit();
        }
        //$empresa = $this->session->userdata("entidad");
        $empresa = $_SESSION['entidad'];
        $data['empresa'] = $empresa['NOMBREEMPRESA'];
        //$usuario = $this->session->userdata("usuario");
        $usuario = $_SESSION['usuario'];
        $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
        $pkcustodio = $usuario['PK_ENT_CODIGO'];
        $tarjetas = $this->db->query("SELECT detped.pk_detped_codigo CODDETPED,
                                    tarjeta.numero NUMEROTARJETA,
                                    tarjeta.pk_esttar_codigo ESTADO
                                    ,desdet.alitblestdet_pk_estped_codigo ESTADOENVIO
                                    ,producto.nombre_producto PRODUCTO
                                    ,cliente.DOCUMENTO
                                    ,NVL(cliente.RAZON_SOCIAL,cliente.NOMBRE || ' ' || cliente.APELLIDO) NOMBRE                                  
                                    FROM MODALISTA.ALITBLPEDIDO pedido 
                                    JOIN MODALISTA.ALITBLDETPED detped
                                    ON pedido.pk_pedido_codigo= detped.pk_pedido
                                    JOIN MODALISTA.ALITBLDESDET desdet
                                    ON desdet.alitbldetped_pk_detped_codigo=detped.pk_detped_codigo
                                    and desdet.pk_desdet_codigo = (SELECT MAX(PK_DESDET_CODIGO)
                                    FROM  MODALISTA.ALITBLDESDET 
                                    WHERE ALITBLDETPED_PK_DETPED_CODIGO=detped.pk_detped_codigo) 
                                    JOIN MODTARHAB.TARTBLTARJET tarjeta
                                    ON tarjeta.pk_detped_codigo=detped.pk_detped_codigo
                                    JOIN MODTARHAB.TARTBLCUENTA cuenta
                                    ON cuenta.pk_tartblcuenta_codigo=tarjeta.pk_tartblcuenta_codigo
                                    JOIN MODPRODUC.PROTBLPRODUC producto
                                    ON producto.pk_produc_codigo=cuenta.pk_produc_codigo
                                    join MODCLIUNI.CLITBLENTIDA cliente
                                    ON cliente.PK_ENT_CODIGO=cuenta.PK_ENT_CODIGO_TH
                                    WHERE PK_PEDIDO_CODIGO=$numeroPedido ");

        $data['tarjetas'] = $tarjetas->result_array;
        //$ultimaconexion = $this->session->userdata("ultimaconexion");
        $ultimaconexion = $_SESSION['ultimaconexion'];
        $data['ultimaconexion'] = $ultimaconexion;

        $this->load->view('portal/templates/header2', $data);
        $this->load->view('portal/entregas/confirmarEntrega', $data);
        $this->load->view('portal/templates/footer', $data);
    }

}
