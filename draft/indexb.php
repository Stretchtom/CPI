<!DOCTYPE HTML>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>Highcharts Example</title>

        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
        <style type="text/css">
            ${demo.css}
        </style>
        <script type="text/javascript">
            $(function () {
                $('#container').highcharts({
                    title: {
                        text: 'Monthly Average Temperature',
                        x: -20 //center
                    },
                    subtitle: {
                        text: 'Source: WorldClimate.com',
                        x: -20
                    },
                    xAxis: {
                        categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                            'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
                    },
                    yAxis: {
                        title: {
                            text: 'Temperature (°C)'
                        },
                        plotLines: [{
                                value: 0,
                                width: 1,
                                color: '#808080'
                            }]
                    },
                    tooltip: {
                        valueSuffix: '°C'
                    },
                    legend: {
                        layout: 'vertical',
                        align: 'right',
                        verticalAlign: 'middle',
                        borderWidth: 0
                    },
                    series: [{
                            name: 'Tokyo',
                            data: [7.0, 6.9, 9.5, 14.5, 18.2, 21.5, 25.2, 26.5, 23.3, 18.3, 13.9, 9.6]
                        }, {
                            name: 'New York',
                            data: [-0.2, 0.8, 5.7, 11.3, 17.0, 22.0, 24.8, 24.1, 20.1, 14.1, 8.6, 2.5]
                        }, {
                            name: 'Berlin',
                            data: [-0.9, 0.6, 3.5, 8.4, 13.5, 17.0, 18.6, 17.9, 14.3, 9.0, 3.9, 1.0]
                        }, {
                            name: 'London',
                            data: [3.9, 4.2, 5.7, 8.5, 11.9, 15.2, 17.0, 16.6, 14.2, 10.3, 6.6, 4.8]
                        }]
                });
            });
        </script>
    </head>
    <body>
<!--<script src="../../js/highcharts.js"></script>
<script src="../../js/modules/exporting.js"></script>-->
        <script src="Highcharts-4.1.5/js/highcharts.js"></script>
        <script src="Highcharts-4.1.5/js/modules/exporting.js"></script>

        <div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>


        <div class="row col-xs-6 pull-left"> 
                    <script type="text/javascript" src="D3/d3.v3.min.js"></script>

            <script>
 var margin = {top: 220, right: 400, bottom: 220, left: 350},
 radius = Math.min(margin.top, margin.right, margin.bottom, margin.left) - 10;

 var hue = d3.scale.category10();

 var luminance = d3.scale.sqrt()
         .domain([0, 1e6])
         .clamp(true)
         .range([90, 20]);

 var svg = d3.select("body").append("svg")
         .attr("width", margin.left + margin.right)
         .attr("height", margin.top + margin.bottom)
         .append("g")
         .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

 var partition = d3.layout.partition()
         .sort(function (a, b) {
             return d3.ascending(a.name, b.name);
         })
         .size([2 * Math.PI, radius]);

 var arc = d3.svg.arc()
         .startAngle(function (d) {
             return d.x;
         })
         .endAngle(function (d) {
             return d.x + d.dx - .01 / (d.depth + .5);
         })
         .innerRadius(function (d) {
             return radius / 3 * d.depth;
         })
         .outerRadius(function (d) {
             return radius / 3 * (d.depth + 1) - 1;
         });

 d3.json("json/flare.json", function (error, root) {

     // Compute the initial layout on the entire tree to sum sizes.
     // Also compute the full name and fill color for each node,
     // and stash the children so they can be restored as we descend.
     partition
             .value(function (d) {
                 return d.size;
             })
             .nodes(root)
             .forEach(function (d) {
                 d._children = d.children;
                 d.sum = d.value;
                 d.key = key(d);
                 d.fill = fill(d);
             });

     // Now redefine the value function to use the previously-computed sum.
     partition
             .children(function (d, depth) {
                 return depth < 2 ? d._children : null;
             })
             .value(function (d) {
                 return d.sum;
             });

     var center = svg.append("circle")
             .attr("r", radius / 3)
             .on("click", zoomOut);

     center.append("title")
             .text("zoom out");

     var path = svg.selectAll("path")
             .data(partition.nodes(root).slice(1))
             .enter().append("path")
             .attr("d", arc)
             .style("fill", function (d) {
                 return d.fill;
             })
             .each(function (d) {
                 this._current = updateArc(d);
             })
             .on("click", zoomIn);

     function zoomIn(p) {
         if (p.depth > 1)
             p = p.parent;
         if (!p.children)
             return;
         zoom(p, p);
     }

     function zoomOut(p) {
         if (!p.parent)
             return;
         zoom(p.parent, p);
     }

     // Zoom to the specified new root.
     function zoom(root, p) {
         if (document.documentElement.__transition__)
             return;

         // Rescale outside angles to match the new layout.
         var enterArc,
                 exitArc,
                 outsideAngle = d3.scale.linear().domain([0, 2 * Math.PI]);

         function insideArc(d) {
             return p.key > d.key
                     ? {depth: d.depth - 1, x: 0, dx: 0} : p.key < d.key
                     ? {depth: d.depth - 1, x: 2 * Math.PI, dx: 0}
             : {depth: 0, x: 0, dx: 2 * Math.PI};
         }

         function outsideArc(d) {
             return {depth: d.depth + 1, x: outsideAngle(d.x), dx: outsideAngle(d.x + d.dx) - outsideAngle(d.x)};
         }

         center.datum(root);

         // When zooming in, arcs enter from the outside and exit to the inside.
         // Entering outside arcs start from the old layout.
         if (root === p)
             enterArc = outsideArc, exitArc = insideArc, outsideAngle.range([p.x, p.x + p.dx]);

         path = path.data(partition.nodes(root).slice(1), function (d) {
             return d.key;
         });

         // When zooming out, arcs enter from the inside and exit to the outside.
         // Exiting outside arcs transition to the new layout.
         if (root !== p)
             enterArc = insideArc, exitArc = outsideArc, outsideAngle.range([p.x, p.x + p.dx]);

         d3.transition().duration(d3.event.altKey ? 7500 : 750).each(function () {
             path.exit().transition()
                     .style("fill-opacity", function (d) {
                         return d.depth === 1 + (root === p) ? 1 : 0;
                     })
                     .attrTween("d", function (d) {
                         return arcTween.call(this, exitArc(d));
                     })
                     .remove();

             path.enter().append("path")
                     .style("fill-opacity", function (d) {
                         return d.depth === 2 - (root === p) ? 1 : 0;
                     })
                     .style("fill", function (d) {
                         return d.fill;
                     })
                     .on("click", zoomIn)
                     .each(function (d) {
                         this._current = enterArc(d);
                     });

             path.transition()
                     .style("fill-opacity", 1)
                     .attrTween("d", function (d) {
                         return arcTween.call(this, updateArc(d));
                     });
         });
     }
 });

 function key(d) {
     var k = [], p = d;
     while (p.depth)
         k.push(p.name), p = p.parent;
     return k.reverse().join(".");
 }

 function fill(d) {
     var p = d;
     while (p.depth > 1)
         p = p.parent;
     var c = d3.lab(hue(p.name));
     c.l = luminance(d.sum);
     return c;
 }

 function arcTween(b) {
     var i = d3.interpolate(this._current, b);
     this._current = i(0);
     return function (t) {
         return arc(i(t));
     };
 }

 function updateArc(d) {
     return {depth: d.depth, x: d.x, dx: d.dx};
 }
 d3.select(self.frameElement).style("height", margin.top + margin.bottom + "px");
            </script>
            
        </div> 
    </body>
</html>
