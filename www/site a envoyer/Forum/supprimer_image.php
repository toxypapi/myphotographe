<?php
session_start();
$titre="Supprimer une photo";
include("includes/identifiants.php");
include("includes/debut.php");
include('entetenonconnecter.php');
include('menu.php');
?><div class="fond">
<?php
	
    if(isset($_POST['submit']))
   { 
    $id_= (int) $_SESSION['id'];
	$id_im = (int) $_GET['ph']?(int) $_GET['ph']:'';
	$query=$db->prepare('SELECT id_photo,personne,supprimer FROM photo ');
	$query->execute();
	$data = $query->fetch();
	
	if($data['supprimer'] = "oui")
    {	$query=$db->prepare('UPDATE photo SET id_photo=:id_p WHERE personne =:id___');
		$query->bindValue(':id_p',$id_im,PDO::PARAM_INT);
		$query->bindValue(':id___',$id_,PDO::PARAM_INT);
        $query->execute();
		$query->CloseCursor();
        $query=$db->prepare('DELETE from photo WHERE id_photo = :id__');
        $query->bindValue(':id__',$id_im,PDO::PARAM_INT);
        $query->execute();
         
        header('Location:./voirprofile.php?m='.$id_.'');
    }
    
	else 
    {	
		header('Location:./voirprofile.php?m='.$id_.'');
    }
    
	
	
	$query->CloseCursor();
	}
	else
echo'<p>Etes-vous certain de vouloir supprimer cette image ?<br />
    <tr>
		<td>Oui<input type="radio" id="oui" name="supprimer" /> <label for="oui"></label></td>
		<td>Non<input type="radio" id="non" name="supprimer" /> <label for="non"></label></td>
	</tr>';
	$o= (int) $_GET['p']?(int) $_GET['p']:'';
echo'<form method="post" action="supprimer_image.php?ph='.$o.'" enctype="multipart/form-data">		
	<input type="submit"  name="submit" tabindex="20" value="Valider" class="classe_button"/>
	</form>';
	$query->CloseCursor();
	?>
</div>