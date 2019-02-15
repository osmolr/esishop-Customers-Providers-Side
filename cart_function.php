<?php
/**
 * Verifie si le cart existe, le créé sinon
 * @return booleen
 */
function creationcart(){
   if (!isset($_SESSION['cart'])){
      $_SESSION['cart']=array();
      $_SESSION['cart']['IDProduit'] = array();
	  $_SESSION['cart']['IDArticle'] = array();
	  $_SESSION['cart']['IDMarque'] = array();
	  $_SESSION['cart']['IDCouleur'] = array();
	  $_SESSION['cart']['taille'] = array();
	  $_SESSION['cart']['quantite'] = array();
	  $_SESSION['cart']['prix'] = array();
	  $_SESSION['cart']['prixtotal'] = array();
	  $_SESSION['cart']['remise'] = array();
      $_SESSION['cart']['TVA'] = array();
      $_SESSION['cart']['image'] = array();

   }
   return true;
}

/**
 * Verifie si la facture existe, le créé sinon
 * @return booleen
 */
function creationfacture(){
   if (!isset($_SESSION['facture'])){
      $_SESSION['facture']=array();
      $_SESSION['facture']['IDProduit'] = array();
	  $_SESSION['facture']['quantite'] = array();
	  $_SESSION['facture']['prixtotal'] = array();
	  $_SESSION['facture']['IDArticle'] = array();
	  $_SESSION['facture']['IDCouleur'] = array();
	  $_SESSION['facture']['taille'] = array();
	  $_SESSION['facture']['montanttotal']=0;
   }
   return true;
}

/**
 * Verifie si le tableau des produits manques existe, le créé sinon
 * @return booleen
 */
function creationmissedproduct(){
   if (!isset($_SESSION['missedproduct'])){
      $_SESSION['missedproduct']=array();
      $_SESSION['missedproduct']['IDProduit'] = array();
	  $_SESSION['missedproduct']['IDArticle'] = array();
	  $_SESSION['missedproduct']['IDCouleur'] = array();
	  $_SESSION['missedproduct']['quantite'] = array();
	  $_SESSION['missedproduct']['taille'] = array();
   }
   return true;
}

/**
 * fonction d information des produits
 * @return String
 */
function toString($i,$table,$link){
	$info=GETNOM('type-article','article','IDArticle',$table['IDArticle'][$i],$link).' '.GETNOM('nom-couleur','couleur','IDCouleur',$table['IDCouleur'][$i],$link).' Taille ('.$table['taille'][$i].')';
	return $info;
}

/**
 * Ajoute un article dans le cart
 * @return void
 */
function ajouterArticle($IDProduit,$IDArticle,$IDMarque,$IDCouleur,$taille,$quantite,$prix,$remise,$TVA,$image){

   //Si le cart existe
   if (creationcart())
   {
      //Si le produit existe déjà on ajoute seulement la quantite
      $positionProduit = array_search($IDProduit,  $_SESSION['cart']['IDProduit']);

      if ($positionProduit !== false)
      {
         $_SESSION['cart']['quantite'][$positionProduit] += $quantite ;
		 $prix_total=prix_total($_SESSION['cart']['prix'][$positionProduit],$_SESSION['cart']['quantite'][$positionProduit],$_SESSION['cart']['remise'][$positionProduit],$_SESSION['cart']['TVA'][$positionProduit]);
		 $_SESSION['cart']['prixtotal'][$positionProduit]=$prix_total;	
      }
      else
      {
         //Sinon on ajoute le produit
         array_push( $_SESSION['cart']['IDProduit'],$IDProduit);
         array_push( $_SESSION['cart']['IDArticle'],$IDArticle);
         array_push( $_SESSION['cart']['IDMarque'],$IDMarque);
		 array_push( $_SESSION['cart']['IDCouleur'],$IDCouleur);
		 array_push( $_SESSION['cart']['taille'],$taille);
		 array_push( $_SESSION['cart']['quantite'],$quantite);
		 array_push( $_SESSION['cart']['prix'],$prix);
		 array_push( $_SESSION['cart']['remise'],$remise);
		 array_push( $_SESSION['cart']['TVA'],$TVA);
		 array_push( $_SESSION['cart']['image'],$image);
		 $positionProduit = array_search($IDProduit,  $_SESSION['cart']['IDProduit']);
		 $prix_total=prix_total($_SESSION['cart']['prix'][$positionProduit],$_SESSION['cart']['quantite'][$positionProduit],$_SESSION['cart']['remise'][$positionProduit],$_SESSION['cart']['TVA'][$positionProduit]);
		 $_SESSION['cart']['prixtotal'][$positionProduit]=$prix_total;
      }
   }
   else
   echo "Un problème est survenu veuillez contacter l'administrateur du site.";
}

/**
 * Modifie la quantite d'un article
 * @return void
 */
function modifierQTeArticle($IDProduit,$quantite){
   //Si le cart éxiste
   if (creationcart())
   {	
      //Si la quantite est positive on modifie sinon on supprime l'article
      if ($quantite > 0)
      {
         //Recharche du produit dans le cart
         $positionProduit = array_search($IDProduit,  $_SESSION['cart']['IDProduit']);

         if ($positionProduit !== false)
         {
            $_SESSION['cart']['quantite'][$positionProduit] = $quantite ;
			$prix_total=prix_total($_SESSION['cart']['prix'][$positionProduit],$_SESSION['cart']['quantite'][$positionProduit],$_SESSION['cart']['remise'][$positionProduit],$_SESSION['cart']['TVA'][$positionProduit]);
		 	$_SESSION['cart']['prixtotal'][$positionProduit]=$prix_total;

         }
      }
      else supprimerArticle($IDProduit);
   }
   else
   echo "Un problème est survenu veuillez contacter l'administrateur du site.";
}

/**
 * Supprime un article du cart
 * @return unknown_type
 */
function supprimerArticle($IDProduit){
	//Si le cart existe
	if (creationcart() )
		{
		//Nous allons passer par un cart temporaire
	  	$tmp=array();
      	$tmp['IDProduit'] = array();
	  	$tmp['IDArticle'] = array();
	  	$tmp['IDMarque'] = array();
	  	$tmp['IDCouleur'] = array();
	  	$tmp['taille'] = array();
	  	$tmp['quantite'] = array();
	  	$tmp['prix'] = array();
	  	$tmp['remise'] = array();
      	$tmp['TVA'] = array();
		$tmp['image'] = array();
		$tmp['prixtotal'] = array();
		
      	for($i = 0; $i < count($_SESSION['cart']['IDProduit']); $i++)
      		{
         	if ($_SESSION['cart']['IDProduit'][$i] !== $IDProduit)
         		{
            	array_push( $tmp['IDProduit'],$_SESSION['cart']['IDProduit'][$i]);
				array_push( $tmp['IDArticle'],$_SESSION['cart']['IDArticle'][$i]);
				array_push( $tmp['IDCouleur'],$_SESSION['cart']['IDCouleur'][$i]);
				array_push( $tmp['IDMarque'],$_SESSION['cart']['IDMarque'][$i]);
				array_push( $tmp['quantite'],$_SESSION['cart']['quantite'][$i]);
				array_push( $tmp['remise'],$_SESSION['cart']['remise'][$i]);
            	array_push( $tmp['taille'],$_SESSION['cart']['taille'][$i]);
            	array_push( $tmp['image'],$_SESSION['cart']['image'][$i]);
				array_push( $tmp['prix'],$_SESSION['cart']['prix'][$i]);
				array_push( $tmp['TVA'],$_SESSION['cart']['TVA'][$i]);
				array_push( $tmp['prixtotal'],$_SESSION['cart']['prixtotal'][$i]);
         		}

      		}	
      	//On remplace le cart en session par notre cart temporaire à jour
      	$_SESSION['cart'] =  $tmp;
      	//On efface notre cart temporaire
      	unset($tmp);
	}else echo "Un problème est survenu veuillez contacter l'administrateur du site.";
}

/**
 * Montant total du cart
 * @return int
 */
function prix_total($prix,$quantite,$remise,$TVA){
   $prix=$prix-($prix*$remise)/100;
   $prix=$prix+($prix*$TVA)/100;
   $prix=$prix*$quantite;
   return $prix;
}

/**
 * return le montant des achats
 * @return double
 */
function MontantGlobal(){
   $total=0;
   for($i = 0; $i < count($_SESSION['cart']['prixtotal']); $i++)
   {
	 $total +=$_SESSION['cart']['prixtotal'][$i];
   }
   return $total;
}

/**
 * Fonction de suppression du cart
 * @return void
 */
function supprimecart(){

   unset($_SESSION['cart']);
}

/**
 * Compte le nombre d'articles différents dans le cart
 * @return int
 */
function compterArticles(){
   if (isset($_SESSION['cart']))
   return count($_SESSION['cart']['IDProduit']);
   else
   return 0;
}

/**
 * creation des facture et modification de la base + notification
 */
function confirmation($link){
	/*initialisation*/
	if (isset($_SESSION['facture'])) unset($_SESSION['facture']);
	if (isset($_SESSION['missedproduct'])) unset($_SESSION['missedproduct']);
	$facture=false;
	if (creationfacture()) if (creationmissedproduct()) {
		for ($i=0; $i<count($_SESSION['cart']['IDProduit']);$i++){
			$sql="SELECT * FROM `bddproject`.`produit` WHERE `IDProduit`='".$_SESSION['cart']['IDProduit'][$i]."' ";
			$result=mysqli_query($link,$sql);
			while($row =mysqli_fetch_array($result)){
				if ($_SESSION['cart']['quantite'][$i]<=$row['quantite']){
					array_push( $_SESSION['facture']['IDProduit'],$_SESSION['cart']['IDProduit'][$i]);
					array_push( $_SESSION['facture']['quantite'] ,$_SESSION['cart']['quantite'][$i] );
					array_push( $_SESSION['facture']['prixtotal'],$_SESSION['cart']['prixtotal'][$i]);
					array_push( $_SESSION['facture']['IDArticle'],$_SESSION['cart']['IDArticle'][$i]);
					array_push( $_SESSION['facture']['IDCouleur'],$_SESSION['cart']['IDCouleur'][$i]);
					array_push( $_SESSION['facture']['taille'],$_SESSION['cart']['taille'][$i]);
					/*modification bdd*/
					$sql="UPDATE `bddproject`.`produit` SET `quantite` = '".($row['quantite']-$_SESSION['cart']['quantite'][$i])."' WHERE `produit`.`IDProduit` ='".$_SESSION['cart']['IDProduit'][$i]."'; ";
					mysqli_query($link,$sql);
					/*ajouter une notification*/
					if(($row['quantite']-$_SESSION['cart']['quantite'][$i])<=$row['quantitemin']){
						$sql="INSERT INTO `bddproject`.`notification` (`IDNotification`, `IDFournisseur`, `IDProduit`, `titre`, `description`) VALUES (NULL, '1', '".$_SESSION['cart']['IDProduit'][$i]."', 'qt min', 'le produit atteindre la quantite minimum');";
						mysqli_query($link,$sql);
					}
					$facture=true;
				} else if ($row['quantite']>0){
					/*remplire la table de facture */
					array_push( $_SESSION['facture']['IDProduit'],$_SESSION['cart']['IDProduit'][$i]);
					array_push( $_SESSION['facture']['quantite'],$_SESSION['cart']['quantite'][$i]);
					array_push( $_SESSION['facture']['prixtotal'],prix_total($_SESSION['cart']['prix'][$i],$row['quantite'],$_SESSION['cart']['remise'][$i],$_SESSION['cart']['TVA'][$i]));
					array_push( $_SESSION['facture']['IDArticle'],$_SESSION['cart']['IDArticle'][$i]);
					array_push( $_SESSION['facture']['IDCouleur'],$_SESSION['cart']['IDCouleur'][$i]);
					array_push( $_SESSION['facture']['taille'],$_SESSION['cart']['taille'][$i]);
					/*remplire la table missedProduct */
					array_push( $_SESSION['missedproduct']['IDProduit'] ,$_SESSION['cart']['IDProduit'][$i]);
					array_push( $_SESSION['missedproduct']['IDArticle'] ,$_SESSION['cart']['IDArticle'][$i]);
					array_push( $_SESSION['missedproduct']['IDCouleur'] ,$_SESSION['cart']['IDCouleur'][$i]);
					array_push( $_SESSION['missedproduct']['taille'] ,$_SESSION['cart']['taille'][$i]);
					array_push( $_SESSION['missedproduct']['quantite'] ,$_SESSION['cart']['quantite'][$i]-$row['quantite']);
					/*modification bdd*/
					$sql="UPDATE `bddproject`.`produit` SET `quantite` = '0' WHERE `produit`.`IDProduit` ='".$_SESSION['cart']['IDProduit'][$i]."'; ";
					/*ajouter une notification*/
					mysqli_query($link,$sql);
					$today=getdate(); $d = $today['mday']; $m = $today['mon']; $y = $today['year']; $today=$y.'-'.$m.'-'.$d;
					$sql="INSERT INTO `bddproject`.`notification` (`IDNotification`, `IDFournisseur`, `IDProduit`, `titre`, `description`) VALUES (NULL, '1', '".$_SESSION['cart']['IDProduit'][$i]."', 'qt min','".$today."', 'le produit atteindre la quantite minimum');";
					mysqli_query($link,$sql);
					$facture=true;
				} else{
					/*remplire la table missedProduct */
					array_push( $_SESSION['missedproduct']['IDProduit'] ,$_SESSION['cart']['IDProduit'][$i]);
					array_push( $_SESSION['missedproduct']['IDArticle'] ,$_SESSION['cart']['IDArticle'][$i]);
					array_push( $_SESSION['missedproduct']['IDCouleur'] ,$_SESSION['cart']['IDCouleur'][$i]);
					array_push( $_SESSION['missedproduct']['taille'] ,$_SESSION['cart']['taille'][$i]);
					array_push( $_SESSION['missedproduct']['quantite'] ,$_SESSION['cart']['quantite'][$i]);
					/*ajouter une notification*/
					mysqli_query($link,$sql);
					$sql="INSERT INTO `bddproject`.`notification` (`IDNotification`, `IDFournisseur`, `IDProduit`, `titre`, `date`, `description`, `vu`) VALUES (NULL, '1', '".$_SESSION['cart']['IDProduit'][$i]."', 'qt min', 'le produit atteindre la quantite minimum', '0');";
					mysqli_query($link,$sql);
				}
			}
		}
		/*insertion de la facture dans la base de donnees*/
		if($facture){
			/*recupiration du dernier IDcp*/
			$sql="SELECT `IDcf` FROM `bddproject`.`facture` WHERE `IDcf` != '-1' "; $result=mysqli_query($link,$sql); while($row=mysqli_fetch_assoc($result)) $IDcf=$row['IDcf']+1;
			if($IDcf==NULL) $IDcf=1;
			/*recupiration de la date*/
			$today=getdate(); $d = $today['mday']; $m = $today['mon']; $y = $today['year']; $today=$y.'-'.$m.'-'.$d;
			/*insertion de la facture*/
			$total=0;
			for ($i=0; $i<count($_SESSION['facture']['IDProduit']);$i++){
				$sql="INSERT INTO `bddproject`.`facture` (`IDFacture`, `IDcf`, `IDClient`, `IDProduit`, `quantite`, `date`, `prixtotal`, `Etat`) VALUES (NULL, '".$IDcf."', '".$_SESSION['idclient']."', '".$_SESSION['facture']['IDProduit'][$i]."', '".$_SESSION['facture']['quantite'][$i]."', '".$today."', '".$_SESSION['facture']['prixtotal'][$i]."', 'vente');";
				mysqli_query($link,$sql);
				$total += $_SESSION['facture']['prixtotal'][$i];
			}
			/*ajout des point a la carte de fidelite*/
				$sql='SELECT `codebarreCF`,`point` FROM `bddproject`.`client` WHERE `IDClient` = '.$_SESSION['idclient'];
				$result= mysqli_query($link,$sql);
				$row=mysqli_fetch_assoc($result);
				if ($row['codebarreCF']!=null){
					$point=(($total/1000)+$row['point']);
					$sql='UPDATE `client` SET `point` = \''.$point.'\' WHERE `client`.`IDClient` = '.$_SESSION['idclient'].'; ';
					mysqli_query($link,$sql);
				}
			/*suppression de la facture temporaire*/
			unset($_SESSION['cart']);
		}
	}
}

/**
 * insertion des produit manque
 */
function savemissedproduct($i,$link){
	$today=getdate(); $d = $today['mday']; $m = $today['mon']; $y = $today['year']; $today=$y.'-'.$m.'-'.$d;
	$sql="INSERT INTO `bddproject`.`facture` (`IDFacture`, `IDcf`, `IDClient`, `IDProduit`, `quantite`, `date`, `prixtotal`, `Etat`) VALUES (NULL, '-1', '".$_SESSION['IDClient']."', '".$_SESSION['missedproduct']['IDProduit'][$i]."','".$_SESSION['missedproduct']['quantite'][$i]."', '".$today."', '0', 'manque');";
	mysqli_query($link,$sql);
	array_splice($_SESSION['missedproduct']['IDProduit'], $i, 1);
	array_splice($_SESSION['missedproduct']['IDArticle'], $i, 1);
	array_splice($_SESSION['missedproduct']['IDCouleur'], $i, 1);
	array_splice($_SESSION['missedproduct']['quantite'], $i, 1);
	array_splice($_SESSION['missedproduct']['taille'], $i, 1);
}
?>