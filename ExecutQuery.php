<?php
if(!isset($_SESSION)) { 
        session_start(); 
    }
require("CreatQuery.php"); 

/* ruturn available color for selected size */
if(isset($_GET["taille"])){
	$taille =($_GET['taille']);
	$sql="SELECT `IDCouleur` FROM `bddproject`.`produit` WHERE `taille` ='".$taille."' AND `IDcp`= '".$_GET['idcp']."' AND `quantite` > '0' AND `supprimer` = '0'";
	$result = mysqli_query($link,$sql);
	echo ("<option disabled selected value hidden='hidden'>Choisissez une couleur</option>");
	while($row = mysqli_fetch_assoc($result)) {
		$couleur = GETNOM('nom-couleur','couleur','IDCouleur',$row['IDCouleur'],$link);
		echo "<option>" .$couleur. "</option>";
	}
}

/* get result filtre area from index page*/
if (isset($_POST["filtre"])){
	if ($_POST["category"]!="") $IDArticle=GETID("IDArticle","article","type-article",$_POST["category"],$link); else $IDArticle=null;
	if ($_POST["couleur"]!="") $IDCouleur=GETID("IDCouleur","couleur","nom-couleur",$_POST["couleur"],$link); else $IDCouleur=null;
	if ($_POST["marque"]!="") $IDMarque=GETID("IDMarque","marque","nom-marque",$_POST["marque"],$link); else $IDMarque=null;	
	if (isset($_POST["taille"])) $taille=$_POST["taille"]; else $taille=null;
	if (isset($_POST["prixmax"])) $prixmax=$_POST["prixmax"]; else $prixmax=null;

	$sql=PRODUCTSELECT(NULL,$IDArticle,$IDMarque,$prixmax,$IDCouleur,$taille);
	$_SESSION['productquery']=$sql;
	header('location:filter.php');
}

/* remplir table produit id*/
function remplirtableproduit($link,$sql){
    $idcp=0;$tableproduit=array();
    $result=mysqli_query($link,$sql);	
    while($row = mysqli_fetch_assoc($result)){
        if ($idcp!=$row["IDcp"]){
            $idcp=$row["IDcp"];
            array_push( $tableproduit,$row["IDProduit"]);
        }
    }
    return $tableproduit;
}

/*fonction d information des produits*/
function infoproduit($row,$link){
	$info=GETNOM('type-article','article','IDArticle',$row['IDArticle'],$link).' '.GETNOM('nom-marque','marque','IDMarque',$row['IDMarque'],$link);
	return $info;
}

/* get result search area from index page*/
if (isset($_POST['search'])){
$sqlsearch=CreatQuerySEARCH($_POST['searchtext']);
$_SESSION['productquery']=$sqlsearch;
header('location:filter');
}

/* add prodect comment from produit page*/
if (isset($_POST["addcomment"])){
	$comment=$_POST["comment"];
	$idcp=$_GET["idcp"];
	$idclient=$_SESSION["idclient"];
	if ($comment!=""){
		$sql="INSERT INTO `bddproject`.`commentaire` (`IDCommentaire`, `IDClient`, `description`, `IDcp`) VALUES (NULL, '".$idclient."', '".$comment."', '".$idcp."')";
		mysqli_query($link,$sql);			
	}
	header("location:http://localhost".$_SESSION['returnurl']);	
}

/* log in*/
if ((isset($_POST["email"]))&& (isset($_POST["password"]))&& (isset($_POST["login"]))){
	$email=$_POST["email"];
	$password=$_POST["password"];
	$sql= LOGINSQLSELECT($password,$email);
	$result =mysqli_query($link,$sql);
	if (mysqli_num_rows($result)==0)  $_SESSION["connect"]="faute"; 
	else while($row = mysqli_fetch_assoc($result)){ 
			$_SESSION["connect"]="yes";
			$_SESSION["idclient"]=$row['IDClient'];
			$_SESSION["fname"]=$row['nom'];
			$_SESSION["lname"]=$row['prenom'];
			$_SESSION["uname"]=$row['nomdutilisateur'];
			$_SESSION["photo"]=$row['photo'];
	}

			header('location:http://localhost'.$_SESSION['returnurl']);
			
}

/* log out*/
if (isset($_POST["deconnexion"])){
	unset($_SESSION["connect"]);
	unset($_SESSION["idclient"]);
	unset($_SESSION["fname"]);
	unset($_SESSION["lname"]);
	unset($_SESSION["uname"]);
	unset($_SESSION["facture"]);
	unset($_SESSION["missedproduct"]);

	header('location:http://localhost'. $_SESSION['returnurl'] );
	exit();
}

/* inscription*/
if (isset($_POST["inscription"])){
   
        if($_POST["password1"]!=$_POST["password2"]) $_SESSION["motdepasseereur"]=true;
    	
        $exist=false;
    
        $sql ="SELECT `nomdutilisateur` FROM `bddproject`.`client` WHERE `nomdutilisateur`='".$_POST['nomuser']."'";
        $result=mysqli_query($link,$sql);
        if(mysqli_num_rows($result)>0) {$_SESSION["usernameexist"]=true;$exist=true;}
    
        $sql ="SELECT `email` FROM `bddproject`.`client` WHERE `email`='".$_POST['email']."'";
        $result=mysqli_query($link,$sql);
        if(mysqli_num_rows($result)>0) {$_SESSION["emailexist"]=true;$exist=true;}
	
    	$today=getdate(); $d = $today['mday']; $m = $today['mon']; $y = $today['year']; $today=$y.'-'.$m.'-'.$d;
        if(!$exist){
            if($_POST["sexe"]=="Mr.") $sexe="h" ; else $sexe="m"; ($sexe=="h")?$photo="profilH.png":$photo="profilM.png";
            $sql="INSERT INTO `bddproject`.`client` (`IDClient`, `nom`, `prenom`, `nomdutilisateur`, `motdepasse`, `sexe`, `datedenaissance`, `email`, `adresse`, `codepostal`, `ville`, `ntelephone`, `datedecreation`,`codebarreCF`,`point`,`photo`) VALUES (NULL, '".$_POST["nom"]."', '".$_POST["prenom"]."', '".$_POST["nomuser"]."', '".$_POST["password1"]."', '".$sexe."', '".$_POST["date"]."', '".$_POST["email"]."', '".$_POST["adresse"]."', '".$_POST["codepostal"]."', '".$_POST["ville"]."', '".$_POST["tel"]."', '".$today."', NULL, NULL,'".$photo."');" ;
            $_SESSION["etetinscription"]=mysqli_query($link,$sql);
            
			$sql= LOGINSQLSELECT($_POST["password1"],$_POST["email"]);
			$result =mysqli_query($link,$sql);
			if (mysqli_num_rows($result)==0)  $_SESSION["connect"]="faute"; 
			else while($row = mysqli_fetch_assoc($result)){ 
				$_SESSION["connect"]="yes";
				$_SESSION["idclient"]=$row['IDClient'];
				$_SESSION["fname"]=$row['nom'];
				$_SESSION["lname"]=$row['prenom'];
				$_SESSION["uname"]=$row['nomdutilisateur'];
				$_SESSION["photo"]=$row['photo'];
			}
		}
    header('location:http://localhost'.$_SESSION['returnurl']);
	exit();
}

/* info modification*/
if (isset($_POST["modification"])){ 	
        $exist=false;
        $sql ="SELECT `nomdutilisateur` FROM `bddproject`.`client` WHERE `nomdutilisateur`='".$_POST['nomuser']."' AND `IDClient` != '".$_SESSION['idclient']."' ";
        $result=mysqli_query($link,$sql);
        if(mysqli_num_rows($result)>0) {$_SESSION["usernameexist2"]=true;$exist=true;}
	
        if(!$exist){
            if($_POST["sexe"]=="Mr.") $sexe="h" ; else $sexe="m"; ($sexe=="h")?$photo="profilH.png":$photo="profilM.png";
			
			$sql="UPDATE `bddproject`.`client` SET `nom` = '".$_POST["nom"]."', `prenom` = '".$_POST["prenom"]."', `nomdutilisateur` = '".$_POST["nomuser"]."', `sexe` = '".$sexe."', `datedenaissance` = '".$_POST["date"]."', `adresse` = '".$_POST["adresse"]."', `codepostal` = '".$_POST["codepostal"]."', `ville` = '".$_POST["ville"]."', `ntelephone` = '".$_POST["tel"]."' WHERE `client`.`IDClient` = '".$_SESSION["idclient"]."' "; 
			
            if(mysqli_query($link,$sql)){
				$_SESSION["fname"]=$_POST["nom"];
				$_SESSION["lname"]=$_POST["prenom"];
				$_SESSION["uname"]=$_POST["nomuser"];
				$_SESSION["etatmodification"]=true;
			}
       }
    header('location:profil.php' );
	exit();
}

/* modification password */
if (isset($_POST['modificationpassword'])){
	$ancienMDP=false;
	$sql ="SELECT `motdepasse` FROM `bddproject`.`client` WHERE `IDClient` ='".$_SESSION['idclient']."'";
    $result=mysqli_query($link,$sql);
	while($row=mysqli_fetch_assoc($result)){
		if ($_POST['password0']==$row['motdepasse']) $ancienMDP=true; else $_SESSION["erreurancienmotdepass"]=true;
	}
	if ($_POST['password1']==$_POST['password2']){
		if ($ancienMDP){
			$sql ="UPDATE `client` SET `motdepasse` = '".$_POST['password2']."' WHERE `client`.`IDClient` = '".$_SESSION['idclient']."'; ";
    		mysqli_query($link,$sql);
		}
	} else $_SESSION["differentpassword"]=true;
	header('location:profil.php');
	exit();
}

/*return nombre couleur d' article*/
function nbcol($idcp,$link){
	$result=mysqli_query($link,'SELECT `IDCouleur` FROM `bddproject`.`produit` WHERE `IDcp` = "'.$idcp.'" ');
	$tabcol=array();
	while($row=mysqli_fetch_assoc($result)){
		if(!in_array($row['IDCouleur'],$tabcol)) array_push($tabcol,$row['IDCouleur']);
	}
	return count ($tabcol);
}
/*return nombre taille d' article*/
function nbtaille($idcp,$link){
	$result=mysqli_query($link,'SELECT `taille` FROM `bddproject`.`produit` WHERE `IDcp` = "'.$idcp.'" ');
	$tabtaille=array();
	while($row=mysqli_fetch_assoc($result)){
		if(!in_array($row['taille'],$tabtaille)) array_push($tabtaille,$row['taille']);
	}
	return count ($tabtaille);
}

?>
