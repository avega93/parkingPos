<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Tarifas extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->acceso->validar();
    }

    public function lista($alert = NULL) {
        $data_header['menu_activo'] = 'parqueadero';
        if ($alert == 'ok') {
            $data_body['alert'] = 'ok';
        }
        if ($alert == 'exist') {
            $data_body['alert'] = 'exist';
        }
        $data_header['breadcrumb'] = array(
            array(
                'link' => '/parqueadero/tarifas/lista',
                'nombre' => 'Parqueo'
            ),
        );


        $tarifas['select'] = base64_encode(" 
            p.id_par_tarifa,
            p.precio_par_tarifa,
            rt.nombre_par_res_temporal,
            tv.nombre_par_vehiculo_tipo,
            p.editado_par_tarifa
            ");
        $tarifas['from'] = base64_encode(" 
            FROM 
                par_tarifa p
            INNER JOIN par_vehiculo_tipo tv ON tv.id_par_vehiculo_tipo = p.id_par_vehiculo_tipo
            INNER JOIN par_res_temporal rt ON rt.id_par_res_temporal = p.id_par_res_temporal
               ");
        $tarifas['where'] = base64_encode("");
        $tarifas['group'] = base64_encode("");
        $tarifas['order'] = base64_encode(""
                . "ORDER BY p.editado_par_tarifa DESC");
        $tarifas['limit'] = base64_encode("");
        $this->session->set_userdata(array("tarifas" => $tarifas));

        $this->load->view('administrador/templates/header', $data_header);
        $this->load->view('parqueadero/tarifas/lista', $data_body);
        $this->load->view('administrador/templates/footer');
    }

    public function agregar($id_par_tarifa = NULL) {

        $post = $this->input->post();
        if ($post) {
            if (is_null($id_par_tarifa)) {
                $exist = $this->modelo->getpar_tarifa(array('id_par_vehiculo_tipo' => $post['id_par_vehiculo_tipo'], 'id_par_res_temporal' => $post['id_par_res_temporal']));
                $exist = $exist[0];
                if (!is_null($exist)) {
                    redirect('/parqueadero/tarifas/lista/exist');
                }
            }
            $post['id_par_tarifa'] = $id_par_tarifa;
            $id_par_tarifa = $this->modelo->addpar_tarifa($post);
            redirect('/parqueadero/tarifas/lista/ok');
        }

        $data_header['menu_activo'] = 'usuario';

        if ($id_admin_usuario == NULL)
            $data_body['operacion'] = 'Agregar';
        else
            $data_body['operacion'] = 'Modificar';

        $data_header['breadcrumb'] = array(
            array(
                'link' => '/parqueadero/tarifa/lista',
                'nombre' => 'Tarifas'
            ),
            array(
                'link' => '#',
                'nombre' => $data_body['operacion']
            ),
        );
        if (!is_null($id_par_tarifa)) {
            $data_body['tarifa'] = $this->modelo->getpar_tarifa(array('id_par_tarifa' => $id_par_tarifa))[0];
        }
        $data_body['vehiculos'] = $this->modelo->getpar_vehiculo_tipo();
        $data_body['temporales'] = $this->modelo->getpar_res_temporal();

        $this->load->view('administrador/templates/header', $data_header);
        $this->load->view('parqueadero/tarifas/agregar', $data_body);
        $this->load->view('administrador/templates/footer');
    }

    public function eliminar($id_par_tarifa) {
        $this->modelo->delpar_tarifa(array('id_par_tarifa' => $id_par_tarifa));
        redirect('/parqueadero/tarifas/lista');
    }

}
