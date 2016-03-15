<?php
    class SIG_model extends Model
    {
    	function SIG_model()
    	{
    		parent::Model();
    	}

    	function generateSecurityImage()
    	{
    	// Recovers the type of image to be treated
            $type = ($this->config->item('sig_image_type') == 'png' || $this->config->item('sig_image_type') == 'jpeg') ? $this->config->item('sig_image_type') : 'jpeg';
            
		// The header is defined so that the page is an image and by preventing the setting in cash
			header('Expires: Thu, 28 Nov 2007 00:00:00 GMT');
			header('Cache-Control: no-store, no-cache, must-revalidate');
			header('Cache-Control: post-check=0, pre-check=0', false);
			header("Content-type: image/".$type);
 	
		// Test if there is an image file to recover and if it is a valid image file. 
		// If not, creation of a background image following the config paramaeters or
		//    the default parameters if those of the config file are absent.
		// If well, recover the size of the image.
			if (@getimagesize ($this->config->item('sig_image_path')) == false) { 
				$imgW = ($this->config->item('sig_image_width')) ? $this->config->item('sig_image_width') : 100; 
				$imgH = ($this->config->item('sig_image_height')) ? $this->config->item('sig_image_height') : 25;
				$img  = imagecreate($imgW, $imgH);
			} else {
				$imgSize = @getimagesize ($this->config->item('sig_image_path'));
				$img     = ($type == 'png') ? imagecreatefrompng($this->config->item('sig_image_path')) : imagecreatefromjpeg($this->config->item('sig_image_path'));
		    	$imgW    = $imgSize[0];
				$imgH    = $imgSize[1];
			}

		// Define the background color, the lines color, the text color and the text shadow color by
		//   recovering those informations in the config file, and if not, by using default values.
			$cfond = imagecolorallocate($img, ($this->config->item('sig_image_background_color_R') ? $this->config->item('sig_image_background_color_R') : 255), ($this->config->item('sig_image_background_color_G') ? $this->config->item('sig_image_background_color_G') : 255), ($this->config->item('sig_image_background_color_B') ? $this->config->item('sig_image_background_color_B') : 255));
			$cline = imagecolorallocate($img, ($this->config->item('sig_image_jamming_lines_color_R') ? $this->config->item('sig_image_jamming_lines_color_R') : 0), ($this->config->item('sig_image_jamming_lines_color_G') ? $this->config->item('sig_image_jamming_lines_color_G') : 0), ($this->config->item('sig_image_jamming_lines_color_B') ? $this->config->item('sig_image_jamming_lines_color_B') : 0));
			$ctext = imagecolorallocate($img, ($this->config->item('sig_text_color_R') ? $this->config->item('sig_text_color_R') : 0), ($this->config->item('sig_text_color_G') ? $this->config->item('sig_text_color_G') : 0), ($this->config->item('sig_text_color_B') ? $this->config->item('sig_text_color_B') : 0));
			$combr = imagecolorallocate($img, ($this->config->item('sig_text_shadow_color_R') ? $this->config->item('sig_text_shadow_color_R') : 255), ($this->config->item('sig_text_shadow_color_G') ? $this->config->item('sig_text_shadow_color_G') : 255), ($this->config->item('sig_text_shadow_color_B') ? $this->config->item('sig_text_shadow_color_B') : 255));
	
		// If asked, generate horizontal jamming lines (scrambling OCR).
			$nbrhl = ($this->config->item('sig_image_number_of_horizontal_jamming_lines') > -1 && $this->config->item('sig_image_number_of_horizontal_jamming_lines') < ($imgH/2)) ? $this->config->item('sig_image_number_of_horizontal_jamming_lines') : 0;
			if($nbrhl != 0){
				$hlsx  = 0;
				$hlex  = $imgW;
				$hly   = round($imgH / ($nbrhl +1));
				$hlsy  = 0;
				$hley  = 0;
				for($i=0;$i<$nbrhl;$i++){
					if($this->config->item('sig_image_set_randomly_horizontal_jamming_lines')){
						$hlsy = rand(0,$imgH);
						if ($this->config->item('sig_image_set_randomly_angle_jamming_lines')){
							$hley = rand(0,$imgH);
						} else { 
							$hley = $hlsy; 					
						} 					
					} else {
						$hlsy += $hly;
						$hley += $hly; 					
					}					
					imageline($img,$hlsx,$hlsy,$hlex,$hley,$cline);
				}
			}

		// If asked, generate vertical jamming lines (scrambling OCR).
			$nbrvl = ($this->config->item('sig_image_number_of_vertical_jamming_lines') > -1 && $this->config->item('sig_image_number_of_vertical_jamming_lines') < ($imgW/2)) ? $this->config->item('sig_image_number_of_vertical_jamming_lines') : 0;
			if($nbrvl != 0){
				$vlsy  = 0;
				$vley  = $imgH;
				$vlx   = round($imgW / ($nbrvl +1));
				$vlsx  = 0;
				$vlex  = 0;
				for($i=0;$i<$nbrvl;$i++){
					if($this->config->item('sig_image_set_randomly_vertical_jamming_lines')){
						$vlsx = rand(0,$imgW);
						if ($this->config->item('sig_image_set_randomly_angle_jamming_lines')){
							$vlex = rand(0,$imgW);
						} else {
							$vlex = $vlsx;
						} 
					} else {
						$vlsx += $vlx;
						$vlex += $vlx;
					}
					imageline($img,$vlsx,$vlsy,$vlex,$vley,$cline);
				}
			}

		// If asked, generate elliptical jamming lines (scrambling OCR).
			$nbrel = ($this->config->item('sig_image_number_of_ellipse_jamming_lines') > -1 && $this->config->item('sig_image_number_of_ellipse_jamming_lines') < ($imgH/2)) ? $this->config->item('sig_image_number_of_ellipse_jamming_lines') : 0;
			if($nbrel != 0){
				$elx  = round($imgW / 2);
				$ely  = round($imgH / 2);                                                       
				$elwe = round(($imgW / 2) / $nbrel);
				$elhe = round(($imgH / 2) / $nbrel);
				for($i=0;$i<$nbrel;$i++){
					$elw = round((($imgW / 2) - ($elwe * $i)) * 2); 
					$elh = round((($imgH / 2) - ($elhe * $i)) * 2); 
					imageellipse($img,$elx,$ely,$elw,$elh,$cline);
				}
			}		

		// If asked, generate rectangular jamming lines (scrambling OCR).
			$nbrrl = ($this->config->item('sig_image_number_of_rectangle_jamming_lines') > -1 && $this->config->item('sig_image_number_of_rectangle_jamming_lines') < ($imgH/2)) ? $this->config->item('sig_image_number_of_rectangle_jamming_lines') : 0;
			if($nbrrl != 0){
				$rlwe = round(($imgW / 2) / $nbrrl);
				$rlhe = round(($imgH / 2) / $nbrrl);
				for($i=0;$i<$nbrrl;$i++){
					$rlsx = $rlwe * $i;
					$rlsy = $rlhe * $i;                                                       
					$rlex = $imgW - ($rlwe * $i); 
					$rley = $imgH - ($rlhe * $i); 
					imagerectangle($img,$rlsx,$rlsy,$rlex,$rley,$cline);
				}
			}		

		// If asked, generate polygonal jamming lines (scrambling OCR).
			$nbrpl = ($this->config->item('sig_image_number_of_polygonal_jamming_lines') > 2) ? $this->config->item('sig_image_number_of_polygonal_jamming_lines') : 0;
			if($nbrpl != 0){
				$poly = array ();
				for($i=0;$i<$nbrpl;$i++){
					$poly[] = rand(0,$imgW);
					$poly[] = rand(0,$imgH);
				}
				imagepolygon($img,$poly,$nbrpl,$cline);
			}		

		// Define wich characters to use to generate the code
			$string = ($this->config->item('sig_text_characters_string')) ? $this->config->item('sig_text_characters_string') : '0123456789';

		// Define the font to use to show the code
			$font = $this->config->item('sig_text_font');

		// Define where starts (horizontaly) the code in the picture
			$posx = ($this->config->item('sig_text_start_position')) ? $this->config->item('sig_text_start_position') : 0; 

		// Define the distance to leave between two characters in the code
			$spacing = ($this->config->item('sig_text_spacing')) ? $this->config->item('sig_text_spacing') : 0; 

		// Define a padding top and a opadding bottom for the characters of the code
			$vspace = ($this->config->item('sig_text_vspace') != 0) ? $this->config->item('sig_text_vspace') : 1; 

		// Initialize code
			$code = ''; 

		// Code generation loop
			for($i=0;$i<(($this->config->item('sig_text_number_of_characters') ? $this->config->item('sig_text_number_of_characters') : 4));$i++){
		// Generates the code while picking randomly in the characters list
    			$char= rand(0,strlen($string)-1);
    			$text = substr($string,$char,1);
		// Progressively memorizes the characters of the code 
    			$code .= $text;
		// Randomly calculate the shown size and angle of the character
    			$size=rand(($this->config->item('sig_text_font_size_min') ? $this->config->item('sig_text_font_size_min') : 14),($this->config->item('sig_text_font_size_max') ? $this->config->item('sig_text_font_size_max') : 16));
    			$angle=rand(($this->config->item('sig_text_character_angle_min') ? $this->config->item('sig_text_character_angle_min') : 0),($this->config->item('sig_text_character_angle_max') ? $this->config->item('sig_text_character_angle_max') : 0));
		// Define place (left or right) and distance of the shadow character
    			$dist = ($this->config->item('sig_text_shadow_distance')) ? $this->config->item('sig_text_shadow_distance') : 0;
    			$dist=($angle<0)?$dist*-1:$dist*1;
		// Randomly calculate a vertical position
    			$posy=rand($size+$vspace,$imgH-$vspace);
		// Generate first the shadow character (because under the text)
    			imagettftext($img, $size, $angle, $posx+$dist, $posy+$dist, $combr, $font, $text);
		// Generate the character in the text code
    			imagettftext($img, $size, $angle, $posx, $posy, $ctext, $font, $text);
		// Increment the horizontal position for the next generate character
    			$posx+=$size+$spacing;
		    }
     	
     	// Save the randomly generate code in the session
     		$newdata=array('sig_key'=>$code);
    		$this->obj =& get_instance();
    		$this->obj->db_session->set_userdata($newdata);   
 
		// Post the generated image and free memory
			($type == 'png') ? imagepng($img) : imagejpeg($img);
			imagedestroy($img);

    	}
    }
?>