<?php
if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 
if(!isset($_SESSION['connect'])) $_SESSION['connect']= false;
if(!isset($_SESSION['idclient'])) $_SESSION['idclient']= false;
include_once('ConnectToDB.php');
require('CreatQuery.php');
?>
<!doctype html>
<html class="no-js" lang="en">
<head>
    <title>Produit Sélectionné</title>
	<style>
.textcomment{
	height: 60px !important;
	width: 100% !important;
	border: 1px solid #ddd !important;
	vertical-align: top;
}	
.lableproduct{
	font-size: 18px;
	font-family: serif;
	padding-top: 10px;
	font-weight: 500;
}
.hide-bullets {
    list-style:none;
    margin-left: -40px;
    margin-top:20px;
}
.inputproduct{
		width:600px !important;
		border-radius: 0px !important;
		font-size: 18px !important;
		font-family: serif;
		font-weight: 400;
		box-shadow: none;
	}
.btncomment{
		border-radius: 0 !important;
		width: 300px !important;
		margin: 10px 0 ;
		background-color: #5BC0DE !important;
		color: white !important;
		border: none !important;
	}
.btncomment:hover{
	background-color: #7be1ff !important;
}
.thumbnail {
    padding: 0;
}
.carousel-inner>.item>img, .carousel-inner>.item>a>img {
    width: 100%;
}
#addtocart2 {
	  background-color: rgb(255, 172, 0);
	  border: none;
	  border-radius: 2px !important; 
	  font-weight: bold;
	  color: black;
	}
	#addtocart2:hover {
	  background-color: rgb(255, 219, 0);
	}
</style>
  </head>
<body style="padding: 0px 10px 0px 10px; padding-top: 100px;">
    <?php
	  	include('navbar.php');	
		/*recupiration des donnees*/
		$idcp=$_GET['id'];
		$sql='SELECT * FROM `bddproject`.`produit` WHERE `IDcp` ='.$idcp;
		$infoproduit=mysqli_query($link,$sql);
		$tabid=array();
		$tabtaille=array();
		$tabimg=array();
		$tabcomment=array();
			$tabcomment['comment']=array();
			$tabcomment['username']=array();

		function dejaexist($val,$tab) {return in_array($val,$tab);}
		while($row=mysqli_fetch_assoc($infoproduit)){					
			
			if (!dejaexist($row['taille'],$tabtaille)) array_push($tabtaille,$row['taille']);
			if ((!dejaexist($row['img'],$tabimg))&&($row['img']!='standard.png')) array_push($tabimg,$row['img']);
			array_push($tabid,$row['IDProduit']);
			$idmarque=$row['IDMarque'];
			$idarticle=$row['IDArticle'];
			$prix=$row['prixvente'];
			$description=$row['description'];
		}
		if(count($tabimg)==0)array_push($tabimg,'standard.png');
		
		$article= GETNOM("type-article","article","IDArticle",$idarticle,$link);
		$marque= GETNOM("nom-marque","marque","IDMarque",$idmarque,$link);
	?><br>
<!-- script pour l'animation des images (slide show) -->
<script>
	jQuery(document).ready(function($) {

$('#myCarousel').carousel({
	interval: 5000
});

//Handles the carousel thumbnails
$('[id^=carousel-selector-]').click(function () {
var id_selector = $(this).attr("id");
try {
var id = /-(\d+)$/.exec(id_selector)[1];
console.log(id_selector, id);
jQuery('#myCarousel').carousel(parseInt(id));
} catch (e) {
console.log('Regex failed!', e);
}
});
// When the carousel slides, auto update the text
$('#myCarousel').on('slid.bs.carousel', function (e) {
	 var id = $('.item.active').data('slide-number');
	$('#carousel-text').html($('#slide-content-'+id).html());
});
});
</script>
	
<div class="row" style="height:530px;">
	<!-- SLIDE SHOW IMAGES ------------------------------------------------------------------------->
	<div class="col-xs-2 col-sm-1" id="slider-thumbs" style="height:530px;overflow-y: auto;">
                <!-- Bottom switcher of slider -->
                <ul class="hide-bullets">
					<?php
						for ($i=0;$i<count($tabimg);$i++){
							echo('<li><a class="thumbnail" id="carousel-selector-'.$i.'"><img src="uploads/'.$tabimg[$i].'"></a></li>');
						}
					?>
                </ul>
            </div>
	<div class="col-xs-10 col-sm-5">
	<div class="" id="slider">
		<!-- Top part of the slider -->
	<div class="row">
		<div class="col-sm-12" id="carousel-bounding-box">
			<div class="carousel slide" id="myCarousel">
				<!-- Carousel items -->
				<div class="carousel-inner">
					<?php
						for ($i=0;$i<count($tabimg);$i++){
							if($i==0) $item='active item'; else $item='item';
							echo('<div class="'.$item.'" data-slide-number="'.$i.'"><img src="uploads/'.$tabimg[$i].'"></div>');
						}
					?>
					<!-- Carousel nav -->
					<a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
						<span class="glyphicon glyphicon-chevron-left"></span>
					</a>
					<a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
						<span class="glyphicon glyphicon-chevron-right"></span>
					</a>
				</div>
			</div>
		</div>
	</div>
	</div>
	<!--/Slider-->
	</div>
	<!-- PARTIE INFO PRODUIT ET SELECTION POUR L'ACHAT-------------------------------------------->
	<div class="col-xs-12 col-sm-6 form-group-lg" >
		<h3><?php echo($_GET['name']); ?></h3>
		<h4><u>Description</u><?php echo(": ".$description); ?> </h4>
		<form  action="cart.php?action=ajout&IDcp=<?php echo ($_GET["id"]."&name=".$_GET['name']) ?>" method="post">
			<label class="lableproduct">Taille :
				<select class="form-control inputproduct" name="taille" onchange="showcolor(this.value)" required >
					<option hidden="hidden" disabled selected value>Sélectionner une taille</option>
					<?php
					for($i=0;$i<count($tabtaille);$i++)echo("<option value'".$tabtaille[$i]."'>".$tabtaille[$i]."</option>");
					?>
				</select>
				<script>
				function showcolor(str) {
					if (str=="") {
						document.getElementById("couleur").innerHTML="";
						return;
					} 
					if (window.XMLHttpRequest) {
						// code for IE7+, Firefox, Chrome, Opera, Safari
						xmlhttp=new XMLHttpRequest();
					} else { // code for IE6, IE5
					xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
					}
					xmlhttp.open("GET","ExecutQuery.php?idcp=<?php echo $_GET['id']; ?>&taille="+str,true);
					xmlhttp.send();
					xmlhttp.onreadystatechange=function() {
						if (this.readyState==4 && this.status==200) {
							document.getElementById("couleur").innerHTML=this.responseText;
						}
					}
				}
				</script>
			</label>
			<label class="lableproduct">Couleur :
				<select name="couleur" id="couleur" required class="form-control inputproduct">
					<option disabled selected value hidden='hidden'>Sélectionner la taille d'abord</option>
				</select>
			</label>
			<label for="middle-label" class="lableproduct">Quantité :
				<input class="form-control inputproduct" type="number" id="middle-label"  min="1" max="5" value="1" name="quantite">
			</label>
			<hr>
			<input id="addtocart2" class="form-control  inputproduct btn-warning" type="submit" value="Ajouter au panier">
		</form>   
	</div>
</div>
<br><hr>
<div class="row" style="margin:20px 0;">
	<ul class="nav nav-tabs">
		<li class="active"><a style="border-radius:0;" data-toggle="tab" href="#comment"><p style="font-weight:600;font-size:18px;">Commentaires</p></a></li>
		<li><a style="border-radius:0;" data-toggle="tab" href="#menu1"><p style="font-weight:600; font-size:18px;">Produits similaires</p></a></li>
	</ul>
	<div class="tab-content" style="border:1px solid #ddd;border-top:0; padding:10px;">
		<div id="comment" class="tab-pane fade in active">
			<p>
				<?php
					echo('	<form action="ExecutQuery.php?idc='.$_SESSION["idclient"].'&idcp='.$_GET["id"].'" method="post">
							<textarea class="textcomment" name="comment" rows="2" placeholder="Votre commentaire"></textarea>');
					if (isset($_SESSION["connect"])) {if ($_SESSION["connect"]=="yes")	
						echo('<input class="form-control btn-success btncomment" type="submit" value="Ajouter commentaire" name="addcomment"></form>');
						else echo ('</form><button class="form-control btncomment"  data-toggle="modal" data-target="#myModal">Veuillez vous identifier pour commenter</button>');

					}
				?>
				<?php 
					/*recupiration des commentaires*/ 
					$sql='SELECT * FROM `bddproject`.`commentaire` WHERE `IDcp` = '.$_GET['id'];
					$commentresult= mysqli_query($link,$sql);
					while($row=mysqli_fetch_assoc($commentresult)){
						$username=GETNOM("nomdutilisateur","client","IDClient",$row['IDClient'],$link);
						array_push($tabcomment['comment'],$row['description']);
						array_push($tabcomment['username'],$username);
					}
					echo'<hr><h4>Reviews</h4>';
					/*affichage des commentaires*/
					for($i=0;$i<count($tabcomment['comment']);$i++){
						$result =mysqli_query($link,"SELECT `photo` FROM `bddproject`.`client` where `nomdutilisateur` = '".$tabcomment['username'][$i]."'");
						$row = mysqli_fetch_assoc($result);
						echo('<hr style="margin:10px"><div class="row">
								<div class="col-xs-1">
								  <img class="thumbnail" style="width:100%" src="ProfilPic/'.$row["photo"].'">
								</div>
								<div class="col-xs-11">
								  <h4>'.$tabcomment['username'][$i].'</h4>
								  <h5>'.$tabcomment['comment'][$i].'</h5>
								 </div>');
						echo('</div>
							');
					};
			?>
			</p>
		</div>
		<div id="menu1" class="tab-pane fade row">
		  	<p><?php
			  $sql="SELECT * FROM `bddproject`.`produit` WHERE `IDArticle` = '".$idarticle."' AND `IDcp` != '".$idcp."' LIMIT 6 ";
			  $result=mysqli_query($link,$sql);
			  while ($row = mysqli_fetch_assoc($result)){
				  $nomproduit=GETNOM('type-article','article','IDArticle',$row['IDArticle'],$link)." ".GETNOM('nom-couleur','couleur','IDCouleur',$row['IDCouleur'],$link);
				  echo("
					<div class='column'>
					  <img class='thumbnail' src='pic/text3.png'>
					  <h5>".$nomproduit."<span style='margin-left:5px;'><small>$".$row['prixvente']."</small></span></h5>
					  <p>".$row['description']."</p>
					  <a href='product.php?id=".$row['IDcp']."' class='button hollow tiny expanded'>Acheter</a>
					</div>
					
				  ");
			  }?>  
			</p>
		</div>
	</div>
</div>

</body>
</html>


    