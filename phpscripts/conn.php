<?php
error_reporting(0);
mysql_connect('127.0.0.1','root') or die(mysql_error());
//mysql_connect('127.0.0.1','root','nisrpracticum') or die(mysql_error());
//mysql_connect('127.0.0.1','root','admin') or die(mysql_error());
mysql_select_db('nisr_cpi') or die(mysql_error());

?>