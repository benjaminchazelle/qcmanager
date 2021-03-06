<?php

require_once("include/database.inc.php");
require_once("include/auth.class.php");
require_once("include/validation.class.php");

$auth = new Auth();
$user = Auth::getUser();
$error = true;

$data = array();

if(Validation::Query($_GET, array("id")) && is_numeric($_GET["id"])) {

	$questionnaire_result = $_MYSQLI->query('SELECT * FROM questionnaire INNER JOIN user ON user_id = questionnaire_user_id WHERE questionnaire_id  = "'.$_MYSQLI->real_escape_string($_GET["id"]).'" LIMIT 1');
		
	if($questionnaire_result->num_rows == 1)	{
		
		$error = false;
		
		$questionnaire = $questionnaire_result->fetch_object();
		
		$data["questionnaire"] = $questionnaire;
		
		$own = $questionnaire->questionnaire_user_id == Auth::getUserId();

		$data["questionnaire"]->own = $own;
		
		if(!$own && !($questionnaire->questionnaire_start_date < time() && time () < $questionnaire->questionnaire_end_date)) {
			$error = true;
		}
		
	}
	
	
}

if($error) {
	header("Location: 404.php");
	exit;
}
			
?>
<!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8" />
		<title>QCManager</title>
		<link rel="stylesheet" type="text/css" href="css/main.css">
		<script src="js/jquery.min.js"></script>
		<script>
		QUESTIONNAIRE_ID = <?php echo $_GET["id"]; ?>;
		</script>
		<script src="js/formControllers.js"></script>
		<script type="text/javascript" charset="utf-8" src="js/jquery.leanModal.min.js"></script>
	</head>
	
	<body>
	
	<div id="loginmodal" style="display:none;">
		<h1 id="titlemodal">Lien de partage</h1>
		<div id="loginform" name="loginform">
			<input type="text" name="linkmodal" id="username" class="txtfield" onClick="this.select();" value="<?php echo "http://www.qcmanager.tk/form.php?id=" . $_GET["id"]; ?>"  tabindex="1">
		</div>
	</div>
						
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
							<li><a href="./">Tableau de bord</a></li>
							<li><a href="./createForm.php">Créer un QCM</a></li>
							<li><a href="./logout.php">Déconnexion</a></li>
						</ul>
						
						<hr />
						<div id="author">Par <?php echo $questionnaire->user_firstname[0] . ". " . $questionnaire->user_lastname; ?></div>

						<div id="description"><?php echo $questionnaire->questionnaire_description; ?></div>
						<div id="time">Fin: <?php echo date("d/m/Y H:i", $questionnaire->questionnaire_end_date); ?></div>
						<?php
						if($own) { ?>
						<hr/>
						<ul>
							<li><a onclick="EditFormController()" href="#">Éditer les infos</a></li>
							<li><a onclick="AddQuestionController()" href="#">Nouvelle question</a></li>
							<li><a onclick="ViewStatController()"href="#">Statistiques</a></li>
							<li><a href="#loginmodal" id="modaltrigger">Partager</a></li>
						</ul>	
						<?php } ?>						

					</div>
				</div>
			</div>
		</div>

		<div id="questionscolumn">
			<div id="searchBar">
				<div id="searchfield">
					<input id="searchterm" type="text"  placeholder="Chercher une question..." />
				</div>
			</div>
			<div id="questions">
				<iframe id="questionsFrame" name="loaded" src="frame_form_questions.php?id=<?php echo $questionnaire->questionnaire_id; ?>"></iframe>
			</div>
		</div>
			
		<div id="answercolumn" class="content">
			<div id="title">
				<span><?php echo $questionnaire->questionnaire_title; ?></span>
				<div id="questionnumber">5/12&nbsp;</div>
			</div>
			<div id="answer" >
				<iframe id="answerFrame" src=""></iframe>
			</div>
		</div>
	
	</div>
	
	<div id="toast_form" class="toast">Le questionnaire a été mis à jour</div>
	<div id="toast_answer" class="toast">Vos réponses ont été enregistrées</div>
	
	<script type="text/javascript">
	$(function(){
	  $('#loginform').submit(function(e){
		return false;
	  });
	  
	  $('#modaltrigger').leanModal({ top: 110, overlay: 0.45, closeButton: ".hidemodal" });
	});
	</script>
	<script src="js/responsive.js"></script>

	</body>	
	
</html>