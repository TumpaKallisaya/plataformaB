<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ModeloExcelPdf extends Model{
    public function usuariosExcelPdf(){
        $this->db->select('*');
        $this->db->from('tb_usuarios');
        $query = $this->db->get();
        return $query->result_array();
    }
}