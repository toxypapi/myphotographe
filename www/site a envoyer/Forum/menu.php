<?php

include("includes/identifiants.php");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
<head>
        <meta charset="utf-8" />
		
		<link rel="stylesheet" href="kiff2.css" />
	</head>
<body><div class="titre">

		<nav class="titre">
		  <ul id="menu">
			<li>
                <a href="index.php">Accueil</a>
             </li>
             <li>
              <a href="membre.php">Membres</a>
                 <ul>
                    <li>
					 <a href="photographe.php">Photographe</a>
					</li>
                    <li>
					 <a href="modele.php">Modele</a>
					</li>
                 </ul>
             </li>
        
             <li>
             <a href="a_venir.php">Recherche</a>                        
			 </li>       
		
             <li>
			 <a href="a_venir.php">Forum</a>
             </li>
             <li>			 
			
<?php		$query=$db->prepare('SELECT id FROM validation ');
			$query->execute();
			$data = $query->fetch();
			$query->CloseCursor();
			
			if(isset($_SESSION['id']))
			{
				$id_= (int) $_SESSION['id'];
				echo'<a href="./voirprofile.php?m='.$id_.'">Mon profil</a>';
			}
			else
			{
				echo'<a href="connexion.php">Mon profil</a>';
			}
?>			 </li>
			

			       
          </ul>			
        </nav>
		</div>
	</body> 
	
</html>