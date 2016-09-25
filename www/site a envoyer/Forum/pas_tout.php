<?php
session_start();
$titre="Problème d'inscription";
include("includes/identifiants.php");
include('entetenonconnecter.php');
include('menu.php');

include("includes/constants.php");
?>
<br /><br /><div class="fond"><br /><br /><br />
<?php 

echo'<div class="titre"><br /><br /><br /><br /><br /><h2>Inscription interrompue</h2>';
        
        echo'<p>Tous les champs doivent &ecirc;tre remplis</p>';
        echo'<p>Cliquez <a href="./register.php">ici</a> pour recommencer</p></div><br /><br /><br /><br /><br />';

?>
</div>
