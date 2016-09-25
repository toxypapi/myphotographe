<?php
session_start();
$titre="Administration";
$balises = true;
include("includes/identifiants.php");
include("includes/debut.php");
include('entetenonconnecter.php');
include("menu.php");





function verif_auth($auth_necessaire)
{
$level=(isset($_SESSION['level']))?$_SESSION['level']:1;
return ($auth_necessaire <= intval($level));
}



function erreur($err='')
{
   $mess=($err!='')? $err:'Une erreur inconnue s\'est produite';
   exit('<p>'.$mess.'</p>
   <p>Cliquez <a href="index.php">ici</a> pour revenir � la page d\'accueil</p>');
	
}


// On indique o l'on se trouve
$cat = (isset($_GET['cat']))?htmlspecialchars($_GET['cat']):'';

echo'<p><a href="./admin.php">Retour � l\'administration du forum</a>';
if (!verif_auth(ADMIN)) erreur(ERR_AUTH_ADMIN);

$cat = htmlspecialchars($_GET['cat']); //on r�cup�re dans l'url la variable cat
switch($cat) //1er switch
{
case "config":
    echo'<h1>Configuration du forum</h1>';
    //On r�cup�re les valeurs et le nom de chaque entr�e de la table
    $query=$db->query('SELECT config_nom, config_valeur FROM forum_config');
    //Avec cette boucle, on va pouvoir contr�ler le r�sultat pour voir s'il a chang�
    while($data = $query->fetch())
    {
        if ($data['config_valeur'] != $_POST[$data['config_nom']])
	{
            //On met ensuite � jour
            $valeur = htmlspecialchars($_POST[$data['config_nom']]);
	    $query=$db->prepare('UPDATE forum_config SET config_valeur = :valeur
            WHERE config_nom = :nom');
            $query->bindValue(':valeur', $valeur, PDO::PARAM_STR);
            $query->bindValue(':nom', $data['config_nom'],PDO::PARAM_STR);
            $query->execute();
	}
    }
    $query->CloseCursor();
    //Et le message !
    echo'<br /><br />Les nouvelles configurations ont �t� mises � jour !<br />  
    Cliquez <a href="./admin.php">ici</a> pour revenir � l administration';
break;
?>

<?php
case "forum":
    //Ici forum
    $action = htmlspecialchars($_GET['action']); //On r�cup�re la valeur de action
    switch($action) //2�me switch
    {
    case "creer":

        //On commence par les forums
	if ($_GET['c'] == "f")
	{
	    $titre = $_POST['nom'];
	    $desc = $_POST['desc'];
	    $cat = (int) $_POST['cat'];

	
	    $query=$db->prepare('INSERT INTO forum_forum (forum_cat_id, forum_name, forum_desc) 
	    VALUES (:cat, :titre, :desc)');
            $query->bindValue(':cat',$cat,PDO::PARAM_INT);
            $query->bindValue(':titre',$titre, PDO::PARAM_STR);
            $query->bindValue(':desc',$desc,PDO::PARAM_STR);
            $query->execute();
	    echo'<br /><br />Le forum a �t� cr�� !<br />
	    Cliquez <a href="./admin.php">ici</a> pour revenir � l administration';
	    $query->CloseCursor();
        }
        //Puis par les cat�gories
        elseif ($_GET['c'] == "c")
        {
            $titre = $_POST['nom'];
            $query=$db->prepare('INSERT INTO forum_categorie (cat_nom) VALUES (:titre)');
            $query->bindValue(':titre',$titre, PDO::PARAM_STR); 
            $query->execute();          
            echo'<p>La cat�gorie a �t� cr��e !<br /> Cliquez <a href="./admin.php">ici</a> 
            pour revenir � l administration</p>';
	    $query->CloseCursor();
        }
    break;

?>

<?php
    case "edit":
        echo'<h1>Edition d\' un forum</h1>';
        
        if($_GET['e'] == "editf")
        {
            //R�cup�ration d'informations

	    $titre = $_POST['nom'];
	    $desc = $_POST['desc'];
	    $cat = (int) $_POST['depl'];       

            //V�rification
            $query=$db->prepare('SELECT COUNT(*) 
            FROM forum_forum WHERE forum_id = :id');
            $query->bindValue(':id',(int) $_POST['forum_id'],PDO::PARAM_INT);
            $query->execute();
            $forum_existe=$query->fetchColumn();
            $query->CloseCursor();
            if ($forum_existe == 0) erreur(ERR_FOR_EXIST);

            
            //Mise � jour
            $query=$db->prepare('UPDATE forum_forum 
            SET forum_cat_id = :cat, forum_name = :name, forum_desc = :desc 
            WHERE forum_id = :id');
            $query->bindValue(':cat',$cat,PDO::PARAM_INT);  
            $query->bindValue(':name',$titre,PDO::PARAM_STR);
            $query->bindValue(':desc',$desc,PDO::PARAM_STR);
            $query->bindValue(':id',(int) $_POST['forum_id'],PDO::PARAM_INT);
            $query->execute();
            $query->CloseCursor();
            //Message
            echo'<p>Le forum a �t� modifi� !<br />Cliquez <a href="./admin.php">ici</a> 
            pour revenir � l administration</p>';
        
        }

        elseif($_GET['e'] == "editc")
        {
            //R�cup�ration d'informations
            $titre = $_POST['nom'];

            //V�rification
            $query=$db->prepare('SELECT COUNT(*) 
            FROM forum_categorie WHERE cat_id = :cat');
            $query->bindValue(':cat',(int) $_POST['cat'],PDO::PARAM_INT);
            $query->execute();
            $cat_existe=$query->fetchColumn();
            $query->CloseCursor();
            if ($cat_existe == 0) erreur(ERR_CAT_EXIST);
            
            //Mise � jour
            $query=$db->prepare('UPDATE forum_categorie
            SET cat_nom = :name WHERE cat_id = :cat');
            $query->bindValue(':name',$titre,PDO::PARAM_STR);
            $query->bindValue(':cat',(int) $_POST['cat'],PDO::PARAM_INT);
            $query->execute();
            $query->CloseCursor();

            //Message
            echo'<p>La cat�gorie a �t� modifi�e !<br />
            Cliquez <a href="./admin.php">ici</a> 
            pour revenir � l administration</p>';
        
        }

       elseif($_GET['e'] == "ordref")
        {
            //On r�cup�re les id et l'ordre de tous les forums
            $query=$db->query('SELECT forum_id, forum_ordre FROM forum_forum');
            
            //On boucle les r�sultats
            while($data= $query->fetch())
            {
                $ordre = (int) $_POST[$data['forum_id']]; 
        
                //Si et seulement si l'ordre est diff�rent de l'ancien, on le met � jour
                if ($data['forum_ordre'] != $ordre)
                {
                    $query=$db->prepare('UPDATE forum_forum SET forum_ordre = :ordre
                    WHERE forum_id = :id');
                    $query->bindValue(':ordre',$ordre,PDO::PARAM_INT);
                    $query->bindValue(':id',$data['forum_id'],PDO::PARAM_INT);
                    $query->execute();
                    $query->CloseCursor();
                }
            } 
        $query->CloseCursor();
        //Message
        echo'<p>L ordre a �t� modifi� !<br /> 
        Cliquez <a href="./admin.php">ici</a> pour revenir � l administration</p>';
        }

       elseif($_GET['e'] == "ordrec")
        {
    
            //On r�cup�re les id et les ordres de toutes les cat�gories
            $query=$db->query('SELECT cat_id, cat_ordre FROM forum_categorie');
        
            //On boucle le tout
            while($data = $query->fetch())
            {
                $ordre = (int) $_POST[$data['cat_id']]; 
        
                //On met � jour si l'ordre a chang�
                if($data['cat_ordre'] != $ordre)
                {
                    $query=$db->prepare('UPDATE forum_categorie SET cat_ordre = :ordre
                    WHERE cat_id = :id');
                    $query->bindValue(':ordre',$ordre,PDO::PARAM_INT);
                    $query->bindValue(':id',$data['cat_id'],PDO::PARAM_INT);
                    $query->execute();
                    $query->CloseCursor();
                }
            }
        echo'<p>L ordre a �t� modifi� !<br />
        Cliquez <a href="./admin.php">ici</a> pour revenir � l administration</p>';
        }
    break;
?>

<?php
$auth_view = (int) $_POST['auth_view']; 
?>

<?php
    case "droits":    
        //R�cup�ration d'informations
        $auth_view = (int) $_POST['auth_view'];
        $auth_post = (int) $_POST['auth_post'];
        $auth_topic = (int) $_POST['auth_topic'];
        $auth_annonce = (int) $_POST['auth_annonce'];
        $auth_modo = (int) $_POST['auth_modo'];
        
        //Mise � jour
        $query=$db->prepare('UPDATE forum_forum
        SET auth_view = :view, auth_post = :post, auth_topic = :topic,
        auth_annonce = :annonce, auth_modo = :modo WHERE forum_id = :id');
        $query->bindValue(':view',$auth_view,PDO::PARAM_INT);
        $query->bindValue(':post',$auth_post,PDO::PARAM_INT);
        $query->bindValue(':topic',$auth_topic,PDO::PARAM_INT);
        $query->bindValue(':annonce',$auth_annonce,PDO::PARAM_INT);
        $query->bindValue(':modo',$auth_modo,PDO::PARAM_INT);
        $query->bindValue(':id',(int) $_POST['forum_id'],PDO::PARAM_INT);
        $query->execute();
        $query->CloseCursor();
      
        //Message
        echo'<p>Les droits ont �t� modifi�s !<br />
        Cliquez <a href="./admin.php">ici</a> pour revenir � l administration</p>';
    break;
    } //Fin du switch
 
?>

<?php
case "membres":
//Ici membres
$action = htmlspecialchars($_GET['action']); //On r�cup�re la valeur de action
        switch($action) //2eme switch
        {
        case "edit":
		?>
<?php
    //On d�clare les variables 

    $mdp_erreur = NULL;
    $avatar_erreur = NULL;
    $avatar_erreur1 = NULL;
    $avatar_erreur2 = NULL;
    $avatar_erreur3 = NULL;

    //Encore et toujours notre belle variable $i :p
    $i = 0;      
	$nom_ = $_POST['nom'];
	$prenom_ = $_POST['prenom'];
	$foto_de_ = $_POST['foto_de'];
	$experience_ = $_POST['experience'];
	$about_ = $_POST['about'];
	$je_suis_ = $_POST['je_suis'];
	$sexe_ = $_POST['sexe'];
	$age_ = $_POST['age'];
    $ville_ = $_POST['ville'];
    $password_ = ($_POST['password']);
    $repeatpassword_ = ($_POST['repeatpassword']);
	

//V�rification du mdp
    if ($password_ != $repeatpassword_ || empty($repeatpassword_) || empty($password_))
    {
         $mdp_erreur = "Votre mot de passe et votre confirmation diff�rent ou sont vides";
         $i++;
    }
    

    //V�rification de l'avatar
 
    if (!empty($_FILES['avatar']['size']))
    {
        //On d�finit les variables :
        $maxsize = 300720; //Poid de l'image
        $maxwidth = 1000; //Largeur de l'image
        $maxheight = 1000; //Longueur de l'image
        //Liste des extensions valides
        $extensions_valides = array( 'jpg' , 'jpeg' , 'gif' , 'png', 'bmp' );
 
        if ($_FILES['avatar']['error'] > 0)
        {
        $avatar_erreur = "Erreur lors du tranfsert de l'avatar : ";
		$i++;
        }
        if ($_FILES['avatar']['size'] > $maxsize)
        {
        $i++;
        $avatar_erreur1 = "Le fichier est trop gros :
        (<strong>".$_FILES['avatar']['size']." Octets</strong>
        contre <strong>".$maxsize." Octets</strong>)";
        }
 
        $image_sizes = getimagesize($_FILES['avatar']['tmp_name']);
        if ($image_sizes[0] > $maxwidth OR $image_sizes[1] > $maxheight)
        {
        $i++;
        $avatar_erreur2 = "Image trop large ou trop longue :
        (<strong>".$image_sizes[0]."x".$image_sizes[1]."</strong> contre
        <strong>".$maxwidth."x".$maxheight."</strong>)";
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
    
    

 
    if ($i == 0) // Si $i est vide, il n'y a pas d'erreur
    {
        if (!empty($_FILES['avatar']['size']))
        {
                $nomavatar=move_avatar($_FILES['avatar']);
                $query=$db->prepare("UPDATE validation
                SET avatar = :nomavatar
                WHERE id = '{$_SESSION['id']}'");
                $query->bindValue(':nomavatar',$nomavatar,PDO::PARAM_STR);
                $query->execute();
                $query->CloseCursor();
        }
 
        //Une nouveaut� ici : on peut choisir de supprimer l'avatar
        if (isset($_POST['delete']))
        {
                $query=$db->prepare("UPDATE validation
		SET avatar=0 WHERE id = '{$_SESSION['id']}'");
                $query->execute();
                $query->CloseCursor();
        }
 
        echo'<h3>Modification termin�e</h3>';
        echo'<p>Votre profil a �t� modifi� avec succ�s !</p>';
        echo'<p>Cliquez <a href="./index.php">ici</a> 
        pour revenir � la page d accueil</p>';
		
        //On modifie la table
 
        $query=$db->prepare("UPDATE validation 
        SET je_suis=:je_suis_,sexe=:sexe_,nom=:nom_,prenom=:prenom_,ville=:ville_,age=:age_,experience=:experience_,foto_de=:foto_de_,password=:password_, about=:about_
        WHERE id='{$_SESSION['id']}'");
		
        
	
	$query->bindValue(':je_suis_', $je_suis_, PDO::PARAM_STR);
	$query->bindValue(':sexe_', $sexe_, PDO::PARAM_STR);
	$query->bindValue(':nom_', $nom_, PDO::PARAM_STR);
	$query->bindValue(':prenom_', $prenom_, PDO::PARAM_STR);
	$query->bindValue(':ville_', $ville_, PDO::PARAM_STR);
	$query->bindValue(':age_', $age_, PDO::PARAM_STR);
	$query->bindValue(':experience_', $experience_, PDO::PARAM_STR);
	$query->bindValue(':foto_de_', $foto_de_, PDO::PARAM_STR);
	$query->bindValue(':password_',$password_,PDO::PARAM_STR);
	$query->bindValue(':about_', $about_, PDO::PARAM_STR);
	
	
    $query->execute();
	
    $query->CloseCursor();
    }
    else
    {
        echo'<h1>Modification interrompue</h1>';
        echo'<p>Une ou plusieurs erreurs se sont produites pendant la modification du profil</p>';
        echo'<p>'.$i.' erreur(s)</p>';
        echo'<p>'.$mdp_erreur.'</p>';
        echo'<p>'.$avatar_erreur.'</p>';
        echo'<p>'.$avatar_erreur1.'</p>';
        echo'<p>'.$avatar_erreur2.'</p>';
        echo'<p>'.$avatar_erreur3.'</p>';
        echo'<p> Cliquez <a href="./voirprofile.php?action=modifier">ici</a> pour recommencer</p>';
    }
	echo'<p>Cliquez <a href="./voirprofile.php?m='.$_SESSION['id'].'">ici</a> 
        pour retourner vers votre profil</p>';
} //Fin du else
    break;
?>
<?php
    case "droits":
	$membre =$_POST['pseudo'];
	$rang = (int) $_POST['droits'];
	$query=$db->prepare('UPDATE validation SET membre_rang = :rang
	WHERE LOWER(identifiant) = :pseudo');
        $query->bindValue(':rang',$rang,PDO::PARAM_INT);
        $query->bindValue(':pseudo',strtolower($membre), PDO::PARAM_STR);
        $query->execute();
        $query->CloseCursor();
	echo'<p>Le niveau du membre a �t� modifi� !<br />
	Cliquez <a href="./admin.php">ici</a> pour revenir � l administration</p>';
	
    break;
?>

<?php
    case "ban":
        //Bannissement dans un premier temps
        //Si jamais on n'a pas laiss� vide le champ pour le pseudo
        if (isset($_POST['membre']) AND !empty($_POST['membre']))
        {
            $membre = $_POST['membre'];
            $query=$db->prepare('SELECT id 
            FROM validation WHERE LOWER(identifiant) = :pseudo');    
            $query->bindValue(':pseudo',strtolower($membre), PDO::PARAM_STR);
            $query->execute();
            //Si le membre existe
            if ($data = $query->fetch())
            {
                //On le bannit
                $query=$db->prepare('UPDATE validation SET membre_rang = 0 
                WHERE id = :id');
                $query->bindValue(':id',$data['id'], PDO::PARAM_INT);
                $query->execute();
                $query->CloseCursor();
                echo'<br /><br />
                Le membre '.stripslashes(htmlspecialchars($membre)).' a bien �t� banni !<br />';
            }
            else 
            {
                echo'<p>D�sol�, le membre '.stripslashes(htmlspecialchars($membre)).' n existe pas !
                <br />
                Cliquez <a href="./admin.php?cat=membres&action=ban">ici</a> 
                pour r�essayer</p>';
            }
        }
        //Debannissement ici        
        $query = $db->query('SELECT id FROM validation 
        WHERE membre_rang = 0');
        //Si on veut d�bannir au moins un membre
        if ($query->rowCount() > 0)
        {
	    $i=0;
            while($data= $query->fetch())
            {
                if(isset($_POST[$data['id']]))
                {
	            $i++;
                    //On remet son rang � 2
                    $query=$db->prepare('UPDATE validation SET membre_rang = 2 
                    WHERE id = :id');
                    $query->bindValue(':id',$data['id'],PDO::PARAM_INT);
                    $query->execute();
                    $query->CloseCursor();
                }
            }
	    if ($i!=0)
            echo'<p>Les membres ont �t� d�bannis<br />
            Cliquez <a href="./admin.php">ici</a> pour retourner � l administration</p>';
        }
    break;
       }
  

?>
