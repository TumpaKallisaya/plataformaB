<?php
class Person extends Controller {
	private $limit=10;

	public $fichero;//-- fichero de origen
	public $fsalida;//-- nombre del nuevo fichero
	public $dirsalida;//-- directorio del nuevo fichero
	public $prefijo;//-- prefijo del nuevo fichero
	public $valores;//-- valores a reemplazar
	public $datqG;//-- valores de la grafica
	public $titleG;//-- titulo de la grafica
	public $labelG;//-- leyendas de la grafica
	function Person(){
		parent::Controller();
		
		// load library
		$this->load->library(array('cezpdf','fpdf','table','validation', 'My_PHPMailer'));
		
		// load helper
		$this->load->helper(array('form', 'url', 'download'));
		
		// load model
		$this->load->model('personModel','',TRUE);
        $this->load->model('chatmodel');
		error_reporting(E_ALL);
	}	
	function index(){		
		$usu = $this->session->userdata('usuario');//echo $usu;
		$id_rol = $this->session->userdata('id_rol');
		$id_usu = $this->session->userdata('id_usuario');
		$Aplicaciones = $this->personModel->getTable0('tb_url','Tipo','A')->result();
		$Servicios = $this->personModel->getTable0('tb_url','Tipo','S')->result();
		$data['Aplicaciones'] = $Aplicaciones;
		$data['Servicios'] = $Servicios;
               $data['usuario'] = $usu;
                
               $operador = $this->chatmodel->getOperador($id_usu);
                $data['id_usuario'] = $id_usu;
                $data['listaSecciones'] = $this->chatmodel->getListaSecciones();
                $data['operador'] = $operador;
                
                if($this->chatmodel->esAtt($id_usu)){
                    $data['esAtt'] = true;
                    //$data['listaTemasAbiertos'] = $this->chatmodel->getLisTemAbiAtt($id_usu);
                }else{
                    $data['esAtt'] = false;
                    //$data['listaTemasAbiertos'] = $this->chatmodel->getLisTemAbiAtt($id_usu);
                }
                //$listaTemasAbiertos = $this->chatmodel->listaTemasAbiertos($id_usu);
                //$data['listaTemasAbiertos'] = $listaTemasAbiertos;

		$UserAplicaciones = $this->personModel->getTable1('tb_rel_usr_url','IdUsuario',$id_usu,'Tipo','A')->result();	
		$UserServicios = $this->personModel->getTable1('tb_rel_usr_url','IdUsuario',$id_usu,'Tipo','S')->result();	
		$data['UserAplicaciones'] = $UserAplicaciones;
		$data['UserServicios'] = $UserServicios;
		$data['table'] = '';

		$this->load->view('index1', $data);
	}
        public function downloadAdjChat(){
        parse_str(substr(strrchr($_SERVER['REQUEST_URI'], "?"), 1), $_GET);
        $id = $_GET['id_chat'];
        $this->load->helper('download');
        $datosDownload = $this->chatmodel->getAdjChat($id);
        $data = file_get_contents("archivosChat/".$datosDownload->archivo); // Read the file's contents
        $name = $datosDownload->archivo;

        force_download($name, $data);
        //_push_file($data, $name);
        
        //force_download($datosDownload->archivo, $datosDownload->path);
        $this->_setOutput($datosDownload);
    }
    
    public function printChatPdf(){
        parse_str(substr(strrchr($_SERVER['REQUEST_URI'], "?"), 1), $_GET);
        $id_tema = $_GET['id_tema'];
        $chats_tema = $this->chatmodel->chatPdf($id_tema);
        $tema_datos = $this->chatmodel->temaPdf($id_tema);
        
        $this->load->library('table_pdf');
        $this->pdf = new Table_pdf();
        
        // Agregamos una página
        $this->pdf->AddPage();
        // Define el alias para el número de página que se imprimirá en el pie
        $this->pdf->AliasNbPages();

        /* Se define el titulo, márgenes izquierdo, derecho y
         * el color de relleno predeterminado
         */
        $this->pdf->SetTitle("Reporte de conversaciones");
        $this->pdf->SetLeftMargin(15);
        $this->pdf->SetRightMargin(15);
        $this->pdf->SetFillColor(200,200,200);

        // Se define el formato de fuente: Arial, negritas, tamaño 9
        $this->pdf->SetFont('Arial', 'B', 9);
        /*
         * TITULOS DE COLUMNAS
         *
         * $this->pdf->Cell(Ancho, Alto,texto,borde,posición,alineación,relleno);
         */
        
        
        $this->pdf->Image('images/logo1.png',10,8,22);
            $this->pdf->SetFont('Arial','B',13);
            $this->pdf->Cell(40);
            $this->pdf->Cell(120,10,'AUTORIDAD DE REGULACION Y FISCALIZACION DE',0,0,'C');
            $this->pdf->Ln('5');
            $this->pdf->Cell(40);
            $this->pdf->Cell(120,10,'TELECOMUNICACIONES Y TRANSPORTES',0,0,'C');
            $this->pdf->Ln('8');
            $this->pdf->SetFont('Arial','B',8);
            $this->pdf->Cell(38);
            $this->pdf->Cell(120,10,'REPORTE DE CHAT',0,0,'C');
            $this->pdf->Ln(20);
            $this->pdf->Cell(100,7,'Tema: '.$tema_datos->tema,'N',0,'L','0');
        $this->pdf->Ln(5);
        $this->pdf->Cell(100,7,'Seccion: '.$tema_datos->desc_seccion,'N',0,'L','0');
        
        $this->pdf->Ln(8);
        $this->pdf->Cell(35,7,'USUARIO','TBL',0,'C','0');
        $this->pdf->Cell(115,7,'MENSAJE','TB',0,'C','0');
        $this->pdf->Cell(30,7,'FECHA ENVIO','TBR',0,'C','0');
        
        $this->pdf->Ln(7);
        
        
        // La variable $x se utiliza para mostrar un número consecutivo
        $x = 1;
        foreach ($chats_tema as $chat) {
          // se imprime el numero actual y despues se incrementa el valor de $x en uno
          // Se imprimen los datos de cada alumno
          $this->pdf->Cell(35,7,$chat['descripcion_usuario'],'N',0,'L',0);
          $this->pdf->Cell(115,7,$chat['mensaje'],'N',0,'L',0);
          $this->pdf->Cell(30,7,$chat['fecha_envio'],'N',0,'L',0);
          //Se agrega un salto de linea
          $this->pdf->Ln(7);
        }
        //$this->pdf->SetY(-10);
          // $this->pdf->SetFont('Arial','I',8);
           //$this->pdf->Cell(0,10,'Página '.$this->pdf->PageNo().'/{nb}',0,0,'C');
        /*
         * Se manda el pdf al navegador
         *
         * $this->pdf->Output(nombredelarchivo, destino);
         *
         * I = Muestra el pdf en el navegador
         * D = Envia el pdf para descarga
         *
         */
        $this->pdf->Output("Chats.pdf", 'I');
    }
    
	function listUsuario($kkk){
			$flag=base64_decode($kkk);
			$data['action'] = '';
			$usu = $this->session->userdata('usuario');if($usu==false){redirect('/home');}
			$id_rol = $this->session->userdata('id_rol');
			$id_usu = $this->session->userdata('id_usuario');
			$data['mensaje'] = '';	

			// generate table data
			$this->table->clear();
			$this->load->library('table');
			$this->table->set_empty("&nbsp;");
			$this->table->set_heading('<p>Usuario</p>', '<p>Nombre Usuario</p>', '<p>Email</p>', '<p>Rol</p>', '<p>Estado</p>','<p>Acción</p>','<p>Tarjeta</p>','<p>Impresión</p>');

			$usuarios2 = $this->personModel->get_usuarios2()->result(); 
			foreach ($usuarios2 as $a){
				$RolX = $this->personModel->get_by_id_SL('tb_rol','Id_Rol',$a->id_rol)->row();
				if($a->estado=='1'){
					$estado=anchor('person/act_desact_usuarios/'.base64_encode("$a->id_usuario"),'<img src="../../../theme/lib/img/chekbox_on.png">');			}
				if($a->estado=='0'){
					$estado=anchor('person/act_desact_usuarios/'.base64_encode("$a->id_usuario"),'<img src="../../../theme/lib/img/chekbox_off.png">');			}
				
				//rescato la tarjeta de la bd  
				$tarjeta = $this->personModel->get_tarjeta($a->id_usuario)->row_array(); 
				if(count($tarjeta)>0){ 
					$this->table->add_row($a->usuario, $a->descripcion_usuario, $a->email, $RolX->Rol, $estado,
					anchor('person/updateUsuario/'.base64_encode("$a->id_usuario").'/'.base64_encode("0"),'<img src="../../../theme/lib/img/update.png">'.' modificar'),
					anchor('person/genera_tarjeta/'.base64_encode("$a->id_usuario"),'generar / actualizar',array('class'=>'add')),		
					anchor('person/imprime_tarjeta/'.base64_encode("$a->id_usuario"),'imprimir tarjeta',array('class'=>'imprimir'))		
					);	//aumentado el menu 'generar tarjeta' solo para los usuarios operadores by neko		
				}else{
					$this->table->add_row($a->usuario, $a->descripcion_usuario, $a->email, $RolX->Rol, $estado,
					anchor('person/updateUsuario/'.base64_encode("$a->id_usuario").'/'.base64_encode("0"),'<img src="../../../theme/lib/img/update.png">'.' modificar'),
					anchor('person/genera_tarjeta/'.base64_encode("$a->id_usuario"),'generar / actualizar',array('class'=>'add')),
					''	
					);	//aumentado el menu 'generar tarjeta' solo para los usuarios operadores by neko	
				}
				
			}
			$data['table2'] = $this->table->generate();

			$data['add'] = anchor('person/addUsuario','<img src="../../../theme/lib/img/add.png"> Agregar Nuevo Usuario');
			$data['flag'] = $flag;
			$data['info_mensaje'] = '<div class="alert alert-info">'.
									'<button type="button" class="close" data-dismiss="alert">x</button>'.
									'Usuario Añadido Correctamente.'.
									'</div>';
			$data['title'] = "Usuarios";
			$data['usu'] = $usu;
			$xusu=$this->personModel->get_usu($id_usu)->row();
			$rol=$this->personModel->get_roles_by_id($id_rol)->row();
			$data['rol']=$xusu->descripcion_usuario.'-'.$rol->Rol;
			$data['id_rol'] = $id_rol;
			$this->load->view('listUsuarios', $data);
	}
	function act_desact_usuarios($kkk){
		$id_usuario=base64_decode($kkk);
		$usuario = $this->personModel->get_usu($id_usuario)->row();		
		if($usuario->estado=='1'){
			$dato = array('estado' => '0');
		}
		if($usuario->estado=='0'){
			$dato = array('estado' => '1');
		}
		$this->personModel->update_usuarios($id_usuario,$dato);
		redirect('person/listUsuario/'.base64_encode("0"),'refresh');
	}
	function addUsuario(){
		$data['action'] = site_url('person/save_usuarios/');
		$usu = $this->session->userdata('usuario');if($usu==false){redirect('/home');}
		$id_usu = $this->session->userdata('id_usuario');
		$id_rol = $this->session->userdata('id_rol');
		$data['mensaje'] = '';
		$data['usuario']='';
		$data['descripcion_usuario']='';
		
		$data['email']='';
		$data['rol1']='';


		$Aplicaciones = $this->personModel->getTable0('tb_url','Tipo','A')->result();
		$Servicios = $this->personModel->getTable0('tb_url','Tipo','S')->result();
		$data['Aplicaciones'] = $Aplicaciones;
		$data['Servicios'] = $Servicios;


		$data['UserAplicaciones'] = '';
		$data['UserServicios'] = '';

		$data['usuario_reg'] = '';
		$data['fecha_reg'] = '';
		$data['usuario_mod'] = '';		
		$data['fecha_mod'] = '';

		$data['RolC']=$this->personModel->InputSelect4('tb_rol','Rol','Id_Rol','Flag','1');
		$data['usu'] = $usu;
		$data['flag'] = '0';
		$data['title1'] = 'Agregar Usuario';
		$data['boton1'] = 'Agregar';
		$xusu=$this->personModel->get_usu($id_usu)->row();
		$rol=$this->personModel->get_roles_by_id($id_rol)->row();
		$data['rol']=$xusu->descripcion_usuario.'-'.$rol->Rol;
		$data['id_rol'] = $id_rol;
		$this->load->view('updateUsuario', $data);
	}
	function save_usuarios(){
		$data['action'] = site_url('person/save_usuarios/');
		$fecha_actual = date('Y-m-d h:i:s');
		$fecha=date("d-m-Y");
		$usu = $this->session->userdata('usuario');if($usu==false){redirect('/home');}
		$id_usu = $this->session->userdata('id_usuario');
		$data['mensaje'] = '';

		$person = array('usuario' => $this->input->post('usuario'),
						'password' => $this->input->post('password'),
						'descripcion_usuario' => $this->input->post('descripcion_usuario'),
						'email' => $this->input->post('email'),
						'id_rol' => $this->input->post('Rol'),
						'usuario_reg' => $usu,
						'fecha_reg' => $fecha_actual,
						'estado' => '1'
					);
		$this->personModel->save_usuario($person);
		$data['nivel']='admin';
		redirect('/person/listUsuario/'.base64_encode("1"), 'refresh');
	}
	function updateUsuario($kkk1,$kkk2){
		$id_usu10=base64_decode($kkk1);
		$flag=base64_decode($kkk2);
		$data['action'] = site_url('person/update_modificar_usuarios/'.base64_encode("$id_usu10"));
		$usu = $this->session->userdata('usuario');if($usu==false){redirect('/home');}
		$id_usu = $this->session->userdata('id_usuario');
		$id_rol = $this->session->userdata('id_rol');
		$data['mensaje'] = '';
		$data['title1'] = 'Modificar Usuario';
		$data['boton1'] = 'Actualizar';
		$usuario = $this->personModel->get_usu($id_usu10)->row();	
		
		$data['usuario']=$usuario->usuario;
		$data['descripcion_usuario']=$usuario->descripcion_usuario;
		
		$data['email']=$usuario->email;
		$data['rol1']=$usuario->id_rol;
		$data['RolC']=$this->personModel->InputSelect4('tb_rol','Rol','Id_Rol','Flag','1');

		$data['usuario_reg']=$usuario->usuario_reg;
		$data['fecha_reg']=$usuario->fecha_reg;
		$data['usuario_mod']=$usuario->usuario_mod;
		$data['fecha_mod']=$usuario->fecha_mod;
		

		$Aplicaciones = $this->personModel->getTable0('tb_url','Tipo','A')->result();
		$Servicios = $this->personModel->getTable0('tb_url','Tipo','S')->result();
		$data['Aplicaciones'] = $Aplicaciones;
		$data['Servicios'] = $Servicios;


		$UserAplicaciones = $this->personModel->getTable1('tb_rel_usr_url','IdUsuario',$id_usu10,'Tipo','A')->result();	
		$UserServicios = $this->personModel->getTable1('tb_rel_usr_url','IdUsuario',$id_usu10,'Tipo','S')->result();	
		$data['UserAplicaciones'] = $UserAplicaciones;
		$data['UserServicios'] = $UserServicios;

		$data['flag'] = $flag;
		$data['usu'] = $usu;
		$xusu=$this->personModel->get_usu($id_usu)->row();
		$rol=$this->personModel->get_roles_by_id($id_rol)->row();
		$data['rol']=$xusu->descripcion_usuario.'-'.$rol->Rol;
		$data['id_rol'] = $id_rol;
		$this->load->view('updateUsuario', $data);	
	}
	function update_modificar_usuarios($kkk){
		$id_usuario=base64_decode($kkk);
		$data['action'] = site_url('person/save_usuarios/');
		$usu = $this->session->userdata('usuario');if($usu==false){redirect('/home');}
		$id_usu = $this->session->userdata('id_usuario');
		$data['mensaje'] = '';
		$fecha_actual = date('Y-m-d h:i:s');

		$Aplicaciones=$this->personModel->getTable1('tb_rel_usr_url','IdUsuario',$id_usuario,'Tipo','A')->result();
		$Servicios=$this->personModel->getTable1('tb_rel_usr_url','IdUsuario',$id_usuario,'Tipo','S')->result();
		//vaciar tabla que contenga aplicaciones
		$this->personModel->deleteTable1('tb_rel_usr_url','IdUsuario',$id_usuario,'Tipo','A');
		$this->personModel->deleteTable1('tb_rel_usr_url','IdUsuario',$id_usuario,'Tipo','S');
		//generar ceros para aplicaciones y servicios

		$AplicacionesPost=$this->input->post('Aplicaciones');
		$ServiciosPost=$this->input->post('Servicios');
		for($i=0;$i<count($AplicacionesPost);$i++){
			$datos = array('IdUsuario' => $id_usuario,						
						'IdUrl' => $AplicacionesPost[$i],
						'Tipo' => 'A'
					);
			$this->personModel->saveTable('tb_rel_usr_url',$datos);			
		}
		for($i=0;$i<count($ServiciosPost);$i++){
			$datos = array('IdUsuario' => $id_usuario,						
						'IdUrl' => $ServiciosPost[$i],
						'Tipo' => 'S'
					);
			$this->personModel->saveTable('tb_rel_usr_url',$datos);			
		}


		$dato = array('usuario' => $this->input->post('login'),						
						'descripcion_usuario' => $this->input->post('descripcion_usuario'),
						'email' => $this->input->post('email'),
						'id_rol' => $this->input->post('Rol'),
						'usuario_mod' => $usu,
						'fecha_mod' => $fecha_actual						
					);
		$this->personModel->update_usuarios($id_usuario,$dato);

		$dato = array('usuario' => $this->input->post('login'),
						
						'descripcion_usuario' => $this->input->post('descripcion_usuario'),
						'email' => $this->input->post('email'),
						'id_rol' => $this->input->post('Rol'),
						'usuario_mod' => $usu,
						'fecha_mod' => $fecha_actual						
					);
		$this->personModel->update_usuarios($id_usuario,$dato);
		redirect('/person/updateUsuario/'.base64_encode("$id_usuario").'/'.base64_encode("1"), 'refresh');
	}	
	function listUrl($kkk){
			$flag=base64_decode($kkk);
			$data['action'] = '';
			$usu = $this->session->userdata('usuario');if($usu==false){redirect('/home');}
			$id_rol = $this->session->userdata('id_rol');
			$id_usu = $this->session->userdata('id_usuario');
			$data['mensaje'] = '';	

			// generate table data
			$this->table->clear();
			$this->load->library('table');
			$this->table->set_empty("&nbsp;");
			$this->table->set_heading('<p>Tipo</p>', '<p>Descripcion</p>',  '<p>Url</p>','<p>Estado</p>','<p>Acción</p>');

			$Url = $this->personModel->getTable('tb_url')->result(); 
			foreach ($Url as $a){
				$Tipo="Aplicaciones";
				if($a->Tipo=='S'){
					$Tipo="Servicios";
				}
				if($a->Estado=='1'){
					$estado=anchor('person/actdesactUrl/'.base64_encode("$a->Id_Url"),'<img src="../../../theme/lib/img/chekbox_on.png">');			}
				if($a->Estado=='0'){
					$estado=anchor('person/actdesactUrl/'.base64_encode("$a->Id_Url"),'<img src="../../../theme/lib/img/chekbox_off.png">');			}


					$this->table->add_row($Tipo, $a->Descripcion, $a->Url,  $estado,
					anchor('person/updateUrl/'.base64_encode("$a->Id_Url").'/'.base64_encode("0"),'<img src="../../../theme/lib/img/update.png">'.' Modificar').' '.
						anchor('person/updateUrl/'.base64_encode("$a->Id_Url").'/'.base64_encode("0"),'<img src="../../../theme/themeblue/files/imgOffClosed.png">'.' Asignar')					
					
					);

				
			}
			$data['table2'] = $this->table->generate();

			$data['add'] = anchor('person/addUrl','<img src="../../../theme/lib/img/add.png"> Agregar Nuevo Usuario');
			$data['flag'] = $flag;
			$data['info_mensaje'] = '<div class="alert alert-info">'.
									'<button type="button" class="close" data-dismiss="alert">x</button>'.
									'Usuario Añadido Correctamente.'.
									'</div>';
			$data['title'] = "Lista de Aplicaciones y Servicios";
			$data['usu'] = $usu;
			$xusu=$this->personModel->get_usu($id_usu)->row();
			$rol=$this->personModel->get_roles_by_id($id_rol)->row();
			$data['rol']=$xusu->descripcion_usuario.'-'.$rol->Rol;
			$data['id_rol'] = $id_rol;
			$this->load->view('listUsuarios', $data);
	}
	function actdesactUrl($kkk){
		$id_url=base64_decode($kkk);
		$usuario = $this->personModel->getTable0('tb_url','Id_Url',$id_url)->row();		
		if($usuario->Estado=='1'){//echo $id_url;
			$dato = array('Estado' => '0');
		}
		if($usuario->Estado=='0'){ 
			$dato = array('Estado' => '1');
		}
		$this->personModel->updateTable('tb_url',$dato,'Id_Url',$id_url);
		redirect('person/listUrl/'.base64_encode("0"),'refresh');
	}
	function addUrl(){
		$data['action'] = site_url('person/saveUrl/');
		$usu = $this->session->userdata('usuario');if($usu==false){redirect('/home');}
		$id_usu = $this->session->userdata('id_usuario');
		$id_rol = $this->session->userdata('id_rol');
		$data['mensaje'] = '';
		$data['Url']='';
		$data['Descripcion']='';
		$data['Referencia']='';
		
		$data['Imagen']='';
		$data['Tipo1']='';


		$data['UserAplicaciones'] = '';
		$data['UserServicios'] = '';

		$data['usuario_reg'] = '';
		$data['fecha_reg'] = '';
		$data['usuario_mod'] = '';		
		$data['fecha_mod'] = '';

		$data['Tipo']=array(
					        ''         => 'Seleccione un elemento...',
					        'S'           => 'Servicio',
					        'A'         => 'Aplicacion'
						);




		$data['usu'] = $usu;
		$data['flag'] = '0';
		$data['title1'] = 'Agregar Url';
		$data['boton1'] = 'Agregar';
		$xusu=$this->personModel->get_usu($id_usu)->row();
		$rol=$this->personModel->get_roles_by_id($id_rol)->row();
		$data['rol']=$xusu->descripcion_usuario.'-'.$rol->Rol;
		$data['id_rol'] = $id_rol;
		$this->load->view('updateUrl', $data);
	}
	function saveUrl(){
		$data['action'] = site_url('person/save_usuarios/');
		$fecha_actual = date('Y-m-d h:i:s');
		$fecha=date("d-m-Y");
		$usu = $this->session->userdata('usuario');if($usu==false){redirect('/home');}
		$id_usu = $this->session->userdata('id_usuario');
		$data['mensaje'] = '';

		$person = array('Descripcion' => $this->input->post('Descripcion'),
						'Url' => $this->input->post('Url'),
						'Tipo' => $this->input->post('Tipo'),
						'Referencia' => $this->input->post('Referencia'),
						'usuario_reg' => $usu,
						'fecha_reg' => $fecha_actual,
						'estado' => '1'
					);
		$ID=$this->personModel->saveTable('tb_url',$person);


		if(!file_exists("uploads/$usu")){mkdir("uploads/$usu");}
		$ext = explode("/",$_FILES["Imagen"]["type"]);
		if($_FILES["Imagen"]["name"]){			
			$persons = $_FILES["Imagen"]["name"];
				$datos_adj = array(	'Imagen' => $this->input->post('Descripcion').".".$ext[1]);
				$this->personModel->updateTable('tb_url',$datos_adj,'Id_Url',$ID);
				move_uploaded_file($_FILES["Imagen"]["tmp_name"], "uploads/$usu/".$this->input->post('Descripcion').".".$ext[1]);
		}



		$data['nivel']='admin';
		redirect('/person/listUrl/'.base64_encode("1"), 'refresh');
	}
	function updateUrl($kkk1,$kkk2){
		$Id_Url=base64_decode($kkk1);
		$flag=base64_decode($kkk2);
		$data['action'] = site_url('person/updateUrl2/'.base64_encode("$Id_Url"));
		$usu = $this->session->userdata('usuario');if($usu==false){redirect('/home');}
		$id_usu = $this->session->userdata('id_usuario');
		$id_rol = $this->session->userdata('id_rol');
		$data['mensaje'] = 'Url Modificado Correctamente';
		$data['title1'] = 'Modificar Url';
		$data['boton1'] = 'Actualizar';
		$tb_url = $this->personModel->getTable0('tb_url','Id_Url',$Id_Url)->row();	
		
		$data['Descripcion']=$tb_url->Descripcion;
		$data['Referencia']=$tb_url->Referencia;
		$data['Url']=$tb_url->Url;
		
		$data['Imagen']=$tb_url->Imagen;
		$data['Tipo1']=$tb_url->Tipo;
		$data['Tipo']=array(
					        ''         => 'Seleccione un elemento...',
					        'S'           => 'Servicio',
					        'A'         => 'Aplicacion'
						);

		$data['usuario_reg']=$tb_url->usuario_reg;
		$data['fecha_reg']=$tb_url->fecha_reg;
		$data['usuario_mod']=$tb_url->usuario_mod;
		$data['fecha_mod']=$tb_url->fecha_mod;
		


		$data['flag'] = $flag;
		$data['usu'] = $usu;
		$xusu=$this->personModel->get_usu($id_usu)->row();
		$rol=$this->personModel->get_roles_by_id($id_rol)->row();
		$data['rol']=$xusu->descripcion_usuario.'-'.$rol->Rol;
		$data['id_rol'] = $id_rol;
		$this->load->view('updateUrl', $data);	
	}
	function updateUrl2($kkk){
		$Id_Url=base64_decode($kkk);
		$data['action'] = site_url('person/save_usuarios/');
		$usu = $this->session->userdata('usuario');if($usu==false){redirect('/home');}
		$id_usu = $this->session->userdata('id_usuario');
		$data['mensaje'] = '';
		$fecha_actual = date('Y-m-d h:i:s');

		$Aplicaciones=$this->personModel->getTable1('tb_rel_usr_url','IdUsuario',$Id_Url,'Tipo','A')->result();
		$Servicios=$this->personModel->getTable1('tb_rel_usr_url','IdUsuario',$Id_Url,'Tipo','S')->result();
		//vaciar tabla que contenga aplicaciones
		$this->personModel->deleteTable1('tb_rel_usr_url','IdUsuario',$Id_Url,'Tipo','A');
		$this->personModel->deleteTable1('tb_rel_usr_url','IdUsuario',$Id_Url,'Tipo','S');
		//generar ceros para aplicaciones y servicios

		$person = array('Descripcion' => $this->input->post('Descripcion'),
						'Url' => $this->input->post('Url'),
						'Tipo' => $this->input->post('Tipo'),
						//'Imagen' => $this->input->post('Imagen'),
						'Referencia' => $this->input->post('Referencia'),
						'usuario_reg' => $usu,
						'fecha_reg' => $fecha_actual
					);
		$this->personModel->updateTable('tb_url',$person,'Id_Url',$Id_Url);




		if(!file_exists("uploads/$usu")){mkdir("uploads/$usu");}
		$ext = explode("/",$_FILES["Imagen"]["type"]);
		if($_FILES["Imagen"]["name"]){			
			$persons = $_FILES["Imagen"]["name"];
				$datos_adj = array(	'Imagen' => $this->input->post('Descripcion').".".$ext[1]);
				$this->personModel->updateTable('tb_url',$datos_adj,'Id_Url',$Id_Url);
				move_uploaded_file($_FILES["Imagen"]["tmp_name"], "uploads/$usu/".$this->input->post('Descripcion').".".$ext[1]);
		}

		redirect('/person/updateUrl/'.base64_encode("$Id_Url").'/'.base64_encode("1"), 'refresh');
	}		
	function tarjeta(){ /* by neko*/
		//$estado=$kkk;  //si es operador o si es analista fiscalizador llega 'E', si es jefe fiscalizador llega 'P', si es admin llega '0'
		//echo $estado;
		$usu = $this->session->userdata('usuario'); if($usu==false){ redirect('/home'); } 
		
		$id_usu = $this->session->userdata('id_usuario');
		$id_rol = $this->session->userdata('id_rol');
		
		$tarjeta = $this->personModel->get_tarjeta($id_usu)->row_array(); // rescato el registro de la tarjeta del usuario que se esta logueando
		
		$coor_str = $tarjeta['coordenadas']; // rescato las coordenadas delimitadas por |
		$coor_array = explode('|',$coor_str); //convierto a array con la posisión original de la matriz de dos dimensiones por puesto
		
		$cd1 = mt_rand(0,79); //de las 80 posiciones de la tarjeta elijo tres al azar
		$cd2 = mt_rand(0,79); 
		$cd3 = mt_rand(0,79); 
		
		$coordenada1 = explode('-',$coor_array[$cd1]);
		$cd_aux1 = explode(':',$coordenada1[1]);
		$cd_fila1 = $cd_aux1[0];
		$cd_colu1 = $cd_aux1[1]; //print_r($coordenada1);print_r($valor1);print_r(' '.$cd_fila1);print_r(' '.$cd_colu1);die;
		switch ($cd_colu1) {
	    case '1':
	        $cd_colu1 = 'A';
	        break;
	    case '2':
	        $cd_colu1 = 'B';
	        break;
	    case '3':
	        $cd_colu1 = 'C';
	        break;
	    case '4':
	        $cd_colu1 = 'D';
	        break;
	    case '5':
	        $cd_colu1 = 'E';
	        break;
	    case '6':
	        $cd_colu1 = 'F';
	        break;
	    case '7':
	        $cd_colu1 = 'G';
	        break;
	    case '8':
	        $cd_colu1 = 'H';
	        break;
	    case '9':
	        $cd_colu1 = 'I';
	        break;
	    case '10':
	        $cd_colu1 = 'J';
	        break;
		}
		
		$coordenada2 = explode('-',$coor_array[$cd2]);
		$cd_aux2 = explode(':',$coordenada2[1]);
		$cd_fila2 = $cd_aux2[0];
		$cd_colu2= $cd_aux2[1];    //print_r($coordenada2);print_r($valor2);print_r(' '.$cd_fila2);print_r(' '.$cd_colu2);die;
		switch ($cd_colu2) {
	    case '1':
	        $cd_colu2 = 'A';
	        break;
	    case '2':
	        $cd_colu2 = 'B';
	        break;
	    case '3':
	        $cd_colu2 = 'C';
	        break;
	    case '4':
	        $cd_colu2 = 'D';
	        break;
	    case '5':
	        $cd_colu2 = 'E';
	        break;
	    case '6':
	        $cd_colu2 = 'F';
	        break;
	    case '7':
	        $cd_colu2 = 'G';
	        break;
	    case '8':
	        $cd_colu2 = 'H';
	        break;
	    case '9':
	        $cd_colu2 = 'I';
	        break;
	    case '10':
	        $cd_colu2 = 'J';
	        break;
		}
		
		$coordenada3 = explode('-',$coor_array[$cd3]);
		$cd_aux3 = explode(':',$coordenada3[1]);
		$cd_fila3 = $cd_aux3[0];
		$cd_colu3 = $cd_aux3[1];  //print_r($cd3);print_r($coordenada3);print_r($valor3);print_r(' '.$cd_fila3);print_r(' '.$cd_colu3);die;
		switch ($cd_colu3) {
	    case '1':
	        $cd_colu3 = 'A';
	        break;
	    case '2':
	        $cd_colu3 = 'B';
	        break;
	    case '3':
	        $cd_colu3 = 'C';
	        break;
	    case '4':
	        $cd_colu3 = 'D';
	        break;
	    case '5':
	        $cd_colu3 = 'E';
	        break;
	    case '6':
	        $cd_colu3 = 'F';
	        break;
	    case '7':
	        $cd_colu3 = 'G';
	        break;
	    case '8':
	        $cd_colu3 = 'H';
	        break;
	    case '9':
	        $cd_colu3 = 'I';
	        break;
	    case '10':
	        $cd_colu3 = 'J';
	        break;
		}
		
		//$data['estado'] = $estado; 
		$data['f1'] = $cd_fila1;
		$data['c1'] = $cd_colu1;
		$data['f2'] = $cd_fila2;
		$data['c2'] = $cd_colu2;
		$data['f3'] = $cd_fila3;
		$data['c3'] = $cd_colu3;
		
		$this->load->view('tarjeta_view', $data);
		
	}
	function verifica_tarjeta($f1,$c1,$f2,$c2,$f3,$c3){ /* by neko*/
		//echo $estado;die;
		
		$usu = $this->session->userdata('usuario');
		if($usu==false){
			redirect('/home');
		} 
		
		$id_usu = $this->session->userdata('id_usuario'); 
		$valor1 = $this->input->post('coor1'); // rescato las coordenadas ingresadas
		$valor2 = $this->input->post('coor2');
		$valor3 = $this->input->post('coor3');
		
		// convierto la letra a numero de columna
		switch ($c1) {
	    case 'A':
	        $c1 = '1';
	        break;
	    case 'B':
	        $c1 = '2';
	        break;
	    case 'C':
	        $c1 = '3';
	        break;
	    case 'D':
	        $c1 = '4';
	        break;
	    case 'E':
	        $c1 = '5';
	        break;
	    case 'F':
	        $c1 = '6';
	        break;
	    case 'G':
	        $c1 = '7';
	        break;
	    case 'H':
	        $c1 = '8';
	        break;
	    case 'I':
	        $c1 = '9';
	        break;
	    case 'J':
	        $c1 = '10';
	        break;
		}
		// genero la coordenada en el formato almacenado
		$coordenada1 = $f1.':'.$c1;
		
		switch ($c2) {
	    case 'A':
	        $c2 = '1';
	        break;
	    case 'B':
	        $c2 = '2';
	        break;
	    case 'C':
	        $c2 = '3';
	        break;
	    case 'D':
	        $c2 = '4';
	        break;
	    case 'E':
	        $c2 = '5';
	        break;
	    case 'F':
	        $c2 = '6';
	        break;
	    case 'G':
	        $c2 = '7';
	        break;
	    case 'H':
	        $c2 = '8';
	        break;
	    case 'I':
	        $c2 = '9';
	        break;
	    case 'J':
	        $c2 = '10';
	        break;
		}
		// genero la coordenada en el formato almacenado
		$coordenada2 = $f2.':'.$c2;
		
		switch ($c3) {
	    case 'A':
	        $c3 = '1';
	        break;
	    case 'B':
	        $c3 = '2';
	        break;
	    case 'C':
	        $c3 = '3';
	        break;
	    case 'D':
	        $c3 = '4';
	        break;
	    case 'E':
	        $c3 = '5';
	        break;
	    case 'F':
	        $c3 = '6';
	        break;
	    case 'G':
	        $c3 = '7';
	        break;
	    case 'H':
	        $c3 = '8';
	        break;
	    case 'I':
	        $c3 = '9';
	        break;
	    case 'J':
	        $c3 = '10';
	        break;
		}
		// genero la coordenada en el formato almacenado
		$coordenada3 = $f3.':'.$c3;
		
		//rescato la tarjeta de la bd
		$tarjeta = $this->personModel->get_tarjeta($id_usu)->row_array(); // rescato el registro de la tarjeta del usuario que se esta logueando
		$coor_str = $tarjeta['coordenadas']; 
		$coor_array = explode('|',$coor_str);
		
		$error = 1; // si alguna coordenada no coincide se captura como error
		//echo var_dump($coor_array);
		if(in_array($valor1.'-'.$coordenada1,$coor_array)){
			$error = 0;	
		}else{
			$error = 1;
		}
		if(in_array($valor2.'-'.$coordenada2,$coor_array)){
			$error = 0;	
		}else{
			$error = 1;
		}
		if(in_array($valor3.'-'.$coordenada3,$coor_array)){
			$error = 0;	
		}else{
			$error = 1;
		}
		//echo $error;
		//si no existe error redirecciono a la bandeja de sistema sino afuera del sistema
		if($error == 0){
				redirect('person/index','refresh');
		}else{
			redirect('home/loginError');	
		}
		
	}	
	function genera_tarjeta($kkk){  /*genero las coordenadas de la tarjeta como usuario admin by neko*/
		$id_usu=base64_decode($kkk); // el id_usuario seleccionado
		//echo $id_usu;
				
		$fecha_actual = date('Y-m-d');
		$usu = $this->session->userdata('usuario');
		if($usu==false){redirect('/home');}
		
		$usuario = $this->personModel->get_usu($id_usu)->row_array(); 
		
		$cod_tarje = base64_encode($usuario['usuario'].'.'.$id_usu);  //print_r($cod_tarje);die;
		
			
		for($c = 1;$c <= 8;$c++){
			for($k = 1;$k <= 10;$k++){
				$coor[$c][$k] = mt_rand(1,99);
			}	
		}
		//print_r($coor);
		$coordenadas = '';
		for($c = 1;$c <= 8;$c++){
			for($k = 1;$k <= 10;$k++){
				$coordenadas = $coordenadas.$coor[$c][$k].'-'.$c.':'.$k.'|';
			}	
		}
		//print_r($coordenadas);die;

		$tarjeta = array('id_usuario' => $id_usu,
						'cod_tarjeta' => $cod_tarje,
						'coordenadas' => $coordenadas,
						'fecha_cre' => $fecha_actual,
						'fecha_mod' => '',
					);
		
		//rescato la tarjeta de la bd  
		$existe = $this->personModel->get_tarjeta($id_usu)->row_array(); // rescato el registro de la tarjeta del usuario que se esta logueando	
		if(count($existe)>0){
			$tarjeta['fecha_cre'] = $existe['fecha_cre'];
			$tarjeta['fecha_mod'] = date('Y-m-d');
			$this->personModel->update_tarjeta($tarjeta,$id_usu); 			
		}else{
			$this->personModel->save_tarjeta($tarjeta);	
		}
		
		redirect('/person/listUsuario/'.base64_encode("1"), 'refresh');
		
		//$this->load->view('tarjeta_view', $data);
		
	}



	
	function imprime_tarjeta($kkk){  /*imprimo las coordenadas de la tarjeta como usuario admin by neko*/
		
		$id_usu=base64_decode($kkk); // el id_usuario seleccionado
		//echo $id_usu;
				
		$usu = $this->session->userdata('usuario'); 
		if($usu==false){redirect('/home');}
		
		//rescato el usuario operador
		$usuario = $this->personModel->get_usu($id_usu)->row_array(); 
		$nombre_usuario = $usuario['descripcion_usuario'];
		$direccion = $usuario['direccion'];
		$ciudad = $usuario['ciudad'];
		$login = $usuario['usuario'];
		
		//rescato la tarjeta de la bd  
		$tarjeta = $this->personModel->get_tarjeta($id_usu)->row_array(); // rescato el registro de la tarjeta del usuario que se esta logueando	
		$cod_tarjeta = $tarjeta['cod_tarjeta'];
		$fecha_creacion = $tarjeta['fecha_cre'];
		
		//genera el qr a colocar en el pdf
		if(!file_exists("uploads/$login")){mkdir("uploads/$login");}
        include 'codigoqr/qrlib.php';
        QRcode::png($login.'..'.$direccion.'..'.$cod_tarjeta.'..'.$fecha_creacion, "uploads/$login/".'archivo.png'); // creates file
		
		
		$coordenadas = explode('|',$tarjeta['coordenadas']); 
		
		$j = 0;
		$jj = 1;
		for($c = 0;$c <= 7;$c++){
			for($k = 0;$k <= 9;$k++){
				$aux = explode('-',$coordenadas[$j]);
				$coor[$c][$k] = $aux[0];
				$j++;
			}	
			array_unshift($coor[$c], $jj);
			$jj++;
		}
		//print_r($coor);die;
		//-------------PDF
		$this->fpdf->AddPage('P','Letter');
		$this->fpdf->SetMargins(25, 25);
		/*** cabecera ***/
		$this->fpdf->Image('theme/att_pdf.jpg',25,25,0);
		$this->fpdf->SetFont('Arial','B',15);
		$this->fpdf->Cell(80);
		$this->fpdf->Ln(20);
		$this->fpdf->Cell(0,10,'Tarjeta de Coordenadas de Operador',0,0,'C');
		$this->fpdf->Ln(20);
		$this->fpdf->SetFont('','',10);
		$this->fpdf->Cell(0,10,utf8_decode('Nombre del Operador:   '.$nombre_usuario),0,0,'L');
		$this->fpdf->Ln(5);
		$this->fpdf->Cell(0,10,utf8_decode('Código de Tarjeta:   '.$cod_tarjeta),0,0,'L');
		$this->fpdf->Ln(5);
		$this->fpdf->Cell(0,10,utf8_decode('Dirección:   '.$direccion.' - '.$ciudad),0,0,'L');
		$this->fpdf->Ln(20);
		$this->fpdf->SetFont('','',10);
		$this->fpdf->Multicell(0,5,utf8_decode('Esta tarjeta le sirve para ingresar al Sistema de Generación de Interrupciones SIGEINT, en ella encontrara coordenadas como por ejemplo 1A, 8H, etc. El sistema le pedira que ingrese los valores de las coordenadas para autentificar que efectivamente se trata de un usuario válido para registrar Interrupciones.'),0);
	  $this->fpdf->Ln(10);
	  $this->fpdf->Multicell(0,5,utf8_decode('Este documento es INSTRANFERIBLE y solo debe ser de conocimiento del responsable designado por el Operador para el ingreso al sistema de la ATT. Cualquier uso indevido de este documento oficial será sancionado bajo bajo la normativa vigente amparada en la Ley N° 1178 que rige en nuestro país.'),0);
		$this->fpdf->Ln(20);
		/*** cuerpo ***/
		$this->fpdf->AliasNbPages();
		$this->fpdf->SetFont('Times','',12);
		    
		// Anchuras de las columnas
	    $w = array(7, 7, 7, 7, 7, 7, 7, 7, 7, 7, 7);
	    // Títulos de las columnas
			$header = array('/','A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
			// Carga de datos
	    $data = $coor;
	    
	    // definiendo el fondo de las celdas
	    $this->fpdf->SetFillColor(230, 230, 250);
	    //negrita y cursiva al texto y fuente mas grande
	    $this->fpdf->SetFont('','BI',15);
	    $this->fpdf->Cell(35); //para centrar la tabla en la hoja
	    for($i=0;$i<count($header);$i++)
	        $this->fpdf->Cell($w[$i],7,$header[$i],1,0,'C',true);
	    $this->fpdf->Ln();
	    // Datos
	    foreach($data as $row)
	    {
	        $this->fpdf->SetFont('','BI',9); // negrita y cursiva a la primera columna y fuente mas grande
	        $this->fpdf->Cell(35); //para centrar la tabla en la hoja
	        $this->fpdf->Cell($w[0],4,$row[0],'LR',0,'L',true);
	        $this->fpdf->SetFont('','',8); // quito negrita y cursiva
	        $this->fpdf->Cell($w[1],4,$row[1],'LR',0,'C');
	        $this->fpdf->Cell($w[2],4,$row[2],'LR',0,'C');
	        $this->fpdf->Cell($w[3],4,$row[3],'LR',0,'C');
	        $this->fpdf->Cell($w[4],4,$row[4],'LR',0,'C');
	        $this->fpdf->Cell($w[5],4,$row[5],'LR',0,'C');
	        $this->fpdf->Cell($w[6],4,$row[6],'LR',0,'C');
	        $this->fpdf->Cell($w[7],4,$row[7],'LR',0,'C');
	        $this->fpdf->Cell($w[8],4,$row[8],'LR',0,'C');
	        $this->fpdf->Cell($w[9],4,$row[9],'LR',0,'C');
	        $this->fpdf->Cell($w[10],4,$row[10],'LR',0,'C');
	        $this->fpdf->Ln();
	    }
	    // Línea de cierre
	    $this->fpdf->Cell(35); //para centrar la tabla en la hoja
	    $this->fpdf->Cell(array_sum($w),0,'','T');// dibuja la linea de abajo de la tabla
	    
	    $this->fpdf->Ln(60);
	    $this->fpdf->Image('uploads/'.$login.'/archivo.png',163,215,0);
	    $this->fpdf->SetFont('','BI',10);
			$this->fpdf->Cell(0,10,utf8_decode('Autoridad de Fiscalización y Regulación de Telecomunicaciones y Transportes.'),0,0,'R');
	    
			$this->fpdf->Output();
			
		
		
		//redirect('/person/buscar_usuarios/'.base64_encode("1"), 'refresh');
		
		$this->load->view('imprime_tarjeta', $data);
		
	}	

function mostrarArea($p){
	//echo $p;
		if($p!='0'){ 
			$Area = $this->personModel->InputSelect1('tb_frecuencia','AreaCobertura','Departamento',$p);			
		}
		else{	
			$Area = array('0'=>'Todos');
		}
		echo form_dropdown('Area', $Area,'','');
	}

	
	//---------------------------------------------------------------------------
	//---------------------------------------------------------------------------
	function formularios(){
		$usu = $this->session->userdata('usuario');
		$id_operador = $this->session->userdata('nivel');
		$data['usuario'] = $usu;
		$data['nivel'] = $id_operador;
		$fecha=date("d-m-Y");
		$hora=date("h:i:s");
		$data['fecha'] = $fecha;
		$data['action'] = site_url('person/form_paso2');

		$datos_operador = $this->personModel->obt_operador_all($id_operador)->row();
		$data['tipo_ope'] = $datos_operador->TipoOperador;
		$data['name_ope'] = $datos_operador->NomComercialOperador;
		$this->load->view('form', $data);
	}







}
?>