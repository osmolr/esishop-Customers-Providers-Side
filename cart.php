<?php
if(!isset($_SESSION)) 
    { 
        session_start(); 
	}
if(!isset($_SESSION['IDClient'])) $_SESSION['IDClient']=3;
include_once("ConnectToDB.php");
include_once("cart_function.php");
include_once("CreatQuery.php");

$erreur = false;
//determination de l'operation sur le panier--------------------------------------------------------------------------
$action = (isset($_POST['action'])? $_POST['action']:  (isset($_GET['action'])? $_GET['action']:NULL )) ;
if($action==!NULL)
{
	if(!in_array($action,array('ajout', 'suppression', 'refresh','confirmation','supprimecart','savemissedproduct'))) 
		$erreur=true;
	//execution de l'actions :-----------------------------------------------------------------------------------------
  	if (!$erreur){
   		switch($action){
      		Case "ajout":
				// recuperation des donneés reçu par la methode (POST) ou par la methode (GET)-------------------------
				$IDcp = (isset($_POST['IDcp'])? $_POST['IDcp']:(isset($_GET['IDcp'])? $_GET['IDcp']:NULL )) ;
				$couleur = (isset($_POST['couleur'])? $_POST['couleur']:(isset($_GET['couleur'])? $_GET['couleur']:NULL )) ;
				$couleur=GETID("IDCouleur","couleur","nom-couleur",$couleur,$link);
				$taille = (isset($_POST['taille'])? $_POST['taille']:(isset($_GET['taille'])? $_GET['taille']:NULL )) ;
				$quantite = (isset($_POST['quantite'])? $_POST['quantite']:(isset($_GET['quantite'])? $_GET['quantite']:NULL )) ;
				//recuperation de le reste d'ifos depuis la BDD -------------------------------------------------------
				$sql=PRODUCTSELECT($IDcp,NULL,NULL,NULL,$couleur,$taille);
				$result=mysqli_query($link,$sql); 
				$exist=false;
				// si le produit existe----------------------
				if (mysqli_num_rows($result)>0) 
					while ($row=mysqli_fetch_assoc($result))
						{
						$IDProduit=$row['IDProduit'];
						$prix=$row['prixvente'];
						$remise=$row['remise'];
						$image=$row['img'];
						$IDArticle=$row['IDArticle'];
						$IDMarque=$row['IDMarque'];
						$TVA=$row['TVA'];
						//appel de la fonction ajouterArticle dans cart_function.php---------------------------------
						ajouterArticle($IDProduit,$IDArticle,$IDMarque,$couleur,$taille,$quantite,$prix,$remise,$TVA,$image);
						header('location:'.$_SESSION['returnurl']);
						}
				// si non afficher un message----------------
				else{	echo('<center><h3>  desole ce produit n est pas disponible avec cette couleur  </h3>
							  <a href="javascript:history.go(-1)"><h3>retour</h3></a></center>');
						exit;
					}
         		break;
      		Case "suppression":
         		supprimerArticle($_GET['IDProduit']);
				$_SESSION["operation"]="Le produit a été suprimer";
				header('location:cart.php');
         		break;
      		Case "refresh" :
            	modifierQTeArticle($_GET['IDProduit'],$_POST['quantite']);
				$_SESSION["operation"]="La quantité du produit a été modifier";
				header('location:cart.php');
         		break;
			Case "confirmation" :

                confirmation($link);
				header('location:cart.php');
       
         		break;
			Case "supprimecart" :
				if(isset($_SESSION['cart'])){
            		supprimecart();
					$_SESSION["operation"]="votre panier est vide";
					header('location:cart.php');
				}
				break;
			Case "savemissedproduct" :
				savemissedproduct($_GET['i'],$link);
				header('location:cart.php');
				break;
				
         	break;
   		}
   	exit;
	}
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Panier</title>
</head>

<body style="padding-top:90px; background-color:#fff">
<?php 
//including nav bar----------------------------------------------------------------------------------------------------
require('navbar.php');
	
if (isset($_SESSION["operation"])){
	//script pour afficher ler allerts en cas d' une operation terminer -----------------------------------------------
	echo ('<script>
				jQuery(document).ready(function($){
				$("#notification").fadeIn(3000).delay(4000).fadeOut(2000)
				});
			</script>
			<center>
				<div class="alert alert-success" id="notification" style="display:none; width:70%; margin-top:10px" >
				  <strong>Info!</strong> '.$_SESSION["operation"].'.
				</div>
			</center>'); 
unset($_SESSION["operation"]);
}
?>
	
<style>
	table{
		box-shadow:  0 1px 2px rgba(0,0,0,0.24), 0 1px 2px rgba(0,0,0,0.24);
		width: 95% !important;
		text-align:center;
	}
	.row{
		margin: 0;
	}
	 th{
		background-color:#f0f0f0; 
		color:#338; 
		font-size:14px; 
		text-align: center !important;
		cursor: pointer;
		padding: 20px !important;
		
	}
	.panel{
		margin:0 32px;
		border-radius: 0;
		box-shadow:  0 1px 2px rgba(0,0,0,0.24), 0 1px 2px rgba(0,0,0,0.24);
	}
	td{
		vertical-align: middle !important;
		background-color: #fff !important;
		border:0px 0px 0px 1px solid #ddd !important;
	}
	table th:hover {
		background-color:#666;
		color: #FFF;
	}
	.btn {
		border-radius: 0px !important;
		height: 40px !important;
		box-shadow: none;
	}
	.prix-total{
		color:#F55; 
		font-size:25px;
		font-family: monospace ;
		font-variant: small-caps 
	}
	#modifier:hover{
		color: darkorange;
		font-size: 15px;
		border: 1px solid darkorange;
		background-color: #fff;
	}
	#suprimer:hover{
		color: crimson;
		font-size: 15px;
		border: 1px solid crimson;
		background-color: #fff;
	}
	#numberinput{
		border: 1px solid #ccc;
		font-size: 15px;
		width: 40%;
		text-align: center;
	}
	#numberinput:hover{
		width: 45%;
		text-align: center;
		color: darkorange;
		border: 1px solid darkorange;
		
	}
	.emptycart{
		display: inline-block;
		height:300px; 
		width:100%;
		background-repeat: no-repeat;
		background-position: center;
		background-size:280px auto;
		text-align: center;
		font-size: 32px;
		color: #666;
		text-shadow: 1px 1px 1px #fff;
		margin: 20px 0;
	}
	#facturetext{
		font-size:  20px;
		font-family: serif;
		color: cornflowerblue;
	}
</style>
	
<?php
echo('<div class="row" id="facture">');
	//table de facture
	if (creationfacture()) if (count($_SESSION['facture']['IDProduit'])>0){
		echo('<hr />
			<div class="col-lg-12" style="padding:10px 0;">
			<div class="panel panel-default">
  			<center><div class="panel-heading"><h3>Voici votre facture</h3><h4>Vous pouvez cosulter vos factures par <a href="profil.php?target=facture"> ici </a></h4></div></center>
			<center><table class="table table-striped" style="box-shadow:none;">
			  <tr>
				  <th width="70%">DESCRIPTION</th>
				  <th width="10%">QUANTITE</th>
				  <th width="20%">PRIX_TOTAL</th>
			  </tr>');
		for ($i=0 ;$i < count($_SESSION['facture']['IDProduit']) ; $i++){
			$toString=toString($i,$_SESSION['facture'],$link);
			echo "
                    <tr>
                      <td>".$toString."</td>
                      <td>".$_SESSION['facture']['quantite'][$i]."</td>
                      <td>".$_SESSION['facture']['prixtotal'][$i]." DA</td>
                    </tr>
                ";
		}
		echo('	</table></center>
				</div>
				</div>');
		}
		unset ($_SESSION['facture']);
		if (isset($_SESSION['deletemissedproduct']))if($_SESSION['deletemissedproduct']) unset($_SESSION['missedproduct']);
		if (creationmissedproduct()) if (count($_SESSION['missedproduct']['IDProduit'])>0){
		echo('
			<div class="col-lg-12" style="padding:10px 0 ">
			<div class="panel panel-default">
  			<center><div class="panel-heading"><h4>Désolé le produit fini avant la confirmation de votre panier ou la quantité depasse la quantite disponible</br> contacter nous par <a> ici </a></h></div></center>
  			<center><table class="table table-striped" style="box-shadow:none;">
			  <tr>
				  <th width="50%">DESCRIPTION</th>
				  <th width="10%">QUANTITE</th>
				  <th width="40%">PRIX_TOTAL</th>
			  </tr>');
		for ($i=0 ;$i < count($_SESSION['missedproduct']['IDProduit']) ; $i++){
			$toString=toString($i,$_SESSION['missedproduct'],$link);
			echo "
                    <tr>
                      <td>".$toString."</td>
                      <td>".$_SESSION['missedproduct']['quantite'][$i]."</td>
					<form method='post' action='cart.php?action=savemissedproduct&i=".$i."'>
                      <td><button class='btn-success form-control btn' id='filsdattente' type='submit'/>Add to waited list <span class='glyphicon glyphicon-floppy-saved'></span></button></td>
                    </form>
                    </tr>
                ";
		}
		$_SESSION['deletemissedproduct']=true;
		echo('	   </table></center>
			</div>
			</div>');
		}
echo('</div>');
if (creationcart()) $nbArticles=count($_SESSION['cart']['IDProduit']); else $nbArticles=0;
if ($nbArticles <= 0)
      echo (" 
	  	<div class='emptycart row' style='position: relative;'>
	  		<img src='pic/emptycart.png' class='col-lg-4' style='max-width:300px; left:70px;'></img>
	  		<strong><p class='col-lg-8' style='top:100px;'><span class='glyphicon glyphicon-shopping-cart'></span> Votre panier est vide !<br>Commencer le shopping maintenant.</br></p></strong>
		</div> 
		<a href='filter.php?f=all'><button class='btn-info form-control btn' type='submit' style='box-shadow:  0 1px 2px rgba(0,0,0,0.24), 0 1px 2px rgba(0,0,0,0.24);'/></span>CONTINUE LE SHOPPING <span class='glyphicon glyphicon-share-alt'> </button></a>");
else  echo ' <!-- <div class="row" style="padding:19px">
					<div class="col-sm-4">
						<form method="post" action="cart.php?action=confirmation">
						<button class="btn-success form-control btn" type="submit" style="		box-shadow:  0 1px 2px rgba(0,0,0,0.24), 0 1px 2px rgba(0,0,0,0.24);"/><span class="glyphicon glyphicon-ok"></span> CONFIRMER L\' ACHAT </button></form>
					</div>
					<div class="col-sm-8">
						<a href="filter.php"><button class="btn-info form-control btn" type="submit" style="box-shadow:  0 1px 2px rgba(0,0,0,0.24), 0 1px 2px rgba(0,0,0,0.24);"/></span>CONTINUE LE SHOPPING <span class="glyphicon glyphicon-share-alt"> </button></a>
					</div>
			</div>	 -->

<div class="row" style="margin-top:20px;">
<center><table class="table table-hover " style="text-align:center">
  <tr id="head">
	  <th width="15%"><span class="glyphicon glyphicon-picture"></span> IMAGE</th>
      <th width="25%"><span class="glyphicon glyphicon-list-alt"></span> DESCRIPTION</th>

      <th width="15%"><span class="glyphicon glyphicon-inbox"></span> QUANTITE</th>
      
      <th width="15%"><span class="glyphicon glyphicon-usd"></span> PRIX</th>
      <th width="15%">MODIFIER</th>
      <th width="15%">SUPRIMER</th>
  </tr>';
	//l affichage de cart
    if (creationcart()){  
		echo"<tbody>";
	   for ($i=0 ;$i < count($_SESSION['cart']['IDProduit']) ; $i++){
            $toString=toString($i,$_SESSION['cart'],$link);
		    $toString=strtoupper($toString);
            echo "
                    <tr>
                      <td><img src='uploads/".$_SESSION['cart']['image'][$i]."' style='height:100px'/></td>
                      <td>".$toString."</td>
                      
                    <form method='post' action='cart.php?action=refresh&IDProduit=".rawurlencode($_SESSION['cart']['IDProduit'][$i])."'>
                      <td><center><input class='form-control btn' type='number' name='quantite' value='".$_SESSION['cart']['quantite'][$i]."' min='1' id='numberinput'/></center></td>
                      <td class='prix-total'>".$_SESSION['cart']['prixtotal'][$i]." (DA)</td>
                      <td><button class='btn-default form-control btn' id='modifier' type='submit'/><span class='glyphicon glyphicon-refresh'></span>  Modifier Qt</button></td>
                    </form>
                    <form method='post' action='cart.php?action=suppression&IDProduit=".rawurlencode($_SESSION['cart']['IDProduit'][$i])."'>
                      <td><button class='btn-default form-control btn' id='suprimer' type='submit'/><span class='glyphicon glyphicon-trash'></span>  Suprimer</button></td>
                    </form>
                    </tr>
                ";
        }
        echo"</tbody>";
    }
    echo "</table></center></div>";
    //info et confirmationd achat
    if ($nbArticles > 0) 
		{echo('
			<div class="row">
				<div class="panel panel-default">
					<div class="panel-heading"><b>Confirmation d\'achat</b></div>
					<div class="panel-body">
						<div class="col-sm-5" style="left:150px; top:10px;">
						<h3><strong>Nombres de produits: </strong>'.compterArticles().'</h3>
						<h3><strong>Total: </strong>'.MontantGlobal().' DA</h3>						
						</div>
						<div class="col-sm-7">

<div class="row" style="padding:19px">
				<div class="col-sm-12" style="margin-bottom:20px">');
					if(!isset($_SESSION['connect'])||($_SESSION['connect']!='yes'))
						echo('<button class="btn-info form-control btn" data-toggle="modal" data-target="#myModal"/><span class="glyphicon glyphicon-user"></span>  Veulliez vous identifiez pour confirmer </button>'); 
					else echo(' <form method="post" action="cart.php?action=confirmation">
								<button class="btn-success form-control btn" type="submit"/><span class="glyphicon glyphicon-ok"></span> CONFIRMER 
								L\' ACHAT </button> 
								</form>');
					echo('</div>
				<div class="col-sm-12">
					<form method="post" action="cart.php?action=supprimecart">
					<button class="btn-default form-control btn" type="submit"/><span class="glyphicon glyphicon-remove"></span> VIDER LE PANIER </button>
					</form>
				</div>
			</div>
						</div>
					</div>
				</div>
			</div>');
		}
	
	
?>
	<!-- FOOTER --> 
	<?php include('footer.php') ?>
<script
  integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI="
  crossorigin="anonymous"></script>
</body>
</html>
	
    		