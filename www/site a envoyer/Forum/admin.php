<?php
session_start();
$titre="Administration";
$balises = true;
include("includes/identifiants.php");
include("includes/debut.php");
include('entetenonconnecter.php');
include('menu.php');
?> 

<?php 

function verif_auth($auth_necessaire)
{
$level=(isset($_SESSION['level']))?$_SESSION['level']:1;
return ($auth_necessaire <= intval($level));
}
?>

<div class="fond"><?php
// On indique ou l'on se trouve
$cat = (isset($_GET['cat']))?htmlspecialchars($_GET['cat']):'';
if (!verif_auth(ADMIN)) erreur(ERR_AUTH_ADMIN);

switch($cat) //1er switch
{

case "config":
	//ici configuration
	echo'<h1>Configuration du forum</h1>';
	echo '<form method="post" action="adminok.php?cat=config">';

	//Le tableau associatif
	$config_name = array(
	"avatar_maxsize" => "Taille maximale de l avatar",
	"avatar_maxh" => "Hauteur maximale de l avatar",
	"avatar_maxl" => "Largeur maximale de l avatar",
	"sign_maxl" => "Taille maximale de la signature",
	"auth_bbcode_sign" => "Autoriser le bbcode dans la signature",
	"pseudo_maxsize" => "Taille maximale du pseudo",
	"pseudo_minsize" => "Taille minimale du pseudo",
	"topic_par_page" => "Nombre de topics par page",
	"post_par_page" => "Nombre de posts par page",
	"forum_titre" => "Titre du forum"
	);
	$query = $db->query('SELECT config_nom, config_valeur FROM forum_config');
	
	while($data=$query->fetch())
	{
           echo '<p><label for='.$data['config_nom'].'>'.$config_name[$data['config_nom']].' </label> :
           <input type="text" id="'.$data['config_nom'].'" value="'.$data['config_valeur'].'" name="'.$data['config_nom'].'"></p>';
	}
	echo '<p><input type="submit" value="Envoyer" /></p></form>';
	$query->CloseCursor();
break;

 
case "forum":
//Ici forum
$action = htmlspecialchars($_GET['action']); //On récupère la valeur de action
        switch($action) //2eme switch
        {
		
    case "creer":
        //Création d'un forum

        //1er cas : pas de variable c
        if(empty($_GET['c']))
        {
                echo'<br /><br /><br />Que voulez-vous faire?<br />
                <a href="./admin.php?cat=forum&action=creer&c=f">Créer un forum</a><br />
                <a href="./admin.php?cat=forum&action=creer&c=c">Créer une catégorie</a></br>';
        }

        //2ème cas : on cherche à créer un forum (c=f)
        elseif($_GET['c'] == "f")
        {
                $query=$db->query('SELECT cat_id, cat_nom FROM forum_categorie
                ORDER BY cat_ordre DESC');
                echo'<h1>Création d un forum</h1>';
                echo'<form method="post" action="./adminok.php?cat=forum&action=creer&c=f">';
                echo'<label>Nom :</label><input type="text" id="nom" name="nom" /><br /><br />
                <label>Description :</label>
                <textarea cols=40 rows=4 name="desc" id="desc"></textarea>
                <br /><br />
                <label>Catégorie : </label><select name="cat">';
                while($data = $query->fetch())
                {
		    echo'<option value="'.$data['cat_id'].'">'.$data['cat_nom'].'</option>';
                }
                echo'</select><br /><br />
                <input type="submit" value="Envoyer"></form>';
		$query->CloseCursor();
        }       
        //3ème cas : on cherche à créer une catégorie (c=c)
        elseif($_GET['c'] == "c")
        {
                echo'<h1>Création d une catégorie</h1>';
                echo'<form method="post" action="./adminok.php?cat=forum&action=creer&c=c">';
                echo'<label> Indiquez le nom de la catégorie :</label>
                <input type="text" id="nom" name="nom" /><br /><br />   
                <input type="submit" value="Envoyer"></form>';
        }
    break;
?>

        
        <?php
    case "edit":
        //Edition d'un forum
        echo'<h1>Edition d un forum</h1>';
       
        if(!isset($_GET['e']))
        {
                echo'<p>Que voulez vous faire ?<br />
                <a href="./admin.php?cat=forum&action=edit&amp;e=editf">
                Editer un forum</a><br />
                <a href="./admin.php?cat=forum&action=edit&amp;e=editc">
                Editer une catégorie</a><br />
                <a href="./admin.php?cat=forum&action=edit&amp;e=ordref">
                Changer l ordre des forums</a><br />
                <a href="./admin.php?cat=forum&action=edit&amp;e=ordrec">
                Changer l ordre des catégories</a>
                <br /></p>';
        }

        elseif($_GET['e'] == "editf")
        {
            //On affiche dans un premier temps la liste des forums
            if(!isset($_POST['forum']))
            {
		$query=$db->query('SELECT forum_id, forum_name
		FROM forum_forum ORDER BY forum_ordre DESC');
			   
		echo'<form method="post" action="admin.php?cat=forum&amp;action=edit&amp;e=editf">';
		echo'<p>Choisir un forum :</br /></h2>
		<select name="forum">';
				   
		while($data = $query->fetch())
		{
		    echo'<option value="'.$data['forum_id'].'">
		    '.stripslashes(htmlspecialchars($data['forum_name'])).'</option>';
		}
		echo'<input type="submit" value="Envoyer"></p></form>';
		$query->CloseCursor();

            }               
            //Ensuite, on affiche les renseignements sur le forum choisi
            else
            {
	        $query = $db->prepare('SELECT forum_id, forum_name, forum_desc,
		forum_cat_id
		FROM forum_forum
		WHERE forum_id = :forum');
		$query->bindValue(':forum',(int) $_POST['forum'],PDO::PARAM_INT);
		$query->execute();
				
		$data1 = $query->fetch();

		echo'<p>Edition du forum
		<strong>'.stripslashes(htmlspecialchars($data1['forum_name'])).'</strong></p>';
				   
		echo'<form method="post" action="adminok.php?cat=forum&amp;action=edit&amp;e=editf">
		<label>Nom du forum : </label><input type="text" id="nom"
		name="nom" value="'.$data1['forum_name'].'" />
		<br />
				   
		<label>Description :</label><textarea cols=40 rows=4 name="desc"
		id="desc">'.$data1['forum_desc'].'</textarea><br /><br />';
		$query->CloseCursor();				  
		//A partir d'ici, on boucle toutes les catégories,
		//On affichera en premier celle du forum

		$query = $db->query('SELECT cat_id, cat_nom
		FROM forum_categorie ORDER BY cat_ordre DESC');
		echo'<label>Déplacer le forum vers : </label>
		<select name="depl">';
		while($data2 = $query->fetch())
		{
		    if($data2['cat_id'] == $data1['forum_cat_id'])
		    {
		    echo'<option value="'.$data2['cat_id'].'" 
                    selected="selected">'.stripslashes(htmlspecialchars($data2['cat_nom'])).' 
                    </option>';
		    }
		    else
		    {
		        echo'<option value="'.$data2['cat_id'].'">'.$data2['cat_nom'].'</option>';
		    }
	        }
	        echo'</select><input type="hidden" name="forum_id" value="'.$data1['forum_id'].'">';
	        echo'<p><input type="submit" value="Envoyer"></p></form>';
	        $query->CloseCursor();				  
				
            }
        }

        elseif($_GET['e'] == "editc")
        {
            //On commence par afficher la liste des catégories
            if(!isset($_POST['cat']))
            {
	        $query = $db->query('SELECT cat_id, cat_nom
		FROM forum_categorie ORDER BY cat_ordre DESC');
		echo'<form method="post" action="admin.php?cat=forum&amp;action=edit&amp;e=editc">';
		echo'<p>Choisir une catégorie :</br />
		<select name="cat">';
		while($data = $query->fetch())
		{
		    echo'<option value="'.$data['cat_id'].'">'.$data['cat_nom'].'</option>';
		}
		echo'<input type="submit" value="Envoyer"></p></form>';		
                $query->CloseCursor();				  					
            }         
            //Puis le formulaire
            else
            {
	        $query = $db->prepare('SELECT cat_nom FROM forum_categorie
	        WHERE cat_id = :cat');
		$query->bindValue(':cat',(int) $_POST['cat'],PDO::PARAM_INT);
		$query->execute();
		$data = $query->fetch();
		echo'<form method="post" action="./adminok.php?cat=forum&amp;action=edit&amp;e=editc">';

		echo'<label> Indiquez le nom de la catégorie :</label>
		<input type="text" id="nom" name="nom"
		value="'.stripslashes(htmlspecialchars($data['cat_nom'])).'" />
		<br /><br />   
		<input type="hidden" name="cat" value="'.$_POST['cat'].'" />
		<input type="submit" value="Envoyer" /></p></form>';
		$query->CloseCursor();				  
				
            }
        }

        elseif($_GET['e'] == "ordref")
        {
            $categorie="";
            $query = $db->query('SELECT forum_id, forum_name, forum_ordre,
            forum_cat_id, cat_id, cat_nom
            FROM forum_categorie
            LEFT JOIN forum_forum ON cat_id = forum_cat_id
            ORDER BY cat_ordre DESC');

            echo'<form method="post"
            action="adminok.php?cat=forum&amp;action=edit&amp;e=ordref">';
               
            echo '<table>';

            while($data = $query->fetch())
            {
	        if( $categorie !== $data['cat_id'] )
		{
		    $categorie = $data['cat_id'];
		    echo'
		    <tr>       
	            <th><strong>'.stripslashes(htmlspecialchars($data['cat_nom'])).'</strong></th>
		    <th><strong>Ordre</strong></th>
		    </tr>';
		}
		echo'<tr><td>
                <a href="./voirforum.php?f='.$data['forum_id'].'">'.$data['forum_name'].'</a></td>
		<td><input type="text" value="'.$data['forum_ordre'].'" name="'.$data['forum_id'].'" />
                </td></tr>';
            }
            echo'</table>
            <p><input type="submit" value="Envoyer" /></p></form>';
				
        }

        elseif($_GET['e'] == "ordrec")
        {
            $query = $db->query('SELECT cat_id, cat_nom, cat_ordre
            FROM forum_categorie
            ORDER BY cat_ordre DESC');
 
            echo'<form method="post" action="adminok.php?cat=forum&amp;action=edit&amp;e=ordrec">';
            while($data = $query->fetch())
            {
		echo'<label>'.stripslashes(htmlspecialchars($data['cat_nom'])).' :</label>
		<input type="text" value="'.$data['cat_ordre'].'"name="'.$data['cat_id'].'" /><br /><br />';
            }
            echo '<input type="submit" value="Envoyer" /></form>';
            $query->CloseCursor();				  					
        }
    break;
?>


        
       <?php
    case "droits":
        //Gestion des droits
        echo'<h1>Edition des droits</h1>';     
       
        if(!isset($_POST['forum']))
        {
            $query=$db->query('SELECT forum_id, forum_name
            FROM forum_forum ORDER BY forum_ordre DESC');
            echo'<form method="post" action="admin.php?cat=forum&action=droits">';
            echo'<p>Choisir un forum :</br />
            <select name="forum">';
            while($data = $query->fetch())
            {
                echo'<option value="'.$data['forum_id'].'">'.$data['forum_name'].'</option>';
            }
            echo'<input type="submit" value="Envoyer"></p></form>';
            $query->CloseCursor();				  					
        }
        else
        {
	    $query = $db->prepare('SELECT forum_id, forum_name, auth_view,
	    auth_post, auth_topic, auth_annonce, auth_modo
	    FROM forum_forum WHERE forum_id = :forum');
	    $query->bindValue(':forum',(int) $_POST['forum'], PDO::PARAM_INT);
	    $query->execute();
 
            echo '<form method="post" action="adminok.php?cat=forum&action=droits"><p><table><tr>
	    <th>Lire</th>
	    <th>Répondre</th>
	    <th>Poster</th>
	    <th>Annonce</th>
	    <th>Modérer</th>
	    </tr>';
	    $data = $query->fetch();
		   
	    //Ces deux tableaux vont permettre d'afficher les résultats
	    $rang = array(
            VISITEUR=>"Visiteur",
            INSCRIT=>"Membre", 
            MODO=>"Modérateur",
            ADMIN=>"Administrateur");
	    $list_champ = array("auth_view", "auth_post", "auth_topic","auth_annonce", "auth_modo");
	 
	    //On boucle
	    foreach($list_champ as $champ)
	    {
	        echo'<td><select name="'.$champ.'">';
		for($i=1;$i<5;$i++)
		{
		    if ($i == $data[$champ])
		    {
		        echo'<option value="'.$i.'" selected="selected">'.$rang[$i].'</option>';
		    }	
		    else
		    {
		        echo'<option value="'.$i.'">'.$rang[$i].'</option>';
		    }
		}
		echo'</td></select>';
	    }	
	    echo'<br /><input type="hidden" name="forum_id" value="'.$data['forum_id'].'" />
	    <input type="submit" value="Envoyer"></p></form>';			          

            $query->CloseCursor();				  					

        }
        echo '</table>';
    break;
?>
<?php
        
        default; //action n'est pas remplie, on affiche le menu
        echo'<h1>Administration des forums</h1>';
        echo'<p>Bonjour, que souhaitez vous faire ?
        <br />
        <a href="./admin.php?cat=forum&amp;action=creer">Créer un forum</a>
        <br />
        <a href="./admin.php?cat=forum&amp;action=edit">Modifier un forum</a>
        <br />
        <a href="./admin.php?cat=forum&amp;action=droits">
        Modifier les droits d un forum</a><br /></p>';
        break;
        }

 
case "membres":
//Ici membres
$action = htmlspecialchars($_GET['action']); //On récupère la valeur de action
        switch($action) //2eme switch
        {
        case "edit":
            echo'<h1>Edition du profil d\'un membre</h1>';   

            if(!isset($_POST['membre'])) //Si la variable $_POST['membre'] n'existe pas
            {
                echo'De quel membre voulez-vous éditer le profil ?<br />';
                echo'<br /><form method="post" action="./admin.php?cat=membres&amp;action=edit">
                <p><label for="membre">Inscrivez le pseudo : </label> 
                <input type="text" id="membre" name="membre"><input type="submit" name="Chercher">
                </p></form>';
            }
        

        else //sinon
        {
            $pseudo_d = $_POST['membre'];

            //Requête qui ramène des info sur le membre à 
            //Partir de son pseudo
            $query = $db->prepare('SELECT id,je_suis,sexe,nom,prenom,ville,age ,email,experience,foto_de,identifiant,password,avatar, about,membre_inscrit,membre_derniere_visite,membre_rang,membre_post
            FROM validation WHERE LOWER(identifiant)=:pseudo');
	    $query->bindValue(':pseudo',strtolower($pseudo_d),PDO::PARAM_STR);
            $query->execute();    
            //Si la requête retourne un truc, le membre existe
            if ($data = $query->fetch()) 
            {
		
?>	    <form method="post" action="adminok.php?cat=membres&amp;action=edit" enctype="multipart/form-data">

			
<?php		
			
?>		
		<fieldset><legend>Identifiants</legend>
<?php        echo'Pseudo : <strong>'.stripslashes(htmlspecialchars($data['identifiant'])).'</strong><br />       
		     
        <label for="password">Nouveau mot de Passe :</label>
        <input type="password" name="password" id="password" value="'.stripslashes($data['password']).'"/><br />
        <label for="repeatpassword">Confirmer le mot de passe :</label>
        <input type="password" name="repeatpassword" id="repeatpassword" value="'.stripslashes($data['password']).'" /><br />';
 ?>       </fieldset>
		<fieldset><legend>Renseignements personels</legend>
		<label for="nom">Nom :</label>
		<input type = "text" name="nom" id="nom"
		value="<?php echo stripslashes(htmlspecialchars($data['nom'])) ?>" /><br />
		<label for="prenom">Prenom :</label>
		<input type = "text" name="prenom" id="prenom"
		value="<?php echo stripslashes(htmlspecialchars($data['prenom'])) ?>" /><br />
		<label for="age">Age :</label>
		<input type = "text" name="age" id="age"
		value="<?php echo stripslashes(htmlspecialchars($data['age'])) ?>"/><br />
		<label for="ville">Ville :</label>
		<input type = "text" name="ville" id="ville"
		value="<?php echo stripslashes(htmlspecialchars($data['ville'])) ?>" /><br />
<?php   echo'Email : <strong>'.stripslashes(htmlspecialchars($data['email'])).'</strong><br />';
?>
<?php	echo'<label for="sexe">Sexe :</label>';
		if ($data['sexe'] == 'homme')
			{
				echo'Homme<input type="radio" name="sexe" id="sexe" value="homme" checked />  <label for="homme"></label>	
				Femme<input type="radio" name="sexe" id="sexe" value="femme" />  <label for="femme"></label>';
			}
		else
			
				echo'Homme<input type="radio" name="sexe" id="sexe" value="homme"  />  <label for="homme"></label>	
				Femme<input type="radio" name="sexe" id="sexe" value="femme" checked/>  <label for="femme"></label>
			</fieldset>';
		
		
echo'		<fieldset><legend>Je suis</legend>';
		if($data['je_suis'] == 'photographe')
			{
				echo'
		<label for="photographe">Photographe</label><input type="radio" name="je_suis" id="je_suis" value="photographe" checked> </br>	
		<label for="model">Model</label><input type="radio" id="je_suis"name="je_suis" value="model" /> </br>';
		
			}
			else
			{
				echo'
				<label for="photographe">Photographe</label><input type="radio" name="je_suis" id="je_suis" value="photographe" > </br>	
		<label for="model">Model</label><input type="radio" id="je_suis"name="je_suis" value="model" checked/> </br>';
		}
?>		</fieldset>
<?php	
		

echo'		<fieldset><legend>Mon experience:</legend>';
		if($data['experience'] == 'debutant')
			{
				echo'
		<label for="debutant">Debutant(e)</label><input type="radio" id="debutant" name="experience" value="debutant" checked/></br>
		<label for="amateur">Amateur(rice)</label><input type="radio" id="amateur" name="experience" value="amateur"/></br>
		<label for="experimente">Experimente(e)</label><input type="radio" id="experimente" name="experience"  value="experimente" /></br>';
			}
			elseif($data['experience'] == 'amateur')
			{
				echo'
		<label for="debutant">Debutant(e)</label><input type="radio" id="debutant" name="experience" value="debutant" /></br>
		<label for="amateur">Amateur(rice)</label><input type="radio" id="amateur" name="experience" value="amateur" checked/></br>
		<label for="experimente">Experimente(e)</label><input type="radio" id="experimente" name="experience"  value="experimente" /></br>';
			}
			elseif($data['experience'] == 'experimente')
			{
				echo'
		<label for="debutant">Debutant(e)</label><input type="radio" id="debutant" name="experience" value="debutant" /></br>
		<label for="amateur">Amateur(rice)</label><input type="radio" id="amateur" name="experience" value="amateur" /></br>
		<label for="experimente">Experimente(e)</label><input type="radio" id="experimente" name="experience"  value="experimente" checked/></br>';
			}
?>		</fieldset>
<?php		echo'<fieldset><legend>Ici pour des photos de:</legend>';
		if($data['foto_de'] == 'mode')
		{
				echo'
		<label for="mode">Mode</label><input type="radio" id="mode" name="foto_de" value="mode" checked/></br> 
		<label for="mariage">Mariage</label><input type="radio" id="mariage" name="foto_de" value="mariage" /></br> 
		<label for="baby">Baby</label><input type="radio" id="Baby" name="foto_de" value="baby" /> </br>
		<label for="famille">Famille</label><input type="radio" id="famille"name="foto_de" value="famille" /> </br>
		<label for="ami(es)">Ami(es)</label><input type="radio" id="amis" name="foto_de" value="amis" /> </br>
		<label for="couple">Couple</label><input type="radio" id="couple" name="foto_de" value="couple" /> </br>';
		}
		elseif($data['foto_de'] == 'mariage')
		{
				echo'
		<label for="mode">Mode</label><input type="radio" id="mode" name="foto_de" value="mode" /></br> 
		<label for="mariage">Mariage</label><input type="radio" id="mariage" name="foto_de" value="mariage" checked/></br> 
		<label for="baby">Baby</label><input type="radio" id="Baby" name="foto_de" value="baby" /> </br>
		<label for="famille">Famille</label><input type="radio" id="famille"name="foto_de" value="famille" /> </br>
		<label for="ami(es)">Ami(es)</label><input type="radio" id="amis" name="foto_de" value="amis" /> </br>
		<label for="couple">Couple</label><input type="radio" id="couple" name="foto_de" value="couple" /> </br>';
		}
		elseif($data['foto_de'] == 'baby')
		{
				echo'
		<label for="mode">Mode</label><input type="radio" id="mode" name="foto_de" value="mode" /></br> 
		<label for="mariage">Mariage</label><input type="radio" id="mariage" name="foto_de" value="mariage" /></br> 
		<label for="baby">Baby</label><input type="radio" id="Baby" name="foto_de" value="baby" checked/> </br>
		<label for="famille">Famille</label><input type="radio" id="famille"name="foto_de" value="famille" /> </br>
		<label for="ami(es)">Ami(es)</label><input type="radio" id="amis" name="foto_de" value="amis" /> </br>
		<label for="couple">Couple</label><input type="radio" id="couple" name="foto_de" value="couple" /> </br>';
		}
		elseif($data['foto_de'] == 'famille')
		{
				echo'
		<label for="mode">Mode</label><input type="radio" id="mode" name="foto_de" value="mode" /></br> 
		<label for="mariage">Mariage</label><input type="radio" id="mariage" name="foto_de" value="mariage" /></br> 
		<label for="baby">Baby</label><input type="radio" id="Baby" name="foto_de" value="baby" /> </br>
		<label for="famille">Famille</label><input type="radio" id="famille"name="foto_de" value="famille" checked/> </br>
		<label for="ami(es)">Ami(es)</label><input type="radio" id="amis" name="foto_de" value="amis" /> </br>
		<label for="couple">Couple</label><input type="radio" id="couple" name="foto_de" value="couple" /> </br>';
		}
		elseif($data['foto_de'] == 'amis')
		{
				echo'
		<label for="mode">Mode</label><input type="radio" id="mode" name="foto_de" value="mode" /></br> 
		<label for="mariage">Mariage</label><input type="radio" id="mariage" name="foto_de" value="mariage" /></br> 
		<label for="baby">Baby</label><input type="radio" id="Baby" name="foto_de" value="baby" /> </br>
		<label for="famille">Famille</label><input type="radio" id="famille"name="foto_de" value="famille" /> </br>
		<label for="ami(es)">Ami(es)</label><input type="radio" id="amis" name="foto_de" value="amis" checked/> </br>
		<label for="couple">Couple</label><input type="radio" id="couple" name="foto_de" value="couple" /> </br>';
		}
		elseif($data['foto_de'] == 'couple')
		{
				echo'
		<label for="mode">Mode</label><input type="radio" id="mode" name="foto_de" value="mode" /></br> 
		<label for="mariage">Mariage</label><input type="radio" id="mariage" name="foto_de" value="mariage" /></br> 
		<label for="baby">Baby</label><input type="radio" id="Baby" name="foto_de" value="baby" /> </br>
		<label for="famille">Famille</label><input type="radio" id="famille"name="foto_de" value="famille" /> </br>
		<label for="ami(es)">Ami(es)</label><input type="radio" id="amis" name="foto_de" value="amis" /> </br>
		<label for="couple">Couple</label><input type="radio" id="couple" name="foto_de" value="couple" checked/> </br>';
		}
?>		</fieldset>
				   
		<fieldset><legend>Photo de profil</legend>
		<label for="avatar">Changer l\'avatar :</label>
		<input type="file" name="avatar" id="avatar" />
		<br /><br />
		<label><input type="checkbox" name="delete" value="Delete" /> Supprimer l\'avatar</label>
		Avatar actuel :
		<?php echo'
		<img src="./images/avatars/'.$data['avatar'].' "
       height="150" width="120" alt="pas d avatar" />' ?>
		 
		<br /><br />
		<label for="about">A propos de moi:</label>
		<textarea cols=40 rows=4 name="about" id="about">
		<?php echo $data['about'] ?></textarea>
		
		<br /></h2>
		</fieldset>
		<?php
		echo'<input type="hidden" value="'.stripslashes($pseudo_d).'" name="pseudo_d">
		<input type="submit" value="Modifier le profil" /></form>';
                $query->CloseCursor();

            }
            else echo' <p>Erreur : Ce membre n existe pas, <br />
            cliquez <a href="./admin.php?cat=membres&amp;action=edit">ici</a> pour réessayez</p>';
        }
    break; 
?>

        
        <?php
    case "droits":
        //Droits d'un membre (rang)
        echo'<h1>Edition des droits d\'un membre</h1>';  

        if(!isset($_POST['membre']))
        {
                echo'De quel membre voulez-vous modifier les droits ?<br />';
                echo'<br /><form method="post" action="./admin.php?cat=membres&action=droits">
                <p><label for="membre">Inscrivez le pseudo : </label> 
                <input type="text" id="membre" name="membre">
                <input type="submit" value="Chercher"></p></form>';
        }
        else
        {
            $pseudo_d = $_POST['membre'];
            $query = $db->prepare('SELECT identifiant,membre_rang
            FROM validation WHERE LOWER(identifiant) = :pseudo');
	    $query->bindValue(':pseudo',strtolower($pseudo_d),PDO::PARAM_STR);
            $query->execute();
	    if ($data = $query->fetch())
            {       
                echo'<form action="./adminok.php?cat=membres&amp;action=droits" method="post">';
?>
<?php
                $rang = array
                (0 => "Bannis",
                1 => "Visiteur", 
                2 => "Membre", 
                3 => "Modérateur", 
                4 => "Administrateur"); //Ce tableau associe numéro de droit et nom
                echo'<label>'.$data['identifiant'].'</label>';
                echo'<select name="droits">';
                for($i=0;$i<5;$i++)
                {
		    if ($i == $data['membre_rang'])
		        {
			    echo'<option value="'.$i.'" selected="selected">'.$rang[$i].'</option>';
			}
			else
			{
			    echo'<option value="'.$i.'">'.$rang[$i].'</option>';
			}
                }
		echo'</select>
		<input type="hidden" value="'.stripslashes($pseudo_d).'" name="pseudo">               
		<input type="submit" value="Envoyer"></form>';
                $query->CloseCursor();
            }				  					
            else echo' <p>Erreur : Ce membre n existe pas, <br />
            cliquez <a href="./admin.php?cat=membres&amp;action=edit">ici</a> pour réessayer</p>';
        }
    break;
?>

        
        <?php
    case "ban":
        //Bannissement
        echo'<h1>Gestion du bannissement</h1>'; 

        //Zone de texte pour bannir le membre
        echo'Quel membre voulez-vous bannir ?<br />';
        echo'<br />
        <form method="post" action="./adminok.php?cat=membres&amp;action=ban">
        <label for="membre">Inscrivez le pseudo : </label> 
        <input type="text" id="membre" name="membre">
        <input type="submit" value="Envoyer"><br />';

        //Ici, on boucle : pour chaque membre banni, on affiche une checkbox
        //Qui propose de le débannir
        $query = $db->query('SELECT id, identifiant 
        FROM validation WHERE membre_rang = 0');
        
        //Bien sur, on ne lance la suite que s'il y a des membres bannis !
        if ($query->rowCount() > 0)
        {
        
	    while($data = $query->fetch())
            {
                echo'<br /><label><a href="./voirprofil.php?action=consulter&amp;m='.$data['membre_id'].'">
                '.stripslashes(htmlspecialchars($data['membre_pseudo'])).'</a></label>
                <input type="checkbox" name="'.$data['membre_id'].'" />
                Débannir<br />';
            }
            echo'<p><input type="submit" value="Go !" /></p></form>';
        }
        else echo' <p>Aucun membre banni pour le moment :p</p>';
        $query->CloseCursor();
    break;
?>

   <?php     
        default; //action n'est pas remplie, on affiche le menu 
        echo'<h1>Administration des membres</h1>';
        echo'<p>Que voulez vous faire?<br />
        <a href="./admin.php?cat=membres&amp;action=edit">
        Editer le profil d un membre</a><br />
        <a href="./admin.php?cat=membres&amp;action=droits">
        Modifier les droits d un membre</a><br />
        <a href="./admin.php?cat=membres&amp;action=ban">
        Bannir / Debannir un membre</a><br /></p>';
        
        }
break;
default; //cat n'est pas remplie, on affiche le menu général
echo'<h1>L\'administration</h1>';
echo'<p>Bienvenue sur la page d\'administration.<br />
<a href="./admin.php?cat=config">Configuration du forum</a><br />
<a href="./admin.php?cat=forum">Administration des forums</a><br />
<a href="./admin.php?cat=membres">Administration des membres</a><br /></p>';
break;
}
?>
