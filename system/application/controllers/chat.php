<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Chat extends Controller{
    
    public function Chat(){
        parent::Controller();
        $this->load->helper('url');
        $this->load->model('chatmodel');
    }
    
    public function enviar_mensaje(){
        //$mensaje = $this->input->get('mensaje', null);
        parse_str(substr(strrchr($_SERVER['REQUEST_URI'], "?"), 1), $_GET);
        $mensaje = $_GET['mensaje'];
        $id_usuario_de = $this->input->get('id_usuario_de', '');
        $id_usuario_para = $this->input->get('id_usuario_para', '');
        $timestamp = time();
        
        $this->chatmodel->guardarMensaje($id_usuario_de, $id_usuario_para, $mensaje, $timestamp);
        $this->_setOutput($mensaje);
    }
    
    public function get_mensajes(){
        parse_str(substr(strrchr($_SERVER['REQUEST_URI'], "?"), 1), $_GET);
        $id_usuario_de = $_GET['id_usuario_de'];
        $id_usuario_para = $this->input->get('id_usuario_para', '');
        $timestamp = $this->input->get('timestamp', null);
        $mensajes = $this->chatmodel->getMensajes($id_usuario_de, $id_usuario_para, $timestamp);
        $this->_setOutput($mensajes);
    }
    
    public function get_mensajes_historicos(){
        parse_str(substr(strrchr($_SERVER['REQUEST_URI'], "?"), 1), $_GET);
        $id_usuario_de = $_GET['id_usuario_de'];
        $id_usuario_para = $_GET['id_usuario_para'];
        $mensajes = $this->chatmodel->getMensajesHistoricos($id_usuario_de, $id_usuario_para);
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
        
        $listaTemasAtt = $this->chatmodel->getLisTemAbiOpe($id_usuario, $ultimo_tema);
        $this->_setOutput($listaTemasOpe);
    }
    
}