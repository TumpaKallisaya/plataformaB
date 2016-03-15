<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Chatmodel extends Model{
    
    //devolverá todos aquellos mensajes que fueron escritos entre 2 segundos y el tiempo actual 
    //pasandole el nick del usuario que hace la solicitud para que no le devuelva sus propios mensajes
    public function getMensajes($id_usuario_de, $id_usuario_para, $timestamp){
        $this->db->where('id_usuario_de', $id_usuario_de);
        $this->db->where('id_usuario_para', $id_usuario_para);
        $this->db->where('timestamp >', $timestamp);
        $this->db->order_by('fecha_envio', 'DESC');
        //$this->db->limit(10);
        $query = $this->db->get('tb_chat');
        
        return $query->result_array();
    }
    
    public function getMensajesHistoricos($id_usuario_de, $id_usuario_para){
        $where1 = '(id_usuario_de="'.$id_usuario_de.'" or id_usuario_de="'.$id_usuario_para.'") and ('.'id_usuario_para="'.$id_usuario_para.'" or id_usuario_para="'.$id_usuario_de.'")';
        $this->db->select('c.id, c.id_tema, c.id_usuario_de, c.id_usuario_para, c.mensaje, c.fecha_envio, ude.descripcion_usuario as dde, upa.descripcion_usuario as dpa');
        $this->db->from('tb_chat c');
        $this->db->join('tb_usuarios ude', 'c.id_usuario_de = ude.id_usuario');
        $this->db->join('tb_usuarios upa', 'c.id_usuario_para = upa.id_usuario');
        $this->db->where($where1);
        $this->db->order_by('fecha_envio', 'DESC');
        $this->db->limit(15);
        $query = $this->db->get();
        
        return array_reverse($query->result_array());
    }
    
    //añadiendo los mensajes que los distintos usuarios pongan en el chat
    public function guardarMensaje($id_usuario_de, $id_usuario_para, $mensaje, $timestamp){
        $data = array(
            'id_usuario_de' => $id_usuario_de,
            'id_usuario_para' => $id_usuario_para,
            'mensaje' => $mensaje,
            'timestamp' => $timestamp
        );
        $this->db->set('fecha_envio', 'NOW()', false);
        
        return $this->db->insert('tb_chat', $data);
    }
    
    public function getListaContactos($usuario){
        $this->db->where('usuario !=', $usuario);
        $this->db->limit(5);
        $query = $this->db->get('tb_usuarios');
        
        return $query->result_array();
    }
    
    public function getListaContactosChatActivos($id_usuario){
        $this->db->where('id_usuario !=', $id_usuario);
        $this->db->limit(5);
        $query = $this->db->get('tb_usuarios');
        
        return $query->result_array();
    }
    
    public function getListaSecciones(){
        $this->db->order_by('cod_seccion', 'ASC');
        $query = $this->db->get('tb_seccion');
        
        return $query->result_array();
    }
    
    public function crearTema($id_usuario, $tema, $cod_seccion, $id_operador){
        $data = array(
            'id_usuario' => $id_usuario,
            'cod_seccion' => $cod_seccion,
            'id_operador' => $id_operador,
            'tema' => $tema,
            'estado' => 'ABIERTO'
        );
        $this->db->set('fecha_creacion', 'NOW()', false);
        
        return $this->db->insert('tb_chat_tema', $data);
    }
    
    public function recuperaAttSeccion($cod_seccion){
        
        $qry = "a.cod_seccion=".$cod_seccion.' and o.id_operador=4605';
        $this->db->select('u.id_usuario, u.usuario, u.descripcion_usuario');
        $this->db->from('tb_usuarios u');
        $this->db->join('tb_usuario_seccion a', 'a.id_usuario = u.id_usuario');
        $this->db->join('tb_usuario_operador o', 'o.id_usuario = u.id_usuario');
        $this->db->where($qry);
        $query = $this->db->get();
        
        return $query->row();
    }
    
    public function ultimoTemaCreado($id_usuario){
        $this->db->where('id_usuario', $id_usuario);
        $this->db->order_by('id', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get('tb_chat_tema');
        
        return $query->row();
    }

    public function crearChatDefecto($id_tema, $id_usuario_de, $id_usuario_para){
        $data = array(
            'id_tema' => $id_tema,
            'id_usuario_de' => $id_usuario_de,
            'id_usuario_para' => $id_usuario_para,
            'mensaje' => 'Mensaje por defecto para iniciar la conversación',
            'timestamp' => time()
        );
        $this->db->set('fecha_envio', 'NOW()', false);
        
        return $this->db->insert('tb_chat', $data);
    }
    
    public function esAtt($id_usuario){
        $this->db->where('id_usuario', $id_usuario);
        $this->db->where('id_operador', 4605);
        $query = $this->db->get('tb_usuario_operador');
        
        if ($query->num_rows() > 0){
            return true;
        }else{
            return false;
        }
    }
    
    public function listaTemasAbiertos($id_usuario){
        $qry = "(c.id_usuario_para=".$id_usuario.' or c.id_usuario_de='.$id_usuario.') and t.estado = "ABIERTO"';
        $this->db->select('distinct(t.id), t.tema, t.estado, t.fecha_creacion, c.id_usuario_de, c.id_usuario_para');
        $this->db->from('tb_chat_tema t');
        $this->db->join('tb_chat c', 't.id = c.id_tema');
        $this->db->limit(1);
        $this->db->where($qry);
        $query = $this->db->get();
        
        return $query->result_array();
    }
    
    public function getOperador($id_usuario){
        $this->db->select('o.id, o.RazonSocial, o.NombreComercialDeLaEmpresa');
        $this->db->from('tb_usuario_operador uo');
        $this->db->join('tb_operador o', 'o.id = uo.id_operador');
        $this->db->where('uo.id_usuario', $id_usuario);
        $this->db->limit(1);
        $query = $this->db->get();
       
        return $query->row();
    }
    
    public function getLisTemAbiAtt($id_usuario, $ultimo_tema){
        $qry = "us.id_usuario = ".$id_usuario." and ct.estado = 'ABIERTO' and ct.id > ".$ultimo_tema;
        $this->db->select('ct.id, ct.cod_seccion, ct.id_operador, ct.tema');
        $this->db->from('tb_chat_tema ct');
        $this->db->join('tb_usuario_seccion us', 'us.cod_seccion = ct.cod_seccion');
        $this->db->where($qry);
        $this->db->order_by('ct.id', 'ASC');
        $query = $this->db->get();
        
        return $query->result_array();
    }
    
    public function getLisTemAbiOpe($id_usuario, $ultimo_tema){
        $qry = "uo.id_usuario = ".$id_usuario." and ct.estado = 'ABIERTO' and ct.id > ".$ultimo_tema;
        $this->db->select('ct.id, ct.cod_seccion, ct.id_operador, ct.tema');
        $this->db->from('tb_chat_tema ct');
        $this->db->join('tb_usuario_operador uo', 'uo.id_operador = ct.id_operador');
        $this->db->where($qry);
        $this->db->order_by('ct.id', 'ASC');
        $query = $this->db->get();
        
        return $query->result_array();
    }
    
}