<?php
session_start();
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Soporte extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    private function soporte(
    $PARTIPCAT, $PARSUBCAT, $PARENTIDA, $PARUSUSOL, $PARROLUSU, $PARCAMPAN, $PARTELCON, $PARCORCON, $PARDESCRI, $PARTIPDOC, $PARDOCUME, $PARPRODUC, $PARNUMTAR, $PARVALNOTCRE, $PARCOMPAR, $PARNUMFAC, $PARVALANT, $PARCOMPAG, $PARNUMGUI, $PARNUMPED, $PARCANTAR, $PARURL, $PARNOMBREARC
    ) {
        $PARRESPUE = '';

        $sql = "BEGIN MODULOSAC.SACPKGEMPRESARIAL.PRCSOPORTEPAGEMP(
                    :PARTIPCAT, :PARSUBCAT,
                    :PARENTIDA, :PARUSUSOL,
                    :PARROLUSU, :PARCAMPAN,
                    :PARTELCON, :PARCORCON,
                    :PARDESCRI, :PARTIPDOC,
                    :PARDOCUME, :PARPRODUC,
                    :PARNUMTAR, :PARVALNOTCRE,
                    :PARCOMPAR, :PARNUMFAC,
                    :PARVALANT, :PARCOMPAG,
                    :PARNUMGUI, :PARNUMPED,
                    :PARCANTAR, :PARURL,
                    :PARNOMBREARC,:PARTICKET,
                    :PARRESPUE);
                    END;";
        $conn = $this->db->conn_id;
        $stmt = oci_parse($conn, $sql);

        oci_bind_by_name($stmt, ':PARTIPCAT', $PARTIPCAT, 100);
        oci_bind_by_name($stmt, ':PARSUBCAT', $PARSUBCAT, 100);
        oci_bind_by_name($stmt, ':PARENTIDA', $PARENTIDA, 100);
        oci_bind_by_name($stmt, ':PARUSUSOL', $PARUSUSOL, 100);
        oci_bind_by_name($stmt, ':PARROLUSU', $PARROLUSU, 100);
        oci_bind_by_name($stmt, ':PARCAMPAN', $PARCAMPAN, 100);
        oci_bind_by_name($stmt, ':PARTELCON', $PARTELCON, 100);
        oci_bind_by_name($stmt, ':PARCORCON', $PARCORCON, 100);
        oci_bind_by_name($stmt, ':PARDESCRI', $PARDESCRI, 2500);
        oci_bind_by_name($stmt, ':PARTIPDOC', $PARTIPDOC, 100);
        oci_bind_by_name($stmt, ':PARDOCUME', $PARDOCUME, 100);
        oci_bind_by_name($stmt, ':PARPRODUC', $PARPRODUC, 100);
        oci_bind_by_name($stmt, ':PARNUMTAR', $PARNUMTAR, 100);
        oci_bind_by_name($stmt, ':PARVALNOTCRE', $PARVALNOTCRE, 100);
        oci_bind_by_name($stmt, ':PARCOMPAR', $PARCOMPAR, 100);
        oci_bind_by_name($stmt, ':PARNUMFAC', $PARNUMFAC, 100);
        oci_bind_by_name($stmt, ':PARVALANT', $PARVALANT, 100);
        oci_bind_by_name($stmt, ':PARCOMPAG', $PARCOMPAG, 100);
        oci_bind_by_name($stmt, ':PARNUMGUI', $PARNUMGUI, 100);
        oci_bind_by_name($stmt, ':PARNUMPED', $PARNUMPED, 100);
        oci_bind_by_name($stmt, ':PARCANTAR', $PARCANTAR, 100);
        oci_bind_by_name($stmt, ':PARURL', $PARURL, 100);
        oci_bind_by_name($stmt, ':PARNOMBREARC', $PARNOMBREARC, 100);
        oci_bind_by_name($stmt, ':PARTICKET', $PARTICKET, 100);
        oci_bind_by_name($stmt, ':PARRESPUE', $PARRESPUE, 100);

        if (!oci_execute($stmt)) {
            $e = oci_error($stmt);
            VAR_DUMP($e);
            exit;
        } else if ($PARRESPUE != 1) {
            $PARRESPUE = '0';
        }

        return $PARRESPUE;
    }

    public function categorias($pantalla = 0) {
        $this->session->set_userdata(array("pedidoAbono" => null));
        $this->session->set_userdata(array("llavesTemp" => null));

        $empresa = $this->session->userdata("entidad");
        $data['empresa'] = $empresa['NOMBREEMPRESA'];
        $usuario = $this->session->userdata("usuario");
        $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
        $data['ERROR'] = $pantalla;
        $data['menu'] = "soporte";
        $ultimaconexion = $this->session->userdata("ultimaconexion");
        $data['ultimaconexion'] = $ultimaconexion;
        $this->load->view('portal/templates/header2', $data);
        $this->load->view('portal/soporte/soporte', $data);
        $this->load->view('portal/templates/footer', $data);
    }

    public function tarjetas($pantalla = 0) {
        $post = $this->input->post();
        if ($post) {


            $respuesta = $this->soporte(
                    1, //$PARTIPCAT,
                    1, //$PARSUBCAT,
                    $this->session->userdata('usuario')['PK_ENT_CODIGO'], //$PARENTIDA,
                    $this->session->userdata('usuario')['USUARIO_ACCESO'], //$PARUSUSOL,rol
                    $this->session->userdata('usuario')['rol'], //$PARROLUSU,
                    $this->session->userdata('usuario')['campana'], //$PARCAMPAN,
                    $post['telefono'], //$PARTELCON,
                    $post['correo'], //$PARCORCON,
                    $post['desc'], //$PARDESCRI,
                    null, //$PARTIPDOC,
                    null, //$PARDOCUME,
                    null, //$PARPRODUC,
                    null, //$PARNUMTAR,
                    null, //$PARVALNOTCRE,
                    null, //$PARCOMPAR,
                    null, //$PARNUMFAC,
                    null, //$PARVALANT,
                    null, //$PARCOMPAG,
                    null, //$PARNUMGUI,
                    null, //$PARNUMPED,
                    null, //$PARCANTAR
                    null, //$PARURL
                    null //$PARNOMBREARC
            );


            if ($respuesta == 1) {
                $data = 1;
                redirect('portal/soporte/categorias/' . $data);
            } else {
                $data = $respuesta;
                redirect('portal/soporte/tarjetas/' . $data);
            }
        }
        $ultimaconexion = $this->session->userdata("ultimaconexion");
        $data['ultimaconexion'] = $ultimaconexion;
        $empresa = $this->session->userdata("entidad");
        $data['empresa'] = $empresa['NOMBREEMPRESA'];
        $usuario = $this->session->userdata("usuario");
        $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
        $data['menu'] = "soporte";
        $this->load->view('portal/templates/header2', $data);
        $this->load->view('portal/soporte/tarjetas', $data);
        $this->load->view('portal/templates/footer', $data);
    }

    public function tarjetas2($pantalla = 0) {
        $post = $this->input->post();
        if ($post) {
            $respuesta = $this->soporte(
                    1, //$PARTIPCAT,
                    2, //$PARSUBCAT,
                    $this->session->userdata('usuario')['PK_ENT_CODIGO'], //$PARENTIDA,
                    $this->session->userdata('usuario')['USUARIO_ACCESO'], //$PARUSUSOL,rol
                    $this->session->userdata('usuario')['rol'], //$PARROLUSU,
                    $this->session->userdata('usuario')['campana'], //$PARCAMPAN,
                    $post['telefono'], //$PARTELCON,
                    $post['correo'], //$PARCORCON,
                    $post['desc'], //$PARDESCRI,
                    $post['tdocumento'], //$PARTIPDOC,
                    $post['documento'], //$PARDOCUME,
                    $post['prod'], //$PARPRODUC,
                    $post['tarjeta'], //$PARNUMTAR,
                    null, //$PARVALNOTCRE,
                    null, //$PARCOMPAR,
                    null, //$PARNUMFAC,
                    null, //$PARVALANT,
                    null, //$PARCOMPAG,
                    null, //$PARNUMGUI,
                    null, //$PARNUMPED,
                    null, //$PARCANTAR
                    null, //$PARURL
                    null //$PARNOMBREARC
            );
            if ($respuesta == 1) {
                $data = 1;
                redirect('/index.php/portal/soporte/categorias/' . $data);
            } else {
                $data = $respuesta;
                redirect('portal/soporte/tarjetas2/' . $data);
            }
        }
        $empresa = $this->session->userdata("entidad");
        $data['empresa'] = $empresa['NOMBREEMPRESA'];
        $usuario = $this->session->userdata("usuario");
        $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
        $tipodocumento = $this->db->query('SELECT PK_TD_CODIGO,ABREVIACION,NOMBRE FROM MODCLIUNI.CLITBLTIPDOC');
        $data['tipoDocumento'] = $tipodocumento->result_array;
        $ultimaconexion = $this->session->userdata("ultimaconexion");
        $data['ultimaconexion'] = $ultimaconexion;
        $data['menu'] = "soporte";
        $this->load->view('portal/templates/header2', $data);
        $this->load->view('portal/soporte/tarjetas2', $data);
        $this->load->view('portal/templates/footer', $data);
    }

    public function tarjetas3($pantalla = 0) {
        $post = $this->input->post();
        if ($post) {
            $respuesta = $this->soporte(
                    1, //$PARTIPCAT,
                    3, //$PARSUBCAT,
                    $this->session->userdata('usuario')['PK_ENT_CODIGO'], //$PARENTIDA,
                    $this->session->userdata('usuario')['USUARIO_ACCESO'], //$PARUSUSOL,rol
                    $this->session->userdata('usuario')['rol'], //$PARROLUSU,
                    $this->session->userdata('usuario')['campana'], //$PARCAMPAN,
                    $post['telefono'], //$PARTELCON,
                    $post['correo'], //$PARCORCON,
                    $post['desc'], //$PARDESCRI,
                    $post['tdocumento'], //$PARTIPDOC,
                    $post['documento'], //$PARDOCUME,
                    $post['prod'], //$PARPRODUC,
                    $post['tarjeta'], //$PARNUMTAR,
                    null, //$PARVALNOTCRE,
                    null, //$PARCOMPAR,
                    null, //$PARNUMFAC,
                    null, //$PARVALANT,
                    null, //$PARCOMPAG,
                    null, //$PARNUMGUI,
                    null, //$PARNUMPED,
                    null, //$PARCANTAR
                    null, //$PARURL
                    null //$PARNOMBREARC
            );
            if ($respuesta == 1) {
                $data = 1;
                redirect('/index.php/portal/soporte/categorias/' . $data);
            } else {
                $data = $respuesta;
                redirect('portal/soporte/tarjetas3/' . $data);
            }
        }

        $empresa = $this->session->userdata("entidad");
        $data['empresa'] = $empresa['NOMBREEMPRESA'];
        $usuario = $this->session->userdata("usuario");
        $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
        $tipodocumento = $this->db->query('SELECT PK_TD_CODIGO,ABREVIACION,NOMBRE FROM MODCLIUNI.CLITBLTIPDOC');
        $data['tipoDocumento'] = $tipodocumento->result_array;
        $ultimaconexion = $this->session->userdata("ultimaconexion");
        $data['ultimaconexion'] = $ultimaconexion;
        $data['menu'] = "soporte";
        $this->load->view('portal/templates/header2', $data);
        $this->load->view('portal/soporte/tarjetas3', $data);
        $this->load->view('portal/templates/footer', $data);
    }

    public function tarjetas4($pantalla = 0) {
        $post = $this->input->post();
        if ($post) {
            $respuesta = $this->soporte(
                    1, //$PARTIPCAT,
                    4, //$PARSUBCAT,
                    $this->session->userdata('usuario')['PK_ENT_CODIGO'], //$PARENTIDA,
                    $this->session->userdata('usuario')['USUARIO_ACCESO'], //$PARUSUSOL,rol
                    $this->session->userdata('usuario')['rol'], //$PARROLUSU,
                    $this->session->userdata('usuario')['campana'], //$PARCAMPAN,
                    $post['telefono'], //$PARTELCON,
                    $post['correo'], //$PARCORCON,
                    $post['desc'], //$PARDESCRI,
                    $post['tdocumento'], //$PARTIPDOC,
                    $post['documento'], //$PARDOCUME,
                    $post['prod'], //$PARPRODUC,
                    $post['tarjeta'], //$PARNUMTAR,
                    null, //$PARVALNOTCRE,
                    null, //$PARCOMPAR,
                    null, //$PARNUMFAC,
                    null, //$PARVALANT,
                    null, //$PARCOMPAG,
                    null, //$PARNUMGUI,
                    null, //$PARNUMPED,
                    null, //$PARCANTAR
                    null, //$PARURL
                    null //$PARNOMBREARC
            );
            if ($respuesta == 1) {
                $data = 1;
                redirect('/index.php/portal/soporte/categorias/' . $data);
            } else {
                $data = $respuesta;
                redirect('portal/soporte/tarjetas4/' . $data);
            }
        }
        $empresa = $this->session->userdata("entidad");
        $data['empresa'] = $empresa['NOMBREEMPRESA'];
        $usuario = $this->session->userdata("usuario");
        $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
        $tipodocumento = $this->db->query('SELECT PK_TD_CODIGO,ABREVIACION,NOMBRE FROM MODCLIUNI.CLITBLTIPDOC');
        $data['tipoDocumento'] = $tipodocumento->result_array;
        $ultimaconexion = $this->session->userdata("ultimaconexion");
        $data['ultimaconexion'] = $ultimaconexion;
        $data['menu'] = "soporte";
        $this->load->view('portal/templates/header2', $data);
        $this->load->view('portal/soporte/tarjetas4', $data);
        $this->load->view('portal/templates/footer', $data);
    }

    public function comercial($pantalla = 0) {
        $post = $this->input->post();
        if ($post) {
            $parsubcat = 7;
            if ($pantalla == 1) {
                $parsubcat = 5;
            } else if ($pantalla == 2) {
                $parsubcat = 6;
            }
            $respuesta = $this->soporte(
                    2, //$PARTIPCAT,
                    $parsubcat, //$PARSUBCAT,
                    $this->session->userdata('usuario')['PK_ENT_CODIGO'], //$PARENTIDA,
                    $this->session->userdata('usuario')['USUARIO_ACCESO'], //$PARUSUSOL,rol
                    $this->session->userdata('usuario')['rol'], //$PARROLUSU,
                    $this->session->userdata('usuario')['campana'], //$PARCAMPAN,
                    $post['telefono'], //$PARTELCON,
                    $post['correo'], //$PARCORCON,
                    $post['desc'], //$PARDESCRI,
                    null, //$PARTIPDOC,
                    null, //$PARDOCUME,
                    null, //$PARPRODUC,
                    null, //$PARNUMTAR,
                    null, //$PARVALNOTCRE,
                    null, //$PARCOMPAR,
                    null, //$PARNUMFAC,
                    null, //$PARVALANT,
                    null, //$PARCOMPAG,
                    null, //$PARNUMGUI,
                    null, //$PARNUMPED,
                    null, //$PARCANTAR
                    null, //$PARURL
                    null //$PARNOMBREARC
            );
            if ($respuesta == 1) {
                $data = 1;
                redirect('/index.php/portal/soporte/categorias/' . $data);
            } else {
                $data = $respuesta;
                redirect('portal/soporte/comercial/' . $data);
            }
        }
        $empresa = $this->session->userdata("entidad");
        $data['empresa'] = $empresa['NOMBREEMPRESA'];
        $usuario = $this->session->userdata("usuario");
        $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
        $ultimaconexion = $this->session->userdata("ultimaconexion");
        $data['ultimaconexion'] = $ultimaconexion;
        $data['pantalla'] = $pantalla;
        $data['menu'] = "soporte";
        $this->load->view('portal/templates/header2', $data);
        $this->load->view('portal/soporte/comercial', $data);
        $this->load->view('portal/templates/footer', $data);
    }

    public function facturas($pantalla = 0) {
        $post = $this->input->post();
        if ($post) {
            //$dir = 'http://192.168.10.30:80/uploads/';
            //$dir = '/uploads/';
            $date = date('Y-m-d-H-i-s');
            $random = rand(1000, 9999);
            $split_name_file = explode('.', basename($_FILES['file']['name']));
            $extention = end($split_name_file);
            $name = strtolower($date . '-' . $random . '.' . $extention);
            // $file_dir = $dir . $name; //.basename($_FILES['file']['name']);
            $PARURL = 'http://localhost/uploads/' . $name;
            //$PARURL=$file_dir;
            // var_dump($file_dir);
            $temp_file = $_FILES['file']['tmp_name'];
            try {
                move_uploaded_file($temp_file, $PARURL);
                echo "El fichero es válido y se subió con éxito.\n";
                $cargado = 1;
            } catch (Exception $exc) {
                echo $exc->getTraceAsString();
                echo $exc;
                echo "¡Posible ataque de subida de ficheros!\n";
                $cargado = 2;
                exit();
            }


            $PARNOMBREARC = $_FILES['file']['name'];
            if ($cargado == 1) {

                $respuesta = $this->soporte(
                        3, //$PARTIPCAT,
                        8, //$PARSUBCAT,
                        $this->session->userdata('usuario')['PK_ENT_CODIGO'], //$PARENTIDA,
                        $this->session->userdata('usuario')['USUARIO_ACCESO'], //$PARUSUSOL,rol
                        $this->session->userdata('usuario')['rol'], //$PARROLUSU,
                        $this->session->userdata('usuario')['campana'], //$PARCAMPAN,
                        $post['telefono'], //$PARTELCON,
                        $post['correo'], //$PARCORCON,
                        $post['desc'], //$PARDESCRI,
                        null, //$PARTIPDOC,
                        null, //$PARDOCUME,
                        null, //$PARPRODUC,
                        null, //$PARNUMTAR,
                        null, //$PARVALNOTCRE,
                        null, //$PARCOMPAR,
                        null, //$PARNUMFAC,
                        null, //$PARVALANT,
                        null, //$PARCOMPAG,
                        null, //$PARNUMGUI,
                        null, //$PARNUMPED,
                        null, //$PARCANTAR
                        $PARURL, //$PARURL
                        $PARNOMBREARC //$PARNOMBREARC
                );

                if ($respuesta == 1) {
                    $data = 1;
                    redirect('/index.php/portal/soporte/categorias/' . $data);
                } else {
                    $data = $respuesta;
                    redirect('portal/soporte/facturas/' . $data);
                }
            }
        }
        $empresa = $this->session->userdata("entidad");
        $data['empresa'] = $empresa['NOMBREEMPRESA'];
        $usuario = $this->session->userdata("usuario");
        $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
        $ultimaconexion = $this->session->userdata("ultimaconexion");
        $data['ultimaconexion'] = $ultimaconexion;
        $data['menu'] = "soporte";
        $this->load->view('portal/templates/header2', $data);
        $this->load->view('portal/soporte/facturas', $data);
        $this->load->view('portal/templates/footer', $data);
    }

    public function facturas2($pantalla = 0) {
        $post = $this->input->post();
        if ($post) {
            $respuesta = $this->soporte(
                    3, //$PARTIPCAT,
                    9, //$PARSUBCAT,
                    $this->session->userdata('usuario')['PK_ENT_CODIGO'], //$PARENTIDA,
                    $this->session->userdata('usuario')['USUARIO_ACCESO'], //$PARUSUSOL,rol
                    $this->session->userdata('usuario')['rol'], //$PARROLUSU,
                    $this->session->userdata('usuario')['campana'], //$PARCAMPAN,
                    $post['telefono'], //$PARTELCON,
                    $post['correo'], //$PARCORCON,
                    null, //$PARDESCRI,
                    null, //$PARTIPDOC,
                    null, //$PARDOCUME,
                    null, //$PARPRODUC,
                    null, //$PARNUMTAR,
                    $post['valnotcre'], //$PARVALNOTCRE,
                    null, //$PARCOMPAR,
                    null, //$PARNUMFAC,
                    null, //$PARVALANT,
                    null, //$PARCOMPAG,
                    null, //$PARNUMGUI,
                    null, //$PARNUMPED,
                    null, //$PARCANTAR
                    null, //$PARURL
                    null //$PARNOMBREARC
            );
            if ($respuesta == 1) {
                $data = 1;
                redirect('/index.php/portal/soporte/categorias/' . $data);
            } else {
                $data = $respuesta;
                redirect('portal/soporte/facturas2/' . $data);
            }
        }
        $empresa = $this->session->userdata("entidad");
        $data['empresa'] = $empresa['NOMBREEMPRESA'];
        $usuario = $this->session->userdata("usuario");
        $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
        $ultimaconexion = $this->session->userdata("ultimaconexion");
        $data['ultimaconexion'] = $ultimaconexion;
        $data['menu'] = "soporte";
        $this->load->view('portal/templates/header2', $data);
        $this->load->view('portal/soporte/facturas2', $data);
        $this->load->view('portal/templates/footer', $data);
    }

    public function facturas3($pantalla = 0) {
        $post = $this->input->post();
        if ($post) {
            $respuesta = $this->soporte(
                    3, //$PARTIPCAT,
                    10, //$PARSUBCAT,
                    $this->session->userdata('usuario')['PK_ENT_CODIGO'], //$PARENTIDA,
                    $this->session->userdata('usuario')['USUARIO_ACCESO'], //$PARUSUSOL,rol
                    $this->session->userdata('usuario')['rol'], //$PARROLUSU,
                    $this->session->userdata('usuario')['campana'], //$PARCAMPAN,
                    $post['telefono'], //$PARTELCON,
                    $post['correo'], //$PARCORCON,
                    null, //$PARDESCRI,
                    $post['tdocumento'], //$PARTIPDOC,
                    $post['numeroDocumento'], //$PARDOCUME,
                    $post['producto'], //$PARPRODUC,
                    $post['numeroTarjeta'], //$PARNUMTAR,
                    $post['valorNotaCredito'], //$PARVALNOTCRE,
                    null, //$PARCOMPAR,
                    null, //$PARNUMFAC,
                    null, //$PARVALANT,
                    null, //$PARCOMPAG,
                    null, //$PARNUMGUI,
                    null, //$PARNUMPED,
                    null, //$PARCANTAR
                    null, //$PARURL
                    null //$PARNOMBREARC
            );
            if ($respuesta == 1) {
                $data = 1;
                redirect('/index.php/portal/soporte/categorias/' . $data);
            } else {
                $data = $respuesta;
                redirect('portal/soporte/facturas3/' . $data);
            }
        }
        $empresa = $this->session->userdata("entidad");
        $data['empresa'] = $empresa['NOMBREEMPRESA'];
        $usuario = $this->session->userdata("usuario");
        $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
        $tipodocumento = $this->db->query('SELECT PK_TD_CODIGO,ABREVIACION,NOMBRE FROM MODCLIUNI.CLITBLTIPDOC');
        $data['tipoDocumento'] = $tipodocumento->result_array;
        $ultimaconexion = $this->session->userdata("ultimaconexion");
        $data['ultimaconexion'] = $ultimaconexion;
        $data['menu'] = "soporte";
        $this->load->view('portal/templates/header2', $data);
        $this->load->view('portal/soporte/facturas3', $data);
        $this->load->view('portal/templates/footer', $data);
    }

    public function abonos($pantalla = 0) {
        $post = $this->input->post();
        if ($post) {
            $respuesta = $this->soporte(
                    4, //$PARTIPCAT,
                    11, //$PARSUBCAT,
                    $this->session->userdata('usuario')['PK_ENT_CODIGO'], //$PARENTIDA,
                    $this->session->userdata('usuario')['USUARIO_ACCESO'], //$PARUSUSOL,rol
                    $this->session->userdata('usuario')['rol'], //$PARROLUSU,
                    $this->session->userdata('usuario')['campana'], //$PARCAMPAN,
                    $post['telefono'], //$PARTELCON,
                    $post['correo'], //$PARCORCON,
                    $post['desc'], //$PARDESCRI,
                    $post['tdocumento'], //$PARTIPDOC,
                    $post['numeroDocumento'], //$PARDOCUME,
                    $post['prod'], //$PARPRODUC,
                    $post['tarjeta'], //$PARNUMTAR,
                    $post['valorNotaCredito'], //$PARVALNOTCRE,
                    $post['empresaComp'], //$PARCOMPAR
                    null, //$PARNUMFAC,
                    null, //$PARVALANT,
                    null, //$PARCOMPAG,
                    null, //$PARNUMGUI,
                    null, //$PARNUMPED,
                    null, //$PARCANTAR
                    null, //$PARURL
                    null //$PARNOMBREARC
            );
            if ($respuesta == 1) {
                $data = 1;
                redirect('/index.php/portal/soporte/categorias/' . $data);
            } else {
                $data = $respuesta;
                redirect('portal/soporte/abonos/' . $data);
            }
        }
        $empresa = $this->session->userdata("entidad");
        $data['empresa'] = $empresa['NOMBREEMPRESA'];
        $usuario = $this->session->userdata("usuario");
        $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
        $tipodocumento = $this->db->query('SELECT PK_TD_CODIGO,ABREVIACION,NOMBRE FROM MODCLIUNI.CLITBLTIPDOC');
        $data['tipoDocumento'] = $tipodocumento->result_array;
        $ultimaconexion = $this->session->userdata("ultimaconexion");
        $data['ultimaconexion'] = $ultimaconexion;
        $data['menu'] = "soporte";
        $this->load->view('portal/templates/header2', $data);
        $this->load->view('portal/soporte/abonos', $data);
        $this->load->view('portal/templates/footer', $data);
    }

    public function abonos2($pantalla = 0) {
        $post = $this->input->post();
        if ($post) {
            $respuesta = $this->soporte(
                    4, //$PARTIPCAT,
                    12, //$PARSUBCAT,
                    $this->session->userdata('usuario')['PK_ENT_CODIGO'], //$PARENTIDA,
                    $this->session->userdata('usuario')['USUARIO_ACCESO'], //$PARUSUSOL,rol
                    $this->session->userdata('usuario')['rol'], //$PARROLUSU,
                    $this->session->userdata('usuario')['campana'], //$PARCAMPAN,
                    $post['telefono'], //$PARTELCON,
                    $post['correo'], //$PARCORCON,
                    $post['motivo'], //$PARDESCRI,
                    null, //$PARTIPDOC,
                    null, //$PARDOCUME,
                    null, //$PARPRODUC,
                    null, //$PARNUMTAR,
                    null, //$PARVALNOTCRE,
                    null, //$PARCOMPAR
                    $post['factura'], //$PARNUMFAC,
                    $post['anticipo'], //$PARVALANT,
                    $post['compromiso'], //$PARCOMPAG,
                    null, //$PARNUMGUI,
                    null, //$PARNUMPED,
                    null, //$PARCANTAR
                    null, //$PARURL
                    null //$PARNOMBREARC
            );
            if ($respuesta == 1) {
                $data = 1;
                redirect('/index.php/portal/soporte/categorias/' . $data);
            } else {
                $data = $respuesta;
                redirect('portal/soporte/abonos2/' . $data);
            }
        }
        $empresa = $this->session->userdata("entidad");
        $data['empresa'] = $empresa['NOMBREEMPRESA'];
        $usuario = $this->session->userdata("usuario");
        $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
        $ultimaconexion = $this->session->userdata("ultimaconexion");
        $data['ultimaconexion'] = $ultimaconexion;
        $data['menu'] = "soporte";
        $this->load->view('portal/templates/header2', $data);
        $this->load->view('portal/soporte/abonos2', $data);
        $this->load->view('portal/templates/footer', $data);
    }

    public function abonos3($pantalla = 0) {
        $post = $this->input->post();
        if ($post) {
            $respuesta = $this->soporte(
                    4, //$PARTIPCAT,
                    13, //$PARSUBCAT,
                    $this->session->userdata('usuario')['PK_ENT_CODIGO'], //$PARENTIDA,
                    $this->session->userdata('usuario')['USUARIO_ACCESO'], //$PARUSUSOL,rol
                    $this->session->userdata('usuario')['rol'], //$PARROLUSU,
                    $this->session->userdata('usuario')['campana'], //$PARCAMPAN,
                    $post['telefono'], //$PARTELCON,
                    $post['correo'], //$PARCORCON,
                    $post['motivo'], //$PARDESCRI,
                    null, //$PARTIPDOC,
                    null, //$PARDOCUME,
                    null, //$PARPRODUC,
                    null, //$PARNUMTAR,
                    null, //$PARVALNOTCRE,
                    null, //$PARCOMPAR
                    $post['factura'], //$PARNUMFAC,
                    $post['anticipo'], //$PARVALANT,
                    null, //$PARCOMPAG,
                    null, //$PARNUMGUI,
                    null, //$PARNUMPED,
                    null, //$PARCANTAR
                    null, //$PARURL
                    null //$PARNOMBREARC
            );
            if ($respuesta == 1) {
                $data = 1;
                redirect('/index.php/portal/soporte/categorias/' . $data);
            } else {
                $data = $respuesta;
                redirect('portal/soporte/abonos3/' . $data);
            }
        }
        $empresa = $this->session->userdata("entidad");
        $data['empresa'] = $empresa['NOMBREEMPRESA'];
        $usuario = $this->session->userdata("usuario");
        $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
        $ultimaconexion = $this->session->userdata("ultimaconexion");
        $data['ultimaconexion'] = $ultimaconexion;
        $data['menu'] = "soporte";
        $this->load->view('portal/templates/header2', $data);
        $this->load->view('portal/soporte/abonos3', $data);
        $this->load->view('portal/templates/footer', $data);
    }

    public function abonos4($pantalla = 0) {
        $post = $this->input->post();
        if ($post) {
            $respuesta = $this->soporte(
                    4, //$PARTIPCAT,
                    14, //$PARSUBCAT,
                    $this->session->userdata('usuario')['PK_ENT_CODIGO'], //$PARENTIDA,
                    $this->session->userdata('usuario')['USUARIO_ACCESO'], //$PARUSUSOL,rol
                    $this->session->userdata('usuario')['rol'], //$PARROLUSU,
                    $this->session->userdata('usuario')['campana'], //$PARCAMPAN,
                    $post['telefono'], //$PARTELCON,
                    $post['correo'], //$PARCORCON,
                    $post['desc'], //$PARDESCRI,
                    null, //$PARTIPDOC,
                    null, //$PARDOCUME,
                    null, //$PARPRODUC,
                    null, //$PARNUMTAR,
                    null, //$PARVALNOTCRE,
                    null, //$PARCOMPAR
                    null, //$PARNUMFAC,
                    null, //$PARVALANT,
                    null, //$PARCOMPAG,
                    $post['guia'], //$PARNUMGUI,
                    null, //$PARNUMPED,
                    null, //$PARCANTAR
                    null, //$PARURL
                    null //$PARNOMBREARC
            );
            if ($respuesta == 1) {
                $data = 1;
                redirect('/index.php/portal/soporte/categorias/' . $data);
            } else {
                $data = $respuesta;
                redirect('portal/soporte/abonos4/' . $data);
            }
        }
        $empresa = $this->session->userdata("entidad");
        $data['empresa'] = $empresa['NOMBREEMPRESA'];
        $usuario = $this->session->userdata("usuario");
        $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
        $ultimaconexion = $this->session->userdata("ultimaconexion");
        $data['ultimaconexion'] = $ultimaconexion;
        $data['menu'] = "soporte";
        $this->load->view('portal/templates/header2', $data);
        $this->load->view('portal/soporte/abonos4', $data);
        $this->load->view('portal/templates/footer', $data);
    }

    public function entregas($pantalla = 0) {
        $post = $this->input->post();
        if ($post) {
            $respuesta = $this->soporte(
                    5, //$PARTIPCAT,
                    15, //$PARSUBCAT,
                    $this->session->userdata('usuario')['PK_ENT_CODIGO'], //$PARENTIDA,
                    $this->session->userdata('usuario')['USUARIO_ACCESO'], //$PARUSUSOL,rol
                    $this->session->userdata('usuario')['rol'], //$PARROLUSU,
                    $this->session->userdata('usuario')['campana'], //$PARCAMPAN,
                    $post['telefono'], //$PARTELCON,
                    $post['correo'], //$PARCORCON,
                    $post['obs'], //$PARDESCRI,
                    null, //$PARTIPDOC,
                    null, //$PARDOCUME,
                    null, //$PARPRODUC,
                    null, //$PARNUMTAR,
                    null, //$PARVALNOTCRE,
                    null, //$PARCOMPAR
                    null, //$PARNUMFAC,
                    null, //$PARVALANT,
                    null, //$PARCOMPAG,
                    null, //$PARNUMGUI,
                    $post['pedido'], //$PARNUMPED,
                    $post['cantidad'], //$PARCANTAR
                    null, //$PARURL
                    null //$PARNOMBREARC
            );
            if ($respuesta == 1) {
                $data = 1;
                redirect('/index.php/portal/soporte/categorias/' . $data);
            } else {
                $data = $respuesta;
                redirect('portal/soporte/entregas/' . $data);
            }
        }
        $empresa = $this->session->userdata("entidad");
        $data['empresa'] = $empresa['NOMBREEMPRESA'];
        $usuario = $this->session->userdata("usuario");
        $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
        $ultimaconexion = $this->session->userdata("ultimaconexion");
        $data['ultimaconexion'] = $ultimaconexion;
        $data['menu'] = "soporte";
        $this->load->view('portal/templates/header2', $data);
        $this->load->view('portal/soporte/entregas', $data);
        $this->load->view('portal/templates/footer', $data);
    }

    public function plataforma($pantalla = 0) {
        $post = $this->input->post();
        if ($post) {
            $respuesta = $this->soporte(
                    6, //$PARTIPCAT,
                    16, //$PARSUBCAT,
                    $this->session->userdata('usuario')['PK_ENT_CODIGO'], //$PARENTIDA,
                    $this->session->userdata('usuario')['USUARIO_ACCESO'], //$PARUSUSOL,rol
                    $this->session->userdata('usuario')['rol'], //$PARROLUSU,
                    $this->session->userdata('usuario')['campana'], //$PARCAMPAN,
                    $post['telefono'], //$PARTELCON,
                    $post['correo'], //$PARCORCON,
                    $post['desc'], //$PARDESCRI,
                    null, //$PARTIPDOC,
                    null, //$PARDOCUME,
                    null, //$PARPRODUC,
                    null, //$PARNUMTAR,
                    null, //$PARVALNOTCRE,
                    null, //$PARCOMPAR
                    null, //$PARNUMFAC,
                    null, //$PARVALANT,
                    null, //$PARCOMPAG,
                    null, //$PARNUMGUI,
                    null, //$PARNUMPED,
                    null, //$PARCANTAR
                    null, //$PARURL
                    null //$PARNOMBREARC
            );
            if ($respuesta == 1) {
                $data = 1;
                redirect('/index.php/portal/soporte/categorias/' . $data);
            } else {
                $data = $respuesta;
                redirect('portal/soporte/plataforma/' . $data);
            }
        }
        $empresa = $this->session->userdata("entidad");
        $data['empresa'] = $empresa['NOMBREEMPRESA'];
        $usuario = $this->session->userdata("usuario");
        $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
        $ultimaconexion = $this->session->userdata("ultimaconexion");
        $data['ultimaconexion'] = $ultimaconexion;
        $data['menu'] = "soporte";
        $this->load->view('portal/templates/header2', $data);
        $this->load->view('portal/soporte/plataforma', $data);
        $this->load->view('portal/templates/footer', $data);
    }

    public function qys($pantalla = 0) {
        $post = $this->input->post();
        if ($post) {
            $respuesta = $this->soporte(
                    7, //$PARTIPCAT,
                    null, //$PARSUBCAT,
                    $this->session->userdata('usuario')['PK_ENT_CODIGO'], //$PARENTIDA,
                    $this->session->userdata('usuario')['USUARIO_ACCESO'], //$PARUSUSOL,rol
                    $this->session->userdata('usuario')['rol'], //$PARROLUSU,
                    $this->session->userdata('usuario')['campana'], //$PARCAMPAN,
                    $post['telefono'], //$PARTELCON,
                    $post['correo'], //$PARCORCON,
                    $post['desc'], //$PARDESCRI,
                    null, //$PARTIPDOC,
                    null, //$PARDOCUME,
                    null, //$PARPRODUC,
                    null, //$PARNUMTAR,
                    null, //$PARVALNOTCRE,
                    null, //$PARCOMPAR
                    null, //$PARNUMFAC,
                    null, //$PARVALANT,
                    null, //$PARCOMPAG,
                    null, //$PARNUMGUI,
                    null, //$PARNUMPED,
                    null, //$PARCANTAR
                    null, //$PARURL
                    null //$PARNOMBREARC
            );
            if ($respuesta == 1) {
                $data = 1;
                redirect('/index.php/portal/soporte/categorias/' . $data);
            } else {
                $data = $respuesta;
                redirect('portal/soporte/qys/' . $data);
            }
        }
        $empresa = $this->session->userdata("entidad");
        $data['empresa'] = $empresa['NOMBREEMPRESA'];
        $usuario = $this->session->userdata("usuario");
        $data['usuario'] = $usuario['NOMBRE'] . " " . $usuario['APELLIDO'];
        $ultimaconexion = $this->session->userdata("ultimaconexion");
        $data['ultimaconexion'] = $ultimaconexion;
        $data['menu'] = "soporte";
        $this->load->view('portal/templates/header2', $data);
        $this->load->view('portal/soporte/qys', $data);
        $this->load->view('portal/templates/footer', $data);
    }

}
