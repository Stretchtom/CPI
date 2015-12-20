<?php
//error_reporting(E_ALL);
//ini_set('display_errors','1');
include_once("../phpscripts/check_sessions.php");
$today = date("Y-m-d");
$day = date("d")-7;
$week_before = date("Y-m-$day");
if($autorisation == 'PHARMACY'){
	$pharmaview = '
<table style="font-size:11px; border:none;">
  <tr>
    <td style="width:60px; border:none;">&nbsp;</td>
    <td style="width:60px; border:none;"><a href="dpb.php">DATABASE PHARMA BETA</a></td>
    <td style="width:60px; border:none;"><a href="order.php">MAKE AN ORDER</a></td>
    <td style="width:60px; border:none;"><a href="#">PROPOSE A PRICE AND QUANTITY</a></td>
  </tr>
  <tr>
    <td style="width:60px; border:none;">Products: </td>
    <td style="width:60px; border:none;"><a href="created_products.php">VIEW</a></td>
    <td style="width:60px; border:none;">CREATE</td>
    <td style="width:60px; border:none;">UPDATE</td>
  </tr>
  <tr>
    <td style="width:60px; border:none;">Account: </td>
    <td style="width:60px; border:none;"><a href="orders.php">CMD</a></td>
    <td style="width:60px; border:none;"><a href="logs.php">LOGS</a></td>
    <td style="width:60px; border:none;"><a href="sales.php?pharma='.$societe.'&period='.$week_before.';'.$today.'">SALES</a></td>
  </tr>
</table>
<table style="border:none;">
  <tr>
    <td style="width:120px; border:none;">HEALTH SOCIAL NETWORK: </td>
    <td style="width:60px; border:none;"><a href="ehn_home.php?ehn=1">MY PROFILE</a></td> 
  </tr>
</table> ';
} else {
	if($special_field == 'PHARMACY'){
		$special_view = '<a href="pr_grview.php">VIEW</a>';
	} else {
		$special_view = 'VIEW';
	}
	$pharmaview = '
	<table style="font-size:11px; border:none;">
	  <tr>
		<td style="width:60px; border:none;">&nbsp;</td>
		<td style="width:60px; border:none;"><a href="dpb.php">DATABASE PHARMA BETA</a></td>
		<td style="width:60px; border:none;">MAKE AN ORDER</td>
		<td style="width:60px; border:none;">PROPOSE A PRICE AND QUANTITY</td>
	  </tr>
	  <tr>
		<td style="width:60px; border:none;">Products: </td>
		<td style="width:60px; border:none;">VIEW</td>
		<td style="width:60px; border:none;">CREATE</td>
		<td style="width:60px; border:none;">UPDATE</td>
	  </tr>
	  <tr>
		<td style="width:60px; border:none;">Account: </td>
		<td style="width:60px; border:none;">CMD</td>
		<td style="width:60px; border:none;">LOGS</td>
		<td style="width:60px; border:none;">SALES</td>
	  </tr>
	</table>';
}
if($autorisation == 'ASSURANCE' || $autorisation == 'PHARMACY' ){
	$assview = '
	<table style="font-size:11px; border:none;">
	  <tr>
		<td style="width:60px; border:none;">Prices:</td>
		<td style="width:60px; border:none;"><a href="assview.php">VIEW</a></td>
		<td style="width:60px; border:none;"><a href="assup.php">UPDATE</a></td>
		<td style="width:60px; border:none;"><a href="upld.php">UPLOAD</a></td>
	  </tr>
	  <tr>
		<td style="width:60px; border:none;">Affiliates List:</td>
		<td style="width:60px; border:none;"><a href="dwnldaff.php">DOWNLOAD</a></td>
		<td style="width:60px; border:none;"><a href="upldaff.php">UPLOAD</a></td>
		<td style="width:60px; border:none;"><a href="validiteaff.php">VALIDITE</a></td>
	  </tr>
	  <tr>
		<td style="width:60px; border:none;">Monthly Bill:</td>
		<td style="width:60px; border:none;"><a href="#">SEND</a></td>
		<td style="width:60px; border:none;"><a href="#">DOWNLOAD</a></td>
		<td style="width:60px; border:none;">&nbsp;</td>
	  </tr>
	</table>';
} else {
	if($special_field == 'ASSURANCE'){
		$special_view = '<a href="as_grview.php">VIEW</a>	
		<table style="border:none;">
  			<tr>
				<td style="width:120px; border:none;">HEALTH SOCIAL NETWORK: </td>
				<td style="width:60px; border:none;"><a href="ehn_home.php?ehn=1">MY PROFILE</a></td> 
 			</tr>
		</table>';
	} else {
		$special_view = 'VIEW';
	}
	$assview = '
	<table style="font-size:11px; border:none;">
	  <tr>
		<td style="width:60px; border:none;">Prices:</td>
		<td style="width:60px; border:none;">'.$special_view.'</td>
		<td style="width:60px; border:none;">UPDATE</td>
		<td style="width:60px; border:none;">UPLOAD</td>
	  </tr>
	  <tr>
		<td style="width:60px; border:none;">Affiliates List:</td>
		<td style="width:60px; border:none;">DOWNLOAD</td>
		<td style="width:60px; border:none;">UPLOAD</td>
		<td style="width:60px; border:none;">&nbsp;</td>
	  </tr>
	  <tr>
		<td style="width:60px; border:none;">Monthly Bill:</td>
		<td style="width:60px; border:none;">SEND</td>
		<td style="width:60px; border:none;">DOWNLOAD</td>
		<td style="width:60px; border:none;">&nbsp;</td>
	  </tr>
	</table>';
}
if($autorisation == 'ALGORITHME'){
	$algoview = '
<table style="font-size:11px; border:none;">
  <tr>
    <td style="width:60px; border:none;">DPB:</td>
    <td style="width:60px; border:none;"><a href="transform.php">TRANSFORM</a></td>
    <td style="width:60px; border:none;"><a href="prlist.php">UPDATE NP</a></td>
    <td style="width:60px; border:none;"><a href="update_db.php">UPDATE DB</a></td>
  </tr>
  <tr>
    <td style="width:60px; border:none;">Users:</td>
    <td style="width:60px; border:none;"><a href="userslist.php">VIEW</a></td>
    <td style="width:60px; border:none;"><a href="update_user.php">UPDATE</a></td>
    <td style="width:60px; border:none;"><a href="create_user.php">CREATE</a></td>
  </tr>
  <tr>
    <td style="width:60px; border:none;">Companies:</td>
    <td style="width:60px; border:none;"><a href="complist.php">VIEW</a></td>
    <td style="width:60px; border:none;"><a href="update_comp.php">UPDATE</a></td>
    <td style="width:60px; border:none;"><a href="create_company.php">CREATE</a></td>
  </tr>
  <tr>
    <td style="width:120px; border:none;"><a href="upldpb.php">UPDATE DPB BY UPLOAD</a></td>
    <td style="width:120px; border:none;">CONTACT US</td>
	
  </tr>
</table>
<table style="border:none;">
  <tr>
    <td style="width:120px; border:none;">HEALTH SOCIAL NETWORK: </td>
    <td style="width:60px; border:none;"><a href="ehn_home.php?ehn=1">MY PROFILE</a></td> 
  </tr>
</table>';
} else {
	$algoview = '
<table style="font-size:11px; border:none;">
  <tr>
    <td style="width:60px; border:none;">DPB:</td>
    <td style="width:60px; border:none;">TRANSFORM</td>
    <td style="width:60px; border:none;">UPDATE NP</td>
    <td style="width:60px; border:none;">UPDATE DB</td>
  </tr>
  <tr>
    <td style="width:60px; border:none;">Users:</td>
    <td style="width:60px; border:none;">VIEW</td>
    <td style="width:60px; border:none;">UPDATE</td>
    <td style="width:60px; border:none;">CREATE</td>
  </tr>
  <tr>
    <td style="width:60px; border:none;">Companies:</td>
    <td style="width:60px; border:none;">VIEW</td>
    <td style="width:60px; border:none;">UPDATE</td>
    <td style="width:60px; border:none;">CREATE</td>
  </tr>
</table>
<table style="font-size:11px; border:none;">
  <tr>
    <td style="width:120px; border:none;">UPDATE DPB BY UPLOAD</td>
    <td style="width:120px; border:none;"><a href="contact.php">CONTACT US</a></td>
  </tr>
</table>';
}
if($autorisation == 'DEPOT'){
	$depview = '
	<table style="font-size:11px; border:none;">
	  <tr>
		<td style="width:40px; border:none;">Stock:</td>
		<td style="width:30px; border:none;"><a href="depview.php">VIEW</a></td>
		<td style="width:40px; border:none;"><a href="depup.php">UPDATE</a></td>
		<td style="width:40px; border:none;"><a href="dep_create.php">INSERT</a></td>
		<td style="width:40px; border:none;"><a href="upldprice.php?soc='.$societe.'&user='.$username.'">UPLOAD</a></td>
	  </tr>
	</table>
	<table style="font-size:11px; border:none;">
	  <tr>
		<td style="width:40px; border:none;"><a href="#">ORDERS</a></td>
		<td style="width:200px; border:none;"><a href="#">SALES STATISTICS</a></td>
	  </tr>
	</table>
	<table style="border:none;">
  <tr>
    <td style="width:120px; border:none;">HEALTH SOCIAL NETWORK: </td>
    <td style="width:60px; border:none;"><a href="ehn_home.php?ehn=1">MY PROFILE</a></td> 
  </tr>
</table> 
<table style="border:none;">
  <tr>
    <td style="width:120px; border:none;">HEALTH SOCIAL NETWORK: </td>
    <td style="width:60px; border:none;"><a href="ehn_home.php?ehn=1">MY PROFILE</a></td> 
  </tr>
</table>';
} else {
	$depview = '
	<table style="font-size:11px; border:none;">
	  <tr>
		<td style="width:40px; border:none;">Stock:</td>
		<td style="width:30px; border:none;">VIEW</td>
		<td style="width:40px; border:none;">UPDATE</td>
		<td style="width:40px; border:none;">UPLOAD</td>
		<td style="width:40px; border:none;">UPLOAD</td>
	  </tr>
	</table>
	<table style="font-size:11px; border:none;">
	  <tr>
		<td style="width:40px; border:none;">&nbsp;</td>
		<td style="width:30px; border:none;">ORDERS</td>
		<td style="width:160px; border:none;">SALES STATISTICS</td>
	  </tr>
	</table>';
}
if($autorisation == 'CLINIC'){
	$clinicview = '
	<table style="border:none;">
		<tr>
		<td style="border:none; width:150px;">ICD10</td>
		<td style="border:none; width:30px;">PRESCRIPTION</td>
		</tr>
		<tr>
		<td style="border:none; width:150px;">SCHEME</td>
		<td style="border:none; width:30px;">DESEASE</td>
		</tr>
    </table>
	<table style="border:none;">
  <tr>
    <td style="width:120px; border:none;">HEALTH SOCIAL NETWORK: </td>
    <td style="width:60px; border:none;"><a href="ehn_home.php?ehn=1">MY PROFILE</a></td> 
  </tr>
</table> ';
} else {
	$clinicview = '
	<table style="border:none;">
		<tr>
		<td style="border:none; width:150px;">ICD10</td>
		<td style="border:none; width:30px;">PRESCRIPTION</td>
		</tr>
		<tr>
		<td style="border:none; width:150px;">SCHEME</td>
		<td style="border:none; width:30px;">DESEASE</td>
		</tr>
    </table>';
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="EN" lang="EN" dir="ltr">
<head profile="http://gmpg.org/xfn/11">
<title>Ishyiga Cloud - Home Page</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta http-equiv="imagetoolbar" content="no" />
<link rel="stylesheet" href="../styles/layout.css" type="text/css" />
<link rel="shortcut icon" href="../images/icon.png" />
</head>
<body id="top">
<?php include_once("../top.php"); ?>
<?php include_once("../header.php"); ?>
<div class="wrapper col2" style="margin-top:10px;">
  <div id="container" class="clear">
    <div id="portfolio">
      <div class="portfoliocontainer clear">
        <div style="background-color:#85d97c; border-bottom:1px solid #4ac23f; padding:10px 10px 3px 10px;">
          <h4 style="font-size:11px;">You are at: <a href="http://<?php echo $_SERVER['HTTP_HOST']; ?>" style="background:none; color:#2e3192;"> Home</a> &raquo; Cloud <?php echo strtoupper($location); ?> - Logged in: <strong><?php echo ucwords(strtolower($n_u_n)); ?> </strong> from <strong><?php echo ucwords(strtolower($s_n_c)); ?></strong> &nbsp;-&nbsp; <a href="logout.php" style="background:none; color:#2e3192;">Log out</a></h4>
        </div>
        <br />
        <?php include_once '../left_side.php'; ?>
        <div class="fl_right">
          <table style="text-align: left; width: 100%; " border="1" cellpadding="2" cellspacing="2">
            <tbody>
              <tr>
                <td style="background-color:#6695c5; color:#d3e1ef; border:1px solid #6695c5;"><font style="font-size:14px;">PHARMACY ZONE By <a href="#" style="background:none; color:#2e3192;">Ishyiga Pharma</a></font></td>
                <td style="background-color:#6695c5; color:#d3e1ef; border:1px solid #6695c5;"><font style="font-size:14px;">CLINIC ZONE By <a href="#" style="background:none; color:#2e3192;">Ishyiga Clinic</a></font></td>
              </tr>
              <tr>
                <td style="border-top:none; border-left:none; border-bottom:none;"><?php echo $pharmaview; ?></td>
              	<td style="border:none;"><?php echo $clinicview; ?></td>  
              </tr>
              <tr >
                <td style="background-color:#6695c5; color:#d3e1ef; border:1px solid #6695c5;"><font style="font-size:14px;">PHARMACEUTICAL DEPOTS By <a href="#" style="background:none; color:#2e3192;">Ishyiga Import</a></font></td>
                <td style="background-color:#6695c5; color:#d3e1ef; border:1px solid #6695c5;"><font style="font-size:14px;">INSURANCE ZONE By <a href="#" style="background:none; color:#2e3192;">Ishyiga Symphony</a></font></td>
              </tr>
              <tr> 
                <!--            <td style="border-top:none; border-left:none; border-bottom:none;">
            	
            </td>-->
                
                <td style="border:none;"><table style="border:none;">
                    <tr>
                      <td style="border:none; width:80px;">Supplyers:</td>
                      <td style="border:none; width:160px;"><select name="cmd" size="1">
                          <?php 
							$query="SELECT * FROM `societe` WHERE 
							`FIELD_SOCIETE` = 'DEPOT' ORDER BY `ID_SOCIETE` " ;  
							$SqlStr = mysql_query($query) or die(mysql_error()); 
							while ($rang=mysql_fetch_array($SqlStr)){
								$SIGLE= $rang['ID_SOCIETE'];
								echo "<option value='$SIGLE'>$SIGLE</option>";
							}
                        ?>
                        </select></td>
                    </tr>
                  </table>
                  <?php echo $depview; ?></td>
                <td style="border:none;"><table style="border:none;">
                    <tr>
                      <td style="border:none; width:10px;">Insurances:</td>
                      <td style="border:none; width:230px;"><select name="selectAss" size="1">
                          <?php
                        $query="SELECT * FROM `societe` 
						WHERE FIELD_SOCIETE='ASSURANCE' ORDER BY ID_SOCIETE " ;  
                        $SqlStr = mysql_query($query) or die(mysql_error()); 
                        while ($rang=mysql_fetch_array($SqlStr))
                        {  
                        $SIGLE= $rang['ID_SOCIETE'];   
                        echo "<option value='$SIGLE'>$SIGLE</option>";   
                        } 
                    ?>
                        </select></td>
                    </tr>
                  </table>
                  <?php echo $assview; ?></td>
              </tr>
              <tr>
                <td style="background-color:#6695c5; color:#d3e1ef; border:1px solid #6695c5;"><font style="font-size:14px;">MINISTRY OF HEALTH</font></td>
                <td style="background-color:#6695c5; color:#d3e1ef; border:1px solid #6695c5;"><font style="font-size:14px;">ALGORITHM INC&nbsp;</font></td>
                <!--<td style="background-color:#6695c5; color:#d3e1ef; border:1px solid #6695c5;">
            <font style="font-size:14px;">ISHYIGA LINES By 
            <a href="#" style="background:none; color:#2e3192;">Algorithm Inc</a></font></td>--> 
              </tr>
              <tr>
                <td style="border-top:none; border-left:none; border-bottom:none;"><table style="border:none;">
                    <tr>
                      <td style="border:none; width:120px;">Publish Public Notice</td>
                      <td style="border:none; width:120px;">View Public Notice</td>
                    </tr>
                    <tr>
                      <td style="border:none; width:120px;">Publish</td>
                      <td style="border:none; width:60px;"><select name="cmd" size="1">
                          <?php
                            $query="SELECT * FROM `societe`  ORDER BY ID_SOCIETE " ;  
                            $SqlStr = mysql_query($query) or die(mysql_error()); 
                            while ($rang=mysql_fetch_array($SqlStr))
                            {  
                                $SIGLE= $rang['ID_SOCIETE'];   
                                echo "<option value='$SIGLE'>$SIGLE</option>";   
                            }
                        ?>
                        </select></td>
                      <td style="border:none; width:60px;">Notice  F</td>
                    </tr>
                    <tr>
                      <td style="border:none; width:120px;">View</td>
                      <td style="border:none; width:60px;"><select name="cmd" size="1">
                          <?php
                        $query="SELECT * FROM `societe`  ORDER BY ID_SOCIETE " ;  
                        $SqlStr = mysql_query($query) or die("D! Impossible d'effectuer la slection sur la table..."); 
                        while ($rang=mysql_fetch_array($SqlStr))
                        {  
                        $SIGLE= $rang['ID_SOCIETE'];   
                        echo "<option value='$SIGLE'>$SIGLE</option>";   
                        } 
                    ?>
                        </select></td>
                      <td style="border:none; width:60px;">Notice  F</td>
                    </tr>
                  </table>
                <td style="border-top:none; border-left:none; border-bottom:none;"><?php echo $algoview; ?></td>
                <!--<td style="border:none;">
            	<table style="border:none;">
                <tr>
                <td style="border:none; width:150px;">ISHYIGA PHARMA 2.0</td>
                <td style="border:none; width:30px;">UPDATES</td>
                <td style="border:none; width:30px;">GUIDE</td>
                <td style="border:none; width:30px;">DEMO</td>
                </tr>
                <tr>
                <td style="border:none; width:150px;">ISHYIGA IMPORT 1.6</td>
                <td style="border:none; width:30px;">UPDATES</td>
                <td style="border:none; width:30px;">GUIDE</td>
                <td style="border:none; width:30px;">DEMO</td>
                </tr>
                <tr>
                <td style="border:none; width:150px;">ISHYIGA COMPTA 1.0</td>
                <td style="border:none; width:30px;">UPDATES</td>
                <td style="border:none; width:30px;">GUIDE</td>
                <td style="border:none; width:30px;">DEMO</td>
                </tr>
                <tr>
                <td style="border:none; width:150px;">ISHYIGA MOBILE 1.5</td>
                <td style="border:none; width:30px;">UPDATES</td>
                <td style="border:none; width:30px;">GUIDE</td>
                <td style="border:none; width:30px;">DEMO</td>
                </tr>
                <tr>
                <td style="border:none; width:150px;">ISHYIGA SYMPHONY 1.7</td>
                <td style="border:none; width:30px;">UPDATES</td>
                <td style="border:none; width:30px;">GUIDE</td>
                <td style="border:none; width:30px;">DEMO</td>
                </tr>
                </table>
            </td> --> 
              </tr>
            </tbody>
          </table>
          <br>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include_once '../footer.php'; ?>
</body>
</html>