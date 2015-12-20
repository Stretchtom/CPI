<ol class="breadcrumb" style="background-color:#85d97c; padding:10px 10px 9px 9px;">
	<li>
		<a href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/cloudaos/cloudaos/index.php?home=Ishyiga-Live-africa"> Ishyiga Live</a>
	
	<!-- <li>
		- Logged in: <strong><?php //echo ucwords(strtolower($AOS_NAME_USER)); ?>
	</strong>
	 from <strong> -->
	 <?php 
	 //echo ucwords(strtolower($AOS_ID_CLIENT)); 
	 if ($AOS_ID_CLIENT == "ALGORITHM") {
?>
	</li>
	
	<?php  }
	else { ?>


<?php } ?>

       <li class="dropdown pull-right" style=" padding-right: 1.5cm;">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <!-- <i class="glyphicon glyphicon-user"> </i> -->
                                <i> <img src="../images/defaultimag.jpg" width="40px" height="40px" class="img-circle" alt="User Image" /></i>
                                <strong><span style=" padding-right: 1.8cm;"><?php echo ucwords(strtolower($AOS_NAME_USER)); ?>
								 <i class="caret"></i></span></strong>
                            </a>
                            <ul class="dropdown-menu pull-right">
                                <!-- User image -->
                                <li class="user-header bg-light-blue" style=" padding-left: 1.2cm;">
                                	
                                    <img src="../images/defaultimag.jpg" width="120px" height="120px" class="img-circle" alt="User Image" />
                                  </li>
                             
                                    <p style="padding-left: 0.3cm;">
                                        Title<strong>:- <?php echo ucwords(strtolower($AOS_TITLE)); ?></br></strong>
                                        <small>Member since<strong>: <?php
                                        //Date date = new Date($AOS_SIGNUP.getTime());
                                        $date=explode(' ', $AOS_SIGNUP);
                                        echo $date[0];

                                        ?></small></strong>
                                    </p>
                               
                                <!-- Menu Body -->
                                <li class="user-body">
                                    <div class="col-xs-4 text-center">
                                        <a href="#"> </a>
                                    </div>
                                    <div class="col-xs-4 text-center">
                                        <a href="#"></a>
                                    </div>
                                    <div class="col-xs-4 text-center">
                                        <a href="#"> </a>
                                    </div>
                                </li>
                                <!-- Menu Footer-->
                                <li class="user-footer">
                                    <div class="pull-left" style=" padding-left: 0.3cm;">
                                        <a href="#" class="btn btn-default btn-flat">Profile</a>
                                    </div>
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    
                                    <div class="pull-right" style=" padding-right: 0.3cm;">
                                        <a href="../includes/scripts/aos_logout.php"  class="btn btn-default btn-flat">Sign out</a>
                                    </div>
                                </li>
                            </ul>
                             </li>

                       

</ol>