 
<?php
session_start();
$titre="Voir un sujet";
include("includes/identifiants.php");
include("includes/debut.php");
include("includes/bbcode.php"); //On verra plus tard ce qu'est ce fichier
include("menu.php"); 
//On récupère la valeur de t
$topic = (int) $_GET['t'];
 
//A partir d'ici, on va compter le nombre de messages pour n'afficher que les 15 premiers
$query=$db->prepare('SELECT topic_titre, topic_post, forum_topic.forum_id, topic_last_post,
forum_name, auth_view, auth_topic, auth_post 
FROM forum_topic 
LEFT JOIN forum_forum ON forum_topic.forum_id = forum_forum.forum_id 
WHERE topic_id = :topic');
$query->bindValue(':topic',$topic,PDO::PARAM_INT);
$query->execute();
$data=$query->fetch();
$forum=$data['forum_id']; 
$totalDesMessages = $data['topic_post'] + 1;
$nombreDeMessagesParPage = 15;
$nombreDePages = ceil($totalDesMessages / $nombreDeMessagesParPage);
?>


<?php
echo '<p><i>Vous êtes ici</i> : <a href="./index.php">Index du forum</a> --> 
<a href="./voirforum.php?f='.$forum.'">'.stripslashes(htmlspecialchars($data['forum_name'])).'</a>
 --> <a href="./voirtopic.php?t='.$topic.'">'.stripslashes(htmlspecialchars($data['topic_titre'])).'</a>';
echo '<h1>'.stripslashes(htmlspecialchars($data['topic_titre'])).'</h1><br /><br />';
?>

<?php
//Nombre de pages
$page = (isset($_GET['page']))?intval($_GET['page']):1;

//On affiche les pages 1-2-3 etc...
echo '<p>Page : ';
for ($i = 1 ; $i <= $nombreDePages ; $i++)
{
    if ($i == $page) //On affiche pas la page actuelle en lien
    {
    echo $i;
    }
    else
    {
    echo '<a href="voirtopic.php?t='.$topic.'&page='.$i.'">
    ' . $i . '</a> ';
    }
}
echo'</p>';
 
$premierMessageAafficher = ($page - 1) * $nombreDeMessagesParPage;

 
if (verif_auth($data['auth_post']))
{
//On affiche l'image répondre
echo'<a href="./poster.php?action=repondre&amp;t='.$topic.'">
<img src="./images/repondre.gif" alt="Répondre" title="Répondre à ce topic" /></a>';
 
 }
 if (verif_auth($data['auth_topic']))
{
//On affiche l'image nouveau topic
echo'<a href="./poster.php?action=nouveautopic&amp;f='.$data['forum_id'].'">
<img src="./images/nouveau.gif" alt="Nouveau topic" title="Poster un nouveau topic" /></a>';
$query->CloseCursor(); 
//Enfin on commence la boucle !

}
?>
<?php
$query=$db->prepare('SELECT post_id , post_createur , post_texte , post_time ,forum_topic.topic_id,
id, identifiant,ville,avatar, membre_inscrit,membre_post,
FROM forum_post
LEFT JOIN validation ON validation.id = forum_post.post_createur
WHERE topic_id = :topic
ORDER BY post_id 
LIMIT :premier, :nombre');
$query->bindValue(':topic',$topic,PDO::PARAM_INT);
$query->bindValue(':premier',(int) $premierMessageAafficher,PDO::PARAM_INT);
$query->bindValue(':nombre',(int) $nombreDeMessagesParPage,PDO::PARAM_INT);
$query->execute();
 
//On vérifie que la requête a bien retourné des messages
if ($query->rowCount()<0)
{
        echo'<p>Il n y a aucun post sur ce topic</p>';
}
else
{
        //Si on récupère des informations de la bd on affiche notre tableau puis on remplit avec une boucle
        ?><table>
        <tr>
        <th class="vt_auteur"><strong>Auteurs</strong></th>             
        <th class="vt_mess"><strong>Messages</strong></th>       
        </tr>
        <?php
        while ($data = $query->fetch())
        {

//On commence à afficher le pseudo du créateur du message :
         
        
         echo'<tr><td><strong>
         <a href="./voirprofile.php?m='.$data['id'].'&amp;action=consulter_membre">
         '.stripslashes(htmlspecialchars($data['identifiant'])).'</a></strong></td>';
           
         // On vérifie que c'est bien celui qui a posté    
   
         if ($id == $data['post_createur'])
         {
         echo'<td id=p_'.$data['post_id'].'>Posté à '.date('H\hi \l\e d M y',$data['post_time']).'
         <a href="./poster.php?p='.$data['post_id'].'&amp;action=delete">
         <img src="./images/supprimer.gif" alt="Supprimer"
         title="Supprimer ce message" /></a>   
         <a href="./poster.php?p='.$data['post_id'].'&amp;action=edit">
         <img src="./images/editer.gif" alt="Editer"
         title="Editer ce message" /></a></td></tr>';
         }
         else
         {
         echo'<td>
         Posté à '.date('H\hi \l\e d M y',$data['post_time']).'
         </td></tr>';
         }
       
         //Détails sur le membre qui a posté
         echo'<tr><td>
         <img src="./images/avatars/'.$data['avatar'].'" alt="" />
         <br />Membre inscrit le '.date('d/m/Y',$data['membre_inscrit']).'
         <br />Messages : '.$data['membre_post'].'<br />
         Localisation : '.stripslashes(htmlspecialchars($data['ville'])).'</td>';
               
         //Message
         echo'<td>'.code(nl2br(stripslashes(htmlspecialchars($data['post_texte'])))).'
         <br /><hr /></td></tr>';
         } //Fin de la boucle ! \o/
         $query->CloseCursor();

         ?>
</table>

<?php
        echo '<p>Page : ';
        for ($i = 1 ; $i <= $nombreDePages ; $i++)
        {
                if ($i == $page) //On affiche pas la page actuelle en lien
                {
                echo $i;
                }
                else
                {
                echo '<a href="voirtopic.php?t='.$topic.'&amp;page='.$i.'">
                ' . $i . '</a> ';
                }
        }
        echo'</p>';
       
        //On ajoute 1 au nombre de visites de ce topic
        $query=$db->prepare('UPDATE forum_topic
        SET topic_vu = topic_vu + 1 WHERE topic_id = :topic');
        $query->bindValue(':topic',$topic,PDO::PARAM_INT);
        $query->execute();
        $query->CloseCursor();

} //Fin du if qui vérifiait si le topic contenait au moins un message
?>           


		
		<?php
$query=$db->prepare('SELECT forum_id, forum_name FROM forum_forum WHERE forum_id <> :forum');
$query->bindValue(':forum',$forum,PDO::PARAM_INT);
$query->execute();

//$forum a été définie tout en haut de la page !
echo'<p>Déplacer vers :</p>
<form method="post" action=postok.php?action=deplacer&amp;t='.$topic.'>
<select name="dest">';               
while($data=$query->fetch())
{
     echo'<option value='.$data['forum_id'].' id='.$data['forum_id'].'>'.$data['forum_name'].'</option>';
}
$query->CloseCursor();
echo'
</select>
<input type="hidden" name="from" value='.$forum.'>
<input type="submit" name="submit" value="Envoyer" />
</form>';
?>

		<?php
$query = $db->prepare('SELECT topic_locked FROM forum_topic WHERE topic_id = :topic');
$query->bindValue(':topic',$topic,PDO::PARAM_INT);
$query->execute();
$data=$query->fetch();

if ($data['topic_locked'] == 1) // Topic verrouillé !
{
    echo'<a href="./postok.php?action=unlock&t='.$topic.'">
    <img src="./images/unlock.gif" alt="deverrouiller" title="Déverrouiller ce sujet" /></a>';
}
else //Sinon le topic est déverrouillé !
{
    echo'<a href="./postok.php?action=lock&amp;t='.$topic.'">
    <img src="./images/lock.gif" alt="verrouiller" title="Verrouiller ce sujet" /></a>';
}
$query->CloseCursor();




?>           
</div>
</body>
</html>