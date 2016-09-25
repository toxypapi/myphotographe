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

function verif_auth($auth_necessaire)
{
$level=(isset($_SESSION['level']))?$_SESSION['level']:1;
return ($auth_necessaire <= intval($level));
}
?>


		<script src="./lightbox/js/jquery-1.7.2.min.js"> 
		</script>
		<script src="./lightbox/js/lightbox.js"> 
		</script>
		
<link rel="stylesheet"  href="./lightbox/css/lightbox.css"	/>	
		
<body>	</br></br></br><div id="corps">	
		<?php

		

//On récupère la valeur de nos variables passées par URL
$action = (isset($_GET['action']))?htmlspecialchars($_GET['action']):'consulter';
$membre = isset($_GET['m'])?(int) $_GET['m']:'';
?>
<?php
//On regarde la valeur de la variable $action
switch($action)
{
    //Si c'est "consulter"
    case "consulter":
       //On récupère les infos du membre
?>	   
	   <div class="fond">
	   <a href="deconnexion_forum.php"> Se deconnecter</a></br>
	   <a href="./voirprofile.php?action=modifier&amp;dest='.$data['id'].'">Modifier mon profil</a></br>
	   <a href="modifier_mot_de_passe.php">Modifier mon mot de passe</a></br>
<?php	   if (verif_auth(ADMIN))
			{
				echo'<a class="droite" href="./admin.php">Acces administrateur</a>';
			}
?>
		<fieldset class="cro"><legend ><h1 > Mon profil </h1></legend>
	    
		
		
<?php
       $query=$db->prepare('SELECT identifiant,avatar,email,membre_inscrit,membre_post,je_suis,sexe,nom,prenom,ville,age ,experience,foto_de,password, about,membre_derniere_visite
       FROM validation WHERE id=:membre');
       $query->bindValue(':membre',$membre, PDO::PARAM_INT);
       $query->execute();
       $data=$query->fetch();
	   
       //On affiche les infos sur le membre
?>	   <div>
	   <section id="contenu_gauche3">
<?php	   echo'<a href="./images/avatars/'.$data['avatar'].'" style="color:black" rel = "lightbox"><img src="./images/avatars/'.$data['avatar'].'"
        width="150 px"  border=" 8px solid black" alt="Ce membre n a pas d avatar" /></a>
	   
	   </section>
	   <section id="contenu_centre"></br></br></br>
	   
       <i>'.stripslashes(htmlspecialchars($data['identifiant'])).'</i></br>';
?>	        
<?php    
	   if($data['sexe']=="homme")
			{
				  echo "Homme";
			} 
		else echo "Femme";
?>
<?php	 echo'</br>Habite &agrave; 
       '.stripslashes(htmlspecialchars($data['ville'])).'</a></br>';
		
?> 
	
	   <?php		
		
		 if($data['experience']=="debutant" && $data['je_suis']=="photographe")
			{
				  echo "Photographe Debutant";
			} 	   
				                         
			else if($data['experience']=="amateur" && $data['je_suis']=="photographe" && $data['sexe']=="homme")
			{
				 echo "Photographe Amateur";   
			}
			else if($data['experience']=="amateur" && $data['je_suis']=="photographe" && $data['sexe']=="femme")
			{
				 echo "Photographe Amatrice)";   
			}
				   
		    else if($data['experience']=="experimente" && $data['je_suis']=="photographe" && $data['sexe']=="homme")
			{
				 echo "Photographe Experimente";            
			}
			else if($data['experience']=="experimente" && $data['je_suis']=="photographe" && $data['sexe']=="femme")
			{
				 echo "Photographe Experiment&eacute;e";            
			}			
			else if($data['experience']=="debutant" && $data['je_suis']=="model")
		    {
				 echo "Modele Debutant";
			}
			else if($data['experience']=="amateur" && $data['je_suis']=="model" && $data['sexe']=="homme")
			{
				 echo "Modele Amateur";
		    }
			else if($data['experience']=="amateur" && $data['je_suis']=="model" && $data['sexe']=="femme")
			{
				 echo "Modele Amatrice";
		    }
			else if($data['experience']=="experimente" && $data['je_suis']=="model" && $data['sexe']=="homme")
		    {
				 echo "Modele Experiment&eacute;e";
			}
			else if($data['experience']=="experimente" && $data['je_suis']=="model" && $data['sexe']=="femme")
		    {
				 echo "Modele Experiment&eacute;e";
			}	            	         
?>			


   </br>Interess&eacute; pour des photos de:
<?php  	    stripslashes(htmlspecialchars($data['foto_de']))	;
			if($data['foto_de']=="mode")
			{
			   echo"Mode";
		    }
		    else if($data['foto_de']=="mariage")
		   {
			   echo"Mariage";
		   }
			else if($data['foto_de']=="baby")
		   {
			   echo"Baby";
		   }
		    else if($data['foto_de']=="famille")
		   {
			   echo"Famille";
		   }
			else if($data['foto_de']=="amis")
		   {
			   echo"Ami(es)";
		   }
			else if($data['foto_de']=="couple")
		   {
			   echo"Couple";
		   }
?>		     
<?php       echo'</br>Adresse E-Mail : 
       <a href="mailto:'.stripslashes($data['email']).'">
       '.stripslashes(htmlspecialchars($data['email'])).'</a>
		</section>
		<section id="contenu_droite"><a id="droite"  href="./messagesprives.php"><img src="./images/envelope.jpg"  width="70 px" title="Boite de r&eacute;ception" /></br>Messagerie</a></section>';
	   echo'</br></br></br></br></br></br ></br ></br ></br ></br ></br ></br>
	   <fieldset class="bordure" ><legend ><h3> A propos de moi: </h3></legend >
       <a>'.stripslashes(htmlspecialchars($data['about'])).'</a></br >
	   </fieldset >';
?></br>
		<?php	
		$query=$db->prepare('SELECT id_photo,image,personne 
		FROM photo WHERE personne=:membre  ') ;
		$query->bindValue(':membre',$membre, PDO::PARAM_INT);
       $query->execute();
       
		
		
		
		$ie=0;
        //On commence la boucle
		echo'Photos<br />';
        while($data=$query->fetch()  )
        {	echo'
		<a href="./images/avatars/'.$data['image'].'" rel = "lightbox[roadtrip]" ><img src="./images/avatars/'.$data['image'].'"
        width="140px" alt="Ce membre n\'a pas d avatar" /></a>
		<a href="supprimer_image.php?p='.$data['id_photo'].'"><img src="./images/supprimer.jpg"
        width="15px"title="Supprimer" /></a>
                
                </tr>
					';
                
				$ie++;
			
			
		}
		if($ie<1)
			{
				echo'<p>Vous ne poss&eacute;dez pas de photos</p>';
			}		 
			  
		else
		{
     
?> 			
 <?php       
		}
		$query->CloseCursor();
?>
		<form method="post" action="ajout_photo.php" enctype="multipart/form-data">		
		<div><label for="photo"tabindex="19">Ajouter une photo :</label><input type="file" name="avatar"/>
		<input type="submit"  name="submit" value="envoyer" class="classe_button"/>
		</div>
		</form>
		</fieldset></br></br></br></div>
<?php
       break;
?>
<?php
//Si c'est "consulter en tant qu'inscrit"
    case "consulter_membre":
       //On récupère les infos du membre
?>
	   
			<div class="fond"><fieldset class="cro"><legend ><h1 > Profil </h1></legend>
	    
		
		
<?php
       $query=$db->prepare('SELECT identifiant,avatar,email,membre_inscrit,membre_post,je_suis,sexe,nom,prenom,ville,age ,experience,foto_de,password, about,membre_derniere_visite
       FROM validation WHERE id=:membre');
       $query->bindValue(':membre',$membre, PDO::PARAM_INT);
       $query->execute();
       $data=$query->fetch();
	   
       //On affiche les infos sur le membre
?>	   <div>
	   <section id="contenu_gauche3">
<?php	   echo'<img src="./images/avatars/'.$data['avatar'].'"
        width="150 px" border=" 3px solid black" alt="Ce membre n a pas d avatar" />
	   
	   </section>
	   <section id="contenu_centre"></br></br></br>
	   
       '.stripslashes(htmlspecialchars($data['identifiant'])).'</br>';
?>	        
<?php    
	   if($data['sexe']=="homme")
			{
				  echo "Homme";
			} 
		else echo "Femme";
?>
<?php	 echo'</br>Habite &agrave; 
       '.stripslashes(htmlspecialchars($data['ville'])).'</a></br>';
		
?>
	
	   <?php		
		
		 if($data['experience']=="debutant" && $data['je_suis']=="photographe")
			{
				  echo "Photographe Debutant";
			} 	   
				                         
			else if($data['experience']=="amateur" && $data['je_suis']=="photographe" && $data['sexe']=="homme")
			{
				 echo "Photographe Amateur";   
			}
			else if($data['experience']=="amateur" && $data['je_suis']=="photographe" && $data['sexe']=="femme")
			{
				 echo "Photographe Amatrice)";   
			}
				   
		    else if($data['experience']=="experimente" && $data['je_suis']=="photographe" && $data['sexe']=="homme")
			{
				 echo "Photographe Experimente";            
			}
			else if($data['experience']=="experimente" && $data['je_suis']=="photographe" && $data['sexe']=="femme")
			{
				 echo "Photographe Experiment&eacute;e";            
			}			
			else if($data['experience']=="debutant" && $data['je_suis']=="model")
		    {
				 echo "Modele Debutant";
			}
			else if($data['experience']=="amateur" && $data['je_suis']=="model" && $data['sexe']=="homme")
			{
				 echo "Modele Amateur";
		    }
			else if($data['experience']=="amateur" && $data['je_suis']=="model" && $data['sexe']=="femme")
			{
				 echo "Modele Amatrice";
		    }
			else if($data['experience']=="experimente" && $data['je_suis']=="model" && $data['sexe']=="homme")
		    {
				 echo "Modele Experimente";
			}
			else if($data['experience']=="experimenté" && $data['je_suis']=="model" && $data['sexe']=="femme")
		    {
				 echo "Modele Experiment&eacute;e";
			}	            	         
?>			


   </br>Interess&eacute; pour des photos de:
<?php  	    stripslashes(htmlspecialchars($data['foto_de']))	;
			if($data['foto_de']=="mode")
			{
			   echo"Mode";
		    }
		    else if($data['foto_de']=="mariage")
		   {
			   echo"Mariage";
		   }
			else if($data['foto_de']=="baby")
		   {
			   echo"Baby";
		   }
		    else if($data['foto_de']=="famille")
		   {
			   echo"Famille";
		   }
			else if($data['foto_de']=="amis")
		   {
			   echo"Ami(es)";
		   }
			else if($data['foto_de']=="couple")
		   {
			   echo"Couple";
		   }
?>		     
<?php       echo'</br>
		</section>';
	   echo'</br></br></br></br></br></br ></br ></br ></br ></br ></br >
	   <fieldset class="bordure" ><legend ><h3> A propos de moi: </h3></legend >
       <a>'.stripslashes(htmlspecialchars($data['about'])).'</a></br >
	   </fieldset ></br ></br ></div>';
?></br>
		<?php	
		$query=$db->prepare('SELECT image,personne 
		FROM photo WHERE personne=:membre  ') ;
		$query->bindValue(':membre',$membre, PDO::PARAM_INT);
       $query->execute();
       
		
		
		
		$ie=0;
        //On commence la boucle
		echo'Photos<br />';
        while($data=$query->fetch()  )
        {	echo'
				<a href="./images/avatars/'.$data['image'].'" rel = "lightbox[roadtrip]"><img src="./images/avatars/'.$data['image'].'"
        width="140px" alt="Ce membre n\'a pas d avatar" /></a>
                
                </tr>
					';
                
				$ie++;
			
			
		}
		if($ie<1)
			{
				echo'<p>Ce membre ne poss&egrave;de pas de photos</p>';
			}		 
			  
		else
		{
 ?>       
			
 <?php       
		}
		$query->CloseCursor();
		echo'</fieldset></br></br></br></div>';
		break;
?>
<?php
    //Si on choisit de modifier son profil
    case "modifier":

        echo '<div class="fond"><fieldset><legend ><h2 >Modifier mon profil</h2></legend>';
    if (empty($_POST['sent'])) // Si on la variable est vide, on peut considérer qu'on est sur la page de formulaire
    {
        //On commence par s'assurer que le membre est connecté
        

        //On prend les infos du membre
        $query=$db->prepare("SELECT identifiant,password,email,ville,nom,prenom,age ,sexe,foto_de,experience,je_suis,avatar, about,membre_inscrit,membre_derniere_visite
        FROM validation WHERE id='{$_SESSION['id']}'");
        
        $query->execute();
        $data=$query->fetch();
		if ($data['id']=0) erreur(ERR_IS_NOT_CO);
		
		

        
        
        echo '<form method="post" action="voirprofile.php?action=modifier" enctype="multipart/form-data">
       
 
        <b><u>Identifiants</u></b><br />
        Pseudo : <strong>'.stripslashes(htmlspecialchars($data['identifiant'])).'</strong><br />       
		Email : <strong>'.stripslashes(htmlspecialchars($data['email'])).'</strong><br />       
        <br />
       ';
 
?>         <b><u>Modification du profil</u></b><br />
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
		
		<b><u>Sexe :</u></b></br>
<?php		if ($data['sexe'] == 'homme')
			{
				echo'<label for="homme">Homme</label><input type="radio" name="sexe" id="sexe" value="homme" checked /></br>  	
				<label for="femme">Femme</label><input type="radio" name="sexe" id="sexe" value="femme" /></br>  ';
			}
		else
			{
				echo'<label for="homme">Homme</label><input type="radio" name="sexe" id="sexe" value="homme"  />  </br>	
				<label for="femme">Femme</label><input type="radio" name="sexe" id="sexe" value="femme" checked/>  </br>';
			}
		
		
?>		
        
		
<?php
echo'		<u><b>Je suis</u></b><br />';
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
?>		
<?php	
		

echo'		<u><b>Mon experience:</u></b><br />';
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
?>		
<?php		echo'<u><b>Ici pour des photos de:</u></b><br />';
		if($data['foto_de'] == 'mode')
		{
				echo'
		<label for="mode">Mode</label><input type="radio" id="mode" name="foto_de" value="mode" checked/></br> 
		<label for="mariage">Mariage</label><input type="radio" id="mariage" name="foto_de" value="mariage" /></br> 
		<label for="baby">Baby</label><input type="radio" id="Baby" name="foto_de" value="baby" /> </br>
		<label for="famille">Famille</label><input type="radio" id="famille"name="foto_de" value="famille" /> </br>
		<label for="amis">Ami(es)</label><input type="radio" id="amis" name="foto_de" value="amis" /> </br>
		<label for="couple">Couple</label><input type="radio" id="couple" name="foto_de" value="couple" /> </br>';
		}
		elseif($data['foto_de'] == 'mariage')
		{
				echo'
		<label for="mode">Mode</label><input type="radio" id="mode" name="foto_de" value="mode" /></br> 
		<label for="mariage">Mariage</label><input type="radio" id="mariage" name="foto_de" value="mariage" checked/></br> 
		<label for="baby">Baby</label><input type="radio" id="Baby" name="foto_de" value="baby" /> </br>
		<label for="famille">Famille</label><input type="radio" id="famille"name="foto_de" value="famille" /> </br>
		<label for="amis">Ami(es)</label><input type="radio" id="amis" name="foto_de" value="amis" /> </br>
		<label for="couple">Couple</label><input type="radio" id="couple" name="foto_de" value="couple" /> </br>';
		}
		elseif($data['foto_de'] == 'baby')
		{
				echo'
		<label for="mode">Mode</label><input type="radio" id="mode" name="foto_de" value="mode" /></br> 
		<label for="mariage">Mariage</label><input type="radio" id="mariage" name="foto_de" value="mariage" /></br> 
		<label for="baby">Baby</label><input type="radio" id="Baby" name="foto_de" value="baby" checked/> </br>
		<label for="famille">Famille</label><input type="radio" id="famille"name="foto_de" value="famille" /> </br>
		<label for="amis">Ami(es)</label><input type="radio" id="amis" name="foto_de" value="amis" /> </br>
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
		<label for="amis">Ami(es)</label><input type="radio" id="amis" name="foto_de" value="amis" checked/> </br>
		<label for="couple">Couple</label><input type="radio" id="couple" name="foto_de" value="couple" /> </br>';
		}
		elseif($data['foto_de'] == 'couple')
		{
				echo'
		<label for="mode">Mode</label><input type="radio" id="mode" name="foto_de" value="mode" /></br> 
		<label for="mariage">Mariage</label><input type="radio" id="mariage" name="foto_de" value="mariage" /></br> 
		<label for="baby">Baby</label><input type="radio" id="Baby" name="foto_de" value="baby" /> </br>
		<label for="famille">Famille</label><input type="radio" id="famille"name="foto_de" value="famille" /> </br>
		<label for="amis">Ami(es)</label><input type="radio" id="amis" name="foto_de" value="amis" /> </br>
		<label for="couple">Couple</label><input type="radio" id="couple" name="foto_de" value="couple" checked/> </br>';
		}
?>		
 <?php
echo'   <u><b>Informations suppl&eacute;mentaires</u></b><br />       
        <label for="avatar">Changer votre avatar :</label>
        <input type="file" name="avatar" id="avatar" />
        (Taille max : 10 ko)<br /><br />
        <label><input type="checkbox" name="delete" value="Delete" />
        Supprimer l\'avatar</label>
        Avatar actuel :
        <img src="./images/avatars/'.$data['avatar'].'"
         width="150" alt="pas d avatar" />
     
        <br /><br />
        <label for="about">A propos de moi :</label>
        <textarea rows="6" cols="40" id="about" name="about">'.stripslashes($data['about']).'</textarea></textarea>
        
		</fieldset>
        <p>
        <input type="submit" value="Modifier son profil" />
        <input type="hidden" id="sent" name="sent" value="1" />
        </p></form></fieldset>';
        $query->CloseCursor();   
    }   
 
    else //Cas du traitement
    {
     //On déclare les variables 

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
    
	
	
    

    //Vérification de l'avatar
 
    if (!empty($_FILES['avatar']['size']))
    {
        //On définit les variables :
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
 
        //Une nouveauté ici : on peut choisir de supprimer l'avatar
        if (isset($_POST['delete']))
        {
                $query=$db->prepare("UPDATE validation
		SET avatar=0 WHERE id = '{$_SESSION['id']}'");
                $query->execute();
                $query->CloseCursor();
        }
		
		//On modifie la table
 
        $query=$db->prepare("UPDATE validation 
        SET je_suis=:je_suis_,sexe=:sexe_,nom=:nom_,prenom=:prenom_,ville=:ville_,age=:age_,experience=:experience_,foto_de=:foto_de_, about=:about_
        WHERE id='{$_SESSION['id']}'");
		
        
	
	$query->bindValue(':je_suis_', $je_suis_, PDO::PARAM_STR);
	$query->bindValue(':sexe_', $sexe_, PDO::PARAM_STR);
	$query->bindValue(':nom_', $nom_, PDO::PARAM_STR);
	$query->bindValue(':prenom_', $prenom_, PDO::PARAM_STR);
	$query->bindValue(':ville_', $ville_, PDO::PARAM_STR);
	$query->bindValue(':age_', $age_, PDO::PARAM_STR);
	$query->bindValue(':experience_', $experience_, PDO::PARAM_STR);
	$query->bindValue(':foto_de_', $foto_de_, PDO::PARAM_STR);
	$query->bindValue(':about_', $about_, PDO::PARAM_STR);
	
	
    $query->execute();
	
    $query->CloseCursor();
	
 
        echo'<u>Modification termin&eacute;e !!!!</u>';
        echo'<p>Votre profil a &eacute;t&eacute; modifi&eacute; avec succ&egrave;s !</p>';
        echo'<p>Cliquez <a href="./index.php">ici</a> 
        pour revenir &agrave; la page d\'accueil</p>';
		
        
	


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
        pour retourner vers votre profil</p></div>';
} //Fin du else
    break;
 
default; //Si jamais c'est aucun de ceux là c'est qu'il y a eu un problème :o
echo'<p>Cette action est impossible</p>';
 
} //Fin du switch
?>
</div>
</body>
</html>