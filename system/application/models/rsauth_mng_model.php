<?php
	if (!defined('BASEPATH')) exit('No direct script access allowed');
	/** 
	 * Model for RSAuth
	 * if rsauth table doesn't exits is created automatically
	 * @author SeViR CW · http://www.sevir.org
	 */
	class Rsauth_mng_model extends Model {
		var $app;
		var $lasterror = "";

		/**
		 * Constructor
		 */
		function Rsauth_mng_model(){
			parent::Model();
			$this->app = & get_instance();
			$this->app->config->load('rsauth',TRUE,FALSE);

			if (!($this->app->db->table_exists($this->app->config->item('rsauth_table','rsauth')))){
				$this->_createauthtable();
			}
		}

		/**
		 * check username and password in the database
		 * @return boolean
		 */
		function checklogin($username,$password,$ismd5 = false){
			$this->app->db->select('*');
			$this->app->db->from($this->app->config->item('rsauth_table','rsauth'));
			$this->app->db->where(array('username'=>$username,'password'=>($ismd5)?$password:md5($password)));
			$result = $this->app->db->get();

			if ($result->num_rows() > 0){
				log_message('debug',"Checklogin of $username is: OK");
				return TRUE;
			}else{
				log_message('debug',"Checklogin of $username is: error");
				return FALSE;
			}
		}

		/**
		 * check if user exists in the database
		 * @return boolean
		 */
		function checkuser($username){
			$this->app->db->select('*');
			$this->app->db->from($this->app->config->item('rsauth_table','rsauth'));
			$this->app->db->where(array('username'=>$username));
			$result = $this->app->db->get();

			if ($result->num_rows() > 0){
				return TRUE;
			}else{
				return FALSE;
			}
		}

		/**
		 * Add user identify with username and password, passwords are stored in MD5
		 * @param username string
		 * @param password string
		 * @return boolean true if successfull
		 */
		function adduser($username,$password){
			if ($this->checkuser($username)){
				$this->lasterror = "Username $username already exists";
				return FALSE;
			}else{
				$this->app->db->insert($this->app->config->item('rsauth_table','rsauth'),
							   array('username'=>$username,'password'=>md5($password)));
				return TRUE;
			}
		}

		/**
		 * Update MD5 password stored in database
		 * @param username string
		 * @param password string with the new password
		 */
		function updatepassword($username,$password){
			if (!$this->checkuser($username)){
				$this->lasterror = "Username $username don't exists'";
				return FALSE;
			}else{
				$this->app->db->update($this->app->config->item('rsauth_table','rsauth'),
								   array('password'=>md5($password)),
								   array('username'=>$username));
				return TRUE;
			}
		}

		/**
		 * private function create auth table in the constructor library process
		 */
		function _createauthtable(){
			switch ($this->app->config->item('rsauth_dbdriver','rsauth')){
				case 'mysql':
					$this->app->db->query("CREATE TABLE ".$this->app->config->item('rsauth_table','rsauth')." (".
					"username VARCHAR(50) NOT NULL DEFAULT '',".
					"password VARCHAR(32) NOT NULL DEFAULT '',".
					"PRIMARY KEY(username),".
					"KEY (username))");
					log_message('debug','rsauth table created');
					$this->app->db->query("INSERT INTO ".$this->app->config->item('rsauth_table','rsauth').
										"(username, password) VALUES('".$this->app->config->item('rsauth_adminuser','rsauth').
										"','".md5($this->app->config->item('rsauth_adminpassword','rsauth'))."')");
					log_message('debug','rsauth admin data is set');
					break;
				default:
					log_message('error','No auth table creation script for the database driver: '.
								$this->app->config->item('rsauth_dbdriver','rsauth'));
			}
		}

		function getlasterror(){
			return $this->lasterror;
		}
	}
?>