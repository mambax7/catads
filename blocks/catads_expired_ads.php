<?php
// $Id: blocks/catads_news.php,v 1.0 2008/11/22 Kraven30
// ------------------------------------------------------------------------- //
// Catads for Xoops                                                          //
// Version:  1.5                                                             //
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
//include("./mainfile.php");
if (!defined('XOOPS_ROOT_PATH')) {
	die('XOOPS root path not defined');
}

function b_catads_expired_ads()
{
	global $xoopsDB, $xoopsModuleConfig, $xoopsConfig, $xoopsUser;

	//Pour acceder aux preferences du module
	$hModule =& xoops_gethandler('module');
	$hModConfig =& xoops_gethandler('config');
	$catadsModule =& $hModule->getByDirname('catads');
	$module_id = $catadsModule -> getVar( 'mid' );
	$module_name = $catadsModule -> getVar( 'dirname' );
	$catadsConfig =& $hModConfig->getConfigsByCat(0, $catadsModule->getVar('mid'));
	
	//Nb jours en secondes
	$nbDays = $catadsConfig['nb_days_expired'];
	$daysSecondes = $nbDays * 86400;

	//Selection des annonces qui ne sont pas encore fini
	$sql = $xoopsDB->query("SELECT ads_id, expired FROM ".$xoopsDB->prefix("catads_ads")." WHERE waiting = '0' AND expired_mail_send = '1' OR expired_mail_send = '2'");
	while ($resultat = $xoopsDB->fetchArray($sql)) 
	{
		//Date d'expiration - Nb jours en seconde
		$dateLessNbDays = $resultat['expired'] - $daysSecondes;
		
		//Recherche des annonces qui vont bientot expirees
		if ( time() >= $dateLessNbDays && time() <= $resultat['expired'] )
		{
			
			//Selection des infos de l'annonce
			$sql1 = $xoopsDB->query("SELECT ads_id, ads_title, uid, email, expired_mail_send, created FROM ".$xoopsDB->prefix("catads_ads")." WHERE ads_id=".$resultat['ads_id']);
			list ($ads_id, $ads_title, $uid, $email, $expired_mail_send, $created) = $xoopsDB -> fetchRow($sql1);
			//$resultat1 = $xoopsDB->fetchArray($sql1));
			
			
				//Cherche le nom de l'utilisateur
				$sql2 = $xoopsDB->query("SELECT uname FROM ".$xoopsDB->prefix("users")." WHERE uid = ".$uid);
				list($uname) = $xoopsDB->fetchRow($sql2);
				
				if ($catadsConfig['auto_mp'] == 1) 
				{		
							
					//Envoie par MP ou par Email	
					if ( $expired_mail_send == 1)
					{
						//echo "Envoie par mp ".$ads_id."<br />";
						//Envoie par MP
						$mp_url_ads = "[b][url=".XOOPS_URL."/modules/catads/adsitem.php?ads_id=".$ads_id."]\"".$ads_title."\"[/url][/b]";
						$mp_url_ads_renew = "[b][url=".XOOPS_URL."/modules/catads/renew.php?ads_id=".$ads_id."&uid=".$uid."&key=".$created."&expired=".$expired_mail_send."]\""._MB_CATADS_RENEW."\"[/url][/b]";
						
						$mp_title_ads = $resultat1['ads_title'];
		
						$mp_msg_text = str_replace("{X_UNAME}", $uname,  _MB_CATADS_MP_TEXT);
						$mp_msg_text2 .= str_replace("{X_ADS_TITLE}", $mp_title_ads, $mp_msg_text);
						$mp_msg_text3 .= str_replace("{X_ADS}", $mp_url_ads, $mp_msg_text2);
						$mp_msg_text4 .= str_replace("{X_DAY}", $catadsConfig['nb_days_expired'], $mp_msg_text3);
						$mp_msg_text5 .= str_replace("{X_ADS_RENEW}", $mp_url_ads_renew, $mp_msg_text4);
						$mp_msg_text6 .= str_replace("{X_SITEURL}", XOOPS_URL, $mp_msg_text5);
						$mp_msg_text7 .= str_replace("{X_ADMINMAIL}", $xoopsConfig['adminmail'], $mp_msg_text6);
						$mp_msg_text8 .= str_replace("{X_SITENAME}", $xoopsConfig['sitename'], $mp_msg_text7);
						
						$mp_msg = $mp_msg_text8;
						$mp_suject = _MB_CATADS_MP_TITLE;
						$mp_msg_time = time(); 
						
						//Insertion d'un message lors de l'expiration
						 $sql = "INSERT INTO ".$xoopsDB->prefix("priv_msgs")."(msg_id, msg_image, subject, from_userid, to_userid, msg_time, msg_text, read_msg) VALUES('','','".$mp_suject."','1','".$uid."','".$mp_msg_time."','".$mp_msg."','')";
						 $result = $xoopsDB->queryF($sql);
						 
						  //L'annonce est modifiee pour eviter que l'annonce soit a nouveau envoyer en MP
						$sql = "UPDATE ". $xoopsDB->prefix('catads_ads')." SET expired_mail_send = '0' WHERE ads_id = ".$ads_id;
						$result = $xoopsDB->queryF($sql);
					}
					elseif( $expired_mail_send == 2)
					{		
						//echo "Envoie par email : ".$ads_id."<br />";
						//Envoie par email
						////////////////////////////////////////////
						$mail_url_ads = "<a href='".XOOPS_URL."/modules/catads/adsitem.php?ads_id=".$ads_id."'>".$ads_title."</a>";
						$mail_url_ads_renew = "<a href='".XOOPS_URL."/modules/catads/renew.php?ads_id=".$ads_id."&uid=".$uid."&key=".$created."&expired=".$expired_mail_send."'>"._MB_CATADS_RENEW."</a>";
						

		
						$mail_msg_text = str_replace("{X_UNAME}", $uname,  _MB_CATADS_MAIL_TEXT);
						$mail_msg_text2 .= str_replace("{X_ADS_TITLE}", $mail_title_ads, $mail_msg_text);
						$mail_msg_text3 .= str_replace("{X_ADS}", $mail_url_ads, $mail_msg_text2);
						$mail_msg_text4 .= str_replace("{X_DAY}", $catadsConfig['nb_days_expired'], $mail_msg_text3);
						$mail_msg_text5 .= str_replace("{X_ADS_RENEW}", $mail_url_ads_renew, $mail_msg_text4);
						$mail_msg_text6 .= str_replace("{X_SITEURL}", XOOPS_URL, $mail_msg_text5);
						$mail_msg_text7 .= str_replace("{X_ADMINMAIL}", $xoopsConfig['adminmail'], $mail_msg_text6);
						$mail_msg_text8 .= str_replace("{X_SITENAME}", $xoopsConfig['sitename'], $mail_msg_text7);
						
						$mail_msg = $mail_msg_text8;
						
						include_once XOOPS_ROOT_PATH."/class/xoopsmailer.php";

						$xoopsMailer =& getMailer();
						$xoopsMailer->useMail();
						$xoopsMailer->setFromEmail($xoopsConfig['adminmail']);
						$xoopsMailer->setFromName($xoopsConfig['sitename']);
						$xoopsMailer->setToEmails($email);
						$xoopsMailer->setSubject(_MB_CATADS_MAIL_TITLE);
						$xoopsMailer->setBody($mail_msg);
						$xoopsMailer->usePM();
						$xoopsMailer->multimailer->isHTML(true);
						$xoopsMailer->send();
						
						/*if ( !$xoopsMailer->send()) 
						{
							$msgsend .= $xoopsMailer->getErrors();
						} 
						else 
						{
							$msgsend .= "<h4>"._MD_CATADS_MSGSEND."</h4>";
						}*/
						/*$msgsend .= "<br /><br /><a href=\"javascript:window.close();\">"._MD_CATADS_CLOSEF."</a></div>";
						echo $msgsend;*/
						 
						 //L'annonce est modifiee pour eviter que l'annonce soit a nouveau envoyer en MP
						$sql = "UPDATE ". $xoopsDB->prefix('catads_ads')." SET expired_mail_send = '0' WHERE ads_id = ".$ads_id;
						$result = $xoopsDB->queryF($sql);
					}
	
					
				}

			
		}
	}	
	return '';
}
		
?>