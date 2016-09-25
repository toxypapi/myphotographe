<?php
session_start();
$titre="Connexion";
include("includes/identifiants.php");
include("includes/debut.php");
include("entetenonconnecter.php");
include("menu.php");


?>
<?php


function erreur($err='')
{
   $mess=($err!='')? $err:'Une erreur inconnue s\'est produite';
   exit('<p>'.$mess.'</p>
   <p>Cliquez <a href="index.php">ici</a> pour revenir à la page d\'accueil</p>');
	
}
?>

<?php 

function verif_auth($auth_necessaire)
{
$level=(isset($_SESSION['level']))?$_SESSION['level']:1;
return ($auth_necessaire <= intval($level));
}
?>

<body>
		
<div class="fond">		
<?php

if ($id=0) erreur(ERR_IS_CO);
?>
<?php
if (!isset($_POST['identifiant'])) //On est dans la page de formulaire
{
	echo '</br></br></br><br /><br /><br /><!-- Contenu de la page -->
			<div class="cro4">
			<fieldset >
				<legend><h1 > Connexion </h1></legend>
				<form method="post" action="connexion.php"  enctype="multipart/form-data">
			<div >
				<input type="text" name="identifiant" id="identifiant" placeholder="Identifiant" size="10" maxlength="100" />
			</div>
			<div >
				<input type="password" name="password" id="password" placeholder="Mot de passe" size="10" maxlength="100" />
			</div>
			<div >
				<input type="submit" name="submit" value="GO" class="classe_button"/>
			</div>
			<div>
			</form> 
			</fieldset>
		</div><br /><br /><br /><br />
		</div>';
		include('piedpage.php');
		echo'</body>	
	</html>';
}

else
{
    $message='';
	
    if (empty($_POST['identifiant']) || empty($_POST['password']) ) //Oublie d'un champ
    {
        $message = '<div class="titre"><br /><br /><br /><br /><br /><p>une erreur s\'est produite pendant votre identification.
	Vous devez remplir tous les champs</p>
	<p>Cliquez <a href="./connexion.php">ici</a> pour revenir</p></div><br /><br /><br /><br /><br />';
    }
    else //On check le mot de passe
    {
        $query=$db->prepare('SELECT id,je_suis,sexe,nom,prenom,ville,age ,email,experience,foto_de,identifiant,password,avatar, about,membre_inscrit,membre_derniere_visite,membre_rang,membre_post
        FROM validation WHERE identifiant = :pseudo');
        $query->bindValue(':pseudo',$_POST['identifiant'], PDO::PARAM_STR);
        $query->execute();
        $data=$query->fetch();
	if ($data['password'] == md5($_POST['password'])) // Acces OK !
	{
	    $_SESSION['pseudo'] = $data['identifiant'];
	    $_SESSION['level'] = $data['membre_rang'];
	    $_SESSION['id'] = $data['id'];
	    $message = '<p>Bienvenue '.$data['identifiant'].', 
			vous êtes maintenant connecté!</p>';
			header('Location:./voirprofile.php?m='.$data['id'].'');
		}		  
	
	else // Acces pas OK !
	{
	    $message = '<div class="titre"><br /><br /><br /><br /><br /><p >Une erreur s\'est produite 
	    pendant votre identification.<br /> Le mot de passe ou le pseudo 
            entré n\'est pas correcte.</p><p>Cliquez <a href="./connexion.php">ici</a> 
	    pour revenir à la page précédente
	    <br /><br />Cliquez <a href="index.php">ici</a> 
	    pour revenir à la page d\'accueil</p></div><br /><br /><br /><br /><br />';
	}
    $query->CloseCursor();
    }
    echo $message.'</div></body></html>';
}

?>
