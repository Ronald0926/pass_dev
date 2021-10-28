<?php
session_start();
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Consultas extends CI_Controller {

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

    public function consultasAbonos($pantalla = 0) {
        $this->verificarllaveMestraNuevosPerfiles();
        $this->session->set_userdata(array("pedidoAbono" => null));
        $this->session->set_userdata(array("llavesTemp" => null));
        //$empresa = $this->session->userdata("entidad");
        $empresa = $_SESSION['entidad'];
        $data['empresa'] = $empresa['NOMBREEMPRESA'];
        //$usuario = $this->session->userdata("usuario");
        $usuario = $_SESSION['usuario'];
        $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
        $pkcustodio = $usuario['PK_ENT_CODIGO'];
        //$campana = $this->session->userdata("campana");
        $campana = $_SESSION['campana'];
        $facturapref = $this->db->query(
                "  Select VALOR_PARAMETRO par from MODFACTUR.FACTBLPARFAC where PK_PARFAC_CODIGO = 4  "
        );
        $data['facturapref'] = $facturapref->result_array;

        $data['facturapref'] = $data['facturapref'][0]['PAR'];

        $sqlabonosinfo="SELECT FAC,NOMPRO,MON,ESTMOV,DOC,NOMENT,FECCRE,FECPRO,FECFAC,FECDIS,ORDEN,NUMTAR,IDBANCO FROM (SELECT   nvl(fac.NUMERO_FACTURA,0) AS FAC,
        p.nombre_producto NOMPRO,
        detpab.MONTO MON,
        ed.nombre ESTMOV,
        entidadth.documento DOC,
        upper(NVL(entidadth.razon_social, entidadth.nombre || ' ' || entidadth.apellido)) NOMENT,
        NVL(TO_CHAR(detpab.fecha_creacion,'DD/MM/YYYY HH24:MI:SS'),'PENDIENTE') FECCRE,
      NVL(TO_CHAR(detpab.fecha_a_dispersar,'DD/MM/YYYY HH24:MI:SS'),'PENDIENTE') FECPRO,
      NVL(TO_CHAR(fac.fecha_creacion,'DD/MM/YYYY HH24:MI:SS'),'PENDIENTE') FECFAC,
      NVL(TO_CHAR(detpab.FECHA_DISPERCION,'DD/MM/YYYY HH24:MI:SS'),'PENDIENTE') FECDIS,
      ord.pk_ordcom_codigo ORDEN,
      tarjet.NUMERO NUMTAR,
      tarjet.ID_EMPRESA IDBANCO
FROM MODPROPAG.ppatblpedabon ped
INNER JOIN MODPROPAG.ppatbldetpab detpab
ON detpab.pk_pedido=ped.pk_pedabon_codigo    
INNER JOIN modcliuni.clitblentida entidadth
ON detpab.PK_TARJETA_HAB = entidadth.pk_ent_codigo
INNER JOIN  MODCLIUNI.CLITBLTIPDOC td
ON  entidadth.clitbltipdoc_pk_td_codigo=td.pk_td_codigo
INNER JOIN MODTARHAB.tartblcuenta cuenta
ON entidadth.pk_ent_codigo=cuenta.pk_ent_codigo_th 
and cuenta.PK_PRODUC_CODIGO=detpab.PK_PRODUCTO
AND detpab.pk_cuenta=cuenta.pk_tartblcuenta_codigo 
LEFT JOIN MODTARHAB.tartbltarjet tarjet
ON cuenta.PK_TARTBLCUENTA_CODIGO =tarjet.pk_tartblcuenta_codigo 
and detpab.pk_cuenta=tarjet.pk_tartblcuenta_codigo
and detpab.PK_TARJET_CODIGO=tarjet.PK_TARJET_CODIGO
INNER JOIN modproduc.protblproduc p
ON  cuenta.PK_PRODUC_CODIGO = p.pk_produc_codigo
INNER JOIN MODPROPAG.ppatblestdis ed
ON detpab.pk_estado = ed.pk_estdis_codigo
INNER JOIN MODCLIUNI.CLITBLCAMPAN campana
ON ped.pk_campan_codigo=campana.pk_campan_codigo
INNER JOIN MODPROPAG.PPATBLORDCOM ord
ON ped.pk_orden=ord.pk_ordcom_codigo
INNER JOIN MODFACTUR.FACTBLFACORD FACORD
on ord.pk_ordcom_codigo=facord.pk_ordcom_codigo
INNER JOIN MODFACTUR.factblfactur fac
ON fac.pk_factur_codigo=facord.pk_factur_codigo
where ped.PK_CLIENTE={$empresa ['PK_ENT_CODIGO']} 
AND campana.pk_campan_codigo = {$campana}
order by detpab.fecha_creacion DESC  
)where rownum <= 500";
//echo  $sqlabonosinfo;
        $abonosinfo = $this->db->query( $sqlabonosinfo
						);


        $data['abonosinfo'] = $abonosinfo->result_array;
        $data['a'] = $empresa ['PK_ENT_CODIGO'];
        // var_dump($abonosinfo);
        // exit();
        //se cuenta la informacion
        $Abonosnum = array();

        foreach ($abonosinfo->result_array as $value) {
            if (is_null($value['FAC'])) {
                $temp = 0;
            } else {
                $temp = $value['FAC'];
            }
            if (!array_key_exists($temp, $Abonosnum)) {
                $Abonosnum[$temp] = 1;
            } else {
                $Abonosnum[$temp] = $Abonosnum[$temp] + 1;
            }
        }

        //si es perfil autorizador de pagos
        //$rol = $this->session->userdata("rol");
        $rol = $_SESSION['rol'];
        if ($rol == 58 || $rol == 56) {
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

        // var_dump($Abonosnum);
        // exit();
        $data['abonosnum'] = $Abonosnum;
        //$ultimaconexion = $this->session->userdata("ultimaconexion");
        $ultimaconexion = $_SESSION['ultimaconexion'];
        $data['ultimaconexion'] = $ultimaconexion;
        $data['menu'] = "consultas";
        // var_dump($Abonosnum);
        $this->load->view('portal/templates/header2', $data);
        $this->load->view('portal/consultas/consultasAbonos', $data);
        $this->load->view('portal/templates/footer', $data);
    }

    public function consultasAbonostodo($pantalla = 0) {
        $this->verificarllaveMestraNuevosPerfiles();
        //$empresa = $this->session->userdata("entidad");
        $empresa = $_SESSION['entidad'];
        $data['empresa'] = $empresa['NOMBREEMPRESA'];
        //$usuario = $this->session->userdata("usuario");
        $usuario = $_SESSION['usuario'];
        $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
        $pkcustodio = $usuario['PK_ENT_CODIGO'];

        $abonosinfo = $this->db->query(
                " SELECT nvl(fac.pk_factur_codigo,0) FAC,
                            p.nombre_producto NOMPRO,
                            c.MONTO MON,
                            ed.nombre ESTMOV,
                            e.documento DOC,
                            upper(NVL(e.razon_social, e.nombre || ' ' || e.apellido)) NOMENT,
                            NVL(TO_CHAR(c.fecha_creacion,'DD/MM/YYYY HH24:MM:SS'),'PENDIENTE') FECCRE,
                          NVL(TO_CHAR(c.fecha_a_dispersar,'DD/MM/YYYY HH24:MM:SS'),'PENDIENTE') FECPRO,
                          NVL(TO_CHAR(fac.fecha_creacion,'DD/MM/YYYY HH24:MM:SS'),'PENDIENTE') FECFAC,
                          NVL(TO_CHAR(c.FECHA_DISPERCION,'DD/MM/YYYY HH24:MM:SS'),'PENDIENTE') FECDIS,
                          ord.pk_ordcom_codigo ORDEN,
                          tarjet.NUMERO NUMTAR,
                          tarjet.ID_EMPRESA IDBANCO
                FROM MODPROPAG.ppatbldetpab c
                INNER JOIN modcliuni.clitblentida e
                ON c.PK_TARJETA_HAB = e.pk_ent_codigo
                INNER JOIN MODTARHAB.tartblcuenta cuenta
                ON e.pk_ent_codigo=cuenta.pk_ent_codigo_th and cuenta.PK_PRODUC_CODIGO=c.PK_PRODUCTO
                INNER JOIN MODTARHAB.tartbltarjet tarjet
                ON cuenta.PK_TARTBLCUENTA_CODIGO =tarjet.pk_tartblcuenta_codigo and c.pk_cuenta=tarjet.pk_tartblcuenta_codigo
                INNER JOIN modproduc.protblproduc p
                ON  cuenta.PK_PRODUC_CODIGO = p.pk_produc_codigo
                left JOIN MODPROPAG.ppatblestdis ed
                ON c.pk_estado = ed.pk_estdis_codigo
                left JOIN MODPROPAG.ppatblpedabon ped
                ON c.pk_pedido=ped.pk_pedabon_codigo
                INNER JOIN MODCLIUNI.CLITBLCAMPAN campana
                ON ped.pk_campan_codigo=campana.pk_campan_codigo
                INNER JOIN MODPROPAG.PPATBLORDCOM ord
                ON ped.pk_orden=ord.pk_ordcom_codigo
                INNER JOIN MODFACTUR.FACTBLFACORD FACORD
                on ord.pk_ordcom_codigo=facord.pk_ordcom_codigo
                INNER JOIN MODFACTUR.factblfactur fac
                ON fac.pk_factur_codigo=facord.pk_factur_codigo
                INNER JOIN MODCLIUNI.CLITBLTIPDOC td
                ON  e.clitbltipdoc_pk_td_codigo=td.pk_td_codigo
                where ord.pk_cliente={$empresa ['PK_ENT_CODIGO']}
            order by fac.pk_factur_codigo desc, p.nombre_producto ASC");
        $data['abonosinfo'] = $abonosinfo->result_array;
        $data['a'] = $empresa ['PK_ENT_CODIGO'];
        // var_dump($abonosinfo);
        // exit();
        //se cuenta la informacion
        $Abonosnum = array();

        foreach ($abonosinfo->result_array as $value) {
            if (is_null($value['FAC'])) {
                $temp = 0;
            } else {
                $temp = $value['FAC'];
            }
            if (!array_key_exists($temp, $Abonosnum)) {
                $Abonosnum[$temp] = 1;
            } else {
                $Abonosnum[$temp] = $Abonosnum[$temp] + 1;
            }
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
 
        // var_dump($Abonosnum);
        // exit();
        $data['abonosnum'] = $Abonosnum;
        //$ultimaconexion = $this->session->userdata("ultimaconexion");
        $ultimaconexion = $_SESSION['ultimaconexion'];
        $data['ultimaconexion'] = $ultimaconexion;
        $data['menu'] = "consultas";
        // var_dump($Abonosnum);
        $this->load->view('portal/templates/header2', $data);
        $this->load->view('portal/consultas/consultasAbonos', $data);
        $this->load->view('portal/templates/footer', $data);
    }

    /* consulta facturas */

    public function consultasFacturas() {
        $this->verificarllaveMestraNuevosPerfiles();
        //$empresa = $this->session->userdata("entidad");
        $empresa = $_SESSION['entidad'];
        $data['empresa'] = $empresa['NOMBREEMPRESA'];
        //$usuario = $this->session->userdata("usuario");
        $usuario = $_SESSION['usuario'];
        $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
        $pkcustodio = $usuario['PK_ENT_CODIGO'];

        $factura = $this->db->query("SELECT NVL(TO_CHAR(fac.fecha_creacion,'DD/MM/YYYY'),'PENDIENTE') FECCRE,
                fac.pk_factur_codigo FAC,
                fac.numero_factura NUMERO_FACTURA,
                PMA,
                PCO,
                TOTAL,
                MODFACTUR.facpkgconsultas.fncconsultarordenesfactura(fac.pk_factur_codigo) ORDEN
                ,estfac.nombre ESTADO,
                NVL(ordcom.medio_pago,'SIN PAGO') MEDIO_PAGO,
                ordcom.fecha_pago
                FROM MODFACTUR.factblfactur fac
                JOIN MODFACTUR.FACTBLESTFAC estfac
                ON fac.pk_estado=estfac.pk_estfac_codigo
                JOIN MODPROPAG.ppatblordcom ordcom ON ordcom.pk_ordcom_codigo = MODFACTUR.facpkgconsultas.fncconsultarordenesfactura(fac.pk_factur_codigo)
                where fac.pk_ent_codigo =  {$empresa ['PK_ENT_CODIGO']}
                order by fac.pk_factur_codigo desc");
        $data['factura'] = $factura->result_array;
        //si es perfil autorizador de pagos
        //$rol = $this->session->userdata("rol");
        $rol = $_SESSION['rol'];
        if ($rol == 58 || $rol==56) {
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

        //$ultimaconexion = $this->session->userdata("ultimaconexion");
        $ultimaconexion = $_SESSION['ultimaconexion'];
        $data['ultimaconexion'] = $ultimaconexion;
        $data['menu'] = "consultas";
        $this->load->view('portal/templates/header2', $data);
        $this->load->view('portal/consultas/consultasFacturas', $data);
        $this->load->view('portal/templates/footer', $data);
    }

    /* consulta tarjetas */

    public function consultasTarjetas() {
         $this->verificarllaveMestraNuevosPerfiles();
        //$empresa = $this->session->userdata("entidad");
        $empresa = $_SESSION['entidad'];
        $data['empresa'] = $empresa['NOMBREEMPRESA'];
        //$usuario = $this->session->userdata("usuario");
        $usuario = $_SESSION['usuario'];
        $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
        $pkcustodio = $usuario['PK_ENT_CODIGO'];

        //$campana = $this->session->userdata("campana");
        $campana = $_SESSION['campana'];
        $tarjeta = $this->db->query("
     SELECT ent.documento DOC,
     tipdoc.abreviacion TIPDOC, 
     NVL(TO_CHAR(tar.fecha_creacion,'DD/MM/YYYY'),'PENDIENTE') FEC,
     nvl(ent.razon_social,ent.nombre ||' '||ent.apellido) NOMTH,
     TAR.NUMERO NUMTAR,
     PRO.NOMBRE_PRODUCTO NOMPRO,
     ENTCUS.nombre||' '||entcus.apellido CUS,
     esttar.nombre ESTTAR,
     ped.pk_pedido_codigo PEDIDO,
     tar.IDENTIFICADOR
     FROM MODTARHAB.tartbltarjet tar 
     join MODTARHAB.TARTBLCUENTA CUE 
     ON cue.pk_tartblcuenta_codigo = tar.pk_tartblcuenta_codigo 
     AND cue.PK_ENT_CODIGO_EMP = {$empresa ['PK_ENT_CODIGO']}
     JOIN MODCLIUNI.CLITBLENTIDA ENT 
     ON ent.pk_ent_codigo = cue.pk_ent_codigo_th 
     JOIN MODCLIUNI.CLITBLTIPDOC TIPDOC 
     ON tipdoc.pk_td_codigo = ent.clitbltipdoc_pk_td_codigo 
     JOIN MODPRODUC.PROTBLPRODUC PRO 
     ON pro.pk_produc_codigo = cue.pk_produc_codigo 
     JOIN MODALISTA.ALITBLDETPED DETPED 
     ON detped.pk_detped_codigo = tar.pk_detped_codigo 
     JOIN MODALISTA.ALITBLPEDIDO PED ON ped.pk_pedido_codigo = detped.pk_pedido 
     JOIN MODCLIUNI.CLITBLENTIDA ENTCUS ON entcus.pk_ent_codigo = ped.pk_custodio
     JOIN MODCLIUNI.CLITBLCAMPAN CAM ON cam.pk_campan_codigo = ped.pk_campan_codigo 
     AND cam.pk_campan_codigo ={$campana}
     JOIN MODTARHAB.tartblesttar ESTTAR 
     ON esttar.pk_esttar_codigo = tar.pk_esttar_codigo
     where rownum <= 500           
     order by tar.fecha_creacion desc");

        $data['tarjeta'] = $tarjeta->result_array;

        //si es perfil autorizador de pagos
        //$rol = $this->session->userdata("rol");
        $rol = $_SESSION['rol'];
        if ($rol == 58 || $rol == 56) {
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


        //var_dump($tarjeta);
        //$ultimaconexion = $this->session->userdata("ultimaconexion");
        $ultimaconexion = $_SESSION['ultimaconexion'];
        $data['ultimaconexion'] = $ultimaconexion;
        $data['menu'] = "consultas";

        $this->load->view('portal/templates/header2', $data);
        $this->load->view('portal/consultas/consultasTarjetas', $data);
        $this->load->view('portal/templates/footer', $data);
    }

    /* consulta Bussines */

    public function consultasTarjetasTodas() {
        $this->verificarllaveMestraNuevosPerfiles();
        //$empresa = $this->session->userdata("entidad");
        $empresa = $_SESSION['entidad'];
        $data['empresa'] = $empresa['NOMBREEMPRESA'];
        //$usuario = $this->session->userdata("usuario");
        $usuario = $_SESSION['usuario'];
        $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
        $pkcustodio = $usuario['PK_ENT_CODIGO'];

        $tarjeta = $this->db->query("
         SELECT ent.documento DOC,
     tipdoc.abreviacion TIPDOC, 
     NVL(TO_CHAR(tar.fecha_creacion,'DD/MM/YYYY'),'PENDIENTE') FEC,
     ent.nombre ||' '||ent.apellido NOMTH, TAR.NUMERO NUMTAR,
     PRO.NOMBRE_PRODUCTO NOMPRO,
     ENTCUS.nombre||' '||entcus.apellido CUS,
     esttar.nombre ESTTAR,
     ped.pk_pedido_codigo PEDIDO,
     tar.IDENTIFICADOR
     FROM MODTARHAB.tartbltarjet tar 
     join MODTARHAB.TARTBLCUENTA CUE 
     ON cue.pk_tartblcuenta_codigo = tar.pk_tartblcuenta_codigo 
     AND cue.PK_ENT_CODIGO_EMP = {$empresa ['PK_ENT_CODIGO']}
     JOIN MODCLIUNI.CLITBLENTIDA ENT 
     ON ent.pk_ent_codigo = cue.pk_ent_codigo_th 
     JOIN MODCLIUNI.CLITBLTIPDOC TIPDOC 
     ON tipdoc.pk_td_codigo = ent.clitbltipdoc_pk_td_codigo 
     JOIN MODPRODUC.PROTBLPRODUC PRO 
     ON pro.pk_produc_codigo = cue.pk_produc_codigo 
     JOIN MODALISTA.ALITBLDETPED DETPED 
     ON detped.pk_detped_codigo = tar.pk_detped_codigo 
     JOIN MODALISTA.ALITBLPEDIDO PED ON ped.pk_pedido_codigo = detped.pk_pedido 
     JOIN MODCLIUNI.CLITBLENTIDA ENTCUS ON entcus.pk_ent_codigo = ped.pk_custodio
     JOIN MODCLIUNI.CLITBLCAMPAN CAM ON cam.pk_campan_codigo = ped.pk_campan_codigo 
     LEFT JOIN MODPROPAG.PPATBLDETORD DETORD ON detord.pk_pedido = detped.pk_detped_Codigo
     left JOIN MODFACTUR.FACTBLFACORD FACORD ON facord.pk_ordcom_codigo=detord.pk_orden_compra
     left JOIN MODPROPAG.PPATBLORDCOM ORDCOM ON facord.pk_ordcom_codigo=ordcom.pk_ordcom_codigo
     LEFT JOIN MODFACTUR.FACTBLFACTUR factur ON facord.pk_factur_codigo=factur.pk_factur_codigo 
     JOIN MODTARHAB.tartblesttar ESTTAR 
     ON esttar.pk_esttar_codigo = tar.pk_esttar_codigo
     order by tar.fecha_creacion desc");

        $data['tarjeta'] = $tarjeta->result_array;

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

        //var_dump($tarjeta);
        //$ultimaconexion = $this->session->userdata("ultimaconexion");
        $ultimaconexion = $_SESSION['ultimaconexion'];
        $data['ultimaconexion'] = $ultimaconexion;
        $data['menu'] = "consultas";

        $this->load->view('portal/templates/header2', $data);
        $this->load->view('portal/consultas/consultasTarjetas', $data);
        $this->load->view('portal/templates/footer', $data);
    }

    public function consultasBussines() {
        $this->verificarllaveMestraNuevosPerfiles();
        //$empresa = $this->session->userdata("entidad");
        $empresa = $_SESSION['entidad'];
        $data['empresa'] = $empresa['NOMBREEMPRESA'];
        //$usuario = $this->session->userdata("usuario");
        $usuario = $_SESSION['usuario'];
        $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
        $pkcustodio = $usuario['PK_ENT_CODIGO'];
        //$campana = $this->session->userdata("campana");
        $campana = $_SESSION['campana'];
        $sqlbusiness=  " SELECT ent.documento ID, 
        tar.id_empresa id_empresa,
      tar.numero NUMTAR, 
      PRO.NOMBRE_PRODUCTO NOMPRO,
      tar.identificador DESCRIPCION,
      NVL(TO_CHAR(tar.fecha_creacion,'DD/MM/YYYY'),'PENDIENTE') FEC,
      esttar.nombre ESTTAR,
      esttar.pk_esttar_codigo ESTTAR_CODIGO
      FROM MODTARHAB.tartbltarjet tar 
      join MODTARHAB.TARTBLCUENTA CUE ON cue.pk_tartblcuenta_codigo = tar.pk_tartblcuenta_codigo
      AND cue.PK_ENT_CODIGO_EMP = {$empresa ['PK_ENT_CODIGO']}
      JOIN MODPRODUC.protblproduc PRO 
      ON pro.pk_produc_codigo = cue.pk_produc_codigo 
      AND pro.pk_linpro_codigo = 2 
      JOIN MODCLIUNI.CLITBLENTIDA ENT 
      ON ent.pk_ent_codigo = cue.pk_ent_codigo_th 
      JOIN MODCLIUNI.CLITBLTIPDOC TIPDOC 
      ON tipdoc.pk_td_codigo = ent.clitbltipdoc_pk_td_codigo 
      JOIN MODPRODUC.PROTBLPRODUC PRO 
      ON pro.pk_produc_codigo = cue.pk_produc_codigo 
      JOIN MODALISTA.ALITBLDETPED DETPED ON detped.pk_detped_codigo = tar.pk_detped_codigo
      JOIN MODALISTA.ALITBLPEDIDO PED ON ped.pk_pedido_codigo = detped.pk_pedido 
      JOIN MODCLIUNI.CLITBLCAMPAN CAM ON cam.pk_campan_codigo = ped.pk_campan_codigo 
      and cam.pk_campan_codigo={$campana}
      JOIN MODTARHAB.tartblesttar ESTTAR ON esttar.pk_esttar_codigo = tar.pk_esttar_codigo 
      order by tar.fecha_creacion desc";
//echo  $sqlbusiness;
      //echo $sqlbusiness;
        $bussines = $this->db->query($sqlbusiness);
        $data['bussines'] = $bussines->result_array;
        //si es perfil autorizador de pagos
        //$rol = $this->session->userdata("rol");
        $rol = $_SESSION['rol'];
        if ($rol == 58 || $rol == 56) {
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

        //$ultimaconexion = $this->session->userdata("ultimaconexion");
        $ultimaconexion = $_SESSION['ultimaconexion'];
        $data['ultimaconexion'] = $ultimaconexion;
        $data['menu'] = "consultas";
        $this->load->view('portal/templates/header2', $data);
        $this->load->view('portal/consultas/consultasBussines', $data);
        $this->load->view('portal/templates/footer', $data);
    }

    /* consulta Pedidos Parjeta */

    public function consultasPedidosTarjeta() {
        $this->verificarllaveMestraNuevosPerfiles();
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
        pedidos.fecha_creacion FECHASOLICITUD,
        envios.fechaentregado FECHARECIBIDO,
        nvl(envios.nomestado,estped.nombre) ESTADOPEDIDO
        from modalista.alitblpedido PEDIDOS
        join modalista.alitblestped estped
        ON pedidos.ALITBLESTPED_PK_ESTPED_CODIGO=estped.pk_estped_codigo
        join (select pedid.pk_pedido_codigo pedido,count(1) cantidad
            from MODALISTA.alitblpedido pedid
            join MODALISTA.alitbldetped detped
            on detped.pk_pedido=pedid.pk_pedido_codigo
            group by pedid.pk_pedido_codigo) cantidad
        ON cantidad.pedido=pedidos.pk_pedido_codigo
        left join (
            SELECT PK_PEDIDO PEDIDO, PK_ESTADO ESTADO,
                estenv.nombre nomestado,env.fecha_actualizacion actualizacion,env.fecha_entregado fechaentregado
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
        //si es perfil autorizador de pagos
        //$rol = $this->session->userdata("rol");
        $rol = $_SESSION['rol'];
        if ($rol == 58 || $rol == 56) {
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

        //$ultimaconexion = $this->session->userdata("ultimaconexion");
        $ultimaconexion = $_SESSION['ultimaconexion'];
        $data['menu'] = "consultas";
        $data['ultimaconexion'] = $ultimaconexion;
        $this->load->view('portal/templates/header2', $data);
        $this->load->view('portal/consultas/consultasPedidosTarjeta', $data);
        $this->load->view('portal/templates/footer', $data);
    }

    public function verDetalle($idEmpresa, $numeroTarjeta,$producto) {
        $this->verificarllaveMestraNuevosPerfiles();
        $numeroTarjeta = str_replace("%20", "*", $numeroTarjeta);
        //$empresa = $this->session->userdata("entidad");
        $empresa = $_SESSION['entidad'];
        //si es perfil autorizador de pagos
        //$rol = $this->session->userdata("rol");
        $rol = $_SESSION['rol'];
        if ($rol == 58) {
            $queryanticipo="select distinct
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
            WHERE ent.pk_ent_codigo={$empresa['PK_ENT_CODIGO']}";
            $sqlAnticipo = $this->db->query($queryanticipo);
            $data['dataAnticipo'] = $sqlAnticipo->result_array[0];
        }
        $sqldetalle="
        SELECT
    tbl.id_empresa,
    tar.numero_documento,
    tar.nombre_comercio,
    tar.tipo_movimiento,
    tar.id_tipo_movimiento,
    tbl.identificador,
    pro.nombre_producto,
    to_date(tar.fecha_transaccion, 'YYYY-MM-DD') AS fecha,
    to_char(to_date(tar.hora_transaccion,'HH24MISS'),'HH24:mi:ss')HORA_TRANSACCION,
    tar.terminal,
    tar.ciudad,
    tar.monto,
    tar.propina,
    tar.iva,
    tar.pan_enmascarado
FROM
         modtarhab.view_movtarj tar
    JOIN modtarhab.tartbltarjet    tbl ON tbl.numero = tar.pan_enmascarado  and tar.numero_documento=tbl.id_empresa
    
    JOIN modtarhab.tartblcuenta    cue ON cue.pk_tartblcuenta_codigo = tbl.pk_tartblcuenta_codigo
    JOIN modproduc.protblproduc    pro ON pro.pk_produc_codigo = cue.pk_produc_codigo
    where tar.numero_documento = {$idEmpresa} 
         and tar.pan_enmascarado = '{$numeroTarjeta}'
         ";
  //echo $sqldetalle;
       /*
       
       */
        $detalleMov = $this->db->query($sqldetalle);
        $data['enmascarado'] = $numeroTarjeta;
        $detalleMov = $detalleMov->result_array;
        $data['movimientos'] = $detalleMov;
        if(!empty($detalleMov[0]['NOMBRE_PRODUCTO'])){
            $data['tipo'] =$detalleMov[0]['NOMBRE_PRODUCTO'];
     
        }else{
            $data['tipo'] = str_replace("%20", " ", $producto) ;

        }
        
        //$ultimaconexion = $this->session->userdata("ultimaconexion");
        $ultimaconexion = $_SESSION['ultimaconexion'];
        $data['ultimaconexion'] = $ultimaconexion;
        $data['empresa'] = $empresa['NOMBREEMPRESA'];
        //$usuario = $this->session->userdata("usuario");
        $usuario = $_SESSION['usuario'];
        $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
        $data['menu'] = "consultas";
        $this->load->view('portal/templates/header2', $data);
        $this->load->view('portal/consultas/verDetalleBussines', $data);
        $this->load->view('portal/templates/footer', $data);
    }
           public function verificarllaveMestraNuevosPerfiles() {
        //$rol = $this->session->userdata("rol");
        $rol = $_SESSION['rol'];
        
        //log_info('VAMOS PUES PERFIL SAL DE AHI ' . $_SESSION['PRODUCTOLLAVE']['CODIGO_PRODUCTO'].'y rol que tiene es'.$rol);
        //if (($rol != 45) || ($rol != 47) and ( $this->session->userdata("CODIGO_PRODUCTO") != 70)) {
        if(( $_SESSION['PRODUCTOLLAVE']['CODIGO_PRODUCTO'] == 70)){
        if (( $rol != 45 ) && ( $rol != 47) && ( $rol != 46) && ( $rol != 56) && ( $rol != 58)) {
            redirect('/portal/principal/pantalla');
        }
        }
    }

}
