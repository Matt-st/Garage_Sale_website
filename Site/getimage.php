<?php
include_once 'connect.php';
//getimage.php
$id=$_GET['id'];
$image_result=mysql_query("select * from IMAGE where IMAGE_NUM='$id'");
 $image_assoc = mysql_fetch_assoc($image_result);
  $image_type=$image_assoc['IMAGE_TYPE'];
 $image=$image_assoc['IMAGE'];
 echo $image;

//$image_filename=
  header('Content-type: '. $image_type);

 //echo $row['IMAGE'];

?>