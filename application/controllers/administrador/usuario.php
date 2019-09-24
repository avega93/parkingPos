<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Usuario extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->acceso->validar();
    }

    public function lista($alert = NULL) {
        $data_header['menu_activo'] = 'usuario';

        $data_header['breadcrumb'] = array(
            array(
                'link' => '/administrador/usuario/lista',
                'nombre' => 'Usuarios'
            ),
        );

        $usuarios['select'] = base64_encode(" * ");
        $usuarios['from'] = base64_encode(" 
            FROM 
                admin_usuario u
                INNER JOIN admin_rol r ON u.id_admin_rol = r.id_admin_rol
                INNER JOIN admin_estado_usuario e ON u.id_admin_estado_usuario = e.id_admin_estado_usuario
        ");
        $usuarios['where'] = base64_encode("");
        $usuarios['group'] = base64_encode("");
        $usuarios['order'] = base64_encode("");
        $usuarios['limit'] = base64_encode("");
        if ($alert == 'ok') {
            $data_body['alert'] = 'ok';
        }
        $this->session->set_userdata(array("usuarios" => $usuarios));

        $this->load->view('administrador/templates/header', $data_header);
        $this->load->view('administrador/usuario/lista', $data_body);
        $this->load->view('administrador/templates/footer');
    }

    public function agregar($id_admin_usuario = NULL, $alert = NULL) {

        $post = $this->input->post();
        if ($post) {
            if ($id_admin_usuario == NULL)
                $post['password_admin_usuario'] = md5('123456');
            $post['id_admin_usuario'] = $id_admin_usuario;
            $id_admin_usuario = $this->modelo->addadmin_usuario($post);
            redirect('/administrador/usuario/lista/ok');
        }

        if ($alert == 'ok') {
            $data_body['alert'] = 'ok';
        }

        $data_header['menu_activo'] = 'usuario';

        if ($id_admin_usuario == NULL)
            $data_body['operacion'] = 'Agregar';
        else
            $data_body['operacion'] = 'Modificar';

        $data_header['breadcrumb'] = array(
            array(
                'link' => '/administrador/usuario/lista',
                'nombre' => 'Usuarios'
            ),
            array(
                'link' => '#',
                'nombre' => $data_body['operacion']
            ),
        );


        if ($id_admin_usuario != NULL) {
            $data_body['admin_usuario'] = $this->modelo->getadmin_usuario(array('id_admin_usuario' => $id_admin_usuario));
            $data_body['admin_usuario'] = $data_body['admin_usuario'][0];
        }

        $data_body['roles'] = $this->modelo->getAdmin_rol();
        $data_body['estados'] = $this->modelo->getAdmin_estado_usuario();
        $this->load->view('administrador/templates/header', $data_header);
        $this->load->view('administrador/usuario/agregar', $data_body);
        $this->load->view('administrador/templates/footer');
    }

    public function eliminar($id_admin_usuario) {
        $this->modelo->delAdmin_usuario(array('id_admin_usuario' => $id_admin_usuario));
        redirect('/administrador/usuario/lista');
    }

    public function contrasena($alert = NULL) {

        $data_body['alert'] = $alert;

        $post = $this->input->post();
        if ($post) {
            if ($post['ncontrasena'] == $post['n2contrasena']) {
                $usuario = $this->modelo->getAdmin_usuario(
                        array(
                            'id_admin_usuario' => $this->session->userdata('id_admin_usuario'),
                            'password_admin_usuario' => md5($post['contrasena'])
                        )
                );
                $usuario = $usuario[0];
                if (isset($usuario['id_admin_usuario']) && isset($usuario['mail_admin_usuario']) && isset($usuario['password_admin_usuario'])) {
                    $post['id_admin_usuario'] = $this->session->userdata('id_admin_usuario');
                    $post['password_admin_usuario'] = md5($post['ncontrasena']);
                    $id_admin_usuario = $this->modelo->addadmin_usuario($post);
                    redirect('/administrador/usuario/contrasena/ok');
                }
                redirect('/administrador/usuario/contrasena/contrasena');
            }
            redirect('/administrador/usuario/contrasena/ncontrasena');
        }

        if ($alert === 'ok')
            $this->session->sess_destroy();

        $data_header['menu_activo'] = 'usuario';
        $data_body['operacion'] = 'Cambiar contrase&ntilde;a';

        $data_header['breadcrumb'] = array(
            array(
                'link' => '#',
                'nombre' => $data_body['operacion']
            ),
        );

        $this->load->view('administrador/templates/header', $data_header);
        $this->load->view('administrador/usuario/contrasena', $data_body);
        $this->load->view('administrador/templates/footer');
    }

}
