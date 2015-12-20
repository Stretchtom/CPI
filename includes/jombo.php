<?php 

    include_once 'scripts/aos_session.php';
    include_once '../phpscripts/conn.php';

?> 
 <div class="container">
                        <div class="row">
                              <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        <h2 class="panel-title">
                                            <span class="glyphicon glyphicon-bookmark"></span> Ishyiga Live Africa:</h2>
                                    </div>
                                    <div class="panel-body"  style="padding-left:40px;" >
                                                                             
                                          <!-- <div class="row"> -->
                                            <label>Social:</label>
                                            <div class="row">                                              
                                             <a href="#" class="btn btn-primary btn-lg " role="button"><span class="glyphicon glyphicon-signal"></span> <br/>Friends</a>    
                                             <a href="#" class="btn btn-primary btn-lg usecol" role="button"><span class="glyphicon glyphicon-comment"></span> <br/>Request</a>                                             
                                             <a  href="../cloudaos/aos_users.php?view_users=<?php echo $AOS_ID_CLIENT  ?>" class="btn btn-primary btn-lg acc" role="button"><span class="glyphicon glyphicon-user "></span> <br/>Users</a>                                        
                                             <a href="sub_accountclient.php" class="btn btn-primary btn-lg sdc" role="button"><span class="glyphicon glyphicon-book"></span> <br/>Group</a>
                                             </div>
                                             <label>Management:</label>                                      
                                           <div class="row">
                                           <a href="../cloudaos/sales_view.php" class="btn btn-danger btn-lg " role="button"><span class="glyphicon glyphicon-credit-card"></span> <br/>Sales</a>
                                           <a href="refund_view.php" class="btn btn-primary btn-lg grou" role="button"><span class="glyphicon glyphicon-cloud-download"></span> <br/>Refund</a>                                            
                                           <a  href="#" class="btn btn-primary btn-lg itcolor" role="button"><span class="glyphicon glyphicon-usd "></span> <br/>Purchase</a>                                              
                                           <a href="accounting_view.php" class="btn btn-primary btn-lg frecolor" role="button"><span class="glyphicon glyphicon-briefcase"></span> <br/>Accounting</a>                                                                                                                                                                                                                          
                                             
                                            </div>

                                         
                                   
                                       <label>Tools:</label>                                      
                                          <div class="row">
                                           <!-- <div class="col-xs-10 col-md-10"> -->
                                            <a href="../cloudaos/home.php" class="btn btn-primary btn-lg sal" role="button"><span class="glyphicon glyphicon-envelope"></span> <br/>Report</a> 
                                            <a href="availability.php" class="btn btn-primary btn-lg pur" role="button"><span class="glyphicon glyphicon-list"></span> <br/>Availability</a>                                               
                                           <!-- <a href="../cloudaos/sales_view.php" class="btn btn-primary btn-lg sal" role="button"><span class="glyphicon glyphicon-credit-card"></span> <br/>Sales</a>
                                            --> 
                                             <a href="View_interaction.php" class="btn btn-primary btn-lg rep" role="button"><span class="glyphicon glyphicon-random"></span> <br/>Interaction</a>
                                              <a href="dashboard.php" class="btn btn-primary btn-lg log " role="button"><span class="glyphicon glyphicon-stats"></span> <br/>Dashboard</a>
                                             <!-- </div> -->

                                    </div>
                                     <label>Account:</label>                                      
                                          <div class="row">
                                           
                                               <?php 
                                                if ($AOS_AUTORISATION=="ALGORITHM") {
                                                    
                                                ?>
                                              <a href="account.php" class="btn btn-primary btn-lg avai" role="button"><span class="glyphicon glyphicon-bookmark"></span> <br/>Profile</a>   
                                              <a href="sdc.php" class="btn btn-primary btn-lg acount " role="button"><span class="glyphicon glyphicon-file"></span> <br/>Sdc</a>   
                                             
                                              <?php }else{?>
                                              <a href="account_client.php" class="btn btn-primary btn-lg avai" role="button"><span class="glyphicon glyphicon-bookmark"></span> <br/>Profile</a>                                                 
                                              <a href="sdc_client.php" class="btn btn-primary btn-lg acount" role="button"><span class="glyphicon glyphicon-file"></span> <br/>Sdc</a>   
                                             
                                              <?php }?>
                                              <a href="../cloudaos/iterms_list.php" class="btn btn-primary btn-lg sta" role="button"><span class="glyphicon glyphicon-list-alt"></span> <br/>Iterms</a>                                                                                                                                    
                                              <a href="View_tier.php" class="btn btn-primary btn-lg err" role="button"><span class="glyphicon glyphicon-tag"></span> <br/>Tier</a>
                                           
                                            <!--  <a href="#" class="btn btn-primary btn-lg log" role="button"><span class="glyphicon glyphicon-cloud-upload"></span> <br/>Logs</a>                                                                                    
                                              <a href="#" class="btn btn-primary btn-lg sta" role="button"><span class="glyphicon glyphicon-random"></span> <br/>Statistics</a>                                              
                                             < -->
                                           </div>
                                    </div>                        
                        
                        </div>                      
                        </div>
</div>