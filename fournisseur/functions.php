<?php 
function redirect($url) {
    ob_start();
    header('Location: '.$url);
    ob_end_flush();
    die();
}
function find_fournisseur_by_username($Username){
	global $link;

	$safe_username = mysqli_real_escape_string($link,$Username);

	$query = "SELECT * ";
	$query .= "FROM `bddproject`.`fournisseur` ";
	$query .= "WHERE nomdutilisateur = '{$safe_username}' ";
	$query .= "LIMIT 1";
	$fournisseur_set = mysqli_query($link,$query);
	
	if ($fournisseur = mysqli_fetch_assoc($fournisseur_set)){
		return $fournisseur;
	}else { 
		return null;}
}
function attempt_login($Username,$Password){
	$fournisseur = find_fournisseur_by_username($Username);
	if ($fournisseur && ($fournisseur["supprimerM"]==0)){
		//fournisseur found .
        if ($fournisseur["motdepasse"]==$Password){
        	return $fournisseur;}else {
                $_SESSION["message"] = "username/password n'est pas correct";
        		return false ;
        	}

	}else{
		//fournisseur not found .
		$_SESSION["message"] = "username/password n'est pas correct";
        return false;

	}
}

 ?>