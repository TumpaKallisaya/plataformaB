<?php
class PersonModel extends Model {
	
	private $notificaciones= 'notificaciones';
	private $tbl_adjuntos= 'tbl_adjuntos';
	private $tbl_mensajes= 'tbl_mensajes';
	private $usuarios= 'usuarios';
	private $ci_sessions= 'ci_sessions';

	private $oper_form= 'oper_form';
	private $control_envio= 'control_envio';
	private $f212ldni_anual_nac= 'f212ldni_anual_nac';
	private $f212ldni_gestion_nac= 'f212ldni_gestion_nac';
	private $deven= 'deven';
	private $deven_mod= 'deven_mod';
	private $cuenta= 'cuenta';
	private $devengados= 'devengados';
	private $control_pagos= 'control_pagos';
	private $desglose= 'desglose';
	private $ope='operador';
	private $operador='operador';
	private $multas='multas';
	private $servicio='servicio';
	private $cambio_multa='cambio_multa';
	private $otorgaciones='otorgaciones';
	private $prametro_duf='prametro_duf';
	private $f212lndi='f212lndi';
	private $gestione_nacional='gestione_nacional';
	private $gestione_internac='gestione_internac';

	 

	const DEVEN = 'devengados';
	const ELEM = 'Seleccione un elemento';
	const TABLA_REGISTROS = 'concepto_pago';
	const TABLA_CUENTA = 'cuenta';
	const TABLA_SERVICIOS = 'servicio';
	const TABLA_OPERADOR = 'operador';
	const TABLA_OTORGACION = 'otorgaciones';
	const RAZONSOCIAL = 'RazonSocial';
	const ID_OPERADOR = 'Id_Operador';
	const ID = 'Id_Pago';
	const NUMERO_CUENTA = 'Descripcion_Pago';
	const NRO_CUENTA = 'Numero_cuenta';
	const CUENTA = 'Descripcion_cuenta';
	const GESTION = 'Gestion';
	const TABLA_GESTION = 'gestion';
	const TABLA_GESTION_IDEN = 'gestion_ident';
	const ID_GESTION = 'Id_Gestion';
	const TABLA_MULTAS = 'multas';
	const COD_MULTAS = 'Cod_Multa';
	const COD_SERVICIO = 'Codigo';
	const ESTADO = 'Estado';
	const SECTOR = 'Sector';
	const TIPO_RED = 'Tipo_Red';
	const SERVICIO_OTOR = 'Servicio';
	const DUF_IMPORTE = 'DUF_Importe';
	//------------------------------------------------------------------------
	//-------------------   Superadmin        --------------------------------
	//------------------------------------------------------------------------
	function notif_all(){
		return $this->db->get($this->notificaciones);
	}
	function notif_all_analista($usuario){
		$this->db->like('Usuario_Reg', $usuario);
		return $this->db->get($this->notificaciones);
	}
	//------------------------------------------------------------------------
	function guardar_log($datos_logs){
		$this->db->insert($this->ci_sessions, $datos_logs);
		return $this->db->insert_id();
	}

	function existencia_mensaje($Id_Notificacion){
		$this->db->where('Id_Notificacion', $Id_Notificacion);
		$query=$this->db->get($this->tbl_mensajes);
		return $query->num_rows(); 
	}

	function get_all_operador(){
		return $this->db->get($this->operador);
	}

	function get_total_ope(){
		$query=$this->db->get($this->operador);
		return $query->num_rows(); 
	}

	function save_usuario($valores){
		$this->db->insert($this->usuarios, $valores);
		return $this->db->insert_id();
	}

	function id_usuario($id){
		$this->db->where('id', $id);
		return $this->db->get($this->usuarios);
	}

	function update_usuario($id, $person){
		$this->db->where('id', $id);
		$this->db->update($this->usuarios, $person);
	}
	
	function get_by_word($palabras){
		$this->db->like('Id_Operador', $palabras);
		$this->db->or_like('RazonSocial', $palabras);
		return $this->db->get($this->operador);
	}

	function update_visualiza($person, $id){
		$this->db->where('Id_Notificacion', $id);
		$this->db->update($this->notificaciones, $person);
	}
	function update_visualiza_ope($person, $id){
		$this->db->where('Id_Operador', $id);
		$this->db->update($this->notificaciones, $person);
	}
	function guardar_form_notif($datos_form){
		$this->db->insert($this->notificaciones, $datos_form);
		return $this->db->insert_id();
	}
	function guardar_mail($datos_mail){
		$this->db->insert($this->tbl_mensajes, $datos_mail);
		return $this->db->insert_id();
	}
	function update_mensajes($datos_mail, $Id_Mensaje){
		$this->db->where('Id_Mensaje', $Id_Mensaje);
		$this->db->update($this->tbl_mensajes, $datos_mail);
	}

	function guardar_adjuntos($datos_adj){
		$this->db->insert($this->tbl_adjuntos, $datos_adj);
		return $this->db->insert_id();
	}

	function get_adjuntos($Id_Notificacion){
		$this->db->where('Id_Notificacion', $Id_Notificacion);
		return $this->db->get($this->tbl_adjuntos);
	}
	function get_notificaciones($Id_Notificacion){
		$this->db->where('Id_Notificacion', $Id_Notificacion);
		return $this->db->get($this->notificaciones);
	}
	function get_usuario_ope($operador){
		$this->db->where('nivel', $operador);
		return $this->db->get($this->usuarios);
	}
	function get_usuario($usu){
		$this->db->where('usuario', $usu);
		return $this->db->get($this->usuarios);
	}
	function get_notificado2($id_operador){
		$this->db->order_by('Fecha_Reg','desc');
		$this->db->where('Id_Operador', $id_operador);
		return $this->db->get($this->notificaciones);
	}
	function get_notificado_usu($usuario){
		//$paso=$usuario;
		/*$this->db->like('Usuario_Reg', $usuario);
		$this->db->like('Visualizado', '2');
		$this->db->like('Visualizado', '3');
		return $this->db->get($this->notificaciones);*/
		return $this->db->query("SELECT * FROM notificaciones WHERE (Usuario_Reg='$usuario') and (Visualizado='2' or Visualizado='3' )");
	}

	function obt_notificado_usu($usuario){
		$this->db->like('Usuario_Reg', $usuario);
		return $this->db->get($this->notificaciones);
	}

	function obt_notif_usu($usuario, $id_operador, $Nro_Acto_Admin){
		if ($id_operador != '0'){
			$this->db->where('Id_Operador', $id_operador);
		}
		if ($Nro_Acto_Admin != ''){
			$this->db->where('Nro_Acto_Adm', $Nro_Acto_Admin);
		}
		$this->db->like('Usuario_Reg', $usuario);
		return $this->db->get($this->notificaciones);
	}

	function get_recibido_usu($usuario){
		$this->db->like('Usuario_Reg', $usuario);
		$this->db->where('Visualizado', '1');
		return $this->db->get($this->notificaciones);
	}

	function get_mail_usuario($usuario){
		$this->db->where('Usuario_Mensaje', $usuario);
		$this->db->where('Estado', '1');
		return $this->db->get($this->tbl_mensajes);
	}

	function get_mail_usuario2($usuario){
		$this->db->where('Usuario_Mensaje', $usuario);
		$this->db->where('Estado', '2');
		return $this->db->get($this->tbl_mensajes);
	}

	function get_mail_ope_res($operador){
		$this->db->where('Id_Operador', $operador);
		$this->db->where('Estado', '2');
		return $this->db->get($this->tbl_mensajes);
	}
	function get_mail_usuario_all($Id_Mensaje){
		$this->db->where('Id_Mensaje', $Id_Mensaje);
		return $this->db->get($this->tbl_mensajes);
	}
	function get_mail_notif_all($Id_Notificacion){
		$this->db->where('Id_Notificacion', $Id_Notificacion);
		return $this->db->get($this->tbl_mensajes);
	}
	function get_notif_send($usuario){
		$this->db->like('Usuario_Reg', $usuario);
		$this->db->where('Visualizado', '1');
		return $this->db->count_all_results($this->notificaciones);
	}
	function get_notif_send2($usuario){
		//$paso = $usuario;
		/*$this->db->like('Usuario_Reg', $usuario);
		$this->db->like('Visualizado', '2');
		$this->db->like('Visualizado', '3');
		return $this->db->count_all_results($this->notificaciones);*/
		$query = $this->db->query("SELECT * FROM notificaciones WHERE (Usuario_Reg='$usuario') and (Visualizado='2' or Visualizado='3' )");
		return $query->num_rows();
	}


	function get_notificado($id_operador, $Nro_Acto_Admin){
		if ($id_operador != '0'){
			$this->db->where('Id_Operador', $id_operador);
		}
		if ($Nro_Acto_Admin != ''){
			$this->db->where('Nro_Acto_Adm', $Nro_Acto_Admin);
		}
		return $this->db->get($this->notificaciones);
	}
	
	function get_notif_ope($id_operador){
		$this->db->where('Visualizado', '1');
		$this->db->where('Id_Operador', $id_operador);
		return $this->db->count_all_results($this->notificaciones);
	}
	function get_mail_usu($usuario){
		$this->db->where('Estado', '1');
		$this->db->where('Usuario_Mensaje', $usuario);
		return $this->db->count_all_results($this->tbl_mensajes);
	}
	function get_mail_usu_rec($usuario){
		$this->db->where('Estado', '2');
		$this->db->where('Usuario_Mensaje', $usuario);
		return $this->db->count_all_results($this->tbl_mensajes);
	}
	function get_mail_ope($operador){
		$this->db->where('Estado', '1');
		$this->db->where('Id_Operador', $operador);
		return $this->db->count_all_results($this->tbl_mensajes);
	}
	function get_mail_ope_usu($operador){
		$this->db->where('nivel', $operador);
		return $this->db->get($this->usuarios);
	}
	function get_mail_ope_usu2($operador){
		$this->db->where('usuario', $operador);
		return $this->db->get($this->usuarios);
	}
	function get_mail_ope_rec($operador){
		$this->db->where('Estado', '2');
		$this->db->where('Id_Operador', $operador);
		return $this->db->count_all_results($this->tbl_mensajes);
	}
	function borrar_dat_f212ldni($Gestion,$mes,$id_operador){
		$this->db->where('Gestion', $Gestion);
		$this->db->where('Mes', $mes);
		$this->db->where('Id_Operador', $id_operador);
		$this->db->delete($this->f212lndi);
	}

	function update_f212ldni_contro($formulario,$Gestion,$id_operador,$personup){
		$this->db->where('Id_Form', $formulario);
		$this->db->where('Gestion', $Gestion);
		$this->db->where('Id_Operador', $id_operador);
		$this->db->update($this->control_envio, $personup);
	}

	function rep_nacional(){
		return $this->db->get($this->gestione_nacional);
	}

	function rep_internac(){
		return $this->db->get($this->gestione_internac);
	}

	function rep_nacional_mes(){
		$this->db->where('Nacional_Internacional', '1');
		return $this->db->get($this->f212lndi);
	}

	function rep_internac_mes(){
		$this->db->where('Nacional_Internacional', '2');
		return $this->db->get($this->f212lndi);
	}
	
	function rep_gestion_ope(){
	
		return $this->db->get($this->f212ldni_gestion_nac);
	}

	function rep_nacional_mes_ope($Id_Operador,$gestion,$mes){
		$this->db->where('Id_Operador', $Id_Operador);
		$this->db->where('Mes', $mes);
		$this->db->where('Gestion', $gestion);
		$this->db->where('Nacional_Internacional', '1');
		return $this->db->get($this->f212lndi);
	}

	function rep_internacional_mes_ope($Id_Operador,$gestion,$mes){
		$this->db->where('Id_Operador', $Id_Operador);
		$this->db->where('Mes', $mes);
		$this->db->where('Gestion', $gestion);
		$this->db->where('Nacional_Internacional', '2');
		return $this->db->get($this->f212lndi);
	}
	function get_operador($Id_Operador){
		$this->db->where('Id_Operador', $Id_Operador);
		return $this->db->get($this->operador);
	}

	function get_formularios($Id_Operador){
	return $this->db->query("SELECT a.Id_Form,a.descripcion,b.frecuencia FROM oper_form a,formularios b WHERE a.Id_Operador=$Id_Operador AND a.Id_Form=b.Id_Form ORDER BY a.Id_Form"); 

	/*	$this->db->where('Id_Operador', $Id_Operador);
		return $this->db->get($this->oper_form);*/
	}

	function get_controlform_anual_nac($Id_Operador){
		$this->db->where('Id_Operador', $Id_Operador);
		
		return $this->db->get($this->f212ldni_anual_nac);
	}

	function get_controlform($Id_Form,$Id_Operador){
		$this->db->where('Id_Operador', $Id_Operador);
		$this->db->where('Id_Form', $Id_Form);
		return $this->db->get($this->control_envio);
	}

	function list_mod_multas2(){
		$this->db->where('Id_Operador', $Id_Operador);
		return $this->db->get($this->cambio_multa);
	}
	function list_mod_multas(){
		$this->db->order_by('Fecha_Reg','asc');
		return $this->db->get($this->cambio_multa);
	}



	function list_otorg(){
		$this->db->order_by('Id_Otorgacion','asc');
		return $this->db->get($this->otorgaciones);
	}


	function get_pago_DUF($Id_Operador,$Id_Pago,$Id_Gestion,$Identificado){

		$this->db->where('Id_Operador', $Id_Operador);
		
		$this->db->where('Id_Pago', $Id_Pago);
		$this->db->where('Id_Gestion', $Id_Gestion);
		$this->db->where('Identificado', $Identificado);
		$this->db->order_by('Fecha_Pago','asc');
		return $this->db->get($this->control_pagos);
	}
	
	function devengado($operador){
		$this->db->where('Codigo_op', $operador);
		return $this->db->get($this->devengados);
	}
	
	function cuenta_deven(){

		return $this->db->count_all($this->prametro_duf);

	}

	function par($cant){
		$this->db->where('Id_PD', $cant);
		return $this->db->get($this->prametro_duf);
	}
	function concepto_select(){

    $query = $this->db->get(self::TABLA_REGISTROS);
    $data = array();
    $data[]='Seleccione un elemento'; //aqui agregamos una opcion sin valor a nuestro select, la cual sera la seleccion por defecto
    if($query->num_rows()>0){
        foreach($query->result_array() as $row){
            $data[$row['Id_Pago']]= $row[self::NUMERO_CUENTA].'---> ['.$row[self::ID].']';
			}
        return $data;
		}
	}

	function Cod_multa_select(){

    $query = $this->db->get(self::TABLA_MULTAS);
    $data = array();
    $data[]='Seleccione un elemento'; //aqui agregamos una opcion sin valor a nuestro select, la cual sera la seleccion por defecto
    if($query->num_rows()>0){
        foreach($query->result_array() as $row){
            $data[$row['Cod_Multa']]= $row[self::COD_MULTAS].'---> ['.$row[self::ID_OPERADOR].']';
			}
        return $data;
		}
	}

	function Cod_Otorga_select_servicio(){

    $query = $this->db->get(self::TABLA_SERVICIOS);
    $data = array();
    $data[]='Seleccione un elemento'; //aqui agregamos una opcion sin valor a nuestro select, la cual sera la seleccion por defecto
    if($query->num_rows()>0){
        foreach($query->result_array() as $row){
            $data[$row['Codigo']]= $row[self::COD_SERVICIO].' ---> ['.$row[self::TIPO_RED].']';
			}
        return $data;
		}
	}


	
    
	
	function operador_select(){
	//$this->db->order_by('RazonSocial','asc');
    $query = $this->db->get(self::TABLA_OPERADOR);
    $data = array();
    $data[]='Seleccionar Operador'; //aqui agregamos una opcion sin valor a nuestro select, la cual sera la seleccion por defecto
    if($query->num_rows()>0){
        foreach($query->result_array() as $row){
            $data[$row['Id_Operador']]= $row[self::ID_OPERADOR].'---> ['.$row[self::RAZONSOCIAL].']';
			}
        return $data;
		}
	}
	

function operador_select_otorga(){
	$Sector='Telecomunicaciones';
	$this->db->where('Sector', $Sector);
    $query = $this->db->get(self::TABLA_OPERADOR);
    $data = array();
    $data[]='Seleccione un elemento'; //aqui agregamos una opcion sin valor a nuestro select, la cual sera la seleccion por defecto
    if($query->num_rows()>0){
        foreach($query->result_array() as $row){
            $data[$row['Id_Operador']]= $row[self::ID_OPERADOR].'---> ['.$row[self::RAZONSOCIAL].']';
			}
        return $data;
		}
	}

	function operador_select_in_multa(){
	$Sector1='MULTA TRP';
	$Sector2='MULTA TEL';
	//$this->db->order_by('RazonSocial','asc');
	 $this->db->not_like('Sector', $Sector1);
	  $this->db->not_like('Sector', $Sector2);
    $query = $this->db->get(self::TABLA_OPERADOR);
    $data = array();
    $data[]='Seleccione un elemento'; //aqui agregamos una opcion sin valor a nuestro select, la cual sera la seleccion por defecto
    if($query->num_rows()>0){
        foreach($query->result_array() as $row){
            $data[$row['Id_Operador']]= $row[self::ID_OPERADOR].'---> ['.$row[self::RAZONSOCIAL].']';
			}
        return $data;
		}
	}
	
	function cuenta_select(){
    $query = $this->db->get(self::TABLA_CUENTA);
    $data = array();
    $data[]='Seleccione un elemento'; //aqui agregamos una opcion sin valor a nuestro select, la cual sera la seleccion por defecto
    if($query->num_rows()>0){
        foreach($query->result_array() as $row){
            $data[$row['Numero_cuenta']]= $row[self::CUENTA].'---> ['.$row[self::NRO_CUENTA].']';
			}
        return $data;
		}
	}

	function gestion_select(){
    $query = $this->db->get(self::TABLA_GESTION);
    $data = array();
    $data[]='Seleccione un elemento'; //aqui agregamos una opcion sin valor a nuestro select, la cual sera la seleccion por defecto
    if($query->num_rows()>0){
        foreach($query->result_array() as $row){
            $data[$row['Gestion']]= $row[self::GESTION];
			}
        return $data;
		}
	}

	function gestion_iden_select(){
    $query = $this->db->get(self::TABLA_GESTION_IDEN);
    $data = array();
    $data[]='Seleccione un elemento'; //aqui agregamos una opcion sin valor a nuestro select, la cual sera la seleccion por defecto
    if($query->num_rows()>0){
        foreach($query->result_array() as $row){
            $data[$row['Id_Gestion_Iden']]= $row[self::GESTION];
			}
        return $data;
		}
	}
	
	function list_all(){
		$this->db->order_by('Id','asc');
		return $this->db->get($control_pagos);
	}


	
function cuenta_all(){
		$this->db->select('Numero_Cuenta');
		$this->db->order_by('Id_Cuenta','asc');
		return $this->db->get($this->cuenta);
	}
	
function cuenta_all2() {
		$data = array();
		$Q = $this->db->get('cuenta');
		if ($Q->num_rows() > 0) {
			foreach ($Q->result_array() as $row){
		         	$data[] = $row;
		        }
		}	
		$Q->free_result();
		return $data;	
}
/*	function cuenta_all(){
		$this->db->order_by('Id_Cuenta','asc');
		return $this->db->get($cuenta);
	}*/

	function count_all(){
		return $this->db->count_all($this->control_pagos);
	}
	
	function count_all_Otor(){
		return $this->db->count_all($this->otorgaciones);
	}


	function get_paged_list($limit = 10, $offset = 0){
		$this->db->order_by('id','asc');
		return $this->db->get($this->control_pagos, $limit, $offset);
	}

	function get_deven_mod(){
		$this->db->order_by('Id_Devengado','asc');
		return $this->db->get($this->deven_mod);
	}
	function get_paged_list1(){
		$this->db->order_by('id','asc');
		return $this->db->get($this->control_pagos);
	}
	
	function get_by_cm($cuentap,$mesp,$Gestion_Iden_Pago){
		$cpago='DNI';
		if ($cuentap != '0'){
			$this->db->where('Id_Cuenta', $cuentap);
		}
		if ($mesp != '0'){
		$this->db->where('MONTH(Fecha_Pago)', $mesp);
		}
		$this->db->where('Gestion_Iden_Pago', $Gestion_Iden_Pago);
		$this->db->where('Id_Pago', $cpago);
		return $this->db->get($this->control_pagos);
	}


	function get_by_multas($operador,$C_multa,$Estado,$Pago,$Sector){
		
		if ($operador != '0'){
			$this->db->where('Id_Operador', $operador);
		}
		if ($C_multa != '0'){
		$this->db->where('Cod_Multa', $C_multa);
		}
		if ($Estado != '0'){
			$this->db->where('Estado', $Estado);
		}
		if ($Pago != '0'){
			$this->db->where('Pago', $Pago);
		}
		if ($Sector != '0'){
			$this->db->where('Sector', $Sector);
		}

		return $this->db->get($this->multas);
	}

	function get_by_otorga($Id_Oper,$Servi,$P_DUF,$Pen_DUF,$P_TR,$Pen_TR,$Vigen){
		
		if ($Id_Oper != '0'){
			$this->db->where('Id_Operador', $Id_Oper);
		}
		if ($Servi != '0'){
		$this->db->where('Servicio', $Servi);
		}
		if ($P_DUF != ''){
			$this->db->where('Paga_DUF', $P_DUF);
		}
		if ($Pen_DUF != ''){
			$this->db->where('Pendiente_DUF', $Pen_DUF);
		}
		if ($P_TR != ''){
			$this->db->where('Paga_TR', $P_TR);
		}

		if ($Pen_TR != ''){
			$this->db->where('Pendiente_TR', $Pen_TR);
		}

		if ($Vigen != ''){
			$this->db->where('Vigente', $Vigen);
		}

		return $this->db->get($this->otorgaciones);
	}

	function get_rep($cpago,$ges,$mesp){
		$this->db->where('Id_Gestion', $ges);
		$this->db->where('MONTH(Fecha_Pago)', $mesp);
		$this->db->where('Id_Pago', $cpago);
	
		return $this->db->get($this->control_pagos);
	}

function get_rep_det($cpago,$ges,$mesp,$gestionIden,$cuenta){
		
		if ($cpago != 'DUF')
	{

		$this->db->where('Id_Gestion', $ges);
		$this->db->where('MONTH(Fecha_Pago)', $mesp);
		$this->db->where('Gestion_Iden_Pago', $gestionIden);
		$this->db->where('Id_Pago', $cpago);
		if ($cuenta !='0'){
		$this->db->where('Id_Cuenta', $cuenta);
		}
		return $this->db->get($this->control_pagos);

	} else
	{
		if ($cuenta !='0'){

		return $this->db->query("SELECT * FROM control_pagos WHERE (Id_Gestion=$ges AND MONTH(Fecha_Pago)=$mesp AND Gestion_Iden_Pago=$gestionIden AND Id_Cuenta=$cuenta ) AND (Id_Pago='DUF' OR Id_Pago='DUFMO' OR Id_Pago='DUFIN')"); 
		}
		else{
		return $this->db->query("SELECT * FROM control_pagos WHERE (Id_Gestion=$ges AND MONTH(Fecha_Pago)=$mesp AND Gestion_Iden_Pago=$gestionIden ) AND (Id_Pago='DUF' OR Id_Pago='DUFMO' OR Id_Pago='DUFIN')"); 
		}
	}
		
		
	}

     function get_rep_det4($cpago,$mesp,$gestionIden,$cuenta){
		


		$this->db->where('MONTH(Fecha_Pago)', $mesp);
		$this->db->where('Gestion_Iden_Pago', $gestionIden);
		$this->db->where('Id_Pago', $cpago);
		if ($cuenta !='0'){
		$this->db->where('Id_Cuenta', $cuenta);
		}
		return $this->db->get($this->control_pagos);

		
		
	}

 function get_rep_eje($cpago,$cuenta,$gestionIden){
		

		$this->db->where('Id_Cuenta', $cuenta);
		$this->db->where('Gestion_Iden_Pago', $gestionIden);
		$this->db->where('Id_Pago', $cpago);
		
		
		return $this->db->get($this->control_pagos);

		
		
	}

 function get_rep_eje1($cpago,$cuenta,$gestionIden){
		
		
		$this->db->where('Id_Cuenta', $cuenta);
		$this->db->where('Gestion_Iden_Pago', $gestionIden);
		$this->db->where('Id_Pago', $cpago);
		
		
		return $this->db->get($this->control_pagos);


		
		
	}


function get_rep_eje2($cpago,$cuenta,$gestionact,$gestionIden){
		
		
		$this->db->where('Id_Cuenta', $cuenta);
		$this->db->where('Gestion_Iden_Pago', $gestionIden);
		$this->db->where('Id_Pago', $cpago);
		$this->db->where('Id_Gestion', $gestionact);
		
		
		return $this->db->get($this->control_pagos);

		
	}


/*  function get_rep_eje11($cpago,$cuenta,$gestionIden){
		

		if ($cuenta=='Libreta'){
								return $this->db->query("SELECT * FROM control_pagos WHERE (Id_Cuenta='Libreta' AND  Gestion_Iden_Pago=$gestionIden ) AND (Id_Pago='DUF' OR Id_Pago='DUFMO' OR Id_Pago='DUFIN')"); 
		}
				else{
		
					if ($cuenta=='1-4670837')
						{
							return $this->db->query("SELECT * FROM control_pagos WHERE (Id_Cuenta='1-4670837' AND  Gestion_Iden_Pago=$gestionIden ) AND (Id_Pago='DUF' OR Id_Pago='DUFMO' OR Id_Pago='DUFIN')"); 
						}
							else{
		
									if ($cuenta=='1-4670902')
										{
												return $this->db->query("SELECT * FROM control_pagos WHERE (Id_Cuenta='1-4670902' AND  Gestion_Iden_Pago=$gestionIden ) AND (Id_Pago='DUF' OR Id_Pago='DUFMO' OR Id_Pago='DUFIN')"); 
										}
									else{
		
												if ($cuenta=='1-4670861')
													{
															return $this->db->query("SELECT * FROM control_pagos WHERE (Id_Cuenta='1-4670861' AND  Gestion_Iden_Pago=$gestionIden ) AND (Id_Pago='DUF' OR Id_Pago='DUFMO' OR Id_Pago='DUFIN')"); 
													}

										else{
														$this->db->where('Id_Cuenta', $cuenta);
														$this->db->where('Gestion_Iden_Pago', $gestionIden);
														$this->db->where('Id_Pago', $cpago);
		
		
															return $this->db->get($this->control_pagos);
											}
										}
									}
							}
			
}
*/

	function get_rep_2($cpago,$mesp){

		$this->db->where('MONTH(Fecha_Pago)', $mesp);
		$this->db->where('Id_Pago', $cpago);
		return $this->db->get($this->control_pagos);
	}

	

	function get_multas($Cod_Multa){

		$this->db->where('Cod_Multa', $Cod_Multa);
		$query=$this->db->get($this->multas);
		return $query->num_rows(); 
	}

	function get_operador_habilitado($Cod_Multa){

		$this->db->where('Id_Operador', $Cod_Multa);
		$query=$this->db->get($this->operador);
		return $query->num_rows(); 
	}


	function get_otorga_id($Id_Otorgacion){

		$this->db->where('Id_Otorgacion', $Id_Otorgacion);
		return $this->db->get($this->otorgaciones);
	}

/*	function get_otorga_id_Operador(){
	$this->db->like('Id_Operador', '313');
	$query=$this->db->get($this->otorgaciones);
	$data = array();
	if($query->num_rows()>0){
        foreach($query->result_array() as $row){
            $data[$row['Servicio']]= $row->Servicio.'---> ['.$row->DUF_Importe.']';
			}
        return $data;
		}
	}*/

function get_otorga_id_Operador($Id_Operador){

		$this->db->where('Id_Operador', $Id_Operador);
		$query=$this->db->get($this->otorgaciones);
		if($query->num_rows()>0){
        foreach($query->result_array() as $row){
            $data['DUFRNO'.$row['Id_Otorgacion']]= $row['Id_Otorgacion'].'-DUF-->'.$row['Titulo'].'-->'.$row['DUF_Importe'];
			}
		}
		else{
		$data[]='No tiene nueva otorgacion';
		}
        return $data;
		
	}

	
/*	function get_otorga_id_Operador($Id_Operador){
	
	$this->db->where('Id_Operador', $Id_Operador);	
	
	$query=$this->db->get($this->otorgaciones);
	$data = array();
    //$data[]='Seleccione un elemento'; //aqui agregamos una opcion sin valor a nuestro select, la cual sera la seleccion por defecto
   if($query->num_rows()>0){


    while ($row = mysql_fetch_array($query)) { //you can assume rest of the code, right?
    $data[$row['Id_Otorgacion']] = array($row['DUF_Importe']);
	}
	}
	 return $data;
	}
		
	/*	foreach($query->result_array() as $row){
            $data[$row['Servicio']]= $row[self::SERVICIO_OTOR].'---> ['.$row[self::DUF_IMPORTE].']';
			}
        return $data;
		}
	}*/

/*	function get_otorga_id_Operador($Id_Operador){
		$this->db->select('Id_Otorgacion, DUF_Importe');
		$this->db->where('Id_Operador', $Id_Operador);
		return $this->db->get($this->otorgaciones);
	}*/


	
	function get_multa_id($Id_Multa){

		$this->db->where('Id_Multa', $Id_Multa);
		return $this->db->get($this->multas);
	}

	

	function get_by_cgm($operadorp,$cuentap,$conceptop,$gestionp,$gestionidenp,$mesp){
		
		if ($operadorp != '0'){
			$this->db->where('Id_Operador', $operadorp);
		}
		if ($cuentap != '0'){
			$this->db->where('Id_Cuenta', $cuentap);
		}
		
		if ($gestionp != '0'){
		$this->db->where('Id_Gestion', $gestionp);
		}
		if ($gestionidenp != '0'){
			$this->db->where('Gestion_Iden_Pago', $gestionidenp);
		}
		if ($mesp != '0'){
		$this->db->where('MONTH(Fecha_Pago)', $mesp);
		}

		if ($conceptop != '0'){
			$this->db->where('Id_Pago', $conceptop);
			
		}
		$this->db->order_by('Razon_Social asc,Fecha_Pago asc, Importe_Operador desc'); 
		return $this->db->get($this->control_pagos);
	}

	function get_deven ($operadorp,$pagaduf,$tipoduf,$pagatr,$tipotr){
		
	if ($operadorp != '0'){
			$this->db->where('Id_Operador', $operadorp);
		}

	if ($pagaduf != ''){
			$this->db->where('Paga_DUF', $pagaduf);
						}
		
	if ($tipoduf != ''){
		 if ($tipoduf == '1'){
				$this->db->where('DUF_PUA', $tipoduf);
				}
		  if ($tipoduf == '2'){
				$this->db->where('DUF_Inalambrico', $tipoduf);
				}
		  if ($tipoduf == '3'){
				$this->db->where('DUF_Movil', $tipoduf);
				}
		}


		if ($pagatr != ''){
			$this->db->where('DUF_TR', $pagatr);
			}


		if ($tipotr != ''){
		 if ($tipotr == '1'){
				$this->db->where('DUF_TRF221', $tipotr);
				}
		  if ($tipoduf == '2'){
				$this->db->where('DUF_TRF222', $tipotr);
				}
		  if ($tipoduf == '3'){
				$this->db->where('DUF_TRF223', $tipotr);
				}
		}

					
		
		$this->db->order_by('Id_Operador asc'); 
		return $this->db->get($this->deven);
	}

	function get_by_Id_Operador($Id_Operador){
		$this->db->where('Id_Operador', $Id_Operador);
		return $this->db->get($this->ope);
	}

	function get_by_Id_Servicio($TServicio){
		$this->db->where('Codigo', $TServicio);
		return $this->db->get($this->servicio);
	}

	function get_by_Id_Operador_Deven($Id_Operador){
		$this->db->where('Id_Operador', $Id_Operador);
		return $this->db->get($this->deven);
		}

	

	function get_by_id($id){
		$this->db->where('Id', $id);
		return $this->db->get($this->control_pagos);
	}

	function get_by_id2($id){
		$this->db->where('Id_Control_Pago', $id);
		return $this->db->get($this->desglose);
	}
	
	function get_by_id_3($id){
		$this->db->where('Id_Desglose', $id);
		return $this->db->get($this->desglose);
	}
	
	function save($person){
		$this->db->insert($this->control_pagos, $person);
		return $this->db->insert_id();
	}

	function save_f212($person){
		$this->db->insert($this->f212lndi, $person);
		return $this->db->insert_id();
	}
	
	function save_desglose($person){
		$this->db->insert($this->desglose, $person);
		return $this->db->insert_id();
	}

	function save_otorga($person){
		$this->db->insert($this->otorgaciones, $person);
		return $this->db->insert_id();
	}

	function save_opera($person){
		$this->db->insert($this->operador, $person);
		return $this->db->insert_id();
	}

	function save_multas($person){
		$this->db->insert($this->multas, $person);
		return $this->db->insert_id();
	}

	function save_modimultas($person){
		$this->db->insert($this->cambio_multa, $person);
		return $this->db->insert_id();
	}

	function save_deven($person){
		$this->db->insert($this->deven, $person);
		return $this->db->insert_id();
	}
	
	function save_deven_mod($person){
		$this->db->insert($this->deven_mod, $person);
		return $this->db->insert_id();
	}

	function update($id){
		$r=2;
		return $this->db->query ("UPDATE control_pagos SET Identificado=$r WHERE Id=$id ");
	}

	
	function update_deven_mod($id){
		$r=0;
		return $this->db->query ("UPDATE deven_mod SET Actualizar=$r WHERE Id_Devengado=$id ");
	}

	function update_d_b($id){
		$r=6;
		return $this->db->query ("UPDATE control_pagos SET Identificado=$r WHERE Id=$id ");
	}
	
	function update_2($id, $person){
		$this->db->where('Id', $id);
		$this->db->update($this->control_pagos, $person);
	}

	function update_multas($id, $person){
		$this->db->where('Id_Multa', $id);
		$this->db->update($this->multas, $person);
	}

	function update_otorga($id, $person){
		$this->db->where('Id_Otorgacion', $id);
		$this->db->update($this->otorgaciones, $person);
	}

	function update_deven($id, $person){
		$this->db->where('Id_Operador', $id);
		$this->db->update($this->deven, $person);
	}
	function delete($id){
		$this->db->where('Id', $id);
		$this->db->delete($this->control_pagos);
	}

	function delete_deven($id){
		$this->db->where('Id_Operador', $id);
		$this->db->delete($this->deven);
	}


	function delete_3($id){
		$this->db->where('Id_Control_Pago', $id);
		$this->db->delete($this->control_pagos);
	}
	function delete_2($id){
		$this->db->where('Id_Desglose', $id);
		$this->db->delete($this->desglose);
	}

	function sumar(){
	$this->db->select_sum('Importe_Operador');
	return $this->db->get($this->control_pagos);
	

	}
	
	function actualiza_regs($id_Ope,$id){
	return $this->db->query("UPDATE deven SET Id_Operador = (SELECT deven_mod.Id_Operador FROM deven_mod WHERE deven.Id_Devengado=$id_Ope), SET Paga_DUF = (SELECT deven_mod.Paga_DUF FROM deven_mod WHERE deven.Id_Devengado=$id_Ope), SET DUF_PUA = (SELECT deven_mod.DUF_PUA FROM deven_mod WHERE deven.Id_Devengado=$id_Ope), SET DUF_PUA_Importe = (SELECT deven_mod.DUF_PUA_Importe FROM deven_mod WHERE deven.Id_Devengado=$id_Ope), SET DUF_Inalambrico = (SELECT deven_mod.DUF_Inalambrico FROM deven_mod WHERE deven.Id_Devengado=$id_Ope), SET DUF_IDicGA = (SELECT deven_mod.DUF_IDicGA FROM deven_mod WHERE deven.Id_Devengado=$id_Ope), SET DUF_I_Importe_Anual = (SELECT deven_mod.DUF_I_Importe_Anual FROM deven_mod WHERE deven.Id_Devengado=$id_Ope), SET DUF_IEne = (SELECT deven_mod.DUF_IEne FROM deven_mod WHERE deven.Id_Devengado=$id_Ope), SET DUF_IFeb = (SELECT deven_mod.DUF_IFeb FROM deven_mod WHERE deven.Id_Devengado=$id_Ope), SET DUF_IMar = (SELECT deven_mod.DUF_IMar FROM deven_mod WHERE deven.Id_Devengado=$id_Ope), SET DUF_IAbr = (SELECT deven_mod.DUF_IAbr FROM deven_mod WHERE deven.Id_Devengado=$id_Ope), SET DUF_IMay = (SELECT deven_mod.DUF_IMay FROM deven_mod WHERE deven.Id_Devengado=$id_Ope), SET DUF_IJun = (SELECT deven_mod.DUF_IJun FROM deven_mod WHERE deven.Id_Devengado=$id_Ope), SET DUF_IJul = (SELECT deven_mod.DUF_IJul FROM deven_mod WHERE deven.Id_Devengado=$id_Ope), SET DUF_IAgo = (SELECT deven_mod.DUF_IAgo FROM deven_mod WHERE deven.Id_Devengado=$id_Ope), SET DUF_ISep = (SELECT deven_mod.DUF_ISep FROM deven_mod WHERE deven.Id_Devengado=$id_Ope), SET DUF_IOct = (SELECT deven_mod.DUF_IOct FROM deven_mod WHERE deven.Id_Devengado=$id_Ope), SET DUF_INov = (SELECT deven_mod.DUF_INov FROM deven_mod WHERE deven.Id_Devengado=$id_Ope), SET DUF_Movil = (SELECT deven_mod.DUF_Movil FROM deven_mod WHERE deven.Id_Devengado=$id_Ope), SET DUF_MDicGA = (SELECT deven_mod.DUF_MDicGA FROM deven_mod WHERE deven.Id_Devengado=$id_Ope), SET DUF_M_Importe_Anual = (SELECT deven_mod.DUF_M_Importe_Anual FROM deven_mod WHERE deven.Id_Devengado=$id_Ope), SET DUF_MEne = (SELECT deven_mod.DUF_MEne FROM deven_mod WHERE deven.Id_Devengado=$id_Ope), SET DUF_MFeb = (SELECT deven_mod.DUF_MFeb FROM deven_mod WHERE deven.Id_Devengado=$id_Ope),SET DUF_MMar = (SELECT deven_mod.DUF_MMar FROM deven_mod WHERE deven.Id_Devengado=$id_Ope),SET DUF_MAbr = (SELECT deven_mod.DUF_MAbr FROM deven_mod WHERE deven.Id_Devengado=$id_Ope),SET DUF_MMay = (SELECT deven_mod.DUF_MMay FROM deven_mod WHERE deven.Id_Devengado=$id_Ope),SET DUF_MJun = (SELECT deven_mod.DUF_MJun FROM deven_mod WHERE deven.Id_Devengado=$id_Ope),SET DUF_MJul = (SELECT deven_mod.DUF_MJul FROM deven_mod WHERE deven.Id_Devengado=$id_Ope),SET DUF_MAgo = (SELECT deven_mod.DUF_MAgo FROM deven_mod WHERE deven.Id_Devengado=$id_Ope),SET DUF_MSep = (SELECT deven_mod.DUF_MSep FROM deven_mod WHERE deven.Id_Devengado=$id_Ope),SET DUF_MOct = (SELECT deven_mod.DUF_MOct FROM deven_mod WHERE deven.Id_Devengado=$id_Ope), SET DUF_MNov = (SELECT deven_mod.DUF_MNov FROM deven_mod WHERE deven.Id_Devengado=$id_Ope), SET DUF_TR = (SELECT deven_mod.DUF_TR FROM deven_mod WHERE deven.Id_Devengado=$id_Ope),SET DUF_TRF221 = (SELECT deven_mod.DUF_TRF221 FROM deven_mod WHERE deven.Id_Devengado=$id_Ope),SET DUF_TRF222 = (SELECT deven_mod.DUF_TRF222 FROM deven_mod WHERE deven.Id_Devengado=$id_Ope),SET DUF_TRF223 = (SELECT deven_mod.DUF_TRF223 FROM deven_mod WHERE deven.Id_Devengado=$id_Ope), SET Id_Gestion_Identificacion = (SELECT deven_mod.Id_Gestion_Identificacion FROM deven_mod WHERE deven.Id_Devengado=$id_Ope),SET Actualizar = 3 WHERE Id_Operador=$id"); 


	}

	function copia_regs($id){
	return $this->db->query("INSERT INTO control_pagos (Id_Cuenta,Id_Control_Pago,Banco,Id_Gestion,Id_Operador,Id_Pago,Fecha_Pago,Razon_Social, Importe_Operador_Identificado,Registro_Estado_Cuenta,Hoja_Ruta,Nro_C21,Identificado,Gestion_Iden_Pago) SELECT Id_Cuenta,Id_Control_Pago,Banco,Id_Gestion,Id_Operador,Id_Pago,Fecha_Pago,Razon_Social, Importe_Operador,Registro_Estado_Cuenta,Hoja_Ruta,Nro_C21,Identificado,Gestion_Iden_Pago FROM desglose WHERE Id_Control_Pago=$id"); 


	}

	function copia_reg_deven($id){
	return $this->db->query("INSERT INTO deven (Id_Operador,Paga_DUF,DUF_PUA,DUF_PUA_Importe,DUF_Inalambrico,DUF_IDicGA,DUF_I_Importe_Anual,DUF_IEne, DUF_IFeb,  	DUF_IMar,DUF_IAbr,DUF_IMay,DUF_IJun,DUF_IJul,DUF_IAgo,DUF_ISep,DUF_IOct,DUF_INov,DUF_Movil,DUF_MDicGA,DUF_M_Importe_Anual,DUF_MEne,DUF_MFeb,DUF_MMar,DUF_MAbr,DUF_MMay,DUF_MJun,DUF_MJul,DUF_MAgo,DUF_MSep,DUF_MOct,DUF_MNov,DUF_TR,DUF_TRF221,DUF_TRF222,DUF_TRF223,Id_Gestion_Identificacion,Actualizar) SELECT Id_Operador,Paga_DUF,DUF_PUA,DUF_PUA_Importe,DUF_Inalambrico,DUF_IDicGA,DUF_I_Importe_Anual,DUF_IEne, DUF_IFeb,  	DUF_IMar,DUF_IAbr,DUF_IMay,DUF_IJun,DUF_IJul,DUF_IAgo,DUF_ISep,DUF_IOct,DUF_INov,DUF_Movil,DUF_MDicGA,DUF_M_Importe_Anual,DUF_MEne,DUF_MFeb,DUF_MMar,DUF_MAbr,DUF_MMay,DUF_MJun,DUF_MJul,DUF_MAgo,DUF_MSep,DUF_MOct,DUF_MNov,DUF_TR,DUF_TRF221,DUF_TRF222,DUF_TRF223,Id_Gestion_Identificacion,Actualizar FROM deven_mod WHERE Id_Devengado=$id"); 

	}

	function borrar_notif($fecha_actual, $id_operador){
		return $this->db->query("UPDATE notificaciones SET Visualizado='2', Fecha_Notif='$fecha_actual' WHERE Visualizado='1' AND Id_Operador=$id_operador"); 
	}


}
?>