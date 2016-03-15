<?php  $this->load->view('doc_head');?>
<div class="main-wrapper">
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="content-widgets gray">
                <div class="widget-head bondi-blue">
                    <h3><?php echo $title;?></h3>
                </div>
                <div class="message info">
                    <?php if($flag==1) echo $info_mensaje;?>  
                </div>
                <div class="widget-container">
                    
                    <?php  echo $add;?>
               	    <?php  echo $table2;?>
                </div>
            </div> 
        </div> 
                  
    </div>        
</div>
<?php  $this->load->view('doc_foot');?>
