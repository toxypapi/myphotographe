 <?php
try
{
	$PARAM_hote='localhost'; // le chemin vers le serveur
	$PARAM_nom_bd='validations'; // le nom de votre base de données
	$PARAM_utilisateur='root'; // nom d'utilisateur pour se connecter
	$PARAM_mot_passe=''; // mot de passe de l'utilisateur pour se connecter
	$db = new PDO('mysql:host='.$PARAM_hote.';dbname='.$PARAM_nom_bd, $PARAM_utilisateur, $PARAM_mot_passe);
}
catch (Exception $e)
{
        die('Erreur : ' . $e->getMessage());
		echo 'Erreur : '.$e->getMessage().'<br />';
        echo 'N° : '.$e->getCode();
}
?>
