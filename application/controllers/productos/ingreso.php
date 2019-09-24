<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Ingreso extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->acceso->validar();
    }

    public function lista($alert = NULL) {
        $data_header['menu_activo'] = 'productos';

        $data_header['breadcrumb'] = array(
            array(
                'link' => '/productos/venta/lista',
                'nombre' => 'Productos'
            ),
        );
        $productos['select'] = base64_encode(" 
            p.id_par_producto,
            p.nombre_par_producto,
            p.valor_par_producto,
            p.cantidad_par_producto
            ");
        $productos['from'] = base64_encode(" 
            FROM 
                par_producto p
               ");
        $productos['where'] = base64_encode("");
        $productos['group'] = base64_encode("");
        $productos['order'] = base64_encode("");
        $productos['limit'] = base64_encode("");
        if ($alert == 'ok') {
            $data_body['alert'] = 'ok';
        }
        if ($alert == 'error') {
            $data_body['alert'] = 'error';
        }
        $this->session->set_userdata(array("productos" => $productos));
        $this->load->view('administrador/templates/header', $data_header);
        $this->load->view('productos/ingreso/lista', $data_body);
        $this->load->view('administrador/templates/footer');
    }

    public function agregar($id_par_producto = null) {

        $post = $this->input->post();
        if ($post) {
            $post['id_par_producto'] = $id_par_producto;
            $producto = $this->modelo->addpar_producto($post);
            if ($producto) {
                redirect('productos/ingreso/lista/ok');
            }
            redirect('productos/ingreso/lista/error');
        }

        $data_header['menu_activo'] = 'productos';


        $data_body['operacion'] = 'Agregar';

        $data_header['breadcrumb'] = array(
            array(
                'link' => '/productos/ingreso/lista',
                'nombre' => 'Ingreso Productos'
            ),
            array(
                'link' => '#',
                'nombre' => $data_body['operacion']
            ),
        );
        if (!is_null($id_par_producto)) {
            $data_body['producto'] = $this->modelo->getpar_producto(array('id_par_producto' => $id_par_producto));
            $data_body['producto'] = $data_body['producto'][0];
        }


        $this->load->view('administrador/templates/header', $data_header);
        $this->load->view('productos/ingreso/agregar', $data_body);
        $this->load->view('administrador/templates/footer');
    }

    public function eliminar($id_par_producto = null) {
        $this->modelo->delpar_producto(array('id_par_producto' => $id_par_producto));
        redirect('/productos/ingreso/lista/ok');
    }

}
