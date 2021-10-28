<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Principal extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('CORREO_ELECTRONICO') == NULL) {
            redirect('index.php/chat/login/loginchat');
        }
    }

    public function __destruct()
    {
        $this->db->close();
    }

    var $data;

    public function pantalla()
    {
        $this->load->view('portal/templates/headerChat');
        $this->load->view('portal/templates/footerChat');
        $this->load->view('chat/index');
    }   
}
