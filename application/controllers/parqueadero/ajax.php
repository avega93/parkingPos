<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require $_SERVER['DOCUMENT_ROOT'] . '/pos/autoload.php';

use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\EscposImage;

class Ajax extends CI_Controller {

    public function finTurno() {
//        $data = json_encode($_POST);
        $post = $this->input->post();
        $money_current = ($post['b50'] * 50000) + ($post['b20'] * 20000) + ($post['b10'] * 10000) + ($post['b5'] * 5000) + ($post['b2'] * 2000) + ($post['b1'] * 1000) + $post['moneda'];
        $activo_turno = $this->modelo->query('SELECT id_admin_usuario,id_par_turno FROM par_turno WHERE fin_par_turno IS NULL');
        $activo_turno = $activo_turno[0];
        $id_turno = $activo_turno['id_par_turno'];
        $cierre = $this->modelo->query("
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
            INNER JOIN (SELECT SUM(valor_par_venta_producto) AS totalProducts, COUNT(id_par_producto) AS cantProducts FROM par_venta_producto WHERE id_par_turno = $id_turno) vp
            INNER JOIN (SELECT SUM(valor_par_historial_mensualidad) as totalMen,COUNT(id_par_historial_mensualidad) as cantMen FROM par_historial_mensualidad WHERE id_par_turno = $id_turno) tm
            INNER JOIN (SELECT SUM(valor_par_gasto) as totalGasto,COUNT(id_par_gasto) as cantGasto FROM par_gasto WHERE id_par_turno = $id_turno) g
            WHERE id_par_turno = $id_turno
            ");
        $cierre = $cierre[0];
        $money = $cierre['totalParking'] + $cierre['totalProducts'] + $cierre['totalMen'] - $cierre['totalGasto'];
        $variacion = $money - $money_current;
        $insert = array(
            'valor_par_cierre' => $money,
            'ingresado_par_cierre' => $money_current,
            'variacion_par_cierre' => $variacion,
            'id_par_turno' => $id_turno,
            'suma_gastos_par_cierre' => $cierre['totalGasto'],
            'b50_par_cierre' => $post['b50'],
            'b20_par_cierre' => $post['b20'],
            'b10_par_cierre' => $post['b10'],
            'b5_par_cierre' => $post['b5'],
            'b2_par_cierre' => $post['b2'],
            'b1_par_cierre' => $post['b1'],
            'moneda_par_cierre' => $post['moneda']
        );
        $cierre = $this->modelo->addpar_cierre($insert);
        if ($cierre) {
            $this->imprimir($cierre);
            $activo_turno['fin_par_turno'] = date('Y-m-d H:i:s');
            $fin_turno = $this->modelo->addpar_turno($activo_turno);
            $data = json_encode(1);
        } else {
            $data = json_encode(2);
        }
        echo $data;
//        $data = json_encode($money_current);
//        sleep(100);
//        echo $data; 
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
        $eliminados = $this->modelo->getpar_historial(array('id_par_turno'=>$id_turno,'eliminado_par_historial'=>1));
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
            
            $printer->text("________________________________________________\n");
            $printer->setEmphasis(true);
            $printer->setTextSize(2, 2);
            $printer->text("Eliminados\n");
            $printer->setTextSize(1, 1);
            $printer->setEmphasis(false);
            foreach ($eliminados as $eliminado) {
                $printer->setEmphasis(true);
                $printer->text($eliminado['placa_par_historial'].": ");
                $printer->setEmphasis(false);
                for ($i = 0; $i < (20-strlen($eliminado['placa_par_historial'])); $i++) {
                    $printer->text(" ");
                }
                $printer->text($eliminado['salida_par_historial']."\n");
            }
            
            $printer->feed();
        } catch (Exception $e) {
            /* Images not supported on your PHP, or image file not found */
            $printer->text($e->getMessage() . "\n");
        }
        $printer->cut();
        $printer->close();
    }

}
