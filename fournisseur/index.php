<?php
session_start();
if(isset($_SESSION["IDFournisseur"])) header("location:interface_fournisseur.php");
 ?>
<?php
include('functions.php'); 
include('../ConnectToDB.php');

$Username=""; 
if (isset($_POST['submit'])){
	// Process the form
	//attempt login 
	$Username = $_POST["username"];
	$Password = $_POST["password"];
	$found_fournisseur = attempt_login($Username,$Password);
	if ($found_fournisseur){
		//success
		//logged in
		$_SESSION["IDFournisseur"] = $found_fournisseur["IDFournisseur"];
		$_SESSION["nomdutilisateur"] = $found_fournisseur["nomdutilisateur"]; 
    $_SESSION["IDcf"] = $found_fournisseur["IDcf"];
        redirect('http://localhost/esishop/fournisseur/interface_fournisseur.php');
	} 
	}
 ?>
<html>
<head>
	<title>Identification</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="../css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="../css/login-style.css">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css">

</head>
<body>
		<nav class="navbar navbar-default">
          <div class="container-fluid">
            <div class="navbar-header" style="padding-bottom:5px;">
              <a class="navbar-brand" href="#">
                <img alt="Brand" width="65px" src="logo.png" >
              </a>
            </div>
          </div>
        </nav>

	<center>
	<div class="container">
	    <img style="width:30%;" src="logo.png">
	    <center>
	    	<form action="index.php" method="post" class="form-horizontal">
             <div class="col-sm-offset-1 col-sm-10" >
             <div class="form-group">
               <label for="inputUsername" class="col-sm-1 control-label"><i class="fa fa-user" aria-hidden="true" style="font-size: 200%;" ></i></label>
               <div class="col-sm-10">
                 <input type="text" class="form-control" name="username" id="inputUsername" placeholder="Nom d'utilisateur " value="<?php echo htmlentities($Username); ?>" required>
               </div>
             </div>
             <div class="form-group">
               <label for="inputPassword3" class="col-sm-1 control-label"><i class="fa fa-key" style="font-size: 200%;" aria-hidden="true"></i>
</label>
               <div class="col-sm-10">
                 <input type="password" class="form-control" name="password" id="inputPassword3" placeholder="Mot de Pass" required>
               </div>
             </div>
             </div>
             <?php 
               if (isset($_SESSION["message"])){
               echo'<div class="alert alert-danger col-sm-6 col-sm-offset-3" role="alert">'.$_SESSION["message"].'</div>';
                 }
              ?>
               <div class="form-group">
               <div class="col-sm-offset-3 col-sm-6">
                 <button type="submit" name="submit" class="btn btn-info btn-block">Se Connecter</button>
               </div>
             </div>
        </form>
    </center>
	</div>
	<footer></footer>
</center>
</body>
</html>
<?php mysqli_close($link) ?>