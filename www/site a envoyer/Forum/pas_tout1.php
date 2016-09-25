<?php
session_start();
$titre="Problème de modification";
include("includes/identifiants.php");
include('entetenonconnecter.php');
include('menu.php');
include("includes/constants.php");
?>
<br /><br /><div class="fond"><br /><br /><br />
<?php 

echo'<div class="titre"><br /><br /><br /><br /><br /><h2>Modification interrompue</h2>';
		$id_= (int) $_SESSION['id'];
        $query=$db->prepare('SELECT id FROM validation ');
		$query->execute();
		$data=$query->fetch();
		$query->CloseCursor();
        echo'<p>Tous les champs doivent &ecirc;tre remplis</p>';
        echo'<p>Cliquez <a href="./voirprofile.php?m='.$id_.'">ici</a> pour recommencer</p></div><br /><br /><br /><br /><br />';

?>
</div>
