<?php
include ('settings.php');

$regNum = $_GET['reg'];

// Function qui renvoie un partie de la requete SQL pour selectionner les departements d'une region
$sqlReqDep = '';

switch ($regNum) {
	case "1":
		$sqlReqDep = " (`$champCodePostal` LIKE '67%'
									OR`$champCodePostal` LIKE '68%')
									AND `$champPays` LIKE 'france'
									";
		break;
		
	case "2":
		$sqlReqDep = "( `$champCodePostal` LIKE '33%'
								OR`$champCodePostal` LIKE '24%'
								OR`$champCodePostal` LIKE '40%'
								OR`$champCodePostal` LIKE '47%'
								OR`$champCodePostal` LIKE '64%')
								AND `$champPays` LIKE 'france'
								";
		break;
		
	case "3":
		$sqlReqDep = "( `$champCodePostal` LIKE '03%'
									OR`$champCodePostal` LIKE '43%'
									OR`$champCodePostal` LIKE '15%'
									OR`$champCodePostal` LIKE '63%')
									AND `$champPays` LIKE 'france'
									";
		break;
		
	case "4":
		$sqlReqDep = " (`$champCodePostal` LIKE '14%'
									OR`$champCodePostal` LIKE '50%'
									OR`$champCodePostal` LIKE '61%')
									AND `$champPays` LIKE 'france'
									";
		break;
		
	case "5":
		$sqlReqDep = "( `$champCodePostal` LIKE '21%'
									OR`$champCodePostal` LIKE '71%'
									OR`$champCodePostal` LIKE '89%'
									OR`$champCodePostal` LIKE '58%')
									AND `$champPays` LIKE 'france'
									";
		break;
		
	case "6":
		$sqlReqDep = " (`$champCodePostal` LIKE '22%'
									OR`$champCodePostal` LIKE '29%'
									OR`$champCodePostal` LIKE '35%'
									OR`$champCodePostal` LIKE '56%')
									AND `$champPays` LIKE 'france'
									";
		break;
		
	case "7":
		$sqlReqDep = " (`$champCodePostal` LIKE '36%'
									OR`$champCodePostal` LIKE '37%'
									OR`$champCodePostal` LIKE '18%'
									OR`$champCodePostal` LIKE '28%'
									OR`$champCodePostal` LIKE '41%'
									OR`$champCodePostal` LIKE '45%')
									AND `$champPays` LIKE 'france'
									";
		break;
		
	case "8":
		$sqlReqDep = "( `$champCodePostal` LIKE '08%'
									OR`$champCodePostal` LIKE '10%'
									OR`$champCodePostal` LIKE '51%'
									OR`$champCodePostal` LIKE '52%')
									AND `$champPays` LIKE 'france'
									";
		break;
		
	case "9":
		$sqlReqDep = " `$champCodePostal` LIKE '20%'
									AND `$champPays` LIKE 'france'
									";
		break;
		
	case "10":
		$sqlReqDep = " `$champCodePostal` LIKE '97%'
									AND `$champPays` LIKE 'france'
									";
		break;
		
	case "11":
		$sqlReqDep = " (`$champCodePostal` LIKE '25%'
									OR`$champCodePostal` LIKE '39%'
									OR`$champCodePostal` LIKE '70%'
									OR`$champCodePostal` LIKE '90%')
									AND `$champPays` LIKE 'france'
									";
		break;
		
	case "12":
		$sqlReqDep = " (`$champCodePostal` LIKE '27%'
									OR`$champCodePostal` LIKE '76%')
									AND `$champPays` LIKE 'france'
									";
		break;
	
	case "13":
		$sqlReqDep = " (`$champCodePostal` LIKE '77%'
									OR`$champCodePostal` LIKE '78%'
									OR`$champCodePostal` LIKE '91%'
									OR`$champCodePostal` LIKE '92%'
									OR`$champCodePostal` LIKE '93%'
									OR`$champCodePostal` LIKE '94%'
									OR`$champCodePostal` LIKE '95%'
									OR`$champCodePostal` LIKE '75%')
									AND `$champPays` LIKE 'france'
									";
		break;
		
	case "14":
		$sqlReqDep = " (`$champCodePostal` LIKE '11%'
									OR`$champCodePostal` LIKE '30%'
									OR`$champCodePostal` LIKE '34%'
									OR`$champCodePostal` LIKE '48%'
									OR`$champCodePostal` LIKE '66%')
									AND `$champPays` LIKE 'france'
									";
		break;
		
	case "15":
		$sqlReqDep = " (`$champCodePostal` LIKE '19%'
									OR`$champCodePostal` LIKE '23%'
									OR`$champCodePostal` LIKE '87%')
									AND `$champPays` LIKE 'france'
									";
		break;
	
	case "16":
		$sqlReqDep = " (`$champCodePostal` LIKE '54%'
									OR`$champCodePostal` LIKE '55%'
									OR`$champCodePostal` LIKE '57%'
									OR`$champCodePostal` LIKE '88%')
									AND `$champPays` LIKE 'france'
									";
		break;
		
	case "17":
		$sqlReqDep = " (`$champCodePostal` LIKE '09%'
									OR`$champCodePostal` LIKE '12%'
									OR`$champCodePostal` LIKE '31%'
									OR`$champCodePostal` LIKE '32%'
									OR`$champCodePostal` LIKE '46%'
									OR`$champCodePostal` LIKE '65%'
									OR`$champCodePostal` LIKE '81%'
									OR`$champCodePostal` LIKE '82%')
									AND `$champPays` LIKE 'france'
									";
		break;
		
	case "18":
		$sqlReqDep = " (`$champCodePostal` LIKE '59%'
									OR`$champCodePostal` LIKE '62%')
									AND `$champPays` LIKE 'france'
									";
	case "19":
		$sqlReqDep = "( `$champCodePostal` LIKE '44%'
									OR`$champCodePostal` LIKE '49%'
									OR`$champCodePostal` LIKE '53%'
									OR`$champCodePostal` LIKE '72%'
									OR`$champCodePostal` LIKE '85%')
									AND `$champPays` LIKE 'france'
									";
		break;
		
	case "20":
		$sqlReqDep = " (`$champCodePostal` LIKE '02%'
									OR`$champCodePostal` LIKE '60%'
									OR`$champCodePostal` LIKE '80%')
									AND `$champPays` LIKE 'france'
									";
		break;
		
	case "21":
		$sqlReqDep = " (`$champCodePostal` LIKE '16%'
									OR`$champCodePostal` LIKE '17%'
									OR`$champCodePostal` LIKE '79%'
									OR`$champCodePostal` LIKE '86%')
									AND `$champPays` LIKE 'france'
									";
		break;
		
	case "22":
		$sqlReqDep = " (`$champCodePostal` LIKE '04%'
									OR`$champCodePostal` LIKE '05%'
									OR`$champCodePostal` LIKE '06%'
									OR`$champCodePostal` LIKE '13%'
									OR`$champCodePostal` LIKE '83%'
									OR`$champCodePostal` LIKE '84%')
									AND `$champPays` LIKE 'france'
									";
		break;
		
	case "23":
		$sqlReqDep = " (`$champCodePostal` LIKE '01%'
									OR`$champCodePostal` LIKE '07%'
									OR`$champCodePostal` LIKE '26%'
									OR`$champCodePostal` LIKE '38%'
									OR`$champCodePostal` LIKE '42%'
									OR`$champCodePostal` LIKE '69%'
									OR`$champCodePostal` LIKE '73%'
									OR`$champCodePostal` LIKE '74%')
									AND `$champPays` LIKE 'france'
									";
		break;
		
	case "24":
		$sqlReqDep = " `$champCodePostal` LIKE '98%'
									AND `$champPays` LIKE 'france'
									";
		break;
		
	case "25":
		$sqlReqDep = " `$champPays`NOT LIKE 'france'
									AND `$champPays`NOT LIKE ''
									";
		break;
}


	
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

$sqlDatas = mysql_query("SELECT `$champId`,`$champNom`, `$champVille`,`$champCodePostal`, `$champPays`
											FROM `$tableUtilisee`
											WHERE ".$sqlReqDep."
											AND `$champWaiting` = '0'
											AND `$champSuspend` = '0'
											AND ".time()." < `$champExpired`
											ORDER BY `$champCodePostal`");




/*$sqlDatas2 = "SELECT `$champId`,`$champNom`, `$champVille`,`$champCodePostal`, `$champPays`
											FROM `$tableUtilisee`
											WHERE ".$sqlReqDep."
											ORDER BY `$champId`";*/




							
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

