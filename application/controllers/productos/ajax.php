<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Ajax extends CI_Controller {

    public function aumentarProducto() {
//        $data = json_encode($_POST);
        $post = $this->input->post();
        if (is_null($post['id_par_producto'])) {
            echo 'Esta intentando hacer algo no permitido';
            exit;
        }
        $producto = $this->modelo->addpar_producto($post);
        if ($producto) {
            echo 'Producto aumentado correctamente';
        } else {
            echo 'Hubo un error, intente de nuevo';
        }
    }

}
