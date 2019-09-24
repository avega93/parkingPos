<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Privilegio extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->acceso->validar();
    }

    public function lista() {
        $data_header['menu_activo'] = 'usuario';

        $data_header['breadcrumb'] = array(
            array(
                'link' => '/administrador/privilegio/lista/',
                'nombre' => 'Privilegios'
            ),
        );

        $grid['select'] = base64_encode(" * ");
        $grid['from'] = base64_encode(" 
            FROM 
                admin_privilegios
        ");
        $grid['where'] = base64_encode("");
        $this->session->set_userdata(array("privilegios" => $grid));

        $this->load->view('administrador/templates/header', $data_header);
        $this->load->view('administrador/privilegio/lista', $data_body);
        $this->load->view('administrador/templates/footer');
    }

    public function agregar($id_admin_privilegios = NULL, $alert = NULL) {

        $post = $this->input->post();
        if ($post) {
            $post['id_admin_privilegios'] = $id_admin_privilegios;
            $id_admin_privilegios = $this->modelo->addAdmin_privilegios($post);
            redirect('/administrador/privilegio/agregar/' . $id_admin_privilegios . '/ok');
        }

        if ($alert == 'ok') {
            $data_body['alert'] = 'ok';
        }

        if ($id_admin_privilegios == NULL)
            $data_body['operacion'] = 'Agregar';
        else
            $data_body['operacion'] = 'Modificar';

        $data_header['menu_activo'] = 'usuario';
        $data_header['breadcrumb'] = array(
            array(
                'link' => '/administrador/privilegio/lista/',
                'nombre' => 'Privilegios'
            ),
            array(
                'link' => '#',
                'nombre' => $data_body['operacion']
            ),
        );

        if ($id_admin_privilegios != NULL) {
            $data_body['privilegio'] = $this->modelo->getAdmin_privilegios(array('id_admin_privilegios' => $id_admin_privilegios));
            $data_body['privilegio'] = $data_body['privilegio'][0];
        }

        $this->load->view('administrador/templates/header', $data_header);
        $this->load->view('administrador/privilegio/agregar', $data_body);
        $this->load->view('administrador/templates/footer');
    }

    public function eliminar($id_admin_privilegios) {
        $this->modelo->delAdmin_privilegios(array('id_admin_privilegios' => $id_admin_privilegios));
        redirect('/administrador/privilegio/lista');
    }

}
