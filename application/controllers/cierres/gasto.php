<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require $_SERVER['DOCUMENT_ROOT'] . '/pos/autoload.php';

use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\EscposImage;

class Gasto extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->acceso->validar();
    }

    public function lista($alert = NULL) {
        $data_header['menu_activo'] = 'cierres';

        $data_header['breadcrumb'] = array(
            array(
                'link' => '/cierres/gasto/lista',
                'nombre' => 'Gastos'
            ),
        );
        $activo_turno = $this->modelo->query('SELECT id_admin_usuario,id_par_turno FROM par_turno WHERE fin_par_turno IS NULL');
        $activo_turno = $activo_turno[0];
        if (is_null($activo_turno)) {
            $data_body['registrar'] = false;
            $data_body['eliminar'] = false;
        } else {
            $id_turno = $activo_turno['id_par_turno'];
            $gastos['select'] = base64_encode(" 
            p.id_par_gasto,
            p.fecha_par_gasto,
            p.valor_par_gasto,
            p.detalle_par_gasto
            ");
                $gastos['from'] = base64_encode(" 
            FROM 
                par_gasto p
               ");
                $gastos['where'] = base64_encode("
                    WHERE 
                    p.id_par_turno = $id_turno ");
                $gastos['group'] = base64_encode("");
                $gastos['order'] = base64_encode("");
                $gastos['limit'] = base64_encode("");
                $this->session->set_userdata(array("gastos" => $gastos));
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
        $this->load->view('cierres/gasto/lista', $data_body);
        $this->load->view('administrador/templates/footer');
    }

    public function agregar() {
        $post = $this->input->post();
        if ($post) {
            $activo_turno = $this->modelo->query('SELECT id_admin_usuario,id_par_turno FROM par_turno WHERE fin_par_turno IS NULL');
            $activo_turno = $activo_turno[0];
            if(is_null($activo_turno)){
                redirect('/cierres/gasto/lista/error');
            }
            $post['id_par_turno'] = $activo_turno['id_par_turno'];
            $id_par_gasto = $this->modelo->addpar_gasto($post);
            $this->imprimir($id_par_gasto);
            redirect('/cierres/gasto/lista/ok');
        }

        $data_header['menu_activo'] = 'cierres';


        $data_body['operacion'] = 'Agregar';

        $data_header['breadcrumb'] = array(
            array(
                'link' => '/cierres/gasto/lista',
                'nombre' => 'Gasto'
            ),
            array(
                'link' => '#',
                'nombre' => $data_body['operacion']
            ),
        );


        $this->load->view('administrador/templates/header', $data_header);
        $this->load->view('cierres/gasto/agregar', $data_body);
        $this->load->view('administrador/templates/footer');
    }

    public function eliminar($id_par_gasto) {
        $this->modelo->delpar_gasto(array('id_par_gasto' => $id_par_gasto));
        redirect('/cierres/gasto/lista/ok');
    }

    public function imprimir($id_par_gasto = null) {
        $gasto = $this->modelo->getpar_gasto(array('id_par_gasto' => $id_par_gasto));
        $gasto = $gasto[0];
        $turno = $this->modelo->getpar_turno(array('id_par_turno' => $gasto['id_par_turno']));
        $turno = $turno[0];
        $name_valet = $this->modelo->getadmin_usuario(array('id_admin_usuario' => $turno['id_admin_usuario']));
        $name_valet = $name_valet[0]['nombres_admin_usuario'] . ' ' . $name_valet[0]['apellidos_admin_usuario'];
        $connector = new WindowsPrintConnector("Pruebas");
        $printer = new Printer($connector);

        /* Print a "Hello world" receipt" */


        try {
            $tux = EscposImage::load($_SERVER['DOCUMENT_ROOT'] . "/static/img/impresora.png", false);
            $fonts = array(
                Printer::FONT_A,
                Printer::FONT_B,
                Printer::FONT_C);
            $printer->setFont($fonts[0]);
            $printer->setEmphasis(true);
            $printer->setTextSize(2, 2);
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("Recibo de gasto\n");
            $printer->setEmphasis(false);
            $printer->setTextSize(1, 1);
            $printer->setJustification();
            $printer->bitImage($tux);
            $printer->text("________________________________________________\n");
            $printer->text("Recibo: ");
            $printer->setEmphasis(true);
            $printer->text($gasto['id_par_gasto']);
            $printer->setEmphasis(false);
            for ($i = 0; $i < (18 - strlen($gasto['id_par_gasto'])); $i++) {
                $printer->text(" ");
            }
            $printer->text("Valor: ");
            $printer->setEmphasis(true);
            $printer->text("$" . number_format($gasto['valor_par_gasto']) . "\n");
            $printer->setEmphasis(false);
            $printer->text("Fecha:");
            $printer->setEmphasis(true);
            $printer->text($gasto['fecha_par_gasto']."\n");
           
            $printer->setEmphasis(false);
            $printer->text("Responsable:");
            $printer->setEmphasis(true);
            $printer->text(" $name_valet\n");

            $printer->setEmphasis(true);
            $printer->setUnderline(true);
            $printer->text("________________________________________________\n");
            $printer->text("Detalle :\n");
            $printer->setUnderline(false);
            $printer->text($gasto['detalle_par_gasto'] . "\n");
            $printer->feed();
        } catch (Exception $e) {
            /* Images not supported on your PHP, or image file not found */
            $printer->text($e->getMessage() . "\n");
        }
        $printer->cut();
        $printer->close();
    }

}
