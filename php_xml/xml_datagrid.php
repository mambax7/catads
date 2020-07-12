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

$myCp = $_GET['dep'];

if ($myCp != '99') {
	if ($myCp == '2A') {
		//Requete
		$sqlDatas = mysql_query("SELECT `$champId`,`$champNom`, `$champVille`,`$champCodePostal`, `$champPays`
												FROM `$tableUtilisee`
												WHERE (`$champCodePostal` LIKE '201%' OR  `$champCodePostal` LIKE '200%' ) AND
												`$champPays` LIKE 'france'
												AND `$champWaiting` = '0'
												AND `$champSuspend` = '0'
												AND ".time()." < `$champExpired`
												ORDER BY `$champId`");
	} else if ($myCp == '2B') {
		$myCp = '202';
		//Requete
		$sqlDatas = mysql_query("SELECT `$champId`,`$champNom`, `$champVille`,`$champCodePostal`, `$champPays`
												FROM `$tableUtilisee`
												WHERE `$champCodePostal` LIKE '$myCp%' AND
												`$champPays` LIKE 'france'
												AND `$champWaiting` = '0'
												AND `$champSuspend` = '0'
												AND ".time()." < `$champExpired`
												ORDER BY `$champId`");
	} else {
		//Requete
		$sqlDatas = mysql_query("SELECT `$champId`,`$champNom`, `$champVille`,`$champCodePostal`, `$champPays`
												FROM `$tableUtilisee`
												WHERE `$champCodePostal` LIKE '$myCp%' AND
												`$champPays` LIKE 'france'
												AND `$champWaiting` = '0'
												AND `$champSuspend` = '0'
												AND ".time()." < `$champExpired`
												ORDER BY `$champId`");
	}
} else {
// Je demande les pays étranger
//Requete
	$sqlDatas = mysql_query("SELECT `$champId`,`$champNom`, `$champVille`,`$champCodePostal`, `$champPays`
											FROM `$tableUtilisee`
											WHERE
											`$champPays`NOT LIKE 'france' AND 
											`$champPays`NOT LIKE ''
											AND `$champWaiting` = '0'
											AND `$champSuspend` = '0'
											AND ".time()." < `$champExpired`
											ORDER BY `$champId`");
}





if (!$sqlDatas) {
   die('Impossible d\'ex&eacute;cuter la requ&ecirc;te sqlDatas ' . mysql_error());
}
echo '<?xml version="1.0" encoding="UTF-8" ?>'.retour;
echo tab.'<dataProvider>'.retour;

while ($row = mysql_fetch_array($sqlDatas)) {
	
		// Nom
		$nom = htmlspecialchars($row[$champNom], ENT_QUOTES);
		$nom = utf8_encode($nom);
		$nom = str_replace(array("\r", "\n"), array('', ''), $nom);
		$nom = stripslashes($nom);
		
		// Ville
		$ville = htmlspecialchars($row[$champVille], ENT_QUOTES);
		$ville = utf8_encode($ville);
		$ville = str_replace(array("\r", "\n"), array('', ''), $ville);
		$ville = stripslashes($ville);
		$ville = strtoupper($ville);
		
		echo tab.tab.'<data Id="'.intval($row[$champId]).'" Nom="'.$nom.'" CP="'.$row[$champCodePostal].'" Ville="'.$ville.'" />'.retour;
		
}

echo tab.'</dataProvider>'.retour;

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//
// Fermeture connexion
mysql_close($connexSql);
?>

