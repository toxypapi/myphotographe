<?php
session_start();
$titre="Inscription";
include("includes/identifiants.php");
include("includes/debut.php");
include('entetenonconnecter.php');
include('menu.php');?>

<?php

function move_avatar($avatar)
{
    $extension_upload = strtolower(substr(strrchr($avatar['name'],'.'),1));
    $name = time();
    $nomavatar = str_replace('','',$name).".".$extension_upload;
    $name = "./images/avatars/".str_replace('','',$name).".".$extension_upload;
    move_uploaded_file($avatar['tmp_name'],$name);
    return $nomavatar;
}
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

<?php


if ($id=0) erreur(ERR_IS_CO);
?>
<body ></br></br></br>
<div class="fond">
		
<?php
if (empty($_POST['identifiant'])) // Si on la variable est vide, on peut considérer qu'on est sur la page de formulaire
{	
	
	echo '<form method="post" action="register.php" enctype="multipart/form-data">
			
			<fieldset class="cro4" ><legend class="titre"><h1 >CREATION DU PROFIL</h1></legend>
				 
					<div>
					<div class="gauche"><b><u>Je suis:</b></u></div>
					 <table > 
						<tr>
						<td>Photographe<input type="radio" id="photographe" name="je_suis" value="photographe" /> <label for="photographe"tabindex="1"></label></td>
						<td>Modele<input type="radio" id="modele" name="je_suis" value="modele"/><label for="modele"  tabindex="2"></label></td>
						</tr>
				    </table>
					</div>		
					
					<div></br></br>
					<div class="gauche"><b><u>Sexe:</b></u></div>
					<label for="sexe"tabindex="4"></label> 
					<table>
						<tr>
						<td>Homme<input type="radio" name="sexe" id="sexe" value="homme" tabindex="4"/> <label for="homme"></label>	</td>
						<td>Femme<input type="radio" id="sexe"name="sexe" value="femme" tabindex="5"/> <label for="femme"></label></td>
						</tr> 
				    </table >
					</div>
					<div class="gauche"><b><u>Informations personnels:</b></u></div>
					<div><label for="nom"tabindex="6">Nom:</label><input type="text" name="nom" id="nom"/>
					</div>
					<div ><label for="prenom"tabindex="7">Prenom:</label><input type="text" name="prenom" id="prenom"/>
					</div>
					<div ><label for="age"tabindex="9">Age:</label><input type="text" name="age" id="age"/>
					</div>
					<!--<div ><label for="age"tabindex="8">Date de naissance:</label><input type="text" name="jour" id="jour" size="1,5" maxlength="2"/></label><input type="text" name="mois" id="mois" size="1,5" maxlength="2"/></label><input type="text" name="annee" id="annee" size="1,5" maxlength="4"/>
					</div>-->
					<div ><label for="ville"tabindex="9">Ville:</label><input type="text" name="ville" id="ville"/>
					</div>
					<div ><label for="email"tabindex="10">Adresse mail:</label><input type="text" name="email" id="email"/>
					</div>
					<div >
					<table></br></br>
						<div class="gauche"><b><u>Mon experience:</b></u></div>
						<tr>
						<td>Debutant(e)<input type="radio" id="debutant" name="experience" value="debutant" tabindex="11"/> <label for="debutant"></label></td>
						<td>Amateur(rice)<input type="radio" id="amateur" name="experience" value="amateur" tabindex="12"/> <label for="amateur"></label></td>
						<td>Experimente(e)<input type="radio" id="experimente" name="experience"  value="experimente" tabindex="13"/> <label for="experimente"></label></td></tr>
						</table>
					</div>
					<div >
					<table>
						<div class="gauche"><b><u >Ici pour des photos de:</b></u></div>
						<tr>
						<td>Mode<input type="radio" id="mode" name="foto_de" value="mode" /> <label for="mode"></label></td>
						<td>Mariage<input type="radio" id="mariage" name="foto_de" value="mariage" tabindex="12"/> <label for="mariage"></label></td>
						<td>Baby<input type="radio" id="Baby" name="foto_de" value="baby" tabindex="13"/> <label for="baby"></label></td></tr>
						<tr><td>Famille<input type="radio" id="famille"name="foto_de" value="famille" tabindex="14"/> <label for="famille"></label></td>
						<td>Ami(es)<input type="radio" id="amis" name="foto_de" value="amis" tabindex="15"/> <label for="ami(es)"></label></td>
						<td>Couple<input type="radio" id="couple" name="foto_de" value="couple" tabindex="16"/> <label for="couple"></label></td>
						</tr>
						</table>
					</div>
					<div class="gauche"><b><u>Paramètre de connexion:</b></u></div>
					<div ><label for="identifiant"tabindex="17">Identifiant:</label><input type="text" name="identifiant" id="identifiant"/>
					</div>
					<div ><label for="mot_de_passe"tabindex="18">Saisir un mot de passe:</label><input type="password" name="password" id="password" />
					
					</div>
					<div ><label for="mot_de_passe"tabindex="18">Retaper le mot de passe: </label><input type="password" name="repeatpassword" id="repeatpassword" />
					</div>	
						
					<div><label for="photo"tabindex="19">Photo de profil:</label><input type="file" name="avatar"/><br/><br/>
					</div>
				
				    <p><label for="about"tabindex="19">A propos de moi(Ecrire sans accent):</label></p>
				    <textarea rows="6" cols="30" id="about" name="about"></textarea> </br>
					<label><input type="checkbox" name="condition" value="condition" />J\'accepte que myphotographe publie mes informations et mes photos en ligne.</label> </br>
        
				    <div class="titre"><input type="submit"  name="submit" tabindex="20" value="GO!" class="classe_button"/></div>
					</form>      
				   	</fieldset>';
					     		
					
	
	
} //Fin de la partie formulaire

else //On est dans le cas traitement
{
    $pseudo_erreur1 = NULL;
    $pseudo_erreur2 = NULL;
    $mdp_erreur = NULL;
    $email_erreur1 = NULL;
    $email_erreur2 = NULL;
    $msn_erreur = NULL;
    $avatar_erreur = NULL;
    $avatar_erreur1 = NULL;
    $avatar_erreur2 = NULL;
    $avatar_erreur3 = NULL;
	$tous_les_champs = NULL;
	// NE PAS OUBLIER LE CAS OU LA PERSONNE NE REMPLIS PAS TOUT
?>
<?php

    //On récupère les variables
    $i = 0;
    $temps = time(); 
	$nom = NULL;
	$prenom = NULL;
    $identifiant=NULL;
    $email = NULL;
	$foto_de = NULL;
	$experience = NULL;
	$about = NULL;
	$je_suis = NULL;
	$sexe = NULL;
	$age = NULL;
    $ville = NULL;
    $password = NULL;
    $repeatpassword = NULL;
	$condition = NULL;
    //Vérification du pseudo
    $query=$db->prepare('SELECT COUNT(*) AS nbr FROM validation WHERE identifiant =:pseudo');
    $query->bindValue(':pseudo',$identifiant, PDO::PARAM_STR);
    $query->execute();
    $pseudo_free=($query->fetchColumn()==0)?1:0;
    $query->CloseCursor();
	
	if (empty($_POST['nom']) or empty($_POST['prenom']) or empty($_POST['identifiant'])or empty($_POST['condition']) or empty($_POST['email']) or empty($_POST['foto_de']) or empty( $_POST['experience']) or empty( $_POST['je_suis']) or empty( $_POST['sexe']) or empty($_POST['age']) or empty($_POST['ville']) or empty($_POST['password']) or empty ($_POST['repeatpassword']))
   {
		header('Location:pas_tout.php');
   }
   
    $nom = $_POST['nom'];
	$prenom = $_POST['prenom'];
    $identifiant=$_POST['identifiant'];
    $email = $_POST['email'];
	$foto_de = $_POST['foto_de'];
	$experience = $_POST['experience'];
	$about = $_POST['about'];
	$je_suis = $_POST['je_suis'];
	$sexe = $_POST['sexe'];
	$age = $_POST['age'];
    $ville = $_POST['ville'];
    $password = md5($_POST['password']);
    $repeatpassword = md5($_POST['repeatpassword']);
	$condition = $_POST['condition'];
   
   if(!$pseudo_free)
    {
        $pseudo_erreur1 = "Votre pseudo est déjà utilis&eacute; par un membre";
        $i++;
    }

    if (strlen($identifiant) < 3 || strlen($identifiant) > 15)
    {
        $pseudo_erreur2 = "Votre pseudo doit &ecirc;tre inf&eacute;rieur &agrave; 15 et sup&eacute;rieure &agrave; 3 caract&egrave;res";
        $i++;
    }

    //Vérification du mdp
    if ($password != $repeatpassword || empty($repeatpassword) || empty($password))
    {
        $mdp_erreur = "Votre mot de passe et votre confirmation diff&egrave;rent, ou sont vides";
        $i++;
    }
	
	if ( strlen($password) < 6)
    {
        $mdp_erreur1 = "Le mot de passe est inf&eacute;r &agrave; 6 carat&egrave;res";
        $i++;
    }
?>
<?php
    //Vérification de l'adresse email

    //Il faut que l'adresse email n'ait jamais été utilisée
    $query=$db->prepare('SELECT COUNT(*) AS nbr FROM validation WHERE email =:mail');
    $query->bindValue(':mail',$email, PDO::PARAM_STR);
    $query->execute();
    $mail_free=($query->fetchColumn()==0)?1:0;
    $query->CloseCursor();
    
    if(!$mail_free)
    {
        $email_erreur1 = "Votre adresse email est déjà utilisée par un membre";
        $i++;
    }
    //On vérifie la forme maintenant
    if (!preg_match("#^[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]{2,}\.[a-z]{2,4}$#", $email) || empty($email))
    {
        $email_erreur2 = "Votre adresse E-Mail n'a pas un format valide";
        $i++;
    }
?>
<?php
    //Vérification de l'avatar :
    if (!empty($_FILES['avatar']['size']))
    {
        //On définit les variables :
        $maxsize = 1002400000090099; //Poid de l'image
        $maxwidth = 100000000090099; //Largeur de l'image
        $maxheight = 10000000090099; //Longueur de l'image
        $extensions_valides = array( 'jpg' , 'jpeg' , 'gif' , 'png', 'bmp' ); //Liste des extensions valides
        
        if ($_FILES['avatar']['error'] > 0)
        {
                $avatar_erreur = "Erreur lors du transfert de l'avatar : ";
        }
        if ($_FILES['avatar']['size'] > $maxsize)
        {
                $i++;
                $avatar_erreur1 = "Le fichier est trop gros : (<strong>".$_FILES['avatar']['size']." Octets</strong>    contre <strong>".$maxsize." Octets</strong>)";
        }

        $image_sizes = getimagesize($_FILES['avatar']['tmp_name']);
        if ($image_sizes[0] > $maxwidth OR $image_sizes[1] > $maxheight)
        {
		
               $image_sizes[0] == $maxwidth;
                $image_sizes[1] == $maxheight; 
				
        }
        
        $extension_upload = strtolower(substr(  strrchr($_FILES['avatar']['name'], '.')  ,1));
        if (!in_array($extension_upload,$extensions_valides) )
        {
                $i++;
                $avatar_erreur3 = "Extension de l'avatar incorrecte";
        }
    }
?>
<?php
   if ($i==0)
   {
	echo'<div class="titre"><br /><br /><br /><br /><br /><h2>Inscription terminée !!!!</h2>';
        echo'<p>Bienvenue '.stripslashes(htmlspecialchars($_POST['identifiant'])).' vous &ecirc;tes maintenant inscrit sur le site un email va vous etre envoye comportant votre identifiant et votre mot de passe!!!!</p>
	<p>Cliquez <a href="./index.php">ici</a> pour revenir &agrave; la page d\'accueil</p>';

		echo'<p>Cliquez <a href="./connexion.php">ici</a> 
        pour vous connecter et acc&eacute;der &agrave; votre profil</p></div><br /><br /><br /><br /><br />';
	
	
        //La ligne suivante sera commentée plus bas
	$avatar=(!empty($_FILES['avatar']['size']))?move_avatar($_FILES['avatar']):''; 
   
        $query=$db->prepare('INSERT INTO validation (avatar,je_suis,sexe,nom,prenom,ville,age ,email,experience,foto_de,identifiant,password, about,membre_inscrit,membre_derniere_visite)
        VALUES (:avatar,:je_suis,:sexe,:nom,:prenom,:ville,:age,:email,:experience,:foto_de,:identifiant,:password,:about,:temps,:temps)');
	$query->bindValue(':avatar', $avatar, PDO::PARAM_STR);
	$query->bindValue(':identifiant', $identifiant, PDO::PARAM_STR);
	$query->bindValue(':password', $password, PDO::PARAM_INT);
	$query->bindValue(':email', $email, PDO::PARAM_STR);
	$query->bindValue(':je_suis', $je_suis, PDO::PARAM_STR);
	$query->bindValue(':ville', $ville, PDO::PARAM_STR);
	$query->bindValue(':nom', $nom, PDO::PARAM_STR);
	$query->bindValue(':prenom', $prenom, PDO::PARAM_STR);
	$query->bindValue(':age', $age, PDO::PARAM_INT);
	$query->bindValue(':sexe', $sexe, PDO::PARAM_STR);
	$query->bindValue(':experience', $experience, PDO::PARAM_STR);
	$query->bindValue(':foto_de', $foto_de, PDO::PARAM_STR);
	$query->bindValue(':about', $about, PDO::PARAM_STR);
	$query->bindValue(':temps', $temps, PDO::PARAM_INT);
    $query->execute();

	//Et on définit les variables de sessions
        $_SESSION['identifiant'] = $pseudo;
        $_SESSION['id'] = $db->lastInsertId(); ;
        $_SESSION['level'] = 2;
        $query->CloseCursor();
		$data=$query->fetch();
		?>
<?php
$mail = $email; // Déclaration de l'adresse de destination.
if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $mail)) // On filtre les serveurs qui rencontrent des bogues.
{
	$passage_ligne = "\r\n";
}
else
{
	$passage_ligne = "\n";
}
//=====Déclaration des messages au format texte et au format HTML.
$message_txt = "Bienvenue sur le site de MyPhotograghe !
</br>
Votre identifiant: ".$identifiant."</br>
Mot de passe: ".$_POST['password']."";
$message_html = "<html><head></head><body><b>Bienvenue sur le site de MyPhotographe !</b></br>
Votre identifiant: ".$identifiant."</br>
Mot de passe: ".$_POST['password']."</body></html>";
//==========
 
//=====Création de la boundary
$boundary = "-----=".md5(rand());
//==========
 
//=====Définition du sujet.
$sujet = "Inscription à MyPhotographe !";
//=========
 
//=====Création du header de l'e-mail.
$header = "From: \"MyPhotographe\"<wb@mail.fr>".$passage_ligne;
$header.= "Reply-to: \"MyPhotographe\" <wb@mail.fr>".$passage_ligne;
$header.= "MIME-Version: 1.0".$passage_ligne;
$header.= "Content-Type: multipart/alternative;".$passage_ligne." boundary=\"$boundary\"".$passage_ligne;
//==========
 
//=====Création du message.
$message = $passage_ligne."--".$boundary.$passage_ligne;
//=====Ajout du message au format texte.
$message.= "Content-Type: text/plain; charset=\"ISO-8859-1\"".$passage_ligne;
$message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
$message.= $passage_ligne.$message_txt.$passage_ligne;
//==========
$message.= $passage_ligne."--".$boundary.$passage_ligne;
//=====Ajout du message au format HTML
$message.= "Content-Type: text/html; charset=\"ISO-8859-1\"".$passage_ligne;
$message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
$message.= $passage_ligne.$message_html.$passage_ligne;
//==========
$message.= $passage_ligne."--".$boundary."--".$passage_ligne;
$message.= $passage_ligne."--".$boundary."--".$passage_ligne;
//==========
 
//=====Envoi de l'e-mail.
mail($mail,$sujet,$message,$header);
//==========
?>

<?php
		
	
    }
    else
    {
        echo'<div class="titre"><br /><br /><br /><br /><br /><h2>Inscription interrompue</h2>';
        echo'<p>Une ou plusieurs erreurs se sont produites pendant l\'incription</p>';
        echo'<p>'.$i.' erreur(s)</p>';
        echo'<p>'.$pseudo_erreur1.'</p>';
        echo'<p>'.$pseudo_erreur2.'</p>';
        echo'<p>'.$mdp_erreur.'</p>';
		echo'<p>'.$mdp_erreur1.'</p>';
        echo'<p>'.$email_erreur1.'</p>';
        echo'<p>'.$email_erreur2.'</p>';
        echo'<p>'.$msn_erreur.'</p>';
        echo'<p>'.$avatar_erreur.'</p>';
        echo'<p>'.$avatar_erreur1.'</p>';
        
        echo'<p>'.$avatar_erreur3.'</p>';
        echo'<p>'.$tous_les_champs.'</p>';
        echo'<p>Cliquez <a href="./register.php">ici</a> pour recommencer</p></div><br /><br /><br /><br /><br />';
    }
}	
?>
</div>
</body>
<?php include('piedpage.php');?>
</html>
