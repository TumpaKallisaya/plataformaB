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
		    	<form method="post" action="<?php echo $action;?>" role="login">
					<div class="form-group">
						<label>Usuario</label>
						<input type="text" name="usuario" onkeyup="this.value=this.value.toLowerCase();" required class="form-control" />
					</div>
					
					<div class="form-group">
						<label>Clave</label>
						<input type="password" name="password" required value="<?php echo $usuario; ?>" class="form-control" />
					</div>
					
					
					<button type="submit" name="go" class="btn btn-primary btn-block">Ingresar</button>
				</form>
		  	</div>
		  	<div class="panel-footer">
		  		<font color="red"> * <?php  echo $error;?></red>
		  		<a href="#">Olvidaste tu clave?</a>
		  	</div>
		</div>

		</section>
	</section>
	
	<script src="<?php echo base_url();?>theme/login/assets/bootstrap/js/jquery.min.js"></script>
	<script src="<?php echo base_url();?>theme/login/assets/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>