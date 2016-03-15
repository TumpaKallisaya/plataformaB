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
        $de = $this->input->get('de', '');
        $para = $this->input->get('para', '');
        $timestamp = time();
        
        $this->chatmodel->guardarMensaje($de, $para, $mensaje, $timestamp);
        $this->_setOutput($mensaje);
    }
    
    public function get_mensajes(){
        parse_str(substr(strrchr($_SERVER['REQUEST_URI'], "?"), 1), $_GET);
        $de = $_GET['de'];
        $para = $this->input->get('para', '');
        $timestamp = $this->input->get('timestamp', null);
        $mensajes = $this->chatmodel->getMensajes($de, $para, $timestamp);
        $this->_setOutput($mensajes);
    }
    
    private function _setOutput($data){
        header('Cache-Control: no-cache, must-revalidate');
	header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
	header('Content-type: application/json');
		
	echo json_encode($data);
    }
    
    public function mensajes_canal(){
        $usuario = $this->input->post('usuario');
        $mensajesCanal = $this->chatmodel->getMensajesCanalOnTime($usuario);
        echo json_encode($mensajesCanal);
    }
}