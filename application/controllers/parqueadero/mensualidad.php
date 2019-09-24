<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require $_SERVER['DOCUMENT_ROOT'] . '/pos/autoload.php';

use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\EscposImage;
class Mensualidad extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->acceso->validar();
    }

    public function lista($alert = NULL) {
        $data_header['menu_activo'] = 'parqueadero';

        $data_header['breadcrumb'] = array(
            array(
                'link' => '/parqueadero/mensualidad/lista',
                'nombre' => 'Parqueo'
            ),
        );
        $mensualidades['select'] = base64_encode(" 
            p.id_par_mensualidad,
            p.placa_par_mensualidad,
            p.inicio_par_mensualidad,
            p.fin_par_mensualidad,
            p.valor_par_mensualidad,
            p.nombre_par_mensualidad,
            p.modelo_par_mensualidad,
            p.color_par_mensualidad,
            p.telefono_par_mensualidad,
            tv.nombre_par_vehiculo_tipo,
            IF(p.estado_par_mensualidad =1,'Activa','Vencida') as estado,
            p.estado_par_mensualidad
            ");
        $mensualidades['from'] = base64_encode(" 
            FROM 
                par_mensualidad p
            INNER JOIN par_vehiculo_tipo tv ON tv.id_par_vehiculo_tipo = p.id_par_vehiculo_tipo 
               ");
        $mensualidades['where'] = base64_encode("");
        $mensualidades['group'] = base64_encode("");
        $mensualidades['order'] = base64_encode(""
                . "ORDER BY p.id_par_mensualidad DESC");
        $mensualidades['limit'] = base64_encode("");
        if ($alert == 'ok') {
            $data_body['alert'] = 'ok';
        }
        if ($alert == 'exist') {
            $data_body['alert'] = 'exist';
        }
        if ($alert == 'noPagar') {
            $data_body['alert'] = 'noPagar';
        }
        $activo_turno = $this->modelo->query('SELECT id_admin_usuario FROM par_turno WHERE fin_par_turno IS NULL');
        $activo_turno = $activo_turno[0];
        $iniciar_turno = false;
        if (is_null($activo_turno)) {
            $iniciar_turno = true;
            $registrar = false;
        } else {
            if ($activo_turno['id_admin_usuario'] == $this->session->userdata['id_admin_usuario']) {
                $registrar = true;
            } else {
                $registrar = false;
            }
        }
        $data_body['iniciar_turno'] = $iniciar_turno;
        $data_body['registrar'] = $registrar;
        $this->session->set_userdata(array("mensualidad" => $mensualidades));

        $this->load->view('administrador/templates/header', $data_header);
        $this->load->view('parqueadero/mensualidad/lista', $data_body);
        $this->load->view('administrador/templates/footer');
    }

    public function agregar($id_par_mensualidad = null) {

        $post = $this->input->post();
        if ($post) {
            if (is_null($id_par_mensualidad)) {
                $placa = $post['placa_par_mensualidad'];
                $exist = $this->modelo->getpar_mensualidad(array('placa_par_mensualidad' => $placa));

                if (!empty($exist)) {
                    redirect('/parqueadero/mensualidad/lista/exist');
                }
                $placa = $post['placa_par_mensualidad'];
                if (strlen($placa) == 5) {
                    $tipo = 1;
                } elseif (is_numeric(substr($placa, -1, 1))) {
                    $tipo = 2;
                } elseif (!is_numeric(substr($placa, -1, 1))) {
                    $tipo = 1;
                }
                $post['placa_par_mensualidad'] = strtoupper($post['placa_par_mensualidad']);
                $post['id_par_vehiculo_tipo'] = $tipo;
                $nuevafecha = strtotime($post['inicio_par_mensualidad'] . '23:59:59 +30 days');
                $nuevafecha = date('Y-m-d H:i:s', $nuevafecha);
                $post['fin_par_mensualidad'] = $nuevafecha;
                $id_par_mensualidad = $this->modelo->addpar_mensualidad($post);
                $activo_turno = $this->modelo->query('SELECT id_par_turno,id_admin_usuario FROM par_turno WHERE fin_par_turno IS NULL');
                $activo_turno = $activo_turno[0];
                $insert = array(
                    'valor_par_historial_mensualidad' => $post['valor_par_mensualidad'],
                    'id_par_mensualidad' => $id_par_mensualidad,
                    'id_par_turno' => $activo_turno['id_par_turno']
                );
                $historial_mensualidad = $this->modelo->addpar_historial_mensualidad($insert);
                $this->imprimir($id_par_mensualidad);
                redirect('/parqueadero/mensualidad/lista/ok');
            } else {

                $nuevafecha = strtotime($post['inicio_par_mensualidad'] . '23:59:59 +30 days');
                $nuevafecha = date('Y-m-d H:i:s', $nuevafecha);
                $post['fin_par_mensualidad'] = $nuevafecha;
                $post['id_par_mensualidad'] = $id_par_mensualidad;
                $id_par_mensualidad = $this->modelo->addpar_mensualidad($post);
                redirect('/parqueadero/mensualidad/lista/ok');
            }
        }

        $data_header['menu_activo'] = 'parqueadero';


        $data_body['operacion'] = 'Agregar';

        $data_header['breadcrumb'] = array(
            array(
                'link' => '/parqueadero/mensualidad/lista',
                'nombre' => 'Mensualidad'
            ),
            array(
                'link' => '#',
                'nombre' => $data_body['operacion']
            ),
        );
        if (!is_null($id_par_mensualidad)) {
            $data_body['mensualidad'] = $this->modelo->getpar_mensualidad(array('id_par_mensualidad' => $id_par_mensualidad));
            $data_body['mensualidad'] = $data_body['mensualidad'][0];
        }


        $this->load->view('administrador/templates/header', $data_header);
        $this->load->view('parqueadero/mensualidad/agregar', $data_body);
        $this->load->view('administrador/templates/footer');
    }

    public function eliminar($id_par_mensualidad = null) {
        $this->modelo->delpar_mensualidad(array('id_par_mensualidad' => $id_par_mensualidad));
        redirect('/parqueadero/mensualidad/lista?ok');
    }

    public function pagar($id_par_mensualidad = null) {
        $post = $this->input->post();
        if ($post) {
            $post['id_par_mensualidad'] = $id_par_mensualidad;
            $post['estado_par_mensualidad'] = 1;
            $nuevafecha = strtotime($post['inicio_par_mensualidad'] . '23:59:59 +30 days');
            $nuevafecha = date('Y-m-d H:i:s', $nuevafecha);
            $post['fin_par_mensualidad'] = $nuevafecha;
            $id_par_mensualidad = $this->modelo->addpar_mensualidad($post);
            $activo_turno = $this->modelo->query('SELECT id_admin_usuario,id_par_turno FROM par_turno WHERE fin_par_turno IS NULL');
            $activo_turno = $activo_turno[0];
            $insert = array(
                'valor_par_historial_mensualidad' => $post['valor_par_mensualidad'],
                'id_par_mensualidad' => $id_par_mensualidad,
                'id_par_turno' => $activo_turno['id_par_turno']
            );
            $historial_mensualidad = $this->modelo->addpar_historial_mensualidad($insert);
            $this->imprimir($id_par_mensualidad);
            redirect('/parqueadero/mensualidad/lista/ok');
        }

        $data_header['menu_activo'] = 'parqueadero';


        $data_body['operacion'] = 'Pagar';

        $data_header['breadcrumb'] = array(
            array(
                'link' => '/parqueadero/mensualidad/lista',
                'nombre' => 'Mensualidad'
            ),
            array(
                'link' => '#',
                'nombre' => $data_body['operacion']
            ),
        );
        if (!is_null($id_par_mensualidad)) {
            $data_body['mensualidad'] = $this->modelo->getpar_mensualidad(array('id_par_mensualidad' => $id_par_mensualidad));
            $data_body['mensualidad'] = $data_body['mensualidad'][0];
//            if ($data_body['mensualidad']['estado_par_mensualidad'] === '1') {
//                redirect('/parqueadero/mensualidad/lista/noPagar');
//            }
        }


        $this->load->view('administrador/templates/header', $data_header);
        $this->load->view('parqueadero/mensualidad/pagar', $data_body);
        $this->load->view('administrador/templates/footer');
    }
    
    public function imprimir($id_par_mensualidad = null) {
        $mensualidad = $this->modelo->getpar_mensualidad(array('id_par_mensualidad'=>$id_par_mensualidad));
        $mensualidad= $mensualidad[0];
        $historico_mensualidad = $this->modelo->query("SELECT * FROM par_historial_mensualidad WHERE id_par_mensualidad = $id_par_mensualidad ORDER BY pago_par_historial_mensualidad DESC LIMIT 1");
        $historico_mensualidad = $historico_mensualidad[0];
        $turno = $this->modelo->getpar_turno(array('id_par_turno'=>$historico_mensualidad['id_par_turno']));
        $turno = $turno[0];
        $name_valet = $this->modelo->getadmin_usuario(array('id_admin_usuario' => $turno['id_admin_usuario']));
        $name_valet = $name_valet[0]['nombres_admin_usuario'] . ' ' . $name_valet[0]['apellidos_admin_usuario'];
        $name_vehicle = $this->modelo->getpar_vehiculo_tipo(array('id_par_vehiculo_tipo' => $mensualidad['id_par_vehiculo_tipo']));
        $name_vehicle = $name_vehicle[0]['nombre_par_vehiculo_tipo'];
        $fecha_pago = date('d-m-y H:i',  strtotime($historico_mensualidad['pago_par_historial_mensualidad']));
        $fecha_inicio = date('d-m-y',  strtotime($mensualidad['inicio_par_mensualidad']));
        $fecha_fin = date('d-m-y',  strtotime($mensualidad['fin_par_mensualidad']));
        $valor = $historico_mensualidad['valor_par_historial_mensualidad'];
        $placa = $mensualidad['placa_par_mensualidad'];
        $recibo = $historico_mensualidad['id_par_historial_mensualidad'];
        $connector = new WindowsPrintConnector("Pruebas");
        $printer = new Printer($connector);
        $parametros = $this->modelo->getpar_parametros(array('id_par_parametros' => 1));
        $parametros = $parametros[0];
        /* Print a "Hello world" receipt" */


        try {
            $tux = EscposImage::load($_SERVER['DOCUMENT_ROOT'] . "/static/img/impresora.png", false);
            $fonts = array(
                Printer::FONT_A,
                Printer::FONT_B,
                Printer::FONT_C);
            $printer->setFont($fonts[0]);
            $printer->setEmphasis(true);
            $printer->setTextSize(2,2);
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("Recibo de Mensualidad\n");
            $printer->setEmphasis(false);
            $printer->setTextSize(1,1);
            $printer->setJustification();
            $printer->bitImage($tux);
            $printer->text("________________________________________________\n");
            $printer->text("Recibo: ");
            $printer->setEmphasis(true);
            $printer->text($recibo);
            $printer->setEmphasis(false);
            for ($i = 0; $i < (18 - strlen($recibo)); $i++) {
                $printer->text(" ");
            }
            $printer->text("Placa: ");
            $printer->setEmphasis(true);
            $printer->text("$placa\n");
            $printer->setEmphasis(false);
            $printer->text("Inicio:");
            $printer->setEmphasis(true);
            $printer->text($fecha_inicio);
            for ($i = 0; $i < (18 - strlen($fecha_inicio)); $i++) {
                $printer->text(" ");
            }
            $printer->setEmphasis(false);
            $printer->text("Vehiculo:");
            $printer->setEmphasis(true);
            $printer->text(" $name_vehicle\n");
            $printer->setEmphasis(false);
            $printer->text("Fin: ");
            $printer->setEmphasis(true);
            $printer->text($fecha_fin);
            $printer->setEmphasis(false);
            for ($i = 0; $i < (15 - strlen($fecha_fin)); $i++) {
                $printer->text(" ");
            }
            $printer->text("Fecha Pago:");
            $printer->setEmphasis(true);
            $printer->text(" $fecha_pago\n");
            $printer->setEmphasis(true);
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("                          TOTAL\n");
            $printer->text("________________________________________________\n");
            $printer->setJustification();
            $printer->text("Mensusalidad $name_vehicle");
            for ($i = 0; $i < 16; $i++) {
                $printer->text(" ");
            }
            $printer->text("$".$valor."\n");
            $printer->text("________________________________________________\n");
            $printer->text("Recibio Pago: $name_valet\n");
            $printer->setUnderline(true);
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("\nHorario de Atencion :\n");
            $printer->setFont($fonts[1]);
            $printer->setEmphasis(false);
            $printer->setUnderline(false);
            $printer->text($parametros['horario_atencion_par_parametros'] . "\n");
            $printer->setEmphasis(true);
            $printer->text("Importante:");
            $printer->setEmphasis(false);
            $printer->text($parametros['nota_par_parametros'] . "\n");
            $printer->feed();
        } catch (Exception $e) {
            /* Images not supported on your PHP, or image file not found */
            $printer->text($e->getMessage() . "\n");
        }
        $printer->cut();
        $printer->close();
    }

}
