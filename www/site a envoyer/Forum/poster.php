<?php
session_start();
$titre="Poster";
$balises = true;
include("includes/identifiants.php");
include("includes/debut.php");
include('entetenonconnecter.php');
include("menu.php");
?>

<?php


function erreur($err='')
{
   $mess=($err!='')? $err:'Une erreur inconnue s\'est produite';
   exit('<p>'.$mess.'</p>
   <p>Cliquez <a href="index.php">ici</a> pour revenir � la page d\'accueil</p>');
	
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
//Qu'est ce qu'on veut faire ? poster, r�pondre ou �diter ?
$action = (isset($_GET['action']))?htmlspecialchars($_GET['action']):'';

//Il faut �tre connect� pour poster !
if ($id==0) erreur(ERR_IS_CO);

//Si on veut poster un nouveau topic, la variable f se trouve dans l'url,
//On r�cup�re certaines valeurs
if (isset($_GET['f']))
{
    $forum = (int) $_GET['f'];
    $query= $db->prepare('SELECT forum_id,forum_name, auth_view, auth_post, auth_topic, auth_annonce, auth_modo
    FROM forum_forum WHERE forum_id =:forum');
    $query->bindValue(':forum',$forum,PDO::PARAM_INT);
    $query->execute();
    $data=$query->fetch();
    echo '<p><i>Vous �tes ici</i> : <a href="./index.php">Index du forum</a> --> 
    <a href="./voirforum.php?f='.$data['forum_id'].'">'.stripslashes(htmlspecialchars($data['forum_name'])).'</a>
    --> Nouveau topic</p>';

 
}
 
//Sinon c'est un nouveau message, on a la variable t et
//On r�cup�re f gr�ce � une requ�te
elseif (isset($_GET['t']))
{
    $topic = (int) $_GET['t'];
    $query=$db->prepare('SELECT topic_titre, forum_topic.forum_id,
    forum_name, auth_view, auth_post, auth_topic, auth_annonce, auth_modo
    FROM forum_topic
    LEFT JOIN forum_forum ON forum_forum.forum_id = forum_topic.forum_id
    WHERE topic_id =:topic');
    $query->bindValue(':topic',$topic,PDO::PARAM_INT);
    $query->execute();
    $data=$query->fetch();
    $forum = $data['forum_id'];  

    echo '<p><i>Vous �tes ici</i> : <a href="./index.php">Index du forum</a> --> 
    <a href="./voirforum.php?f='.$data['forum_id'].'">'.stripslashes(htmlspecialchars($data['forum_name'])).'</a>
    --> <a href="./voirtopic.php?t='.$topic.'">'.stripslashes(htmlspecialchars($data['topic_titre'])).'</a>
    --> R�pondre</p>';
}
 
//Enfin sinon c'est au sujet de la mod�ration(on verra plus tard en d�tail)
//On ne connait que le post, il faut chercher le reste
elseif (isset ($_GET['p']))
{
    $post = (int) $_GET['p'];
    $query=$db->prepare('SELECT post_createur, forum_post.topic_id, topic_titre, forum_topic.forum_id,
    forum_name, auth_view, auth_post, auth_topic, auth_annonce, auth_modo
    FROM forum_post
    LEFT JOIN forum_topic ON forum_topic.topic_id = forum_post.topic_id
    LEFT JOIN forum_forum ON forum_forum.forum_id = forum_topic.forum_id
    WHERE forum_post.post_id =:post');
    $query->bindValue(':post',$post,PDO::PARAM_INT);
    $query->execute();
    $data=$query->fetch();
	if (!verif_auth($data['auth_view']))
{
    erreur(ERR_AUTH_VIEW);
}

    $topic = $data['topic_id'];
    $forum = $data['forum_id'];
 
    echo '<p><i>Vous �tes ici</i> : <a href="./index.php">Index du forum</a> --> 
    <a href="./voirforum.php?f='.$data['forum_id'].'">'.stripslashes(htmlspecialchars($data['forum_name'])).'</a>
    --> <a href="./voirtopic.php?t='.$topic.'">'.stripslashes(htmlspecialchars($data['topic_titre'])).'</a>
    --> Mod�rer un message</p>';
}
$query->closeCursor();  

switch($action)
{
case "repondre": //Premier cas on souhaite r�pondre
if (verif_auth($data['auth_post']))
{
?>
<h1>Poster une r�ponse</h1>
 
<form method="post" action="postok.php?action=repondre&amp;t=<?php echo $topic ?>" name="formulaire">
 
<fieldset><legend>Mise en forme</legend>
<input type="button" id="gras" name="gras" value="Gras" onClick="javascript:bbcode('[g]', '[/g]');return(false)" />
<input type="button" id="italic" name="italic" value="Italic" onClick="javascript:bbcode('[i]', '[/i]');return(false)" />
<input type="button" id="soulign�" name="soulign�" value="Soulign�" onClick="javascript:bbcode('[s]', '[/s]');return(false)" />
<input type="button" id="lien" name="lien" value="Lien" onClick="javascript:bbcode('[url]', '[/url]');return(false)" />
<br /><br />
<img src="./images/smileys/heureux.gif" title="heureux" alt="heureux" onClick="javascript:smilies(' :D ');return(false)" />
<img src="./images/smileys/lol.gif" title="lol" alt="lol" onClick="javascript:smilies(' :lol: ');return(false)" />
<img src="./images/smileys/triste.gif" title="triste" alt="triste" onClick="javascript:smilies(' :triste: ');return(false)" />
<img src="./images/smileys/cool.gif" title="cool" alt="cool" onClick="javascript:smilies(' :frime: ');return(false)" />
<img src="./images/smileys/rire.gif" title="rire" alt="rire" onClick="javascript:smilies(' XD ');return(false)" />
<img src="./images/smileys/confus.gif" title="confus" alt="confus" onClick="javascript:smilies(' :s ');return(false)" />
<img src="./images/smileys/choc.gif" title="choc" alt="choc" onClick="javascript:smilies(' :o ');return(false)" />
<img src="./images/smileys/question.gif" title="?" alt="?" onClick="javascript:smilies(' :interrogation: ');return(false)" />
<img src="./images/smileys/exclamation.gif" title="!" alt="!" onClick="javascript:smilies(' :exclamation: ');return(false)" />
</fieldset>
 
<fieldset><legend>Message</legend><textarea cols="80" rows="8" id="message" name="message"></textarea></fieldset>
 
<input type="submit" name="submit" value="Envoyer" />
<input type="reset" name = "Effacer" value = "Effacer"/>
</p></form>
<?php
}
break;
 
case "nouveautopic":
?>
 <?php if (verif_auth($data['auth_topic']))
{
?>
<h1>Nouveau topic</h1>
<form method="post" action="postok.php?action=nouveautopic&amp;f=<?php echo $forum ?>" name="formulaire">
 
<fieldset><legend>Titre</legend>
<input type="text" size="80" id="titre" name="titre" /></fieldset>
 
<fieldset><legend>Mise en forme</legend>
<input type="button" id="gras" name="gras" value="Gras" onClick="javascript:bbcode('[g]', '[/g]');return(false)" />
<input type="button" id="italic" name="italic" value="Italic" onClick="javascript:bbcode('[i]', '[/i]');return(false)" />
<input type="button" id="soulign�" name="soulign�" value="Soulign�" onClick="javascript:bbcode('[s]', '[/s]');return(false)" />
<input type="button" id="lien" name="lien" value="Lien" onClick="javascript:bbcode('[url]', '[/url]');return(false)" />
<br /><br />
<img src="./images/smileys/heureux.gif" title="heureux" alt="heureux" onClick="javascript:smilies(':D');return(false)" />
<img src="./images/smileys/lol.gif" title="lol" alt="lol" onClick="javascript:smilies(':lol:');return(false)" />
<img src="./images/smileys/triste.gif" title="triste" alt="triste" onClick="javascript:smilies(':triste:');return(false)" />
<img src="./images/smileys/cool.gif" title="cool" alt="cool" onClick="javascript:smilies(':frime:');return(false)" />
<img src="./images/smileys/rire.gif" title="rire" alt="rire" onClick="javascript:smilies('XD');return(false)" />
<img src="./images/smileys/confus.gif" title="confus" alt="confus" onClick="javascript:smilies(':s');return(false)" />
<img src="./images/smileys/choc.gif" title="choc" alt="choc" onClick="javascript:smilies(':O');return(false)" />
<img src="./images/smileys/question.gif" title="?" alt="?" onClick="javascript:smilies(':interrogation:');return(false)" />
<img src="./images/smileys/exclamation.gif" title="!" alt="!" onClick="javascript:smilies(':exclamation:');return(false)" /></fieldset>
 
<fieldset><legend>Message</legend>
<textarea cols="80" rows="8" id="message" name="message"></textarea>
<?php
if (verif_auth($data['auth_annonce']))
{
    ?>
<label><input type="radio" name="mess" value="Annonce" />Annonce</label>
<label><input type="radio" name="mess" value="Message" checked="checked" />Topic</label>
<?php
}
?>
</fieldset>
<p>
<input type="submit" name="submit" value="Envoyer" />
<input type="reset" name = "Effacer" value = "Effacer" /></p>
</form>

<?php
}
break;
 
//D'autres cas viendront s'ajouter ici par la suite
?>
<?php
case "edit": //Si on veut �diter le post
    //On r�cup�re la valeur de p
    $post = (int) $_GET['p'];
    echo'<h1>Edition</h1>';
 
    //On lance enfin notre requ�te
 
    $query=$db->prepare('SELECT post_createur, post_texte, auth_modo FROM forum_post
    LEFT JOIN forum_forum ON forum_post.post_forum_id = forum_forum.forum_id
    WHERE post_id=:post');
    $query->bindValue(':post',$post,PDO::PARAM_INT);
    $query->execute();
    $data=$query->fetch();

    $text_edit = $data['post_texte']; //On r�cup�re le message

    //Ensuite on v�rifie que le membre a le droit d'�tre ici (soit le cr�ateur soit un modo/admin) 
    if (!verif_auth($data['auth_modo']) && $data['post_createur'] != $id)
    {
        // Si cette condition n'est pas remplie �a va barder :o
        erreur(ERR_AUTH_EDIT);
    }
    else //Sinon �a roule et on affiche la suite
    {
        //Le formulaire de postage
        ?>
        <form method="post" action="postok.php?action=edit&amp;p=<?php echo $post ?>" name="formulaire">
        <fieldset><legend>Mise en forme</legend>
        <input type="button" id="gras" name="gras" value="Gras" onClick="javascript:bbcode('[g]', '[/g]');return(false)" />
        <input type="button" id="italic" name="italic" value="Italic" onClick="javascript:bbcode('[i]', '[/i]');return(false)" />
        <input type="button" id="soulign�" name="soulign�" value="Soulign�" onClick="javascript:bbcode('[s]', '[/s]');return(false)"/>
        <input type="button" id="lien" name="lien" value="Lien" onClick="javascript:bbcode('[url]', '[/url]');return(false)" />
        <br /><br />
        <img src="./images/smileys/heureux.gif" title="heureux" alt="heureux" onClick="javascript:smilies(':D');return(false)" />
        <img src="./images/smileys/lol.gif" title="lol" alt="lol" onClick="javascript:smilies(':lol:');return(false)" />
        <img src="./images/smileys/triste.gif" title="triste" alt="triste" onClick="javascript:smilies(':triste:');return(false)" />
        <img src="./images/smileys/cool.gif" title="cool" alt="cool" onClick="javascript:smilies(':frime:');return(false)" />
        <img src="./images/smileys/rire.gif" title="rire" alt="rire" onClick="javascript:smilies('XD');return(false)" />
        <img src="./images/smileys/confus.gif" title="confus" alt="confus" onClick="javascript:smilies(':s');return(false)" />
        <img src="./images/smileys/choc.gif" title="choc" alt="choc" onClick="javascript:smilies(':O');return(false)" />
        <img src="./images/smileys/question.gif" title="?" alt="?" onClick="javascript:smilies(':interrogation:');return(false)" />
        <img src="./images/smileys/exclamation.gif" title="!" alt="!" onClick="javascript:smilies(':exclamation:');return(false)" />
        </fieldset>
 
        <fieldset><legend>Message</legend><textarea cols="80" rows="8" id="message" name="message"><?php echo $text_edit ?>
        </textarea>
        </fieldset>
        <p>
        <input type="submit" name="submit" value="Editer !" />
        <input type="reset" name = "Effacer" value = "Effacer"/></p>
        </form>
        <?php
    }
break; //Fin de ce cas :o
?>
<?php
case "delete": //Si on veut supprimer le post
    //On r�cup�re la valeur de p
    $post = (int) $_GET['p'];
    //Ensuite on v�rifie que le membre a le droit d'�tre ici
    echo'<h1>Suppression</h1>';
    $query=$db->prepare('SELECT post_createur, auth_modo
    FROM forum_post
    LEFT JOIN forum_forum ON forum_post.post_forum_id = forum_forum.forum_id
    WHERE post_id= :post');
    $query->bindValue(':post',$post,PDO::PARAM_INT);
    $query->execute();
    $data = $query->fetch();
 
    if (!verif_auth($data['auth_modo']) && $data['post_createur'] != $id)
    {
        // Si cette condition n'est pas remplie �a va barder :o
        erreur(ERR_AUTH_DELETE); 
    }
    else //Sinon �a roule et on affiche la suite
    {
        echo'<p>�tes vous certains de vouloir supprimer ce post ?</p>';
        echo'<p><a href="./postok.php?action=delete&amp;p='.$post.'">Oui</a> ou <a href="./index.php">Non</a></p>';
    }
    $query->CloseCursor();
break;
?>

<?php
default: //Si jamais c'est aucun de ceux l� c'est qu'il y a eu un probl�me :o
echo'<p>Cette action est impossible</p>';
} //Fin du switch
?>
</div>
</body>
</html>