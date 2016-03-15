<?php
/**
* 
*/
class Acceso
{
	
	function identificado(){
		$this->CI =&get_instance();
		$controllersprivados = array('User', 'Home','Person');
		
		if($this->CI->session->userdata('logged_in')==true && $this->CI->router->method == 'login') redirect('user/login');
		
		if($this->CI->session->userdata('logged_in')!=true && $this->CI->router->method!='login' && in_array($this->CI->router->class, $controllersprivados)) redirect('home/login');
	}
}

?>