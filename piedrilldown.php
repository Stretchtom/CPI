<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <?php
    include_once '../CPI/phpscripts/conn.php';
//include_once '../CPI/includes/scripts/aos_session.php'; 
    ?>
    <?php
    $start = $_GET['starting'];
    $end = $_GET['ending'];
//    echo ''.$start.' :ending: '.$end;
//    $startE = "2015-09-01";
//    $endE = "2015-09-30";
    $cpi_classification = array();
    $cpi_group = array();
    $cpi_subgroup = array();
    $cpi_classes = array();
    $cpi_pie_value = array();
    $cpi_time_start = month($start, 5, 2) . '-' . year($start, 0, 4);
    $cpi_time_end = month($end, 5, 2) . '-' . year($end, 0, 4);
    $count_group = 0;
    $count_subgroup = 0;
    $count_classes = 0;

//    $sql_weight = "select weight_value from cpi_weight where start_time<='" . $start_time . "' and end_time>='" . $start_time . "' and CODE='" . $code . "'";
//        $sql_weight = mysql_query($sql_weight) or die(mysql_error());
//        $count_weight = mysql_num_rows($sql_weight);
//        if ($count_weight > 0) 
//        {
//            while ($row2 = mysql_fetch_array($sql_weight)) {
//                return $row2['weight_value'];
//            }
//        }
//        return 0;

    $sql_group = "Select cpi_group.id_group,name,weight_value,color_group from cpi_group,cpi_weight where cpi_group.id_group=cpi_weight.CODE"
            . " and start_time<='" . $start . "' and end_time>='" . $start . "' ";
//echo ''.$sql_group.'<br>';
    $sql_group = mysql_query($sql_group) or die(mysql_error());
    $count_default = mysql_num_rows($sql_group);


    if ($count_default > 0) {
        while ($row0 = mysql_fetch_array($sql_group)) {
            $id_group = $row0['id_group'];
            $group_name = $row0['name'];
            $group_value = $row0['weight_value'];
            $color_group = $row0['color_group'];
            $nb = getCount('id_group', 'cpi_subgroup', 'id_group', $id_group);
            $cpi_pie_value = getDifference('group', '' . $id_group, '' . $start, '' . $end);

//            print_r($cpi_pie_value);

            $group = array($id_group, $group_name, 'group', $group_value, $color_group, $nb, $cpi_pie_value[0], $cpi_pie_value[1], $count_group);
            array_push($cpi_group, $group);
            array_push($cpi_classification, $group);
            $count_group++;
            $count_subgroup = 0;

            $sql_subgroup = "Select cpi_subgroup.id_subgroup,name_subgroup,weight_value,color_subgroup from cpi_subgroup,cpi_weight where cpi_subgroup.id_group='" . $id_group . "'"
                    . " and cpi_subgroup.id_subgroup=cpi_weight.CODE and start_time<='" . $start . "' and end_time>='" . $start . "' ";
            //if($id_group==='12')
//            echo "".$sql_subgroup."<br>";
            $sql_subgroup = mysql_query($sql_subgroup) or die(mysql_error());
            $count_sub = mysql_num_rows($sql_subgroup);

            if ($count_sub > 0) {
                while ($row = mysql_fetch_array($sql_subgroup)) {
                    $id_subgroup = $row['id_subgroup'];
                    $subgroup_name = $row['name_subgroup'];
                    $subgroup_value = $row['weight_value'];
                    $color_subgroup = $row['color_subgroup'];
                    $nb = getCount('id_subgroup', 'cpi_class', 'id_subgroup', $id_subgroup);
                    $cpi_pie_value = getDifference('subgroup', '' . $id_subgroup, '' . $start, '' . $end);
                    $subgroup = array($id_subgroup, $subgroup_name, 'subgroup', $subgroup_value, $color_subgroup, $nb, $cpi_pie_value[0], $cpi_pie_value[1], $count_subgroup);
                    array_push($cpi_subgroup, $subgroup);
                    array_push($cpi_classification, $subgroup);
                    $count_subgroup++;
                    $count_classes = 0;

                    $sql_class = "select cpi_class.id_class,name_class,weight_value,color_class from cpi_class,cpi_weight where id_subgroup='" . $id_subgroup . "'"
                            . " and cpi_class.id_class=cpi_weight.CODE and start_time<='" . $start . "' and end_time>='" . $start . "' ";
                    //if($id_group==='12')
//                echo ''.$sql_class.'<br>';
                    $sql_class = mysql_query($sql_class) or die(mysql_error());
                    $count_class = mysql_num_rows($sql_class);
                    if ($count_class > 0) {
                        while ($row1 = mysql_fetch_array($sql_class)) {
                            $id_class = $row1['id_class'];
                            $name_class = $row1['name_class'];
                            $class_value = $row1['weight_value'];
                            $color_class = $row1['color_class'];
                            $cpi_pie_value = getDifference('class', '' . $id_class, '' . $start, '' . $end);
                            $class = array($id_class, $name_class, 'class', $class_value, $color_class, 0, $cpi_pie_value[0], $cpi_pie_value[1], $count_classes);
                            array_push($cpi_classification, $class);
                            $count_classes++;
                        }
                    }
                }
            }
        }
    }
    ?>
    <?php

    function getDifference($table_Search, $id_search, $start_time, $end_time) {
        $previous = 0;
        $first_value = 0;
        $growth = 0;
        $last_value = 0;
        $toReturn = array();
        $sql_default = "Select * from cpi_data_" . $table_Search . " where id_" . $table_Search . "='" . $id_search . "' and time_cpi_" . $table_Search . ">='" . $start_time . "' and time_cpi_" . $table_Search . "<='" . $end_time . "' order by time_cpi_" . $table_Search;
//        echo '' . $sql_default;
        $sql_default = mysql_query($sql_default) or die(mysql_error());
        $count_default = mysql_num_rows($sql_default);
        if ($count_default > 0) {
            while ($row_diff = mysql_fetch_array($sql_default)) {
                //$period = $row_diff['time_cpi_' . $table_search];
                $total = $row_diff['cpi_' . $table_Search . '_value'];
                if ($previous === 0) {
                    $first_value = $total;
                } else {
                    $growth = (($total / $previous) - 1) * 100;
                }
                $previous = $total;
                $last_value = $total;
            }
        }
        $growth_of_time = (($last_value / $first_value) - 1) * 100;
        array_push($toReturn, $growth, $growth_of_time);
        return $toReturn;
    }

    function getCount($column, $table, $id_col, $code) {
        $sql_weight = "select count(" . $column . ") as number from " . $table . " where " . $id_col . "='" . $code . "'";
//        echo ''.$sql_weight;
        $sql_weight = mysql_query($sql_weight) or die(mysql_error());
        $count_weight = mysql_num_rows($sql_weight);
        if ($count_weight > 0) {
            while ($row2 = mysql_fetch_array($sql_weight)) {
                return $row2['number'];
            }
        }
        return 0;
    }

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
    ?>
</head>

<!--<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>-->
<script src="js/1.8.2/jquery.min.js"></script>

<script src="Highcharts-4.1.5/js/highcharts.js"></script>
<script src="Highcharts-4.1.5/js/modules/drilldown.js"></script>
<script src="Highcharts-4.1.5/js/modules/data.js"></script>
<script src="../CPI/Highcharts-4.1.5/js/modules/exporting.js"></script>
<script type="text/javascript" src="js/jquery_scripts.js"></script>
<!--<script src="../js/1.8.2/jquery.min.js"></script>-->
<style>

    body{
        font-family: Arial,Helvetica Neue,Helvetica,sans-serif;

    }
</style>

<body> 
    <script type="text/javascript">

        $(function () {

            var title = new Array(<?php echo json_encode($end); ?>);
            var cpi_group =<?php echo json_encode($cpi_group); ?>;
            var cpi_subgroup = new Array(<?php echo json_encode($cpi_subgroup); ?>);
            var cpi_data =<?php echo json_encode($cpi_classification); ?>;

            var start_time =<?php echo json_encode($start); ?>;
            var end_time =<?php echo json_encode($end); ?>;
            var start_time_world =<?php echo json_encode($cpi_time_start); ?>;
            var end_time_world =<?php echo json_encode($cpi_time_end); ?>;

            var i = 0;
            var j = 0;
            var space = " ";
            var serie = [];
            var group = [];
            var subgroup = [];
            var classes = [];
            var gid = "";
            var gname = "";
            var sid = "";
            var sname = "";
            var level = "";
            var subCat = "";
            var couleur = "";
            var posit = 0;
            var growth_m = "";
            var growth_time = "";
            var iniData = [];
            var iniSub = [];

            for (i = 1; i < cpi_data.length; i++)
            {
                if (cpi_data[i][2] === "group")
                {
                    if (iniSub.length > 0)
                    {
                        subgroup.push(
                                {
                                    id: gid,
                                    name: gname,
                                    data: iniSub,
                                    lev: level,
                                    growth_m: growth_m,
                                    growth_time: growth_time,
//                                    color:couleur,
                                    posit: posit,
                                    subcat: subCat
                                });
                        iniSub = [];
                    }

                    gid = cpi_data[i][0];
                    gname = cpi_data[i][1];
                    level = cpi_data[i][2];
                    couleur = "#" + cpi_data[i][4];
                    growth_m = parseFloat(cpi_data[i][6]);
                    growth_time = parseFloat(cpi_data[i][7]);
                    posit = parseInt(cpi_data[i][8]);
                    subCat = cpi_data[i][5];

                    group.push(
                            {
                                id: cpi_data[i][0],
                                name: capitalize(cpi_data[i][1]),
                                lev: cpi_data[i][2],
                                y: parseFloat(cpi_data[i][3]),
                                growth_m: parseFloat(cpi_data[i][6]),
                                growth_time: parseFloat(cpi_data[i][7]),
                                color: "#" + cpi_data[i][4],
                                subcat: cpi_data[i][5],
                                posit: parseInt(cpi_data[i][8]),
                                drilldown: "" + gid
                            });

                }
                else if (cpi_data[i][2] === "subgroup")
                {
                    sid = cpi_data[i][0];
                    sname = cpi_data[i][1];
                    level = cpi_data[i][2];
                    couleur = "#" + cpi_data[i][4];
                    growth_m = parseFloat(cpi_data[i][6]);
                    growth_time = parseFloat(cpi_data[i][7]);
                    posit = parseInt(cpi_data[i][8]);
//                    alert(couleur);
                    iniSub.push(
                            {
                                id: cpi_data[i][0],
                                name: cpi_data[i][1],
                                lev: cpi_data[i][2],
                                y: parseFloat(cpi_data[i][3]),
                                growth_m: parseFloat(cpi_data[i][6]),
                                growth_time: parseFloat(cpi_data[i][7]),
                                color: "#" + cpi_data[i][4],
                                posit: parseInt(cpi_data[i][8]),
                                subcat: cpi_data[i][5],
                                drilldown: "" + sid
                            });
                    iniData = [];
                }
                else if (cpi_data[i][2] === "class")
                {
                    for (j = i; j < cpi_data.length; j++)
                    {
                        if (cpi_data[j][2] === "class")
                        {
                            iniData.push(
                                    {
                                        id: cpi_data[j][0],
                                        name: cpi_data[j][1],
                                        lev: cpi_data[j][2],
                                        y: parseFloat(cpi_data[j][3]),
                                        growth_m: parseFloat(cpi_data[j][6]),
                                        growth_time: parseFloat(cpi_data[j][7]),
                                        color: "#" + cpi_data[j][4],
                                        posit: parseInt(cpi_data[j][8]),
                                        subcat: cpi_data[j][5]
                                    });
                        }
                        else
                        {
                            subgroup.push(
                                    {
                                        id: sid,
                                        name: sname,
                                        data: iniData,
                                        lev: level,
                                        growth_m: growth_m,
                                        growth_time: growth_time,
//                                        color:couleur,
                                        posit: posit,
                                        subcat: subCat
                                    });
                            iniData = [];
                            i = j - 1;
                            break;
                        }
                    }
                }
            }

            if (iniSub.length > 0)
            {
                subgroup.push(
                        {
                            id: gid,
                            name: gname,
                            data: iniSub,
                            lev: level,
                            growth_m: growth_m,
                            growth_time: growth_time,
//                            color:couleur,
                            posit: posit,
                            subcat: subCat
                        });
                iniSub = [];
            }

            var beforeTitle = "General CPI";
            var currentTitle = "General CPI";
            var afterbefTitle = "General CPI";


            function setTranslation(p, slice) {
                p.sliced = slice;
                if (p.sliced) {
                    p.graphic.animate({
                        translateX: 0,
                        translateY: 0
                    });
                } else {
                    p.graphic.animate({
                        translateX: 0,
                        translateY: 0
                    });
                }
            }

            function setSliceLength(graphic, v)
            {
//                for(var i=0;i<6;i++)
//                {
//                    var graphic = chart.series[0].data[i].graphic;

//                    if(graphic!==null)
//                    {
//                                    alert(i);
                setTimeout(function () {
                    var prevR = graphic.r;
                    graphic.attr({
                        r: prevR + v
                    });
                }, 1001);
//                    }
//                    else
//                    {
//                        break;}
//                }

            }

            function capitalize(s)
            {
                if (s.toString().toLowerCase().indexOf("general cpi") !== -1)
                {
                    return s + " (" + cpi_data[0][7].toFixed(2) + ")% ";
                }
                s = s.toLowerCase();
                return s[0].toUpperCase() + s.slice(1);
            }

            Highcharts.setOptions({
                lang: {
                    drillUpText: 'Back to {series.lev}'
                }
            });
            // Create the chart
            $('#container').highcharts({
                chart: {
                    type: 'pie',
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false,
                    marginRight: 110,
                    events: {
                        load: function () {
//                            alert(this.series[0].data.length);
//                            var y = this.series[0].data.length;
//                            for (var i = 0; i < y; i++)
//                            {
//                                var graphic = this.series[0].data[i].graphic;
//                                if (graphic !== null)
//                                {
//                                    setSliceLength(graphic, this.series[0].data[i].growth_time);
//                                } else
//                                    break;
//                            }

                        },
                        drilldown: function (e) {
                            afterbefTitle = beforeTitle;
                            beforeTitle = currentTitle;
                            currentTitle = e.point.name + " (" + e.point.growth_time.toFixed(2) + ")%";
                            this.setTitle({text: capitalize(currentTitle)});

//                            var y=this.series[0].data[e.point.posit].drilldown.series.data[0];
//                            alert(y);
//                            for(var i=0;i<y;i++)
//                            {
////                                 var graphic = this.series[0].data[4].drilldown[i].graphic;
////                                 if(graphic!==null)
////                                    setSliceLength(graphic,this.series[0].data[4].growth_time);
////                                 else
////                                     break;
//                            }
                        },
                        drillup: function (e) {
                            currentTitle = beforeTitle;
                            beforeTitle = afterbefTitle;
                            afterbefTitle = "General CPI";
                            this.setTitle({text: capitalize(currentTitle)});
                        }
                    }
                },
                legend: {
                    align: 'right',
                    verticalAlign: 'top',
                    x: -130,
                    y: 72,
                    layout: 'vertical'
                },
                title: {
                    text: capitalize(currentTitle),
                    align: 'center',
//                    y: 20,
                    x: -50,
                    style: {
                        color: 'black',
                        fontWeight: 'bold',
                        fontSize: '18px',
                        stroke: 'none'
                    }
                },
                subtitle: {
                    text: ' Year on year change of ' + start_time_world + ' and ' + end_time_world + '',
                    align: 'center',
                    x: -20,
                    style: {
                        color: 'black',
                        fontSize: '12px',
                        stroke: 'none'
                    }
                },
                plotOptions: {
                    pie: {
                        size: '110%',
                        cursor: 'pointer',
                        borderWidth: 0,
                        shadow: false,
//                        dataLabels: {
//                        enabled: false
//                    },
//                    showInLegend: true,
                        point: {
                            events: {
                                mouseOut: function () {
//                                    this.defaultR = this.graphic.r;
//                                    this.graphic.attr({
//                                        r: this.defaultR+20
//                                    });
//                                    setTranslation(this,false);
                                },
                                mouseOver: function (e) {
//                                    this.graphic.attr({
//                                    r: this.defaultR});
                                    var color_line = this.color;
//                                 alert ("linebasic.php?starting=" + start_time + "&ending=" + end_time + "&id_search=" + this.id + "&name_search=" + this.name + "&table=" + this.lev + "&color=" + color_line.substring(1, color_line.length) + "&subcat=" + this.subcat + "&cpibasket=" + this.y);

                                    parent.document.getElementById("lines").src = "linebasic.php?starting=" + start_time + "&ending=" + end_time + "&id_search=" + this.id + "&name_search=" + this.name + "&table=" + this.lev + "&color=" + color_line.substring(1, color_line.length) + "&subcat=" + this.subcat + "&cpibasket=" + this.y;

                                }
                            }
                        }
                    },
                    series: {
                        dataLabels: {
                            enabled: false,
                            format: '{point.name}: {point.y:.1f}'
                        },
                        states:{
                            hover:{
                                enabled:false
                            }
                        }
                    }
                },
                tooltip: {
                    headerFormat: '<span></span><br>',
//                    formatter: function(args){
//                        var this_index=this.series.data.indexOf(this.point);
////                        alert(this_index);
////                        var that_series=args.chart.series
//                        return '<b>'+title+
//                                '<br>  <br>  <br><b>'+Number(array_cpi_growth[this_index]).toFixed(2)+'%</b> in '+array_cpi_time[this_index]+
//                                '<br><b>'+time_growth.toFixed(2)+'%</b> from '+array_cpi_time[0]+' to '+array_cpi_time[array_cpi_time.length-1];
//                                //'<br><br> of ';
//                    },
                    pointFormat: '<span style="color:{black}"></span><b>{point.name}</b> <br> . <br> <b>{point.growth_m:.2f}%</b> in ' + end_time_world + '<br> <b>{point.growth_time:.2f}%</b> between ' + start_time_world + ' and ' + end_time_world + ' <br> <b>{point.y:.2f}%</b> of ' + start_time_world + ' CPI Basket <br> . <br> <i> Click to Zoom <br></i>'

                },
                series: [{
                        name: "CPI Visualization",
                        colorByPoint: true,
                        lev: 'group',
                        data: group
                    }],
                drilldown:
                        {
                            drillUpButton: {
                                relativeTo: 'spacingBox',
                                position: {
                                    y: 358,
                                    x: -362
                                },
                                theme: {
                                    fill: 'white',
                                    'stroke-width': 1,
                                    stroke: 'silver',
                                    r: 0,
                                    states: {
                                        hover: {
                                            fill: '#bada55'
                                        },
                                        select: {
                                            stroke: '#039',
                                            fill: '#bada55'
                                        }
                                    }
                                }

                            },
                            series: subgroup
                        },
                navigation: {
                    buttonOptions: {
//                        align: 'center'
                        x: -30,
                        y: 20
                    }
                },
                exporting: {
                    buttons: {
                        contextButton: {
                            menuItems: [{
                                    textKey: 'downloadPNG',
                                    text: 'Export to PNG',
                                    onclick: function () {
                                        this.exportChart();
                                    }
                                }, {
                                    textKey: 'downloadJPEG',
                                    text: 'Export to JPEG',
                                    onclick: function () {
                                        this.exportChart({
                                            type: 'image/jpeg'
                                        });
                                    }
                                }, {
                                    textKey: 'downloadPDF',
                                    text: 'Export to PDF',
                                    onclick: function () {
                                        this.exportChart({
                                            type: 'pdf'
                                        });
                                    }
                                }
                            ]
                        }
                    }
                }
            });
        });

    </script>

    <div id="container"></div>

</body>

