<?php
$data = array();
	$data['title'] = 'Titulo para la pagina web.';
	if($this -> input -> post('username'))
	{ // Verificamos si llega mediante post el username
	 
	    $rules = array(
	            array('field'=>'username','label'=>'username','rules'=>'required'),
	            array('field'=>'password','label'=>'password','rules'=>'required')
	    );//Reglas de validación en este caso solo es requerido que los campos tengan contenido
	 
	    $this -> form_validation -> set_rules($rules); // Establecemos las reglas de validacion
	 
	    if($this->form_validation->run() == FALSE)
	    { //Si la informacion no fue correctamente enviada
	        $this->load->view('v_login'); //Carga la vista de login
	    }
	    else
	    {
	        $username = $this -> input -> post('username');
	        $password = $this -> input -> post('password');
	        $result = $this -> common -> login($username, $password); //Llamamos a la función login dentro del modelo common mandando los argumentos password y username
	 
	        if($result)
	        { //login exitoso
			$sess_array = array();
	            foreach($result as $row)
	            {
	 
	                $sess_array = array(
	                    'id' => $row -> id,
	                    'username' => $row -> nombre
	                );
	 
	                $this -> session -> set_userdata('logged_in', $sess_array); //Iniciamos una sesión con los datos obtenidos de la base.
	            }
	            redirect('index', 'refresh');
	        }
	        else
	        { // La validación falla
	            $data['error'] = 'Nombre de usuario / Password Incorrecto'; //Error que será enviado a la vista en forma de arreglo
	            $this -> load -> view('manager/v_login', $data); //Cargamos el mensaje de error en la vista.
	        }
	    }
	}
	else
	{
	    $this -> load -> view('v_login');
	}