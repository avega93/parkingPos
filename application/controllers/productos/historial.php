<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require $_SERVER['DOCUMENT_ROOT'] . '/pos/autoload.php';

use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\EscposImage;

class Historial extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->acceso->validar();
    }

    public function lista($alert = NULL) {
        $data_header['menu_activo'] = 'productos';

        $data_header['breadcrumb'] = array(
            array(
                'link' => '/productos/historial/lista',
                'nombre' => 'Historial Productos'
            ),
        );
        $activo_turno = $this->modelo->query('SELECT id_admin_usuario,id_par_turno FROM par_turno WHERE fin_par_turno IS NULL');
        $activo_turno = $activo_turno[0];
        if (is_null($activo_turno)) {
            $data_body['registrar'] = false;
            $data_body['eliminar'] = false;
        } else {
            $id_turno = $activo_turno['id_par_turno'];
            $productos['select'] = base64_encode(" 
            p.id_par_venta_producto,
            p.fecha_par_venta_producto,
            p.valor_par_venta_producto,
            p.cantidad_par_venta_producto,
            pr.nombre_par_producto
            ");
            $productos['from'] = base64_encode(" 
            FROM 
                par_venta_producto p
                INNER JOIN par_producto pr ON p.id_par_producto = pr.id_par_producto
               ");
            $productos['where'] = base64_encode("
                    WHERE 
                    p.id_par_turno = $id_turno ");
            $productos['group'] = base64_encode("");
            $productos['order'] = base64_encode("");
            $productos['limit'] = base64_encode("");
            $this->session->set_userdata(array("productos" => $productos));
            if ($activo_turno['id_admin_usuario'] == $this->session->userdata['id_admin_usuario']) {
                $data_body['registrar'] = true;
                $data_body['eliminar'] = true;
            } else {
                $data_body['registrar'] = false;
                $data_body['eliminar'] = true;
            }
        }


        if ($alert == 'ok') {
            $data_body['alert'] = 'ok';
        }
        if ($alert == 'error') {
            $data_body['alert'] = 'error';
        }

        $this->load->view('administrador/templates/header', $data_header);
        $this->load->view('productos/historial/lista', $data_body);
        $this->load->view('administrador/templates/footer');
    }

    public function eliminar($id_venta) {
        $this->modelo->delpar_venta_producto(array('id_par_venta_producto' => $id_venta));
        redirect('/productos/historial/lista/ok');
    }

}
