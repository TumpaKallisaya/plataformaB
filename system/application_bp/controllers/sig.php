<?php

    class SIG extends Controller {

    	function SIG()
    	{                           
    		parent::Controller();
    		$this->config->load('sig_config');
    		$this->load->model('SIG_model');
    	}

    	function index()
    	{                     
    		$this->SIG_model->generateSecurityImage();
    	}

    }
?>