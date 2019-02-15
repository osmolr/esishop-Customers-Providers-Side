<?php
include("ConnectToDB.php");

function LOGINSQLSELECT($password,$email){
    $sql= "SELECT * FROM `bddproject`.`client` WHERE motdepasse = '".$password."' AND email = '".$email."'";
    return $sql;
	}
	
function PRODUCTSELECT($IDcp,$IDArticle,$IDMarque,$prixmax,$IDCouleur,$taille){
	$sql="SELECT * FROM `bddproject`.`produit` NATURAL JOIN `couleur`";
	$v=false;
	if (($IDcp!= null )&&($v==false)) {$sql.=" WHERE IDcp = '".$IDcp."' ";$v=true;} 
				else if (($IDcp!= null )&&($v=true)) $sql.=" AND IDcp = '".$IDcp."' ";
	if (($IDArticle!= null )&&($v==false)) {$sql.=" WHERE IDArticle = '".$IDArticle."' ";$v=true;} 
				else if (($IDArticle!= null )&&($v=true)) $sql.=" AND IDArticle = '".$IDArticle."' ";
	if (($IDMarque!= null )&&($v==false)) {$sql.=" WHERE IDMarque = '".$IDMarque."' ";$v=true;} 
				else if (($IDMarque!= null )&&($v=true))  $sql.=" AND IDMarque = '".$IDMarque."' ";
	if (($prixmax!= null )&&($v==false)) {$sql.=" WHERE prixvente <= '".$prixmax."' ";$v=true;} 
				else if (($prixmax!= null )&&($v=true))  $sql.=" AND prixvente <= '".$prixmax."' ";
	if (($IDCouleur!= null )&&($v==false)) {$sql.=" WHERE IDCouleur = '".$IDCouleur."' ";$v=true;} 
				else if (($IDCouleur!= null )&&($v=true))  $sql.=" AND IDCouleur = '".$IDCouleur."' ";
	if (($taille!= null )&&($v==false)) {$sql.=" WHERE taille = '".$taille."' ";$v=true;} 
				else if (($taille!= null )&&($v=true))  $sql.=" AND taille = '".$taille."' ";
	if ($v==false) {$sql.=" WHERE `supprimer` = '0' AND `quantite` > '0'"; $v=true;}
				else {$sql.=" AND `supprimer` = '0' AND `quantite` > '0'";}
	return $sql;
	}
	
function PRODUCTMARQUE($nom,$id,$tableau){
	$sql = "SELECT `".$nom."`,`".$id."` FROM `bddproject`.`".$tableau."` ";
	return $sql;
	}

function GETID($id,$tableau,$nom,$var,$link){
	$sql = "select `".$id."` from `bddproject`.`".$tableau."` where `".$nom."` = '".$var."'";
				$result=mysqli_query($link,$sql);
				while($row=mysqli_fetch_assoc($result)) $ID=$row[$id];
	echo $sql;
	return $ID;
	}
	
function GETNOM($nom,$tableau,$id,$var,$link){
				$sql = "select`".$nom."` from `bddproject`.`".$tableau."` where `".$id."` = '".$var."'";
				$result=mysqli_query($link,$sql);
				while($row=mysqli_fetch_assoc($result)) $nom=$row[$nom];
				return $nom;
	}

function CreatQuerySEARCH($searchtext){
	$sql = "SELECT * FROM `produit` NATURAL JOIN `article` NATURAL JOIN `couleur` NATURAL JOIN `marque` WHERE ";
	$myArray=array();
	$myArray = explode(',', $searchtext);
	for($i=0;$i<count($myArray);$i++){
		 $sql .= "(`type-article` LIKE '%".$myArray[$i]."%' OR `nom-marque` LIKE '%".$myArray[$i]."%' OR `nom-couleur` LIKE '%".$myArray[$i]."%' ) ";
		 if ($i+1< count ($myArray)) $sql .= "AND" ;
	}
	$sql .= "AND `supprimer` = '0' AND `quantite` > '0'" ;
	return $sql;
}
?>