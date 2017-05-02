<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
<title>Untitled 1</title>
</head>

<body>
	<!-- Empiezan los Desplegables -->
                        <!-- ================ -->
                        <div class="panel-group panel-group-dinamic collapse-style-2" id="accordion-2">

                            {% if events|length <= 0 %}
                                <h4 class="text-center color-blue">No se han creado {{ fiesta }}s todavÃ­a. </h4>
                            {% else %}
                                <div class="holder"></div>
                                <form role="form" action="/eventos/delete-massive/" name="formDeleteMassive" id="formDeleteMassive" method="post">
                                {% for evento in events %}
                                    <div class="row" id="row">
                                        <div class="col-xs-1 col-sm-1  col-md-1 checks-events margin-left--2">
                                            <input name="check[]" id="check[{{ evento.id }}]" data-toggle="checkbox-x" data-size="sm"  data-three-state="false"/>
                                            <input name="id[]" type="hidden" value="{{ evento.id }}" />
                                        </div>
                                        <div class="col-xs-10 col-sm-11 col-md-11 panel-event padding-left-0">
                                            <div class="panel panel-default  margin-top-bandos">
                                                <div class="panel-headtitulo">
                                                    <h4 class="panel-title">
                                                        <a data-toggle="collapse" data-parent="#accordion-2"
                                                           href="#{{ evento.id }}" data-id="{{ evento.id }}" class="collapsed">
                                                            {% if  evento.active == 0 %}
                                                                <del class="red-del">{{ evento.titulo }} </del><span class="pull-right margin-right-1">del {{ evento.fecha|date('d-m-Y') }} a las {{ evento.fecha|date('H:i') }}</span>
                                                            {% elseif evento.done > 0 %}
                                                                <del class="blue-del">{{ evento.titulo }} </del><span class="pull-right margin-right-1">del {{ evento.fecha|date('d-m-Y') }} a las {{ evento.fecha|date('H:i') }}</span>
                                                            {% else %}
                                                                {{ evento.titulo }} <span class="pull-right margin-right-1">del {{ evento.fecha|date('d-m-Y') }} a las {{ evento.fecha|date('H:i') }}</span>
                                                            {% endif %}
                                                        </a>
                                                    </h4>
                                                </div>
                                                <div id="{{ evento.id }}" class="panel-collapse collapse">
                                                    <div class="panel-body bordered p-15">
                                                        <div id="anima1">
                                                            <div class="img-margin">
                                                                <div class="row">
                                                                    <div class="col-md-5">
                                                                        <div class="panel panel-info">
                                                                            <div class="panel-heading">
                                                                                <h3 class="panel-title">Foto Principal</h3>
                                                                            </div>
                                                                            <div class="panel-body">
                                                                                {#<img id="img" class="img-responsive"
                                                                                     src="http://staticpanelfiestas.devecomputer.es/eventos/{{ evento.usuario }}/{{ evento.id }}/main{{ evento.id }}.{{ evento.mainType }}"
                                                                                     alt="foto principal">#}
                                                                                <img id="img" class="img-responsive"
                                                                                     src="{{ path }}files/eventos/{{ evento.usuario }}/{{ evento.id }}/main{{ evento.id }}.{{ evento.mainType }}?t={{ date().timestamp }}"
                                                                                     alt="foto principal">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-3 col-md-offset-2">
                                                                        <div class="panel panel-info">
                                                                            <div class="panel-heading">
                                                                                <h3 class="panel-title">Foto Miniatura</h3>
                                                                            </div>
                                                                            <div class="panel-body">
                                                                                {#<img id="img" class="img-responsive"
                                                                                     src="http://staticpanelfiestas.devecomputer.es/eventos/{{ evento.usuario }}/{{ evento.id }}/mini{{ evento.id }}.{{ evento.miniType }}"
                                                                                     alt="foto miniatura">#}
                                                                                <img id="img" class="img-responsive"
                                                                                     src="{{ path }}files/eventos/{{ evento.usuario }}/{{ evento.id }}/mini{{ evento.id }}.{{ evento.miniType }}?t={{ date().timestamp }}"
                                                                                     alt="foto miniatura">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-xs-12">
                                                                    <div class="panel panel-info">
                                                                        <div class="panel-heading">
                                                                            <h3 class="panel-title">TÃ­tulo</h3>
                                                                        </div>
                                                                        <div class="panel-body">
                                                                            <p class="">{{ evento.titulo }}</p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="panel panel-info">
                                                                        <div class="panel-heading">
                                                                            <h3 class="panel-title">DescripciÃ³n</h3>
                                                                        </div>
                                                                        <div class="panel-body">
                                                                            <p class="">{{ evento.descripcion }}</p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="panel panel-info">
                                                                        <div class="panel-heading">
                                                                            <h3 class="panel-title">Â¿Pertenece al programa de
                                                                                fiestas?</h3>
                                                                        </div>
                                                                        <div class="panel-body">
                                                                            <p class="">
                                                                                {% if evento.programaFiestas == 1 %}
                                                                                    Si.
                                                                                {% else %}
                                                                                    No.
                                                                                {% endif %}
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="panel panel-info">
                                                                        <div class="panel-heading">
                                                                            <h3 class="panel-title">Â¿Permite suscripciones?</h3>
                                                                        </div>
                                                                        <div class="panel-body">
                                                                            <p class="">
                                                                                {% if evento.subscribe == 1 %}
                                                                                    Si.
                                                                                {% else %}
                                                                                    No.
                                                                                {% endif %}
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="panel panel-info">
                                                                        <div class="panel-heading">
                                                                            <h3 class="panel-title">OrganizaciÃ³n</h3>
                                                                        </div>
                                                                        <div class="panel-body">
                                                                            <p class="">{{ evento.organizacion }}</p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="panel panel-info">
                                                                        <div class="panel-heading">
                                                                            <h3 class="panel-title">Video</h3>
                                                                        </div>
                                                                        <div class="panel-body">
                                                                            {% if evento.url is not null %}
                                                                                {#<a target="_blank" href="{{ evento.url }}">{{ evento.url }}</a>#}
                                                                                {{ evento.url }}
                                                                            {% else %}
                                                                                No hay video.
                                                                            {% endif %}
                                                                        </div>
                                                                    </div>
                                                                    <div class="panel panel-info">
                                                                        <div class="panel-heading">
                                                                            <h3 class="panel-title">Link para informaciÃ³n extra</h3>
                                                                        </div>
                                                                        <div class="panel-body">
                                                                            {% if evento.link is not null %}
                                                                                <a readonly type="text" target="_blank" id="link" name="link" placeholder="Link de informaciÃ³n extra" href="{{ evento.link }}">{{ evento.link }}</a></p>
                                                                            {% else %}
                                                                                No hay link.
                                                                            {% endif %}
                                                                        </div>
                                                                    </div>
                                                                    {% if hasComarca %}
                                                                        <div class="panel panel-info">
                                                                            <div class="panel-heading">
                                                                                <h3 class="panel-title">Pueblo</h3>
                                                                            </div>
                                                                            <div class="panel-body">
                                                                                <p class="">{{ evento.pueblo }}</p>
                                                                            </div>
                                                                        </div>
                                                                    {% endif %}
                                                                    <div class="panel panel-info">
                                                                        <div class="panel-heading">
                                                                            <h3 class="panel-title">Fecha inicial</h3>
                                                                        </div>
                                                                        <div class="panel-body">
                                                                            <p class="">{{ evento.fecha|date('d-m-Y H:i') }}</p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="panel panel-info">
                                                                        <div class="panel-heading">
                                                                            <h3 class="panel-title">Fecha final</h3>
                                                                        </div>
                                                                        <div class="panel-body">
                                                                            <p class="">
                                                                                {% if evento.fecha_final is not null %}
                                                                                    {{ evento.fecha_final|date('d-m-Y H:i') }}
                                                                                {% else %}
                                                                                    Sin fecha final.
                                                                                {% endif %}
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="panel panel-info">
                                                                        <div class="panel-heading">
                                                                            <h3 class="panel-title">Fecha de publicaciÃ³n</h3>
                                                                        </div>
                                                                        <div class="panel-body">
                                                                            <p class="">
                                                                                {% if evento.fecha_publicacion is not null %}
                                                                                    {{ evento.fecha_publicacion|date('d-m-Y H:i') }}
                                                                                {% else %}
                                                                                    Evento publicado en el momento de su creaciÃ³n.
                                                                                {% endif %}
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="panel panel-info">
                                                                        <div class="panel-heading">
                                                                            <h3 class="panel-title">CategorÃ­a</h3>
                                                                        </div>
                                                                        <div class="panel-body">
                                                                            <p class="">{{ evento.category }}</p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="panel panel-info">
                                                                        <div class="panel-heading">
                                                                            <h3 class="panel-title">Lugar</h3>
                                                                        </div>
                                                                        <div class="panel-body">
                                                                            <p class="">{{ evento.lugar }}</p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="panel panel-info">
                                                                        <div class="panel-heading">
                                                                            <h3 class="panel-title">Latitud</h3>
                                                                        </div>
                                                                        <div class="panel-body">
                                                                            <p class="">{{ evento.latitud }}</p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="panel panel-info">
                                                                        <div class="panel-heading">
                                                                            <h3 class="panel-title">Longitud</h3>
                                                                        </div>
                                                                        <div class="panel-body">
                                                                            <p class="">{{ evento.longitud }}</p>
                                                                        </div>
                                                                    </div>
                                                                    {% if capabilities.subscribe %}
                                                                        <div class="panel panel-info">
                                                                            <div class="panel-heading">
                                                                                <h3 class="panel-title">Suscripciones</h3>
                                                                            </div>
                                                                            <div class="panel-body">
                                                                                {% if evento.subscribe == 1 %}
                                                                                    {% for subscriber in evento.subscribers %}
                                                                                        <p>Nombre: {{ subscriber.name }}<span class="margin-left-1">Email: {{ subscriber.email }}</span><span class="margin-left-1">TelÃ©fono: {{ subscriber.phone }}</span></p>
                                                                                    {% else %}
                                                                                        Sin suscripciones.
                                                                                    {% endfor %}
                                                                                {% else %}
                                                                                    <p class=""> No es un evento con suscripciones.</p>
                                                                                {% endif %}
                                                                            </div>
                                                                        </div>
                                                                    {% endif %}
                                                                    <div class="col-md-12 margin-top-1" >
                                                                        <a id="eventoEdit{{ evento.id }}" href="/eventos/edit/{{ evento.id }}" class="form-button btn btn-lg btn-primary float-right  btn-margin-top margin-right-1">Editar</a>

                                                                        <a class="color-eliminar " data-toggle="modal" data-target="#myModal{{ evento.id }}">Eliminar evento</a>
                                                                        <div class="modal fade" id="myModal{{ evento.id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                                                            <div class="modal-dialog" role="document">
                                                                                <div class="modal-content">
                                                                                    <div class="modal-header">
                                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                                        <h4 class="modal-title" id="myModalLabel">ConfirmaciÃ³n de borrado</h4>
                                                                                    </div>
                                                                                    <div class="modal-body">
                                                                                        <p>Â¿EstÃ¡ seguro de querer eliminar <strong>permanentemente</strong> este evento ?</p>
                                                                                    </div>
                                                                                    <div class="modal-footer ">
                                                                                        <div class="align-eliminar col-md-6 col-xs-6">
                                                                                            <a class="btn btn-danger " href="/eventos/delete/{{ evento.id }}/eventos">Eliminar</a>
                                                                                        </div>
                                                                                        <div class="col-md-6 col-xs-6" >
                                                                                            <button type="button" class="btn btn-default align-cancel" data-dismiss="modal">Cancelar</button>
                                                                                        </div>

                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        {% if evento.active == 0 %}
                                                                            <a id="eventoEdit{{ evento.id }}" href="/eventos/active/{{ evento.id }}" class="btn btn-warning btn-lg btn-margin-top" data-toggle="modal">Activar</a>
                                                                            {#{% else %}#}
                                                                            {#<a id="eventoEdit{{ evento.id }}" href="/eventos/deactive/{{ evento.id }}" class="btn btn-warning btn-lg btn-margin-top" data-toggle="modal">Desactivar</a>#}
                                                                        {% endif %}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                {% endfor %}
                                </form>
                                <!--Pagination-->
                                <div class="holder"></div>
                            {% endif %}
                        </div>

</body>

</html>
