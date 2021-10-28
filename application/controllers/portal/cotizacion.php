<?php
session_start();
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Cotizacion extends CI_Controller {

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
        if (( $rol != 45 ) and ( $rol != 47)) {
            redirect('/portal/principal/pantalla');
        }
    }

    public function cotizar($error = 0) {
        $this->verificarPerfilCo();
        //$entidad = $this->session->userdata("entidad");
        $entidad = $_SESSION['entidad'];
        $data['entidad'] = $entidad;
        $pk_ent_codigo = $entidad['PK_ENT_CODIGO'];

        $lineaproductos = $this->db->query("select lower(NOMBRE)NOMBRE, PK_LINPRO_CODIGO CODIGO FROM MODPRODUC.PROTBLLINPRO");

        $data['lineaproductos'] = $lineaproductos->result_array;

        $productos = $this->db->query("select producto1.PK_PRODUC_CODIGO CODIGO,producto1.NOMBRE_PRODUCTO ,producto1.PK_TIPPRO_CODIGO Codigo_Producto,
                                    NVL(producto1.PK_LINPRO_CODIGO,0) CODIGOL,
                                    NVL(PARAMETRO.CANTIDAD,0) CANTIDAD,
                                    NVL(PARAMETRO.ACTIVO,0) ACTIVO
                                    FROM MODPRODUC.protblproduc producto1
                                    LEFT JOIN
                                    (SELECT parametro.pk_producto_codigo CODIGO,'1' ACTIVO,parametro.CANTIDAD
                                           from MODCOMERC.comtblparame parametro 
                                     JOIN MODCOMERC.comtblproces proceso
                                    ON proceso.pk_proces_codigo=parametro.pk_proces_codigo
                                     JOIN MODCOMERC.comtblcotiza cotizacion
                                    on cotizacion.pk_cotiza_codigo=proceso.pk_cotiza_codigo
                                    JOIN MODPRODUC.PROTBLPRODUC producto 
                                    ON producto.pk_produc_codigo=parametro.pk_producto_codigo
                                    where  cotizacion.pk_entida_cliente=$pk_ent_codigo
                                    and cotizacion.pk_estado_codigo=1
                                    and proceso.pk_estado_codigo=1) PARAMETRO
                                    on producto1.pk_produc_codigo = parametro.CODIGO");
        $data['productos'] = $productos->result_array;
        $data['error'] = $error; //no se ha seleccionado ningun producto
        //$ultimaconexion = $this->session->userdata("ultimaconexion");
        $ultimaconexion = $_SESSION['ultimaconexion'];
        $data['ultimaconexion'] = $ultimaconexion;
        //$empresa = $this->session->userdata("entidad");
        $empresa = $_SESSION['entidad'];
        $data['empresa'] = $empresa['NOMBREEMPRESA'];
        //$usuario = $this->session->userdata("usuario");
        $usuario = $_SESSION['usuario'];
        $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
        $data['menu'] = "cotizacion";

        // var_dump($data);

        $this->load->view('portal/templates/header2', $data);
        $this->load->view('portal/cotizacion/cotizar', $data);
        $this->load->view('portal/templates/footer', $data);
    }

    public function cotizar2($pantalla = 0) {
        $post = $this->input->post();

        if ($post) {
            unset($this->session->userdata['Cotizacion']);
            unset($this->session->userdata['data_produc_cant']);
            //Cantidad tarjetas que tiene activas 
            $countCant = 0;
            //Cantidad de tarjetas que ingreso para la nueva cotizacion
            $countCantNew = 0;
            //cantidad de productos que tiene activos 
            $cantProductosActivos = 0;
            //cantidad de productos que selecciono  para la nueva cotizacion
            $cantProductos = 0;


            $pk_ent_codigo = $this->session->userdata["entidad"]['PK_ENT_CODIGO'];
            $productos = $this->db->query("select producto1.PK_PRODUC_CODIGO CODIGO,producto1.NOMBRE_PRODUCTO ,producto1.PK_TIPPRO_CODIGO Codigo_Producto,
                                    NVL(producto1.PK_LINPRO_CODIGO,0) CODIGOL,
                                    NVL(PARAMETRO.CANTIDAD,0) CANTIDAD,
                                    NVL(PARAMETRO.ACTIVO,0) ACTIVO
                                    FROM MODPRODUC.protblproduc producto1
                                    LEFT JOIN
                                    (SELECT parametro.pk_producto_codigo CODIGO,'1' ACTIVO,parametro.CANTIDAD
                                           from MODCOMERC.comtblparame parametro 
                                     JOIN MODCOMERC.comtblproces proceso
                                    ON proceso.pk_proces_codigo=parametro.pk_proces_codigo
                                     JOIN MODCOMERC.comtblcotiza cotizacion
                                    on cotizacion.pk_cotiza_codigo=proceso.pk_cotiza_codigo
                                    JOIN MODPRODUC.PROTBLPRODUC producto 
                                    ON producto.pk_produc_codigo=parametro.pk_producto_codigo
                                    where  cotizacion.pk_entida_cliente=$pk_ent_codigo
                                    and cotizacion.pk_estado_codigo=1
                                    and proceso.pk_estado_codigo=1) PARAMETRO
                                    on producto1.pk_produc_codigo = parametro.CODIGO");
            $productos = $productos->result_array;


            //obtiene PK_PROCES_CODIGO
            $PK_PROCES_CODIGO = $this->db->query("SELECT  parametro.pk_proces_codigo,proceso.pk_cotiza_codigo
                                           from MODCOMERC.comtblparame parametro 
                                     JOIN MODCOMERC.comtblproces proceso
                                    ON proceso.pk_proces_codigo=parametro.pk_proces_codigo
                                     JOIN MODCOMERC.comtblcotiza cotizacion
                                    on cotizacion.pk_cotiza_codigo=proceso.pk_cotiza_codigo
                                    JOIN MODPRODUC.PROTBLPRODUC producto 
                                    ON producto.pk_produc_codigo=parametro.pk_producto_codigo
                                    where  cotizacion.pk_entida_cliente=$pk_ent_codigo
                                    and cotizacion.pk_estado_codigo=1
                                    and proceso.pk_estado_codigo=1 and
                                    rownum = 1 ");
            $PK_PROCES_CODIGO = $PK_PROCES_CODIGO->result_array;
            $PK_PROCES_CODIGO_C = $PK_PROCES_CODIGO[0]['PK_PROCES_CODIGO'];
            $pk_cotiza_codigo = $PK_PROCES_CODIGO[0]['PK_COTIZA_CODIGO'];
//            var_dump("VALOR pk_cotiza_codigo=" . $pk_cotiza_codigo);
            //Array con idProducto y cantidad ingresada 
            $idProductos = array();


            for ($i = 0; $i < count($productos); $i++) {
                if ($productos[$i]['ACTIVO'] && $productos[$i]['CODIGO_PRODUCTO'] == 1) {
                    $countCant += $productos[$i]['CANTIDAD'];
                    $cantProductosActivos++;
                }
                if (!$productos[$i]['ACTIVO'] && $productos[$i]['CODIGO_PRODUCTO'] == 1 && $post['value'][$i] != "") {
                    $countCantNew += $post['value'][$i];
                    $cantProductos++;
                    $obj = (object) [
                                'nombre_producto' => $productos[$i]['NOMBRE_PRODUCTO'],
                                'id_producto' => $productos[$i]['CODIGO'],
                                'cantidad_tarjetas' => $post['value'][$i]
                    ];
                    array_push($idProductos, $obj);
//                    var_dump("check=" . $productos[$i]['CODIGO']);
//                    var_dump("value=" . $post['value'][$i]);
                }
                if ($productos[$i]['ACTIVO'] && $productos[$i]['CODIGO_PRODUCTO'] == 2) {
                    $abono_ant = $productos[$i]['CANTIDAD'];
                }
            }

            $this->session->set_userdata('data_produc_cant', $idProductos);

//            var_dump("Can prodctos=" . $cantProductos);
//            var_dump("Can prodctos activos=" . $cantProductosActivos);
            //El total abono es el valor anterior de abono activo mas el nuevo valor ingresado para los productos y cantidad de tarjetas sellecionadas
            $totalAbonoNuevo = $abono_ant + $post['facturacion'];
            $valor_abono = round(($post['facturacion'] / $cantProductos), 0);
//            var_dump("TOTAL ABONO=" . $totalAbonoNuevo);
//            var_dump("ABONO ____" . $valor_abono);
            //se obtiene PK_plan_Codigo
            $pk_plan_codigo = $this->db->query("select PK_PLAN_CODIGO
                                        from MODPRODUC.PROTBLPLAN 
                                        where $totalAbonoNuevo>PAGO_MINIMO and $totalAbonoNuevo<=PAGO_MAXIMO");
            $pk_plan_codigo = $pk_plan_codigo->result_array;
            $id_planprod = $pk_plan_codigo[0]['PK_PLAN_CODIGO'];

            //Total cantidad de productos 
            $NewCantidadProductos = $cantProductos + $cantProductosActivos;

            //Total tarjetas que tiene activas mas la cantidad de tarjetas nuevas que ingreso 
            $totalTarjetas = $countCantNew + $countCant;
            //Abono promedio por tarjetas
            $abonoPorTarjeta = round(($totalAbonoNuevo / $totalTarjetas), 0);
            // CONSULTA EL VALOR DEL PORCENTAJE SEGUN EL PROMEDIO, LA CANTIDAD DE PRODUCTOS DIFERENTES Y EL PLAN AL QUE APLICA
            $conporcentajecentro = $this->db->query("select porcentaje,valor_fijo 
                                        from MODPRODUC.PROTBLCOMPLA
                                        where cantidad_producto=$NewCantidadProductos
                                        and pk_plan_codigo=$id_planprod
                                        and $abonoPorTarjeta> minimo and $abonoPorTarjeta <=maximo");
            $conporcentajecentro = $conporcentajecentro->result_array;
            $porcentajecentro = $conporcentajecentro[0]['PORCENTAJE'];

            $conporcentajemenor = $this->db->query("select * from (select *
                                        from MODPRODUC.PROTBLCOMPLA
                                        where cantidad_producto=$NewCantidadProductos
                                        and pk_plan_codigo=$id_planprod
                                        and $abonoPorTarjeta <= minimo order By minimo asc)
                                        orden where rownum=1");
            $conporcentajemenor = $conporcentajemenor->result_array;
            $porcentajemenor = $conporcentajemenor[0]['PORCENTAJE'];

            $conporcentajemayor = $this->db->query("select * from (select *
                                        from MODPRODUC.PROTBLCOMPLA
                                        where cantidad_producto=$NewCantidadProductos
                                        and pk_plan_codigo=$id_planprod
                                        and $abonoPorTarjeta> maximo order By maximo desc)
                                        orden where rownum=1");
            $conporcentajemayor = $conporcentajemayor->result_array;
            $porcentajemayor = $conporcentajemayor[0]['PORCENTAJE'];


            if ($porcentajecentro == 0) {
                $porcentajecentro = $conporcentajecentro[0]['VALOR_FIJO'];
                $porcentajemayor = $conporcentajecentro[0]['VALOR_FIJO'];
            }
            if ($porcentajemayor == 0) {
                $porcentajemayor = $conporcentajemayor[0]['VALOR_FIJO'];
            }

//            print_r($porcentajemayor[0]);
//            var_dump("POrcentaje=" . $porcentajecentro);
//            var_dump("POrcentaje mayor=" . $porcentajemayor);
//            var_dump("POrcentaje menor=" . $porcentajemenor);
//
//            var_dump("Cantidad productos total=" . $NewCantidadProductos);
//            var_dump("pk PLAN=" . $id_planprod);
//            var_dump("Nueva facturacion=".$post['facturacion']);
//            var_dump("Count cant=" . $countCant);
//            var_dump("Cant nuevas tarjetas=" . $countCantNew);
//            var_dump("Abono anterior=" . $abono_ant);
//            var_dump("Tarjetas nuevas=" . $totalTarjetas);
//            var_dump("Abono nuevo=" . $totalAbonoNuevo);
//            var_dump("Abono por tarejta=" . $abonoPorTarjeta);
//             var_dump($post['value'][15]);
//            $parametrosSession = array();
//            $objdata = (object) [
//                        'valor_abono' => $valor_abono,
//                        'total_tarjetas' => $countCantNew,
//                        'total_abono' => $totalAbonoNuevo,
//                        'abono_por_tarjeta' => $abonoPorTarjeta,
//                        'pk_proces_codigo' => $PK_PROCES_CODIGO_C,
//                        'pk_cotiza_codigo' => $pk_cotiza_codigo
//            ];
//            $this->session->set_userdata('Cotizacion', $parametrosSession);
//            
            $this->session->set_userdata('valor_abono', $valor_abono);
            $this->session->set_userdata('total_tarjetas', $countCantNew);
            $this->session->set_userdata('total_abono', $totalAbonoNuevo);
            $this->session->set_userdata('abono_por_tarjeta', $abonoPorTarjeta);
            $this->session->set_userdata('pk_proces_codigo', $PK_PROCES_CODIGO_C);
            $this->session->set_userdata('pk_cotiza_codigo', $pk_cotiza_codigo);
        }

        $data['productosCheck'] = $idProductos;
        $data['porcentajecentro'] = $porcentajecentro;
        $data['porcentajemenor'] = $porcentajemenor;
        $data['porcentajemayor'] = $porcentajemayor;
        $entidad = $this->session->userdata("entidad");
        $data['entidad'] = $entidad;
        $ultimaconexion = $this->session->userdata("ultimaconexion");
        $data['ultimaconexion'] = $ultimaconexion;
        $empresa = $this->session->userdata("entidad");
        $data['empresa'] = $empresa['NOMBREEMPRESA'];
        $usuario = $this->session->userdata("usuario");
        $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
        $data['menu'] = "cotizacion";
        $this->load->view('portal/templates/header2', $data);
        $this->load->view('portal/cotizacion/cotizar2', $data);
        $this->load->view('portal/templates/footer', $data);
    }

    public function activar() {
        $post = $this->input->post();
//          unset($this->session->userdata['datacotizacion']);
     //  var_dump($this->session->userdata);
//        print_r($data_new_pro_cant);
//        print_r($dataCoti);
        if ($post) {
            if ($post['porcentaje'] != '') {
//                $dataCoti = $this->session->userdata("Cotizacion");
//                $array = get_object_vars($dataCoti);
                $pk_cotiza_codigo = $this->session->userdata("pk_cotiza_codigo"); /* $array['pk_cotiza_codigo']; */
                $pk_proces_codigo = $this->session->userdata("pk_proces_codigo"); /* $array['pk_proces_codigo']; */
                $par_abono = $this->session->userdata("valor_abono"); /* $array['valor_abono']; */

                $usuario = $this->session->userdata("usuario");
                $usuario_creacion = $usuario['USUARIO_ACCESO'];
             //  var_dump("Valor par_abono_1=" . $par_abono);
            //   var_dump("Valor pk_proces_codigo=" . $pk_proces_codigo);
             //  var_dump("Valor usuario_creacion=" . $usuario_creacion);
             //  var_dump("Valor pk_cotiza_codigo=" . $pk_cotiza_codigo);
                //crear cituzacuibb
                $sql = "BEGIN MODCOMERC.COMPKMODACTUALIZAR.PRCCREARRECOTIZAR(
                                     :parpkoldproceso,
                                     :parpkcotizacion,
                                     :parusuario,
                                     :parnewproceso,
                                     :parrespuesta); END;";
                $conn = $this->db->conn_id;
                $stmt = oci_parse($conn, $sql);
                //TIPO NUMBER 
                oci_bind_by_name($stmt, ':parpkoldproceso', $pk_proces_codigo, 32);
                //TIPO NUMBER
                oci_bind_by_name($stmt, ':parpkcotizacion', $pk_cotiza_codigo, 32);
                //TIPO VARCHAR
                oci_bind_by_name($stmt, ':parusuario', $usuario_creacion, 32);
                //TIPO NUMBER
                oci_bind_by_name($stmt, ':parnewproceso', $parnewproceso, 32);
                //TIPO VARCHAR
                oci_bind_by_name($stmt, ':parrespuesta', $parrespuesta, 32);

                if (!@oci_execute($stmt)) {
                    $e = oci_error($stmt);
                    var_dump($e);
                }
                if ($parrespuesta == 1) {
                    $par_new_proceso = $parnewproceso;
                } else {
                    $data['succes'] = 0;
                    $this->load->view('portal/templates/header2', $data);
                    $this->load->view('portal/cotizacion/cotizar', $data);
                    $this->load->view('portal/templates/footer', $data);
                }

                $data_new_pro_cant = $this->session->userdata("data_produc_cant");

                for ($i = 0; $i < count($data_new_pro_cant); $i++) {
                    $array_data_new_pro_cant = get_object_vars($data_new_pro_cant[$i]);
                    $array = get_object_vars($dataCoti);
                    $PK_TIPPAR_PARCODIGO = 1;
                    $parametro = $array_data_new_pro_cant['nombre_producto'];
                    $par_cantidad = $array_data_new_pro_cant['cantidad_tarjetas'];
                    $par_abono = $par_abono = $this->session->userdata("valor_abono"); /* $array['valor_abono']; */
                    $par_tasa = 0;
                    $pk_producto_codigo = $array_data_new_pro_cant['id_producto'];
                    $parbinproducto=null;
                    $parusuariocreacion=$usuario_creacion;
//
//                    var_dump("Valor parametro=" . $parametro);
//                    var_dump("Valor par_cantidad=" . $par_cantidad);
//                    var_dump("Valor par_abono=" . $par_abono);
//                    var_dump("Valor PK_TIPPAR_PARCODIGO=" . $PK_TIPPAR_PARCODIGO);
//                    var_dump("Valor pk_producto_codigo=" . $pk_producto_codigo);
//                    var_dump("Valor par_new_proceso=" . $par_new_proceso);
                    //prcagregarparametrosycostos
                    $sql = "BEGIN MODCOMERC.COMPKMODACTUALIZAR.prcagregarparametrosycostos(
                                     parnewproceso=>:parnewproceso,
                                     parpkproducto=>:parpkproducto,
                                     prpktipproducto =>:prpktipproducto,
                                     parcantidad =>:parcantidad,
                                     parvalorabono =>:parvalorabono,
                                     parnombreproducto =>:parnombreproducto,
                                     parbinproducto=>:parbinproducto,
                                     parusuariocreacion=>:parusuariocreacion,
                                     parpkparametro=>:parpkparametro
                                     parresultado =>:parresultado); END;";
                    $conn = $this->db->conn_id;
                    $stmt = oci_parse($conn, $sql);
                    //TIPO NUMBER 
                    oci_bind_by_name($stmt, ':parnewproceso', $par_new_proceso, 32);
                    //TIPO NUMBER 
                    oci_bind_by_name($stmt, ':parpkproducto', $pk_producto_codigo, 32);
                    //TIPO NUMBER
                    oci_bind_by_name($stmt, ':prpktipproducto', $PK_TIPPAR_PARCODIGO, 32);
                    //TIPO NUMBER
                    oci_bind_by_name($stmt, ':parcantidad', $par_cantidad, 32);
                    //TIPO NUMBER
                    oci_bind_by_name($stmt, ':parvalorabono', $par_abono, 32);
                    //TIPO VARCHAR
                    oci_bind_by_name($stmt, ':parnombreproducto', $parametro, 32);
                    //TIPO VARCHAR
                    oci_bind_by_name($stmt, ':parbinproducto', $parbinproducto, 32);
                    //TIPO VARCHAR
                    oci_bind_by_name($stmt, ':parusuariocreacion', $parusuariocreacion, 32);
                    //TIPO VARCHAR
                    oci_bind_by_name($stmt, ':parusuariocreacion', $parpkparametro, 32);
                    //TIPO VARCHAR
                    oci_bind_by_name($stmt, ':parresultado', $parresultado, 32);

                    if (!@oci_execute($stmt)) {
                        $e = oci_error($stmt);
                        var_dump($e);
                    }
                }
                if ($parresultado == 1) {
                    $pk_tippar_codigo = 2;
                    $newtasa = $post['porcentaje'];
                    $newcantidad = $par_abono = $this->session->userdata("total_abono"); /* $array['total_abono']; */
//                    var_dump("tipparcodigo=" . $pk_tippar_codigo);
//                    var_dump("procescodigo=" . $pk_proces_codigo);
//                    var_dump("newtasa=" . $newtasa);
//                    var_dump("newcantidad=" . $newcantidad);
//                    var_dump("Valor par_new_proceso update=" . $par_new_proceso);
                    //actualizar abonomensual comtblparame
                    $sql = "BEGIN MODCOMERC.COMPKMODACTUALIZAR.PRCUPDATEOMTBLPARAME(
                    PAR_TIPPAR_CODIGO =>:PAR_TIPPAR_CODIGO,
                    PAR_PK_PROCES_CODIGO=>:PAR_PK_PROCES_CODIGO,
                    PARTASA=>:PARTASA,
                    PARCANTIDAD=>:PARCANTIDAD,
                    PARMENSAJE=>:PARMENSAJE);
                    END;";
                    $conn = $this->db->conn_id;
                    $stmt = oci_parse($conn, $sql);
                    //TIPO NUMBER INPUT
                    oci_bind_by_name($stmt, ':PAR_TIPPAR_CODIGO', $pk_tippar_codigo, 32);
                    oci_bind_by_name($stmt, ':PAR_PK_PROCES_CODIGO', $parnewproceso, 32);
                    oci_bind_by_name($stmt, ':PARTASA', $newtasa, 32);
                    oci_bind_by_name($stmt, ':PARCANTIDAD', $newcantidad, 32);
                    oci_bind_by_name($stmt, ':PARMENSAJE', $parresultado, 32);
                    if (!@oci_execute($stmt)) {
                        $e = oci_error($stmt);
                        var_dump($e);
                    }

                    $empresa = $this->session->userdata("entidad");
                    $pk_entidad = $empresa['PK_ENT_CODIGO'];
                    $partipopro = 1;
                    $pk_cotiza_codigo = $this->session->userdata("pk_cotiza_codigo"); /* $array['pk_cotiza_codigo']; */
//                    var_dump("Valor pk_entidad=" . $pk_entidad);
//                    var_dump("Valor partipopro=" . $partipopro);
//                    var_dump("Valor pk_cotiza_codigo=" . $pk_cotiza_codigo);
//                    var_dump("Valor par_new_proceso=" . $par_new_proceso);
                      
                    if ($parresultado == 1) {
                        //ejecuta el procedimiento de activar proceso de cotizacion 
                        $sql = "BEGIN MODCOMERC.COMPKMODACTUALIZAR.PRCACTIVARPROCESO(
                            PK_ENTIDAD =>:PK_ENTIDAD,
                            PK_PROCESO=>:PK_PROCESO,
                            PK_COTIZACION=>:PK_COTIZACION,
                            PARTIPPRO=>:PARTIPPRO,
                            PARRESPUEST=>:PARRESPUEST);
                            END;";
                        $conn = $this->db->conn_id;
                        $stmt = oci_parse($conn, $sql);
                        //TIPO NUMBER INPUT
                        oci_bind_by_name($stmt, ':PK_ENTIDAD', $pk_entidad, 32);
                        oci_bind_by_name($stmt, ':PK_PROCESO', $parnewproceso, 32);
                        oci_bind_by_name($stmt, ':PK_COTIZACION', $pk_cotiza_codigo, 32);
                        oci_bind_by_name($stmt, ':PARTIPPRO', $partipopro, 32);
                        oci_bind_by_name($stmt, ':PARRESPUEST', $parresultadoactiva, 32);
                        if (!@oci_execute($stmt)) {
                            $e = oci_error($stmt);
                            var_dump($e);
                        }
                        if ($parresultadoactiva == 1) {
                         //   var_dump("Valor pk_cotiza_codigo=" . $parresultadoactiva);
                            $data['succes'] = $parresultadoactiva;
                            $this->load->view('portal/templates/header2', $data);
                            $this->load->view('portal/cotizacion/cotizar', $data);
                            $this->load->view('portal/templates/footer', $data);
                        } else {
                            $data['succes'] = 0;
                            $this->load->view('portal/templates/header2', $data);
                            $this->load->view('portal/cotizacion/cotizar', $data);
                            $this->load->view('portal/templates/footer', $data);
                        }
                    } else {
                        $data['succes'] = 0;
                        $this->load->view('portal/templates/header2', $data);
                        $this->load->view('portal/cotizacion/cotizar', $data);
                        $this->load->view('portal/templates/footer', $data);
                      //  var_dump("Resultado error=" . $parresultado);
                    }
                }
            }
        }
    }

}
