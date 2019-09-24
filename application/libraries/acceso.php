<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Acceso {

    public function validar() {
        $CI = & get_instance();

        $segments = $CI->uri->segment_array();

        $seg['seg_1_admin_privilegios'] = NULL;
        $seg['seg_2_admin_privilegios'] = NULL;
        $seg['seg_3_admin_privilegios'] = NULL;
        $seg['seg_4_admin_privilegios'] = NULL;
        $seg['seg_5_admin_privilegios'] = NULL;
        $seg['seg_6_admin_privilegios'] = NULL;
        $seg['seg_7_admin_privilegios'] = NULL;
        $seg['seg_8_admin_privilegios'] = NULL;
        $seg['seg_9_admin_privilegios'] = NULL;
        $seg['seg_10_admin_privilegios'] = NULL;

        $privilegios = NULL;
        
            
        for ($i = 1; $i <= count($segments) && $i <= 10; $i++) {
            $seg['seg_' . $i . '_admin_privilegios'] = $segments[$i];
            $privilegio = $CI->modelo->getAdmin_privilegios($seg);
            if (isset($privilegio[0]['codigo_admin_privilegios']))
                $privilegios[$privilegio[0]['id_admin_privilegios']] = $privilegio[0]['codigo_admin_privilegios'];
        }
        foreach ($privilegios as $id => $codigo) {
            $control = $CI->modelo->getAdmin_privilegios_rol(
                    array(
                        'id_admin_rol' => $CI->session->userdata('id_admin_rol'),
                        'id_admin_privilegios' => $id
                    )
            );
            
            if (!(isset($control[0]['id_admin_privilegios_rol']) &&
                    $control[0]['id_admin_rol'] === $CI->session->userdata('id_admin_rol') &&
                    $control[0]['id_admin_privilegios'] !== $id)
            )
                redirect('/administrador/login/validar/2');
        }
    }

    public function privilegios() {
        $CI = & get_instance();

        $priviliegios_rol = $CI->modelo->getAdmin_privilegios_rol(
                array(
                    'id_admin_rol' => $CI->session->userdata('id_admin_rol')
                )
        );
        foreach ($priviliegios_rol as $value) {
            $p = $CI->modelo->getAdmin_privilegios(
                    array(
                        'id_admin_privilegios' => $value['id_admin_privilegios']
                    )
            );
            $privilegios[$p[0]['codigo_admin_privilegios']] = $p[0]['id_admin_privilegios'];
        }
        return $privilegios;
    }

}
