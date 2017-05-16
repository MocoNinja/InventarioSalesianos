    //  MiniApplet = undefined;
        firma = true;
    /**
     * Created by  on 06/02/2015.
     */
    $(document).ready(function(e) {


    var last_clicked_show_docs = "";
    var last_clicked_show_msgs = "";
    var last_clicked_load_docs = "";


    $('html').on('click', function (e) {
        if (typeof $(e.target).data('original-title') == 'undefined' && !$(e.target).parents().is('.popover.in')) {
            $('[data-original-title]').popover('hide');
        }
    });


    $('#listadomensajes').DataTable({
        "autowidth": true,
        "lengthChange": false,
        "bSort": false,
        "pagingType": "simple",
        "columns": [
            {"orderable": false},
            {"orderable": false}
        ]
    });

    var tableFicheros = $('#listadoficheros').DataTable({
        "autowidth": true,
        "lengthChange": false,
        "bSort": false,
        "pagingType": "simple",
        "columns": [
            {"orderable": false},
            {"orderable": false},
            {"orderable": false},
            {"orderable": false}
        ]
    });

    tableFicheros.on('draw', function () {
        $.checkAppletLoadedUI();
    });

    /*
     $('#tableusers').DataTable({
     "autowidth": true,
     "lengthChange":false,
     "bSort":false,
     "pagingType": "simple",
     "aoColumns": [
     { "orderable": false },
     { "orderable": false },
     { "orderable": false },
     { "orderable": false }


     ],
     "columnDefs": [
     { "searchable": false, "targets": 1 }
     ],

     "bFilter" : false,
     "bInfo" :false,
     "pageLength" : 40

     });*/
    $(".mensaje").popover({
        html: true,
        title: function () {
            return $("#popover-head" + $(this).data("id")).html();
        },
        content: function () {
            return $("#popover-content" + $(this).data("id")).html();
        },
        placement: "left"
    });
    $(".dropzone-single").dropzone({url: "/admin/uploadfile", parallelUploads: 8, uploadMultiple: false});

    $(document).on("click", "#edituser", function () {
        window.location.href = $(this).attr("data-url");
    });
    $(document).on("click", "#editsubuser", function () {
        window.location.href = $(this).attr("data-url");
    });
    $(document).on("click", "#createsubuser", function () {
        window.location.href = $(this).attr("data-url");
    });

    $(document).on("click", "#linkedButton", function () {
        window.location.href = $(this).attr("data-url");
    });

    $(document).on("click", ".messagepanel", function () {
        $('#myModal' + $(this).data("id")).modal('toggle')
    });

    $(".modal").on("hidden.bs.modal", function (e) {
        var mensaje_div = $("#message" + $(this).data("id"));
        $.messageVisto("/user/messagevisto/", {
            "id_msg": $(this).data("id"),
            "id_de": $(this).data("deid"),
            "id_para": $(this).data("paraid")
        }, mensaje_div);
    });

    $(document).on("click", "#edit_user_form input[type='password']", function (e) {
        bootbox.confirm("Se modificará permanentemente la contraseña del usuario.<br />¿Desea continuar?", function (result) {
            if (result) {
                setTimeout(function () {
                    $("#form_pass").focus();
                    // Do something after 5 seconds
                }, 100);

            }
        });
    });

    $(document).on("click", "#delete_user", function (e) {
        e.preventDefault();
        var url = $(this).data("url");
        bootbox.confirm("Se borrará permanentemente el usuario y <b>todos</b> sus <b>doumentos</b>.<br />¿Desea continuar?", function (result) {
            if (result) {
                $.callAjaxGet(url, true, function (response) {
                    if (response.status == "ok") {
                        $.notification("warning", 5000, "<strong>Éxito</strong>");
                    }
                    else {
                        $.notification("error", 5000, "<strong>Error</strong>");
                    }
                });
            }
        });
    });
    $(document).on("submit", '.delete-file', function (event) {
        event.preventDefault();
        var form = $(this);

        bootbox.confirm("Se borrará permanentemente el documento.<br />¿Desea continuar?", function (result) {
            if (result) {
                $.callAjax(form, "json", false, function (response) {
                    waitingDialog.hide("slow");
                    if (response.status == "ok") {
                        $.hideClosestRow(form);
                        $.notification("warning", 5000, "<strong>Éxito</strong>");
                    }
                    else {
                        $.notification("error", 5000, "<strong>Error</strong>");
                    }
                });


            }
        });

    });
    $(document).on("click", "#select_all", function (e) {
        e.preventDefault();
        if ($("#form_usuarios option").attr("selected") == "selected") {
            $.each($("#form_usuarios option"), function () {
                $(this).removeAttr("selected");
            });
        }
        else
            $("#form_usuarios option").attr("selected", "selected");
    });

    $(document).on("click", ".toggle-single-upload", function () {
        var id = $(this).data('id');
        $.hideAlltrList();

        last_clicked_show_docs = "";
        last_clicked_show_msgs = "";
        $('#tableusers tbody tr').css('opacity', '0.3');

        $("#uploader-single-container-" + id).css('opacity', '1');
        $('#main-row-' + id).css('opacity', '1');

        if (last_clicked_load_docs != id) {

            $("#uploader-single-container-" + id).show("fast");


        }

        if (last_clicked_load_docs == id) {
            last_clicked_load_docs = "";
        }
        else {
            last_clicked_load_docs = id;
        }

    });

    $(document).on("click", ".toggle-show-docs", function () {

        var id = $(this).data('id');
        $.hideAlltrList();


        last_clicked_show_msgs = "";
        last_clicked_load_docs = "";
        $('#tableusers tbody tr').css('opacity', '0.3');
        $('#main-row-' + id).css('opacity', '1');


        $.callAjaxGet('/user/files/' + id, false, function (response) {
            if (response) {

                $('#showdocs-single-container-' + id).empty();

                $('#showdocs-single-container-' + id).html('<td colspan="5">' + response + '</td>');
                $('#showdocs-single-container-' + id).css('opacity', '1');

                $.checkAppletLoadedUI();
            }

            if (last_clicked_show_docs != id) {
                $("#showdocs-single-container-" + id).show("fast");
            }

            if (last_clicked_show_docs == id) {
                last_clicked_show_docs = "";
            }
            else {
                last_clicked_show_docs = id;
            }

        }, 'html');

    });

    $(document).on("click", ".toggle-show-msgs", function () {
        var id = $(this).data('id');
        $.hideAlltrList();

        last_clicked_show_docs = "";
        last_clicked_load_docs = "";

        $('#tableusers tbody tr').css('opacity', '0.3');
        $('#main-row-' + id).css('opacity', '1');

        $.callAjaxGet('/user/messages/' + id, false, function (response) {


            if (response) {


                $('#showmsgs-single-container-' + id).empty();

                $('#showmsgs-single-container-' + id).html('<td colspan="5">' + response + '</td>');
                $('#showmsgs-single-container-' + id).css('opacity', '1');

            }

            if (last_clicked_show_msgs != id) {
                $("#showmsgs-single-container-" + id).show("fast");
            }
            if (last_clicked_show_msgs == id) {
                last_clicked_show_msgs = "";
            }
            else {
                last_clicked_show_msgs = id;
            }


        }, 'html');

    });
    $(document).on("click", "#edituser", function () {
        window.location.href = $(this).attr("data-url");
    });
    /** AJAX CALLS **/
    /*  $("#edit_user_form").submit(function(event){
     event.preventDefault();
     $.callAjax($(this),"json",true,function(response){
     if(response.status == "ok")
     {
     $.notification("warning",5000,"<strong>Éxito</strong>");
     }
     else
     {
     $.notification("warning",5000,"<strong>Error</strong>");
     }
     });
     });*/
    /*$('#create_user_form').submit(function(event){
     event.preventDefault();
     $.callAjax($(this),"json",true,function(response){

     $.handleAjaxResponse(response);

     });
     });*/
    $('#filter_user_form').submit(function (event) {
        event.preventDefault();
        $.callAjax($(this), "json", false, function (response) {
            $.cargaUsers(response);
            last_clicked_show_docs = "";
            last_clicked_show_msgs = "";
        });
    });
    $('#todos_form_button').click(function (event) {
        event.preventDefault();
        $.callAjax($('#filter_user_form'), "json", false, function (response) {

            $.cargaUsers(response);
        }, "/admin/filterusers");
    });


    $(document).on("submit", ".send-message", function (event) {
        event.preventDefault();
        $.callAjax($(this), "json", false, function (response) {
            if (response.status == "ok") {
                $.notification("warning", 5000, "<strong>Éxito</strong>");
                $.limpiaFormMessages();
                if (typeof $(e.target).data('original-title') == 'undefined' && !$(e.target).parents().is('.popover.in')) {
                    $('[data-original-title]').popover('hide');
                }
            }
            else {
                $.notification("warning", 5000, "<strong>Error</strong>");
            }
        })
    });
    $(document).bind('drop dragover', function (e) {
        e.preventDefault();
    });
    $(document).on("submit", '.download-file', function (event) {
        event.preventDefault();

        $.callAjax($(this), "json", false, function (response) {

            if (response.status == "ok") {
                //Create an IFRAME.
                var iframe = document.createElement("iframe");
                // Point the IFRAME to GenerateFile, with the
                //   desired region as a querystring argument.


                iframe.src = '/user/' + response.user + '/download/' + encodeURI(response.doc);
                // This makes the IFRAME invisible to the user.
                iframe.style.display = "none";
                // Add the IFRAME to the page.  This will trigger
                //   a request to GenerateFile now.
                document.body.appendChild(iframe);
                //devolver una url temporal
                waitingDialog.hide();
                $.notification("warning", 5000, "<strong>Éxito</strong>");
            }
            else {
                $.notification("warning", 5000, "<strong>Error</strong>");
            }
        });
    });

    $(document).on("submit", '.firma-file', function (event) {
        event.preventDefault();


        if (!$.isAppletLoaded()) {
            $.loadfirmaApplet();
        }


        $('.firma-file button').prop('disabled', true);
        $.callAjax($(this), "json", false, function (response) {

            if (response.status == "ok") {
                var user = response.user;
                var doc = encodeURI(response.doc);
                // "data:application/pdf;base64,"
                $.get('/user/' + user + '/downloadb64/' + doc, function (data) {


                    doSign(data.doc_b64, $.getSignParams(), function (signatureB64) {

                        var signed_file_b64 = {"signed_file_b64": signatureB64};
                        var jqxhr = $.ajax({
                            url: '/user/' + user + '/uploadb64/' + doc,
                            data: signed_file_b64,
                            type: 'POST',
                            dataType: 'json',
                            beforeSend: function () {

                            }
                        }).done(function (response) {
                            if (response.status == "ok") {
                                $.notification("warning", 5000, "<strong>Contrato firmado</strong>");
                                $('.firma-file button').prop('disabled', false);
                            }

                        }).fail(function (jqXHR, textStatus, errorThrown) {
                            $.notification("danger", 5000, "<strong>Error al firmar</strong>");

                        });

                    }, $.manageAppletException);


                });


            }
            else {
                $.notification("warning", 5000, "<strong>Error</strong>");
            }
        });
    });


    $(document).on("click", ".button-load-applet", function () {
        if (!$.isAppletLoaded()) {
            $.loadfirmaApplet();
        }

    });

    /** FIN AJAX CALLS **/
        //caja de confimración.
    bootbox.setDefaults({
        /**
         * @optional String
         * @default: en
         * which locale settings to use to translate the three
         * standard button labels: OK, CONFIRM, CANCEL
         */
        locale: "es",

        /**
         * @optional Boolean
         * @default: true
         * whether the dialog should be shown immediately
         */
        show: true

    });

    $.initToolTip();
    $.initIntervalCheks()
    $.checkAppletLoadedUI();
    $.checkWindowSize();

    // Función cambiar opacidad al pulsar botón

    $(".boton-evento").click(function () {

        if ($("#barras").css('opacity') < 1) {
            $("#barras").css('opacity', '1');
            $("#barras").css('pointer-events', 'all');
        }
        else {
            //$("#barras").css('opacity','0.3');
            //$("#barras").css('pointer-events','none');
        }

    });

    $(".boton-evento_bandos").click(function () {


        if ($("#barras_bandos").css('opacity') < 1) {
            $("#barras_bandos").css('opacity', '1');
            $("#barras_bandos").css('pointer-events', 'all');
        }
        else {
            $("#barras_bandos").css('opacity', '0.3');
            $("#barras_bandos").css('pointer-events', 'none');
        }

    });

    $(".boton-evento_eventos").click(function () {

        if ($("#barras_eventos").css('opacity') < 1) {
            $("#barras_eventos").css('opacity', '1');
            $("#barras_eventos").css('pointer-events', 'all');
        }
        else {
            $("#barras_eventos").css('opacity', '0.3');
            $("#barras_eventos").css('pointer-events', 'none');
        }

    });

    $(".closepanel1").on("click", function () {

        var isDivEventShown = $('#collapseBtn-2').attr('aria-expanded');
        var isDivcategoryShown = $('#collapseBtn-cat').attr('aria-expanded');
        var isDivpueblosShown = $('#collapseBtn-3').attr('aria-expanded');

        if (isDivEventShown == 'false' && isDivcategoryShown == 'false' && isDivpueblosShown == 'false') {
            $("#collapseBtn-2").collapse('show');
        }

        if (isDivEventShown == 'true') {
            $("#collapseBtn-2").collapse('hide');
            if (isDivcategoryShown == 'true') {
                $('#collapseBtn-cat').collapse('hide');
            }
            if (isDivpueblosShown == 'true') {
                $("#collapseBtn-3").collapse('hide');
            }
        } else {
            if (isDivcategoryShown == 'true') {
                $('#collapseBtn-cat').collapse('hide');
            }
            if (isDivpueblosShown == 'true') {
                $('#collapseBtn-3').collapse('hide');
            }
            $("#collapseBtn-2").collapse('show');
        }

    });

    $(".btnBando-new").on("click", function () {
        var isDivEventShown = $('#collapseBtn-2').attr('aria-expanded');
        var isDivcategoryShown = $('#collapseBtn-cat').attr('aria-expanded');


        if (isDivEventShown == 'false' && isDivcategoryShown == 'false') {
            $("#collapseBtn-2").collapse('show');
        }

        if (isDivEventShown == 'true') {
            $("#collapseBtn-2").collapse('hide');
            if (isDivcategoryShown == 'true') {
                $('#collapseBtn-cat').collapse('hide');
            }
        } else {
            if (isDivcategoryShown == 'true') {
                $('#collapseBtn-cat').collapse('hide');
            }
            $("#collapseBtn-2").collapse('show');
        }
    });

    $(".btnBando-clock").on("click", function () {
        var isDivEventShown = $('#collapseBtn-2').attr('aria-expanded');
        var isDivcategoryShown = $('#collapseBtn-cat').attr('aria-expanded');


        if (isDivEventShown == 'false' && isDivcategoryShown == 'false') {
            $("#collapseBtn-cat").collapse('show');
        }

        if (isDivcategoryShown == 'true') {
            $("#collapseBtn-cat").collapse('hide');
            if (isDivEventShown == 'true') {
                $('#collapseBtn-2').collapse('hide');
            }
        } else {
            if (isDivEventShown == 'true') {
                $('#collapseBtn-2').collapse('hide');
            }
            $("#collapseBtn-cat").collapse('show');
        }
    });

    $(".closepanel2").on("click", function () {

        var isDivEventShown = $('#collapseBtn-2').attr('aria-expanded');
        var isDivcategoryShown = $('#collapseBtn-cat').attr('aria-expanded');
        var isDivpueblosShown = $('#collapseBtn-3').attr('aria-expanded');
      /*  console.log(isDivcategoryShown);
        console.log(isDivpueblosShown);*/
        if (isDivEventShown == 'false' && isDivcategoryShown == 'false' && isDivpueblosShown == 'false') {
            $("#collapseBtn-cat").collapse('show');
        }

        if (isDivcategoryShown == 'true') {
            $("#collapseBtn-cat").collapse('hide');
            if (isDivEventShown == 'true') {
                $('#collapseBtn-2').collapse('hide');
            }
            if (isDivpueblosShown == 'true') {
                $("#collapseBtn-3").collapse('hide');
            }
        } else {
            if (isDivEventShown == 'true') {
                $('#collapseBtn-2').collapse('hide');
            }
            if (isDivpueblosShown == 'true') {
                $('#collapseBtn-3').collapse('hide');
            }
            $("#collapseBtn-cat").collapse('show');
        }

    });

    $(".closepanel3").on("click", function () {
        var isDivpuebloShown = $('#collapseBtn-3').attr('aria-expanded');
        var isDivEventShown = $('#collapseBtn-2').attr('aria-expanded');
        var isDivcategoryShown = $('#collapseBtn-cat').attr('aria-expanded');


        if (isDivpuebloShown == 'true') {
            if (isDivEventShown == 'true') {
                $('#collapseBtn-2').collapse('hide');
            }
            if (isDivcategoryShown == 'true') {
                $('#collapseBtn-cat').collapse('hide');
            }
        } else {
            if (isDivEventShown == 'true') {
                $('#collapseBtn-2').collapse('hide');
            }
            if (isDivcategoryShown == 'true') {
                $('#collapseBtn-cat').collapse('hide');
            }
            $("#collapseBtn-3").collapse('show');
        }
    });

    $(".closepanel4").on("click", function () {
        var isDivEventShown = $('#collapseBtn-2').attr('aria-expanded');
        var isDivcategoryShown = $('#collapseBtn-cat').attr('aria-expanded');
        var isDivpueblosShown = $('#collapseBtn-3').attr('aria-expanded');

        if (isDivEventShown == 'false' && isDivcategoryShown == 'false' && isDivpueblosShown == 'false') {
            $("#collapseBtn-2").collapse('show');
        }

        if (isDivEventShown == 'true') {
            $("#collapseBtn-2").collapse('hide');
            if (isDivcategoryShown == 'true') {
                $('#collapseBtn-cat').collapse('hide');
            }
            if (isDivpueblosShown == 'true') {
                $("#collapseBtn-3").collapse('hide');
            }
        } else {
            if (isDivcategoryShown == 'true') {
                $('#collapseBtn-cat').collapse('hide');
            }
            if (isDivpueblosShown == 'true') {
                $('#collapseBtn-3').collapse('hide');
            }
            $("#collapseBtn-2").collapse('show');
        }
    });

        $(".closepanel5").on("click", function () {
            var isDivEventShown = $('#collapseBtn-2').attr('aria-expanded');
            var isDivcategoryShown = $('#collapseBtn-cat').attr('aria-expanded');
            var isDivpueblosShown = $('#collapseBtn-3').attr('aria-expanded');
            console.log(isDivcategoryShown);
            console.log(isDivpueblosShown);
            console.log(isDivEventShown);
            if (isDivEventShown == 'false' && isDivcategoryShown == 'false' && isDivpueblosShown == 'false') {
                $("#collapseBtn-cat").collapse('show');
            }

            if (isDivcategoryShown == 'true') {
                $("#collapseBtn-cat").collapse('hide');
                if (isDivEventShown == 'true') {
                    $('#collapseBtn-2').collapse('hide');
                }
                if (isDivpueblosShown == 'true') {
                    $("#collapseBtn-3").collapse('hide');
                }
            } else {
                if (isDivEventShown == 'true') {
                    $('#collapseBtn-2').collapse('hide');
                }
                if (isDivpueblosShown == 'true') {
                    $('#collapseBtn-3').collapse('hide');
                }
                $("#collapseBtn-cat").collapse('show');
            }

        });

        $(".closepanel6").on("click", function () {
            var isDivpuebloShown = $('#collapseBtn-3').attr('aria-expanded');
            var isDivEventShown = $('#collapseBtn-2').attr('aria-expanded');
            var isDivcategoryShown = $('#collapseBtn-cat').attr('aria-expanded');


            if (isDivpuebloShown == 'true') {
                if (isDivEventShown == 'true') {
                    $('#collapseBtn-2').collapse('hide');
                }
                if (isDivcategoryShown == 'true') {
                    $('#collapseBtn-cat').collapse('hide');
                }
            } else {
                if (isDivEventShown == 'true') {
                    $('#collapseBtn-2').collapse('hide');
                }
                if (isDivcategoryShown == 'true') {
                    $('#collapseBtn-cat').collapse('hide');
                }
                $("#collapseBtn-3").collapse('show');
            }
        });


    $('.disabled').click(function (event) {
        event.preventDefault();
    });

        /*  $(function () {
       $('#datetimepicker').datetimepicker({
            language: 'es'
        });

        $('#datetimepicker1').datetimepicker({
            language: 'es'
        });

        $('#datetimepickerfechaFinal').datetimepicker({
            language: 'es'
        });

        $('#datetimepickerBuscadorEventos').datetimepicker({
            language: 'es',
            format: 'L'
        });
    });*/

    //automatiza la transformacion de datepickers cuando no están soportados por el navegador y los inicializa con una fecha
    $(function () {
        if (!Modernizr.inputtypes.date) {
            // If not native HTML5 support, fallback to jQuery datePicker
            $('input[type=date]').datepicker({dateFormat: 'yy/mm/dd'}/*, $.datepicker.regional['es']*/);
            if ($('input[type=date]').val() != "") {
                $('input[type=date]').each(function () {
                    $(this).datepicker('setDate', new Date($(this).val()));
                });
            }
        }

        if (!Modernizr.inputtypes.time) {
            $('input[type=time]').timepicker('', $.timepicker.regional['es']);
        }

    });


    /*  $('#btn-search1').click(function(event){
     event.preventDefault();
     date = $('#search_form input[name="form[search1]"]').val();
     postData = {
     date : date
     };
     $.post('/eventos/filterEvents/', postData,function(data) {
     if(data){
     $('.panel-group-dinamic').remove();
     $('#barras').append(data);
     }
     },"json");
     }); */

    $('#btn-search2').click(function (event) {
        event.preventDefault();
        date = $('#search_form input[name="form[search1]"]').val();
        name = $('#search_form input[name="form[search2]"]').val();
        postData = {
            name: name,
            date: date
        };
        $.post('/eventos/filterEvents/', postData, function (data) {
            console.log(postData);
            if (data !== "") {
                $('.panel-group-dinamic').remove();
                $('#barras').append(data);
            }
        }, "json");
    });

    $('#btn-search3').click(function (event) {
        event.preventDefault();
        date = $('#search_form3 input[name="form[search3]"]').val();
        name = $('#search_form3 input[name="form[search4]"]').val();
        postData = {
            name: name,
            date: date
        };
        $.post('/micro_eventos/filterMicroEvents/', postData, function (data) {

            if (data !== "") {
                $('.panel-group-dinamic').remove();
                $('#barras').append(data);
            }
        }, "json");
    });

    $('#btn-accionesLote').click(function (event) {
        event.preventDefault();
        action = $('#accionesLote_form select').val();


        switch (action) {
            case "1":
                break;
            case "2":
                $('#formDeleteMassive').submit();
                break;
            /*case 3:
             break;
             case 4:
             break;
             case 5:
             break;*/
            default:
        }
    });

    $('#btn-accionesLote2').click(function (event) {
    event.preventDefault();
    action = $('#accionesLote_form2 select').val();

    switch (action) {
        case "1":
            break;
        case "2":
            $('#formDeleteMassive2').submit();
            break;
        /*case 3:
         break;
         case 4:
         break;
         case 5:
         break;*/
        default:
    }
});
    $('#btn-accionesLote3').click(function (event) {
    event.preventDefault();
    action = $('#accionesLote_form3 select').val();

    switch (action) {
        case "1":
            break;
        case "2":
            $('#formDeleteMassive3').submit();
            break;

        default:
    }
});

    $('#btn-accionesLote4').click(function (event) {
    event.preventDefault();
    action = $('#accionesLote_form4 select').val();

    switch (action) {
        case "1":
            break;
        case "2":
            $('#formDeleteMassive4').submit();
            break;
        /*case 3:
         break;
         case 4:
         break;
         case 5:
         break;*/
        default:
    }
});

    $(document).on('click', '#bando_form div[data-toggle="toggle"]', function () {
        $hasClass = $(this).hasClass('off');
        if ($hasClass) {
            $("#bando_form #form_fechaToSend").removeClass('display-none');
            $("#bando_form #labeldateSend").removeClass('display-none');
        } else {
            $("#bando_form #form_fechaToSend").addClass('display-none');
            $("#bando_form #labeldateSend").addClass('display-none');
            $("#form_fechaToSend_time-error").css('display', 'none');
            $("#form_fechaToSend_date-error").css('display', 'none');
        }
    });

    $('#btn-search-farmacias').click(function (event) {
        event.preventDefault();
        filtro = $('#searchFarmacia_form input[name="form[search]"]').val();
        postData = {
            filtro: filtro
        };
        $.post('/farmacias/filter/', postData, function (data) {
            $('#block-farmacias').remove();
            $('.panel-farmacias-dinamic').append(data);
        }, "json");
    });

    $('a.no-checked').on('click',function(){
        $(this).removeClass('no-checked');

        var id = $(this).attr('data-id');
        var collapse = $(this).attr('class');
        collapse = collapse.replace('incidencia-map', "");
        collapse = collapse.trim();

        if (collapse == 'collapsed') {
            postData = {
                id: id
            };
            $.post('/incidencias/checked/', postData, function (datos) {
                if(datos['status']){
                    x = $('.num-no-check').attr('data-badge');
                    $incidenciaNum = x-1;
                    if($incidenciaNum >0)
                        $('.num-no-check').attr('data-badge',$incidenciaNum);
                    else
                        $('.num-no-check').removeAttr('data-badge');
                }
            }, "json");
        }
    });

    /*$('a.no-checked').click(function () {
        console.log("Clickado a.no-checked");
        $(this).removeClass('no-checked');

        var id = $(this).attr('data-id');
        var collapse = $(this).attr('class');
        collapse = collapse.replace('incidencia-map', "");
        collapse = collapse.trim();
        console.log(id);
        console.log(collapse);
        if (collapse == 'collapsed') {
            postData = {
                id: id
            };
            $.post('/incidencias/checked/', postData, function (datos) {
                console.log(datos);
                if(datos['status']){
                    x = $('.num-no-check').attr('data-badge');
                    $('.num-no-check').attr('data-badge',x-1);
                }else{
                    $('.num-no-check').removeAttr('data-badge');
                }

            }, "json");
        }
    });*/

    $(' a.incidencia-map').click(function () {
        var collapse = $(' a.incidencia-map').attr('aria-expanded');
        var id = $(this).attr('data-id');
        var enter = $(this).attr('data-enter');

        if (enter == "false" && collapse == "false") {
            var map;
            $(this).attr('data-enter', 'true');
            var latitud = $(this).attr('data-latitud');
            var longitud = $(this).attr('data-longitud');
            var marker;

            function initializeIncidencia() {
                var canvas = document.getElementById("incidenciaMap" + id);
                var myCenter = new google.maps.LatLng(latitud, longitud);
                var mapProp = {
                    center: myCenter,
                    zoom: 15,
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                };
                map = new google.maps.Map(canvas, mapProp);
                marker = new google.maps.Marker({
                    position: myCenter,
                    map: map,
                });
            }

            function resizeIncidencia() {
                google.maps.event.trigger(map, "resize");
            }

            google.maps.event.addDomListenerOnce(window, 'click', initializeIncidencia);
            google.maps.event.addDomListener($(this), "resize", resizeIncidencia);
        }
    });


    $(document).on('click', ".displaysubusers", function (e) {
        //llamada ajax
        id = $(this).attr('data-id');
        postData = {
            parentId: id
        };

        $.post('/admin/subusers/', postData, function (data) {

            if ($.isEmptyObject(data) == false) {

                var html_head = '<thead><th>Nombre</th><th>Email</th><th>Activo</th></thead>';
                var html = "<tbody>";
                $.each(data, function (i, item) {
                    var active = "No";
                    if (data[i].activo.trim() == 1) {
                        active = "Si";
                    }
                    html +=
                        '<tr class="hovered-tr"  id="main-row-' + data[i].id.trim() + '"  data-rowid="' + data[i].id.trim() + '">' +
                        '<td>' + data[i].nombre.trim() + ' ' + data[i].apellidos.trim() + '</td>' +
                        '<td>' + data[i].email.trim() + '</td>' +
                        '<td>' + active + '</td>' +
                        '<td><a id="editsubuser" type="button" href="/admin/subuser/edit/' + data[i].id + '/" class="btn btn-default margin-left-10"> Editar</a></td>' +
                        '</tr>'
                    ;
                });

                $("#tablesubusers").empty();
                $("#tablesubusers").append(html_head + html);
            } else {
                $("#tablesubusers").append("<p>Problemas al conseguir subusuarios</p>");
            }
            $("#myModalSubUsers").show();
        }, "json");
    });

    $('#realBandoSubmit').click(function () {
        $('#realBandoSubmit').addClass("disabled");
        $("#bandoFooter").append("<div class='col-md-12'><p>Enviando bandos <i class='fa fa-refresh fa-spin'></i></div></p>");
    });
    $('#realBandoSubmitDemo').click(function () {
        $('#realBandoSubmitDemo').addClass("disabled");
        $("#bandoFooter").append("<div class='col-md-12'><p>Enviando bandos <i class='fa fa-refresh fa-spin'></i></div></p>");
    });
    $(".nav.navbar-nav li").on('click', function () {
        $("#navbar").toggle('collapse in');
    });


    $(document).delegate('*[data-toggle="lightbox"]', 'click', function (event) {
        event.preventDefault();
        $(this).ekkoLightbox();
    });


    //Proceso de envio de bandos monitorizado
    $(function () {
        var interval = setInterval(function () {
            if ($("a.isBando[data-status!='completed']").length > 0) {
                $("a.isBando[data-status!='completed']").each(function () {
                    var id = $(this).attr('data-id');
                    var status = $(this).attr('data-status');
                    var element = $(this);
                    postData = {
                        id: id,
                        status: status
                    };

                    $.post('/bandos/processing/', postData, function (data) {
                        status = data['status'];
                        span = element.find("span");
                        span.empty();
                        switch (status) {
                            case "sending":
                                /*span.removeAttr('class');
                                span.addClass('processing-status yellow-span');
                                element.attr("data-status", "sending");
                                span.append("Enviando notificaciones " + data['actual'] + "/" + data['total'] + ' <i class="fa fa-spinner fa-spin"></i>');
                                break;*/

                            case "processing":
                                span.removeAttr('class');
                                span.addClass('processing-status blue-span');
                                element.attr("data-status", "processing");
                                span.append('Procesando los datos del bando <i class="fa fa-spinner fa-spin"></i>');
                                //Ponemos mensaje de sending
                                break;

                            case "resending":
                                span.removeAttr('class');
                                span.addClass('processing-status purple-span');
                                element.attr("data-status", "resending");
                                span.append("Reenviando notificaciones fallidas " + data['actual'] + "/" + data['total'] + ' <i class="fa fa-spinner fa-spin"></i>');
                                //Ponemos mensaje de resending
                                break;

                            case "completed":
                                //Ponemos mensaje de completado
                                location.reload();
                                /*span.removeAttr('class');
                                 span.addClass('processing-status green-span');
                                 element.attr("data-status","completed");
                                 span.append('Envío completado <i class="fa fa-check"></i>');*/
                                //clearInterval(interval);
                                break;

                            case "toSend":
                                span.removeAttr('class');
                                span.addClass('processing-status blue-span');
                                element.attr("data-status", "toSend");
                                span.append('Programado para envío <i class="fa fa-spinner fa-spin"></i>');
                                //Ponemos mensaje de sending
                                break;
                        }
                    }, "json");
                });
            } else {
                clearInterval(interval);
            }
        }, 3000);
    });


    $(function () {
        var interval = setInterval(function () {
            if ($("del.red-del").length > 0) {
                $("del.red-del").each(function () {
                    var id = $(this).parent().attr('data-id');
                    postData = {};
                    $.post('/eventos/checkActive/' + id + '/', postData, function (data) {
                        if (data) {
                            location.reload();
                        }
                    }, "json");
                });
            }
            if ($("del.red-del-wp").length > 0) {
                $("del.red-del-wp").each(function () {
                    var id = $(this).parent().attr('data-id');
                    postData = {};
                    $.post('/micro_eventos/checkActive/' + id + '/', postData, function (data) {

                        if (data) {
                         //   location.reload();
                        }
                    }, "json");
                });
            }
        }, 3000);
    });

    $('#servicio_form input[name="form[comerOdormir]"]').parent().addClass("manual-WH");

    $('.deleteCategoryEvent').click(function (e) {
        e.preventDefault();
        $url = $(this).attr("href");
        postData = "";

        $.post($url, postData, function (data) {

            if (data) {
                window.location.href = "/eventos/";
            } else {
                $.growl.error({
                    title: "¡Error!",
                    message: "No se puede borrar la categoría porque ya está asociada a un evento."
                });
            }
        }, "json");
    });
    $('.deleteCategoryEvent2').click(function (e) {
        e.preventDefault();
        $url = $(this).attr("href");
        postData = "";

        $.post($url, postData, function (data) {

            if (data) {
                window.location.href = "/micro_eventos/";
            } else {
                $.growl.error({
                    title: "¡Error!",
                    message: "No se puede borrar la categoría porque ya está asociada a un evento."
                });
            }
        }, "json");
    });

    $('#calendar').fullCalendar({

        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay'
        },
        events: '/reservas/getReservasAjax/',
        //titleFormat: 'MMMM YYYY',
        //timeFormat: 'DD-MM-YYYY HH:mm',
        timeFormat: 'HH:mm',
        selectable: true,
        selectHelper: true,
        eventLimit: true,
        eventColor: '',
        eventRender: function(event, element) {
            element.attr('title', event.tip);
            //$(element).find(".fc-content").append('<a data-id="'+event.id+'" class="deleteReserva"><span class="ionicons ion-close-round pull-right x-link"></span></a>');
        }
    });

    $(document).on("click", ".deleteReserva", function () {
        $click = $(this);
        $reservaId = $(this).attr("data-id");

        postData = {
            reservaId: $reservaId
        }
        $.post("/reservas/delete/" + $reservaId + "/", postData, function (data) {
            console.log(data);
            if (data) {
                //$($click).parent().parent().parent().remove();
                $("#calendar").fullCalendar('destroy');
                $("#calendar").fullCalendar({
                    header: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'month,agendaWeek,agendaDay'
                    },
                    events: data,
                    //titleFormat: 'MMMM YYYY',
                    timeFormat: 'HH:mm',
                    selectable: true,
                    selectHelper: true,
                    eventLimit: true,
                    eventRender: function (event, element) {
                        // $(element).find(".fc-content").append('<a data-id="'+event.id+'" class="deleteReserva"><span class="ionicons ion-close-round pull-right x-link"></span></a>');
                    }
                });
            }
        }, "json");
    });

    $("#btn-search-place-calendar").click(function (e) {
        e.preventDefault();
        $filter = $("#form_filtroPlace").val();
        postData = {
            filter: $filter
        }

        $.post("/reservas/filtro/lugar/", postData, function (data) {
            $("#calendar").fullCalendar('destroy');
            $("#calendar").fullCalendar({
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay'
                },
                events: data,
                //titleFormat: 'MMMM YYYY',
                timeFormat: 'HH:mm',
                selectable: true,
                selectHelper: true,
                eventLimit: true,
                eventRender: function (event, element) {
                    // $(element).find(".fc-content").append('<a data-id="'+event.id+'" class="deleteReserva"><span class="ionicons ion-close-round pull-right x-link"></span></a>');
                }
            });
        }, "json");
    });

    $(document).on('change', '#formReserva select[name="form[places]"]', function () {
        var $place = $(this).val();
        postData = {
            place: $place
        };
        $.post('/reservas/change/place', postData, function (data) {
            $('#formReserva select[name="form[salas]"]').empty();
            $('#custom-calendar-reservas').datepicker('clearDates');
            $('#reservasDia').empty();
            $('#form_franjas_reserva-error').remove();
            if (data['ok']) {
                $.growl.warning({title: "¡Cuidado!", message: "No hay salas para este lugar.", location: "tc"});
            } else {
                $('#formReserva select[name="form[salas]"]').append(data['view']);
            }
        }, "json");
    });

    $(document).on('change', '#formReserva select[name="form[salas]"]', function () {
        $('#custom-calendar-reservas').datepicker('clearDates');
        $('#reservasDia').empty();
        $('#form_franjas_reserva-error').remove();
    });

    $("a.isBando[data-status='toSend']").click(function () {
        $id = $(this).attr("data-id");
        postData = {};
        $.post('/bando/toSend/loadHtml/' + $id + "/", postData, function (data) {

            $(".toSendBando").each(function () {
                $(this).empty();
            });
            $("#" + $id).append(data);
            if (!Modernizr.inputtypes.date) {
                $('input[type=date]').datepicker({dateFormat: 'dd/mm/yy'}, $.datepicker.regional['es']);
            }
            if (!Modernizr.inputtypes.time) {
                $('input[type=time]').timepicker('', $.timepicker.regional['es']);
            }

            $("input[type='file']").fileinput();

        }, "json");
    });

    $(".viewTypeReservas").click(function () {
        //Si sale false -> listado //Si sale true -> calendar
        $val = $(this).hasClass("off");

        if ($val) {
            $(".filterPlaceReservasView").removeClass("visibility-hidden");
            $("#calendar").fullCalendar('destroy');
            $("#calendar").fullCalendar({
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay'
                },
                events: '/reservas/getReservasAjax/',
                //titleFormat: 'MMMM YYYY',
                timeFormat: 'HH:mm',
                selectable: true,
                selectHelper: true,
                eventLimit: true,
                eventRender: function (event, element) {
                    //  $(element).find(".fc-content").append('<a data-id="'+event.id+'" class="deleteReserva"><span class="ionicons ion-close-round pull-right x-link"></span></a>');
                }
            });
        } else {
            $(".filterPlaceReservasView").addClass("visibility-hidden");
            postData = {};

            $.post('/reservas/getReservas/', postData, function (data) {
                if (!data['ok']) {
                    $("#calendar").empty();
                    $("#calendar").append(data['view']);
                } else {
                    $.growl.warning({
                        title: "¡Cuidado!",
                        message: "¡No se pueden listar reservas, por que no hay!.",
                        location: "tc"
                    });
                }
            }, "json");

        }
    });

    //Controlamos la sesion
    $(function () {
        cookie_user = Cookies.get("PANELETNO");
        $(document).idleTimer(60000);
        setInterval(function () {
            $status = $(document).idleTimer("isIdle");
            if (cookie_user != Cookies.get("PANELETNO") && $status) {
                window.location.href = "/logout";
            }
        }, 2000);
    });


    //add a point
    $(document).on('click',"#add-marker",function(e){
        e.preventDefault();
        //sacar el color seleccionado
        var $fill = $('#color').val()
        $.addPoint($fill);

    });

    //remove a point action
    $(document).on('click',"#remove-marker",function(e){
        e.preventDefault();var $remove = $('circle.active');
        if ( $('circle.active') ){
            $.removePoint($remove);
        }
    });


    //add all points action
    $(document).on('click',"#save-markers",function(e){
        e.preventDefault();
        $.saveMap();
    });


    $.post('/admin/apps/map/puntos/',function(result) {
        $("#main-box").empty();
        $.when($.createMap(appsmapa), result['puntos']).then(
            function(){
                $.setupPoints(result['puntos'], 'red');
            })

    },"json");

    $('#custom-calendar-reservas').datepicker({
        format: "yyyy-mm-dd",
        weekStart: 1,
        startDate: moment().format('YYYY-MM-DD'),
        language: "es",
        multidate: true,
        keyboardNavigation: false,
        maxViewMode: 0,
        templates : {
            leftArrow: '<i class="fa fa-arrow-left"></i>',
            rightArrow: '<i class="fa fa-arrow-right"></i>'
        }
       //datesDisabled: ['2016-04-29', '2016-04-30']
    });


    $('#custom-calendar-reservas').datepicker().on('changeDate', function(e) {
            // `e` here contains the extra attributes
            var BasicDates = e.dates;
            var datesToCheck = [];
            $.each(BasicDates, function( key, value ) {
                datesToCheck.push(moment(value).format('YYYY-MM-DD'));
            });

            $('#form_dates_reserva').val(datesToCheck);

            postData = {
                dates : datesToCheck,
                place: place = $("#formReserva select[name='form[places]']").val(),
                sala : sala = $("#formReserva select[name='form[salas]']").val()
            };

            $.post('/reservas/prepare/', postData, function (data) {
                $('#reservasDia').empty();
                $('#form_franjas_reserva').val('');
                $('#reservasDia').append(data.view);
                //$('#custom-calendar-reservas').datepicker('setDatesDisabled',"");
            }, "json");


        });

    $('#reservasDia').on('click','a',function(e){
        if(!$(this).hasClass('disabled')){
            if($(this).hasClass('active-reserva')){
                $(this).removeClass('active-reserva');
            }else{
                //Añadimos que se seleccione
                $(this).addClass('active-reserva');
            }

            //Despues de cada seleccion/deseleccion hay que recargar el input
            //Cada vez que se selecciona guardamos un array con todas las seleccionadas
            var franjasToCheck = [];
            $('#reservasDia a.active-reserva ').each(function( index ) {
                franjasToCheck.push($(this).attr('data-value'));
            });
            $('#form_franjas_reserva').val(franjasToCheck);
        }
    });


    $(document).on('click', '.mantenimiento [data-toggle="toggle"]', function () {
        $hasClass = $(this).hasClass('off');
        if($hasClass) {
        //Aquí está el código cuando se quita el mantenimiento
            $.post('/mantenimientoOff/','', function (data) {
            }, "json");

        } else {
        //Aquí activamos el mantenimiento
            $.post('/mantenimientoOn/','', function (data) {
            }, "json");
        }
    });

    $(document).on('click', '#incidenciaResueltaBtn [data-toggle="toggle"]', function () {
        $idIncidencia = $(this).children('input').attr('id');

        $hasClass = $(this).hasClass('off');
        if($hasClass) {
            //Aquí está el código cuando se vuelve a no resuelta
            $.post('/incidencias/resueltaOn/'+$idIncidencia,'', function (data) {
            }, "json");

        } else {
            //Aquí está el código cuando se vuelve a resuelta
            $.post('/incidencias/resueltaOff/'+$idIncidencia,'', function (data) {
            }, "json");
        }
    });

        //Para la vista con el filtro. Lo hago en otra funcion porque si no los toggle no cambian de estado
    $(document).on('click', '#filtroIncidencia [data-toggle="toggle"]', function () {
        $idIncidencia = $(this).children('input').attr('id');

        $hasClass = $(this).hasClass('off');
        console.log(('#'+$idIncidencia));
        if($hasClass) {
            $('#'+$idIncidencia+' [data-toggle="toggle"]').prop('checked', true);
            //Aquí está el código cuando se vuelve a no resuelta
            $.post('/incidencias/resueltaOn/'+$idIncidencia,'', function (data) {
            }, "json");

        } else {
            $('#'+$idIncidencia+' [data-toggle="toggle"]').prop('checked', false);
            //Aquí está el código cuando se vuelve a resuelta
            $.post('/incidencias/resueltaOff/'+$idIncidencia,'', function (data) {
            }, "json");
        }
    });

    $('#btn-filtroIncidencias').click(function (event) {
        event.preventDefault();
        action = $('#formFiltro select').val();

        switch (action) {
            case "0":
                window.location.replace('/incidencias/');

                break;
            case "1":
                window.location.replace('/incidencias/resuelta');

                break;
            case "2":
                window.location.replace('/incidencias/NoResuelta');

                break;

            default:
        }
    });

    $('.info-incidencias').hover(function (event) {
        $elemen=$('.info-incidencias').prev();
        $elemen.tooltip({

        });
    });

    /*$("#main-box").zoompanzoom({
        animationSpeed: "fast"
    });*/

    //Plugin de pagination
    $(function(){
        /* iniciar el plugin */
        $("div.holder").jPages({
        });
    });

    $('.color').spectrum({

        preferredFormat: "rgb",
        showButtons: false,

    });

    //Añadimos clases a los radio button del idioma push de los bandos
    $('#form_idiomaToSend label').click(function () {
       // $('#form_idiomaToSend label').removeClass("activeBandoIdioma");
        $("#idiomaBandoPush").html("El bando se enviará en "+this.innerHTML);
    });

    //marca seleccionado el radio button del idioma push de los bandos
   // $("#form_idiomaToSend input").first().prop('checked',true);

});
    function ModaldemoAction(){
        $('#myModaldemo').modal('toggle')
    }


    $('#demoEmail').click(function(){

        var email = $("#form_email").val();
        console.log(email);
        if (email==''){
           alert('Introduce un email')
        }else {
            $.ajax({
                type: "POST",
                url: '/demo/email/',
                data: "email="+ email,
                success: function(){
                    $('#myModaldemo').remove();
                    $('body').removeClass('modal-open');
                }
            });
        }
       // alert(mail);

        return false;
    });


    $('#realBandoSubmitDemo').click(function(){
        // alert('hola').
        var array = $("#bandoDemo_form").serialize();
        $.ajax({
            type: "POST",
            url: '/demo/create/',
            data: array,
            success: function(){
                $('#demofinal').modal('toggle');
                $("#demopage").load('/demo/');

            }
        });


        return false;
    });


    $('#boton-mandar-mensaje').click(function(){

        var email = $("#form_contact").serialize();
        $.ajax({
            type: "POST",
            url: '/soporte/',
            data: email,
            success: function(){
                $("#emailSoporte").load('/soporte/');
            }

        });


        return '/soporte/';
    });

