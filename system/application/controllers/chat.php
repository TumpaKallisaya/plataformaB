<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Chat extends Controller{
    
    public function Chat(){
        parent::Controller();
        $this->load->helper('url');
        $this->load->model('chatmodel');
    }
    
    public function enviar_chat(){
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
    
    public function subirArchivoChat(){
        
        $status = "";
        $msg = "";
        $file_element_name = 'userfile';
        
        if ($status != "error"){
            $config['upload_path'] = './archivosChat/';
            $config['allowed_types'] = 'jpg|png|doc|pdf|txt';
            $config['max_size'] = 1024 * 8;
            $config['encrypt_name'] = FALSE;

            $this->load->library('upload', $config);
            if (!$this->upload->do_upload($file_element_name)){
                $status = 'error';
                $msg = $this->upload->display_errors('', '');
            }else{
                $data = $this->upload->data();
                $file_path = $data['full_path'];
                
                if(file_exists($file_path)){
                    $status = "success";
                    $path = $file_path;
                    $name = $data['file_name'];
                    $size = $data['file_size'];
                }else{
                    $status = "error";
                    $msg = "Something went wrong when saving the file, please try again.";
                }
            }
            @unlink($_FILES[$file_element_name]);
        }
        echo json_encode(array('status' => $status, 'path' => $path, 'name' => $name, 'size' => $size));
    }
    
    public function guardarArchAdj(){
        parse_str(substr(strrchr($_SERVER['REQUEST_URI'], "?"), 1), $_GET);
        $id_usuario_de = $this->input->get('id_usuario_de', '');
        $id_tema = $this->input->get('id_tema', '');
        $path = $this->input->get('path', '');
        $archivo = $this->input->get('archivo', '');
        $tamano = $this->input->get('size', '');
        $timestamp = time();
        
        $chatAdjGuardado = $this->chatmodel->guardarAdjChat($id_usuario_de, $id_tema, $path, $archivo, $tamano, $timestamp);
        $this->_setOutput($chatAdjGuardado);
    }
    
    public function derivarChat(){
        parse_str(substr(strrchr($_SERVER['REQUEST_URI'], "?"), 1), $_GET);
        $id_tema = $_GET['id_tema_actual'];
        $seccion = $_GET['nueva_seccion'];
        
        $temaAntiguo = $this->chatmodel->getTemaSel($id_tema);
        $dataFinTema = array(
            'estado' => 'CERRADO'
        );
        $temaFinalizado = $this->chatmodel->finalizarTema($id_tema, $dataFinTema);
        
        $nuevoTema = $this->chatmodel->crearTema($temaAntiguo->id_usuario, $temaAntiguo->tema, $seccion, $temaAntiguo->id_operador);
        $temaNuevo = $this->chatmodel->buscarTemaCompleto($temaAntiguo->id_usuario, $temaAntiguo->tema, $seccion, $temaAntiguo->id_operador, $estado = 'ABIERTO');
        
        $dataChatDerivado = array(
            'id_tema' => $temaNuevo->id
        );
        $chatDerivado = $this->chatmodel->derivarChatNuevoTema($id_tema, $dataChatDerivado);
        $this->_setOutput($temaNuevo);
    }
    
    public function finalizarChat(){
        parse_str(substr(strrchr($_SERVER['REQUEST_URI'], "?"), 1), $_GET);
        $id_tema = $_GET['id_tema'];
        
        $dataFinTema = array(
            'estado' => 'CERRADO'
        );
        $temaFinalizado = $this->chatmodel->finalizarTema($id_tema, $dataFinTema);
        $this->_setOutput($temaFinalizado);
    }
    
    public function getNroTemasActualesAtt(){
        parse_str(substr(strrchr($_SERVER['REQUEST_URI'], "?"), 1), $_GET);
        $id_usuario = $_GET['id_usuario'];
        
        $nroTemasActuales = $this->chatmodel->getNroTemasAtt($id_usuario);
        $this->_setOutput(array('nroTemasAct' => $nroTemasActuales));
    }
    
    public function getNroTemasActualesOpe(){
        parse_str(substr(strrchr($_SERVER['REQUEST_URI'], "?"), 1), $_GET);
        $id_usuario = $_GET['id_usuario'];
        
        $nroTemasActuales = $this->chatmodel->getNroTemasOpe($id_usuario);
        $this->_setOutput(array('nroTemasAct' => $nroTemasActuales));
    }
    
    public function getNroTemasAntiguos(){
        parse_str(substr(strrchr($_SERVER['REQUEST_URI'], "?"), 1), $_GET);
        $id_usuario = $_GET['id_usuario'];
        $es_att = $_GET['es_att'];
        
        
        if($es_att == 'si'){
            $nroTemasAntiguos = $this->chatmodel->getNroTemasAntiguosAtt($id_usuario)->num_rows();
        }else{
            $nroTemasAntiguos = $this->chatmodel->getNroTemasAntiguosOpe($id_usuario)->num_rows();
        }
        $this->_setOutput(array('nroTemasAnt' => $nroTemasAntiguos));
    }
    
    public function get_temasAntiguos(){
        parse_str(substr(strrchr($_SERVER['REQUEST_URI'], "?"), 1), $_GET);
        $id_usuario = $_GET['id_usuario'];
        $es_att = $_GET['es_att'];
        
        if($es_att == 'si'){
            $temasAntiguos = $this->chatmodel->getNroTemasAntiguosAtt($id_usuario)->result_array();
        }else{
            $temasAntiguos = $this->chatmodel->getNroTemasAntiguosOpe($id_usuario)->result_array();
        }
        $this->_setOutput($temasAntiguos);
    }
    
    
}