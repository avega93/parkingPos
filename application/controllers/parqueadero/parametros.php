<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Parametros extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->acceso->validar();
    }

    public function lista($alert = NULL) {
        $data_header['menu_activo'] = 'parqueadero';
        if ($alert == 'ok') {
            $data_body['alert'] = 'ok';
        }
        if ($alert == 'exist') {
            $data_body['alert'] = 'exist';
        }
        $data_header['breadcrumb'] = array(
            array(
                'link' => '/parqueadero/parametros/lista',
                'nombre' => 'Parametros'
            ),
        );
        $post = $this->input->post();
        if ($post) {
            $post['id_par_parametros'] = 1;
            $this->modelo->addpar_parametros($post);
            redirect('/parqueadero/parametros/lista/ok');
        }

        $parametros = $this->modelo->getpar_parametros(array('id_par_parametros' => 1));
        $parametros = $parametros[0];
        $data_body['parametros'] = $parametros;

        $this->load->view('administrador/templates/header', $data_header);
        $this->load->view('parqueadero/parametros/lista', $data_body);
        $this->load->view('administrador/templates/footer');
    }

}
