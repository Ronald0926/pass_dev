<?php
session_start();
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Notificacion extends CI_Controller {

   

    public function updatenotificacion($idnotificacion) {
        //
        $sql = "BEGIN MODGENERI.GENPKGWEBSERVICE.PRCUPDATENOTIFICATION(
                    parnalerta =>:parnalerta,
                    parresultado=>:parresultado);
                    END;";
            $conn = $this->db->conn_id;
            $stmt = oci_parse($conn, $sql);
        //TIPO NUMBER INPUT
        oci_bind_by_name($stmt, ':parnalerta', $idnotificacion, 32);
        oci_bind_by_name($stmt, ':parresultado', $parresultado, 32);
        if (!@oci_execute($stmt)) {
            $e = oci_error($stmt);
            var_dump($e);
        }
        if($parresultado==1){
            $this->output->set_content_type('text/css');
            $this->output->set_output($parresultado);
        }
            $this->output->set_content_type('text/css');
            $this->output->set_output($parresultado);
    }

}
