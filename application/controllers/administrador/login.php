<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Login extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function validar($error = null) {


        $data['error'] = $error;

        $post = $this->input->post();
        if ($post) {
            $admin_usuario = $this->modelo->getAdmin_usuario(
                    array(
                        'id_admin_estado_usuario' => 1,
                        'mail_admin_usuario' => $post['mail'],
                        'password_admin_usuario' => md5($post['password'])
                    )
            );
            $admin_usuario = $admin_usuario[0];
            if (isset($admin_usuario['id_admin_usuario']) && isset($admin_usuario['mail_admin_usuario']) && $admin_usuario['id_admin_usuario'] != NULL && $admin_usuario['mail_admin_usuario'] == $post['mail']) {
                unset($admin_usuario['password_admin_usuario']);
                $this->session->set_userdata($admin_usuario);
                if (is_null($admin_usuario['id_admin_rol']) || $admin_usuario['id_admin_rol'] == 1) {
                    redirect('/administrador/usuario/lista');
                }
                redirect('/parqueadero/parqueo/lista');
            } else {
                $data['error'] = 1;
            }
        } else {
            $this->session->sess_destroy();
        }

        $this->load->view('administrador/login/login', $data);
    }

    public function recuperar($error = null) {

        $post = $this->input->post();
        if ($post) {
            $usuario = $this->modelo->getAdmin_usuario(array('mail_admin_usuario' => $post['mail']));
            if (isset($usuario[0]['id_admin_usuario'])) {
                $password = rand(1111111, 99999999);
                $this->modelo->addAdmin_usuario(
                        array(
                            'id_admin_usuario' => $usuario[0]['id_admin_usuario'],
                            'password_admin_usuario' => md5($password)
                ));

                $this->load->library('email');
                $this->email->set_mailtype("html");
                $this->email->to($post['mail']);
                $this->email->subject($_SERVER['HTTP_HOST'] . ', recuperar contraseña. ' . $name);
                $mensaje .= $this->load->view('eMail/mailHeader', array(), TRUE);
                $mensaje .= $this->load->view('eMail/mailRecuperarContrasena', array('usuario' => $usuario[0], 'clave' => $password), TRUE);
                $mensaje .= $this->load->view('eMail/mailFooter', array(), TRUE);
                $this->email->message($mensaje);
                $this->email->send();


                redirect('/administrador/login/validar/3');
            } else {
                $data['error'] = 2;
            }
        }

        $this->load->view('administrador/login/recuperar', $data);
    }

}
