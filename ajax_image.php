<?php
require('ConnectToDB.php');
if(!isset($_SESSION)) session_start();
$id=$_GET['id']; // Session_id
$t_width = 300;	// Maximum thumbnail width
$t_height = 300;	// Maximum thumbnail height
$new_name = "client_".$id.".jpg"; // Thumbnail image name
$path = "ProfilPic/";
if(isset($_GET['t']) and $_GET['t'] == "ajax")
	{
		extract($_GET);
		$ratio = ($t_width/$w); 
		$nw = ceil($w * $ratio);
		$nh = ceil($h * $ratio);
		$nimg = imagecreatetruecolor($nw,$nh);
		$im_src = imagecreatefromjpeg($path.$img);
		imagecopyresampled($nimg,$im_src,0,0,$x1,$y1,$nw,$nh,$w,$h);
		imagejpeg($nimg,$path.$new_name,90);
		mysqli_query($link,"UPDATE `bddproject`.`client` SET `photo` = '".$new_name."' WHERE `client`.`IDClient` = '".$id."'; ");
		$_SESSION['photo']=$new_name;
		echo $new_name."?".time();
		exit;
	}
	
	?>