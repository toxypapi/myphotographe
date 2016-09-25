<?php
session_start();
$titre="Profil";
include("includes/identifiants.php");
include("includes/constants.php");
include('menu.php');

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
<div class="fond">
<?php 	//Vérification des photos :

   
	$i=NULL;
	$image_erreur = NULL;
    $image_erreur1 = NULL;
    $image_erreur3 = NULL;
	$id_= (int) $_SESSION['id'];
    if (!empty($_FILES['avatar']['size']))
    {
        //On définit les variables :
        $maxsize = 100240000000000900; //Poid de l'image
        $maxwidth = 10000000000000900; //Largeur de l'image
        $maxheight = 1000000000000900; //Longueur de l'image
        $extensions_valides = array( 'jpg' , 'jpeg' , 'gif' , 'png', 'bmp' ); //Liste des extensions valides
        
        if ($_FILES['avatar']['error'] > 0)
        {
                $image_erreur = "Erreur lors du transfert de l'image : ";
        }
        if ($_FILES['avatar']['size'] > $maxsize)
        {
                $i++;
                $image_erreur1 = "Le fichier est trop gros : (<strong>".$_FILES['avatar']['size']." Octets</strong>    contre <strong>".$maxsize." Octets</strong>)";
        }

        $image_sizes = getimagesize($_FILES['avatar']['tmp_name']);
        if ($image_sizes[0] > $maxwidth OR $image_sizes[1] > $maxheight)
        {
                $image_sizes[0] == $maxwidth;
                $image_sizes[1] == $maxheight; 
				$nomavatar=imagecreatetruecolor($maxwidth[0],$maxheight[1]);  // REVENIR ICI POUR LE PROB DU GRD AFFICHAGE DES PHOTOS
        }
        
        $extension_upload = strtolower(substr(  strrchr($_FILES['avatar']['name'], '.')  ,1));
        if (!in_array($extension_upload,$extensions_valides) )
        {
                $i++;
                $image_erreur3 = "Extension de l'image incorrecte";
        }
    }
	else header('Location:./voirprofile.php?m='.$id_.'');
?>
<?php
if ($i==0)

   {
   
	if (!empty($_FILES['avatar']['size']))
    {	$query=$db->prepare('SELECT id FROM validation ');
		$query->execute();
		
		if($data = $query->fetch())
		{
				$nomavatar=move_avatar($_FILES['avatar']);
				$query=$db->prepare('INSERT INTO photo
				( image, personne)
				VALUES(:nomavatar, :id)'); 
				$query->bindValue(':nomavatar',$nomavatar,PDO::PARAM_STR);
				$query->bindValue(':id',$id_,PDO::PARAM_INT);        
				$query->execute();
				$query->CloseCursor(); 
        }
		
		header('Location:./voirprofile.php?m='.$id_.'');
	}	
	}
    else
    {
        echo'<p>'.$image_erreur.'</p>';
        echo'<p>'.$image_erreur1.'</p>';
        echo'<p>'.$image_erreur3.'</p>';
    }				

				  
  
?>	   

 </div>      
