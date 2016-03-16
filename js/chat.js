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
            var html = '<li id="'+data.id+'" onclick="getIdTema(this.id)"><a href="#">'+ data.tema +'</a></li>';
            document.getElementById('ultimoTema').value = data.id;
            $('#temasRecientes').html($('#temasRecientes').html()+html);
        }
    });
}

function getIdTema(str) {
  console.log('Este es el id del tema: ' + str);
  document.getElementById('idTemaCargado').value = str;
  $('#ultimoChatRec').val('').removeClass('value').removeAttr('value');
  document.getElementById('recibido').innerHTML="";
  
  $.getJSON(baseURL+'index.php/chat/get_tema_sel?id_usuario_de='+$('#id_usuario_de').val()+
            '&id_tema='+$('#idTemaCargado').val(), function(data){
                document.getElementById("temaConversacion").innerHTML = 'Tema: '+data.tema;
                
            });
  
  
  console.log("Recuperando lo que se escribio en el input: " + $('#idTemaCargado').val()); 
  carga_mensajes();
}
$('#submit').click(function(e){
    e.preventDefault();

    /*var $field = $('#mensaje');
    var data = $field.val();*/

    $('#mensaje').addClass('disabled').attr('disabled', 'disabled');
    enviarChat($('#mensaje').val(), function(){
        $('#mensaje').val('').removeClass('disabled').removeAttr('disabled');
    });
});

var enviarChat = function(mensaje, callback){
    $.getJSON(baseURL+'index.php/chat/enviar_chat?mensaje='+mensaje+'&id_usuario_de='+$('#id_usuario_de').val()
            +'&id_tema='+$('#idTemaCargado').val(), function(data){
                callback();
            });
}

var carga_mensajes = function(){
    
    console.log(baseURL+'index.php/chat/get_chats_recientes?id_usuario_de='+$('#id_usuario_de').val()+
            '&id_tema='+$('#idTemaCargado').val()+'&es_att'+$('#esAtt').val());
    $.getJSON(baseURL+'index.php/chat/get_chats_recientes?id_usuario_de='+$('#id_usuario_de').val()+
            '&id_tema='+$('#idTemaCargado').val()+'&es_att'+$('esAtt').val(), function(data){
                append_chat_data(data);
            });
}

var update_chats_constant = function(){
    if(typeof(request_timestamp) == 'undefined' || request_timestamp == 0){
        var offset = 60*15;
        request_timestamp = parseInt(Date.now()/1000 - offset);
    }
    console.log('Constante: '+baseURL+'index.php/chat/get_chats_constantes?id_usuario_de='+$('#id_usuario_de').val()+
            '&id_tema='+$('#idTemaCargado').val()+'&timestamp='+request_timestamp);
    $.getJSON(baseURL+'index.php/chat/get_chats_constantes?id_usuario_de='+$('#id_usuario_de').val()+
            '&id_tema='+$('#idTemaCargado').val()+'&timestamp='+request_timestamp, function(data){
                append_chat_data(data);
                var newIndex =  data.length-1;
                if(typeof(data[newIndex])!='undefined'){
                    request_timestamp = data[newIndex].timestamp;
                }
            });
}

var append_chat_data = function(chat_data){
    chat_data.forEach(function(data){
        var id_usuario = $('#id_usuario_de').val();
        console.log("id_usuario_de chat: "+data.id_usuario_de+" - usuario nativo: " + id_usuario);
        console.log('Id Chat: '+ data.id + " - Ultimo Chat Recibido: "+ document.getElementById('ultimoChatRec').value);
        var chatId = parseInt(data.id);
        var ultimoChatId;
        if(document.getElementById('ultimoChatRec').value == ''){
            ultimoChatId = 0;
        }else{
            ultimoChatId = parseInt(document.getElementById('ultimoChatRec').value);
        }
        if (chatId > ultimoChatId){
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
                document.getElementById('ultimoChatRec').value = data.id;
            }else{
                var html = '    <div class="row msg_container base_sent">';
                html += '           <div class="col-md-10 col-xs-10">';
                html += '               <div class="messages msg_sent">';
                html += '                   <p>'+data.mensaje+'</p>';
                html += '                   <time datetime="2009-11-13T20:00">'+data.dde+' • '+data.fecha_envio+'</time>';
                html += '               </div>';
                html += '           </div>';
                html += '           <div class="col-md-2 col-xs-2 avatar">';
                html += '               <img src="'+baseURL+'images/ope.png" class=" img-responsive ">';
                html += '           </div>';
                html += '       </div>';
                document.getElementById('ultimoChatRec').value = data.id;
            }
            $('#recibido').html($('#recibido').html()+html)
            document.getElementById('scroll').scrollTop=9999999;
        }
        console.log('Ultimo chatId ingresado: '+document.getElementById('ultimoChatRec').value);
    });
}



/**************************************/


setInterval(function(){
    update_temas();
    update_chats_constant();
}, 1500);

// para el menu acordion
$(' ul ul li ').click(function(e) {
    e.preventDefault();
    
    
    //var valorIdTema = $(this).closest('li').data('value');
    //console.log("Este es el id del tema: " + valorIdTema);
    console.log("Holaaaaaaaaaaaaa " );
    /*
    $('#ultimomsjrec').val('').removeClass('value').removeAttr('value');
    var value = $(this).closest('li').data('value');
    var usucont = "#usulist"+value;
    
    console.log("valorr: "+value + "   usuario: "+$(usucont).val());
    var $paraActual = $('#id_usuario_para').val();
    if($paraActual != $(usucont).val()){
        document.getElementById('recibido').innerHTML="";
        document.getElementById('id_usuario_para').value = $(usucont).val();
        carga_mensajes();
    }*/
    
});