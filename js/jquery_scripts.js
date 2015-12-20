// JavaScript Document

 

//----------------------------------------------- POST ACCORDION -----------------------------------

$(function() {

 

	//ACCORDION BUTTON ACTION	

	$('div.accordionButton').click(function() {

		if ($(this).next().is(':visible')) {

			$('div.accordionContent').slideUp('normal');		

	   	} 

	   	else {

       		$('div.accordionContent').slideUp('normal');

            $(this).next().slideDown('normal');

  		}

	});

 

	//HIDE THE DIVS ON PAGE LOAD	

	$("div.accordionContent").hide();

})

//----------------------------------------------- EHN SEARCH -----------------------------------



function showHint(str){

	if (str.length==0){

	  document.getElementById("ehn_txtHint").innerHTML="";

	  return;

	}

	if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari

	  xmlhttp=new XMLHttpRequest();

	}

	else{// code for IE6, IE5

	  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");

	}

	xmlhttp.onreadystatechange=function(){

	  if (xmlhttp.readyState==4 && xmlhttp.status==200){

		document.getElementById("ehn_txtHint").innerHTML=xmlhttp.responseText;

	   }

	}

	xmlhttp.open("GET","../phpscripts/aos_search.php?q="+str,true);

	xmlhttp.send();

}

//----------------------------------------------- EHN SWITCH -----------------------------------



/*$(function(){

	$('a#toggle1').click(function(){

		$('#ehn_center').toggle();

		return false;

	});

})*/





