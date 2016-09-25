<?php
session_start();
session_destroy();
$titre="Déconnexion";
include("includes/debut.php");
include('entetenonconnecter.php');
include("menu.php");
?><br /><br /><div class="fond"><br /><br /><br />
<?php 
header('Location:index.php');

?>

