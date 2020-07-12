<?php
include ('settings.php');

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//
// Connexion à MySql
$connexSql = mysql_connect($hote , $utilisateur, $motDePasse);
if (!$connexSql) {
    die('Non connect&eacute; : ' . mysql_error());
}

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//
// Connexion à la base de données
$dbConnect = mysql_select_db($baseDeDonnees, $connexSql);
if (!$dbConnect) {
    die ('Impossible d\'utiliser la base : ' . mysql_error());
}

$myId = $_GET['id'];

$selectConstructor = "`$champId`,`$champNom`, `$champVille`,`$champCodePostal`, `$champPays`";

if (count($champFacultatifs) > 0) { // Il y a des champs facultatifs
	while ($key = current($champFacultatifs)) {
        //echo key($champFacultatifs).'<br />';
		$selectConstructor .= ' , `'.key($champFacultatifs).'`';
    	next($champFacultatifs);
	}
}

$sqlDatas = mysql_query("SELECT ".$selectConstructor."
												FROM `$tableUtilisee`
												WHERE `$champId` = '$myId'
												AND `$champWaiting` = '0'
												AND `$champSuspend` = '0'
												AND ".time()." < `$champExpired`
												");

if (!$sqlDatas) {
   die('Impossible d\'ex&eacute;cuter la requ&ecirc;te sqlDatas ' . mysql_error());
}


echo '<?xml version="1.0" encoding="UTF-8" ?>'.retour;
echo tab.'<membre>'.retour;

while ($row = mysql_fetch_array($sqlDatas)) {
	
		echo tab.tab.'<id>'.$row[$champId].'</id>'.retour;
		
		// Nom
		$nom = htmlspecialchars($row[$champNom], ENT_QUOTES);
		$nom = utf8_encode($nom);
		$nom = str_replace(array("\r", "\n"), array('', ''), $nom);
		$nom = stripslashes($nom);
		
		echo tab.tab.'<nom>'.$nom.'</nom>'.retour;
		
		echo tab.tab.'<cp>'.$row[$champCodePostal].'</cp>'.retour;
		
		// Ville
		$ville = htmlspecialchars($row[$champVille], ENT_QUOTES);
		$ville = utf8_encode($ville);
		$ville = str_replace(array("\r", "\n"), array('', ''), $ville);
		$ville = stripslashes($ville);
		$ville = strtoupper($ville);
		
		echo tab.tab.'<ville>'.$ville.'</ville>'.retour;
		
		// Champs facultatifs
		
		foreach($champFacultatifs as $cle=>$valeur) 
		{ 
			// varFac
			$varFac = htmlspecialchars($row[$cle], ENT_QUOTES);
			$varFac = utf8_encode($varFac);
			$varFac = str_replace(array("\r", "\n"), array('', ''), $varFac);
			$varFac = stripslashes($varFac);
			
			// valeur
			$valeur = htmlspecialchars($valeur, ENT_QUOTES);
			$valeur = utf8_encode($valeur);

			echo tab.tab.'<'.$cle.' name="'.$valeur.'">'.$varFac.'</'.$cle.'>'.retour;
		} 
		
}

echo tab.'</membre>'.retour;

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//
// Fermeture connexion
mysql_close($connexSql);
?>

