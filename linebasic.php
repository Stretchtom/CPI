<!DOCTYPE html>
<meta charset="utf-8">

<?php
include_once '../CPI/phpscripts/conn.php';
//include_once '../CPI/includes/scripts/aos_session.php'; 
?> 

<?php
$color_line = '#808080';
//if (isset($_GET['submit'])) {
$id_search = $_GET['id_search'];
$name_search = $_GET['name_search'];
$table_search = $_GET['table'];
$cc = $_GET['color'];
$subcat = $_GET['subcat'];
$cpi_basket_value = $_GET['cpibasket'];
if (strlen($cc) > 5) {
    $color_line = $cc;
}

$start = $_GET['starting'];
$end = $_GET['ending'];
$byDefaut = $id_search;
$pvalue = 0;
$sql_pmonth = "SELECT `cpi_" . $table_search . "_value` FROM `cpi_data_" . $table_search . "` WHERE `id_" . $table_search . "`='" . $byDefaut . "' and `time_cpi_" . $table_search . "` = (select max(`time_cpi_" . $table_search . "`)from `cpi_data_" . $table_search . "` where `time_cpi_" . $table_search . "`<'" . $start . "')";
$sql_pmonth = mysql_query($sql_pmonth);
while ($row = mysql_fetch_array($sql_pmonth)) {
    $pvalue = $row['cpi_' . $table_search . '_value'];
}



$sql_default = "Select * from cpi_data_" . $table_search . " where id_" . $table_search . "='" . $byDefaut . "' and time_cpi_" . $table_search . ">='" . $start . "' and time_cpi_" . $table_search . "<='" . $end . "' order by time_cpi_" . $table_search;
//echo ''.$sql_default.'\n';
$sql_default = mysql_query($sql_default) or die(mysql_error());
$count_default = mysql_num_rows($sql_default);

if ($count_default > 0) {
    $cpi_value = array();
    $cpi_growth = array();
    $cpi_growth_first = array();
    $cpi_growth_year = array();
    $cpi_time = array();
    $cpi_rose = 0;
    $previous = 0;
    $first_value = 0;
    $last_value = 0;
    while ($row = mysql_fetch_array($sql_default)) {
        $period = $row['time_cpi_' . $table_search];
        $value_yearback = getyearbackValue('cpi_' . $table_search . '_value', $table_search, 'id_' . $table_search, '' . $byDefaut, $period);
        $total = $row['cpi_' . $table_search . '_value'];
        if ($previous === 0) {
            $growth = (($total / $pvalue) - 1) * 100;
            $growth_first = 0;
            $first_value = $total;
            $growth_year = (($total / $value_yearback) - 1) * 100;
        } else {
            $growth = (($total / $previous) - 1) * 100;
            $growth_first = (($total / $first_value) - 1) * 100;
            $growth_year = (($total / $value_yearback) - 1) * 100;
//            echo ''.$growth.'<br>';
        }
        $previous = $total;
        $last_value = $total;

//            echo 'period:'.$period.':val:'.$total.' <br>';

        array_push($cpi_value, floatval($total));
        array_push($cpi_growth, floatval($growth));
        $cpi_rose = floatval($growth);
        array_push($cpi_time, month($period, 5, 2) . '-' . year($period, 2, 2));
        array_push($cpi_growth_first, $growth_first);
        array_push($cpi_growth_year, $growth_year);
    }
    $growth_of_time = (($last_value / $first_value) - 1) * 100;
}

function getyearbackValue($column, $table, $id_col, $code, $time_search) {
    $sql_weight = "select  " . $column . " as number  from cpi_data_" . $table . " where " . $id_col . "='" . $code . "' and time_cpi_" . $table . ">='" . year_back($time_search) . "-01 00:00:00' and time_cpi_" . $table . "<='" . year_back($time_search) . "-30 23:00:00'";
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

function year_back($current_year) {
    return (intval(year($current_year, 0, 4)) - 1) . '-' . (year($current_year, 5, 2));
    //year($current_year,8,  strlen($current_year)-7);
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
<style>

    circle,
    path {
        cursor: pointer;
    }

    circle {
        fill: none;
        pointer-events: all;
    }
</style>  

<!--<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>-->
<!--<script src="http://code.highcharts.com/highcharts.js"></script>-->
<script src="../CPI/js/1.8.2/jquery.min.js"></script>
<script src="Highcharts-4.1.5/js/highcharts.js"></script>
<script src="../CPI/Highcharts-4.1.5/js/modules/exporting.js"></script>
<style>

    body{
        font-family: Arial,Helvetica Neue,Helvetica,sans-serif;
        font-size: 0.83em;

    }
</style>
<body>          

    <script type="text/javascript">
        $(function () {

            var array_cpi_time =<?php echo json_encode($cpi_time); ?>;
            var array_cpi_value =<?php echo json_encode($cpi_value); ?>;
            var array_cpi_growth =<?php echo json_encode($cpi_growth); ?>;
            var array_cpi_growth_first =<?php echo json_encode($cpi_growth_first); ?>;
            var array_cpi_growth_year =<?php echo json_encode($cpi_growth_year); ?>;
            var title =<?php echo json_encode($name_search); ?>;
            var time_growth =<?php echo json_encode($growth_of_time); ?>;
            var start =<?php echo json_encode($start); ?>;
            var end =<?php echo json_encode($end); ?>;
            var color_line =<?php echo json_encode($color_line); ?>;
//            alert(color_line);

            $('#container1').highcharts({
                chart: {
                    type: 'line',
                    height: 320,
//                    width:600,
                    marginRight: 30
                },
                legend: {
                    enabled: false
                },
                title: {
                    text: '',
                    x: -20 //center
                },
//                subtitle: {
//                    text: 'Source: NISR',
//                    x: -20
//                },
                xAxis: {
                    categories: array_cpi_time
                },
                yAxis: {
                    title: {
                        text: 'CPI Value'
                    },
                    plotLines: [{
                            value: 0,
                            width: 1,
                            color: "#" + color_line
                        }]
                },
                tooltip: {
                    enabled: true,
                    formatter: function (args) {
                        var this_index = this.series.data.indexOf(this.point);
//                        alert(this_index);
//                        var that_series=args.chart.series
//                        return '<b>'+title+
//                                '<br>  <br>  <br><b>'+Number(array_cpi_growth[this_index]).toFixed(2)+'%</b> in '+array_cpi_time[this_index]+
//                                '<br><b>'+time_growth.toFixed(2)+'%</b> from '+array_cpi_time[0]+' to '+array_cpi_time[array_cpi_time.length-1];

                        return '<b>' + title +
                                '<br>  <br>  <br> <b>' + Number(array_cpi_growth[this_index]).toFixed(2) + '%</b> Monthly change (' + array_cpi_time[this_index - 1] + ' to ' + array_cpi_time[this_index] + ')' +
                                '<br> <br> <br> <b>' + Number(array_cpi_growth_first[this_index]).toFixed(2) + '%</b> Total change (' + array_cpi_time[0] + ' to ' + array_cpi_time[this_index] + ')' +
                                '<br> <br> <br> <b>' + Number(array_cpi_growth_year[this_index]).toFixed(2) + '%</b> Year on year change (' + array_cpi_time[0] + ' to ' + array_cpi_time[this_index] + ')';
                        //'<br><b>'+time_growth.toFixed(2)+'%</b> from '+array_cpi_time[0]+' to '+array_cpi_time[array_cpi_time.length-1];

                    }
                },
//                    plotOptions: {
//                        line: {
//                            dataLabels: {
//                                enabled: true
//                            },
//                            enableMouseTracking: false
//                        }
//                    },
//                    legend: {
//                        layout: 'vertical',
//                        align: 'right',
//                        verticalAlign: 'middle',
//                        borderWidth: 0
//                    },
                series: [{
                        name: title,
                        color: "#" + color_line,
                        data: array_cpi_value
                    }],
                navigation: {
                    buttonOptions: {
//                        align: 'center'
                        x:7,
                        y:-10
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
    <b><?php echo '' . $name_search; ?></b> 
<?php
$isclass = substr_count($id_search, '.');
$msg_rose = "";
$msg_higher = "";

if ($cpi_rose > 0) {
    $msg_rose = "Prices rose by " . round($cpi_rose, 2) . '% ';
} else if ($cpi_rose < 0) {
    $msg_rose = "Prices fell by  " . round(($cpi_rose) * (-1), 2) . '% ';
} else {
    $msg_rose = "Prices did not change ";
}

if ($growth_of_time > 0) {
    $msg_higher = ", and were " . round($growth_of_time, 2) . "% higher than they were in ";
} else if ($growth_of_time < 0) {
    $msg_higher = ", and were " . round(($growth_of_time) * (-1), 2) . "% lower than they were in ";
}

if ($id_search === "00") {
    ?>
        <p> <?php echo '' . $name_search; ?> is the overall of  <?php echo'' . $subcat; ?> 
            subgroups which represents <?php
        echo '100% of the ' . month($start, 5, 2) . '-' . year($start, 0, 4) . ' CPI Basket.';
        echo '<p> ' . $msg_rose . 'in ' . month($end, 5, 2) . '-' . year($end, 0, 4) . $msg_higher . month($start, 5, 2) . '-' . year($start, 0, 4);
        ?> 
        <p>
        <?php
    } else if ($isclass <= 1) {
        ?>
        <p> <?php echo '' . ucfirst(strtolower($name_search)); ?> contains <?php echo'' . $subcat; ?> 
            subgroups and was <?php echo '' . $cpi_basket_value . '% of the ' . month($start, 5, 2) . '-' . year($start, 0, 4) . ' CPI Basket.'; ?> 
        <p><?php echo '' . $msg_rose . 'in ' . month($end, 5, 2) . '-' . year($end, 0, 4) . $msg_higher . month($start, 5, 2) . '-' . year($start, 0, 4); ?>
        <?php
    } else {
        $sql_sub = "SELECT name_subgroup FROM cpi_subgroup,cpi_class WHERE cpi_class.id_subgroup=cpi_subgroup.id_subgroup and `id_" . $table_search . "`='" . $byDefaut . "'";
        $sql_sub = mysql_query($sql_sub);
        $psub = '';
        while ($row = mysql_fetch_array($sql_sub)) {
            $psub = $row['name_subgroup'];
        }

        echo '<p>' . ucfirst(strtolower($name_search)) . ' is a class of ' . $psub . ' and represents ' . $cpi_basket_value . '% of the ' . month($start, 5, 2) . '-' . year($start, 0, 4) . ' CPI Basket.';
        echo '<p>' . $msg_rose . 'in ' . month($end, 5, 2) . '-' . year($end, 0, 4) . $msg_higher . month($start, 5, 2) . '-' . year($start, 0, 4);
        ;
    }
    ?>
    <div id="container1" class="col-xs-6 col-md-6">

    </div> 
