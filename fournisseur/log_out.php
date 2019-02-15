<?php require_once('functions.php'); ?>
<?php 
session_start();
$_SESSION["IDFournisseur"] = null;
$_SESSION["nomdutilisateur"] = null;
session_destroy();
redirect('http://localhost/esishop/fournisseur/index.php');
  ?>