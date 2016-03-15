<?php
	if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Rsauth is Really Simple Authentification System
 * Store in a table username and md5 passwords
 * @author SeViR CW http://www.sevir.org
 * @license Creative Commons "share alike"
 */
	class Rsauth {
		var $app;
		var $username = FALSE;
		var $islogged = FALSE;

		/**
		 * Constructor
		 */ 
		function Rsauth(){
			$this->app = & get_instance();
			//Load Database Class with default config data file and session Class
			$this->app->load->database();
			$this->app->load->library('session');
			$this->app->load->model('rsauth_mng_model');
			$this->app->load->helper('url');
			$this->app->load->helper('form');

			log_message('debug','Rsauth class initialized via: '.get_class($this->app));
		}

		/**
		 * Checks if user is loginIn
		 * @return boolean true or false
		 */ 
		function check(){
			return $this->app->session->userdata('islogged');
		}

		/**
		 * Return true if username exists in the user table, false in other case
		 * @param username Username to check
		 */ 
		function userExists($username = ''){
			return $this->app->rsauth_mng_model->checkuser($username);
		}

		/**
		 * Get the username of the loginIn User
		 */ 
		function getUser(){
			return $this->app->session->userdata('username');
		}
		
		/**
		 * Logout, reset all perms, clear loginIn data
		 */ 
		function logout(){
			$this->username = FALSE;
			$this->islogged = FALSE;
			$logindata = array('username'=>FALSE,'islogged'=>FALSE);
			$this->app->session->set_userdata($logindata);
		}

		/**
		 * check login data (username,password) in the rsauth DB table
		 * @param boolean if TRUE show login form when the login data is not correct
		 * @return string empty string or HTML code of login form
		 */ 
		function login($showloginform = FALSE){
			if ($this->app->session->userdata('username') && 
				$this->app->session->userdata('islogged') &&
				$this->app->rsauth_mng_model->checklogin($this->app->session->userdata('username'), $this->app->session->userdata('pass'), TRUE)){
					log_message('info',"User already logged as: ".$this->app->session->userdata('username'));
					return '';
			}else{
				if (($this->username = $this->app->input->post('username')) &&
				 ($pass = $this->app->input->post('password')) &&
				 ($this->islogged = $this->app->rsauth_mng_model->checklogin($this->username, $pass))){
					$logindata = array('username'=>$this->username, 'pass'=>md5($pass),'islogged'=>TRUE);
					$this->app->session->set_userdata($logindata);
					log_message('info',"User logged as: ".$this->username);
					return '';
				}else{
					if ($showloginform){
						return $this->app->load->view('rsauth/rsauthform.php','',TRUE);
					}
					return '';
				}
			}
		}
	}
?>