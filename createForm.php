<?php

require_once("include/database.inc.php");
require_once("include/auth.class.php");
require_once("include/validation.class.php");

$auth = new Auth(true);
$user = Auth::getUser();
?>
<!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8" />
		<title>QCManager</title>
		<link rel="stylesheet" type="text/css" href="css/main.css">
		<script src="js/jquery.min.js"></script>


	</head>
	
	<body>
	
	<div id="maincontainer" class="responsive">
	




		<div id="menucolumn">
			<div id="menu">
				<div id="logo"></div>
				<div id="menu_items">
					<div class="padder">
						<ul>
							<li>
								<a id="profilitem" href="./profil.php">
									<?php 
									if(empty($user->user_photo_path))
										echo "<div id='profilimg'>".$user->user_lastname[0]."</div>".$user->user_firstname[0] . ". " . $user->user_lastname;
									else
										echo '<div id="profilimg" style="background-image: url('.$user->user_photo_path.');background-size:cover;">&nbsp;</div>'.$user->user_lastname;
									?>
								</a>
							</li>
							<li><a href="./index.php">Tableau de bord</a></li>
							<li><a href="./createForm.php">Créer un QCM</a></li>
							<li><a href="./logout.php">Déconnexion</a></li>
						</ul>

					</div>
				</div>
			</div>
		</div>


			
		<div id="onecolumn" class="content">
			<div id="title">
				<span>Créer un questionnaire</span>

			</div>
			<div id="one" >
				<iframe id="oneFrame" src="frame_form_edit.php?id=-1"></iframe>
			</div>
		</div>
	
	</div>
	
	<script src="js/responsive.js"></script>

	</body>	
	
</html>