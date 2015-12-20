<?php
class secureText{
	function cleanText($text){
		$text = stripslashes($text);
		$text = strip_tags($text);
		$text = mysql_real_escape_string($text);
		$text = str_replace("'","&#39;",$text);
		return $text;
	}
}
?>