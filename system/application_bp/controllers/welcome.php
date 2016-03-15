<?php

class Welcome extends Controller {

    /**
    * Load simple captcha library
    */

    function Welcome()
    {
        parent::Controller();

        $this->load->library('captcha');
    }


    /**
    * Show form
    */
    
    function index()
    {
        $this->load->view('index1');
    }


    /**
    * Use this method to output captcha image. 
    * Ex. img src="index.php/welcome/captcha"
    */

    function captcha()
    {
        $this->captcha->generate();
    }


    /**
    * Use this method in form action url, ex. action="index.php/welcome/validate"
    */

    function validate()
    {

        //Validate user input  

        if($this->captcha->validate($this->input->post('captcha')))
        {
            echo 'OK'; //If captcha string is correct

        }else{

            header('Location: /index.php/welcome'); //Otherwise redirect back

        }

    }



}
