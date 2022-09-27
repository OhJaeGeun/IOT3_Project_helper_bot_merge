<?php
include 'config.php';
date_default_timezone_set('Asia/Seoul'); //시간설정

//date now
$pdate = date('Y-m-d H:i:s');

//data(sensor)
$ptype = $_POST['type'];
$pval = $_POST['val'];

//cam_data
$plabel = $_POST['label'];
$pper = $_POST['per'];
$pacc = $_POST['acc'];

if($con){
  //query
  $query = "INSERT INTO data VALUES ('$pdate','$ptype',$pval);";
  $cam_query = "INSERT INTO cam_data VALUES ('$pdate','$plabel',$pper,$pacc);";
  $del_query = "DELETE FROM data WHERE date <DATE_ADD(now(), INTERVAL -1 hour);";
  $del_query2 = "DELETE FROM cam_data WHERE date <DATE_ADD(now(), INTERVAL -1 hour);";

  $result = mysqli_query($con, $query);
  $cam_result = mysqli_query($con, $cam_query);
  $del_result = mysqli_query($con, $del_query);
  $del_result2 = mysqli_query($con, $del_query2);
  
  //select
  $select = "SELECT * FROM data;";
  $res_sel = mysqli_query($con, $select);
  
  $cam_select = "SELECT * FROM cam_data;";
  $cam_sel = mysqli_query($con, $cam_select);
}
else
	die ("Connection failed: ". mysqli_connect_error());	
mysqli_close($con);
