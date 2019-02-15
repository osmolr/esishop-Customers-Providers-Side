<?php
if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 
include('ConnectToDB.php');
$_SESSION['returnurl']=$_SERVER['REQUEST_URI'];
if(!isset($_SESSION['productquery'])) $_SESSION['productquery']='SELECT * FROM `bddproject`.`produit` NATURAL JOIN `couleur` WHERE `supprimer` = \'0\'   AND `quantite` > \'0\' ';
?>


<html>
<head>
    <!-- CSS FILES -->
     
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/navbar.css">   
    <link rel = "stylesheet" href = "fonts/glyphicons-halflings-regular.ttf">
    <link rel="stylesheet" href="css/token-input.css" type="text/css" /> <!-- css of Search spot -->    
    <!-- JS FILES -->
    <script type="text/javascript" src="js/jquery-3.2.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/jquery.tokeninput.js"></script>
</head>


<body>

<div id="flipkart-navbar" class="navbar-fixed-top">
<!-- brand -logo------------------------------------------------------------------------------------------>
	<div class="col-sm-2 logo"  style="padding:0 20px; height: 100% !important">
                <h2 style="margin:0px;"><span class="smallnav menu" onclick="openNav()">☰ Brand</span></h2>
				<a href="index.php"><center><img class="largenav" src="pic/logo1.png"></center></a>
    </div>
<!--search and buttons------------------------------------------------------------------------------------>
	<div class="col-sm-10"  style="padding:0 !important">
		<!--nav bar buttons-------------------------------------->
        <div class="row row1 largenav">
            <ul class="largenav pull-right">
                <li class="upper-links"><a class="links" href="index.php"><span class="glyphicon glyphicon-home"></span> Accueil</a></li>
                <li class="upper-links"><a class="links" href="contact.php"><span class="glyphicon glyphicon-earphone"></span> Contact</a></li>
                <li class="upper-links"><a class="links" href="help.pdf"><span class="glyphicon glyphicon-exclamation-sign"></span> Aide</a></li>
				<?php
				if(!isset($_SESSION['connect'])||$_SESSION['connect']!='yes') 
					echo('<li class="upper-links"><a class="links" href="#"  id="login" role="button" data-toggle="modal" data-target="#myModal"><span class="glyphicon glyphicon-user"></span> S\'identifier</a></li>');
				else echo('<li class="upper-links"><a class="links" href="#"  id="login" role="button"data-toggle="modal" data-target="#mylogoutModal"><span class="glyphicon glyphicon-off"></span> Déconnexion</a></li>
				<li class="upper-links"><a class="links" href="profil.php">'.$_SESSION['uname'].' <img class="img-rounded" src="ProfilPic/'.$_SESSION["photo"].'" style="height:25px;"></a></li>');
				?>
            </ul>
        </div>
		<!--serch and cart button------------------------------->
        <div class="row row2">
            <div class="flipkart-navbar-search smallsearch col-sm-10 col-xs-11">
                <div class="row">
					<form  method="post" action="ExecutQuery.php">
						<input type="text" id="demo-input-local" name="searchtext" >
						<button class="flipkart-navbar-button col-xs-1" type="submit" name="search">
							<span class="glyphicon glyphicon-search"></span>
						</button>
					</form>
                </div>
            </div>
			<?php
			   if (isset($_SESSION['cart']))
			   $articls = count($_SESSION['cart']['IDProduit']);
			   else $articls = 0;
			?>
            <div class="cart largenav col-sm-2">
                <a id="addtocart" class="cart-button" href="cart.php"><span class="glyphicon glyphicon-shopping-cart"></span> Panier
                    <span class="item-number "><?php echo ($articls); ?></span>
                </a>
            </div>
        </div>
	</div>
</div>
<!-- small nav----------------------------------------------------------------------------------------------->
<div id="mySidenav" class="sidenav">
    <div class="container" style="background-color: #eee;height:auto">
		<p onclick="closeNav()"><span  class="glyphicon glyphicon-chevron-left"></span> reduire</a></p>
    </div>
	<a href="index.php">HOME</a>
	<?php
	if(!isset($_SESSION['connect'])||($_SESSION['connect']!="yes"))
		echo('<a href="#"  id="login" role="button" data-toggle="modal" data-target="#myModal">Log-in/Register</a>');
	else echo('
			<a href="#"  id="login" role="button" data-toggle="modal" data-target="#myModal">Logout</a>
			<a href="profil.php">Profil</a>
			<a href="profil.php?target=facture">Facture</a>
	');
	?>
    <a href="cart.php">Panier</a>
    <a href="contact.php">Contact</a>
    <a href="#">About us</a>
</div>
<!-- small nav transition------------------------------------------------------------------------------------>
<script>
function openNav() {
    document.getElementById("mySidenav").style.width = "70%";
    // document.getElementById("flipkart-navbar").style.width = "50%";
    document.body.style.backgroundColor = "rgba(0,0,0,0.4)";
}
function closeNav() {
    document.getElementById("mySidenav").style.width = "0";
    document.body.style.backgroundColor = "rgba(0,0,0,0)";
}
</script>
<!-- search tags--------------------------------------------------------------------------------------------->
<?php
echo('
<script type="text/javascript">
 $(document).ready(function() {
            $("#demo-input-local").tokenInput([
                ');
				$sql="SELECT `type-article` FROM `bddproject`.`article`";
				$result=mysqli_query($link,$sql);
				while($row=mysqli_fetch_assoc($result)){
					echo('{id:"'.$row['type-article'].'", name: "'.$row['type-article'].'"},');
				}
				$sql="SELECT `nom-marque` FROM `bddproject`.`marque`";
				$result=mysqli_query($link,$sql);
				while($row=mysqli_fetch_assoc($result)){
					echo('{id:"'.$row['nom-marque'].'", name: "'.$row['nom-marque'].'"},');
				}
				$sql="SELECT `nom-couleur` FROM `bddproject`.`couleur`";
				$result=mysqli_query($link,$sql);
				while($row=mysqli_fetch_assoc($result)){
					echo('{id:"'.$row['nom-couleur'].'", name: "'.$row['nom-couleur'].'"},');
				}
 echo('      ]);
        });
</script>');
?>
</body>
</html>

<!-- login modal sign up------------------------------------------------------------------------------->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" >
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="z-index:10">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    Connexion / Inscription 
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12" >
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#Login" data-toggle="tab">Se Connecter</a></li>
                            <li><a href="#Registration" data-toggle="tab">S'inscrire</a></li>
                        </ul>
                        <!-- Tab panes -->
                        <div class="tab-content" style="padding-top:10px">
                            <div class="tab-pane active" id="Login">
                                <form role="form" class="form-horizontal" method="post" action="ExecutQuery.php">
                                <div class="form-group">
                                    <label for="email" class="col-sm-2 control-label">
                                        Email</label>
                                    <div class="col-sm-10">
                                        <input type="email" class="form-control" id="email1" placeholder="Email" name="email" required/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputPassword1" class="col-sm-2 control-label">
                                        Mot de passe</label>
                                    <div class="col-sm-10">
                                        <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Mot de passe" name="password" required/>
                                    </div>
                                </div>
                                <?php
								if (isset($_SESSION["connect"])) 
                                    if ($_SESSION["connect"]=="faute"){
									   echo' <div class="col-sm-2"></div>
                                             <div class="alert alert-warning col-sm-10" role="alert">
  										        <strong>Oh snap!</strong> Change a few things up and try submitting again.
											 </div>'; 
									   echo" <script> $('#myModal').modal('show'); </script>" 	;
									   $_SESSION["connect"]= "no";
										}						
								?>
                                <div class="row">
                                    <div class="col-sm-2">
                                    </div>
                                    <div class="col-sm-10">
                                        <button type="submit" name="login" class="btn btn-success btn-sm">Entrer</button>
                                        <button type="" class="btn btn-warning btn-sm">Mot de passe oublié</button>
                                    </div>
                                </div>
                                </form>
                            </div>
                            <div class="tab-pane" id="Registration">
                                <form role="form" class="form-horizontal" action="ExecutQuery.php" method="post">
                                <div class="form-group">
                                    <label for="text" class="col-sm-2 control-label">Nom</label>
                                    <div class="col-sm-10">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <select class="form-control" name="sexe">
                                                    <option value="Mr.">Mr.</option>
                                                    <option value="Ms.">Mme.</option>
                                                </select>
                                            </div>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" placeholder="Nom" name="nom" required/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="text" class="col-sm-2 control-label">Prénom</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="prenom" placeholder="Prénom" name="prenom" required/>
                                    </div>
                                </div>
                                <?php
								if (isset($_SESSION["usernameexist"])) 
                                    if ($_SESSION["usernameexist"]){
									   echo' <div class="col-sm-2"></div>
                                             <div class="alert alert-warning col-sm-10" role="alert">
  										        <strong>Oh snap!</strong> Change a few things up and try submitting again.
											 </div>'; 
									   echo" <script> $('#myModal').modal('show'); </script>" 	;
									   unset($_SESSION["usernameexist"]);
										}						
								?>
                                <div class="form-group">
                                    <label for="email" class="col-sm-2 control-label">Nom d'utilisateur</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="email" placeholder="Nom d'utilisateur" name="nomuser" required/>
                                    </div>
                                </div>
                                <?php
								if (isset($_SESSION["emailexist"])) 
                                    if ($_SESSION["emailexist"]){
									   echo' <div class="col-sm-2"></div>
                                             <div class="alert alert-warning col-sm-10" role="alert">
  										        <strong>Oh snap!</strong> Change a few things up and try submitting again.
											 </div>'; 
									   echo" <script> $('#myModal').modal('show'); </script>" 	;
									   unset($_SESSION["emailexist"]);
										}						
								?>
                                <div class="form-group">
                                    <label for="email" class="col-sm-2 control-label">Email</label>
                                    <div class="col-sm-10">
                                        <input type="email" class="form-control" id="email" placeholder="Email" name="email" required/>
                                    </div>
                                </div>
                                <?php
								if (isset($_SESSION["motdepasseereur"])) 
                                    if ($_SESSION["motdepasseereur"]){
									   echo' <div class="col-sm-2"></div>
                                             <div class="alert alert-warning col-sm-10" role="alert">
  										        <strong>Oh snap!</strong> Change a few things up and try submitting again.
											 </div>'; 
									   echo" <script> $('#myModal').modal('show'); </script>" 	;
									   unset($_SESSION["motdepasseereur"]);
										}						
								?>
                                <div class="form-group">
                                    <label for="password" class="col-sm-2 control-label">Mot de passe</label>
                                    <div class="col-sm-10">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <input type="password" class="form-control" id="password" placeholder="Mot de passe" name="password1" required/>
                                            </div>
                                            <div class="col-sm-6">
                                                <input type="password" class="form-control" id="password" placeholder="Répèter le mot de pass" name="password2" required/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="email" class="col-sm-2 control-label">Date de naissance</label>
                                    <div class="col-sm-10">
                                        <input type="date" class="form-control" id="date" placeholder="Date de naissance" name="date" required/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="email" class="col-sm-2 control-label">Adresse</label>
                                    <div class="col-sm-10">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <input type="text" class="form-control" id="adress" placeholder="Adresse" name="adresse" required/>
                                            </div>
                                            <div class="col-md-3">
                                                <input type="text" class="form-control" placeholder="Ville" name="ville" />
                                            </div>
                                            <div class="col-md-3">
                                                <input type="number" class="form-control" placeholder="Code postal" name="codepostal" required/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="mobile" class="col-sm-2 control-label">Téléphone</label>
                                    <div class="col-sm-10">
                                        <input type="tel" class="form-control" id="mobile" placeholder="Numero de téléphone" name="tel" required/>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-2">
                                    </div>
                                    <div class="col-sm-10">
                                        <input type="submit" class="form-control btn-info" value="Sauvegarder et continuer" name="inscription" required/>
                                    </div>
                                </div>
                                </form>
                            </div>
                        </div>      
                    </div>  
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="mylogoutModal" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Déconnexion</h4>
		</div>
		<div class="modal-body">
		<h3>Est-ce que vous êtes sûr que vous voulez déconnecter ?</h3>
			<hr>
		<div class="row"><div class="col-sm-5">
          <form action="ExecutQuery.php" method="post">
			<button class="form-control btn-success" type="submit" name="deconnexion">Déconnecter</button>
		  </form></div>
		<div class="col-sm-2"><button class="form-control btn-info col-sm-2" data-dismiss="modal">Annuler</button></div></div>
        </div>
      </div>
    </div>
  </div>

<!-- For making the search spot glow when being used -->
  <script type="text/javascript">
      $(function() {
  $('#token-input-demo-input-local').focus(function() {
    $('.token-input-list, .flipkart-navbar-button').css('box-shadow', '0 0 5px rgba(81, 203, 238, 1)');
  }), $('#token-input-demo-input-local').focusout(function() {
    // on mouseout, reset the search spot
    $('.token-input-list, .flipkart-navbar-button').css('box-shadow', 'none');
  });
});
  </script>
  </body>
