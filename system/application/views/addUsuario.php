<?php  	$this->load->view('doc_head');?>
<div class="main-wrapper">
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="content-widgets gray">
                <div class="widget-head bondi-blue">
                    <h3>Agregar Usuario</h3>
                </div>

                <div class="widget-container">
                    <form method="post" id="chooseDateForm" action="<?php  echo $action;?>" enctype="multipart/form-data">
                        <table>
    						<tr>
                                <td><label>Login:</label></td>
                                <td><input type="text" name="usuario" value="" required /></td>
    						</tr>
    						<tr>
                                <td><label>Password:</label></td>
                                <td><input type="password" name="passwd" id="pass" value="" required /></td>
    						</tr>
    						<tr>
                                <td><label>Confirmar Password:</label></td>
                                <td><input type="password" class=":same_as;pass" /></td>
    						</tr>
                            <tr>
                                <td><label>Nombre Completo:</label></td>
                                <td><input type="text" name="descripcion_usuario" value="" required /></td>
    						</tr>
                            <tr>
                                <td><label>E-mail:</label></td>
                                <td><input type="text" name="email" value="" required /></td>
                            </tr>
    						<tr>
                                <td><label>Rol:</label></td>
                                <?php $js = 'required'?>
                                <td><?php  echo form_dropdown('Rol', $Rol,'',$js);?></td>
    						</tr>				
    						<tr>
    						<td></td>
    						<td>
                                <button class="btn btn-primary" ><i class="icon-save"></i>Agregar</button>
    						</td>
    						</tr>
    					</table>				                                                    
                    </form>
                </div>
            </div>
        </div>
    </div>            
</div>

<?php   $this->load->view('doc_foot'); ?>
