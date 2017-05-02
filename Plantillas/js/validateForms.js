$(document).ready(function () {

    $("#event_form").validate({
        rules: {
            'form[titulo]':{
                required:true
            },
            'form[descripcion]':{
                required:true
            },
            'form[organizacion]':{
                required:true
            },
            'form[fecha]':{
                required:true
            },
            'form[fechaInicial]':{
                required:true,
                time:true
            },
            'form[fechaFinal]':{
                required:false,
                time:true
            },
            'form[fechaPubiclacion]':{
                required:false,
                time:true,
            },
            'form[programaFiestas]':{
                required:false
            },
            'form[categories]':{
                required:true
            },
            'form[lugar]':{
                required:true
            },
            'form[latitud]': {
                number: true
            },
            'form[longitud]':{
                number: true
            },
            'form[foto_principal]':{
                extension: "png|jpg",
                filesize: true
            },
            'form[foto_miniatura]':{
                extension: "png|jpg",
                filesize: true
            }
        }
    });
    $("#eventEdit_form").validate({
        rules: {
            'form[latitud]': {
                number: true
            },
            'form[longitud]':{
                number: true
            },
            'form[foto_principal]':{
                extension: "png|jpg",
                filesize: true
            },
            'form[foto_miniatura]':{
                extension: "png|jpg",
                filesize: true
            }
        }
    });
    $('#category_form').validate({
        rules: {
            'form[category]': {
                required : true
            },
            'form[icon]': {
                required: false,
                extension: "png|jpg",
                filesize: true
            }
        }
    });
    $('#editCategory_form').validate({
        rules: {
            'form[category]': {
                required: true
            },
            'form[icon]': {
                extension: "png|jpg",
                filesize: true
            },
            'form[defaultMain]': {
                extension: "png|jpg",
                filesize: true
            },
            'form[defaultMini]': {
                extension: "png|jpg",
                filesize: true
            }
        }
    });
    $("#baner_form").validate({
        rules: {
            'form[baner]':{
                required:true
            },
            'form[descripcion]':{
                required:true
            },
            'form[file]': {
                required:false,
                extension: "png|jpg",
                filesize: true

            }
        }
    });
    $("#banerEdit_form").validate({
        rules: {
            'form[file]': {
                extension: "png|jpg",
                filesize: true
            }
        }
    });
    $("#phone_form").validate({
        rules: {
            'form[name]':{
                required:true
            },
            'form[phone]': {
                digits: true
            }
        }
    });

    $("#formCategoriesPhone").validate({
        rules: {
            'form[category]':{
                required:true
            },
            'form[icon]': {
                required : false,
                extension: "png|jpg",
                filesize: true
            }
        }
    });
    $("#editCategoryPhone_form").validate({
        rules: {
            'form[icon]': {
                extension: "png|jpg",
                filesize: true
            }
        }
    });
    $("#phoneEdit_form").validate({
        rules: {
            'form[name]': {
                required: false
            },
            'form[categories]': {
                required:true
            },
            'form[phone]': {
                required: false,
                digits: true
            }
        }
    });
    $("#farmacia_form").validate({
        rules: {
            'form[name]': {
                required: true
            },
            'form[description]': {
                required:true
            },
            'form[phone]': {
                required: false,
                digits: true
            },
            'form[latitud]': {
                required:true,
                number: true
            },
            'form[longitud]':{
                required:true,
                number: true
            }
        }
    });

    $("#villageForm").validate({
        rules: {
            'form[name]': {
                required: true
            }
        }
    });
    $("#bandosToSend_form").validate({
        rules: {
            'form[bando]': {
                required: true
            },
            'form[descripcion]': {
                required: true
            },
            'form[fechaToSend]': {
                required:true,
                time:true
            },
            'form[canales]': {
                required: true
            }
        }
    });

    $("#turismo_form").validate({
        rules: {
            'form[name]': {
                required: true
            },
            'form[description]': {
                required: true
            },
            'form[latitud]': {
                required: true,
                number: true
            },
            'form[longitud]': {
                required: true,
                number: true
            },
            'form[imagen]': {
                required: false,
                extension: "png|jpg",
                filesize: true

            }
        }
    });

    $("#turismoEdit_form").validate({
        rules: {
            'form[latitud]': {
                number: true
            },
            'form[longitud]': {
                number: true
            },
            'form[imagen]': {
                extension: "png|jpg",
                filesize: true
            }
        }
    });

    $("#turismo_historia_form").validate({
        rules: {
            'form[imagenHistoria]': {
                extension: "png|jpg",
                filesize: true
            },
            'form[imagenZona]': {
                extension: "png|jpg",
                filesize: true
            },
            'form[imagenEscudo]': {
                extension: "png|jpg",
                filesize: true
            }
        }
    });

    $('#defunciones_form').validate({
        rules: {
            'form[name]': {
                required: true
            },
            'form[mensaje]': {
                required: true
            }
        }
    });

    $( "#submitEnviarBando" ).click(function( event ) {
        var validate = $('#bando_form').validate({
            rules: {
                'form[bando]': {
                    required: true
                },
                'form[descripcion]': {
                    required: true
                },
                'form[fechaToSend]': {
                    required:true,
                    time:true
                },
                'form[canales]': {
                    required: true
                },
                'form[image]': {
                    extension: "png|jpg",
                    filesize: true
                }
            }
        });
        var v =validate.form();
        if (v){
            event.preventDefault();
            $('#myModalBandos').modal('show');
        }
    });

    $( "#submitEnviarBando2" ).click(function( event ) {
        var validate = $('#bandoDemo_form').validate({
            rules: {
                'form[bando]': {
                    required: true
                },
                'form[descripcion]': {
                    required: true
                },
                'form[fechaToSend]': {
                    required:true,
                    time:true
                },
                'form[canales]': {
                    required: true
                },
                'form[image]': {
                    extension: "png|jpg",
                    filesize: true
                }
            }
        });
        var v =validate.form();
        if (v){
            event.preventDefault();
            $('#myModalBandosDemo').modal('show');
        }
    });

    $("#regeneratePasswordform").validate({
        rules: {
            'form[password1]': {
                required: true
            },
            'form[password2]': {
                required: true,
                equalTo: "#form_password1"
            }
        },
        messages: {
            'form[password2]': {
                equalTo: "Las contraseñas que ha escrito no concuerdan, por favor vuelva a escribirlas"
            }
        }
    });

    $("#servicio_form").validate({
        rules: {
            'form[servicio]': {
                required: true
            },
            'form[type]': {
                required: true
            },
            'form[phone]': {
                maxlength: 9,
                minlength: 9,
                digits: true
            },
            'form[fax]': {
                maxlength: 9,
                minlength: 9,
                digits: true
            },
            'form[email]': {
                email: true
            },
            'form[latitud]': {
                number: true
            },
            'form[longitud]': {
                number: true
            },
            'form[fotoServicio]': {
                extension: "png|jpg",
                filesize: true
            }
        }
    });

    $("#servicioEdit_form").validate({
        rules: {
            'form[servicio]': {
                required: true
            },
            'form[phone]': {
                maxlength: 9,
                minlength: 9,
                digits: true
            },
            'form[fax]': {
                maxlength: 9,
                minlength: 9,
                digits: true
            },
            'form[email]': {
                email: true
            },
            'form[latitud]': {
                number: true
            },
            'form[longitud]': {
                number: true
            },
            'form[fotoServicio]': {
                extension: "png|jpg",
                filesize: true
            }
        }
    });

    $("#formPlaces").validate({
        rules: {
            'form[latitud]': {
                number: true
            },
            'form[longitud]': {
                number: true
            }
        }
    });

    $("#formEditPlace").validate({
        rules: {
            'form[latitud]': {
                number: true
            },
            'form[longitud]': {
                number: true
            }
        }
    });

    $("#formNewSala").validate({
        rules: {
            'form[franja]': {
                required:true,
                digits: true
            },
            'form[price]': {
                required:true,
                currency: true
            },
            'form[scheduleMorning1]': {
                required:true
            },
            'form[scheduleMorning2]': {
                required:true,
                greaterThan:"#form_scheduleMorning1"
            },
            'form[scheduleAfternoom1]': {
                required:true
            },
            'form[scheduleAfternoom2]': {
                required:true,
                greaterThan:"#form_scheduleAfternoom1"
            }

        }
    });
    $("#formSalaEdit").validate({
        rules: {
            'form[franja]': {
                required:true,
                currency: true
            },
            'form[price]': {
                required:true,
                number: true
            },
            'form[scheduleMorning1]': {
                required:true
            },
            'form[scheduleMorning2]': {
                required:true,
                greaterThan:"#form_scheduleMorning1"
            },
            'form[scheduleAfternoom1]': {
                required:true
            },
            'form[scheduleAfternoom2]': {
                required:true,
                greaterThan:"#form_scheduleAfternoom1"
            }

        }
    });

    $("#formReserva").validate({
        ignore: "",
        rules: {
            'form[nombre]': {
                required:true
            },
            'form[telefono]': {
                digits: true,
                maxlength: 9,
                minlength: 9,
                required: function(element) {
                    return $("#form_email").is(':blank');
                }
            },
            'form[email]': {
                email: true,
                required: function(element) {
                    return $("#form_telefono").is(':blank');
                }
            },
            'form[places]': {

            },
            'form[salas]': {
                required:true
            },
            'form[dates_reserva]': {
                required:true
            },
            'form[franjas_reserva]': {
                required:function(){
                    if($('td.active').length>0){
                        return true;
                    }else{
                        return false;
                    }
                }
            }
        },
        messages:{
            'form[dates_reserva]': {
                required: "Debe seleccionar un día."
            },
            'form[franjas_reserva]': {
                required:"Debe seleccionar una franja."

            }
        }
    });

    $("#anuncio_form").validate({
        rules: {
            'form[anuncio]':{
                required:true
            },
            'form[descripcion]':{
                required:true
            },
            'form[file]': {
                required:false,
                extension: "png|jpg",
                filesize: true
            }
        }
    });

    $("#anuncioEdit_form").validate({
        rules: {
            'form[file]': {
                extension: "png|jpg",
                filesize: true
            }
        }
    });

    $("#micro_eventosform").validate({
        rules: {
            'form[title]':{
                required:true
            },
            'form[content]':{
                required:true
            },
            'form[date_init]':{
                required:true
            },
            'form[lugar]':{
                required:true
            }
        }
    });



    jQuery.validator.addMethod("filesize", function (val, element) {
        if(element.files.length>0){
            var size = element.files[0].size;
            if (size > 2000000)// checks the file more than 1 MB
            {
                return false;
            } else {
                return true;
            }
        }else{
            return true;
        }
    }, "Fichero demasiado grande");

    $.validator.addMethod('time', function(value, element, param) {
        return value == '' || value.match(/^([01][0-9]|2[0-3]):[0-5][0-9]$/);
    }, 'Introduce una hora correcta');

    jQuery.validator.addMethod("greaterThan",function(value, element, params) {

        var time2 = moment(value,"HH:mm");
        var time1 = moment($(params).val(),"HH:mm");

            if (time2 > time1) {
                return true;
            }

    },'La hora final tiene que ser superior a la hora inicial.');

    jQuery.validator.addMethod("lessThan",function(value, element, params) {

        var time2 = moment(value,"HH:mm");
        var time1 = moment($(params).val(),"HH:mm");

        if (time2 < time1) {
            return true;
        }
    },'La hora inicial tiene que ser menor que la final.');

    $.validator.addMethod("currency", function (value, element) {
        return this.optional(element) || /^(\d+|\d{1,3}(,\d{3})*)+(\.\d{2}|\,\d{2})?$/.test(value);
    }, "Escriba un precio válido.");
});
