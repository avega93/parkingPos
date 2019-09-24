<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require $_SERVER['DOCUMENT_ROOT'] . '/pos/autoload.php';

use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\EscposImage;

class Parqueo extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->acceso->validar();
    }

    public function lista($alert = NULL) {
        $data_header['menu_activo'] = 'parqueadero';

        $data_header['breadcrumb'] = array(
            array(
                'link' => '/parqueadero/parqueo/lista',
                'nombre' => 'Parqueo'
            ),
        );
        $post = $this->input->post();
        if ($post) {
            $placa = $this->modelo->getpar_activos(
                    array(
                        'placa_par_activos' => $post['placa_par_activos']
                    )
            );
            $mensualidad = $this->modelo->getpar_mensualidad(array('placa_par_mensualidad' => $post['placa_par_activos']));
            $mensualidad = $mensualidad[0];
            if (!empty($mensualidad)) {
                if ($mensualidad['estado_par_mensualidad'] == 0) {
                    redirect('/parqueadero/parqueo/lista/mensualidadVencida');
                }
                redirect('/parqueadero/parqueo/lista/mensualidad');
            }
            if (!count($placa) > 0) {
                $placa = $post['placa_par_activos'];
                if (strlen($placa) == 5) {
                    $tipo = 1;
                } elseif (is_numeric(substr($placa, -1, 1))) {
                    $tipo = 2;
                } elseif (!is_numeric(substr($placa, -1, 1))) {
                    $tipo = 1;
                }
                $post['placa_par_activos'] = strtoupper($post['placa_par_activos']);
                $post['id_par_vehiculo_tipo'] = $tipo;
                $post['id_admin_usuario'] = $this->session->userdata['id_admin_usuario'];
                $id_par_activos = $this->modelo->addpar_activos($post);
                $post['id_par_activos'] = $id_par_activos;
                $parqueo = $post;
                $parqueo = $this->modelo->getpar_activos(array('id_par_activos' => $id_par_activos));
                $parqueo = $parqueo[0];
                $this->imprimir($parqueo);
                redirect('/parqueadero/parqueo/lista/ok');
            } else {
                redirect('/parqueadero/parqueo/lista/error');
            }
        }

        $usuarios['select'] = base64_encode(" 
            p.id_par_activos,
            p.placa_par_activos,
            p.entrada_par_activos,
            tv.nombre_par_vehiculo_tipo
            ");
        $usuarios['from'] = base64_encode(" 
            FROM 
                par_activos p
            INNER JOIN par_vehiculo_tipo tv ON tv.id_par_vehiculo_tipo = p.id_par_vehiculo_tipo 
               ");
        $usuarios['where'] = base64_encode("");
        $usuarios['group'] = base64_encode("");
        $usuarios['order'] = base64_encode(""
                . "ORDER BY p.id_par_activos DESC");
        $usuarios['limit'] = base64_encode("");
        if ($alert == 'ok') {
            $data_body['alert'] = 'ok';
        }
        if ($alert == 'error') {
            $data_body['alert'] = 'error';
        }
        if ($alert == 'mensualidad') {
            $data_body['alert'] = 'mensualidad';
        }
        if ($alert == 'mensualidadVencida') {
            $data_body['alert'] = 'mensualidadVencida';
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
        $this->session->set_userdata(array("activos" => $usuarios));

        $this->load->view('administrador/templates/header', $data_header);
        $this->load->view('parqueadero/parqueo/lista', $data_body);
        $this->load->view('administrador/templates/footer');
    }

    public function pagar($id_par_activos = NULL) {
        $activo_turno = $this->modelo->query('SELECT id_admin_usuario FROM par_turno WHERE fin_par_turno IS NULL');
        $activo_turno = $activo_turno[0];
        if ($activo_turno['id_admin_usuario'] != $this->session->userdata['id_admin_usuario']) {
            redirect('/administrador/login/validar/2');
        }
        $parqueo = $this->modelo->getpar_activos(array('id_par_activos' => $id_par_activos));
        $parqueo = $parqueo[0];
        if (is_null($parqueo)) {
            redirect('/administrador/usuario/lista/error');
        }
        $start_date = new DateTime(date('Y-m-d H:i:s'));
        $since_start = $start_date->diff(new DateTime($parqueo['entrada_par_activos']));
        $minutes = $since_start->days * 24 * 60;
        $minutes += $since_start->h * 60;
        $minutes += $since_start->i;
        $tiempo = ($minutes - 4) / 30;
        $tiempo = ceil($tiempo);
        $rHora = $this->modelo->getpar_res_temporal(array('tiempo_par_res_temporal' => 1));
        $rFraccion = $this->modelo->getpar_res_temporal(array('tiempo_par_res_temporal' => 0.5));
        $vHora = $this->modelo->getpar_tarifa(array('id_par_res_temporal' => $rHora[0]['id_par_res_temporal'], 'id_par_vehiculo_tipo' => $parqueo['id_par_vehiculo_tipo']));
        $vFraccion = $this->modelo->getpar_tarifa(array('id_par_res_temporal' => $rFraccion[0]['id_par_res_temporal'], 'id_par_vehiculo_tipo' => $parqueo['id_par_vehiculo_tipo']));
        $vHora = $vHora[0]['precio_par_tarifa'];
        $vFraccion = $vFraccion[0]['precio_par_tarifa'];
        if (is_null($vHora)) {
            redirect('/parqueadero/parqueo/lista/error');
        }
        if (is_null($vFraccion)) {
            $vFraccion = $vHora / 2;
        }
        if (($tiempo % 2) != 0) {
            $tiempo = $tiempo / 2;
            $tiempo = floor($tiempo);
            if ($tiempo > 12) {
                $tiempo_cobrar = $tiempo - 4;
            } else {
                $tiempo_cobrar = $tiempo;
            }
            $cobro = ($tiempo_cobrar * $vHora) + $vFraccion;
            $tiempo_real = $tiempo + 0.5;
        } else {
            $tiempo = $tiempo / 2;
            $tiempo = floor($tiempo);
            if ($tiempo > 12) {
                $tiempo_cobrar = $tiempo - 4;
            } else {
                $tiempo_cobrar = $tiempo;
            }
            $cobro = ($tiempo_cobrar * $vHora);
            $tiempo_real = $tiempo;
        }
        $activo_turno = $this->modelo->query('SELECT id_admin_usuario,id_par_turno FROM par_turno WHERE fin_par_turno IS NULL');
        $activo_turno = $activo_turno[0];
        $insert = array(
            'placa_par_historial' => $parqueo['placa_par_activos'],
            'entrada_par_historial' => $parqueo['entrada_par_activos'],
            'cobro_par_historial' => $cobro,
            'tarifa_par_historial' => $vHora,
            'id_par_vehiculo_tipo' => $parqueo['id_par_vehiculo_tipo'],
            'id_par_turno' => $activo_turno['id_par_turno'],
        );
        $historico = $this->modelo->addpar_historial($insert);
        if ($historico) {
            $this->modelo->delpar_activos($parqueo);
            $this->imprimirPago($historico, $tiempo_real);
            redirect('/parqueadero/parqueo/lista/ok');
        }
        redirect('/parqueadero/parqueo/lista/error');
    }

    public function eliminar($id_par_activos = NULL) {
        $parqueo = $this->modelo->getpar_activos(array('id_par_activos' => $id_par_activos));
        $parqueo = $parqueo[0];
        $activo_turno = $this->modelo->query('SELECT id_admin_usuario,id_par_turno FROM par_turno WHERE fin_par_turno IS NULL');
        $activo_turno = $activo_turno[0];
        $insert = array(
            'placa_par_historial' => $parqueo['placa_par_activos'],
            'entrada_par_historial' => $parqueo['entrada_par_activos'],
            'id_par_vehiculo_tipo' => $parqueo['id_par_vehiculo_tipo'],
            'id_par_turno' => $activo_turno['id_par_turno'],
            'eliminado_par_historial' => 1,
        );
        $historico = $this->modelo->addpar_historial($insert);
        $this->modelo->delpar_activos(array('id_par_activos' => $id_par_activos));
        redirect('/parqueadero/parqueo/lista/ok');
    }

    public function iniciarTurno() {
        $this->modelo->addpar_turno(array('id_admin_usuario' => $this->session->userdata['id_admin_usuario']));
        redirect('/parqueadero/parqueo/lista/ok');
    }

    public function finTurno() {
        $turno = $this->modelo->query('SELECT id_par_turno FROM par_turno WHERE id_admin_usuario = ' . $this->session->userdata['id_admin_usuario'] . ' AND fin_par_turno IS NULL');
        $turno = $turno[0];
        $turno['fin_par_turno'] = date('Y-m-d H:i:s');
        $this->modelo->addpar_turno($turno);
        redirect('/parqueadero/parqueo/lista/ok');
    }

    public function reImprimir($id_parqueo = null) {
        $parqueo = $this->modelo->getpar_activos(array('id_par_activos' => $id_parqueo));
        $parqueo = $parqueo[0];
        if (is_null($parqueo)) {
            redirect('/parqueadero/parqueo/lista/error');
        }
        $this->imprimir($parqueo);
        redirect('/parqueadero/parqueo/lista/ok');
    }

    public function imprimir($parqueo = null) {
        $entrada = date('d-m-y H:i');
        $name_valet = $this->modelo->getadmin_usuario(array('id_admin_usuario' => $parqueo['id_admin_usuario']));
        $name_valet = $name_valet[0]['nombres_admin_usuario'] . ' ' . $name_valet[0]['apellidos_admin_usuario'];
        $name_vehicle = $this->modelo->getpar_vehiculo_tipo(array('id_par_vehiculo_tipo' => $parqueo['id_par_vehiculo_tipo']));
        $name_vehicle = $name_vehicle[0]['nombre_par_vehiculo_tipo'];
        $recibo = $parqueo['id_par_activos'];
        $cascos = array($parqueo['casco1_par_activos'], $parqueo['casco2_par_activos']);
        $placa = $parqueo['placa_par_activos'];
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
            $printer->setTextSize(2, 2);
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("Recibo de Ingreso\n");
            $printer->setEmphasis(false);
            $printer->setTextSize(1, 1);
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
            $printer->text("Entrada:");
            $printer->setEmphasis(true);
            $printer->text($entrada);
            for ($i = 0; $i < (18 - strlen($entrada)); $i++) {
                $printer->text(" ");
            }
            $printer->setEmphasis(false);
            $printer->text("Vehiculo:");
            $printer->setEmphasis(true);
            $printer->text(" $name_vehicle\n");
            $printer->setEmphasis(false);
            $printer->text("\nCascos:");
            $printer->setEmphasis(true);
            foreach ($cascos as $key => $casco) {
                if ($cascos[$key] == 0) {
                    $printer->text('__');
                } else {
                    $printer->text($casco);
                }
                if (isset($cascos[$key + 1])) {
                    $printer->text(',');
                }
            }
            $printer->setEmphasis(false);
            $printer->text("     ");
            $printer->text("Valet:");
            $printer->setEmphasis(true);
            $printer->text(" $name_valet\n");
            $printer->setEmphasis(true);
            $printer->setUnderline(true);
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("\nHorario de Atencion :\n");
            $printer->setFont($fonts[1]);
            $printer->setEmphasis(false);
            $printer->setUnderline(false);
            $printer->text($parametros['horario_atencion_par_parametros'] . "\n");
            $printer->text($parametros['mensaje_guardar_par_parametros'] . "\n");
            $printer->setEmphasis(true);
            $printer->text("Importante:");
            $printer->setEmphasis(false);
            $printer->text($parametros['nota_par_parametros']);
            $printer->feed();
        } catch (Exception $e) {
            /* Images not supported on your PHP, or image file not found */
            $printer->text($e->getMessage() . "\n");
        }
        $printer->cut();
        $printer->close();
    }

    public function imprimirPago($id_parqueo = null, $tiempo = null) {
        $parqueo = $this->modelo->getpar_historial(array('id_par_historial' => $id_parqueo));
        $parqueo = $parqueo[0];
        $entrada = date("d-m-y H:i", strtotime($parqueo['entrada_par_historial']));
        $salida = date("d-m-y H:i", strtotime($parqueo['salida_par_historial']));
        $turno = $this->modelo->getpar_turno(array('id_par_turno' => $parqueo['id_par_turno']));
        $turno = $turno[0];
        $name_valet = $this->modelo->getadmin_usuario(array('id_admin_usuario' => $turno['id_admin_usuario']));
        $name_valet = $name_valet[0]['nombres_admin_usuario'] . ' ' . $name_valet[0]['apellidos_admin_usuario'];
        $name_vehicle = $this->modelo->getpar_vehiculo_tipo(array('id_par_vehiculo_tipo' => $parqueo['id_par_vehiculo_tipo']));
        $name_vehicle = $name_vehicle[0]['nombre_par_vehiculo_tipo'];
        $recibo = $parqueo['id_par_historial'];
        $placa = $parqueo['placa_par_historial'];
        $cobro = $parqueo['cobro_par_historial'];
        $tarifa = $parqueo['tarifa_par_historial'];
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
            $printer->setTextSize(2, 2);
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("Recibo de Cobro\n");
            $printer->setEmphasis(false);
            $printer->setTextSize(1, 1);
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
            $printer->text("Entrada:");
            $printer->setEmphasis(true);
            $printer->text($entrada);
            for ($i = 0; $i < (18 - strlen($entrada)); $i++) {
                $printer->text(" ");
            }
            $printer->setEmphasis(false);
            $printer->text("Vehiculo:");
            $printer->setEmphasis(true);
            $printer->text(" $name_vehicle\n");
            $printer->setEmphasis(false);
            $printer->text("Salida: ");
            $printer->setEmphasis(true);
            $printer->text($salida);
            $printer->setEmphasis(false);
            for ($i = 0; $i < (18 - strlen($salida)); $i++) {
                $printer->text(" ");
            }
            $printer->text("Hora Par:");
            $printer->setEmphasis(true);
            $printer->text(" $tiempo\n");
            $printer->setEmphasis(true);
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("      VALOR HORA          TOTAL\n");
            $printer->text("________________________________________________\n");
            $printer->setJustification();
            $printer->text("TARIFA $name_vehicle    $" . "$tarifa    |");
            for ($i = 0; $i < 6; $i++) {
                $printer->text(" ");
            }
            $printer->setTextSize(2, 2);
            $printer->text("$" . $cobro . "\n");
            $printer->setTextSize(1, 1);
            $printer->text("________________________________________________\n");
            $printer->text("Cobró: $name_valet\n");
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
            $printer->text($parametros['nota_par_parametros']);
            $printer->feed();
        } catch (Exception $e) {
            /* Images not supported on your PHP, or image file not found */
            $printer->text($e->getMessage() . "\n");
        }
        $printer->cut();
        $printer->close();
    }

}
