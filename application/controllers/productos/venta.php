<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require $_SERVER['DOCUMENT_ROOT'] . '/pos/autoload.php';

use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\EscposImage;

class Venta extends CI_Controller {

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
        $activo_turno = $this->modelo->query('SELECT id_admin_usuario,id_par_turno FROM par_turno WHERE fin_par_turno IS NULL');
        $activo_turno = $activo_turno[0];
        if (is_null($activo_turno)) {
            $registrar = false;
        } else {
            if ($activo_turno['id_admin_usuario'] == $this->session->userdata['id_admin_usuario']) {
                $registrar = true;
            } else {
                $registrar = false;
            }
        }
        $post = $this->input->post();
        if ($post) {
            $producto = $this->modelo->getpar_producto(array('id_par_producto' => $post['id_par_producto']));
            $producto = $producto[0];
            if (is_null($producto)) {
                redirect('/productos/venta/lista/error');
            }
            $post['valor_par_producto'] = $producto['valor_par_producto']*$post['cantidad_par_producto'];
            if (!is_null($post['descuento'])) {
                $descuento = $post['descuento'];
                $descuento = $post['valor_par_producto'] * ($descuento/100);
                $post['valor_par_producto'] = $post['valor_par_producto'] - $descuento;
            }
            $insert = array(
                'valor_par_venta_producto' => $post['valor_par_producto'],
                'descuento_par_venta_producto' => $descuento,
                'id_par_producto' => $producto['id_par_producto'],
                'id_par_turno' => $activo_turno['id_par_turno'],
                'cantidad_par_venta_producto' => $post['cantidad_par_producto']
            );
            $venta = $this->modelo->addpar_venta_producto($insert);
            if ($venta) {
                $producto['cantidad_par_producto'] = $producto['cantidad_par_producto'] -$post['cantidad_par_producto']; 
                $producto = $this->modelo->addpar_producto($producto);
                redirect('/productos/venta/lista/ok');
            }
            redirect('/productos/venta/lista/error');
        }

        $productos['select'] = base64_encode(" 
            p.id_par_producto,
            p.nombre_par_producto,
            p.valor_par_producto
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


        $lista_productos = $this->modelo->getpar_producto();
        $data_body['lista_productos'] = $lista_productos;
        $data_body['registrar'] = $registrar;
        $this->session->set_userdata(array("productos" => $productos));
        $this->load->view('administrador/templates/header', $data_header);
        $this->load->view('productos/venta/lista', $data_body);
        $this->load->view('administrador/templates/footer');
    }

   

    public function imprimir() {
        $connector = new WindowsPrintConnector("Pruebas");
        $printer = new Printer($connector);

        /* Print a "Hello world" receipt" */


        $tux = EscposImage::load($_SERVER['DOCUMENT_ROOT'] . "/static/img/logo.png", false);
        //$printer->graphics($tux);

        $printer->bitImageColumnFormat($tux);
        $printer->text("Regular Tux (bit image, column format).\n");
        $printer->inlineImage($tux);
        $printer->text("────────────────────────────────────────────────");
        $printer->setTextSize(2, 2);
        $printer->text("Chupame la monda!!\n");
        $printer->cut();

        /* Close printer */
        $printer->close();
    }

}
