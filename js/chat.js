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
    $('#temaChat').val('').removeClass('value').removeAttr('value');
    $('#seccion').val('').removeClass('value').removeAttr('value');
});

var update_temas = function(){
    var esAtt = $('#esAtt').val();
    //console.log('Es Att: '+esAtt);
    
    var numLiTemas = document.getElementById("products").getElementsByTagName("li").length;
    console.log('Numero de lis: ' + numLiTemas);
    if(esAtt === 'si'){
        $.getJSON(baseURL+'index.php/chat/getNroTemasActualesAtt?id_usuario='+$('#id_usuario_de').val()+
            '&valor=null', function(data){
                if(numLiTemas > data['nroTemasAct']){
                    $('#ultimoTema').val('').removeClass('value').removeAttr('value');
                    document.getElementById('temasRecientes').innerHTML="";
                }
            });
    }else{
        $.getJSON(baseURL+'index.php/chat/getNroTemasActualesOpe?id_usuario='+$('#id_usuario_de').val()+
            '&valor=null', function(data){
                if(numLiTemas > data['nroTemasAct']){
                    $('#ultimoTema').val('').removeClass('value').removeAttr('value');
                    document.getElementById('temasRecientes').innerHTML="";
                }
            });
    }    
    
    var ultTema;
    if(document.getElementById('ultimoTema').value == ''){
        ultTema = 0;
    }else{
        ultTema = parseInt(document.getElementById('ultimoTema').value);
    }
    if(esAtt === 'si'){
        //console.log(baseURL+'index.php/chat/get_temasAttAbiertos?id_usuario='+$('#id_usuario_de').val()+
          //  '&ultimo_tema='+ultTema);
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
            var html = '<li id="'+data.id+'" onclick="getIdTema('+data.id+')" data-value="'+data.fec_ult+'"><a href="#"><div style="margin-top:-30px;">'+ data.tema +'</div></a> <div style="float:right; margin-right:4px !important; font-size:10px !important;" id="ultMensajeUsu">'+data.descripcion_usuario+' - '+data.fec_ult+'</div></li>';
            document.getElementById('ultimoTema').value = data.id;
            $('#temasRecientes').prepend(html);
        }
    });
}

function getIdTema(str) {
  console.log('Este es el id del tema: ' + str);
  document.getElementById('idTemaCargado').value = str;
  document.getElementById('idTemaCargadoAdj').value = str;
  $('#ultimoChatRec').val('').removeClass('value').removeAttr('value');
  document.getElementById('recibido').innerHTML="";
  
  $.getJSON(baseURL+'index.php/chat/get_tema_sel?id_usuario_de='+$('#id_usuario_de').val()+
            '&id_tema='+$('#idTemaCargado').val(), function(data){
                document.getElementById("temaConversacion").innerHTML = 'Tema: '+data.tema;
                
            });
  
  
  console.log("Recuperando lo que se escribio en el input: " + $('#idTemaCargado').val()); 
  carga_mensajes();
}

var update_temas_antiguos = function(){
    var esAtt = $('#esAtt').val();
    
    //console.log(baseURL+'index.php/chat/get_temasAttAbiertos?id_usuario='+$('#id_usuario_de').val()+
      //  '&ultimo_tema='+ultTema);
    var numLiTemasAntiguos = document.getElementById("service").getElementsByTagName("li").length;  
    console.log(baseURL+'index.php/chat/getNroTemasAntiguos?id_usuario='+$('#id_usuario_de').val()+
            '&es_att='+esAtt);
    $.getJSON(baseURL+'index.php/chat/getNroTemasAntiguos?id_usuario='+$('#id_usuario_de').val()+
            '&es_att='+esAtt, function(data){
                //console.log('nro temas antiguos: ' + data['nroTemasAnt']);
                if(numLiTemasAntiguos != data['nroTemasAnt']){
                    document.getElementById('temasAntiguos').innerHTML="";
                    $.getJSON(baseURL+'index.php/chat/get_temasAntiguos?id_usuario='+$('#id_usuario_de').val()+
                        '&es_att='+esAtt, function(data){
                            append_lista_antiguos(data);
                        });
                }
                
            });
            
    
}

var append_lista_antiguos = function(listantiguos_data){
    listantiguos_data.forEach(function(data){
        console.log('un datoooo: '+data.tema);
        var html = '<li id="'+data.id+'"><a href="" onclick="imprimirRepAnt('+data.id+'); false;" target="_blank"> <div style="margin-top:-30px;">'+ data.tema +'</div></a> <div style="float:right; margin-right:4px !important; font-size:10px !important;">'+data.fecha_ini+' - '+data.fecha_fin+'</div> </li>';
        $('#temasAntiguos').html($('#temasAntiguos').html()+html);
    });
}

function imprimirRepAnt(id_tema){
    window.location.href = "printChatPdf?id_tema="+id_tema+"&arch=null";
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
    //console.log('Constante: '+baseURL+'index.php/chat/get_chats_constantes?id_usuario_de='+$('#id_usuario_de').val()+
            //'&id_tema='+$('#idTemaCargado').val()+'&timestamp='+request_timestamp);
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
        //console.log("id_usuario_de chat: "+data.id_usuario_de+" - usuario nativo: " + id_usuario);
        //console.log('Id Chat: '+ data.id + " - Ultimo Chat Recibido: "+ document.getElementById('ultimoChatRec').value);
        var chatId = parseInt(data.id);
        var ultimoChatId;
        if(document.getElementById('ultimoChatRec').value == ''){
            ultimoChatId = 0;
        }else{
            ultimoChatId = parseInt(document.getElementById('ultimoChatRec').value);
        }
        if (chatId > ultimoChatId){
            if(data.id_usuario_de === id_usuario){
                if (data.mensaje == ''){
                    var html = '<div class="row msg_container base_receive">';
                        html += '       <div class="col-md-2 col-xs-2 avatar">';
                        html += '           <img src="'+baseURL+'images/ope.png" class=" img-responsive ">';
                        html += '       </div>';
                        html += '       <div class="col-md-10 col-xs-10">';
                        html += '           <div class="messages msg_receive">';
                        html += '               <a href="" onclick="downloadAdjChat('+data.id+'); return false;"> <span class="fa fa-download"></span></a>';
                        html += '               <time datetime="2009-11-13T20:00">'+data.dde+' • '+data.fecha_envio+'</time>';
                        html += '           </div>';
                        html += '       </div>';
                        html += '   </div>';
                }else{
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
                }
                document.getElementById('ultimoChatRec').value = data.id;
            }else{
                if(data.mensaje == ''){
                    var html = '    <div class="row msg_container base_sent">';
                        html += '           <div class="col-md-10 col-xs-10">';
                        html += '               <div class="messages msg_sent">';
                        html += '               <a href="" onclick="downloadAdjChat('+data.id+'); return false;"> <span class="fa fa-download"></span></a>';
                        html += '                   <time datetime="2009-11-13T20:00">'+data.dde+' • '+data.fecha_envio+'</time>';
                        html += '               </div>';
                        html += '           </div>';
                        html += '           <div class="col-md-2 col-xs-2 avatar">';
                        html += '               <img src="'+baseURL+'images/ope.png" class=" img-responsive ">';
                        html += '           </div>';
                        html += '       </div>';
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
                }
                document.getElementById('ultimoChatRec').value = data.id;
            }
            $('#recibido').html($('#recibido').html()+html)
            document.getElementById('scroll').scrollTop=9999999;
        }
        //console.log('Ultimo chatId ingresado: '+document.getElementById('ultimoChatRec').value);
    });
}

$(function() {
    $('#upload_file').submit(function(e) {
        e.preventDefault();
        $.ajaxFileUpload({
            url             :baseURL + 'index.php/chat/subirArchivoChat', 
            secureuri       :false,
            fileElementId   :'userfile',
            dataType: 'JSON',
            success : function (data)
            {
                var obj = jQuery.parseJSON(data);
                if(obj['status'] == 'success'){
                    //$('#msjPorsiacaso').html(obj['msg']);
                    console.log('Logro subir el archivo: '+obj['name']);
                    console.log(baseURL+'index.php/chat/guardarArchAdj?id_usuario_de='+$('#id_usuario_de').val()+
                        '&id_tema='+$('#idTemaCargado').val()+'&path='+obj['path']+
                        '&archivo='+obj['name']+'&size='+obj['size']);
                    $.getJSON(baseURL+'index.php/chat/guardarArchAdj?id_usuario_de='+$('#id_usuario_de').val()+
                        '&id_tema='+$('#idTemaCargado').val()+'&path='+obj['path']+
                        '&archivo='+obj['name']+'&size='+obj['size'],
                        function(data){
                            //document.getElementById("temaConversacion").innerHTML = 'Tema: '+data.tema;
                    });
                    $('#userfile').val('').removeClass('value').removeAttr('value');
                    $('#upload_file').modal('hide');
                }
                else{
                    console.log('No logro subir el archivo');
                    //$('#files').html('No logro subir el archivo');
                }
            }
        });
        return false;
    });
});

function downloadAdjChat(id){
    console.log('Id: '+id);
    console.log(baseURL+"index.php/chat/downloadAdjChat?id_chat="+id+"&arch=null");
    window.location.href = "downloadAdjChat?id_chat="+id+"&arch=null";
}

function derivarConvSec(){
    $.getJSON(baseURL+'index.php/chat/derivarChat?id_tema_actual='+$('#idTemaCargado').val()+
                        '&nueva_seccion='+$('#seccionDerivar').val(),
                        function(data){
                            //document.getElementById("temaConversacion").innerHTML = 'Tema: '+data.tema;
                            $('#ultimoChatRec').val('').removeClass('value').removeAttr('value');
                            $('#idTemaCargado').val('').removeClass('value').removeAttr('value');
                            document.getElementById("temaConversacion").innerHTML = 'Bienvenidos al Chat de la ATT!';
                            document.getElementById('recibido').innerHTML="";
                            $('#modalDerivar').modal('hide');
                            $('#seccionDerivar').val('').removeClass('value').removeAttr('value');
                    });
}

function finalizarConversacion(){
    $.getJSON(baseURL+'index.php/chat/finalizarChat?id_tema='+$('#idTemaCargado').val()+
                        '&valor=null',
                        function(data){
                            $('#ultimoChatRec').val('').removeClass('value').removeAttr('value');
                            $('#idTemaCargado').val('').removeClass('value').removeAttr('value');
                            //$('#ultimoTema').val('').removeClass('value').removeAttr('value');
                            document.getElementById("temaConversacion").innerHTML = 'Bienvenidos al Chat de la ATT!';
                            //document.getElementById('temasRecientes').innerHTML="";
                            document.getElementById('recibido').innerHTML="";
                            $('#modalFinalizar').modal('hide');
                    });
}

var verificaNuevoMensaje = function(){
    var nums = document.getElementById("temasRecientes");
    var listItem = nums.getElementsByTagName("li");
    
    console.log('Nro de lis: ' + listItem.length);
    
    for (var i = 0; i < listItem.length;i++){
        var idTema = listItem[i].id;
        var antiguaFecha = $('#'+listItem[i].id+'').attr('data-value');
        
        console.log('tema cargado: '+idTema+' fecha: ' + antiguaFecha)
        console.log('valor: ' + document.getElementById(listItem[i].id+' ultMensajeUsu').innerHTML);
        
        //console.log('valor actual del div: '+$('#temasRecientes li #'+listItem[i]+' #ultMensajeUsu').val());
        /*$.getJSON(baseURL+'index.php/chat/verifNuevoMensaje?id_tema='+idTema+'&id_usuario='+$('#id_usuario_de').val(),
                        function(data){
                            if (antiguaFecha !== data.fec_ult){
                                $('#temasRecientes li #'+listItem[i]+' #ultMensajeUsu').val();
                                //document.getElementById('temasRecientes').innerHTML="";
                            }
                    });*/
    }
}

// para el nuevo menu
var $submenu = $('.submenu');
  var $mainmenu = $('.mainmenu');
  $submenu.hide();
  $submenu.first().delay(400).slideDown(700);
  $submenu.on('click','li', function() {
    $submenu.siblings().find('li').removeClass('chosen');
    $(this).addClass('chosen');
  });
  $mainmenu.on('click', 'li', function() {
    $(this).next('.submenu').slideToggle().siblings('.submenu').slideUp();
  });
  $mainmenu.children('li:last-child').on('click', function() {
    $mainmenu.fadeOut().delay(500).fadeIn();
  });

/**************************************/


setInterval(function(){
    update_temas();
    update_temas_antiguos();
    update_chats_constant();
    verificaNuevoMensaje();
}, 1500);
