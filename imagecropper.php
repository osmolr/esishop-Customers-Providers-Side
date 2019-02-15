<?php
$id=$_GET['id']; //$session id
$path = "ProfilPic/";
$ratio=1;
if(isset($_POST['finish'])){
	unlink('ProfilPic/'.$_POST['name']);
	header('location:profil.php');
}
?>
<html>
<head>
<link href="css/bootstrap.min.css" rel="stylesheet">
<title>Image crop</title>
<style>
</style>
</head>
<link rel="stylesheet" type="text/css" href="csscropper/imgareaselect-default.css" />
<script type="text/javascript" src="scripts/jquery.min.js"></script>
<script type="text/javascript" src="scripts/jquery.imgareaselect.pack.js"></script>
<?php
	
	$valid_formats = array("jpg", "jpeg");
	if(isset($_POST['submit']))
		{
			$name = $_FILES['photoimg']['name'];
			$size = $_FILES['photoimg']['size'];
			$tmp = $_FILES['photoimg']['tmp_name'];
			if ($size>0) {$image_info = getimagesize($_FILES['photoimg']['tmp_name']);
				$image_width = $image_info[0];
				$ratio=600/$image_width;
				if ($ratio>1) $ratio=1;}
			if(strlen($name))
				{
					list($txt, $ext) = explode(".", $name);
						if(in_array($ext,$valid_formats) && $size<(3*1024*1024)){
							$actual_image_name = time().substr($txt, 5).".".$ext;
							if(move_uploaded_file($tmp, $path.$actual_image_name)){
								$image="<div style='float:left;width:700px;cursor:crosshair;'>
											<h3><center>Veuillez selectionner une partie de l'image</center></h3>
											<img style='margin-left:100px;max-width:600px; margin-bottom:50px;' src='ProfilPic/".$actual_image_name."' id=\"photo\">
										</div>";	
							}
							else	echo "Erreur de chargement.";
						}
						else	echo "Format de fichier non valide.";	
				}
			else	echo "Veuillez selectionner une image.";
		}
?>
<body style="overflow: scroll">
<script type="text/javascript">
function getSizes(im,obj)
	{
		var x_axis = (obj.x1/<?php echo $ratio ?>);
		var x2_axis = obj.x2;
		var y_axis = (obj.y1/<?php echo $ratio ?>);
		var y2_axis = obj.y2;
		var thumb_width = (obj.width/<?php echo $ratio ?>);
		var thumb_height = (obj.height/<?php echo $ratio ?>);
		if(thumb_width > 0)
			{
				
						$.ajax({
							type:"GET",
							url:"ajax_image.php?id=<?php echo($id); ?> &t=ajax&img="+$("#image_name").val()+"&w="+thumb_width+"&h="+thumb_height+"&x1="+x_axis+"&y1="+y_axis,
							cache:false,
							success:function(rsponse)
								{
								 $("#cropimage").hide();
								    $("#thumbs").html("");
									$("#thumbs").html("<h2><center>La photo de profil</center></h2><img src='ProfilPic/"+rsponse+" style='cursor:crosshair; width:100%;  ' /><form action'imagecropper.php' method='post'><input type='hidden' value='<?php echo($actual_image_name); ?>' name='name'><button class='form-control btn btn-info' style='margin-top: 20px;' name='finish' type='submit'>Selectionner</button></form>");
								}
						});
					
			}
		else
			alert("Glissez sur l'image.");
	}

$(document).ready(function(){$('img#photo').imgAreaSelect({aspectRatio: '1:1', onSelectEnd: getSizes});});
</script>
<center><h2><b>Changement de la photo de profile</b></h2></center>
	<div style="margin:0px 30px">
		<?php if (isset($image))echo $image; ?>
		<div id="thumbs" style="width:300px; float: left; margin-left:10px;"></div>

		<div class="container" style="width:600px;">
			<form id="cropimage" method="post" enctype="multipart/form-data">
				<div class="row">
					<div class="col-sm-12">
						<input id="photoimg" accept="image/jpeg" type="file" name="photoimg" class="form-control btn btn-success" value="Selectionner l'image"/>
					</div>
					<?php if (isset($actual_image_name))echo'<input type="hidden" name="image_name" id="image_name" value="'.$actual_image_name.'" />';?>
					<div class="col-sm-12"><input type="submit" name="submit" value="Cropper" class="form-control btn btn-info" style="margin-top:20px !important"/>
					</div>
				</div>
			</form>
		</div>
	</div>

</body>
</html>