 <?php
include_once 'phpscripts/conn.php';
include_once("phpscripts/secure_text.php");
$secure = new secureText;
$msg = "";
$linebasic = "";

if (isset($_GET['name_search'])) {
$id_search = $_GET['id_search'];
$name_search = $_GET['name_search'];
$table = $_GET['table'];
$starting=$_GET['starting'].' 00:00:00';
$ending=$_GET['ending'].' 00:00:00';

$linebasic = "linebasic.php?id_search=" . $id_search . "&name_search=" . $name_search . "&table=" . $table . "&starting=".$starting."&ending=".$ending."&subcat=0";
} else {


    $sql_max_time="SELECT distinct `time_cpi_group` as time_cpi FROM `cpi_data_group` order by `time_cpi_group` desc limit 6 ";
    $sql_max_time = mysql_query($sql_max_time) or die(mysql_error());
    $max_time="";
    $min_time="";
    $i=1;
    $count_max = mysql_num_rows($sql_max_time);
    if ($count_max > 0) {
        while ($row = mysql_fetch_array($sql_max_time)) {
            if($i===1)
            {
                $max_time= $row['time_cpi'];    
            }
            if($i===6)
            {
                $min_time=$row['time_cpi'];
            }
            $i++;
        }
    } 
    
$id_search = "00";
$name_search = "General CPI";
$table = "group";
//echo ''.date('Y-m-d');
$starting=$min_time;
$ending=$max_time;

$linebasic = "linebasic.php?id_search=".$id_search."&name_search=".$name_search."&table=".$table."&starting=".$starting."&ending=".$ending."&subcat=11&cpibasket=100&color=000000";
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
fputcsv($fp, array('LEVEL', 'CODE', 'DESCRIPTION','INDEX VALUE','MONTH'));


$num_column = count($header);

$query = "(SELECT 'group' as status,cpi_group.id_group as id_search,name as name_search,cpi_group_value as index_value,time_cpi_group as period FROM cpi_group,cpi_data_group where cpi_group.id_group=cpi_data_group.id_group and time_cpi_group>='".$starting."' and time_cpi_group<='".$ending."') "
        . "union (SELECT 'subgroup' as status,cpi_subgroup.id_subgroup as id_search,name_subgroup as name_search,cpi_subgroup_value as index_value, time_cpi_subgroup as period FROM cpi_subgroup,cpi_data_subgroup where cpi_subgroup.id_subgroup=cpi_data_subgroup.id_subgroup and time_cpi_subgroup>='".$starting."' and time_cpi_subgroup<='".$ending."') "
        . "union (SELECT 'class' as status,cpi_class.id_class as id_search,name_class as name_search,cpi_class_value as index_value,time_cpi_class as period FROM cpi_class,cpi_data_class where cpi_class.id_class=cpi_data_class.id_class and time_cpi_class>='".$starting."' and time_cpi_class<='".$ending."') order by period,status";
//echo 'tes:'.$query;
$result = mysql_query($query);

while ($row = mysql_fetch_array($result)) {
    $towrite=array();
    $flevel=$row['status'];
    $fcode=$row['id_search'];
    $fname=$row['name_search'];
    $fvalue=$row['index_value'];
    $ftime=$row['period'];
    
    $ftest=''.month($ftime, 5, 2).'-'.year($ftime,0,4);
    $arr=array($flevel,$fcode,$fname,$fvalue,$ftest); 
    array_push($towrite, $arr);   
    //echo ''.$row.'::';
//    print_r($towrite);
    fputcsv($fp, $arr);
}
exit;
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
    <!--        <link rel="stylesheet" href="assets/css/main.css" type="text/css" />
            <link rel="stylesheet" href="assets/css/ktn.css" type="text/css" />-->
    <link rel="shortcut icon" href="assets/images/icon.png" /> 


    <link rel="stylesheet" href="assets/css/redmond/jquery-ui-1.7.1.custom.css" type="text/css" />
    <link rel="Stylesheet" href="assets/css/ui.slider.extras.css" type="text/css" />

    <style type="text/css">	
        .ui-slider {clear: both; top: 1em;}
        circle {
            fill: none;
            pointer-events: all;
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
                        <div class="row form-inline">
                            <img src="../CPI/images/NISR-logo.png" height="5%" width="7%"/>

                            <div class="form-group" style="padding-left:45px">

                                <!-- <form action="" method="get"> -->
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
                                <!-- </form> -->
                            </div>
                            <div class="form-group col-xs-offset-2">
                                <h1> Consumer Price Index</h1>
                                <div class="row pull-right">
                                    <a href="./?Login" class="btn btn-success">Login</a> 
                                    <!--<input type="submit" name="submit" class="" value="Download"/>-->
                                    <!--<a href="?Download" class="btn btn-primary">Download</a>-->
                                    <a href="<?php echo '?Download&starting='.$starting.'&ending='.$ending;?>" class="btn btn-primary">Download</a>
                                    
                                </div>
                            </div>
                        </div>
                    </li>
                </ul><p><span id="txtHint"></span></p>

            </div>  

        </header>
    </div>

    <div class="container">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

            <?php
            if (isset($_GET['Login'])) {
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
  <script type="text/javascript" src="assets/js/bootstrap.min.js"></script>
            

            
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
        
               $(function() {
                   var currentValueB = $('#valueB').val(); 
                   var currentValueA = $('#valueA').val();
//                   alert ("start"+currentValueA+  "stop:"+currentValueB);
                   $('select').selectToUISlider({
                       labels: 7, 
                       sliderOptions: {
                            stop: function(e,ui) {
                              currentValueB = $('#valueB').val(); 
                              currentValueA = $('#valueA').val();
                             console.log ("start"+currentValueA+  "stop:"+currentValueB);
//                             alert(currentValueA+" :::: "+currentValueB);
                             // location.href="?start="+currentValueA+"&&stop="+currentValueB;
                             var id_search = <?php echo json_encode($id_search); ?>,
                                name_search =<?php echo json_encode($name_search); ?>,
                                table = <?php echo json_encode($table); ?>;
                          
                                
                                var data="starting="+currentValueA+"&ending="+currentValueB;
                                
                                document.getElementById("piedrill").src="piedrilldown.php?"+data;
//                                document.frames('piedrill').location.reload();
//                                contentWindow.location.reload(true);
                                
                                
                                
                                var data_line="starting="+currentValueA+"&ending="+currentValueB+"&id_search="+id_search+"&name_search="+name_search+"&table="+table+"&color=000000";
                                document.getElementById("lines").src="linebasic.php?"+data_line;
//                                document.frames('lines').location.reload();
                                contentWindow.location.reload(true);
//                                
//                                alert(data);
                                
//                             window.location.href="?"+data;
//////                             $('#linebase').load('linebasic.php?'+data);
//                             OnClientClick="return false;"
//                                  $.ajax({
//                                 type:"GET",
//                                 cache:false,
//                                 url:"linebasic.php",
//                                 data:data,    // multiple data sent using ajax
//                                 success: function (html) {
//                                     $('#myDiv').load('linebasic.php?'+data);
//                                  console.log("well done:"+data);                                
//                                 }
//                               }); 
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
                <div  class="col-xs-6 col-md-6" style="padding-left:-20px;"> 

                    <iframe id="piedrill" src="<?php echo 'piedrilldown.php?starting='.$starting.'&ending='.$ending;?>" width="600" height="450" scrolling="no" frameborder="0"></iframe>

                </div>

                <div  class="col-xs-6 col-md-6">
                    <iframe id="lines" src="<?php echo $linebasic; ?>" width="540" height="450" scrolling="no" frameborder="0"></iframe>

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
                                $last = month($period, 5, 2);
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
                                $last = month($period, 5, 2);

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
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Subscription Form Demo</h4>
        
<p>This is Bootstrap Modal popup example.</p>
    </div>
      <div class="modal-body">
      
<!--End mc_embed_signup-->

   <!-- Begin MailChimp Signup Form -->
<form class="form-horizontal" role="form">
  <div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">Email</label>
    <div class="col-sm-10">
    <input type="email" value="" name="EMAIL" class="required email form-control" id="mce-EMAIL"/>
    </div>
     </div>
    <div id="mce-responses" class="clear">
        <div class="response" id="mce-error-response" style="display:none"></div>
        <div class="response" id="mce-success-response" style="display:none"></div>
    </div>    <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
    <div style="position: absolute; left: -5000px;"><input type="text" name="b_9e279011b21a23e1aed5e4868_cca4394bc9" value=""/></div>
    <div class="form-group remove-bottom">
    <div class="col-sm-offset-2 col-sm-10">
    <input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="btn btn-default"/>
    </div>
  </div>
</form>

<!--End mc_embed_signup-->
      </div>
      <div class="modal-footer remove-top">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>
<!--            modal end-->
    </div>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha/js/bootstrap.js"></script>
   <script src="//cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.0/jquery.cookie.min.js">
</script>
<script type="text/javascript">
 $(document).ready(function() {
     if ($.cookie('pop') == null) {
         $('#myModal').modal('show');
         $.cookie('pop', ’7′);
     }
 });
</script>
</body>
</html>