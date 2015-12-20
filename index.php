<?php
include_once 'phpscripts/conn.php';
include_once("phpscripts/secure_text.php");
$secure = new secureText;
$msg = "";
$linebasic = "";
$starting="";
$ending="";


if (isset($_GET['name_search'])) {
$id_search = $_GET['id_search'];
$name_search = $_GET['name_search'];
$table = $_GET['table'];
$starting = $_GET['starting'] . ' 00:00:00';
$ending = $_GET['ending'] . ' 00:00:00'; 

$linebasic = "linebasic.php?id_search=" . $id_search . "&name_search=" . $name_search . "&table=" . $table . "&starting=" . $starting . "&ending=" . $ending . "&subcat=0";
} else {
$sql_max_time = "SELECT distinct `time_cpi_group` as time_cpi FROM `cpi_data_group` order by `time_cpi_group` desc limit 13 ";
$sql_max_time = mysql_query($sql_max_time) or die(mysql_error());
$max_time = "";
$min_time = "";
$i = 1;
$count_max = mysql_num_rows($sql_max_time);
if ($count_max > 0) {
    while ($row = mysql_fetch_array($sql_max_time)) {
        if ($i === 1) {
            $max_time = $row['time_cpi'];
        }
        if ($i === 13) {
            $min_time = $row['time_cpi'];
        }
        $i++;
    }
}

$id_search = "00";
$name_search = "General CPI";
$table = "group";
//echo ''.date('Y-m-d');
$starting = $min_time;
$ending = $max_time;
$pvalue=$max_time;
$linebasic = "linebasic.php?id_search=" . $id_search . "&name_search=" . $name_search . "&table=" . $table . "&starting=" . $starting . "&ending=" . $ending . "&subcat=11&cpibasket=100&color=000000";
}

if (isset($_POST['login'])) {
$username = ($secure->cleanText($_POST['username']));
$password = ($secure->cleanText($_POST['password']));

if (($username != "") && ($password != "")) {
    $password = md5($password);
    include_once("phpscripts/conn.php");

    $sql = "SELECT * FROM cpi_users WHERE username='$username'  AND password='$password'";

    $sql = mysql_query($sql) or die(mysql_error());

    $count = mysql_num_rows($sql);
    if ($count > 0) {
        while ($row = mysql_fetch_array($sql)) {
            if (($row['username'] != '') || ($row['password'] != ' ')) {
                session_start();
                $_SESSION['username'] = $row['username'];
                $_SESSION['fname'] = $row['fname'];
                $_SESSION['lname'] = $row['lname'];

                // $ipaddress = getenv('REMOTE_ADDR');
                if ((!$_SESSION['username']) || (!$_SESSION['username']) == 0) {
                    $msg = '<font style="color:red;">ERROR: Invalid Session</font>';
                    $msg = '<font style="color:red;">ERROR: Invalid Session</font>';
                }

                header("location:admin/");
            } else {
                $msg = '<div class="alert alert-danger">
                <button $type="button" $class="close" data-$dismiss="alert" aria-$hidden="true">
                    ×</button>
                <span $class="glyphicon glyphicon-record"></span> <strong>ERROR!</strong>
                <hr $class="message-inner-separator">
                <p>There is no user associated with
             your login credentials.</p>
            </div>';
            }
        }
    } else {
        $msg = '<div class="alert alert-danger">
                <button $type="button" $class="close" data-$dismiss="alert" aria-$hidden="true">
                    ×</button>
                <span $class="glyphicon glyphicon-record"></span> <strong>ERROR!</strong>
                <hr $class="message-inner-separator">
                <p>Invalid username or password</p>
            </div>';
    }
} else {
    $msg = '<div class="alert alert-danger">
                <button $type="button" $class="close" data-$dismiss="alert" aria-$hidden="true">
                    ×</button>
                <span $class="glyphicon glyphicon-record"></span> <strong>ERROR!</strong>
                <hr $class="message-inner-separator">
                <p>Fill all the fields</p>
            </div>';
}
}

if (isset($_GET['Download'])) {
$starting = $_GET['starting'];
$ending = $_GET['ending']; 

$filename = "Download.csv";
$fp = fopen('php://output', 'w');


header('Content-type: application/csv');
header('Content-Disposition: attachment; filename=' . $filename);
fputcsv($fp, array('LEVEL', 'CODE', 'DESCRIPTION', 'INDEX VALUE', 'MONTH'));


$num_column = count($header);

$query = "(SELECT 'group' as status,cpi_group.id_group as id_search,name as name_search,cpi_group_value as index_value,time_cpi_group as period FROM cpi_group,cpi_data_group where cpi_group.id_group=cpi_data_group.id_group and time_cpi_group>='" . $starting . "' and time_cpi_group<='" . $ending . "') "
        . "union (SELECT 'subgroup' as status,cpi_subgroup.id_subgroup as id_search,name_subgroup as name_search,cpi_subgroup_value as index_value, time_cpi_subgroup as period FROM cpi_subgroup,cpi_data_subgroup where cpi_subgroup.id_subgroup=cpi_data_subgroup.id_subgroup and time_cpi_subgroup>='" . $starting . "' and time_cpi_subgroup<='" . $ending . "') "
        . "union (SELECT 'class' as status,cpi_class.id_class as id_search,name_class as name_search,cpi_class_value as index_value,time_cpi_class as period FROM cpi_class,cpi_data_class where cpi_class.id_class=cpi_data_class.id_class and time_cpi_class>='" . $starting . "' and time_cpi_class<='" . $ending . "') order by period,case status when 'group' then 1 when 'subgroup' then 2 when 'class' then 3 end,id_search ";
//echo 'tes:'.$query;
$result = mysql_query($query);

while ($row = mysql_fetch_array($result)) {
    $towrite = array();
    $flevel = $row['status'];
    $fcode = $row['id_search'];
    $fname = $row['name_search'];
    $fvalue = $row['index_value'];
    $ftime = $row['period'];

    $ftest = '' . month($ftime, 5, 2,0) . '-' . year($ftime, 0, 4);
    $arr = array($flevel, $fcode, $fname, $fvalue, $ftest);
    array_push($towrite, $arr);
//    echo ''.$arr.'::';
//    print_r($towrite);
    fputcsv($fp, $arr);
}
exit;
}

function getrwandaup()
{ 
    $table_Search='group';
    $sql_pmonth="SELECT `cpi_".$table_Search."_value`,`time_cpi_".$table_Search."` FROM `cpi_data_".$table_Search."` WHERE `id_".$table_Search."`='00' and `time_cpi_".$table_Search."` = (select max(`time_cpi_".$table_Search."`)from `cpi_data_".$table_Search."` )";
//    echo ''.$sql_pmonth;
    $sql_pmonth=  mysql_query($sql_pmonth);
    while($row=  mysql_fetch_array($sql_pmonth))
    {
        $pvalue=$row['cpi_'.$table_Search.'_value'];
        $ptime=$row['time_cpi_'.$table_Search];
        $yearbackValue=getyearbackValue("cpi_".$table_Search."_value",$table_Search,"id_".$table_Search,"00",$ptime);    
        $recentvalue=((floatval($pvalue)/floatval($yearbackValue))-1)*100;
        return $recentvalue;
    }
    return 0; 
}

function getyearbackValue($column, $table, $id_col, $code,$time_search) {
        $sql_weight = "select  ". $column ." as number  from cpi_data_" . $table . " where " . $id_col . "='" . $code . "' and time_cpi_".$table.">='".year_back($time_search)."-01 00:00:00' and time_cpi_".$table."<='".year_back($time_search)."-30 23:00:00'";
//        echo '<br>'.$sql_weight;
        $sql_weight = mysql_query($sql_weight) or die(mysql_error());
        $count_weight = mysql_num_rows($sql_weight);
        if ($count_weight > 0) {
            while ($row2 = mysql_fetch_array($sql_weight)) {
                return $row2['number'];
            }
        }
        return 0;
    }
    
    function year_back($current_year)
{
    return (intval(year($current_year,0,4))-1).'-'.(year($current_year,5,2));
            //year($current_year,8,  strlen($current_year)-7);
}
    

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="color:Background" content="#f4fbfa" />
    <title>NISR CPI Visualization</title>

    <link rel="stylesheet" href="assets/css/bootstrap.min.css" type="text/css" />
    <!--    <link rel="stylesheet" href="assets/css/main.css" type="text/css" />
            <link rel="stylesheet" href="assets/css/ktn.css" type="text/css" />-->
            <!--<a rel="shortcut icon" src="../CPI/images/favicon.ico" href="../CPI/index.php" />-->
    <link rel="shortcut icon" href="../CPI/images/favicon.ico"/>
    <link rel="stylesheet" href="assets/css/redmond/jquery-ui-1.7.1.custom.css" type="text/css" />
    <link rel="Stylesheet" href="assets/css/ui.slider.extras.css" type="text/css" />

    <style type="text/css">	
        .ui-slider {clear: both; top: 1em;}
        circle {
            fill: none;
            pointer-events: all;
        }
        p{
            font-family: Arial,Helvetica Neue,Helvetica,sans-serif;
            /*color: blue;*/
        }
    </style>

    <meta name="text:Posts Background Alpha" content="0.9"/>
    <script type="text/javascript">
        function showHint(str)
        {
            if (str.length == 0)
            {
                document.getElementById("txtHint").innerHTML = "";
                return;
            }
            if (window.XMLHttpRequest)
            {// code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp = new XMLHttpRequest();
            }

            else

            {// code for IE6, IE5
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }

            xmlhttp.onreadystatechange = function ()
            {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
                {
                    document.getElementById("txtHint").innerHTML = xmlhttp.responseText;
                }
            }
            xmlhttp.open("GET", "phpscripts/search.php?word=" + str, true);
            xmlhttp.send();
        }
    </script> 
    <style>
        .input_container p {
            /*width: 25em;*//*
            border-left: 1px solid #dddddd;
            border-right: 1px solid #dddddd;*/
            position: absolute;
            z-index: 9;
            /*background: #f3f3f3;*/
            list-style: none;
        }

        .input_container p li:hover {
            background: #337ab7;
        }
        #view_search {
            display: none;
        }
        .tete{
            margin-top: -24px;
        }
    </style>
</head> 
<body>
    <div class="container"> 
        <header class="navbar-inverse ">
            <div class="col-xs-12 col-sm-12 col-md-12  col-lg-12 input_container" id="menubar">

                <ul class="list-group tete">
                    <li class="list-group-item">
                        <div class="row form-inline" style="margin-top:12px;">
                            <a href="../CPI/index.php"><img src="../CPI/images/NISR-logo.png"  height="5%" width="7%"/></a>
                            <!--<a src="../CPI/images/NISR-logo.png" href="../CPI/index.php" />-->
                            <!--    <div class="form-group" style="padding-left:45px">
                                   <input type="text" class="form-control" placeholder="Search CPI Group&hellip;" name="x" style="width:400px; height:33px; color:#444444; font-size:16px;" 
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

                               </div> -->
                            <div class="form-group col-xs-offset-2">
                                <h1> Consumer Price Index</h1>

                            </div>
                            <div class="row pull-right" style="margin-top:30px;margin-right:30px">
                                <!--<a href="./?Login" class="btn btn-success">Login</a>--> 
                                <!--<input type="submit" name="submit" class="" value="Download"/>-->
                                <!--<a href="?Download" class="btn btn-primary">Download</a>-->
                                <a id="download" href="<?php echo '?Download&starting=' . $starting . '&ending=' . $ending; ?>" class="btn btn-primary">Download data</a>
                                <a  href="#"  data-toggle="modal" data-target="#myModal" class="btn btn-success">About</a> 

                            </div>
                        </div>
                    </li>
                </ul>
                <!-- <p><span id="txtHint"></span></p> -->
                <div id="rwanda">
                <?php 
                if (!isset($_GET['Download'])){
//                    echo 'danie';
                $rvalue=getrwandaup();
                $msg_r="";
                if($rvalue>=0)
                {
                    $msg_r="Rwanda's CPI rose ".round(getrwandaup(),2)."% ";
                }
                else
                {
                    $msg_r="Rwanda's CPI fell ".round((getrwandaup())*(-1),2)."% ";
                }
                ?>
                <h3 id="hid"> <strong class="col-xs-offset-3" style="margin-top:-30px;"> <?php echo ''.$msg_r.' year on year in '.month($pvalue, 5,2,1).'-'.year($pvalue,0,4); ?></strong></h3>
                <?php } ?>
                </div>
            </div>  

        </header>
    </div>

    <div class="container">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

<?php
if (isset($_GET['Login'])) {
    
    ?><script>
        document.getElementById('hid').setAttribute('style', 'display:none;');
        contentWindow.location.reload(true);
        </script>
    
    <?php
    
echo '
                    ' . $msg . '
                 <div class="container" style="padding-top:-22cm">
                        <div class="row vertical-offset-100">
                            <div class="col-md-4 col-md-offset-3">
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">Please sign in</h3>
                                    </div>
                                    <div class="panel-body" style="background-color: #ccc" >
                                        <form  action="./?Login" method="post" accept-charset="UTF-8" role="form">
                                        <fieldset>
                                            <div class="form-group">
                                                <input class="form-control" placeholder="Username" name="username" type="text">
                                            </div>
                                            <div class="form-group">
                                                <input class="form-control" placeholder="Password" name="password" type="password" value="">
                                            </div>
                                            <input class="btn btn-lg btn-primary btn-lg col-md-offset-4" type="submit" name="login" value="Login">
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

                <script type="text/javascript" src="js/jquery-1.3.2.min.js"></script>

<!--            <script>
var $i = jQuery.noConflict(true);
    </script>-->
                <script type="text/javascript" src="js/jquery-ui-1.7.1.custom.min.js"></script>
                <script type="text/javascript" src="js/selectToUISlider.jQuery.js"></script>

                <script>
    //    $('#valueB').selectToUISlider({
    //  sliderOptions: {
    //    stop: function(e,ui) {
    //      var currentValue = $('#valueB').val();
    //      alert (currentValue);
    //    }
    //  }
    //});
                </script>

                <script type="text/javascript">

                    $(function () {
                        var currentValueB = $('#valueB').val();
                        var currentValueA = $('#valueA').val();
    //                   alert ("start"+currentValueA+  "stop:"+currentValueB);
                        $('select').selectToUISlider({
                            labels: 7,
                            sliderOptions: {
                                stop: function (e, ui) {
                                    currentValueB = $('#valueB').val();
                                    currentValueA = $('#valueA').val();  
                                    var id_search = <?php echo json_encode($id_search); ?>,
                                        name_search =<?php echo json_encode($name_search); ?>,
                                        table = <?php echo json_encode($table); ?>;
//                                    document.getElementById('legend').setAttribute('style', 'display:none;');
//                                    document.getElementById('hid').setAttribute('style', 'display:none;');

                                    var data = "starting=" + currentValueA + "&ending=" + currentValueB;

                                    document.getElementById("piedrill").src = "piedrilldown.php?" + data;
                                    document.getElementById("download").href = "?Download&starting=" + currentValueA + "&ending=" + currentValueB;
//                                    document.getElementById("hid").src="my test";
                                    var data_line = "starting=" + currentValueA + "&ending=" + currentValueB + "&id_search=" + id_search + "&name_search=" + name_search + "&table=" + table + "&color=000000";
                                    document.getElementById("lines").src = "linebasic.php?" + data_line;
    //                                document.frames('lines').location.reload();
                                    contentWindow.location.reload(true);
                                }
                            }
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
                <div  class="col-xs-6 col-md-5" style="padding-left:-20px;"> 

                    <iframe id="piedrill" src="<?php echo 'piedrilldown.php?starting=' . $starting . '&ending=' . $ending; ?>" width="500" height="435" scrolling="no" frameborder="0"></iframe>
<!--                    <p style="margin-top:-30px;font-family:arial;" id="legend">
                        purely for theme-switching demo... ignore this unless you're using a theme switcher
                        quick function for tooltip color match</p>-->
                </div>

                <div  class="col-xs-6 col-md-7">
                    <iframe id="lines" src="<?php echo $linebasic; ?>" width="680" height="435" scrolling="no" frameborder="0"></iframe>

                </div>
            </div>
<?php
}
?>
    </div> 

    <div class="container">
        <form action="" method="get">
            <fieldset>
                <label for="valueA" style="display: none">Start Date:</label>

<?php

function year($cpi_time, $start, $nb) {
return substr($cpi_time, $start, $nb);
}

function month($cpi_time, $start, $nb,$yose) {
$inWord = '';
$ukwezi = substr($cpi_time, $start, $nb);
switch ($ukwezi) {
    case '01':
        if($yose===0)
        {$inWord = 'Jan';}
        else
        {$inWord='January';}
        break;
    case '02':
        if($yose===0)
        {$inWord = 'Feb';}
        else
        {$inWord='February';} 
        break;
    case '03':
        if($yose===0)
        {$inWord = 'Mar';}
        else
        {$inWord='March';} 
        break;
    case '04':
        if($yose===0)
        {$inWord = 'Apr';}
        else
        {$inWord='April';} 
        break;
    case '05':
        $inWord = 'May';
        break;
    case '06':
        if($yose===0)
        {$inWord = 'Jun';}
        else
        {$inWord='June';} 
        break;
    case '07':
        if($yose===0)
        {$inWord = 'Jul';}
        else
        {$inWord='July';}
        break;
    case '08':
        if($yose===0)
        {$inWord = 'Aug';}
        else
        {$inWord='August';}
        break;
    case '09':
        if($yose===0)
        {$inWord = 'Sep';}
        else
        {$inWord='September';} 
        break;
    case '10':
        if($yose===0)
        {$inWord = 'Oct';}
        else
        {$inWord='October';} 
        break;
    case '11':
        if($yose===0)
        {$inWord = 'Nov';}
        else
        {$inWord='November';} 
        break;
    case '12':
        if($yose===0)
        {$inWord = 'Dec';}
        else
        {$inWord='December';}
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
//                        $selected = $count - 6;
//                        echo 'select:' . $selected;
//                        $i = 0;
                    $optgroup = "2009";
                    echo '<optgroup label=' . $optgroup . '>';
                    while ($row = mysql_fetch_array($date_query)) {

                        $period = $row['time_cpi_group'];

                        if ($optgroup !== year($period, 0, 4)) {
                            $optgroup = year($period, 0, 4);
                            echo '<optgroup label=' . $optgroup . '>';
                        } else {
                            $last = month($period, 5, 2,0);
                            if ($period === $starting) {
                                echo '<option value=' . $period . ' selected="selected"> ' . $last . ' ' . year($period, 2, 2) . ' </option>';
                            } else {
                                echo '<option value=' . $period . '> ' . $last . ' ' . year($period, 2, 2) . ' </option>';
                            }
                            if ($last === 'Dec') {
                                echo'</optgroup>';
                            }
                        }
//                            $i++;
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
//                        $selected = $count - 1;
//                        $i = 0;
$optgroup = "2009";
echo '<optgroup label=' . $optgroup . '>';
while ($row = mysql_fetch_array($date_query)) {

    $period = $row['time_cpi_group'];

    if ($optgroup !== year($period, 0, 4)) {
        $optgroup = year($period, 0, 4);
        echo '<optgroup label=' . $optgroup . '>';
    } else {
        $last = month($period, 5, 2,0);

        if ($period === $ending) {
            echo '<option value=' . $period . ' selected="selected"> ' . $last . ' ' . year($period, 2, 2) . ' </option>';
        } else {
            echo '<option value=' . $period . '> ' . $last . ' ' . year($period, 2, 2) . ' </option>';
        }
        if ($last === 'Dec') {
            echo'</optgroup>';
        }
    }
//                            $i++;
}
if ($last !== '12') {
    echo'</optgroup>';
}
}
?>
                </select>
            </fieldset>
        </form>
        <!-- ==================end==================== -->
        <!-- line modal -->
        <!-- Modal -->

        <script  src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>   

        <script type="text/javascript" src="assets/js/bootstrap.min.js"></script> 
        <script>
            var $i = jQuery.noConflict(true);
        </script>
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content" style="background-color: #e5f7fd;">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title" id="myModalLabel"><strong>NISR Consumers Price Index visualisation tool</strong></h4>

<!-- <p>This is Bootstrap Modal popup example.</p> -->
                    </div>
                    <div class="modal-body">

                        <!-- body start  -->
                        <p>Analyse the components of the visualisation tool to see price changes <BR>in the goods on the Rwandan CPI basket.<BR>

                            There are 3 important components to this visualization tool.</p>

                        <p><B>The Pie Chart:</b> The pie chart on the left starts off by representing<br> the weights of the groups of CPI in the given time frame. <br>The larger the slice the larger the weight of the group.
                            <br>In order to explore any slice, click to view its components.</p>

                        <p> <b>The Line Graph:</b> The line graph on the right represents <br>the percentage change of the selected component of the Pie Chart.<br>When you hover the mouse at any point of the line,<br>
                            it will show both the monthly percentage change <br>and the percentage change of the extremes of <br>the selected time frame.</p> 


                        <p> <b>The Slide Bar:</b> The slide bar at the bottom of the page <br>sets the time frame. <br>The scale is on a monthly basis.</p>


                        <p>Data can be downloaded in CSV format from <br>the button at top right of webpage.

                        <p>Data is updated on a monthly basis by NISR.

                            <!-- end body -->
                    </div>
                    <div class="modal-footer remove-top">
                        <p>&copy;2015 All Rights Reserved</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- modal end -->
    </div>
</body>
</html>