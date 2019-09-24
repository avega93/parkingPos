<?php

if (!defined('BASEPATH')) 
    exit('No direct script access allowed');

class Ajax extends CI_Controller {

    public function gridPHP($info = NULL, $gridNumRow = 10, $gridOffset = 0, $gridOrden = "") {

        if ($info == NULL) {
            exit();
        }

        $query = $this->session->userdata($info);
        $query['select'] = base64_decode($query['select']);
        $query['from'] = base64_decode($query['from']);
        $query['where'] = base64_decode($query['where']);
        $query['group'] = base64_decode($query['group']);
        $query['order'] = base64_decode($query['order']);
        $query['limit'] = base64_decode($query['limit']);

        $l = "";
        if ($_GET['l'] != "") {
            $l = " {$_GET['l']} ";
            if (count(explode('WHERE', strtoupper($query['where']))) > 1) {
                $l = " AND (" . str_replace("**", "%", $l) . ")";
            } else {
                $l = " WHERE " . str_replace("**", "%", $l);
            }
        }

        if ($gridOrden != "") {
            $orden = explode('-', $gridOrden);
            $ascDesc = $orden[count($orden) - 1];
            unset($orden[count($orden) - 1]);

            $gridOrden = "";

            foreach ($orden as $campoOrden) {
                if ($gridOrden != "")
                    $gridOrden .= ',';
                $gridOrden .= $campoOrden . ' ' . $ascDesc;
            }

            $gridOrden = " ORDER BY " . $gridOrden;
        } else {
            $gridOrden = $query['order'];
        }
        
        if($query['limit'] != ""){
            $limit = $query['limit'];
            $result['total'] = $this->modelo->query("SELECT count(*) total {$query['from']} {$query['where']} $l {$query['group']} $limit ");
        } else {
            $limit = "LIMIT $gridNumRow OFFSET $gridOffset";
            $result['total'] = $this->modelo->query("SELECT count(*) total {$query['from']} {$query['where']} $l {$query['group']} ");
        }

        $result['total'] = $result['total'][0]['total'];
        $result['rows'] = $this->modelo->query("SELECT {$query['select']} {$query['from']} {$query['where']} $l {$query['group']} $gridOrden $limit ");

        echo json_encode($result);
        exit();
    }

    public function departamento($id_admin_pais = NULL) {
        if ($id_admin_pais != NULL) {
            $data_body['departamento'] = $this->modelo->getAdmin_departamento(array('id_admin_pais' => $id_admin_pais), array('order' => 'nombre_admin_departamento'));
            $this->load->view('administrador/ajax/departamento', $data_body);
        }
    }

    public function ciudad($id_admin_departamento = NULL) {
        if ($id_admin_departamento != NULL) {
            $data_body['ciudad'] = $this->modelo->getAdmin_ciudad(array('id_admin_departamento' => $id_admin_departamento), array('order' => 'nombre_admin_ciudad'));
            $this->load->view('administrador/ajax/ciudad', $data_body);
        }
    }

}
