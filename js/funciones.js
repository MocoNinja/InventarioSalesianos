/**
 * Created by  on 06/02/2015.
 *
 */
//Funciones jquery
(function ( $ ) {
    $.extend({
        callAjax: function(form,response_type,redirect,callbackFunction,custom_url) {

            var url;
            var formulario;

            if(!custom_url)
            {
                formulario=form.serialize();
                url = form.attr("action");

            }
            else
            {
                formulario={};

                var values, index;

                // Get the parameters as an array
                values = form.serializeArray();

                // Find and replace `content` if there
                for (index = 0; index < values.length; ++index) {

                    if (values[index].name != "form[_token]") {
                        values[index].value = "";
                    }
                }
                // Add it if it wasn't there
                if (index >= values.length) {
                    values.push({
                        name: "form[activo]",
                        value: 1
                    });
                }
                // Convert to URL-encoded string
                formulario = jQuery.param(values);

                url=custom_url;
            }

            var jqxhr = $.ajax({
                url: url,
                data: formulario,
                type: 'post',
                dataType: response_type,

                beforeSend: function () {
                    if(response_type=='json') {
                        if(url.search("filterusers") > 0)
                        {
                            $("#filter_user_form_button").html("<span class='glyphicon glyphicon-refresh'></span> Filtrando");
                            var $icon = $("#filter_user_form_button").find( ".glyphicon-refresh" ), animateClass = "icon-refresh-animate";
                            $icon.addClass( animateClass );

                        }
                        else {
                            //waitingDialog.show('Cargando Datos');
                        }
                    }

                }

            }).done(function (response)
            {
                if(response_type=='json')
                {
                    if(response.status == "ok" && redirect)
                        window.location.href = response.url;
                }
                callbackFunction.call(this, response);


            }).fail(function (jqXHR, textStatus, errorThrown) {
                waitingDialog.hide();
            })
        },
        callAjaxGet: function(url,redirect,callbackFunction,type) {
            var html;
            if(!type) type='json';
            var jqxhr = $.ajax({
                url: url,
                type: 'GET',
                dataType: type,
                beforeSend: function ()
                {

                }
            }).done(function (response)
            {
                if(response.status == "ok" && redirect)
                    window.location.href = response.url;

                callbackFunction.call(this, response);
            }).fail(function (jqXHR, textStatus, errorThrown) {

                console.log(errorThrown);
                if(url =="/login/check")
                    location.reload();
            });
        },
        cargaUsers: function(data){
            //var datos = JSON.parse(data);
            $("#filter_user_form_button").html("Filtrar");
            $("#tableusers tbody").remove();
            $("#tableusers thead").remove();
            var html_head='';
            var html = "<tbody>";

           if($.isEmptyObject(data)==false)
           {
               html_head='<thead><th>Nombre</th><th>Email</th><th>Activo</th></thead>';
               $.each(data, function(i, item){
                    //console.log(data[i]);
                   if(data[i].id && data[i].id.trim()!='1')
                   {
                       subUserButton = "";
                       subUserDisplayButton = "";
                       '<button id="edituser" type="button" data-url = "'+data[i].ruta+'" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-edit"></span> Editar</button>';
                       if(data[i].subusers == 1){
                           if(data[i].hasSubUsers >0){
                               subUserDisplayButton = '<button title="Muestra los subusuarios" style="margin-right:2%;" data-id="'+data[i].id.trim()+'" type="button" data-toggle="modal" data-target="#myModalSubUsers" class="btn btn-xs btn-default displaysubusers"><i class="fa fa-user"></i></button>';
                               subUserButton = '<button data-toggle="tooltip" title="crear subusuario" id="createsubuser" style="margin-right:2%;" type="button" data-url= "/admin/subusercreate/'+data[i].id.trim()+'/" class="btn btn-xs btn-default"><i class="fa fa-user-plus"></i></button>';
                           }else{
                               subUserButton = '<button data-toggle="tooltip" title="crear subusuario" id="createsubuser" style="margin-right:2%;" type="button" data-url= "/admin/subusercreate/'+data[i].id.trim()+'/" class="btn btn-xs btn-default"><i class="fa fa-user-plus"></i></button>';
                           }
                       }
                       $promotionBandos="";

                       if(data[i].bandos == 1){
                           if(data[i].promotion){
                               $promotionBandos = '<button data-toggle="tooltip" data-placement="top" title="'+data[i].expedition+'" id="createsubuser" style="margin-right:2%;" type="button" data-url= "/promotion/'+data[i].id.trim()+'/" class="btn btn-xs btn-default btn-success expedition"><i class="fa fa fa-bullhorn"></i></button>';

                           }else{
                               $promotionBandos = '<button data-toggle="tooltip"  title="Periodo de bandos de pruebas desactivado"  class="btn btn-xs btn-default btn-danger expedition" id="createsubuser" style="margin-right:2%;"   type="button" data-url= "/promotion/'+data[i].id.trim()+'/"><i class="fa fa fa-bullhorn"></i></button>';
                           }
                       }

                       $salasLimited="";
                       if(data[i].reservas == 1){
                           if(data[i].salasLimited == 1){
                               $salasLimited = '<button data-toggle="tooltip" data-placement="top" title="Limite de salas por usuario" id="linkedButton" style="margin-right:2%;" type="button" data-url= "/salaLimit/'+data[i].salasLimited.trim()+'/'+data[i].id.trim()+'/" class="btn btn-xs btn-default"><span class="ionicons ion-ios-calendar-outline"></span></button>';
                           }else{
                               $salasLimited = '<button id="linkedButton"  style="margin-right:2%; background-color: #BBBABA;" type="button" data-url= "/salaLimit/'+data[i].salasLimited.trim()+'/'+data[i].id.trim()+'/" class="btn btn-xs btn-default"><span class="ionicons ion-ios-calendar-outline"></span></button>';
                           }
                       }

                       html+=
                           '<tr class="hovered-tr"  id="main-row-'+data[i].id.trim()+'"  data-rowid="'+data[i].id.trim()+'">'+

                               '<td>'+data[i].nombre.trim()+' '+data[i].apellidos.trim()+'</td>'+
                              // '<td>'+ data[i].email.trim() +'</td>'+
                               '<td>'+ data[i].activo.trim() + '</td>'+
                               '<td style="" class="hovered-td td-n-'+data[i].id.trim()+'">'+
                                    '<button id="edituser" type="button"  style="margin-right:2%;" data-url = "'+data[i].ruta+'" class="btn btn-xs btn-default"> Editar</button>'+
                                    subUserDisplayButton+
                                    subUserButton+
                                    $promotionBandos+
                                    $salasLimited+

                                '</td>'
                   }
               });
           }
           else
           {
               html +='<tr><td colspan="3" class="danger">No se encontraraon coincidencias</td></tr>';
           }

            html += "</tbody>";
            $("#tableusers").append(html_head+html);

            $(".dropzone-single").dropzone({ url: "/admin/uploadfile",  parallelUploads: 8, uploadMultiple: false});
            $(".mensaje").popover({
                html: true,
                title: function(){
                    return $("#popover-head"+$(this).data("id")).html();
                },
                content: function(){
                    return $("#popover-content"+$(this).data("id")).html();
                },
                    placement: "left"
            });
        },

        hideAlltrList: function()
        {
            $('.uploader-single-container').hide("fast");
            $('.showdocs-single-container').hide("fast");
            $('.showmsgs-single-container').hide("fast");

        },

        limpiaFormMessages: function(){
            $(".mensaje").val("");
            $(".asunto").val("");
        },
        messageVisto: function(url,mensaje,mensaje_div){
            var jqxhr = $.ajax({
                url: url,
                data: mensaje ,
                type: 'POST',
                dataType: 'json',
                beforeSend: function ()
                {

                }
            }).done(function (response)
            {
                if(response.status == "ok")
                {
                    mensaje_div.removeClass("info");
                    mensaje_div.addClass("success");
                }
                //callbackFunction.call(this, response);
            }).fail(function (jqXHR, textStatus, errorThrown) {
                
            });
        },
        notification: function(tipo,time,mensaje)
        {
            var notificacion = $(".alert");
            notificacion.hide();
            notificacion.html(mensaje);
            notificacion.addClass("alert-"+tipo).show();
            setTimeout(function() {
                notificacion.hide();

            }, time);
        },
        hideClosestRow: function(element)
        {
            $(element).closest('tr').hide("slow");
        },
        handleAjaxResponse: function(response)
        {
            if(response.status == "ok")
            {
                $.notification("warning",5000,"<strong>Éxito</strong>");
            }
            else if(response.status=="ko" && response.message)
            {
                $.notification("warning",5000,"<strong>"+response.message+"</strong>");
                console.log(response.url);


            }
            else
            {
                $.notification("warning",5000,"<strong>Error</strong>");
            }
        },
        ajaxSessionCheck: function()
        {
            //cada 3 segundos checkeamos el login
            setInterval(function(){
                $.callAjaxGet('/login/check',false,function(response){

                    if(response.status != "logged")
                        window.location.href = '/';

                });
                },3000);
        },
        ajaxMessagesCheck: function()
        {
            //cada 3 segundos checkeamos el login
            setInterval(function(){
                /*$.callAjaxGet('/login/check',false,function(response){

                    if(response.status != "logged")
                        window.location.href = '/';

                });*/
            },3000);
        },
        ajaxDocuemntsCheck: function()
        {
            //cada 3 segundos checkeamos el login
            setInterval(function(){
                /*$.callAjaxGet('/login/check',false,function(response){

                 if(response.status != "logged")
                 window.location.href = '/';

                 });*/
            },3000);
        },
        initIntervalCheks: function(){
            //$.ajaxSessionCheck();
            $.ajaxMessagesCheck();
            $.ajaxDocuemntsCheck();
        },


        //funciones de firma

        initFirmaApplet: function(dataTableUserFiles)
        {

            MiniApplet.cargarMiniApplet('http://firmaloserver.devecomputer.es/miniapplet-full_1_2');

           var timer_control_applet = setInterval(function () {
                            if($.isAppletLoaded())
                            {
                                $.checkAppletLoadedUI();

                                window.clearInterval(timer_control_applet);

                            }

                        },
                        300);

        },
        loadfirmaApplet: function ()
        {

            $.getScript( "/afirma-applet-js/miniapplet.js" )
                .done(function( script, textStatus ) {
                   if(textStatus=="success")
                   {
                       console.log("script loaded");

                       $.initFirmaApplet();

                   }
                })
                .fail(function( jqxhr, settings, exception ) {
                    console.log("Error de carga del script del applet");
                });

        },

        isAppletLoaded: function()
        {

            try
            {
                if(typeof MiniApplet === "undefined" && typeof MiniApplet.clienteFirma.echo === "function") {
                    // safe to use the function
                    return false;
                }
                else
                {
                    MiniApplet.clienteFirma.echo();

                    return true;
                }
            }
            catch(e)
            {
                return false;
            }


        },
        getAppletError: function()
        {
           return  MiniApplet.getErrorType();
        },
        manageAppletException: function(errorType, errorMsg)
        {

             if (errorType === "es.gob.afirma.keystores.AOCertificatesNotFoundException" )
             {
                 $(".panel-firma-status").removeClass("panel-success").removeClass("panel-warning").addClass("panel-danger");
                 $(".panel-firma-status .panel-heading").html("<span><icon class='glyphicon glyphicon-flag'></icon>No hay certificados disponibles</span>");

             }
        },
        checkAppletLoadedUI: function()
        {
            if(!$.isAppletLoaded())
            {
                $('.firma-file').hide();
                $(".panel-firma-status").removeClass("panel-success").addClass("panel-warning");
                $(".panel-firma-status .panel-heading").html("<span><icon class='glyphicon glyphicon-flag'></icon> Necesitas Cargar applet para firmar contratos</span><span><button class='btn btn-primary button-load-applet right'>Cargar</button></span>");

            }
            else
            {

                $('.firma-file').show().animate("fast");
                $(".panel-firma-status").removeClass("panel-warning").addClass("panel-success").animate("fast");
                $(".panel-firma-status .panel-heading").html("<span><icon class='glyphicon glyphicon-ok'></icon> Complemento de firma Cargado</span>");

            }

        },

         getSignParams: function()
        {
            /*var signparams = {
                format:"CAdES-EPES",
                algoritm: "SHA512withRSA",
                params:"serverUrl=http://firmaloserver.devecomputer.es/afirma-server-triphase-signer/SignatureService" +
                "\npolicyIdentifier=2.16.724.1.3.1.1.2.1.9"+
                "\npolicyIdentifierHash=G7roucf600+f03r/o0bAOQ6WAs0="+
                "\npolicyIdentifierHashAlgorithm=1.3.14.3.2.26"+
                "\n policyQualifier=https://sede.060.gob.es/politica_de_firma_anexo_1.pdf"+
                "\nmode=explicit"
            };*/

            var signparams =
            {
                format:"PAdES",
                algoritm: "SHA512withRSA",
                params:"serverUrl=http://firmaloserver.devecomputer.es/afirma-server-triphase-signer/SignatureService"

            };

            return signparams;
        },
        checkWindowSize: function()
        {
            var heigh=window.innerHeight-250;
            $('.headingUsers .panel-body').css('height',heigh+'px');

            $( window ).resize(function() {
                var heigh=window.innerHeight-250;
                $('.headingUsers .panel-body').css('height',heigh+'px');
            });
        },
        initToolTip: function(){
            //mensajes de ayuda de la UI
            $('[data-toggle="tooltip"]').tooltip();
        }

    });

    $(function () {
        $('body').tooltip({
            selector: '.expedition'
        })
    });

})( jQuery );
