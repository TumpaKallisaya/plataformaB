<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Chat extends Controller{
    
    public function Chat(){
        parent::Controller();
        $this->load->helper('url');
        $this->load->model('chatmodel');
    }
    
    public function enviar_chat(){
        //$mensaje = $this->input->get('mensaje', null);
        parse_str(substr(strrchr($_SERVER['REQUEST_URI'], "?"), 1), $_GET);
        $mensaje = $_GET['mensaje'];
        $id_usuario_de = $this->input->get('id_usuario_de', '');
        $id_tema = $this->input->get('id_tema', '');
        $timestamp = time();
        
        $chatGuardado = $this->chatmodel->guardarChat($id_usuario_de, $id_tema, $mensaje, $timestamp);
        $this->_setOutput($chatGuardado);
    }
    
    public function get_chats_constantes(){
        parse_str(substr(strrchr($_SERVER['REQUEST_URI'], "?"), 1), $_GET);
        $id_usuario = $_GET['id_usuario_de'];
        $id_tema = $_GET['id_tema'];
        $timestamp = $this->input->get('timestamp', null);
        
        $mensajes = $this->chatmodel->getChatsConstantes($id_usuario, $id_tema, $timestamp);
        $this->_setOutput($mensajes);
    }
    
    public function get_chats_recientes(){
        parse_str(substr(strrchr($_SERVER['REQUEST_URI'], "?"), 1), $_GET);
        $id_usuario = $_GET['id_usuario_de']; //Ya no es necesario
        $id_tema = $_GET['id_tema'];
        $es_att = $this->input->get('es_att', ''); //Ya no es necesario
        
        $mensajes = $this->chatmodel->getChatsRecientesAttOpe($id_usuario, $id_tema);
        $this->_setOutput($mensajes);
    }
    
    private function _setOutput($data){
        header('Cache-Control: no-cache, must-revalidate');
	header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
	header('Content-type: application/json');
		
	echo json_encode($data);
    }
    
    public function crearTemaChat(){
        parse_str(substr(strrchr($_SERVER['REQUEST_URI'], "?"), 1), $_GET);
        $id_usuario = $_GET['id_usuario_de'];
        $tema = $this->input->get('tema_chat', '');
        $cod_seccion = $this->input->get('cod_seccion', '');
        $id_operador = $this->input->get('id_operador', '');
        
        $tema = $this->chatmodel->crearTema($id_usuario, $tema, $cod_seccion, $id_operador);
        
        //$usuarioAtt = $this->chatmodel->recuperaAttSeccion($cod_seccion);
        //$temaRecuperado = $this->chatmodel->ultimoTemaCreado($id_usuario_de);
        //$chatDef = $this->chatmodel->crearChatDefecto($temaRecuperado->id, $id_usuario_de, $usuarioAtt->id_usuario);
        $this->_setOutput($tema);
    }
    
    public function get_lista_abiertos_manual(){
        parse_str(substr(strrchr($_SERVER['REQUEST_URI'], "?"), 1), $_GET);
        $id_usuario = $_GET['id_usuario'];
        
        $listaTemas = $this->chatmodel->listaTemasAbiertos($id_usuario);
        $this->_setOutput($listaTemas);
    }
    
    public function get_temasAttAbiertos(){
        parse_str(substr(strrchr($_SERVER['REQUEST_URI'], "?"), 1), $_GET);
        $id_usuario = $_GET['id_usuario'];
        $ultimo_tema = $this->input->get('ultimo_tema', '');
        
        $listaTemasAtt = $this->chatmodel->getLisTemAbiAtt($id_usuario, $ultimo_tema);
        $this->_setOutput($listaTemasAtt);
    }
    
    public function get_temasOpeAbiertos(){
        parse_str(substr(strrchr($_SERVER['REQUEST_URI'], "?"), 1), $_GET);
        $id_usuario = $_GET['id_usuario'];
        $ultimo_tema = $this->input->get('ultimo_tema', '');
        
        $listaTemasOpe = $this->chatmodel->getLisTemAbiOpe($id_usuario, $ultimo_tema);
        $this->_setOutput($listaTemasOpe);
    }
    
    public function get_tema_sel(){
        parse_str(substr(strrchr($_SERVER['REQUEST_URI'], "?"), 1), $_GET);
        $id_usuario = $_GET['id_usuario_de']; //Ya no es necesario
        $id_tema = $_GET['id_tema'];
        
        $tema = $this->chatmodel->getTemaSel($id_tema);
        $this->_setOutput($tema);
    }
    
}