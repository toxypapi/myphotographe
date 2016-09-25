<?php
session_start();
$titre="Profil";
include("includes/identifiants.php");
include('entetenonconnecter.php');
include('menu.php');

include("includes/constants.php");
?>		
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

 echo '<div class="fond"><fieldset><legend ><h2 >Modifier mon mot de passe</h2></legend>';
    if (empty($_POST['sent'])) 
    {
		$id_= (int) $_SESSION['id'];
        $query=$db->prepare("SELECT identifiant,password,email,ville,nom,prenom,age ,sexe,foto_de,experience,je_suis,avatar, about,membre_inscrit,membre_derniere_visite
        FROM validation WHERE id='{$_SESSION['id']}'");
        
        $query->execute();
        $data=$query->fetch();
		if ($data['id']=0) erreur(ERR_IS_NOT_CO);

        echo '<form method="post" action="modifier_mot_de_passe.php" enctype="multipart/form-data">
       
 
        <br />
        Pseudo : <strong>'.stripslashes(htmlspecialchars($data['identifiant'])).'</strong><br />       
        <label for="password">Nouveau mot de Passe :</label>
        <input type="password" name="password" id="password" /><br />
        <label for="repeatpassword">Confirmer le mot de passe :</label>
        <input type="password" name="repeatpassword" id="repeatpassword"  /><br />
       ';
 
?>         
        
        <input type="submit" value="Modifier mon mot de passe" />
        <input type="hidden" id="sent" name="sent" value="1" />
        </form>
<?php	echo'<a href="./voirprofile.php?m='.$id_.'" >
		<input type="submit" value="Annuler" />
        <input type="hidden" id="sent" name="sent" value="1" />
		</a>';
?>		</fieldset>
<?php
         
    }   
 
    else //Cas du traitement
    {
    


    
         
    $password_ = md5($_POST['password']);
    $repeatpassword_ = md5($_POST['repeatpassword']);
	
	if ( empty($repeatpassword_) || empty($password_))
    {
         header('Location:pas_tout1.php');
		 $i++;
    }
	$i = 0; 
	//Vérification du mdp
	
	
	
    
	if ($password_ != $repeatpassword_ )
    {
         $mdp_erreur1 = "Votre mot de passe et votre confirmation diffèrent ou sont vides";
         $i++;
    }
	
?>
<?php
    
    

 
    if ($i == 0) // Si $i est vide, il n'y a pas d'erreur
    {
		 //On modifie la table
 
        $query=$db->prepare("UPDATE validation SET password=:password_ WHERE id='{$_SESSION['id']}'");
		$query->bindValue(':password_',$password_,PDO::PARAM_STR);
		$query->execute();
		$query->CloseCursor();
 
        echo'<u>Modification termin&eacute;e !!!!</u>';
        echo'<p>Votre mot de passe a &eacute;t&eacute; modifi&eacute; avec succ&egrave;s !</p>';
        echo'<p>Cliquez <a href="./index.php">ici</a> 
        pour revenir &agrave; la page d\'accueil</p>';
		
		$query=$db->prepare("SELECT identifiant,password,email,ville,nom,prenom,age ,sexe,foto_de,experience,je_suis,avatar, about,membre_inscrit,membre_derniere_visite
        FROM validation WHERE id='{$_SESSION['id']}'");
        
        $query->execute();
        $data=$query->fetch();
		
if(	$data['password'] = $password_)
{	
$mail = $data['email']; // Déclaration de l'adresse de destination.
if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $mail)) // On filtre les serveurs qui rencontrent des bogues.
{
	$passage_ligne = "\r\n";
}
else
{
	$passage_ligne = "\n";
}
//=====Déclaration des messages au format texte et au format HTML.
$message_txt = "Vous avez apport&eacute; des mdofication dans votre profil sur le site de MyPhotograghe !
Votre identifiant: ".$data['identifiant']."
Mot de passe: ".$_POST['password']."";
$message_html = "<html><head></head><body><b>Vous avez apport&eacute; des mdofication dans votre profil sur le site de MyPhotograghe !</b>
Votre identifiant: ".$data['identifiant']."</br>
Mot de passe: ".$_POST['password']."</body></html>";
//==========
 
//=====Création de la boundary
$boundary = "-----=".md5(rand());
//==========
 
//=====Définition du sujet.
$sujet = "Modification du profil sur MyPhotographe !";
//=========
 
//=====Création du header de l'e-mail.
$header = "From: \"MyPhotographe\"".$passage_ligne;
$header.= "Reply-to: \"MyPhotographe\" ".$passage_ligne;
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
}

       
	
	


    }
    else
    {
        echo'<h1>Modification interrompue</h1>';
        echo'<p>Une ou plusieurs erreurs se sont produites pendant la modification du profil</p>';
        echo'<p>'.$i.' erreur(s)</p>';
        echo'<p>'.$mdp_erreur1.'</p>';
        echo'<p> Cliquez <a href="modifier_mot_de_passe.php">ici</a> pour recommencer</p>';
    }
	echo'<p>Cliquez <a href="./voirprofile.php?m='.$_SESSION['id'].'">ici</a> 
        pour retourner vers votre profil</p></div>';
} 
  $query->CloseCursor(); 
?>