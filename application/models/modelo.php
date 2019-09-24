<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Modelo extends CI_Model {

    private $funcion;
    private $tabla;
    private $campos;
    private $id_campos;
    private $adicional;
    private $results;
    private $sql;

    function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->load->database();
    }

    public function __call($metodo, $parametros) {

        if ($metodo !== 'query' && $metodo !== 'join') {
            //poner todo en minusculas
            $metodo = strtolower($metodo);

            //obtener nombre de la funcion y nombre de la tabla
            $this->metodo_separar($metodo);
            $this->campos = $parametros[0];
            $this->adicional = $parametros[1];

            //limpiar variable campos
            $this->limpiar_campos();
            //verificar si campos tiene id
            $this->campos_tiene_id();

            //ejefuntar Funcion
            switch ($this->funcion) {
                case 'add':
                    //si compos tiene id edita, si no agrega el registro
                    if ($this->id_campos) {
                        $this->update();
                    } else {
                        $this->add();
                    }

                    break;
                case 'get':

                    $this->get();

                    break;
                case 'del':

                    $this->delete();

                    break;
                case 'cle':

                    $this->clear();

                    break;

                default:
                    echo "<br/><br/><hr/><br/><br/>The function (" . $metodo . ") is not found in the model. <br/><br/><hr/>";
                    exit();
                    break;
            }
        } else {
            if ($metodo === 'query') {
                $this->sql = $parametros[0];
                $this->query();
            } else {
                $this->join($parametros);
            }
        }
        return $this->results;
    }

    //limpiar basura del array de parametros
    //basura: contenidos que no son propios de la tabla.
    private function limpiar_campos() {
        $sql = "DESC " . $this->tabla;
        $results = $this->db->query($sql);
        $results = $results->result_array();

        //solo los campos que existen en la tabla
        $filtrados;
        foreach ($results as $value) {
            if (isset($this->campos[$value['Field']]))
                $filtrados[$value['Field']] = $this->campos[$value['Field']];
        }
        $this->campos = $filtrados;
    }

    private function campos_tiene_id() {
        if (isset($this->campos['id_' . $this->tabla]) && $this->campos['id_' . $this->tabla] !== '')
            $this->id_campos = TRUE;
        else
            $this->id_campos = FALSE;
    }

//    ADD
//    Funcion para agregar un registro a una tabla.
    private function add() {
        $this->sql = $this->db->insert_string($this->tabla, $this->campos);
        $this->query();
    }

//    UPDATE
//    Funcion para actualizar uno o varios registros en una tabla.
    private function update() {
        $id = $this->campos['id_' . $this->tabla];
        unset($this->campos['id_' . $this->tabla]);

        if (isset($this->adicional['where'])) {
            $where = $this->adicional['where'];
        } else {
            $where = "id_" . $this->tabla . " = " . $id;
        }

        $this->sql = $this->db->update_string($this->tabla, $this->campos, $where);
        $this->query();
        $this->results = $id;
    }

//    GET
//    Funcion para consultar los registros en una tabla
    private function get() {
        if (isset($this->adicional['order']))
            $this->db->order_by($this->adicional['order']);

        if ($this->campos !== NULL)
            $this->results = $this->db->where($this->campos);

        if (isset($this->adicional['where']))
            $this->results = $this->db->where($this->adicional['where']);

        $this->results = $this->db->get($this->tabla);
        $this->results = $this->results->result_array();
    }

//    DELETE
//    Funcion para eliminar uno o varios registros en una tabla
    private function delete() {
        if ($this->campos !== NULL)
            $this->db->where($this->campos);

        if (isset($this->adicional['where']))
            $this->results = $this->db->where($this->adicional['where']);

        $this->db->delete($this->tabla);
    }

//    CLEAR
//    Funcion para limpiar una tabla
    private function clear() {
        $this->db->empty_table($this->tabla);
    }

    //QUERY
    //Funcion para ejecutar cualquier query
    private function query() {
        $this->results = $this->db->query($this->sql);
        if ($this->results !== TRUE && $this->results !== FALSE)
            $this->results = $this->results->result_array();
        else
            $this->results = $this->db->insert_id();
    }

    //JOIN
    //Funcion para consultar varias tablas relacionadas
    private function join($parametros) {
        $tablas = $parametros[0];
        $ctrlAnd = FALSE;
        $this->sql = "SELECT * FROM ";
        foreach ($tablas as $value) {
            if ($ctrlAnd)
                $this->sql = " $this->sql, ";
            $this->sql = " $this->sql $value[0] ";
            $ctrlAnd = TRUE;
        }
        $ctrlAnd = FALSE;
        $ctrlAnd2 = FALSE;
        $this->sql = " $this->sql WHERE ";
        foreach ($tablas as $key => $value) {
            if ($ctrlAnd && $value[1] != NULL)
                $this->sql = " $this->sql AND ";
            if ($value[1] != NULL) {
                if (strpbrk($value[1], ",") !== FALSE) {
                    $relacion = split(",", $value[1]);
                    $ctrlAnd2 = FALSE;
                    foreach ($relacion as $r) {
                        if($ctrlAnd2)
                            $this->sql = " $this->sql AND ";
                        $this->sql = " $this->sql $value[0].id_$value[0] = " . $tablas[$r][0] . ".id_$value[0] ";
                        $ctrlAnd2 = TRUE;
                    }
                } else {
                     $this->sql = " $this->sql $value[0].id_$value[0] = " . $tablas[$value[1]][0] . ".id_$value[0] ";
                }
            }
            $ctrlAnd = TRUE;
        }
        if(isset($parametros[1])){
            $this->sql = " $this->sql $parametros[1]";
        }
        $this->query();
    }

    //obtener nombre de la funcion y nombre de la tabla
    private function metodo_separar($metodo) {
        $this->funcion = substr($metodo, 0, 3);
        $this->tabla = substr($metodo, 3);
    }

}
