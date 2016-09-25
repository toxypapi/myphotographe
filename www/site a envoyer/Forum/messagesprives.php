<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
<head>
        <meta charset="utf-8" />
		<link rel="stylesheet" href="kiff2.css" />
        <title>Messagerie</title>
	</head>

<?php
session_start();
$titre="Messages Privés";
$balises = true;
include("includes/identifiants.php");
include("includes/debut.php");
//include("includes/bbcode.php");
include('entetenonconnecter.php');
include("menu.php");




function erreur($err='')
{
   $mess=($err!='')? $err:'Une erreur inconnue s\'est produite';
   exit('<p>'.$mess.'</p>
   <p>Cliquez <a href="index.php">ici</a> pour revenir à la page d\'accueil</p>');
	
}


function verif_auth($auth_necessaire)
{
$level=(isset($_SESSION['level']))?$_SESSION['level']:1;
return ($auth_necessaire <= intval($level));
}


$action = (isset($_GET['action']))?htmlspecialchars($_GET['action']):'';

 ?>
 
 
 
 <br /><br /><div class="fond">
 <?php
switch($action)
{
?>
<?php
case "consulter": //Si on veut lire un message
 //La requête nous permet d'obtenir les infos sur ce message :
    $query = $db->prepare('SELECT  mp_id,mp_expediteur, mp_receveur, mp_titre,               
    mp_text,mp_time,  mp_lu, id, identifiant, avatar,
    ville, membre_inscrit, membre_post
    FROM forum_mp
    LEFT JOIN validation ON id = mp_expediteur
    WHERE mp_id = :id');
    $id_mess = (int) $_GET['id']; //On récupère la valeur de l'id
    echo '<h3>Consulter un message</h3>
	Cliquez <a href="./messagesprives.php">ici</a> pour retourner &agrave;
       la messagerie<br /><br />';

    
    $query->bindValue(':id',$id_mess,PDO::PARAM_INT);
    $query->execute();
    $data=$query->fetch();

    // Attention ! Seul le receveur du mp peut le lire !
    if ($id != $data['mp_receveur']) erreur(ERR_WRONG_USER);
       
    //bouton de réponse
    echo'<p><a href="./messagesprives.php?action=repondre&amp;dest='.$data['mp_expediteur'].'">
    <img src="http://uploads.siteduzero.com/files/40001_41000/40790.gif" alt="Répondre" 
    title="Répondre &agrave; ce message" /></a></p>'; 

    ?>
	<table>     
    <tr>
    <th class="vt_auteur"><strong>Auteur</strong></th>             
    <th class="vt_mess"><strong>Message</strong></th>       
    </tr>
    <tr>
    <td>
    <?php echo'<strong>
    <a href="voirprofile.php?m='.$data['id'].'&amp;action=consulter_membre">
    '.stripslashes(htmlspecialchars($data['identifiant'])).'</a></strong></td>
    <td>Post&eacute; &agrave; '.date('H\hi \l\e d M Y',$data['mp_time']).'</td>';
    ?>
    </tr>
    <tr>
    <td>
    <?php
        
    //Ici des infos sur le membre qui a envoyé le mp
    echo'<p><img src="./images/avatars/'.$data['avatar'].'" width="90px" alt="" />
    <br />Membre inscrit le '.date('d/m/Y',$data['membre_inscrit']).'
    <br />Messages : '.$data['membre_post'].'
    <br />Localisation : '.stripslashes(htmlspecialchars($data['ville'])).'</p>
    </td><td>';
        
    echo ''.(stripslashes(htmlspecialchars($data['mp_text']))).'
    <hr />
    </td></tr></table>';
	?>
	<?php
    if ($data['mp_lu'] == 0) //Si le message n'a jamais été lu
    {
        $query->CloseCursor();
        $query=$db->prepare('UPDATE forum_mp 
        SET mp_lu = :lu
        WHERE mp_id= :id');
        $query->bindValue(':id',$id_mess, PDO::PARAM_INT);
        $query->bindValue(':lu','1', PDO::PARAM_STR);
        $query->execute();
        $query->CloseCursor();
    }
	
        
break; //La fin !
?>

<?php
case "nouveau": //Nouveau mp
       
   echo '<h3>Nouveau message priv&eacute;</h3>
   Cliquez <a href="./messagesprives.php">ici</a> pour retourner &agrave;
       la messagerie<br /><br />';
   ?>
   <form method="post" action="postok.php?action=nouveaump" name="formulaire">
   <p>
   <label for="titre">Envoyer &agrave; : </label>
   <select name="to" >
   <option value=""></option>
   <?php $query=$db->prepare('SELECT identifiant,je_suis FROM validation  ');
    $query->execute();
	while($data=$query->fetch()  )
	{
         echo'<option value="'.stripslashes(htmlspecialchars($data['identifiant'])).'">'.stripslashes(htmlspecialchars($data['identifiant'])).'('.stripslashes(htmlspecialchars($data['je_suis'])).')</option>';
    }
	$query->CloseCursor();
   ?>
   </select>
   <br />
   <label for="titre">Titre : </label>
   <input type="text" size="74" id="titre" name="titre" />
   <br /><br />
   
  

   <textarea cols="81" rows="8" id="message" name="message"></textarea>
   <br />
   <input type="submit" name="submit" value="Envoyer" />
   <input type="reset" name="Effacer" value="Effacer" /></p>
   </form>

<?php   
break;
 ?>

<?php
case "repondre": //On veut répondre
   echo '<h3>R&eacute;pondre &agrave; un message priv&eacute;</h3><br /><br />
   Cliquez <a href="./messagesprives.php">ici</a> pour retourner &agrave;
       la messagerie';

   $dest = (int) $_GET['dest'];
   ?>
   <form method="post" action="postok.php?action=repondremp&amp;dest=<?php echo $dest ?>" name="formulaire">
   <p>
   <label for="titre">Titre : </label><input type="text" size="80" id="titre" name="titre" />
   
   
   <br /><br />
   <textarea cols="80" rows="8" id="message" name="message"></textarea>
   <br />
   <input type="submit" name="submit" value="Envoyer" />
   <input type="reset" name="Effacer" value="Effacer"/>
   </p></form>
   <?php
break;
?>



<?php
    case "supprimer"://4eme cas : on veut supprimer un mp reçu
       
    //On récupère la valeur de l'id
    $id_mess = (int) $_GET['id'];
    //Il faut vérifier que le membre est bien celui qui a reçu le message
    $query=$db->prepare('SELECT mp_receveur
    FROM forum_mp WHERE mp_id = :id');
    $query->bindValue(':id',$id_mess,PDO::PARAM_INT);
    $query->execute();
    $data = $query->fetch();
    //Sinon LA PERSONNE NE PEUT PAS ACCEDER A CETTE PAGE
    if ($id != $data['mp_receveur']) erreur(ERR_WRONG_USER);
    $query->CloseCursor(); 

    //2 cas pour cette partie : on est sûr de supprimer ou alors on ne l'est pas
    $sur = (int) $_GET['sur'];
    //Pas encore certain
    if ($sur == 0)
    {
    echo'<p>Etes-vous certain de vouloir supprimer ce message ?<br />
    <a href="./messagesprives.php?action=supprimer&amp;id='.$id_mess.'&amp;sur=1">
    Oui</a> - <a href="./messagesprives.php">Non</a></p>';
    }
    //Certain
    else
    {
        $query=$db->prepare('DELETE from forum_mp WHERE mp_id = :id');
        $query->bindValue(':id',$id_mess,PDO::PARAM_INT);
        $query->execute();
        $query->CloseCursor(); 
        echo'<p>Le message a bien &eacute;t&eacute; supprim&eacute;.<br />
        Cliquez <a href="./messagesprives.php">ici</a> pour revenir &agrave; la boite
        de messagerie.</p>';
    }
	
    break;
?>

 <?php
default; //Si rien n'est demandé ou s'il y a une erreur dans l'url, on affiche la boite de mp.
 echo '<br /><fieldset class="cro5"><legend><h3>Boite de r&eacute;ception</h3></legend><br />';

    $query=$db->prepare('SELECT mp_lu, mp_id, mp_expediteur, mp_titre,mp_text, mp_time, id, identifiant
    FROM forum_mp
    LEFT JOIN validation ON forum_mp.mp_expediteur = validation.id
    WHERE mp_receveur = :id ORDER BY mp_id DESC');
    $query->bindValue(':id',$id,PDO::PARAM_INT);
    $query->execute();
    echo'<p><a href="./messagesprives.php?action=nouveau">
    <img src="./images/nouveau.gif" alt="Nouveau" title="Nouveau message" />
    </a></p>';
    if ($query->rowCount()>0)
    {
        ?>
        <table>
        <tr>
        <th></th>
        <th class="mp_titre"><strong>Message</strong></th>
        <th class="mp_expediteur"><strong>Exp&eacute;diteur</strong></th>
        <th class="mp_time"><strong>Date</strong></th>
        <th><strong>Action</strong></th>
        </tr>

        <?php
        //On boucle et on remplit le tableau
        while ($data = $query->fetch())
        {
            echo'<tr>';
            //Mp jamais lu, on affiche l'icone en question
            if($data['mp_lu'] == 0)
            {
            echo'<td><a href="./messagesprives.php?action=consulter&amp;id='.$data['mp_id'].'"><img src="./images/message.gif" alt="Non lu" /></a></td>';
            }
            else //sinon une autre icone
            {
            echo'<td><a href="./messagesprives.php?action=consulter&amp;id='.$data['mp_id'].'">
            <img src="./images/message.gif" alt="Déja lu" /></a></td>';
            }
            echo'<td id="mp_titre">
            <a href="./messagesprives.php?action=consulter&amp;id='.$data['mp_id'].'">
            '.stripslashes(htmlspecialchars($data['mp_text'])).'</a></td>
            <td id="mp_expediteur">
            <a href="./voirprofile.php?action=consulter_membre&amp;m='.$data['id'].'">
            '.stripslashes(htmlspecialchars($data['identifiant'])).'</a></td>
            <td id="mp_time">'.date('H\hi \l\e d M Y',$data['mp_time']).'</td>
            <td>
            <a href="./messagesprives.php?action=supprimer&amp;id='.$data['mp_id'].'&amp;sur=0">supprimer</a></td></tr>';
        } //Fin de la boucle
        $query->CloseCursor();
        echo '</table></fieldset>';

    } //Fin du if
    else
    {
        echo'<p class="titre">Vous n\'avez aucun message priv&eacute; pour l\'instant,</br> cliquez
        <a href="./index.php">ici</a> pour revenir &agrave; la page d \'acceuil</p>';
    }
	
}echo'</br></br>'; //Fin du switch
?>
</div>
</div>
</body>
</html>
