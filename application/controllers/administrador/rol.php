<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Rol extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->acceso->validar();
    }

    public function lista() {
        $data_header['menu_activo'] = 'usuario';

        $data_header['breadcrumb'] = array(
            array(
                'link' => '/administrador/rol/lista/',
                'nombre' => 'Roles'
            ),
        );

        $grid['select'] = base64_encode(" * ");
        $grid['from'] = base64_encode(" 
            FROM 
                admin_rol
        ");
        $grid['where'] = base64_encode("");
        $this->session->set_userdata(array("roles" => $grid));

        $this->load->view('administrador/templates/header', $data_header);
        $this->load->view('administrador/rol/lista', $data_body);
        $this->load->view('administrador/templates/footer');
    }

    public function agregar($id_admin_rol = NULL, $alert = NULL) {

        $post = $this->input->post();
        if ($post) {
            $post['id_admin_rol'] = $id_admin_rol;
            $id_admin_rol = $this->modelo->addAdmin_rol($post);

            $this->modelo->delAdmin_privilegios_rol(array('id_admin_rol' => $id_admin_rol));

            foreach ($post as $key => $id_admin_privilegios) {
                if (strpos($key, 'privilegio_') !== false) {
                    $privilegio = array(
                        'id_admin_rol' => $id_admin_rol,
                        'id_admin_privilegios' => $id_admin_privilegios
                    );
                    $id_admin_privilegios_rol = $this->modelo->addAdmin_privilegios_rol($privilegio);
                }
            }
            //redirect('/administrador/rol/agregar/'.$id_admin_rol.'/ok');
        }

        if ($alert == 'ok') {
            $data_body['alert'] = 'ok';
        }

        if ($id_admin_rol == NULL)
            $data_body['operacion'] = 'Agregar';
        else
            $data_body['operacion'] = 'Modificar';

        $data_header['menu_activo'] = 'usuario';
        $data_header['breadcrumb'] = array(
            array(
                'link' => '/administrador/rol/lista/',
                'nombre' => 'Roles'
            ),
            array(
                'link' => '#',
                'nombre' => $data_body['operacion']
            ),
        );

        if ($id_admin_rol != NULL) {
            $data_body['rol'] = $this->modelo->getAdmin_rol(array('id_admin_rol' => $id_admin_rol));
            $data_body['rol'] = $data_body['rol'][0];
            $data_body['privilegios_rol'] = $this->modelo->getAdmin_privilegios_rol(array('id_admin_rol' => $id_admin_rol));
        }

        $data_body['privilegios'] = $this->modelo->getAdmin_privilegios(array(),array("order" => "codigo_admin_privilegios ASC"));

        $this->load->view('administrador/templates/header', $data_header);
        $this->load->view('administrador/rol/agregar', $data_body);
        $this->load->view('administrador/templates/footer');
    }

    public function eliminar($id_admin_rol) {
        $this->modelo->delAdmin_rol(array('id_admin_rol' => $id_admin_rol));
        redirect('/administrador/rol/lista');
    }

}
