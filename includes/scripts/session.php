<?php 
	session_start();
	if (!$_SESSION['username']) {
		header("location: http://{$_SERVER['HTTP_HOST']}/CPI");
	}
	else{
		
		 $sess_username= $_SESSION['username'];
	     $sess_fname=$_SESSION['fname'];
	     $sess_lname=$_SESSION['lname'];
                    

	}
?>
                   
                   