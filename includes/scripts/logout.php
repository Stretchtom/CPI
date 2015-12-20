<?php
session_start();
include_once("../../phpscripts/conn.php");
include_once 'session.php';
header("location: http://{$_SERVER['HTTP_HOST']}/CPI");
//header("location: http://41.74.167.198/CPI");

?>
<!DOCTYPE html PUBLIC"-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
    <head>
        <meta charset="UTF-8"/>
    </head>