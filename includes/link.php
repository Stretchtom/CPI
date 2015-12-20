<ul class="nav nav-tabs" role="tablist" >
    <?php 
    if ($AOS_AUTORISATION=="ALGORITHM") {
        
    ?>
    <li><a href="home.php" ><STRONG><H4><span class="glyphicon glyphicon-home">Home </H4></STRONG></a>
    </li>
    
    <li >
        <a href="account.php"><STRONG><H4><span class="glyphicon glyphicon-user"></span>Super Account </H4></STRONG></a>
    </li>
     <li>
        <a href="add_subaccount.php"><STRONG><H4><span class="glyphicon glyphicon-user"></span> Sub Account </H4></STRONG></a>
    </li>

     <li>
        <a href="sdc.php"><STRONG><H4><span class="glyphicon glyphicon-hdd"></span> SDC </H4></STRONG></a>
    </li>
    <?php }else{?>
    <li><a href="home.php" ><STRONG><H4><span class="glyphicon glyphicon-home">Home </H4></STRONG></a>
    </li>
     <li>
        <a href="sub_accountclient.php"><STRONG><H4><span class="glyphicon glyphicon-user"></span> Sub Account </H4></STRONG></a>
    </li>
    
    <li >
        <a href="sdc_client.php"><STRONG><H4><span class="glyphicon glyphicon-hdd"></span> SDC </H4></STRONG></a>
    </li>
    <?php }
    //?yourtin= echo $tin_number;
    ?>
    
</ul>
