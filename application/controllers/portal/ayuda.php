<?php
session_start();
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Ayuda extends CI_Controller {

    public function __construct() {
        parent::__construct();
        //if($this->session->userdata('entidad') == NULL){
        if($_SESSION['entidad'] == NULL){
            redirect('/');
        }
    }
    
    public function control($pantalla = 0) {
        $nextpantalla = $pantalla + 1;
        $data['pantalla']=$nextpantalla;
        $cantidad=$this->db->query("select  COUNT(SECUENCIA) CANTIDAD  FROM MODGENERI.GENTBLIMGAYU");
        
        if($data['pantalla'] > $cantidad->result_array[0]){
            redirect('portal/ayuda/control');
        }
        $informacion= $this->db->query("select  lower(DESCRIPCION) DESCRIPCION "
                . " from MODGENERI.GENTBLIMGAYU"
                . " where SECUENCIA={$nextpantalla}");
        $data['informacion']=$informacion->result_array[0];
        $data['cantidad']=$cantidad->result_array[0];
        //var_dump($informacion);
        //exit();
        $this->load->view('portal/ayuda/control', $data); 
    }
    
    public function preguntasfrecuentes() {
        //$usuario = $this->session->userdata("usuario");
        $usuario = $_SESSION['usuario'];
        $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
        //$empresa = $this->session->userdata("entidad");
        $empresa = $_SESSION['entidad'];
        $data['empresa'] = $empresa['NOMBREEMPRESA'];
        //$ultimaconexion = $this->session->userdata("ultimaconexion");
        $ultimaconexion = $_SESSION['ultimaconexion'];
        $data['ultimaconexion'] = $ultimaconexion;    
        $this->load->view('portal/templates/header2', $data);
        $this->load->view('portal/soporte/preguntas', $data);
        $this->load->view('portal/templates/footer', $data);
    }
    
}