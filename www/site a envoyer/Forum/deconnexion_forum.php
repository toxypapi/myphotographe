<?php
session_start();
session_destroy();
$titre="D�connexion";
include("includes/debut.php");
include('entetenonconnecter.php');
include("menu.php");
?><br /><br /><div class="fond"><br /><br /><br />
<?php 
header('Location:index.php');

?>

