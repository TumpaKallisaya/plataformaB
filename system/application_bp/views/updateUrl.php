<?php  	$this->load->view('doc_head');?>
<div class="main-wrapper">
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="content-widgets gray">
                <div class="widget-head bondi-blue">
                    <h3><?php echo $title1;?></h3>                    
                </div>
                <div class="widget-container">
                    <form method="post" id="chooseDateForm" action="<?php  echo $action; ?>" enctype="multipart/form-data">
    					<?php if($flag==1){?>
                            <div class="alert alert-success">
                                        <button type="button" class="close" data-dismiss="alert">x</button>
                                        <?php echo $mensaje;?>
                                        </div>
    					<?php }?>
                        <table>
    						<tr>
                                <td><b>Descripcion:</b></td>
                                <td><input type="text" name="Descripcion" value="<?php  echo $Descripcion;?>" required/></td>	
                                <td></td><td></td>
                                <td><b>Url:</b></td>
                                <td><input type="text" name="Url"  value="<?php  echo $Url;?>" required /></td>					
    						</tr>

    						<tr>
                                 <td><b>Tipo:</b></td>
                                <td>   <?php $js = 'required';?> <?php  echo form_dropdown('Tipo', $Tipo,$Tipo1,$js);?></td>
                                <td></td><td></td>
                                <td><b>Imagen:</b></td>
                                <td><img src="<?php echo base_url()."uploads/".$usu."/".$Imagen;?>" width="160" height="160"></td>                              
    						</tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td><td></td>
                                <td><b>Subir:</b></td>
                                <td><input type="file" name="Imagen"/></td>                              
                            </tr>
                        </table>
                        <table>
                            <tr>
                                <td><b>Descripcion del Servicicio/Aplicacion</b></td>
                                <td><input type="text" name="Referencia"  value="<?php  echo $Referencia;?>" required /></td>
                           
                            </tr>
                        </table>
                        <table>



    						<tr>
                                <td><b>Usuario Registro:</b></td>
                                <td><input type="text" name="usuario_reg"  value="<?php  echo $usuario_reg;?>" disabled /></td>
                                <td></td><td></td>
                                <td><b>Fecha Registro:</b></td>
                                <td><input type="text" name="fecha_reg"  value="<?php  echo $fecha_reg;?>" disabled /></td>
    						</tr>						
					
    						<tr>
                                <td><b>Usuario Modificacion:</b></td>
                                <td><input type="text" name="usuario_mod"  value="<?php  echo $usuario_mod;?>" disabled /></td>
                                <td></td><td></td>
                                <td><b>Fecha Modificacion:</b></td>
                                <td><input type="text" name="fecha_mod"  value="<?php  echo $fecha_mod;?>" disabled /></td>
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
