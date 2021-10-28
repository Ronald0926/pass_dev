<?php

ini_set("pcre.backtrack_limit", "5000000");
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Xlsunoauno extends CI_Controller {

    public $iniciLog = '[INFO] ';
    public $logHeader = 'APOLOINFO::::::::: ';
    public $postData = 'POSTDATA::::::::: ';
    public $queryData = 'QUERYDATA::::::: ';
    public $finFuncion = ' FIN PROCEDIMIENTO::::::: ';

    public function __construct() {
        parent::__construct();
        $this->load->helper('log4php');
    }

    public function __destruct() {
        $this->db->close();
    }

    public function crear() {
        $unoauno = $this->db->query("
            select distinct pk_ent_codigo,DESTINATARIO,DIRECCION,TELEFONO,CIUDAD,UNIDADES,PESO,OBSERVACIONES,DOCUMENTO,CONTENIDO,NOTAS,NGUIA,TRANSPORTADORA,ID_dESTINATARIO,TIPOIDDEST from (
           select distinct
 ent.pk_ent_codigo,
ent.nombre ||' '||ent.apellido DESTINATARIO,
NVL(dir.DATO,dird.DATO) DIRECCION,
tel.DATO TELEFONO,
ciu.nombre||'-'||dep.nombre CIUDAD,
'1' UNIDADES,
'1' PESO,
'' OBSERVACIONES,
CIU.CODIGO_DANE DOCUMENTO,
'TARJETA PEOPLE PASS' CONTENIDO,
10000 vrDeclarado,
        entemp.razon_social NOTAS,
CASE WHEN entpro.pk_ent_codigo in (2,4) then 
        env.GUIA_ENVIO
        else 'NO APLICA' end  NGUIA,
0 TRANSPORTADORA,
ENT.DOCUMENTO ID_dESTINATARIO,
doc.ABREVIACION TIPOIDDEST
from modalista.alitblenvio env
left join modcliuni.clitblentida entpro on  entpro.pk_ent_codigo = env.PK_PROVEEDOR
join MODALISTA.alitbldetenv detenv on detenv.pk_envio = env.pk_envio_codigo
join modalista.alitblpedido ped on ped.pk_pedido_codigo = detenv.pk_pedido
join modalista.ALITBLESTENV estenv on estenv.PK_ESTENV_CODIGO = env.PK_ESTADO
join modcliuni.clitblentida entemp on entemp.pk_ent_codigo = ped.pk_empresa 
join MODALISTA.alitbldetped detped on detped.pk_pedido = ped.pk_pedido_codigo
join MODCLIUNI.clitblentida ent on ent.pk_ent_codigo = ped.pk_custodio
left join modcliuni.clitblcontac dir on ent.pk_ent_codigo = dir.clitblentida_pk_ent_codigo and dir.clitbltipcon_pk_tipcon_codigo = 48 and dir.usuario_actualizacion is null and dir.FECHA_ACTUALIZACION is null
left join modcliuni.clitblcontac dird on ent.pk_ent_codigo = dird.clitblentida_pk_ent_codigo and dird.clitbltipcon_pk_tipcon_codigo = 49 and dird.usuario_actualizacion is null and dird.FECHA_ACTUALIZACION is null
left join modcliuni.clitblcontac tel on ent.pk_ent_codigo = tel.clitblentida_pk_ent_codigo and tel.clitbltipcon_pk_tipcon_codigo = 47 and tel.usuario_actualizacion is null and tel.FECHA_ACTUALIZACION is null
JOIN MODCLIUNI.CLITBLCIUDAD CIU ON CIU.PK_CIU_CODIGO = ENT.CLITBLCIUDAD_PK_CIU_CODIGO
JOIN MODCLIUNI.CLITBLDEPPAI DEP ON DEP.PK_DEP_CODIGO = CIU.CLITBLDEPPAI_PK_DEP_CODIGO
join modcliuni.clitbltipdoc doc on ent.CLITBLTIPDOC_PK_TD_CODIGO = doc.PK_TD_CODIGO
where estenv.PK_ESTENV_CODIGO = 2 and entpro.pk_ent_codigo = 5 and
TO_CHAR(SYSDATE, 'DD-MM-YYYY') = TO_CHAR(env.fecha_Envio, 'DD-MM-YYYY')
order by eSTENV.PK_ESTENV_CODIGO,nvl(entpro.razon_social,entpro.nombre ||' '||entpro.apellido),env.GUIA_ENVIO,env.PK_ENVIO_CODIGO)a");
        //ORDER BY env.pk_envio_codigo,linpro.pk_linpro_codigo,nombre_empresa,pro.nombre_producto desc");
        $data_body['unoauno'] = $unoauno->result_array;
        $this->load->view('wsonline2/xlsunoauno/excel', $data_body);
    }



}
