<?php
// include_once '../../includes/scripts/aos_session.php';
include_once 'conn.php';

// include_once'../../phpscripts/secure_text.php';
// $secure = new secureText;


// $word = ($secure->cleanText($_GET["word"]));

$word =$_GET["word"];

$line = explode(' ',$word);
$size=sizeof($line);

if($size==1)
{
    $sqlToSearch=" `Name` LIKE '%$word%' ";
    $sqlToSearch1=" `name_subgroup` LIKE '%$word%' ";
    $sqlToSearch2=" `name_class` LIKE '%$word%' ";
}
else if($size==2)
{ 
  $sqlToSearch=" (Name LIKE '%$line[0]%' and `Name` LIKE '%$line[1]%' )";
  $sqlToSearch1=" (name_subgroup LIKE '%$line[0]%' and name_subgroup LIKE '%$line[1]%')";
  $sqlToSearch2=" (name_class LIKE '%$line[0]%' and name_class LIKE '%$line[1]%' )";

}
else if($size==3)
{ 
  $sqlToSearch="(Name LIKE '%$line[0]%' and `Name` LIKE '%$line[1]%' and `Name` LIKE '%$line[2]%' )";
  $sqlToSearch1="(name_subgroup LIKE '%$line[0]%' and name_subgroup LIKE '%$line[1]%' and name_subgroup LIKE '%$line[2]%')";
  $sqlToSearch2="(name_class LIKE '%$line[0]%' and name_class LIKE '%$line[1]%' and name_class LIKE '%$line[2]%'  )";

}
else if($size==4)
{ 
  $sqlToSearch="(Name LIKE '%$line[0]%' and `Name` LIKE '%$line[1]%' and `Name` LIKE '%$line[2]%' and `Name` LIKE '%$line[3]%')";
    $sqlToSearch1="(name_subgroup LIKE '%$line[0]%' and name_subgroup LIKE '%$line[1]%' and name_subgroup LIKE '%$line[2]%' and name_subgroup LIKE '%$line[3]%')";
  $sqlToSearch2="(name_class LIKE '%$line[0]%' and name_class LIKE '%$line[1]%' and name_class LIKE '%$line[2]%' and name_class LIKE '%$line[3]%'  )";
}



// $search_group="SELECT id_group as id_search,name as name_search,'group' as status from cpi_group WHERE Name like '%".$word."%'
// union(select id_subgroup,name_subgroup,'subgroup' as status from cpi_subgroup where name_subgroup like '%".$word."%' ) 
// union(select id_class,name_class,'class' as status from cpi_class where name_class like '%".$word."%' ) ";
  

$search_group="SELECT id_group as id_search,name as name_search,'group' as status from cpi_group WHERE $sqlToSearch
union(select id_subgroup,name_subgroup,'subgroup' as status from cpi_subgroup where $sqlToSearch1 ) 
union(select id_class,name_class,'class' as status from cpi_class where $sqlToSearch2 ) ";
                            
// echo ''.$search_group;
$search_group= mysql_query($search_group) or die(mysql_error());
$count_seach_group= mysql_num_rows($search_group); 

if($count_seach_group>0)
{

        echo '<ul class="list-group  " style="margin-top:-66px;margin-left:128px">';
    while ($row = mysql_fetch_array($search_group)) {
         $id_group=$row['id_search'];
         $name_group=$row['name_search'];
         $status=$row['status'];
        echo '<a   href="?id_search='.$id_group.'&name_search='.$name_group.'&table='.$status.'&starting=2013-09-01&ending=2014-09-01"><li class="list-group-item">
                     <b>'. $name_group .'</b>
                    <span  class=\"badge glyphicon glyphicon-thumbs-down\" style=\"background-color: #9a009a;\" >
                     </span></li></a>';
    } 
      echo '</ul>';
}else{
  echo '<ul class="list-group  " style="margin-top:-66px;margin-left:128px">
<li class="list-group-item"><strong>
                    we can not find what you are looking for (<font style="color:red;">'.$word.'</font>)
                    </strong>
            </li></ul>
            ';
}
// echo '<p>'.$word.'</p>';
mysql_close();
?>

