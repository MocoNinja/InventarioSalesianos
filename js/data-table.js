/**
 * Created by Usuario on 14/04/2016.
 */
$(document).ready(function() {

  table = $('#example').DataTable( {
        "processing": true,
        "serverSide": true,
        "ajax": "/admin/log/loadTable",

        "language": {
            'sProcessing': 'Procesando...',
            'sLengthMenu': 'Mostrar _MENU_ registros',
            'sZeroRecords': 'No se encontraron resultados',
            'sEmptyTable': 'Ningún dato disponible en esta tabla',
            'sInfo': 'Mostrando registros del _START_ al _END_ de _TOTAL_ registros',
            'sInfoEmpty': 'Mostrando registros del 0 al 0 de 0 registros',
            'sInfoFiltered': '(filtrado de un total de _MAX_ registros)',
            'sInfoPostFix': '',
            'sSearch': 'Buscar:',
            'sUrl': '',
            'sInfoThousands': ',',
            'sLoadingRecords': 'Cargando...',
            'oPaginate': {
                'sFirst': 'Primero',
                'sLast': 'Último',
                'sNext': 'Siguiente',
                'sPrevious': 'Anterior'
            },
            'oAria': {
                'sSortAscending': ': Activar para ordenar la columna de manera ascendente',
                'sSortDescending': ': Activar para ordenar la columna de manera descendente'
            },
        },

        "columns": [

            {
                data: 'id',
            },
            {
                data: 'userId',
                "visible":false,
            },
            {
                data: 'action',
            },
            {
                data: 'descripcion',
            },
            {
                data: 'fecha',
            },
            {
                data: 'id',
                'render' : function(data){
                    return '<button type="button" class="" id="data-button" data-id="'+data+'" data-toggle="modal" data-target=".bs-example-modal-lg">Previsualización</button>'
                }//class=" fa fa-info-circle"
            },


        ],


    });

    $("#example").on('click', '#data-button', function() {

        var data = table.row($(this).parents('tr')).data();

        id = data['id'];
        userId = data['userId']
        descripcion = data['descripcion']
        fecha=data['fecha']
       // console.log(data);
        postData = {
            data: data
        };
        $.post('/admin/log/modal',postData,function(result) {
            $("#addModalContent").empty();
            $("#addModalContent").append(result['modal']);
            $input = $("#addModalContent").find();
            $($input).fileinput();

            if(result['plano']){
                $.when($.createMap(result['plano']), result['puntos']).then(
                    function(){
                        $.setupPoints(result['puntos'], 'red');
                    }
                );
            }
           // $("#dataModal").modal("show");
        }, "json");

    })
});
