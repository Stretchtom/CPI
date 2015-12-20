<?php
include_once 'phpscripts/conn.php';
include_once("phpscripts/secure_text.php");
$secure = new secureText;
$msg = "";
?> 

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="color:Background" content="#f4fbfa" />
        <title>NISR CPI Visualization</title>

        <link rel="stylesheet" href="assets/css/bootstrap.min.css" type="text/css" />
        <link rel="stylesheet" href="assets/css/main.css" type="text/css" />
        <link rel="stylesheet" href="assets/css/ktn.css" type="text/css" />
        <link rel="shortcut icon" href="assets/images/icon.png" /> 
        
        <script type="text/javascript" src="D3/d3.v3.min.js"></script> 

        <link rel="stylesheet" href="assets/css/redmond/jquery-ui-1.7.1.custom.css" type="text/css" />
        <link rel="Stylesheet" href="assets/css/ui.slider.extras.css" type="text/css" />

        <style type="text/css">
            p { clear:both; }
            form { margin: 0 30px;}
            fieldset { border:0; margin-top: 1em;}	
            label {font-weight: normal; float: left; margin-right: .5em; font-size: 1.1em;}
            select {margin-right: 1em; float: left;}
            .ui-slider {clear: both; top: 5em;}

            circle,
            path {
                cursor: pointer;
            }

            circle {
                fill: none;
                pointer-events: all;
            }

        </style>

        <meta name="text:Posts Background Alpha" content="0.9"/>


        <style type="text/css">
            body{
                background-color: #ffffff;   
            }

            .vertical-offset-100{
                padding-top:0px;

            }

        </style> 

    </head> 
    <body>
        <div class="container"> 
            <header class="navbar-inverse tete">
                <div class="col-xs-12 col-sm-12 col-md-12  col-lg-12" id="menubar">

                    <ul class="list-group">
                        <li class="list-group-item">
                            <div class="row form-inline">
                                <img src="../CPI/images/NISR-logo.png" height="5%" width="7%"/>

                                <div class="form-group">

                                    <form action="" method="get">
                                        <input type="text" class="form-control"  name="search" style="width:400px; height:33px; color:#444444; font-size:16px;" 
                                               value="Search CPI Group&hellip;" 
                                               onfocus="if (this.value == 'Search CPI Group&hellip;') {
                                                           this.value = '';
                                                           this.focus();
                                                       }
                                                       ;" 
                                               onblur="if (this.value == '') {
                                                           this.value = 'Search CPI Group&hellip;'
                                                       }
                                                       ;"onkeyup="showHint(this.value)" />
                                        <i class="glyphicon glyphicon-search"></i> 
                                    </form>
                                    <?php // include_once("../CPI/includes/search_field.php"); ?>  
                                </div>
                                <div class="form-group">
                                    <h1> Consumer Price Index</h1>
                                    <div class="row pull-right">
                                        <a href="./?Login" class="btn btn-success">Login</a>
                                        <button class="btn btn-primary">Download</button>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>  

                </div> 

            </header>
        </div>

        <div class="container">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                <?php
                if (isset($_GET['Login'])) {
                    echo '

                         <div class="container" style="padding-top:-22cm">
                                <div class="row vertical-offset-100">
                                    <div class="col-md-4 col-md-offset-3">
                                        <div class="panel panel-primary">
                                            <div class="panel-heading">
                                                <h3 class="panel-title">Please sign in</h3>
                                            </div>
                                            <div class="panel-body" style="background-color: #ccc" >
                                                <form accept-charset="UTF-8" role="form">
                                                <fieldset>
                                                    <div class="form-group">
                                                        <input class="form-control" placeholder="E-mail" name="email" type="text">
                                                    </div>
                                                    <div class="form-group">
                                                        <input class="form-control" placeholder="Password" name="password" type="password" value="">
                                                    </div>
                                                    <input class="btn btn-lg btn-primary btn-lg col-md-offset-4" type="submit" value="Login">
                                                </fieldset>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </div>
                           ';
                } else {
                    ?>


                    <div  class="col-xs-6 col-md-6">
                        <script>

                            var margin = {top: 220, right: 400, bottom: 220, left: 350},
                            radius = Math.min(margin.top, margin.right, margin.bottom, margin.left) - 10;

                            var hue = d3.scale.category10();

                            var luminance = d3.scale.sqrt()
                                    .domain([0, 1e6])
                                    .clamp(true)
                                    .range([90, 20]);

                            var svg = d3.select("body").append("svg")
                                    .attr("width", 600)
                                    .attr("height", 500)
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
                <script src="js/jquery.min.js"></script>
                <script src="Highcharts-4.1.5/js/highcharts.js"></script>
                <script src="Highcharts-4.1.5/js/modules/exporting.js"></script>


                <script type="text/javascript">
            $(function () {
                $('#container1').highcharts({
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

        <div id="container1" class="col-xs-6 col-md-6" style="min-width: 310px; height: 400px; margin: 0 auto"></div>

                </div>
            <script type="text/javascript" src="js/jquery-1.3.2.min.js"></script>
            <script>
        var $i = jQuery.noConflict(true);
                </script>
            <script type="text/javascript" src="js/jquery-ui-1.7.1.custom.min.js"></script>
            <script type="text/javascript" src="js/selectToUISlider.jQuery.js"></script>
                <script type="text/javascript">
                    $(function () {
                        $('select').selectToUISlider({
                            labels: 7
                        });
                        //fix color
                        fixToolTipColor();
                    });

                    //purely for theme-switching demo... ignore this unless you're using a theme switcher
                    //quick function for tooltip color match
                    function fixToolTipColor() {
                        //grab the bg color from the tooltip content - set top border of pointer to same
                        $('.ui-tooltip-pointer-down-inner').each(function () {
                            var bWidth = $('.ui-tooltip-pointer-down-inner').css('borderTopWidth');
                            var bColor = $(this).parents('.ui-slider-tooltip').css('backgroundColor')
                            $(this).css('border-top', bWidth + ' solid ' + bColor);
                        });
                    }
                </script>



                        <?php
                    }
                    ?>
        </div>

        <div class="container" style="margin-top: -2cm">
            <form action="#">
                <fieldset>
                    <label for="valueA" style="display: none">Start Date:</label>

                    <?php

                    function year($cpi_time, $start, $nb) {
                        return substr($cpi_time, $start, $nb);
                    }

                    function month($cpi_time, $start, $nb) {
                        $inWord = '';
                        $ukwezi = substr($cpi_time, $start, $nb);
                        switch ($ukwezi) {
                            case '01':
                                $inWord = 'Jan';
                                break;
                            case '02':
                                $inWord = 'Feb';
                                break;
                            case '03':
                                $inWord = 'Mar';
                                break;
                            case '04':
                                $inWord = 'Apr';
                                break;
                            case '05':
                                $inWord = 'May';
                                break;
                            case '06':
                                $inWord = 'Jun';
                                break;
                            case '07':
                                $inWord = 'Jul';
                                break;
                            case '08':
                                $inWord = 'Aug';
                                break;
                            case '09':
                                $inWord = 'Sep';
                                break;
                            case '10':
                                $inWord = 'Oct';
                                break;
                            case '11':
                                $inWord = 'Nov';
                                break;
                            case '12':
                                $inWord = 'Dec';
                                break;
                        }
                        return $inWord;
                    }

                    $sql_query = "select distinct time_cpi_group from cpi_data_group order by time_cpi_group";
                    $date_query = mysql_query($sql_query) or die(mysql_error());

                    $count = mysql_num_rows($date_query);
                    ?>
                    <select name="valueA" class="" id="valueA" style="display: none">
                        <?php
                        $last = '';
                        if ($count > 0) {
                            $selected = $count * 0.2;
                            $i = 0;
                            $optgroup = "2009";
                            echo '<optgroup label=' . $optgroup . '>';
                            while ($row = mysql_fetch_array($date_query)) {

                                $period = $row['time_cpi_group'];

                                if ($optgroup !== year($period, 0, 4)) {
                                    $optgroup = year($period, 0, 4);
                                    echo '<optgroup label=' . $optgroup . '>';
                                } else {
                                    $last = month($period, 5, 2);
                                    if ($i == $selected) {
                                        echo '<option value=' . $period . ' selected="selected"> ' . $last . ' ' . year($period, 2, 2) . ' </option>';
                                    } else {
                                        echo '<option value=' . $period . '> ' . $last . ' ' . year($period, 2, 2) . ' </option>';
                                    }
                                    if ($last === 'Dec') {
                                        echo'</optgroup>';
                                    }
                                }
                                $i++;
                            }
                            if ($last !== '12') {
                                echo'</optgroup>';
                            }
                        }
                        ?>

                    </select>

                    <label for="valueB" style="display: none">End Date:</label>
                    <select name="valueB" id="valueB" style="display: none">
<?php
$sql_query = "select distinct time_cpi_group from cpi_data_group order by time_cpi_group";
$date_query = mysql_query($sql_query) or die(mysql_error());

$count = mysql_num_rows($date_query);

$last = '';
if ($count > 0) {
    $selected = $count * 0.8;
    $i = 0;
    $optgroup = "2009";
    echo '<optgroup label=' . $optgroup . '>';
    while ($row = mysql_fetch_array($date_query)) {

        $period = $row['time_cpi_group'];

        if ($optgroup !== year($period, 0, 4)) {
            $optgroup = year($period, 0, 4);
            echo '<optgroup label=' . $optgroup . '>';
        } else {
            $last = month($period, 5, 2);

            if ($i == $selected) {
                echo '<option value=' . $period . ' selected="selected"> ' . $last . ' ' . year($period, 2, 2) . ' </option>';
            } else {
                echo '<option value=' . $period . '> ' . $last . ' ' . year($period, 2, 2) . ' </option>';
            }
            if ($last === 'Dec') {
                echo'</optgroup>';
            }
        }
        $i++;
    }
    if ($last !== '12') {
        echo'</optgroup>';
    }
}
?>
                    </select>
                </fieldset>
            </form>
        </div>
    </body>

</html>