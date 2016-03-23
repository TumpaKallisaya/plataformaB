<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Chatmodel extends Model{
    
    //devolverá todos aquellos mensajes que fueron escritos entre 2 segundos y el tiempo actual 
    //pasandole el nick del usuario que hace la solicitud para que no le devuelva sus propios mensajes
    public function getChatsConstantes($id_usuario, $id_tema, $timestamp){
        $where = 'c.id_tema ='.$id_tema.' and c.timestamp > '.$timestamp;
        $this->db->select('c.id, c.id_tema, c.id_usuario_de, c.mensaje, c.path, c.archivo, c.tamano, c.fecha_envio, ude.descripcion_usuario as dde');
        $this->db->from('tb_chat c');
        $this->db->join('tb_usuarios ude', 'c.id_usuario_de = ude.id_usuario');
        $this->db->where($where);
        $this->db->order_by('c.fecha_envio', 'DESC');
        $query = $this->db->get();
        
        return $query->result_array();
    }
    
    public function getChatsRecientesAttOpe($id_usuario, $id_tema){
        $where = 'c.id_tema ='.$id_tema;
        $this->db->select('c.id, c.id_tema, c.id_usuario_de, c.mensaje, c.path, c.archivo, c.tamano, c.fecha_envio, ude.descripcion_usuario as dde');
        $this->db->from('tb_chat c');
        $this->db->join('tb_usuarios ude', 'c.id_usuario_de = ude.id_usuario');
        $this->db->where($where);
        $this->db->order_by('c.fecha_envio', 'DESC');
        $this->db->limit(20);
        $query = $this->db->get();
        
        return array_reverse($query->result_array());
    }
    
    //añadiendo los mensajes que los distintos usuarios pongan en el chat
    public function guardarChat($id_usuario_de, $id_tema, $mensaje, $timestamp){
        $data = array(
            'id_tema' => $id_tema,
            'id_usuario_de' => $id_usuario_de,
            'mensaje' => $mensaje,
            'timestamp' => $timestamp,
            'id_tema_hist' => $id_tema
        );
        $this->db->set('fecha_envio', 'NOW()', false);
        
        return $this->db->insert('tb_chat', $data);
    }
    
    public function guardarAdjChat($id_usuario_de, $id_tema, $path, $archivo, $tamano, $timestamp){
        $data = array(
            'id_tema' => $id_tema,
            'id_usuario_de' => $id_usuario_de,
            'path' => $path,
            'archivo' => $archivo,
            'tamano' => $tamano,
            'timestamp' => $timestamp,
            'id_tema_hist' => $id_tema
                
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
        $qry = 'cod_seccion in (3, 4, 6)';
        $this->db->where($qry);
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
        /*$qry = "us.id_usuario = ".$id_usuario." and ct.estado = 'ABIERTO' and ct.id > ".$ultimo_tema;
        $this->db->select('ct.id, ct.cod_seccion, ct.id_operador, ct.tema');
        $this->db->from('tb_chat_tema ct');
        $this->db->join('tb_usuario_seccion us', 'us.cod_seccion = ct.cod_seccion');
        $this->db->where($qry);
        $this->db->order_by('ct.id', 'DESC');
        $query = $this->db->get();*/
        $query = $this->db->query("select ct.id, ct.cod_seccion, ct.id_operador, ct.tema, ct.estado, c.id_usuario_de as id_usu_ult, c.fecha_envio as fec_ult, max(c.id) as id_conv, u.descripcion_usuario
                from tb_chat_tema ct, tb_usuario_seccion us, tb_chat c, tb_usuarios u
                where us.cod_seccion = ct.cod_seccion
                and ct.id = c.id_tema
                and c.id_usuario_de = u.id_usuario
                and us.id_usuario = ".$id_usuario." and ct.estado = 'ABIERTO'
                and ct.id > ".$ultimo_tema."
                group by ct.id
                union
                select ct.id, ct.cod_seccion, ct.id_operador, ct.tema, ct.estado, ct.id_usuario as id_usu_ult, ct.fecha_creacion as fec_ult, ct.id as id_conv, u.descripcion_usuario  
                from tb_chat_tema ct
                left join tb_chat c
                        on ct.id = c.id_tema	
                join tb_usuario_seccion us
                        on us.cod_seccion = ct.cod_seccion
                join tb_usuarios u
                        on ct.id_usuario = u.id_usuario
                where c.id_tema is null
                and us.id_usuario = ".$id_usuario."
                and ct.estado = 'ABIERTO'
                and ct.id > ".$ultimo_tema."
                order by id asc");
        
        return $query;
    }
    
    public function getLisTemAbiOpe($id_usuario, $ultimo_tema){
        /*$qry = "uo.id_usuario = ".$id_usuario." and ct.estado = 'ABIERTO' and ct.id > ".$ultimo_tema;
        $this->db->select('ct.id, ct.cod_seccion, ct.id_operador, ct.tema');
        $this->db->from('tb_chat_tema ct');
        $this->db->join('tb_usuario_operador uo', 'uo.id_operador = ct.id_operador');
        $this->db->where($qry);
        $this->db->order_by('ct.id', 'ASC');
        $query = $this->db->get();*/
        $query = $this->db->query(" select ct.id, ct.cod_seccion, ct.id_operador, ct.tema, ct.estado, c.id_usuario_de as id_usu_ult, c.fecha_envio as fec_ult, max(c.id) as id_conv, u.descripcion_usuario
                from tb_chat_tema ct, tb_usuario_operador uo, tb_chat c, tb_usuarios u
                where uo.id_operador = ct.id_operador
                and ct.id = c.id_tema
                and c.id_usuario_de = u.id_usuario
                and uo.id_usuario = ".$id_usuario." and ct.estado = 'ABIERTO'
                and ct.id > ".$ultimo_tema."
                group by ct.id
                union
                select ct.id, ct.cod_seccion, ct.id_operador, ct.tema, ct.estado, ct.id_usuario as id_usu_ult, ct.fecha_creacion as fec_ult, ct.id as id_conv, u.descripcion_usuario  
                from tb_chat_tema ct
                left join tb_chat c
                    on ct.id = c.id_tema	
                join tb_usuario_operador uo
                    on uo.id_operador = ct.id_operador
                join tb_usuarios u
                    on ct.id_usuario = u.id_usuario
                where c.id_tema is null
                and uo.id_usuario = ".$id_usuario."
                and ct.estado = 'ABIERTO'
                and ct.id > ".$ultimo_tema."
                order by id asc
                ");
        
        return $query;
    }
    
    public function getTemaSel($id_tema){
        $this->db->where('id', $id_tema);
        $query = $this->db->get('tb_chat_tema');
        
        return $query->row();
    }
    
    public function getAdjChat($id){
        $this->db->select('c.id, c.id_tema, c.id_usuario_de, c.mensaje, c.path, c.archivo, c.tamano, c.fecha_envio');
        $this->db->from('tb_chat c');
        $this->db->where('c.id', $id);
        $this->db->limit(1);
        $query = $this->db->get();
        
        return $query->row();
    }
    
    public function finalizarTema($id_tema, $data){
        $this->db->where('id', $id_tema);
        return $this->db->update('tb_chat_tema', $data);
    }
    
    public function derivarChatNuevoTema($id_tema_antiguo, $dataChatDerivado){
        $this->db->where('id_tema', $id_tema_antiguo);
        return $this->db->update('tb_chat', $dataChatDerivado);
    }
    
    public function buscarTemaCompleto($id_usuario, $tema, $seccion, $id_operador, $estado){
        $qry = 'id_usuario='.$id_usuario.' and tema="'.$tema.'" and cod_seccion='.$seccion.' and id_operador='.$id_operador.' and estado="'.$estado.'"';
        $this->db->where($qry);
        $this->db->limit(1);
        $query = $this->db->get('tb_chat_tema');
        
        return $query->row();
    }
    
    public function getNroTemasAtt($id_usuario){
        $qry = "us.id_usuario = ".$id_usuario." and ct.estado = 'ABIERTO'";
        $this->db->select('ct.id, ct.cod_seccion, ct.id_operador, ct.tema');
        $this->db->from('tb_chat_tema ct');
        $this->db->join('tb_usuario_seccion us', 'us.cod_seccion = ct.cod_seccion');
        $this->db->where($qry);
        $this->db->order_by('ct.id', 'ASC');
        $query = $this->db->get();
        
        return $query->num_rows();
    }
    
    public function getNroTemasOpe($id_usuario){
        $qry = "uo.id_usuario = ".$id_usuario." and ct.estado = 'ABIERTO'";
        $this->db->select('ct.id, ct.cod_seccion, ct.id_operador, ct.tema');
        $this->db->from('tb_chat_tema ct');
        $this->db->join('tb_usuario_operador uo', 'uo.id_operador = ct.id_operador');
        $this->db->where($qry);
        $this->db->order_by('ct.id', 'ASC');
        $query = $this->db->get();
        
        return $query->num_rows();
    }
    
    public function getNroTemasAntiguosAtt($id_usuario){
        $qry = "us.id_usuario = ".$id_usuario." and ct.estado = 'CERRADO'";
        $this->db->select('distinct(ct.id), ct.cod_seccion, ct.id_operador, ct.tema');
        $this->db->from('tb_chat_tema ct');
        $this->db->join('tb_usuario_seccion us', 'us.cod_seccion = ct.cod_seccion');
        $this->db->join('tb_chat c','ct.id = c.id_tema');
        $this->db->where($qry);
        $this->db->order_by('ct.id', 'ASC');
        $query = $this->db->get();
        
        return $query;
    }
    
    public function getNroTemasAntiguosOpe($id_usuario){
        $qry = "uo.id_usuario = ".$id_usuario." and ct.estado = 'CERRADO'";
        $this->db->select('distinct(ct.id), ct.cod_seccion, ct.id_operador, ct.tema');
        $this->db->from('tb_chat_tema ct');
        $this->db->join('tb_usuario_operador uo', 'uo.id_operador = ct.id_operador');
        $this->db->join('tb_chat c','ct.id = c.id_tema');
        $this->db->where($qry);
        $this->db->order_by('ct.id', 'ASC');
        $query = $this->db->get();
        
        return $query;
    }
    
    
    public function chatPdf($id_tema){
        $this->db->select('c.mensaje, c.path, c.archivo, c.tamano, c.fecha_envio, u.usuario, u.descripcion_usuario');
        $this->db->from('tb_chat c');
        $this->db->join('tb_usuarios u', 'u.id_usuario = c.id_usuario_de');
        $this->db->where('c.id_tema', $id_tema);
        $this->db->order_by('c.id', 'ASC');
        $query = $this->db->get();
        
        return $query->result_array();
    }
    
    public function temaPdf($id_tema) {
        $this->db->select('ct.tema  , s.desc_seccion');
        $this->db->from('tb_chat_tema ct');
        $this->db->join('tb_seccion s', 'ct.cod_seccion = s.cod_seccion');
        $this->db->where('ct.id', $id_tema);
        $query = $this->db->get();
        
        return $query->row();
    }
}