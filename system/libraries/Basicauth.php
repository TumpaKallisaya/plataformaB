<?php
/**
* 
*/
class Basicauth
{
	
	function __construct()
	{
			$this->CI = & get_instance();
	}
	
	function login($usuario, $password){
		$data = array();
		$query = $this->CI->db->get_where('tb_usuarios', array('usuario' => $usuario, 'password' => $password));
		
		if($query->num_rows()>0){
			$this->CI->session->sess_destroy();
			$this->CI->session->sess_create();
			$data['id_rol']=$query->row()->id_rol;
			$data['usuario']=$query->row()->usuario;
			$data['estado']=$query->row()->estado;
			$this->CI->session->set_userdata(array('logged_in' => true, 'usuario' => $query->row()->usuario, 'id_rol' => $query->row()->id_rol, 'id_usuario' => $query->row()->id_usuario));
		}else{
			$data['error'] = 'Usuario o contraseña incorrectos';
		}
		return $data;
		
	}
	
	function logout(){
		$this->CI->session->sess_destroy();
		redirect('home');
	}
}

?>