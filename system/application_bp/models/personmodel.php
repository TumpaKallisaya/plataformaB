<?php
class PersonModel extends Model {
		
	function InputSelect1($LaKeySelection,$LosElementos,$compA,$compB){//echo $compB;
		$this->db->order_by($LosElementos,'ASC');
		$this->db->where($compA,$compB);
		$query = $this->db->get($LaKeySelection); //echo var_dump($query);
	    $data = array();
	    $data[]='Todos'; //aqui agregamos una opcion sin valor a nuestro select, la cual sera la seleccion por defecto
	    if($query->num_rows()>0){
	        foreach($query->result_array() as $row){
	            $data[$row[$LosElementos]]= $row[$LosElementos];
			}
			return $data;
		}
	}
	function getTable($table){		
		return $this->db->get($table);
	}
	function getTable0($table,$id1,$id2){		
		$this->db->where($id1,$id2);	
		return $this->db->get($table);
	}
	function getTable1($table,$id1,$id2,$id3,$id4){		
		$this->db->where($id1,$id2);	
		$this->db->where($id3,$id4);	
		return $this->db->get($table);
	}	
	function deleteTable($table,$id0,$id1){
		$this->db->where($id0,$id1);
		$this->db->delete($table);
	}
	function deleteTable1($table,$id0,$id1,$id2,$id3){
		$this->db->where($id0,$id1);
		$this->db->where($id2,$id3);
		$this->db->delete($table);
	}	
	function updateTable($table,$data,$id0,$id1){
		$this->db->where($id0, $id1);
		$this->db->update($table, $data);
	}
	function saveTable($table,$data){		
		$this->db->insert($table,$data);
		return $this->db->insert_id();
	}	
	//----------------------------------------------------------------------
/** graba tarjeta by neko*/
	function save_tarjeta($data){ 
		$this->db->insert("tb_tarjeta",$data);
		return $this->db->insert_id();
	}
	/** consigue tarjeta by neko*/
	function get_tarjeta($id_usu){
	  $this->db->where('id_usuario',$id_usu);	
    return $this->db->get("tb_tarjeta");
	}
	/* actualiza la tarjeta by neko*/
	function update_tarjeta($data,$id_usu){
		$this->db->where('id_usuario',$id_usu);
		return $this->db->update("tb_tarjeta",$data);
	}
///////////////////////////////(((((((((((((((((((((((((((((((((((
	///////// modulos de configuracion//////////////////////
	/////////////////////////////////////////////////////////
		function get_roles_by_id($id){
		$this->db->where('Id_Rol',$id);
		return $this->db->get("tb_rol");
	}
		function get_usu($id){
		$this->db->where('id_usuario',$id);
		return $this->db->get("tb_usuarios");
	}
		function get_by_id_SLD($sl_table,$id_table,$id){
		$this->db->order_by('Id_Adjunto','DESC');
		$this->db->where($id_table,$id);
		return $this->db->get($sl_table);
	}
		function get_by_id_SL($sl_table,$id_table,$id){
		$this->db->where($id_table,$id);
		return $this->db->get($sl_table);
	}
	function get_usuarios1(){
	  $this->db->where('id_rol','3');	
	  $this->db->or_where('id_rol','4');	
	  $this->db->order_by('id_usuario','asc');
      return $this->db->get("tb_usuarios");
	}
	function get_usuarios2(){
	  $this->db->where('id_rol','2');	
	  $this->db->order_by('id_usuario','asc');
      return $this->db->get("tb_usuarios");
	}
	function save_usuario($data){
		$this->db->insert("tb_usuarios",$data);
		return $this->db->insert_id();
	}
		function InputSelect4($LaKeySelection,$LosElementos,$Id,$TipoC,$Tipo){
		$this->db->where($TipoC,$Tipo);
	    $this->db->order_by($Id,'asc');
	    $query = $this->db->get($LaKeySelection);
	    $data = array();
	    $data[]='Seleccione un elemento'; //aqui agregamos una opcion sin valor a nuestro select, la cual sera la seleccion por defecto
	    if($query->num_rows()>0){
	        foreach($query->result_array() as $row){	
	            $data[$row[$Id]]= $row[$LosElementos];
				}
			return $data;
			}
	}
		function update_usuarios($id_usuario,$data){
		$this->db->where('id_usuario', $id_usuario);
		$this->db->update("tb_usuarios",$data);
	}
}
?>