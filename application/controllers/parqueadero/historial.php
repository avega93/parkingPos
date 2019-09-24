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
        $data_header['menu_activo'] = 'parqueadero';

        $data_header['breadcrumb'] = array(
            array(
                'link' => '/parqueadero/historial/lista',
                'nombre' => 'Historial'
            ),
        );
        $historicos['select'] = base64_encode(" 
            ph.id_par_historial,
            ph.placa_par_historial,
            ph.entrada_par_historial,
            ph.salida_par_historial,
            ph.cobro_par_historial,
            IF(ph.eliminado_par_historial = 1, 'Eliminado','Normal') as eliminado,
            CONCAT(au.nombres_admin_usuario,' ',au.apellidos_admin_usuario) as nombre_usuario
            ");
        $historicos['from'] = base64_encode(" 
            FROM 
                par_historial ph
            INNER JOIN par_turno pt ON pt.id_par_turno = ph.id_par_turno
            INNER JOIN admin_usuario au ON pt.id_admin_usuario = au.id_admin_usuario
               ");
        $historicos['where'] = base64_encode("");
        $historicos['group'] = base64_encode("");
        $historicos['order'] = base64_encode(""
                . "ORDER BY ph.id_par_historial DESC");
        $historicos['limit'] = base64_encode("");

        $this->session->set_userdata(array("historial" => $historicos));

        $this->load->view('administrador/templates/header', $data_header);
        $this->load->view('parqueadero/historial/lista', $data_body);
        $this->load->view('administrador/templates/footer');
    }

    public function imprimir($id_parqueo = null) {
        $parqueo = $this->modelo->getpar_historial(array('id_par_historial' => $id_parqueo));
        $parqueo = $parqueo[0];
        
        if ($parqueo['eliminado_par_historial'] == 1) {
            redirect('parqueadero/historial/lista/eliminado');
        }
        $start_date = new DateTime(date($parqueo['salida_par_historial']));
        $since_start = $start_date->diff(new DateTime($parqueo['entrada_par_historial']));
        $minutes = $since_start->days * 24 * 60;
        $minutes += $since_start->h * 60;
        $minutes += $since_start->i;
        $tiempo = ($minutes - 4) / 30;
        $tiempo = ceil($tiempo);
        if (($tiempo % 2) != 0) {
            $tiempo = $tiempo / 2;
            $tiempo = floor($tiempo);
            $tiempo_real = $tiempo + 0.5;
        } else {
            $tiempo = $tiempo / 2;
            $tiempo = floor($tiempo);
            $tiempo_real = $tiempo;
        }
        $tiempo = $tiempo_real;
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
            $printer->text("Recibo de Cobro\n");
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
            $printer->text("TARIFA $name_vehicle    $" . "$tarifa   |");
            for ($i = 0; $i < 6; $i++) {
                $printer->text(" ");
            }
            $printer->setTextSize(2,2);
            $printer->text("$" . $cobro . "\n");
            $printer->setTextSize(1,1);
            $printer->text("________________________________________________\n");
            $printer->text("Cobró: $name_valet\n");
            $printer->setUnderline(true);
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("\nHorario de Atencion :\n");
            $printer->setFont($fonts[1]);
            $printer->setEmphasis(false);
            $printer->setUnderline(false);
            $printer->text($parametros['horario_atencion_par_parametros']."\n");
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
        redirect('parqueadero/historial/lista/ok');
    }

}
