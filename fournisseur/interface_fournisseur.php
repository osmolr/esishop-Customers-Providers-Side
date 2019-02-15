<?php 
session_start();
if(!isset($_SESSION["IDFournisseur"])||($_SESSION["IDFournisseur"]===NULL)) header("location:index.php");?>
<?php include('../ConnectToDB.php'); ?>
<html>
<head>
	<title>fournisseur</title>
	<link rel="stylesheet" type="text/css" href="../css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="../css/fournisseur-style.css">
 <link rel="stylesheet" href="../css/footer-distributed-with-contact-form.css">

  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css">

  <link href="http://fonts.googleapis.com/css?family=Cookie" rel="stylesheet" type="text/css">

</head>
<body >
   <script> 
      var idChoix = "tout";
      idChoix = localStorage.getItem("idChoix");
      localStorage.setItem("idChoix", idChoix);
      function showcommande(str) {
        idChoix = str;
        if (str=="Tout") {
         if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
        } else { // code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.open("GET","executsql.php?Etat=*",true);
        xmlhttp.send();
        xmlhttp.onreadystatechange=function() {if (this.readyState==4 && this.status==200) 
          {
          document.getElementById("table").innerHTML=this.responseText;
        }
        }
        return;
        } 
        if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
        } else { // code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.open("GET","executsql.php?Etat="+str,true);
        xmlhttp.send();
        xmlhttp.onreadystatechange=function() {if (this.readyState==4 && this.status==200) 
          {
          document.getElementById("table").innerHTML=this.responseText;
        }
        }
      }
</script>
<header></header>
		<nav class="navbar navbar-default navbar-fixed-top">
          <div class="container-fluid">
            <div class="navbar-header">
                 <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#ch" aria-expanded="false">
                  <span class="sr-only">Toggle navigation</span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                 </button>
              <a href="log_out.php" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false"><span class="glyphicon glyphicon-off" style="font-size: 170%; color:red;" aria-hidden="true"></span></a>
              <a class="navbar-brand" href="#">
                <img alt="Brand" width="70px" src="logo.png" >
              </a>
            </div>
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav navbar-right">
             <li><a href="log_out.php"><!-- <span class="glyphicon glyphicon-off" style="font-size: 170%; color:red;" aria-hidden="true"></span> --><i class="fa fa-power-off" aria-hidden="true" style="font-size: 170%; color:red;" ></i>
</a></li>
            </ul>
          </div>
          </div>
    </nav>
    <!-- <div class="row"> -->
    <div class="container col-md-8 col-md-offset-2 col-xs-10 col-xs-offset-1">
          <div class="row collapse navbar-collapse" id="ch">
          <div class="col-xs-9 row col-xs-offset-2"> 
            <div class="col-xs-2" style="padding : 0 0 ;"><button class=" btn btn-primary" onClick="showcommande(this.value);idChoix='tout';localStorage.setItem('idChoix', idChoix);" value="Tout" style="width:100%; " id="tout" >Tout</button></div>
            <div class="col-xs-2" style="padding : 0 0 ;"><button class=" btn btn-danger" onClick="showcommande(this.value);idChoix='commande';localStorage.setItem('idChoix', idChoix);" value="commande" style="width:100%; " id="commande" >Commande</button></div>
            <div class="col-xs-2" style="padding : 0 0 ;"><button class=" btn btn-warning" onClick="showcommande(this.value);idChoix='en attent';localStorage.setItem('idChoix', idChoix);" value="en attent" style="width:100%; "id="en attent" >En Attent</button></div>
            <div class="col-xs-2" style="padding : 0 0 ;"><button class=" btn btn-info" onClick="showcommande(this.value);idChoix='en route';localStorage.setItem('idChoix', idChoix);" value="en route" style="width:100%; " id="en route" >En Route</button></div>
            <div class="col-xs-2" style="padding : 0 0 ;"><button class=" btn btn-success" onClick="showcommande(this.value);idChoix='achat';localStorage.setItem('idChoix', idChoix);" value="achat" style="width:100%; " id="achat" >Vente</button></div>
        </div>
        </div>   
        <script type="text/javascript" >
              document.getElementById(idChoix).click();
        </script>
	   <div id="tab">
	   	    <table class="table" id="table" >
          </table>
	   	    
       </div>
    </div>
  <!-- </div> -->
  <footer class="footer-distributed" style=" margin-top:5%;">

      <div class="footer-left">

        <img alt="Brand" width="70px" src="logo.png" >
        

        <p class="footer-company-name">ESI-Shop &copy; 2017</p>

        <div class="footer-icons">

          <a href="#"><i class="fa fa-facebook"></i></a>
          <a href="#"><i class="fa fa-twitter"></i></a>
          <a href="#"><i class="fa fa-linkedin"></i></a>
          <a href="#"><i class="fa fa-github"></i></a>

        </div>

        <?php 
        $sql="SELECT * FROM `login` " ;
        $result=mysqli_query($link,$sql);
        $row = mysqli_fetch_assoc($result);
        echo "<h4> <span>Magasiner:</span> ".$row["prenom"]." ".$row["nom"]." &nbsp &nbsp &nbsp &nbsp &nbsp <span>N°tel :</span> ".$row["ntel"]."</h4>";
        ?>

      </div>

      <div class="footer-right">


        <form action="mail/mail.php" method="post">

          <textarea name="message" placeholder="Message"></textarea>
          <button>Send</button>

        </form>

      </div>

    </footer>
<script src="http://code.jquery.com/jquery-3.2.1.js"></script>
<script src="../js/bootstrap.js"></script>
<script src="../js/jquery.nicescroll.js"></script>
<script src="../js/scrol.js"></script>
</body>
</html>
<?php mysqli_close($link) ?>