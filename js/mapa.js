/**
 * Created by Usuario on 21/04/2016.
 */
(function($){

    //vars to define the map and dots.
    var radius = 8;
    var mapWidth = 1045;
    var mapHeight = 1489;
    //store drag behavior
    var drag = d3.behavior.drag()
        .on("drag", dragmove)
        .on("dragend", activeDragged);

    //function when drag starts
    function dragmove(d) {
        $.removeActives(); //remove the active points

        //move
        d3.select(this)
            .attr("cx", d.x = Math.max(radius, Math.min(mapWidth - radius, d3.event.x)))
            .attr("cy", d.y = Math.max(radius, Math.min(mapHeight - radius, d3.event.y)));
    }

    //select the point we've been moving
    function activeDragged(){
        d3.select(this).attr("class", 'active');
        //d3.select(this).attr('fill', '#A00000');
    }

    //create the svg map, with its image and the given sizes
    $.createMap = function(pointsMap){

        mapImage = pointsMap ;

        return d3.select("#main-box").append("svg")
            .attr("width", mapWidth)
            .attr("height", mapHeight)
            .append("image")
            .attr("xlink:href", mapImage)
            .attr("x",0)
            .attr("y",0)
            .attr("width",mapWidth)
            .attr("height",mapHeight)

    }

    //setup the points in the map when the page loads and when inserting new ones
    $.setupPoints = function(newCircles, setClass){
        svgContainer = d3.select("#main-box svg");
        //add points
        var circles = svgContainer.selectAll("circle")
            .data(newCircles, function(d){return d.x})
            .enter()
            .append("circle");
        //set points style
        var circleAttributes = circles
            .attr("cx", function (d) { return d.x; })
            .attr("cy", function (d) { return d.y; })
            .attr("r", 8 )
            .attr('fill', function (d) { return d.fill; })
            .attr('data-id',function (d) { return d.id; })
            .call(drag); // add drag behavior
    }

    //add new point
    $.addPoint = function($fill){
        var $markers = $('circle');
        var count = $markers.length;
        var newCircle = [{x: 15, y: 15, id: count+1, fill:$fill }];

        $.removeActives($fill);
        $.setupPoints(newCircle);

    }

    //remove selected point
    $.removePoint = function($remove){
        $remove.remove();
    }

    //save the map, array of objects with "x" and "y"
    $.saveMap = function ($fill){
        var $markers = $('circle');
        var i=0;
        arrMarkers = [];
        for ( i = 0 ; i < $markers.length ;i++){
            var marker = $($markers[i]);
            var point ={};
            point['x'] = marker.attr('cx');
            point['y'] = marker.attr('cy');
            point['id']= marker.attr('data-id');
            arrMarkers.push(point);
        }
        postData = {
            data : arrMarkers,
        };
        $.post('/admin/apps/points/save/',postData,function(result) {
            if(result['status'] == "save"){
                var newCommit = '<li>';
                newCommit += '<div class="cbp_tmicon cbp_tmicon-phone"></div>';
                newCommit += '<div class="cbp_tmlabel">';
                newCommit += '<h4><a data-id="'+result["id"]+'" class="custom-link-rv" href="#" role="button">'+result["rv"]+' '+result["fecha"]+'</a></h4>';
                newCommit += '</div>';
                newCommit += '</li>';
                $(".cbp_tmtimeline").prepend(newCommit);
                $.growl.notice({title: "¡Guardado!", message: "Guardado satisfactoriamente" });
            }else if(result['status'] == "noPoints"){
                $.growl.error({ title: "¡Cuidado!", message: "No hay puntos"});
            }else{
                $.growl.warning({ title: "¡Ups!", message: "El mapa no ha cambiado"});
            }
        },"json");

    }

    //unselect item by removing active class and set the standard color
    $.removeActives = function($fill){
        $('circle').attr('fill', $fill);

    }
    //select item by adding active class and set a different color
    $.selectItem = function($selectNode){
        $selectNode.attr('fill', '#A00000');
        $selectNode.attr('class','active');

    }


})(jQuery);