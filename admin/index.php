<?php
// $Id: admin/index.php,v 1.3 2005/02/14 the Cat
// ------------------------------------------------------------------------- //
// Catads for Xoops                                                          //
// ------------------------------------------------------------------------- //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//---------------------------------------------------------------------------//

include_once( "admin_header.php" );
include_once '../include/functions.php';
include_once XOOPS_ROOT_PATH."/modules/".$xoopsModule->dirname()."/admin/functions.php";

xoops_cp_header();
catads_admin_menu(0, _AM_CATADS_INDEXMANAGE);

if (isset($_GET['op']))
	$op = $_GET['op'];
elseif (isset($_POST['op']))
	$op = $_POST['op'];
else
	$op = 'show_index';
	

function show_index()
{
	global $xoopsDB, $xoopsModuleConfig, $xoopsModule;

	//Nb de photos du fichier modules/catads/images/ads
	$dirup = XOOPS_ROOT_PATH . "/modules/".$xoopsModule->dirname()."/images/ads/";
	$fileup = 0;
	if (file_exists($dirup))
	{
		$racine = opendir($dirup);
		$taille = 0;
		$fileup = 0;
		while($dossier=@readdir($racine))
			{
				if(!in_array($dossier, Array("..", ".")))
				{
					if(is_dir("$dirup/$dossier"))
					{
						$taille+=taille_dossier("$dirup/$dossier");
					}
					else
					{
						$taille+=@filesize("$dirup/$dossier");
					}
					$fileup++;
				}
			}
		@closedir($racine);
		if ($fileup == 1) { $images = "image"; }else{ $images = "images"; }
	}
	
	if( $fileup != 0 )
	{
		echo "<div class='errorMsg'>";
		echo "<strong>Importation de ".$fileup." ".$images.".</strong><br /><br />
		\""._AM_CATADS_IMPORT_INFO."\"<br />";
	
		echo "<form action='index.php?op=import_images' method=POST>
				<table border=0 cellpadding=2 cellspacing=3>
					<tr>
						<td width='100%' colspan='2' align='center'><br><input type='submit' name='button' id='import' value='"._AM_CATADS_IMPORT."'></td>
					<tr> 
				</table>
			  </form>";
		  
		echo "</div>";

	}

	//Affichage classique

		sf_collapsableBar('toptable', 'toptableicon');
		echo "<img onclick='toggle('toptable'); toggleIcon('toptableicon');' id='toptableicon' name='toptableicon' src=" . XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/images/icon/close12.gif alt='' /></a>&nbsp;" . _AM_CATADS_RECUPADS . "</h3>";
		echo "<div id='toptable'>";
		echo "<br><fieldset>";

		//Compte le nombre total d'annonces
		$resultat = $xoopsDB->queryF("SHOW TABLE STATUS FROM `".XOOPS_DB_NAME."` LIKE '".$xoopsDB->prefix("catads_ads")."'"); 
		$countAdsTotal = $xoopsDB->fetchArray($resultat);
		
		//Compte le nombre total d'annonces en ligne
		$resultat = $xoopsDB->query("SELECT count(*) from ".$xoopsDB->prefix("catads_ads")." WHERE waiting = '0' AND expired > ".time()."");
		list($countAdsTotalLigne)=$xoopsDB->fetchRow($resultat);
		
		//Compte le nombre total d'annonces en attente
		$resultat = $xoopsDB->query("SELECT count(*) from ".$xoopsDB->prefix("catads_ads")." WHERE waiting = '1'");
		list($countAdsTotalWaiting)=$xoopsDB->fetchRow($resultat);
		
		//Compte le nombre total d'annonces expirées
		$resultat = $xoopsDB->query("SELECT count(*) from ".$xoopsDB->prefix("catads_ads")." WHERE expired < ".time()."");
		list($countAdsTotalExpire)=$xoopsDB->fetchRow($resultat);

		//Affichage  du resumé des status des annonces
		printf(_AM_CATADS_COUNTADSTOTAL, $countAdsTotal['Rows']);
		echo "<br /><br />";
		printf(_AM_CATADS_COUNTADSLIGNE, $countAdsTotalLigne);
		echo "<br />";
		printf(_AM_CATADS_COUNTADSWAIT, $countAdsTotalWaiting);
		echo "<br />";
		printf(_AM_CATADS_COUNTADSEXPIRE, $countAdsTotalExpire);
		echo "<br />";
		echo "</fieldset></div><br /><br />";

		sf_collapsableBar('bottomtable', 'bottomtableicon');
			echo "<img onclick='toggle('bottomtable'); toggleIcon('bottomtableicon');' id='bottomtableicon' name='bottomtableicon' src=" . XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/images/icon/close12.gif alt='' /></a>&nbsp;" . _AM_CATADS_STOCK . "</h3>";
		echo "<div id='bottomtable'><br />";	
	
	
	
		echo "<fieldset>";
		//Nb de photos et de la taille du fichier uploads/catads/images/annonces/original
		$dirup = XOOPS_ROOT_PATH . "/uploads/".$xoopsModule->dirname()."/images/annonces/original/";
		$racine=opendir($dirup);
		$taille=0;
		$fileup = 0;
		 while($dossier=@readdir($racine))
			{
				if(!in_array($dossier, Array("..", ".")))
				{
					if(is_dir("$dirup/$dossier"))
					{
						$taille+=taille_dossier("$dirup/$dossier");
					}
					else
					{
						$taille+=@filesize("$dirup/$dossier");
					}
					$fileup++;
				}
			}
			$fileup -= 1;
		@closedir($racine);

		if ($fileup == -1)
		{
			$fileup = 0;
		}

		echo "<fieldset>";
		printf(_AM_CATADS_NBPICTURE_FILE_ORIGINAL, $fileup);
		echo '<br /><pre><strong>' . $dirup.'</strong><br />';
		echo _AM_CATADS_LENGTH.':<b> ' . Size($taille). '</b>';
		echo"</fieldset><br />";
		//Nb de photos et de la taille du fichier uploads/catads/images/annonces/thumb	
		$dirup = XOOPS_ROOT_PATH . "/uploads/".$xoopsModule->dirname()."/images/annonces/thumb/";
		$racine=opendir($dirup);
		$taille=0;
		$fileup = 0;
		 while($dossier=@readdir($racine))
			{
				if(!in_array($dossier, Array("..", ".")))
				{
					if(is_dir("$dirup/$dossier"))
					{
						$taille+=taille_dossier("$dirup/$dossier");
					}
					else
					{
						$taille+=@filesize("$dirup/$dossier");
					}
					$fileup++;
				}
			}
			$fileup -= 1;
		@closedir($racine);

		if ($fileup == -1)
		{
			$fileup = 0;
		}
		echo "<fieldset>";
		printf(_AM_CATADS_NBPICTURE_FILE_THUMB, $fileup);
		echo '<br /><pre><strong>' . $dirup.'</strong><br />';
		echo _AM_CATADS_LENGTH.':<b> ' . Size($taille). '</b>';
		echo"</fieldset><br />";
		//Nb de photos et de la taille du fichier uploads/catads/images/categories
	
		$dirup = XOOPS_ROOT_PATH . "/uploads/".$xoopsModule->dirname()."/images/categories";
		$racine=opendir($dirup);
		$taille=0;
		$fileup = 0;
		 while($dossier=@readdir($racine))
			{
				if(!in_array($dossier, Array("..", ".")))
				{
					if(is_dir("$dirup/$dossier"))
					{
						$taille+=taille_dossier("$dirup/$dossier");
					}
					else
					{
						$taille+=@filesize("$dirup/$dossier");
					}
					$fileup++;
				}
			}
			$fileup -= 1;
		@closedir($racine);

		if ($fileup == -1)
		{
			$fileup = 0;
			//$taille = 0;
		}
		echo "<fieldset>";
		printf(_AM_CATADS_NBPICTURE_FILE_CAT, $fileup);
		echo '<br /><pre><strong>' . $dirup.'</strong><br />';
		echo _AM_CATADS_LENGTH.':<b> ' . Size($taille). '</b>';
		echo"</fieldset>";
		echo"</fieldset>";
	
		echo "</div>";
}
	
//Importation des images des annonces du dossier modules/catads/images/ads vers uploads/catads/images/annonces/original
function import_images()
{
	global $xoopsDB, $xoopsModuleConfig, $xoopsModule;
	
		//Copie des images des annonces et creation de thumb de photo0
		$dirup = XOOPS_ROOT_PATH . "/modules/catads/images/ads/";
		$racine=opendir($dirup);
	
		echo "<table width='100%' cellspacing='1' class='outer'>
				<tr class='bg3'>
					<td align='center'>"._AM_CATADS_NOM_IMAGE."</td>
					<td align='center'>"._AM_CATADS_CREATION_VIGNETTE."</td>
					<td align='center'>"._AM_CATADS_COPIE_IMAGE."</td>
				</tr>";	
		ob_start();
		 while($dossier=@readdir($racine))
			{
				if(!in_array($dossier, Array("..", ".")))
				{				
					$fileCopy = XOOPS_ROOT_PATH . "/modules/catads/images/ads/".$dossier;
					$query = "SELECT thumb FROM ".$xoopsDB->prefix("catads_ads")." WHERE photo0='".$dossier."'";
					//echo "fichier source = ".$fileCopy."<br />";
					$result = $xoopsDB->queryF($query);
					$data = $xoopsDB->fetchArray($result);
					
					if ( $data['thumb'] == '' )
					{
						$thumb = str_replace('img_', 'thumb_',$dossier);
						$thumb_dir = XOOPS_ROOT_PATH . "/uploads/catads/images/annonces/thumb/".$thumb;
						
						if (!file_exists($thumb_dir))
						{
							if( resize_image($fileCopy, $thumb_dir, $xoopsModuleConfig['thumb_width'], $xoopsModuleConfig['thumb_method']) ) 
							{
								$resize = 1;
							} else {
								$resize = 0;
							}
						}
						/*echo "Thumb = ".$thumb."<br>";
						echo "Dossier = ".$dossier."<br>";*/
						$sql = "UPDATE ". $xoopsDB->prefix('catads_ads')." SET thumb = '".$thumb."' WHERE photo0 = '".$dossier."'";
						$result = $xoopsDB->queryF($sql);
					}
					else
					{
						$resize = 2;
					}
					
					//Insertion dans le fichier uploads/catads/images/thumb
					if(copy($fileCopy, XOOPS_ROOT_PATH."/uploads/catads/images/annonces/original/".$dossier."")) $copy = 1;
					
					if ( $copy == 1 ){
						//Copie reussie
						$copy = "<img src='".XOOPS_URL."/modules/".$xoopsModule->dirname()."/images/icon/en_ligne.gif'>";
					} else {
						//Copie echoué
						$copy = "<img src='".XOOPS_URL."/modules/".$xoopsModule->dirname()."/images/icon/expiree.gif'>";
					}
					if ( $resize == 1 ){
						//Creation reussie
						$resize = "<img src='".XOOPS_URL."/modules/".$xoopsModule->dirname()."/images/icon/en_ligne.gif'>";
					} elseif ( $resize == 2 ) {
						//Thumb deja existant dans la db
						$resize = "<img src='".XOOPS_URL."/modules/".$xoopsModule->dirname()."/images/icon/expiree_bientot.gif'>";
					} else {
						//Thumb echoué
						$resize = "<img src='".XOOPS_URL."/modules/".$xoopsModule->dirname()."/images/icon/expiree.gif'>";
					}
					
					echo "<tr>
							<td align='center' class = 'odd'>".$fileCopy."</td>
							<td align='center' class = 'odd'>".$resize."</td>
							<td align='center' class = 'odd'>".$copy."</td>
						  </tr>";
					unlink("".$fileCopy."");
					
				}
			}
			ob_end_clean();	
			@closedir($racine);
		echo "</table>";
		
		echo "<br><br>
		<fieldset><legend style='font-weight: bold; color: #900;'>" . _AM_CATADS_INFOS_STATUTS_IMAGES_TITLE . "</legend>
			<div style='padding: 8px;'>" . _AM_CATADS_INFOS_STATUTS_IMAGES . "</div>
		</fieldset>";
		

		//$dir = XOOPS_ROOT_PATH . "/modules/catads/images/ads";
		//chmod ($dir, 0777);
		//rmdir($dir);
}


switch ($op) 
{
		case "import_images":
			import_images();
		break;
		
		case "show_index":
		default:
			
			show_index();
			
		break;
}		
	
	
xoops_cp_footer();
?>
