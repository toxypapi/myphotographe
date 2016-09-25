<?php 
session_start();
$titre="Profil";
include("includes/identifiants.php");
include("includes/functions_forum.php");
include("includes/constants.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >

	<head>
        <meta charset="utf-8" />
		<style type="text/css">a:link{text-decoration:none}</style>
		<link rel="stylesheet" href="kiff2.css" />
        <title>Bienvenue sur MyPhotographe</title>
	</head>
	
    <body  >
			<!-- fond de page-->
		
			<!--<section id="contenu_gauche"></br></br>
				<div ><form method="post" action="????"><div class="titre"><b ><u>Espace membre</b></u></br></br></div>
					<input type="text" name="pseudo" id="pseudo" placeholder="Pseudo" size="25" maxlength="80" /></br>
					<div ><input type="password" name="pass" id="pass" placeholder="Mot de passe" size="25" maxlength="80" /></div>
					<div class="titre"><input type="submit" value="GO" style="width:60px" class="classe_button" /></br>
					<input type="button" value="S'inscrire !"  onclick="window.location='formulaire_dinscription.php'"style="width:190px" class="classe_button1"/>
			</div></form></div>
			</section>-->
			<div  ><img src= "http://127.0.0.1/site%20a%20envoyer/Image/logo%20blanc.jpg" width="730" height="150">
			<!--<section id="contenu_doite"><img src="http://127.0.0.1:8887/site%20a%20envoyer/Image/logo%20blanc.jpg" height="184" width="730">
			</section>-->
            <input type="button" value="S'inscrire !"  onclick="window.location='register.php'"style="width:190px" class="classe_button"/>
			<?php 
			$query=$db->prepare('SELECT id FROM validation ');
			$query->execute();
			$query->CloseCursor();
			include('menu.php');?></br></br>
			<div class="fond"></br></br></br></br></br><a class="titre"><h1 class="cro4" >Bient&ocirc;t sur notre site </h1></a></br></br></br>
			</br></br></br>
			</div>
			
			</div>
        </div>
	</body>	
			<?php include('piedpage.php');?>
	
		</div>
		
          
</html>