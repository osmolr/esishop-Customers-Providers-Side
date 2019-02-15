<?php 
session_start();
if(!isset($_SESSION["IDFournisseur"])||($_SESSION["IDFournisseur"]===NULL)) header("location:index.php");
include('../ConnectToDB.php');
//error test 
if (mysqli_connect_errno()){
	die("database link failed".mysqli_connect_error()."(".mysqli_errno().")");
}
 ?>
 <?php 
    $Etat =($_GET['Etat']);
    if ($Etat=="*") {
    	$sql=" SELECT * FROM `achat-commande`, `produit`,`marque`,`article`,`couleur`,`fournisseur` WHERE `achat-commande`.`IDProduit`=`produit`.`IDProduit` AND `produit`.`IDMarque`=`marque`.`IDMarque` AND `produit`.`IDArticle`=`article`.`IDArticle` and `produit`.`IDCouleur`=`couleur`.`IDCouleur` AND ";
	    $sql.= "`achat-commande`.`IDFournisseur`=".$_SESSION["IDFournisseur"]." AND `fournisseur`.`IDFournisseur`= ".$_SESSION["IDFournisseur"]."  AND `IDcf` = ".$_SESSION["IDcf"]." ";
	
    }
    else {
	$sql=" SELECT * FROM `achat-commande`, `produit`,`marque`,`article`,`couleur`,`fournisseur` WHERE `achat-commande`.`IDProduit`=`produit`.`IDProduit` AND `produit`.`IDMarque`=`marque`.`IDMarque` AND `produit`.`IDArticle`=`article`.`IDArticle` and `produit`.`IDCouleur`=`couleur`.`IDCouleur` AND ";
	$sql.= "`achat-commande`.`IDFournisseur`=".$_SESSION["IDFournisseur"]." AND `fournisseur`.`IDFournisseur`= ".$_SESSION["IDFournisseur"]."  AND `IDcf` = ".$_SESSION["IDcf"]." AND `Etat`= '".$Etat."' ";
    }
	$results = mysqli_query($link,$sql);
  if ($results) {
  
  
    while ($row = mysqli_fetch_assoc($results)) {
              if ($row["Etat"]=="achat"){ 
              	echo '<tr class="alert alert-success" data="'.$row["IDAchat_Commande"].'">';
                        echo '<td><img src="../uploads/'.$row["img"].'" alt="Article :'.$row["type-article"].' Marque:'.$row["nom-marque"].'" class="img-thumbnail"></td>';
                        echo '<td><strong>quantité : </strong>'.$row["quantite"] .'<br><strong> Prix Total: </strong>'.$row["total"]. '<br><strong>Article :</strong>'.$row["type-article"].'<br><strong> Marque:</strong>'.$row["nom-marque"].'<br><strong>Taille : </strong>' .$row["taille"] . '</td>';
                        echo '<td>' .$row["date"] . '</td>';
                        echo '<td><strong>Validé</strong></td>';
                 echo '</tr>';
                }
                elseif ($row["Etat"]=="en attent"){ 
                echo '<tr class="alert alert-warning" >';
                        echo '<td><img src="../uploads/'.$row["img"].'" alt=".." class="img-thumbnail"></td>';
                        echo '<td><strong>quantité : </strong>'.$row["quantite"] .'<br><strong> Prix Total: </strong>'.$row["total"]. '<br><strong>Article :</strong>'.$row["type-article"].'<br><strong> Marque:</strong>'.$row["nom-marque"].'<br><strong>Taille : </strong>' .$row["taille"] . '</td>';
                        echo '<td><p style="vertical-align:middle;">' .$row["date"] . '</p></td>';
                        echo '<td> <!-- Button trigger modal -->
                              <button type="button" style="width:100px;" class="btn btn-primary btn-success col-sm-5 col-sm-offset-3" data-toggle="modal" data-target="#Validation">
                                 <i class="fa fa-check" style="font-size: 150%;" aria-hidden="true"></i>
                              </button>
                              
                              <!-- Modal -->
                              <div class="modal fade" id="Validation" tabindex="-1" role="dialog" aria-labelledby="Valider">
                                <div class="modal-dialog" role="document">
                                  <div class="modal-content">
                                    <div class="modal-header">
                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                      <h4 class="modal-title" id="Valider">Validation</h4>
                                    </div>
                                    <form action="validation.php?op=validation&id='.$row["IDAchat_Commande"]."&idproduit=".$row["IDProduit"].'\'" method="post" class="form-horizontal">
                                    <div class="modal-body">
                                           <div class="form-group">
                                             <label for="inputDate" class="col-sm-2 control-label"><i class="fa fa-calendar" style="font-size:150%;" aria-hidden="true"></i></label>
                                             <div class="col-sm-10">
                                               <input type="date" class="form-control" placeholder="yyyy-m-d" name="date" id="inputDate" required>
                                             </div>
                                             </div>
                                             </div>
                                             <div class="modal-footer">
                                               <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times" style="font-size: 150%;" aria-hidden="true"></i></button>
                                               <button  class="btn btn-success"  ><i class="fa fa-check" style="font-size: 150%;" aria-hidden="true"></i></button>
                                             </div>
                                    </form>
                                  </div>
                                </div>
                              </div></td>';
                 echo '</tr>';
                }  
               elseif ($row["Etat"]=="commande") {
                	echo '<tr class="alert alert-danger" data="'.$row["IDAchat_Commande"].'">';
                        echo '<td><img src="../uploads/'.$row["img"].'" alt=".." class="img-thumbnail"></td>';
                        echo '<td><strong>quantité : </strong>'.$row["quantite"] .'<br><strong> Prix Total: </strong>'.$row["total"]. '<br><strong>Article :</strong>'.$row["type-article"].'<br><strong> Marque:</strong>'.$row["nom-marque"].'<br><strong>Taille : </strong>' .$row["taille"] . '</td>';
                        echo '<td>' .$row["date"] . '</td>';
                        echo '<td>
                              <!-- Button trigger modal -->
                              <button type="button" class="btn btn-primary btn-success col-sm-5 " data-toggle="modal" data-target="#Validation">
                               <i class="fa fa-check" style="font-size: 150%;" aria-hidden="true"></i>

                              </button>
                              
                              <!-- Modal -->
                              <div class="modal fade" id="Validation" tabindex="-1" role="dialog" aria-labelledby="Valider">
                                <div class="modal-dialog" role="document">
                                  <div class="modal-content">
                                    <div class="modal-header">
                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                      <h4 class="modal-title" id="Valider">Validation</h4>
                                    </div>
                                    <form action="validation.php?op=validation&id='.$row["IDAchat_Commande"]."&idproduit=".$row["IDProduit"].'\'" method="post" class="form-horizontal">
                                    <div class="modal-body">
                                           <div class="form-group row">
                                            <div class="row col-sm-10 ">
                                            <div class="col-sm-2 ">
                                             <label for="inputDate" class="control-label"><i class="fa fa-calendar" style="font-size:150%;" aria-hidden="true"></i></label>
                                            </div>
                                             <div class="col-sm-10">
                                               <input type="date" class="form-control" name="date" id="inputDate" required>
                                             </div>
                                             </div>
                                             </div>
                                             </div>
                                             <div class="modal-footer">
                                              <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times" style="font-size: 150%;" aria-hidden="true"></i></button>
                                               <button  class="btn btn-success"  ><i class="fa fa-check" style="font-size: 150%;" aria-hidden="true"></i></button>
                                             </div>
                                    </form>
                                  </div>
                                </div>
                              </div>
                              <!-- Button trigger modal -->
                              <button type="button" class="btn btn-primary btn-warning col-sm-5 col-sm-offset-2" data-toggle="modal" data-target="#Description">
                                 <i class="fa fa-pause" style="font-size: 150%;" aria-hidden="true"></i>

                              </button>
                              
                              <!-- Modal -->
                              <div class="modal fade" id="Description" tabindex="-1" role="dialog" aria-labelledby="CauseDeRettar">
                                <div class="modal-dialog" role="document">
                                  <div class="modal-content">
                                    <div class="modal-header">
                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                      <h4 class="modal-title" id="CauseDeRettard">Cause de Retard</h4>
                                    </div>
                                     <form action="validation.php?op=descreption&id='.$row["IDAchat_Commande"]."&idproduit=".$row["IDProduit"].'\'" method="post" class="form-horizontal">
 
                                    <div class="modal-body row">
                                           <div class="form-group row col-sm-10 ">
                                             <div class="col-sm-2 col-sm-offset-2">
                                             <label for="inputText" class="col-sm-2 control-label"><i class="fa fa-envelope" style="font-size:200%;" aria-hidden="true"></i></label>
                                             </div>
                                             <div class="col-sm-10 col-sm-offset-3">
                                               <textarea class="form-control" name="descreption" id="inputText" rows="5" cols="50" required></textarea>
                                             </div>
                                       </div>
                                    </div>
                                    <div class="modal-footer">
                                      <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times" style="font-size: 150%;" aria-hidden="true"></i></button>
                                      <button  class="btn btn-success"  ><i class="fa fa-check" style="font-size: 150%;" aria-hidden="true"></i></button>
                                    </div>
                                    </form>
                                  </div>
                                </div>
                              </div></td>';
                 echo '</tr>';              
                }elseif ($row["Etat"]=="en route") {
                 	echo '<tr class="alert alert-info" >';
                        echo '<td><img src="../uploads/'.$row["img"].'" alt=".." class="img-thumbnail"></td>';
                        echo '<td>' .'<strong>quantité : </strong>'.$row["quantite"] .'<br><strong> Prix Total: </strong>'.$row["total"]. '<br><strong>Article :</strong>'.$row["type-article"].'<br><strong> Marque:</strong>'.$row["nom-marque"].'<br><strong>Taille : </strong>' .$row["taille"] . '</td>';
                        echo '<td>' .$row["date"] . '</td>';
                        echo '<td><strong>En Route</strong></td>';
                 echo '</tr>';
                }
             }
   }else echo  "<td class=\"alert alert-danger \"> il n'y a aucune commande </td>";
   ?>
   <?php mysqli_close($link) ?>