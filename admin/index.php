<?php
include_once '../phpscripts/conn.php';
include_once '../includes/scripts/session.php';
$msg = "";

if (isset($_POST['Uploading'])) {
    $path = $_FILES['file_upload']['name'];
    $file_tmp = $_FILES['file_upload']['tmp_name'];
    $ext = explode(".", $path);
    $fileExt = end($ext);
    // $dat1=date('Y-m-d');
    $dat1 = time();
    $cuntdate = strftime('%Y-%m-%d %H:%M:%S', time());
    if ($fileExt === "csv") {

        $path = 'CVS_' . $dat1 . '.' . $fileExt;
        $move_file = move_uploaded_file($file_tmp, "upload/$path");
        mysql_query("INSERT INTO `cpi_file_track`( `file_name`, `upload_date`) VALUES ('$path','$cuntdate')");

        $msg = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">Close</button><span class="glyphicon glyphicon-thumbs-up"></span> <strong>Well done!</strong><hr class="message-inner-separator"><p>Uploading successfuly.</p></div>';
    } else {
        $msg = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">Close</button><span class="glyphicon glyphicon-thumbs-down"></span> <strong>oops not csv file!</strong><hr class="message-inner-separator"><p>Uploading not successfuly.</p></div>';
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="color:Background" content="#f4fbfa" />
        <title>NISR CPI Visualization</title>

        <link rel="stylesheet" href="../assets/css/bootstrap.min.css" type="text/css" />
        <!--        <link rel="stylesheet" href="assets/css/main.css" type="text/css" />
                <link rel="stylesheet" href="assets/css/ktn.css" type="text/css" />-->
        <link rel="shortcut icon" href="../images/favicon.ico" /> 


        <meta name="text:Posts Background Alpha" content="0.9"/>
        <style>

            .blue {
                color: blue;
            }
            h1.page-header {
                margin-top: -5px;
            }

            .sidebar {
                padding-left: 0;
            }

            .main-container { 
                background: #FFF;
                padding-top: 15px;
                margin-top: -20px;
            }

            .footer {
                width: 100%;
            }  



            /* layout.css Style */
            .custab{
                border: 1px solid #ccc;
                padding: 5px;
                margin: 5% 0;
                box-shadow: 3px 3px 2px #ccc;
                transition: 0.5s;
            }
            .custab:hover{
                box-shadow: 3px 3px 0px transparent;
                transition: 0.5s;
            }
        </style>
    </head> 
    <body>
        <div class="container"> 
            <header class="navbar-inverse tete">
                <div class="col-xs-12 col-sm-12 col-md-12  col-lg-12 input_container" id="menubar">

                    <ul class="list-group">
                        <li class="list-group-item">
                            <div class="row form-inline">
                                <!--<img src="../../CPI/images/NISR-logo.png" height="5%" width="7%"/>-->
                                <a href="../../CPI/index.php"><img src="../../CPI/images/NISR-logo.png"  height="5%" width="7%"/></a>
                            
                                <div class="form-group col-xs-offset-2">
                                    <h1> Consumer Price Index</h1>
                                </div>
                                <div class="form-group col-xs-offset-1">

                                    <span class="btn btn-primary">You Login as  <?php echo $sess_fname . ' ' . $sess_lname; ?></span> 
                                    <!--<a href="../includes/scripts/logout.php" class="btn btn-success">Logout</a>--> 
                                    <a href="../index.php" class="btn btn-success">Logout</a> 


                                </div>                                
                            </div>
                        </li>
                    </ul>
                </div>  

            </header>
            <div class="container-fluid main-container">
                <div class="col-md-4 sidebar">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                Current File</h3>
                        </div>
                        <ul class="list-group">
                            <?php
                            $sql = mysql_query("SELECT * FROM cpi_file_track");
                            while ($row = mysql_fetch_array($sql)) {
                                echo '
                		<a href="?id=' . $row['id_file'] . '&&fielname=' . $row['file_name'] . '" class="list-group-item"><span class="glyphicon  glyphicon-file blue"></span> ' . $row['file_name'] . ' <label class="label label-primary">' . $row['status'] . '<label></a>
                		';
                            }
                            ?>

                        </ul>
                    </div>
                </div> <form action="" method="post" enctype="multipart/form-data">

                    <div class="col-md-8 content">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                Upload CSV
                            </div> 

                            <div class="panel-body">
                                <?php echo $msg; ?>
                                <div class="form-inline">
                                    <input type="file"  name="file_upload" class="form-control"/>							
                                    <input type="submit" name="Uploading" value="Upload" class="btn btn-labeled btn-primary btn-label glyphicon glyphicon-upload"  />
                                </div> 	
                                <div class="form-inline" style="margin-top:12px">
                                    <div class="form-group form-control input-sx">
                                        <label >START</label>
                                        <select name="year" tabindex="1" >
                                            <?php
                                            $current = date("Y", time());
                                            for ($i = $current; $i >= 2010; $i = $i - 1) {
                                                echo '<option>' . $i . '</option>';
                                            }
                                            ?>
                                        </select>
                                        <select name="month"  tabindex="1" >
                                            <?php
                                            $mons = array(1 => "Jan", 2 => "Feb", 3 => "Mar", 4 => "Apr", 5 => "May", 6 => "Jun", 7 => "Jul", 8 => "Aug", 9 => "Sep", 10 => "Oct", 11 => "Nov", 12 => "Dec");

                                            $month = date('M ');
                                            echo '<option>' . $month . '</option>';
                                            for ($i = 1; $i <= count($mons); $i++) {
                                                echo '<option>' . $mons[$i] . '</option>';
                                            }
                                            ?>
                                        </select>


                                    </div>
                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                    <?php 
                                    $id=$_GET['id'];
                                    $quer=mysql_query("SELECT status FROM cpi_file_track WHERE id_file='$id'");
                                    $sta_row=mysql_fetch_array($quer);
                                    $status=$sta_row['status'];
                                    if ($status!='Saved') {
                                       echo '<input type="submit" name="Save" value="Save" class="btn btn-labeled btn-primary btn-label glyphicon glyphicon-upload"  /> 
                               ';
                                    } else {

                                    }
                                    
                                    ?>
                                </div>
                                <?php
                                if (isset($_GET['fielname'])) {
                                    $file_name = $_GET['fielname'];
                                    ?>
                                    <div class="container">
                                        <div class="row col-md-6 custyle">
                                            <table class="table table-striped custab">
                                                <thead>
                                                    <tr>
                                                        <th>CODE</th>
                                                        <th>NAME CLASSIFICATION</th>
                                                        <th>VALUE</th>
                                                        <th>LEVEL</th>
                                                    </tr>
                                                </thead>
                                                <?php
                                                $path = 'upload/' . $file_name;
                                                $CSVfp = fopen($path, 'r');
                                                if ($CSVfp !== FALSE) {
                                                    while (!feof($CSVfp)) {
                                                        $data = fgetcsv($CSVfp, 100000, ",");
                                                        if ($data[0] != '' AND $data[0] != 'CODE') {
                                                            echo
                                                            '
			    <tr>
				                <td>' . $data[0] . '</td>
				                <td>' . $data[1] . '</td>
				                <td>' . $data[2] . '</td>
				                <td>' . $data[3] . '</a></td>
				            </tr>
			    ';
                                                        }
                                                    }
                                                }
                                                fclose($CSVfp);
                                                ?>


                                            </table>
                                        </div>
                                    </div>
                                    <?php
                                    if (isset($_POST[Save])) {
                                        $id = $_GET['id'];

                                        function month($ukwezi) {
                                            $inWord = '';
                                            switch ($ukwezi) {
                                                case 'Jan':
                                                    $inWord = '01';
                                                    break;
                                                case 'Feb':
                                                    $inWord = '02';
                                                    break;
                                                case 'Mar':
                                                    $inWord = '03';
                                                    break;
                                                case 'Apr':
                                                    $inWord = '04';
                                                    break;
                                                case 'May':
                                                    $inWord = '05';
                                                    break;
                                                case 'Jun':
                                                    $inWord = '06';
                                                    break;
                                                case 'Jul':
                                                    $inWord = '07';
                                                    break;
                                                case 'Aug':
                                                    $inWord = '08';
                                                    break;
                                                case 'Sep':
                                                    $inWord = '09';
                                                    break;
                                                case 'Oct':
                                                    $inWord = '10';
                                                    break;
                                                case 'Nov':
                                                    $inWord = '11';
                                                    break;
                                                case 'Dec':
                                                    $inWord = '12';
                                                    break;
                                            }
                                            return $inWord;
                                        }

                                        $ukwezi = $_POST[month];
                                        $umuba = month($ukwezi);
                                        echo $time = $_POST[year] . '-' . $umuba . '-01' . ' 00:00:00';
                                        $CSVfp = fopen($path, 'r');
                                        if ($CSVfp !== FALSE) {
                                            while (!feof($CSVfp)) {
                                                $data = fgetcsv($CSVfp, 100000, ",");
                                                // $row = str_getcsv($path, "\n");

                                                $tablefro = $data[3];
                                                if ($data[0] != '' AND $data[0] != 'CODE') {
                                                    $sd = "INSERT INTO cpi_data_" . $tablefro . " (`id_" . $tablefro . "`, `weight`, `cpi_" . $tablefro . "_value`, `time_cpi_" . $tablefro . "`, `index_cpi_" . $tablefro . "`, `area`) VALUES ('" . $data[0] . "', '0', '" . $data[2] . "', '$time', '0', 'URBAN')";
                                                    $inte = mysql_query($sd);
                                                }
                                            }
                                            mysql_query("UPDATE  `nisr_cpi`.`cpi_file_track` SET  `status` =  'Saved' WHERE  `cpi_file_track`.`id_file` ='$id'");
                                        }
                                    }
                                }
                                fclose($CSVfp);
                                ?>
                            </div>

                        </div>
                    </div></form>
                <footer class="pull-left footer">
                    <p class="col-md-12 pull-center">
                    <hr class="divider">
                    Copyright &COPY; 2015 
                    </p>
                </footer>
            </div>
        </div>
    </body>
</html>