<?php
session_start();
$titre="Connexion";
include("includes/identifiants.php");
include("includes/debut.php");
include("entetenonconnecter.php");
include("menu.php");


?>
<body>
</br></br></br><div class="fond">
<?php if (!isset($_POST['pseudo'])) //On est dans la page de formulaire
		{
	echo '
			<fieldset class="cro">
				<legend><h1 class="titre"> Connexion administrateur </h1></legend>
				<form method="POST" action="connexion_administrateur.php" enctype="multipart/form-data">
			<div >
				<input type="text" name="pseudo" id="pseudo" placeholder="Identifiant" size="10" maxlength="10" />
			</div>
			<div >
				<input type="password" name="password" id="password" placeholder="Mot de passe" size="10" maxlength="10" />
			</div>
				<input type="submit" value="GO" class="classe_button"/>
			</div>
			</form>
			</fieldset>
			
			</br>';	
			
 }

else
{        if (empty($_POST['pseudo']) || empty($_POST['password']) ) //Oublie d'un champ
    {
        echo'<p>Une erreur s\'est produite pendant votre identification.
	Vous devez remplir tous les champs</p>
	<p>Cliquez <a href="connexion_administrateur.php">ici</a> pour revenir</p>';
    } 		
		elseif ($_POST['pseudo']=="bb" and $_POST['password']=="bb" ) //ATTENTION CHANGER LE MOT DE PASSE
    {
 		$_SESSION['level'] = 4;
		header('Location:./admin.php');
		
    } 
	
		elseif ($_POST['pseudo']!="bb" and $_POST['password']!="bb" )
			{
				echo'</br></br></br></br></br><p class="titre">L\'acc&egrave;s &agrave; cette partie est l\'imit&eacute; &agrave; l\'administrateur </p></br></br></br></br></br>';
			}
}
?>		
</div>	
<?php include('piedpage.php');?>	
</body>