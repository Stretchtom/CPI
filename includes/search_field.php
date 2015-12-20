<?php  
    include_once '../phpscripts/conn.php';

?>  
                <!--<div class="input-append">-->
                    <input type="text" class="search-query mac-style" id="find" value="Search, &hellip;" 
                    onfocus="if(this.value=='Search, &hellip;'){this.value='';this.focus();};"
                    onblur="if(this.value==''){this.value='Search, &hellip;'};" onkeyup="showHint(this.value)" 
                    class="form-control" placeholder="Search" style="width:400px; height:25px; color:#444444; font-size:16px;"> 
                        <i class="glyphicon glyphicon-search"></i> 
                <!--</div>-->
                 <p><span id="ehn_txtHint"></span></p> 
    <br><br>
   
<!--</div>-->