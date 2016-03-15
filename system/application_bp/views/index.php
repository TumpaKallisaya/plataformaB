<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!--<!DOCTYPE html>
<html lang="en">-->
    <head>
        <meta charset="utf-8" />
        <!--<meta name="viewport" content="width=device-width, initial-scale=1.0" />-->
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <!--<title>Gebo Admin Panel</title>-->
        <title>ATT</title>
	
			<link rel="stylesheet" href="<?php echo base_url();?>theme/css/style1.css" />
            <link rel="stylesheet" href="<?php echo base_url();?>theme/css/bootstrap.min.css" />
            <link rel="stylesheet" href="<?php echo base_url();?>theme/css/bootstrap-responsive.min.css" />
            <link rel="stylesheet" href="<?php echo base_url();?>theme/css/blue.css" id="link_theme" />
            <link rel="stylesheet" href="<?php echo base_url();?>theme/css/style.css" />

	            <script src="<?php echo base_url();?>theme/js/jquery.min.js"></script>
				<script src="<?php echo base_url();?>theme/js/jquery.debouncedresize.min.js"></script>
				<script src="<?php echo base_url();?>theme/js/jquery.actual.min.js"></script>
				<script src="<?php echo base_url();?>theme/js/jquery.cookie.min.js"></script>
				<script src="<?php echo base_url();?>theme/js/ios-orientationchange-fix.js"></script>
				<script src="<?php echo base_url();?>theme/js/bootstrap.plugins.min.js"></script>					
				<script src="<?php echo base_url();?>theme/js/gebo_tables1.js"></script>
				<script src="<?php echo base_url();?>theme/lib/datatables/jquery.dataTables.min.js"></script>
				<script src="<?php echo base_url();?>theme/lib/datatables/jquery.dataTables.sorting.js"></script>	

				
          		<script>
					


					var xmlHttp
var url0="<?php echo base_url();?>"

function mostrarArea (str) {
   if(!str){str=0} xmlHttp=GetXmlHttpObject();if (xmlHttp==null) { alert ("Browser does not support HTTP Request"); return }
   url=url0+"index.php/person/mostrarArea";
   url=url+"/"+str; xmlHttp.onreadystatechange=ChangeArea;
   xmlHttp.open("POST",url,true);xmlHttp.send(null);
   }
function ChangeArea() {
   if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete") {  document.getElementById("Area").innerHTML=xmlHttp.responseText  } 
   }
function GetXmlHttpObject(){
  var xmlHttp=null;try { xmlHttp=new XMLHttpRequest();} catch (e) { try  { xmlHttp=new ActiveXObject("Msxml2.XMLHTTP"); } catch (e)  { xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");  } } return xmlHttp;
}
				</script>







	</head>
           
			
	<form method="post" action="<?php echo $action; ?>" enctype="multipart/form-data">
	
		<table>
			<tr>
				<td>Departamento:	</td>
				<td><?php 
							$js = 'onChange="mostrarArea(this.value)" id="Departamento"';
							echo form_dropdown('Departamento', $OptionsDepartamento, $Departamento,$js);?>	</td>
				<td></td>
			</tr>
			<tr>
				<td>Area de Cobertura:	</td>
				<td><div id="Area"><?php echo form_dropdown('Area', $OptionsArea, $Area,'');?></td>
				<td></td>
			</tr>
			<tr>
				<td>Razon Social:	</td>
				<td><input name="RazonSocial" type="text" id= "RazonSocial" value="<?php echo $RazonSocial; ?>"/>	</td>
				<td><input name="submit" type="submit" id= "button1" value="Buscar"/></td>
			</tr>

		</table>

	</form>	  				
		<?php echo $table;?>
		<br>
	
	



 
		


			
			
	</body>
</html>

