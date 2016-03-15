<?php 

if (! defined('BASEPATH')) exit('No direct script access');

class Home extends Controller {

	//php 5 constructor
	function __construct() {
		parent::Controller();
		$this->load->library(array('table','validation','form_validation','basicauth'));
		$this->load->helper(array('url','form'));
		// load model
		$this->load->model('personModel','',TRUE);
	}
	
 	function index(){
 		$data['usuario']='';
		$data['password']='';
		$data['error']='';
 		$data['action'] = site_url('home/login');
		$this->load->view('login',$data);
 	}

	function login(){ 			
		$data = array();
		$respuesta = $this->basicauth->login($this->input->post('usuario'), $this->input->post('password'));		
		//echo var_dump($respuesta);
		if(isset($respuesta['error'])){
			$data['usuario'] = $this->input->post('usuario');
			$data['password'] = '';
			$data['error'] = $respuesta['error'];
			$data['action'] = site_url('home/login');
			$this->load->view('login', $data);
		}
		else{
			if($respuesta['estado']=='0'){
				$data['error'] = 'Usuario Deshabilitado';
				$data['usuario'] = $this->input->post('usuario');
				$data['action'] = site_url('home/login');
				$this->load->view('login_view', $data);
			}
			else{ //echo $respuesta['id_rol'];
				if($respuesta['id_rol']!='1'){ //si es administrador
					if($respuesta['id_rol']>='2'){ //si es operador							
						redirect('person/index','refresh');	
					}
					else{
						redirect('home/index','refresh');		
					}
				}	
				else 
					redirect('person/listUsuario/0','refresh');	
			}			
		}
 	}
 	function loginError(){
 		$data['usuario']='';
		$data['password']='';
		$data['error']='Error al Introducir las Coordenadas';
 		$data['action'] = site_url('home/login');
		$this->load->view('login',$data);
 	} 	
 }
  	

