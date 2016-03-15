/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$('#creaTema').click(function(e){
    e.preventDefault();
    console.log(baseURL+'index.php/chat/crearTemaChat?id_usuario_de='+$('#id_usuario_de').val()+
            '&tema_chat='+$('#temaChat').val()+'&cod_seccion='+$('#seccion').val()+
            '&id_operador='+$('#idOperador').val());
    $.getJSON(baseURL+'index.php/chat/crearTemaChat?id_usuario_de='+$('#id_usuario_de').val()+
            '&tema_chat='+$('#temaChat').val()+'&cod_seccion='+$('#seccion').val()+
            '&id_operador='+$('#idOperador').val(), function(data){
                actualiza_tema(data);
            });
    $('#myModal').modal('hide');
});

var update_temas = function(){
    var esAtt = $('#esAtt').val();
    console.log('Es Att: '+esAtt);
    var ultTema;
    if(document.getElementById('ultimoTema').value == ''){
        ultTema = 0;
    }else{
        ultTema = parseInt(document.getElementById('ultimoTema').value);
    }
    if(esAtt === 'si'){
        console.log(baseURL+'index.php/chat/get_temasAttAbiertos?id_usuario='+$('#id_usuario_de').val()+
            '&ultimo_tema='+ultTema);
        $.getJSON(baseURL+'index.php/chat/get_temasAttAbiertos?id_usuario='+$('#id_usuario_de').val()+
            '&ultimo_tema='+ultTema, function(data){
                append_lista_abiertos(data);
            });
    }else{
        console.log(baseURL+'index.php/chat/get_temasOpeAbiertos?id_usuario='+$('#id_usuario_de').val()+
            '&ultimo_tema='+ultTema);
        $.getJSON(baseURL+'index.php/chat/get_temasOpeAbiertos?id_usuario='+$('#id_usuario_de').val()+
            '&ultimo_tema='+ultTema, function(data){
                append_lista_abiertos(data);
            });
    }
}



var append_lista_abiertos = function(listabiertos_data){
    listabiertos_data.forEach(function(data){
        console.log('un datoooo: '+data.tema);
        var ultTema;
        var dataid = parseInt(data.id);
        if(document.getElementById('ultimoTema').value == ''){
            ultTema = 0;
        }else{
            ultTema = parseInt(document.getElementById('ultimoTema').value);
        }
        if(dataid > ultTema){
            var html = '<li data-value="'+data.id+'"><a href="#">'+ data.tema +'</a></li>';
            document.getElementById('ultimoTema').value = data.id;
            $('#temasRecientes').html($('#temasRecientes').html()+html);
        }
    });
}


/**************************************/

var enviarChat = function(mensaje, callback){
    $.getJSON(baseURL+'index.php/chat/enviar_mensaje?mensaje='+mensaje+'&id_usuario_de='+$('#id_usuario_de').val()
            +'&id_usuario_para='+$('#id_usuario_para').val(), function(data){
                callback();
            });
}

var actualiza_tema = function(data){
    /* var html = '<h5>';
    html += data.tema;
    html += '</h5>';
    $('#tittema').html($('#tittema').html()+html)*/
}

var append_chat_data = function(chat_data){
    chat_data.forEach(function(data){
        var id_usuario = $('#id_usuario_de').val();
        console.log("aqui el de: "+data.id_usuario_de+" y el usuario: " + id_usuario);
        console.log('data_id : '+ data.id + "ultimo id en el input: "+ document.getElementById('ultimomsjrec').value);
        var dataid = parseInt(data.id);
        var ultimomsjid;
        if(document.getElementById('ultimomsjrec').value == ''){
            ultimomsjid = 0;
        }else{
            ultimomsjid = parseInt(document.getElementById('ultimomsjrec').value);
        }
        if (dataid > ultimomsjid){
            if(data.id_usuario_de === id_usuario){
                var html = '<div class="row msg_container base_receive">';
                html += '       <div class="col-md-2 col-xs-2 avatar">';
                html += '           <img src="'+baseURL+'images/ope.png" class=" img-responsive ">';
                html += '       </div>';
                html += '       <div class="col-md-10 col-xs-10">';
                html += '           <div class="messages msg_receive">';
                html += '               <p>'+data.mensaje+'</p>';
                html += '               <time datetime="2009-11-13T20:00">'+data.dde+' • '+data.fecha_envio+'</time>';
                html += '           </div>';
                html += '       </div>';
                html += '   </div>';
                document.getElementById('ultimomsjrec').value = data.id;
            }else{
                var html = '    <div class="row msg_container base_sent">';
                html += '           <div class="col-md-10 col-xs-10">';
                html += '               <div class="messages msg_sent">';
                html += '                   <p>'+data.mensaje+'</p>';
                html += '                   <time datetime="2009-11-13T20:00">'+data.dpa+' • '+data.fecha_envio+'</time>';
                html += '               </div>';
                html += '           </div>';
                html += '           <div class="col-md-2 col-xs-2 avatar">';
                html += '               <img src="'+baseURL+'images/ope.png" class=" img-responsive ">';
                html += '           </div>';
                html += '       </div>';
                document.getElementById('ultimomsjrec').value = data.id;
            }
            $('#recibido').html($('#recibido').html()+html)
            document.getElementById('scroll').scrollTop=9999999;
        }
        console.log('Ultimo id ingresado: '+document.getElementById('ultimomsjrec').value);
    });
    //$('#algo').animate({scrollTop:$('#algo').height()}, 1000);
    
}




var update_chats = function(){
    if(typeof(request_timestamp) == 'undefined' || request_timestamp == 0){
        var offset = 60*15;
        request_timestamp = parseInt(Date.now()/1000 - offset);
    }
    console.log('<?php echo base_url();?>index.php/chat/get_mensajes?id_usuario_de='+$('#id_usuario_de').val()+
            '&id_usuario_para='+$('#id_usuario_para').val()+'&timestamp='+request_timestamp);
    $.getJSON(baseURL+'index.php/chat/get_mensajes?id_usuario_de='+$('#id_usuario_de').val()+
            '&id_usuario_para='+$('#id_usuario_para').val()+'&timestamp='+request_timestamp, function(data){
                append_chat_data(data);
                var newIndex = data.length-1;
                if(typeof(data[newIndex])!='undefined'){
                    request_timestamp = data[newIndex].timestamp;
                }
            });
}

var carga_mensajes = function(){
    
    console.log(baseURL+'index.php/chat/get_mensajes_historicos?id_usuario_de='+$('#id_usuario_de').val()+
            '&id_usuario_para='+$('#id_usuario_para').val());
    $.getJSON(baseURL+'index.php/chat/get_mensajes_historicos?id_usuario_de='+$('#id_usuario_de').val()+
            '&id_usuario_para='+$('#id_usuario_para').val(), function(data){
                append_chat_data(data);
            });
}




$('#submit').click(function(e){
    e.preventDefault();

    var $field = $('#mensaje');
    var data = $field.val();

    $field.addClass('disabled').attr('disabled', 'disabled');
    enviarChat(data, function(){
        $field.val('').removeClass('disabled').removeAttr('disabled');
    });
});

$('#temasRecientes li a ').click(function(e) {
    e.preventDefault();
    
    $('#ultimomsjrec').val('').removeClass('value').removeAttr('value');
    var value = $(this).closest('li').data('value');
    var usucont = "#usulist"+value;
    
    console.log("valorr: "+value + "   usuario: "+$(usucont).val());
    var $paraActual = $('#id_usuario_para').val();
    if($paraActual != $(usucont).val()){
        document.getElementById('recibido').innerHTML="";
        document.getElementById('id_usuario_para').value = $(usucont).val();
        carga_mensajes();
    }
    
});



$('#lisTemasRec').click(function(e){
    
    console.log(baseURL+'index.php/chat/getListaAbiertosManual?id_usuario='+$('#id_usuario_de').val());
    $.getJSON(baseURL+'index.php/chat/get_lista_abiertos_manual?id_usuario='+$('#id_usuario_de').val()
    +'&val='+'null', function(data){
                append_lista_abiertos(data);
            });
});

setInterval(function(){
    update_chats();
    update_temas();
}, 1500);

// para el menu acordion
