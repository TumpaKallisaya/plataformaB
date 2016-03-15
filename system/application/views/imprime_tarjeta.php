<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=1,initial-scale=1,user-scalable=1" />
    <title>Plataforma ATT</title>
    
    <!--<link href="http://fonts.googleapis.com/css?family=Lato:100italic,100,300italic,300,400italic,400,700italic,700,900italic,900" rel="stylesheet" type="text/css">-->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>theme/login/assets/bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>theme/login/assets/css/styles.css" />
    
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script>
        function justNumbers(e)
            {
            var keynum = window.event ? window.event.keyCode : e.which;
            if ((keynum == 8) || (keynum == 46))
            return true;
             
            return /\d/.test(String.fromCharCode(keynum));
            }
    </script>
</head>
<body>
    
    <section class="container">
        <section class="login-form">
        
        <section>
            <img src="<?php echo base_url();?>theme/assets/img/att.png" alt="" />
            <h1 style="color:white;">PLATAFORMA ATT</h1>
        </section>
    
    
        <div class="panel panel-default">
            <div class="panel-body">
                <p class="block-heading" >Verificaci&oacute;n de identidad</p>
                <form method="post" action="<?php  echo base_url().'index.php/person/verifica_tarjeta'.'/'.$f1.'/'.$c1.'/'.$f2.'/'.$c2.'/'.$f3.'/'.$c3;?>" > 
                    <div class="form-group">                    
                        <label>Ingrese el valor de cada coordenada de su tarjeta de seguridad:</label>  
                    </div>
										<table width="20%" align="center">
											<tr>
												<td>
													<label style="font-style: italic;font-weight: bold;font-size: 25px"><?php echo $f1.$c1; ?></label>
                                                    <input type="text" onkeypress="return justNumbers(event);" name="coor1" size="4" >
												</td>
												<td>
													-
												</td>
												<td>
													<label style="font-style: italic;font-weight: bold;font-size: 25px"><?php echo $f2.$c2; ?>
                                                    </label><input onkeypress="return justNumbers(event);" type="text" name="coor2"  size="4" >
												</td>
												<td>
													-
												</td>
												<td>
													<label style="font-style: italic;font-weight: bold;font-size: 25px"><?php echo $f3.$c3; ?></label>
                                                    <input type="text" onkeypress="return justNumbers(event);" name="coor3" size="4" >
												</td>
											</tr>	
										</table>
                    
                    
                    <br/>

                    <button type="submit" name="go" class="btn btn-primary btn-block">Ingresar</button>
                   
                </form>
            </div>
    
        </div>


    

        </section>
    </section>

    <script src="<?php echo base_url();?>theme/login/assets/bootstrap/js/jquery.min.js"></script>
    <script src="<?php echo base_url();?>theme/login/assets/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>