<?php  	$this->load->view('doc_head');?>
<div class="main-wrapper">
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="content-widgets gray">
                <div class="widget-head bondi-blue">
                    <h3><?php echo $title1;?></h3>                    
                </div>
                <div class="widget-container">
                    <form method="post" id="chooseDateForm" action="<?php  echo $action; ?>">
    					<?php if($flag==1){?>
                            <div class="alert alert-success">
                                        <button type="button" class="close" data-dismiss="alert">x</button>
                                        Usuario Modificado Correctamente
                                        </div>
    					<?php }?>
                        <table>
    						<tr>
                                <td><b>Login:</b></td>
                                <td><input type="text" name="login" id="grumble" value="<?php  echo $usuario;?>" required/></td>	
                                <td></td><td></td>
                                <td><b>Nombre Completo:</b></td>
                                <td><input type="text" name="descripcion_usuario" id="grumble" value="<?php  echo $descripcion_usuario;?>" required /></td>					
    						</tr>

    						<tr>
                                <td><b>E-mail:</b></td>
                                <td><input type="text" name="email" id="grumble" value="<?php  echo $email;?>" email required /></td>
                                <td></td><td></td>
                                <td><b>Rol:</b></td>
                                <td>   <?php $js = 'required';?> <?php  echo form_dropdown('Rol', $RolC,$rol1,$js);?></td>
    						</tr>

                            <tr>
                                <td><b>Aplicaciones:</b></td>
                                <td>
                                    <?php                                        
                                        foreach ($Aplicaciones as $k) {
                                            $check='';//echo $k->Id_Url;
                                            //echo $UserAplicaciones;
                                            if($UserAplicaciones){
                                                foreach ($UserAplicaciones as $n) {                                                
                                                    if($k->Id_Url==$n->IdUrl){$check='true';}
                                                }   
                                            }
                                                                                 
                                            echo form_checkbox('Aplicaciones[]', $k->Id_Url,  $check).$k->Descripcion."<br>";
                                        }
                                    ?>
                                </td>
                                <td></td><td></td>
                                <td><b>Servicios:</b></td>
                                <td>   
                                    <?php
                                        foreach ($Servicios as $k) {
                                            $check='';//echo $k->Id_Url;
                                            if($UserServicios){
                                                foreach ($UserServicios as $n) {                                                
                                                    if($k->Id_Url==$n->IdUrl){$check='true';}
                                                }      
                                            }
                                            echo form_checkbox('Servicios[]', $k->Id_Url,  $check).$k->Descripcion."<br>";
                                        }
                                    ?>
                                </td>
                            </tr>

    						<tr>
                                <td><b>Usuario Registro:</b></td>
                                <td><input type="text" name="usuario_reg" id="grumble" value="<?php  echo $usuario_reg;?>" disabled /></td>
                                <td></td><td></td>
                                <td><b>Fecha Registro:</b></td>
                                <td><input type="text" name="fecha_reg" id="grumble" value="<?php  echo $fecha_reg;?>" disabled /></td>
    						</tr>						
					
    						<tr>
                                <td><b>Usuario Modificacion:</b></td>
                                <td><input type="text" name="usuario_mod" id="grumble" value="<?php  echo $usuario_mod;?>" disabled /></td>
                                <td></td><td></td>
                                <td><b>Fecha Modificacion:</b></td>
                                <td><input type="text" name="fecha_mod" id="grumble" value="<?php  echo $fecha_mod;?>" disabled /></td>
    						</tr>						
					
    						
    						<tr>
    						<td></td>
    						<td>
    							<button type="submit" class="btn btn-success"><?php echo $boton1;?></button>

    						</td>
                            <td>                               
                            <!--<button class="btn" onClick="javascript:location.href='../../listUsuario/<?php echo $flag;?>'" type="button">Volver</button>-->
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
