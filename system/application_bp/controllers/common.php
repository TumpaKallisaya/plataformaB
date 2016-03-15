<?php

Class Common extends Model
	{
	    function login($username, $password)
	    {
	        // Esta función recibe como parámetros el nombre de usuario y password
			$this -> db -> select('nombre, id'); //Indicamos los campos que usaremos del resultado de la consulta
	        $this -> db -> from('tb_usuarios'); // indicamos la tabla a usar
	        $this -> db -> where('user = ' . "'" . $username . "'"); // Indicamos que va a buscar el nombre de usuario
	        $this -> db -> where('pass = ' . "'" . MD5($password) . "'"); // Indicamos que va a buscar el password con MD5
	        $this -> db -> limit(1);
	                // Solo deberá de existir un usuario
	 
	        $query = $this -> db -> get();
	                // Obtenemos los resultados del query
	 
	        if($query -> num_rows() == 1)
	        {
	            return $query->result();
	                        // Existen nombre de usuario y contra seña.
	        }
	        else
	        {
	            return false;
	                       // No existe nombre de usuario o contraseña.
	        }
	 
	    }
	}