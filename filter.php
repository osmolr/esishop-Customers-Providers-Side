<?php
if(!isset($_SESSION))  {  session_start();  } 	
?>

<html>
<head>	
<title>The filter page</title>
<link href="css/filter.css" rel="stylesheet">
</head>
	
<body>
 
<?php
include('ExecutQuery.php');
include('navbar.php');
if(isset($_GET['article'])){
	$_SESSION['productquery']="SELECT * FROM `bddproject`.`produit` NATURAL JOIN `article` WHERE `type-article` ='".$_GET['article']."' AND `supprimer` = '0' AND `quantite` > '0'";
}

if((isset($_GET['f']))||(!isset($_SESSION['productquery']))){
	$_SESSION['productquery']="SELECT * FROM `bddproject`.`produit` WHERE `supprimer` = '0' AND `quantite` > '0' ";
}
?>
	<!--filtre sidebare---------------------------------------------------------------------------------------->
	<div class="col-sm-2 col-md-2" >
		<div style="margin-top:10px;box-shadow: 0 1px 2px rgba(0,0,0,0.3), 0 1px 2px rgba(0,0,0,0.3); padding:10px;background-color:#fff">
			<form method="post" action="ExecutQuery.php">
				<h4>Filtre des Produits</h4>
				<label>catégorie :
				</label>
				<select class="form-control selecto" name="category">
					<option hidden="hidden" disabled selected value>Sélectionner une Catégorie</option>
					<?php
						$sql = PRODUCTMARQUE("type-article","IDArticle","article");
						$result = mysqli_query($link,$sql);
						while($row = mysqli_fetch_assoc($result)) echo ("<option>".$row['type-article']."</option>");
					?>
				</select>
				<label>Marque :
				</label>
				<select class="form-control selecto" name="marque">
					<option hidden="hidden" disabled selected value>Sélectionner une Marque</option>
					<?php
								$sql = PRODUCTMARQUE("nom-marque","IDMarque","marque");
								$result = mysqli_query($link,$sql);
								while($row = mysqli_fetch_assoc($result)) echo ("<option>".$row['nom-marque']."</option>");
								?>
				</select>
				<label>Couleur :
				</label>
				<select class="form-control selecto" name="couleur">
					<option hidden="hidden" disabled selected value>Sélectionner une Couleur</option>
					<?php
								$sql = PRODUCTMARQUE("nom-couleur","IDCouleur","couleur");
								$result = mysqli_query($link,$sql);
								while($row = mysqli_fetch_assoc($result))echo ("<option>".$row['nom-couleur']."</option>");
								?>
				</select>
				<label>Taille :
				</label>
				<select class="form-control selecto" name="taille">
					<option hidden="hidden" disabled selected value>Sélectionner une Taille</option>
					<option>s</option>
					<option>m</option>
					<option>l</option>
					<option>xl</option>
					<option>xxl</option>
				</select>
				<label>Prix Max (DA):
				</label>
				<input type="number" class="form-control selecto" min="1" value="" placeholder="DA" name="prixmax">
				<input type="submit" class="form-control btn-info selecto" value="Filtrer" name="filtre">
			</form>
				<hr>
			<form method="post" action="filter.php?f=all">
				<input type="submit" class="form-control btn-info selecto" value="Afficher tous">
			</form>
		</div>	
    </div>
	<!-- product nav ----------------------------------------------------------------------------------------->
	<div class="col-sm-10 col-md-10 main" >
		<!-- CARDS ------------------------------------------------------------------------------------------->
		<div class="row placeholders">
			<?php
				$_session["tableproduit"]=array();
				$_session["tableproduit"]=remplirtableproduit($link,$_SESSION['productquery']); 
				if (isset($_GET["p"])) $p=$_GET["p"]; else $p=1;
				for($i=(15*$p-15);$i<15*$p;$i++){
					if ($i==sizeof($_session["tableproduit"])) break;
					$result=mysqli_query($link,'SELECT * FROM `bddproject`.`produit` NATURAL JOIN `couleur` WHERE `IDProduit`='.$_session["tableproduit"][$i].' AND `supprimer` = \'0\' AND `quantite` > \'0\'');	
					while($row = mysqli_fetch_assoc($result)){
					$info = infoproduit($row,$link);
					$nbcol=nbcol($row['IDcp'],$link);
					$nbtaille=nbtaille($row['IDcp'],$link);
					if ($row['remise']!=0) $remise=$row['remise'].'% OFF'; else $remise='';
					echo(' 
						<div class="col-md-3 col-xs-6" style="padding:0 10px">
							<div class="divproduit">
								<div class="col-item">
									<div class="photo">
										<a href="product.php?id='.$row['IDcp'].'&name='.$info.'">
										<img src="uploads/'.$row['img'].'" alt="a" /></a>
									</div>
									<div>
										<div class="row">
											<div>
												<p class="info">'.$info.' ('.$row['taille'].')</p>
												<p class="prix">'.$row["prixvente"].' DA <span style="color:#f00; font-size:12px">'.$remise.'</span></p>
												<p class="autre">(<span>'.$nbcol.'</span> couleurs-<span>'.$nbtaille.'</span> tailles) disponible</p>
											</div>
										</div>
											<div class="row">
												<div class="col-lg-6">
													<a href="cart.php?action=ajout&IDcp='.$row['IDcp'].'&couleur='.$row['nom-couleur'].'&taille='.$row['taille'].'&quantite=1">
													<button id="addtocart" align="left" class="btn-warning" style="color:black; margin: 0px;"><b>Add to cart</b></button></a>
												</div>
													<div class="col-lg-6">
													<a align="right" style="float: right; margin-right: 4px;font-weight:600" 	href="product.php?id='.$row['IDcp'].'&name='.$info.'">Détails <span class="glyphicon glyphicon-chevron-right"></span>
													</a>
												</div>
											</div>
									</div>
								</div>
							</div>
						</div>
						'); 
					}
				}
			?>
		</div>  
	</div>
	<!-- PAGINATION  ------------------------------------------------------------------------------------->
		<?php
			$nbpage=intval(sizeof($_session["tableproduit"])/15);
			if ($nbpage*15!=sizeof($_session["tableproduit"]))	$nbpage++;
		?>
	<div class="row">
		<center>
			<nav aria-label="Page navigation">
				<ul class="pagination">
					<hr>
					<?php (($p-3)<1)?$class="pagedisable" :$class="";
					echo('<li >
					<a class="'.$class.'" href="filter.php?p='.($p-3).'">
					<span aria-hidden="true">&laquo;</span>
					</a>
					</li>'); ?>
					<?php (($p-1)<1)?$class="pagedisable" :$class=""; 
					echo('<li><a class="'.$class.'" href="filter.php?p='.($p-1).'">'.($p-1).'</a></li>'); ?>

					<?php echo('<li ><a class="pageactive" href="filter.php?p='.$p.'">'.$p.'</a></li>'); ?>

					<?php (($p+1)>$nbpage)?$class="pagedisable" :$class=""; 
					echo('<li><a class="'.$class.'" href="filter.php?p='.($p+1).'">'.($p+1).'</a></li>'); ?>

					<?php (($p+2)>$nbpage)?$class="pagedisable" :$class="";
					echo('<li><a class="'.$class.'" href="filter.php?p='.($p+2).'">'.($p+2).'</a></li>'); ?>

					<?php (($p+3)>$nbpage)?$class="pagedisable" :$class=""; 
					echo('<li >
					<a class="'.$class.'" href="filter.php?p='.($p+3).'">
					<span aria-hidden="true">&raquo;</span>
					</a>
					</li>'); ?>
				</ul>
			</nav>
		</center>
	</div>
	<!-- FOOTER --> 
	<?php include('footer.php') ?>
</body>
</html>