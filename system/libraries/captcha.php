<?php

/**
*
* @title Simple CAPTCHA
* @author Andrew Silin <dcez@land.ru>
* @url http://silin.lv - someday will work))
* @version 0.1b
*
* You can use this script as you like :) No strings attached, it can be used
* as stand-alone script as well, CodeIgniter isn't necessary. This script won't
* guarantee 100% security, because the main aim wasn't to make it
* strong as possible, but to make captcha usage as simple as possible.
*
* GD Lib required by the way...
*
*/



class Captcha{




    //----------------------------DEFAULT CONFIG--------------------------------


    var $chars = 'abcdefghijklmnopqrstuvwxyz'; //Characters used to generate captcha string
    var $count = 5;                            //How many character to generate

    var $fonts = array('lexo.ttf', 'actionj.ttf'); //Character fonts
    var $bgs   = array();                          //Captcha backgrounds in GIF format


    var $width  = 200; //Captcha image width
    var $height = 75;  //Captcha image height
    var $margin = 0;   //Spacing around each character

    var $size   = array('min' => 25, 'max' => 40);  //Character size range
    var $angle  = array('min' => 0,  'max' => 20);  //Character angle range


    //Captcha color ranges

    var $color_min = array('r' => 255, 'g' => 255, 'b' => 255);
    var $color_max = array('r' => 255, 'g' => 255, 'b' => 255);


    var $session_name = 'captcha';  //Captcha session index


    var $bgs_folder  = 'bgs/';   //Backgrounds flder path
    var $font_folder = 'fonts/'; //Fonts flder path


    /**
    * Default paths for CodeIgniter
    */

    //system/application/libraries/captcha/bgs/
    //system/application/libraries/captcha/fonts/


    var $debug = false; //Draws lines between characters





    //-----------------------------METHODS--------------------------------------


    //Sessions we'll use.

    function Captcha()
    {

        if(!isset($_SESSION))
        {
            session_start();
        }

    }


    /**
    * To avoid image caching we need to send additional no-cache headers,
    * as well as content type.
    *
    * @return void
    */

    function send_headers()
    {

        //Back in the good old days :)

        header("expires: mon, 26 jul 1997 05:00:00 gmt");
        header("cache-control: no-cache, must-revalidate");
        header("pragma: no-cache");

        //This is GIF image!

        header('Content-type: image/gif');

    }

    //--------------------------------------------------------------------------

    /**
    * This function creates and outputs generated image to screen.
    *
    * @return void
    */

    function generate()
    {

        $image = imagecreatetruecolor($this->width, $this->height);


        /**
        * Set random bacground
        */

        if(!empty($this->bgs))
        {

            $bg_file  = $this->bgs_folder.$this->bgs[array_rand($this->bgs)];

            if(file_exists($bg_file))
            {
                $bg_image = imagecreatefromgif($bg_file);

                imagecopy($image, $bg_image, 0, 0, 0, 0, $this->width, $this->height);
            }


        }


        /**
        * Create random letters
        */

        $letter['offset'] = 0;

        $captcha = '';


        for($i=0;$i<$this->count;$i++){


            /**
            * Generate random color for current letter
            */

            $R = rand($this->color_min['r'], $this->color_max['r']);
            $G = rand($this->color_min['g'], $this->color_max['g']);
            $B = rand($this->color_min['b'], $this->color_max['b']);

            $letter['color'] = imagecolorallocate($image, $R, $G, $B);


            /**
            * Calculate random angle for current letter
            */

            $angle = rand($this->angle['min'], $this->angle['max']);

            $letter['angle'] = (rand() & 1)? $angle : 360 - $angle;



            /**
            * Set random font for current letter
            */


            $letter['font'] = $this->font_folder.$this->fonts[array_rand($this->fonts)];



            /**
            * Select random character from string
            */

            $letter['char'] = $this->chars[rand(0, strlen($this->chars) - 1)];

            $letter['char'] = (rand() & 1)? strtoupper($letter['char']) : $letter['char'];


            /**
            * Calculate current letter size
            */


            $letter['size'] = rand($this->size['min'], $this->size['max']);


            /**
            * Calculate current letter position
            */

            $box = imagettfbbox($letter['size'], $letter['angle'], $letter['font'], $letter['char']);


            $letter['width']   = $box[2] - $box[0] + $this->margin;
            $letter['height']  = $box[1] - $box[7] + $this->margin;;
            $letter['offset'] += $letter['width'];


            $y = round((($this->height - $letter['height']) / 2), 0) + $letter['height'];


            $letter['pos_x'] = $letter['offset'] - ($letter['width'] / 2);
            $letter['pos_y'] = $y;


            /**
            * Finally, we draw our letter!
            */


            imagettftext($image, $letter['size'], $letter['angle'], $letter['pos_x'], $letter['pos_y'], $letter['color'], $letter['font'], $letter['char']);


            /**
            * Add current letter to captcha word
            */


            $captcha .= $letter['char'];


            /**
            * For debuging
            */

            if($this->debug)
            {
                imageline($image, $letter['pos_x'], 0, $letter['pos_x'], $this->height, imagecolorallocate($image, 255, 0, 0));
            }


        }


        //Send image headers first

        $this->send_headers();


        //Store captcha string in session

        $this->store_session($captcha);


        //Output captcha image

        imagegif($image);



    }


    //--------------------------------------------------------------------------

    /**
    * This function compares user input with current captcha string.
    *
    * @param string User input string
    * @return boolean
    */


    function validate($str)
    {


        //Prevent undefined index error message

        if(!isset($_SESSION[$this->session_name]))
        {
            return false;
        }

        //Compare captcha string with user input

        $result = (strtolower($_SESSION[$this->session_name]) == strtolower($str) && !empty($str))? true : false;


        //Destroy session data

        $this->kill_session();


        //Result!

        return $result;


    }



    //--------------------------------------------------------------------------

    /**
    * This function stores captcha string into session.
    *
    * @param string Current captcha string
    * @return void
    */

    function store_session($captcha)
    {

        //Destroy old session data if existed

        $this->kill_session();

        //Store captcha string here

        $_SESSION[$this->session_name] = $captcha;


    }


    //--------------------------------------------------------------------------

    /**
    * This function is used to delete captcha session string only,
    * other session data will be available.
    *
    * @return void
    */


    function kill_session()
    {

        //Unset only captcha session data

        if(isset($_SESSION[$this->session_name]))
        {
            $_SESSION[$this->session_name] = '';

            unset($_SESSION[$this->session_name]);
        }


    }



}



?>