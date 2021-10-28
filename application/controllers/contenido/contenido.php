<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * Posibles clase para los campos:
 * required -> campo requerido
 * daos_datepicker -> fecha para un campo
 * daos_editor -> editor simple para un campo
 * 
 */

class Contenido extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->acceso->validar();
    }

    public function seccion($id_conte_objeto, $id_conte_grupo = 0) {
        $data_header['menu_activo'] = 'contenido';

        $objeto = $this->modelo->getConte_objeto(array('id_conte_objeto' => $id_conte_objeto));
        $objeto = $objeto[0];
        $data_body['objeto'] = $objeto;
        $data_body['id_conte_grupo'] = $id_conte_grupo;

        $data_header['breadcrumb'] = array(
            array(
                'link' => '#',
                'nombre' => 'Administracion de contenidos'
            ),
            array(
                'link' => '/contenido/seccion/' . $id_conte_objeto,
                'nombre' => $objeto['nombre_conte_objeto']
            ),
        );

        if ($id_conte_grupo == NULL)
            $grupo = $this->modelo->getConte_grupo(array('id_conte_objeto' => $id_conte_objeto));
        else
            $grupo = $this->modelo->getConte_grupo(array('id_conte_objeto' => $id_conte_objeto, 'padre_conte_grupo' => $id_conte_grupo));

        if (count($grupo) < $objeto['cantidad_conte_objeto']) {
            $data_body['agregar'] = TRUE;
        } else {
            $data_body['agregar'] = FALSE;
        }

        $hijos = $this->modelo->getConte_objeto(array('padre_conte_objeto' => $id_conte_objeto));
        $data_body['hijos'] = $hijos;

        $data_body['campos'] = $this->modelo->getConte_campo(array('id_conte_objeto' => $id_conte_objeto));
        if ($id_conte_grupo == 0)
            $data_body['grupos'] = $this->modelo->getConte_grupo(array('id_conte_objeto' => $id_conte_objeto));
        else
            $data_body['grupos'] = $this->modelo->getConte_grupo(array('id_conte_objeto' => $id_conte_objeto, 'padre_conte_grupo' => $id_conte_grupo));
        foreach ($data_body['grupos'] as $key => $grupo) {
            $valores = $this->modelo->getConte_valor(array('id_conte_grupo' => $grupo['id_conte_grupo']));
            foreach ($valores as $valor) {
                $data_body['grupos'][$key]['contenido_conte_valor_' . $valor['id_conte_campo']] = $valor['contenido_conte_valor'];
            }
        }

        $this->load->view('administrador/templates/header', $data_header);
        $this->load->view('contenido/lista', $data_body);
        $this->load->view('administrador/templates/footer');
    }

    public function agregar($id_conte_objeto, $padre, $id_conte_grupo = NULL) {

        $data_header['menu_activo'] = 'contenido';

        $objeto = $this->modelo->getConte_objeto(array('id_conte_objeto' => $id_conte_objeto));
        $objeto = $objeto[0];
        $data_body['objeto'] = $objeto;
        $data_body['padre'] = $padre;
        if ($id_conte_grupo == NULL)
            $data_body['operacion'] = 'Agregar';
        else
            $data_body['operacion'] = 'Modificar';

        $data_header['breadcrumb'] = array(
            array(
                'link' => '#',
                'nombre' => 'Administracion de contenidos'
            ),
            array(
                'link' => '/contenido/seccion/' . $id_conte_objeto,
                'nombre' => $objeto['nombre_conte_objeto']
            ),
        );

        $data_body['campos'] = $this->modelo->getConte_campo(array('id_conte_objeto' => $id_conte_objeto));

        $post = $this->input->post();
        if ($post) {
            if ($id_conte_grupo == NULL)
                $id_conte_grupo = $this->modelo->addConte_grupo(array(
                    'padre_conte_grupo' => $padre,
                    'id_conte_objeto' => $id_conte_objeto
                ));

            $config['upload_path'] = './static/files_contenido/';
            $config['allowed_types'] = 'jpg|gif|png|jpeg|pdf|JPG|GIF|PNG|JPEG|PDF';
            $config['max_size'] = '0';
            $config['max_width'] = '0';
            $config['max_height'] = '0';
            $config['encrypt_name'] = TRUE;
            $this->load->library('upload', $config);

            foreach ($data_body['campos'] as $campo) {
                if ($_FILES['file_' . $campo['id_conte_campo']]["name"] != "") {

                    if (!$this->upload->do_upload('file_' . $campo['id_conte_campo'])) {
                        $data_body['alert'] = 3;
                    } else {
                        $data = $this->upload->data();
                        $this->modelo->delConte_valor(array(
                            "id_conte_campo" => $campo['id_conte_campo'],
                            "id_conte_grupo" => $id_conte_grupo
                        ));
                        $id_conte_valor = $this->modelo->addConte_valor(array(
                            "id_conte_campo" => $campo['id_conte_campo'],
                            "id_conte_grupo" => $id_conte_grupo,
                            "contenido_conte_valor" => "/static/files_contenido/" . $data['file_name']
                        ));
                    }
                }
            }

            foreach ($post as $key => $value) {
                if(is_numeric($key)){
                    $this->modelo->delConte_valor(array(
                        "id_conte_campo" => $key,
                        "id_conte_grupo" => $id_conte_grupo
                    ));
                    $id_conte_valor = $this->modelo->addConte_valor(array(
                        "id_conte_campo" => $key,
                        "id_conte_grupo" => $id_conte_grupo,
                        "contenido_conte_valor" => $value
                    ));
                }
            }
            if (!isset($data_body['alert']))
                redirect("/contenido/seccion/$id_conte_objeto/$padre");
        }

        if ($id_conte_grupo != NULL) {
            foreach ($data_body['campos'] as $key => $value) {
                $valor = $this->modelo->getConte_valor(array(
                    'id_conte_campo' => $value['id_conte_campo'],
                    'id_conte_grupo' => $id_conte_grupo
                ));
                $data_body['campos'][$key]['value'] = $valor[0]['contenido_conte_valor'];
            }
        }

        $this->load->view('administrador/templates/header', $data_header);
        $this->load->view('contenido/agregar', $data_body);
        $this->load->view('administrador/templates/footer');
    }

    public function eliminar($id_conte_objeto = null, $id_conte_grupo = NULL) {
        if ($id_conte_grupo != NULL)
            $this->modelo->delConte_grupo(array('id_conte_grupo' => $id_conte_grupo));
        redirect("/contenido/seccion/$id_conte_objeto/0");
    }

}
