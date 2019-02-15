<?php 
session_start();
if(!isset($_SESSION["IDFournisseur"])||($_SESSION["IDFournisseur"]===NULL)) header("location:index.php");
include('../ConnectToDB.php');
$today=getdate(); $d = $today['mday']; $m = $today['mon']; $y = $today['year']; $today=$y.'-'.$m.'-'.$d;
if (isset ($_GET['op'])){
	$id=$_GET['id'];
         if ($_GET['op']=="validation"){
         $query = " UPDATE `achat-commande`  SET `Etat` = 'en route' WHERE `achat-commande`.`IDAchat_Commande` =".$id." ";
         mysqli_query($link,$query);
         $query="INSERT INTO `notification` (`IDNotification`, `IDFournisseur`, `IDProduit`, `titre`, `date`, `description`, `vu`) VALUES (NULL, '".$_SESSION["IDFournisseur"]."', '".$_GET["idproduit"].", 'en route', '".$today."', '".$_POST["date"]."', '0');";
         mysqli_query($link,$query);
     }elseif ($_GET['op']=="descreption"){
         $query = " UPDATE `achat-commande`  SET `Etat` = 'en attent' WHERE `achat-commande`.`IDAchat_Commande` =".$id." ";
         mysqli_query($link,$query);
         $query="INSERT INTO `notification` (`IDNotification`, `IDFournisseur`, `IDProduit`, `titre`, `date`, `description`, `vu`) VALUES (NULL, '".$_SESSION["IDFournisseur"]."', '".$_GET["idproduit"].", 'en attent', '".$today."', '".$_POST["descreption"]."', '0');";
         mysqli_query($link,$query);
     }
 }
 header("location:interface_fournisseur.php");
 ?>