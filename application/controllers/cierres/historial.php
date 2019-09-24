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
        $data_header['menu_activo'] = 'cierres';

        $data_header['breadcrumb'] = array(
            array(
                'link' => '/cierres/historial/lista',
                'nombre' => 'Cierres Historial'
            ),
        );
        

        $cierres['select'] = base64_encode(" 
            p.id_par_cierre,
            p.fecha_par_cierre,
            p.valor_par_cierre,
            CONCAT(a.nombres_admin_usuario,' ',a.apellidos_admin_usuario) as nombre
            ");
        $cierres['from'] = base64_encode(" 
            FROM 
                par_cierre p
            INNER JOIN par_turno pt ON p.id_par_turno = pt.id_par_turno
            INNER JOIN admin_usuario a ON pt.id_admin_usuario = a.id_admin_usuario
                
               ");
        $cierres['where'] = base64_encode("");
        $cierres['group'] = base64_encode("");
        $cierres['order'] = base64_encode("ORDER BY p.id_par_cierre DESC");
        $cierres['limit'] = base64_encode("");
        if ($alert == 'ok') {
            $data_body['alert'] = 'ok';
        }
        if ($alert == 'error') {
            $data_body['alert'] = 'error';
        }
        $this->session->set_userdata(array("cierres" => $cierres));
        $this->load->view('administrador/templates/header', $data_header);
        $this->load->view('cierres/historial/lista', $data_body);
        $this->load->view('administrador/templates/footer');
    }
    public function imprimir($id_cierre = null) {
        $cierre = $this->modelo->getpar_cierre(array('id_par_cierre' => $id_cierre));
        $cierre = $cierre[0];
        $turno = $this->modelo->getpar_turno(array('id_par_turno' => $cierre['id_par_turno']));
        $turno = $turno[0];
        $id_turno = $turno['id_par_turno'];
        $cierre_detalles = $this->modelo->query("
            SELECT 
            sp.totalParking,
            sp.cantidadParking,
            sp.cantidadEliminados,
            vp.totalProducts,
            vp.cantProducts,
            tm.totalMen,
            tm.cantMen,
            g.totalGasto,
            g.cantGasto
            FROM par_turno t 
            INNER JOIN (SELECT SUM(cobro_par_historial) AS totalParking ,(SELECT COUNT(id_par_historial) FROM par_historial WHERE eliminado_par_historial = 0 AND id_par_turno = $id_turno ) AS cantidadParking , (SELECT COUNT(id_par_historial) FROM par_historial WHERE eliminado_par_historial = 1 AND id_par_turno = $id_turno) AS cantidadEliminados FROM par_historial WHERE id_par_turno = $id_turno) sp
            INNER JOIN (SELECT SUM(valor_par_venta_producto) AS totalProducts, SUM(cantidad_par_venta_producto) AS cantProducts FROM par_venta_producto WHERE id_par_turno = $id_turno) vp
            INNER JOIN (SELECT SUM(valor_par_historial_mensualidad) as totalMen,COUNT(id_par_historial_mensualidad) as cantMen FROM par_historial_mensualidad WHERE id_par_turno = $id_turno) tm
            INNER JOIN (SELECT SUM(valor_par_gasto) as totalGasto,COUNT(id_par_gasto) as cantGasto FROM par_gasto WHERE id_par_turno = $id_turno) g
            WHERE id_par_turno = $id_turno
            ");
        $cierre_detalles = $cierre_detalles[0];
        $tipos_vehiculos = $this->modelo->query("SELECT v.nombre_par_vehiculo_tipo,COUNT(ph.id_par_vehiculo_tipo) as totalVehiculo FROM par_historial ph INNER JOIN  par_vehiculo_tipo v ON ph.id_par_vehiculo_tipo = v.id_par_vehiculo_tipo WHERE ph.id_par_turno = $id_turno GROUP BY ph.id_par_vehiculo_tipo");
        $tipos_productos = $this->modelo->query("SELECT SUM(cantidad_par_venta_producto) as totalProducto,p.nombre_par_producto FROM par_venta_producto vp INNER JOIN par_producto p ON vp.id_par_producto = p.id_par_producto WHERE vp.id_par_turno = $id_turno GROUP BY p.id_par_producto");
        $mensualidades_pagas = $this->modelo->query("SELECT m.placa_par_mensualidad,hm.valor_par_historial_mensualidad  FROM par_mensualidad m INNER JOIN  par_historial_mensualidad hm ON hm.id_par_mensualidad = m.id_par_mensualidad WHERE hm.id_par_turno = $id_turno");
        $gastos = $this->modelo->getpar_gasto(array('id_par_turno'=>$id_turno));
        $fecha_cierre = $cierre['fecha_par_cierre'];
        $valor_cierre = $cierre['valor_par_cierre'];
        $valor_ingresado = $cierre['ingresado_par_cierre'];
        $variacion = $cierre['variacion_par_cierre'];
        $name = $this->modelo->getadmin_usuario(array('id_admin_usuario'=>$turno['id_admin_usuario']));
        $name = $name[0];
        $name = $name['nombres_admin_usuario'].' '.$name['apellidos_admin_usuario'];
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
            $printer->text("Cierre Fin Turno\n");
            $printer->setEmphasis(false);
            $printer->setTextSize(1, 1);
            $printer->setJustification();
            $printer->bitImage($tux);
            $printer->text("________________________________________________\n");
            $printer->text("Fecha Cierre:");
            for ($i = 0; $i < (30 - strlen($fecha_cierre)); $i++) {
                $printer->text(" ");
            }
            $printer->setEmphasis(true);
            $printer->text($fecha_cierre . "\n");
            $printer->setEmphasis(false);

            $printer->text("Valor Cierre:");
            for ($i = 0; $i < (24); $i++) {
                $printer->text(" ");
            }
            $printer->setEmphasis(true);
            $printer->text("$" . number_format($valor_cierre) . "\n");
            $printer->setEmphasis(false);

            $printer->text("Valor Ingresado:");
            for ($i = 0; $i < (21); $i++) {
                $printer->text(" ");
            }
            $printer->setEmphasis(true);
            $printer->text("$" . number_format($valor_ingresado) . "\n");
            $printer->setEmphasis(false);
            if ($variacion < 0) {
                $printer->text("Sobrante:");
                for ($i = 0; $i < (28); $i++) {
                    $printer->text(" ");
                }
                $printer->setEmphasis(true);
                $printer->text("$" . number_format($variacion * -1) . "\n");
                $printer->setEmphasis(false);
            } else if ($variacion > 0) {
                $printer->text("Faltante:");
                for ($i = 0; $i < (28); $i++) {
                    $printer->text(" ");
                }
                $printer->setEmphasis(true);
                $printer->text("$" . number_format($variacion) . "\n");
                $printer->setEmphasis(false);
            }
            $printer->text("Encargado:");
            for ($i = 0; $i < (24); $i++) {
                $printer->text(" ");
            }
            $printer->setEmphasis(true);
            $printer->text("$name\n");
            $printer->setEmphasis(false);
            $printer->text("________________________________________________\n");
            $printer->text("Total Parqueadero:");
            for ($i = 0; $i < (30-strlen("Total Parqueadero:")); $i++) {
                $printer->text(" ");
            }
            $printer->setEmphasis(true);
            $printer->text("$".number_format($cierre_detalles['totalParking']) . "\n");
            $printer->setEmphasis(false);
            $printer->text("Cantidad vehiculos:");
            for ($i = 0; $i < ((30-strlen("Cantidad vehiculos"))); $i++) {
                $printer->text(" ");
            }
            $printer->setEmphasis(true);
            $printer->text($cierre_detalles['cantidadParking'] . "\n");
            $printer->setEmphasis(false);
            $printer->text("Total Mensualidades:");
            for ($i = 0; $i < (30-strlen("Total Mensualidades:")); $i++) {
                $printer->text(" ");
            }
            $printer->setEmphasis(true);
            $printer->text("$".number_format($cierre_detalles['totalMen']) . "\n");
            $printer->setEmphasis(false);
            $printer->text("Cantidad mensualidades:");
            for ($i = 0; $i < (30-strlen("Cantidad mensualidades:")); $i++) {
                $printer->text(" ");
            }
            $printer->setEmphasis(true);
            $printer->text($cierre_detalles['cantMen'] . "\n");
            $printer->setEmphasis(false);

            $printer->text("Total Productos:");
            for ($i = 0; $i < ((30-strlen("Total Productos:"))); $i++) {
                $printer->text(" ");
            }
            $printer->setEmphasis(true);
            $printer->text("$".number_format($cierre_detalles['totalProducts']) . "\n");
            $printer->setEmphasis(false);
            $printer->text("Cantidad Productos:");
            for ($i = 0; $i < (30-strlen("Cantidad Productos:")); $i++) {
                $printer->text(" ");
            }
            $printer->setEmphasis(true);
            $printer->text($cierre_detalles['cantProducts'] . "\n");
            $printer->setEmphasis(false);
            
            $printer->text("Total Gastos:");
            for ($i = 0; $i < (30-strlen("Total Gastos:")); $i++) {
                $printer->text(" ");
            }
            $printer->setEmphasis(true);
            $printer->text("$".number_format($cierre['suma_gastos_par_cierre']) . "\n");
            $printer->setEmphasis(false);
            $printer->text("Cantidad Gastos:");
            for ($i = 0; $i < (30-strlen("Cantidad Gastos:")); $i++) {
                $printer->text(" ");
            }
            $printer->setEmphasis(true);
            $printer->text($cierre_detalles['cantGasto'] . "\n");
            $printer->setEmphasis(false);
            $printer->text("Cantidad Eliminados:");
            for ($i = 0; $i < ((30-strlen("Cantidad Eliminados:"))); $i++) {
                $printer->text(" ");
            }
            $printer->setEmphasis(true);
            $printer->text($cierre_detalles['cantidadEliminados'] . "\n");
            $printer->setEmphasis(false);
            $printer->text("________________________________________________\n");
            $printer->setEmphasis(true);
            $printer->setTextSize(2, 2);
            $printer->text("Parqueadero\n");
            $printer->setTextSize(1, 1);
            $printer->setEmphasis(false);
            foreach ($tipos_vehiculos as $vehiculo) {
                $printer->setEmphasis(true);
                $printer->text($vehiculo['nombre_par_vehiculo_tipo']);
                $printer->setEmphasis(false);
                for ($i = 0; $i < (34); $i++) {
                    $printer->text(" ");
                }
                $printer->text($vehiculo['totalVehiculo']."\n");
            }
            $printer->text("________________________________________________\n");
            $printer->setEmphasis(true);
            $printer->setTextSize(2, 2);
            $printer->text("Productos\n");
            $printer->setTextSize(1, 1);
            $printer->setEmphasis(false);
            foreach ($tipos_productos as $producto) {
                $printer->setEmphasis(true);
                $printer->text($producto['nombre_par_producto']);
                $printer->setEmphasis(false);
                for ($i = 0; $i < (34-  strlen($producto['nombre_par_producto'])); $i++) {
                    $printer->text(" ");
                }
                $printer->text($producto['totalProducto']."\n");
            }
            $printer->text("________________________________________________\n");
            $printer->setEmphasis(true);
            $printer->setTextSize(2, 2);
            $printer->text("Mensualidades\n");
            $printer->setTextSize(1, 1);
            $printer->setEmphasis(false);
            foreach ($mensualidades_pagas as $mensualidad) {
                $printer->setEmphasis(true);
                $printer->text($mensualidad['placa_par_mensualidad']);
                $printer->setEmphasis(false);
                for ($i = 0; $i < (30-strlen($mensualidad['valor_par_historial_mensualidad'])); $i++) {
                    $printer->text(" ");
                }
                $printer->text("$".number_format($mensualidad['valor_par_historial_mensualidad'])."\n");
            }
            $printer->text("________________________________________________\n");
            $printer->setEmphasis(true);
            $printer->setTextSize(2, 2);
            $printer->text("Gastos\n");
            $printer->setTextSize(1, 1);
            $printer->setEmphasis(false);
            foreach ($gastos as $gasto) {
                $printer->setEmphasis(true);
                $printer->text($gasto['detalle_par_gasto'].": ");
                $printer->setEmphasis(false);
                for ($i = 0; $i < (30-strlen($gasto['detalle_par_gasto'])); $i++) {
                    $printer->text(" ");
                }
                $printer->text("$".number_format($gasto['valor_par_gasto'])."\n");
            }
            $printer->feed();
        } catch (Exception $e) {
            /* Images not supported on your PHP, or image file not found */
            $printer->text($e->getMessage() . "\n");
        }
        $printer->cut();
        $printer->close();
        redirect('/cierres/historial/lista/ok');
    }
   

    

}
