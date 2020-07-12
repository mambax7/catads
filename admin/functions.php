<?php
/*
 * ****************************************************************************
 * Catads - MODULE FOR XOOPS
 * 2008/11/15 by Kraven30
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  You may not change or alter any portion of this comment or credits
 *  of supporting developers from this source code or any supporting
 *  source code which is considered copyrighted (c) material of the
 *  original comment or credit authors.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA
 *
 * ****************************************************************************
 */

//////////////////////////////////////////////////////
//////////////////PAGE INDEX//////////////////////////
//////////////////////////////////////////////////////

/*
 * AUTEUR                : Venom
 * ENTREES                : $size
 * SORTIES                : $mysize
 * DESCRIPTION        : Compte la taille du fichier
 * COMMENTAIRES        : Code recuperer sur MPManager de Venom
 * GLOBALES                :
 */
        function Size($size)
        {
                $mb = 1024 * 1024;
                if ($size > $mb)
                {
                        $mysize = sprintf ("%01.2f", $size / $mb) . " Mb";
                }elseif ($size >= 1024)
                {
                        $mysize = sprintf ("%01.2f", $size / 1024) . " Kb";
                }
                else
                {
                        $mysize = sprintf(_AM_CATADS_NUMBYTES, $size);
                }
                return $mysize;
        }

//////////////////////////////////////////////////////
//////////////////PAGE D'ANNONCES/////////////////////
//////////////////////////////////////////////////////

/*
 * AUTEUR                : Kraven30
 * ENTREES                :
 * SORTIES                :
 * DESCRIPTION        : Afficher les annonces
 * COMMENTAIRES        :
 * GLOBALES                : $xoopsModule, $xoopsDB, $xoopsModuleConfig
 */
        function show_ads()
        {
                global $xoopsModule, $xoopsDB, $xoopsModuleConfig;

                $pick = isset($_GET['pick']) ? intval($_GET['pick']) : 0;
                $start = isset($_GET['start']) ? intval($_GET['start']) : 0;
                $sel_status = isset($_GET['sel_status']) ? $_GET['sel_status'] : 0;
                // pk set default sort order to show latest ads at top of list
                $sel_order = isset($_GET['sel_order']) ? $_GET['sel_order'] : 'DESC';
                $limit = $xoopsModuleConfig['nb_perpage_admin'];
                $status_option0 = '';
                $status_option1 = '';
                $status_option2 = '';
                $status_option3 = '';
                $order_option_asc = '';
                $order_option_desc = '';
                $jourSecondes = $xoopsModuleConfig['nb_days_expired'] * 86400;
                $ads_handler =& xoops_getmodulehandler('ads');

                switch ($sel_status)
                {
                        case 0 :
                                $status_option0 = "selected='selected'";
                                $title = _AM_CATADS_ALL;
                                $criteria = new Criteria('ads_id', '0', '>');
                                $criteria->setSort('published');
                        break;

                        case 1 :
                                $status_option2 = "selected='selected'";
                                $title = _AM_CATADS_WAIT;
                                $criteria = new Criteria('waiting', '1');
                                $criteria->setSort('created');
                        break;

                        case 2 :
                                $status_option1 = "selected='selected'";
                                $title = _AM_CATADS_PUB;
                                $criteria = new CriteriaCompo(new Criteria('waiting', '0'));
                                $criteria->add(new Criteria('published', time(),'<'));
                                $criteria->add(new Criteria('expired', time(),'>'));
                                $criteria->setSort('published');
                        break;


                        case 3 :
                                $status_option3 = "selected='selected'";
                                $title = _AM_CATADS_EXP;
                                $criteria = new CriteriaCompo(new Criteria('expired', time(), '<'));
                                $criteria->add(new Criteria('waiting', '0'));
                                $criteria->setSort('expired');
                        break;
                }

                switch ($sel_order)
                {
                        case 'ASC':
                        $order_option_asc = "selected='selected'";
                        $criteria->setOrder('ASC');
                        break;

                        case 'DESC':
                        $order_option_desc = "selected='selected'";
                        $criteria->setOrder('DESC');
                        break;
                }

                $totalcount = $ads_handler->getCount($criteria);
                $criteria->setLimit($limit);
                $criteria->setStart($start);
                $msg =& $ads_handler->getObjects($criteria);


                /*sf_collapsableBar('toptable', 'toptableicon');
                echo "<img onclick='toggle('toptable'); toggleIcon('toptableicon');' id='toptableicon' name='toptableicon' src=" . XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/images/icon/close12.gif alt='' /></a>&nbsp;" . _AM_CATADS_ADS . "</h3>";
                echo "<div id='toptable'>";
                echo "<br>";*/

                        $ads = $ads_handler->getAllAds($criteria);
                        //$mytree = new XoopsTree($xoopsDB->prefix("catads_cat"),"topic_id","topic_pid");
                        $arr_cat = AdsCategory::getAllLastChild();

                        echo "<form name='pick' id='pick' action='" . $_SERVER['PHP_SELF'] . "' method='GET' style='margin: 0;'>";

                echo "
                        <table width='100%' cellspacing='1' cellpadding='2' border='0' style='border-left: 1px solid silver; border-top: 1px solid silver; border-right: 1px solid silver;'>
                                <tr>
                                        <td><span style='font-weight: bold; font-size: 12px; font-variant: small-caps;'>" .$title." : ".$totalcount."</span></td>
                                        <td align='right'>
                                        " . _AM_CATADS_DISPLAY . " :
                                                <select name='sel_status' onchange='submit()'>
                                                        <option value='0' $status_option0>" . _AM_CATADS_ALL . " </option>
                                                        <option value='1' $status_option2>" . _AM_CATADS_WAIT . " </option>
                                                        <option value='2' $status_option1>" . _AM_CATADS_PUB . " </option>
                                                        <option value='3' $status_option3>" . _AM_CATADS_EXP . " </option>
                                                </select>
                                        " . _AM_CATADS_SELECT_SORT . "
                                                <select name='sel_order' onchange='submit()'>
                                                <option value='ASC' $order_option_asc>" . _AM_CATADS_SORT_ASC . "</option>
                                                <option value='DESC' $order_option_desc>" . _AM_CATADS_SORT_DESC . "</option>
                                                </select>
                                        </td>
                                </tr>
                        </table>
                        </form>";


                                echo "<table  width='100%' cellspacing='1' class='outer'>";
                                echo "<tr class='bg3'>";
                                echo "<td align='center'><input name='allbox' id='allbox' onclick='xoopsCheckAll(\"adslist\", \"allbox\");' type='checkbox' value='Check All' /></td>";
                                echo "<td align='center'><b>"._AM_CATADS_IMAGE."</td>";
                                echo "<td align='center'><b>"._AM_CATADS_STATUS."</td>";
                                echo "<td align='center'><b>"._AM_CATADS_TITLE_ADS."</td>";
                                echo "<td align='center'><b>"._AM_CATADS_AUTHOR."</td>";
                                echo "<td align='center'><b>"._AM_CATADS_PRICE."</td>";
                                echo "<td align='center'><b>"._AM_CATADS_DATE."</td>";
                                echo "<td align='center'><b>"._AM_CATADS_IP."</td>";
                                echo "<td align='center'><b>"._AM_CATADS_ACTION."</td>";
                                echo "</tr>";


                        if ($totalcount != '0')
                        {
                                echo "<form name='adslist' id='adslist' action='" . $_SERVER['PHP_SELF'] . "' method='POST' style='margin: 0;'>";

                                foreach( $msg as $onemsg )
                                {
                                        $dateLessNbDays = $onemsg->getVar('expired') - $jourSecondes;
                                        if ($onemsg->getVar('waiting') > 0)
                                        {
                                        $approve = "<a href='adsmod.php?op=approve&ads_id=".$onemsg->getVar('ads_id')."'>"._AM_CATADS_APPROVE."</a><br />";

                                        if ($onemsg->getVar('published') > time())
                                        $img_status = "<img src='".XOOPS_URL."/modules/".$xoopsModule->dirname()."/images/icon/ic16_clockblue.gif' alt='En attente' / >";
                                        else
                                        $img_status = "<img src='".XOOPS_URL."/modules/".$xoopsModule->dirname()."/images/icon/en_attente.gif' alt='"._AM_CATADS_WAIT."' title='"._AM_CATADS_WAIT."' />";

                                        } elseif ( $dateLessNbDays <= time() && time() <= $onemsg->getVar('expired'))
                                        {
                                        if ( $onemsg->getVar('expired_mail_send') == 0 ){
                                        $img_status = "<img src='".XOOPS_URL."/modules/".$xoopsModule->dirname()."/images/icon/letter.png' alt='"._AM_CATADS_SEND_MAIL."' title='"._AM_CATADS_SEND_MAIL."' />";
                                        } else {
                                        $img_status = "<img src='".XOOPS_URL."/modules/".$xoopsModule->dirname()."/images/icon/expiree_bientot.gif' alt='"._AM_CATADS_EXP_SOON."' title='"._AM_CATADS_EXP_SOON."' />";
                                        }
                                        } elseif ($onemsg->getVar('expired') < time())
                                        {
                                        $img_status = "<img src='".XOOPS_URL."/modules/".$xoopsModule->dirname()."/images/icon/expiree.gif' alt='"._AM_CATADS_EXP."' title='"._AM_CATADS_EXP."' />";
                                        } elseif ($onemsg->getVar('published') > time())
                                        {
                                        $img_status = "<img src='".XOOPS_URL."/modules/".$xoopsModule->dirname()."/images/icon/ic16_clockgreen.gif' alt='Expirée' />";
                                        } else
                                        {
                                        $img_status = "<img src='".XOOPS_URL."/modules/".$xoopsModule->dirname()."/images/icon/en_ligne.gif' alt='"._AM_CATADS_PUB."' title='"._AM_CATADS_PUB."' />";
                                        }

                                        // pk bugfix - add correct icon for suspended ads
                                        if ($onemsg->getVar('suspend') == '1'){
                                        $img_status = "<img src='".XOOPS_URL."/modules/".$xoopsModule->getVar('dirname')."/images/icon/stop.gif' alt='Suspended' title='Suspended' />";
                                        }


                                        if(!in_array($onemsg->getVar('cat_id'),$arr_cat))
                                        $img_status ="<img src='".XOOPS_URL."/modules/".$xoopsModule->dirname()."/images/icon/attention.gif' alt='"._AM_CATADS_WARNING."' title='"._AM_CATADS_WARNING."' />";

                                        $sentby = XoopsUser::getUnameFromId($onemsg->getVar('uid'));

                                        //$cat_path = $mytree->getpathFromId( $onemsg->getVar('topic_id'), 'topic_title');
                                        //$cat_path = substr($cat_path, 1);
                                        //$cat_path = str_replace("/","<br />",$cat_path);

                                        if ( $onemsg->getVar('thumb') != '' ){
                                        $image = "<img src=\"".XOOPS_URL."/uploads/catads/images/annonces/thumb/".$onemsg->getVar('thumb')."\" alt=\"\" />";
                                        } else {
                                        $image = "<img src=\"".XOOPS_URL."/modules/catads/images/no_dispo_mini.gif\" alt=\"\" />";
                                        }

                                        echo "<tr class='odd'>";
                                        echo "<td width='5%' align='center' class='odd';><input type='checkbox' name='ads_id[]' id='ads_id[]' value='".$onemsg->getVar('ads_id')."'/></td>";
                                        echo "<td width='5%' align='center' style='border:1px solid #666666;'><b>".$image."</b></td>";
                                        echo "<td width='5%' align='center' class='odd'>".$img_status."</td>";
                                        echo "<td align='center' class = 'odd'><a href='../adsitem.php?ads_id=".$onemsg->getVar('ads_id')."'>".$onemsg->getVar('ads_title')."</a></td>";
                                        echo "<td align='center' class = 'odd'>".$sentby."</td>";

                                        // echo "<td align='center' class = 'odd'>".$onemsg->getVar('price')." euro</td>";
                                        echo "<td align='center' class = 'odd'>".$onemsg->getVar('price')."&nbsp;".$onemsg->getVar('monnaie')."</td>"; 

                                        echo "<td align='center' class = 'odd'>".formatTimestamp($onemsg->getVar('published'))."</td>";
                                        echo "<td align='center' class = 'odd'>".$onemsg->getVar('poster_ip')."</td>";
                                        echo "<td align='center' class = 'odd'><a href='adsmod.php?op=edit&ads_id=".$onemsg->getVar('ads_id')."'><img src='".XOOPS_URL."/modules/".$xoopsModule->dirname()."/images/icon/edit.gif' alt="._AM_CATADS_EDIT." title="._AM_CATADS_EDIT." /></a></td>";
                                        echo "</tr>";
                                }

                                switch ($sel_status)
                                        {
                                                case 1 :
                                                echo "<tr class='foot'><td><select name='op'>";
                                                echo "<option value='approve_ads'>"._AM_CATADS_APPROUVE."</option>";
                                                echo "<option value='delete_ads'>"._AM_CATADS_DELETE."</option>";
                                                echo "</select>&nbsp;</td>";
                                                echo "<td colspan='8'>".$GLOBALS['xoopsSecurity']->getTokenHTML()."<input type='submit' value='"._GO."' />";
                                                echo "</td></tr>";
                                                echo "</form>";
                                                break;

                                                case 2 :
                                                echo "<tr class='foot'><td><select name='op'>";
                                                echo "<option value='wait_ads'>"._AM_CATADS_WAIT1."</option>";
                                                echo "<option value='delete_ads'>"._AM_CATADS_DELETE."</option>";
                                                echo "</select>&nbsp;</td>";
                                                echo "<td colspan='8'>".$GLOBALS['xoopsSecurity']->getTokenHTML()."<input type='submit' value='"._GO."' />";
                                                echo "</td></tr>";
                                                echo "</form>";
                                                break;

                                                case 3 :
                                                echo "<tr class='foot'><td><select name='op'>";
                                                echo "<option value='wait_ads'>"._AM_CATADS_WAIT1."</option>";
                                                echo "<option value='renew_ads'>"._AM_CATADS_RENEW."</option>";
                                                echo "<option value='delete_ads'>"._AM_CATADS_DELETE."</option>";
                                                echo "</select>&nbsp;</td>";
                                                echo "<td colspan='8'>".$GLOBALS['xoopsSecurity']->getTokenHTML()."<input type='submit' value='"._GO."' />";
                                                echo "</td></tr>";
                                                echo "</form>";
                                                break;

                                                default:
                                                echo "<tr class='foot'><td><select name='op'>";
                                                echo "<option value='approve_ads'>"._AM_CATADS_APPROUVE."</option>";
                                                echo "<option value='wait_ads'>"._AM_CATADS_WAIT1."</option>";
                                                echo "<option value='renew_ads'>"._AM_CATADS_RENEW."</option>";
                                                echo "<option value='delete_ads'>"._AM_CATADS_DELETE."</option>";
                                                echo "</select>&nbsp;</td>";
                                                echo "<td colspan='8'>".$GLOBALS['xoopsSecurity']->getTokenHTML()."<input type='submit' value='"._GO."' />";
                                                echo "</td></tr>";
                                                echo "</form>";
                                                break;
                                        }
                        } else {
                                echo "<tr ><td align='center' colspan ='10' class = 'head'><b>"._AM_CATADS_NOMSG."</b></td></tr>";
                        }
                                echo "</table>";

                        if ( $totalcount > $limit )
                        {
                                include_once XOOPS_ROOT_PATH.'/class/pagenav.php';
                                $pagenav = new XoopsPageNav($totalcount, $limit, $start, 'start', 'sel_status='.$sel_status.'&sel_order='.$sel_order);
                                echo "<div style='text-align: center;' class = 'head'>".$pagenav->renderNav()."</div><br />";
                        } else
                        {
                                echo '';
                        }
                                //echo "<div align='right'><img src='../images/icon/en_attente.gif' alt='"._AM_CATADS_WAIT."' title='"._AM_CATADS_WAIT."'>&nbsp;"._AM_CATADS_WAIT."&nbsp;&nbsp;<img src='../images/icon/en_ligne.gif' alt='"._AM_CATADS_PUB."'title='"._AM_CATADS_PUB."'>&nbsp;&nbsp;"._AM_CATADS_PUB."&nbsp;&nbsp;<img src='../images/icon/expiree_bientot.gif' alt='"._AM_CATADS_EXP_SOON."' title='"._AM_CATADS_EXP_SOON."'>&nbsp;"._AM_CATADS_EXP_SOON."&nbsp;&nbsp;<img src='../images/icon/expiree.gif' alt='"._AM_CATADS_EXP."' title='"._AM_CATADS_EXP."'>&nbsp;"._AM_CATADS_EXP."&nbsp;&nbsp;<img src='../images/icon/attention.gif' alt='"._AM_CATADS_EXP."' title='"._AM_CATADS_WARNING."'>&nbsp;"._AM_CATADS_WARNING."</div><br /><br />";
        //echo "</div>";

                        echo "<br><br>
                <fieldset><legend style='font-weight: bold; color: #900;'>" . _AM_CATADS_INFOS_STATUTS_ADS_TITLE . "</legend>
                        <div style='padding: 8px;'>" . _AM_CATADS_INFOS_STATUTS_ADS . "</div>
                </fieldset>";
        }


/*
 * AUTEUR                : Kraven30
 * ENTREES                :
 * SORTIES                : $messagesent
 * DESCRIPTION        : Approuver les annonces
 * COMMENTAIRES        :
 * GLOBALES                : $xoopsModule
 */
        function approve_ads()
        {
                global $xoopsModule;

                $ads_handler =& xoops_getmodulehandler('ads');
                $ads_count = (!empty($_POST['ads_id']) && is_array($_POST['ads_id'])) ? count($_POST['ads_id']) : 0;
                $ads_id = isset($_POST['ads_id']) ? intval($_POST['ads_id']) : 0;

                if ($ads_count > 0)
                {
                        //echo $ads_count;
                        $messagesent = _AM_CATADS_VALIDATE;
                        for ( $i = 0; $i < $ads_count; $i++ )
                        {
                                $ads = & $ads_handler->get($_POST['ads_id'][$i]);

                                //$ads = $ads_handler->get($_POST['ads_id'][$i]);
                                $ads_id = $ads->getVar('ads_id');
                                $topic_id = $ads->getVar('cat_id');
                                include_once XOOPS_ROOT_PATH."/class/xoopsblock.php";
                                include_once XOOPS_ROOT_PATH.'/class/template.php';
                                xoops_template_clear_module_cache($xoopsModule->getVar('mid'));
                                // Notification
                                $notification_handler =& xoops_gethandler('notification');
                                $tags = array();
                                $tags['ADS_TITLE'] = $ads->getVar('ads_type').' '.$ads->getVar('ads_title');
                                $tags['ADS_URL'] = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/adsitem.php?ads_id=' . $ads_id;

                                if( $ads->getVar('notify_pub') == 1 )
                                {
                                        $notification_handler->triggerEvent('global', 0, 'new_ads', $tags);
                                        $notification_handler->triggerEvent('category', $topic_id, 'new_ads', $tags);
                                        $notification_handler->triggerEvent('ads', $ads_id, 'approve', $tags);
                                }

                                $ads->setVar('waiting', 0);
                                $ads->setVar('notify_pub', 0);
                                if (!$ads_handler->insert($ads))
                                {
                                        $messagesent = _AM_CATADS_ERRORVALID;
                                }
                        }
                }
                else {
                        $messagesent = _AM_CATADS_NOMSG;
                }
                redirect_header($_SERVER['PHP_SELF'],2,$messagesent);
                exit();
        }


/*
 * AUTEUR                : Kraven30
 * ENTREES                :
 * SORTIES                : $messagesent
 * DESCRIPTION        : Mettre les annonces en attente les annonces
 * COMMENTAIRES        :
 * GLOBALES                : Aucunes
 */
        function wait_ads()
        {
                $ads_handler =& xoops_getmodulehandler('ads');
                $ads_count = (!empty($_POST['ads_id']) && is_array($_POST['ads_id'])) ? count($_POST['ads_id']) : 0;
                if ($ads_count > 0) {
                        $messagesent = _AM_CATADS_VALIDATE;
                        for ( $i = 0; $i < $ads_count; $i++ )
                        {
                                $ads = & $ads_handler->get($_POST['ads_id'][$i]);
                                $ads->setVar('waiting', 1);
                                if (!$ads_handler->insert($ads)) {
                                        $messagesent = _AM_CATADS_ERRORVALID;
                                }
                        }
                } else {
                        $messagesent = _AM_CATADS_NOMSG;
                }
                redirect_header($_SERVER['PHP_SELF'],2,$messagesent);
                exit();
        }


/*
 * AUTEUR                : Kraven30
 * ENTREES                :
 * SORTIES                : $messagesent
 * DESCRIPTION        : Renouveler les annonces
 * COMMENTAIRES        :
 * GLOBALES                : $xoopsModuleConfig
 */
        function renew_ads()
        {
                global $xoopsModuleConfig;

                $ads_handler =& xoops_getmodulehandler('ads');
                $ads_count = (!empty($_POST['ads_id']) && is_array($_POST['ads_id'])) ? count($_POST['ads_id']) : 0;
                $expired = time() + ($xoopsModuleConfig['renew_nb_days'] * 86400);

                if ($ads_count > 0) {
                        $messagesent = _AM_CATADS_VALIDATE;
                        for ( $i = 0; $i < $ads_count; $i++ )
                        {
                                $ads = & $ads_handler->get($_POST['ads_id'][$i]);
                                $ads->setVar('published', time());
                                $ads->setVar('expired', $expired);
                                if (!$ads_handler->insert($ads)) {
                                        $messagesent = _AM_CATADS_ERRORVALID;
                                }
                        }
                } else {
                        $messagesent = _AM_CATADS_NOMSG;
                }
                redirect_header($_SERVER['PHP_SELF'],2,$messagesent);
                exit();
        }


/*
 * AUTEUR                : Kraven30
 * ENTREES                :
 * SORTIES                : $messagesent
 * DESCRIPTION        : Supprimer les annonces
 * COMMENTAIRES        :
 * GLOBALES                : Aucunes
 */
        function delete_ads()
        {
                $ads_handler =& xoops_getmodulehandler('ads');
                $ads_count = (!empty($_POST['ads_id']) && is_array($_POST['ads_id'])) ? count($_POST['ads_id']) : 0;
                if ($ads_count > 0) {
                        $messagesent = _AM_CATADS_MSGDELETED;
                        for ( $i = 0; $i < $ads_count; $i++ )
                        {
                                $ads = & $ads_handler->get($_POST['ads_id'][$i]);
                                $filename = $ads->getVar('title');
                                $filename = $ads->getVar('photo');
                                if (!$ads_handler->delete($ads))
                                {
                                        $messagesent = _AM_CATADS_ERRORDEL;
                                }
                                /*if($filename != '') {
                                        $filename = XOOPS_ROOT_PATH.'/modules/catads/images/ads/'.$filename;
                                        unlink($filename);
                                }*/
                        }
                }
                else {
                        $messagesent = _AM_CATADS_NOMSG;
                }
                redirect_header($_SERVER['PHP_SELF'],2,$messagesent);
                exit();
        }


//////////////////////////////////////////////////////
////////////////PAGE EDITER ANNNONCES/////////////////
//////////////////////////////////////////////////////

/*
 * AUTEUR                : The Cat
 * ENTREES                : $ads_id
 * SORTIES                : $messagesent
 * DESCRIPTION        : Supprimer annonce
 * COMMENTAIRES        :
 * GLOBALES                : $xoopsModule
 */
        function delete($ads_id) {
                global $xoopsModule;

                $ads_id =  isset($_POST['ads_id']) ? intval($_POST['ads_id']) : intval($_GET['ads_id']);
                $ok =  isset($_POST['ok']) ? intval($_POST['ok']) : 0;

                if ( $ok == 1 ) {
                        $ads_handler =& xoops_getmodulehandler('ads');
                        $ads = & $ads_handler->get($ads_id);
                        // cache
                        include_once XOOPS_ROOT_PATH."/class/xoopsblock.php";
                        include_once XOOPS_ROOT_PATH.'/class/template.php';
                        xoops_template_clear_module_cache($xoopsModule->getVar('mid'));
                        $i = 0;
                        while ($i < 6){
                                if ($ads->getVar('photo'.$i)) {
                                        $filename = XOOPS_ROOT_PATH.'/uploads/catads/images/annonces/original/'.$ads->getVar('photo'.$i);
                                        unlink($filename);
                                }
                                $i++;
                        }
                        $del_ads_ok = $ads_handler->delete($ads);
                        if ($del_ads_ok){
                                // delete comments
                                xoops_comment_delete($xoopsModule->getVar('mid'), $ads_id);
                                // delete notifications
                                xoops_notification_deletebyitem($xoopsModule->getVar('mid'), 'ads', $ads_id);
                                $messagesent = _AM_CATADS_ADSDELETED;
                        } else {
                                $messagesent = _AM_CATADS_ERRORDEL;
                        }
                        redirect_header("index.php?op=show", 2, $messagesent);

                } else {
                        xoops_cp_header();
                        xoops_confirm(array('op' => 'delete', 'ads_id' => $ads_id, 'ok' => 1), 'adsmod.php', _AM_CATADS_CONF_DEL);
                        xoops_cp_footer();
                }
        }



/*
 * AUTEUR                : The Cat
 * ENTREES                : $ads_id
 * SORTIES                : $messagesent
 * DESCRIPTION        : Approuver l'annonce apres l'avoir modifiée
 * COMMENTAIRES        :
 * GLOBALES                : $xoopsModuleConfig, $xoopsModule
 */
        function approve($ads_id) {
                global $xoopsModuleConfig, $xoopsModule;

                $ads_handler =& xoops_getmodulehandler('ads');
                $ads = & $ads_handler->get($ads_id);

                if ($ads->getVar('published') < time()){
                        $duration = $ads->getVar('expired') - $ads->getVar('published');
                        $ads->setVar('published', time());
                        $expired = time() + $duration*86400;
                } else {
                        $ads->setVar('published', $published);
                        $ads->setVar('expired', $expired);
                }
                $ads->setVar('waiting', 0);
                $cat_id = $ads->getVar('cat_id');
                $approve_ads_ok = $ads_handler->insert($ads);
                if ($approve_ads_ok){
                        $messagesent = _AM_CATADS_ADSAPPROVED;
                        // cache
                        include_once XOOPS_ROOT_PATH."/class/xoopsblock.php";
                        include_once XOOPS_ROOT_PATH.'/class/template.php';
                        xoops_template_clear_module_cache($xoopsModule->getVar('mid'));
                        // Notification
                        $notification_handler =& xoops_gethandler('notification');
                        $tags = array();
                        $tags['ADS_TITLE'] = $ads->getVar('ads_type').' '.$ads->getVar('ads_title');
                        $tags['ADS_URL'] = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/adsitem.php?ads_id=' . $ads_id;

                        $notification_handler->triggerEvent('global', 0, 'new_ads', $tags);
                        $notification_handler->triggerEvent('category', $cat_id, 'new_ads', $tags);
                        $notification_handler->triggerEvent('ads', $ads_id, 'approve', $tags);
                } else {
                        $messagesent = _AM_CATADS_ERROR_UPDATE;
                }
                redirect_header("index.php?op=show&sel_status=0", 1, $messagesent);
                exit();
        }


//////////////////////////////////////////////////////
/////////////////PAGE DES CATEGORIES//////////////////
//////////////////////////////////////////////////////

/*
 * AUTEUR                : The Cat
 * ENTREES                : $cat_id, $add
 * SORTIES                :
 * DESCRIPTION        : Formulaire des categories
 * COMMENTAIRES        :
 * GLOBALES                : $xoopsDB, $xoopsModule
 */
        function categoryForm($topic_id=0, $add)
        {
                        global $xoopsDB, $xoopsModule;
                        include_once XOOPS_ROOT_PATH.'/class/xoopsformloader.php';

                        $cat = new AdsCategory($topic_id);
                        if ($topic_id) {
                                $sform = new XoopsThemeForm(_AM_CATADS_MODIFYCATEGORY, "op", xoops_getenv('PHP_SELF'));
                        } else {
                                $sform = new XoopsThemeForm(_AM_CATADS_ADDCATEGORY, "op", xoops_getenv('PHP_SELF'));
                        }

                        if ($add == 1) {
                                $cat->weight = '1';
                                // pk default image - blank.gif is not initially available in catads uploads
                                // $cat->img = 'blank.gif';
                                $cat->img = '----------';
                                $cat->display_cat = 1;
                                $cat->display_price = 1;
                                $cat->nb_photo = 6;
                        }
                        $sform->addElement(new XoopsFormText(_AM_CATADS_CATEGORYWEIGHT, 'weight', 10, 5, $cat->weight), false);
                        $sform->addElement(new XoopsFormText(_AM_CATADS_CATEGORYNAME, 'title', 30, 30, $cat->topic_title('E')), true);

                   $sform->addElement(new XoopsFormTextArea(_AM_CATADS_CATEDESC, 'desc', $cat->topic_desc('E')), false);

                        $xt = new XoopsTree($xoopsDB->prefix("catads_cat"),'topic_id','topic_pid');
                        ob_start();
                        if ($add == 1) {
                                $xt->makeMySelBox('topic_title','topic_title', 0, 1,'topic_pid');
                                $sform->addElement(new XoopsFormLabel(_AM_CATADS_IN, ob_get_contents()));
                        }else{
                                $xt->makeMySelBox('topic_title','topic_title',$cat->topic_pid, 1, 'topic_pid');
                                $sform->addElement(new XoopsFormLabel(_AM_CATADS_MOVETO, ob_get_contents()));
                        }
                        ob_end_clean();
                        echo "<br />";

                        $graph_array =& XoopsLists::getImgListAsArray(XOOPS_ROOT_PATH."/uploads/".$xoopsModule->dirname()."/images/categories");
                        $indeximage_select = new XoopsFormSelect('', 'indeximage', $cat->img);
                        // pk set no-image as default category image selection
                        $indeximage_select->addOption ('', '----------');
                        // end pk mod
                        $indeximage_select->addOptionArray($graph_array);
                        $indeximage_select->setExtra("onchange='showImgSelected(\"image\", \"indeximage\", \"uploads/".$xoopsModule->dirname()."/images/categories\", \"\", \"".XOOPS_URL."\")'");
                        $indeximage_tray = new XoopsFormElementTray(_AM_CATADS_CATEGORYIMG, '&nbsp;');
                        $indeximage_tray->addElement($indeximage_select);
                        $indeximage_tray->addElement(new XoopsFormLabel('', "<br /><br /><img src='".XOOPS_URL."/uploads/".$xoopsModule->dirname()."/images/categories/".$cat->img."' name='image' id='image' alt='' />" ));
                        $sform->addElement($indeximage_tray);

                        $display_cat = new XoopsFormRadioYN(_AM_CATADS_CAT_DISP, 'display_cat',$cat->display_cat);
                        $sform->addElement($display_cat);

                        $check_price = new XoopsFormRadioYN(_AM_CATADS_PRICE_DISP, 'display_price',$cat->display_price);
                        $sform->addElement($check_price);

                        $select_photo = new XoopsFormSelect(_AM_CATADS_NBMAX_PHOTO, 'nb_photo',$cat->nb_photo);
                        $select_photo->addOptionArray(array('0'=>0, '1'=>1, '2'=>2, '3'=>3, '4'=>4, '5'=>5, '6'=>6));
                        $sform->addElement($select_photo);

                        $button_tray = new XoopsFormElementTray('','');
                        if ($topic_id) {
                                $button_tray->addElement(new XoopsFormHidden('topic_id', $topic_id));
                                $button_tray->addElement(new XoopsFormButton('', 'save', _SEND, 'submit'));
                                $button_tray->addElement(new XoopsFormButton('', 'delete', _DELETE, 'submit'));
                        } else {
                                $button_tray->addElement(new XoopsFormButton('', 'save', _SEND, 'submit'));
                        }
                        $button_tray->addElement(new XoopsFormHidden('add', $add));
                        $sform->addElement($button_tray);
                        $sform->display();

        }

//////////////////////////////////////////////////////
///////////////////PAGE DE PURGE//////////////////////
//////////////////////////////////////////////////////
/*
 * AUTEUR                : Kraven30
 * ENTREES                :
 * SORTIES                :
 * DESCRIPTION        : Traitement Purge annonces de tout le monde
 * COMMENTAIRES        : Revoir cette fonction, manipuler avec une classe
 * GLOBALES                : $xoopsDB
 */
        function purge_ads_all_user()
        {
                global $xoopsDB;
                        $supp_ads_all_user = isset($_POST['supp_ads_all_user']) ? intval($_POST['supp_ads_all_user']) : intval($_GET['supp_ads_all_user']);
                        $ok =  isset($_POST['ok']) ? intval($_POST['ok']) : 0;

                        if ( $supp_ads_all_user == 0) redirect_header("purge.php", 2);;

                        if ( $ok == 1 )
                        {
                                if ( $supp_ads_all_user == 1 )
                                {
                                        $query = "DELETE FROM ".$xoopsDB->prefix("catads_ads")."";
                                        $result = $xoopsDB->queryF($query);
                                }
                                $messagesent = sprintf(_AM_CATADS_DELETE_ADS_ALL_USER);
                                redirect_header("purge.php", 2, $messagesent);
                        }
                        else
                        {
                                xoops_confirm(array('op' => 'purge_ads_all_user', 'supp_ads_all_user' => $supp_ads_all_user, 'ok' => 1), 'purge.php', _AM_CATADS_CONF_DEL_ALL);
                        }
        }


/*
 * AUTEUR                : The Cat
 * ENTREES                : Nombre de jours
 * SORTIES                :
 * DESCRIPTION        : Traitement Purge annonces expirées
 * COMMENTAIRES        :
 * GLOBALES                : Aucunes
 */
        function purge_ads_expired()
        {
                $nbdays =  isset($_POST['nbdays']) ? intval($_POST['nbdays']) : intval($_GET['nbdays']);
                $ok =  isset($_POST['ok']) ? intval($_POST['ok']) : 0;

                if ( $ok == 1 ) {
                        $date_del = time() - $nbdays*86400;
                        $criteria = new CriteriaCompo(new Criteria('published', '0', '>'));
                        $criteria->add(new Criteria('expired', $date_del,'<'));

                        $ads_handler =& xoops_getmodulehandler('ads');
                        $ads = $ads_handler->getObjects($criteria);
                        $nbok = $nbfailed =0;
                        foreach($ads as $oneads){
                                $i = 0;
                                while ($i < 6){
                                        if ($oneads->getVar('photo'.$i)) {
                                                $filename = XOOPS_ROOT_PATH.'/uploads/catads/images/annonces/original/'.$oneads->getVar('photo'.$i);
                                                unlink($filename);
                                        }
                                        $i++;
                                }
                                if($del_ads_ok = $ads_handler->delete($oneads))
                                        $nbok ++;
                                else
                                        $nbfailed ++;
                        }
                        $messagesent = sprintf(_AM_CATADS_ADSEXPDELETED,$nbok);
                        redirect_header("index.php?sel_status=0", 2, $messagesent);
                } else {
                        xoops_confirm(array('op' => 'purge_ads_expired', 'nbdays' => $nbdays, 'ok' => 1), 'purge.php',_AM_CATADS_CONF_DELEXP);
                }
        }


/*
 * AUTEUR                : Kraven30
 * ENTREES                :
 * SORTIES                :
 * DESCRIPTION        : Traitement Purge annonces user
 * COMMENTAIRES        : Revoir cette fonction, manipuler avec une classe
 * GLOBALES                : $xoopsDB
 */
        function purge_ads_user()
        {
                global $xoopsDB;
                        $user = isset($_POST['user']) ? intval($_POST['user']) : 0;
                        $supp_ads_exp = isset($_POST['supp_ads_exp']) ? intval($_POST['supp_ads_exp']) : intval($_GET['supp_ads_exp']);
                        $ok =  isset($_POST['ok']) ? intval($_POST['ok']) : 0;

                        if ( $user == 0) redirect_header("purge.php", 2, _AM_CATADS_DELETE_ADS_NOUSER);;

                        if ( $ok == 1 ) {
                        $query = "SELECT uname FROM ".$xoopsDB->prefix("users")." WHERE uid=".$user;
                        $result = $xoopsDB->queryF($query);
                        $data = $xoopsDB->fetchArray($result);
                        $uname = $data['uname'];

                        if ( $supp_ads_exp == 1 ) {
                        //if ($onemsg->getVar('expired') < time()) {
                        $query = "DELETE FROM ".$xoopsDB->prefix("catads_ads")." WHERE uid=".$user;
                        $result = $xoopsDB->queryF($query);
                        }
                        else
                        {
                        $query = "DELETE FROM ".$xoopsDB->prefix("catads_ads")." WHERE uid=".$user." AND expired < ".time()."";
                        $result = $xoopsDB->queryF($query);
                        }
                        $messagesent = sprintf(_AM_CATADS_DELETE_ADS_USER,$uname);
                        redirect_header("purge.php", 2, $messagesent);
                        }
                        else
                        {
                                xoops_confirm(array('op' => 'purge_ads_user', 'user' => $user, 'supp_ads_exp' => $supp_ads_exp, 'ok' => 1), 'purge.php', _AM_CATADS_CONF_DELEXP);
                        }
        }


/*
 * AUTEUR                : Kraven30
 * ENTREES                :
 * SORTIES                :
 * DESCRIPTION        : Affichage des formulaire de purge
 * COMMENTAIRES        :
 * GLOBALES                : $xoopsDB
 */
        function show_purge()
        {
                global $xoopsDB;
                //Formulaire de Purge pour tout le monde
                ///////////////////////////////////////////////
                $form = new XoopsThemeForm(_AM_CATADS_ADS_PURGE_ALL_USER, "form", "purge.php");
                $form->addElement(new XoopsFormRadioYN(_AM_CATADS_DELEXP3, 'supp_ads_all_user', 0), true);
                $form->addElement(new XoopsFormHidden("op", "purge_ads_all_user"), true);
                $form->addElement(new XoopsFormButton("", "submit", _AM_CATADS_PURGER, "submit"), true);
                $form->display();
                echo "<br />";

                //Formulaire de Purge par utilisateur
                ///////////////////////////////////////////////
                $query = "SELECT uid FROM ".$xoopsDB->prefix("catads_ads")." GROUP BY uid";
                $result = $xoopsDB->query($query);
                $form = new XoopsThemeForm(_AM_CATADS_ADS_USER,"form", "purge.php");
                $select = new XoopsFormSelect(_AM_CATADS_DELEXP1, "user", null, 5, false);
                while($user = $xoopsDB->fetchArray($result))
                {
                        $select->addOption($user["uid"], XoopsUser::getUnameFromId($user["uid"]));
                }

                $form->addElement($select, true);
                $form->addElement(new XoopsFormRadioYN(_AM_CATADS_ADS_DELEXP, 'supp_ads_exp', 0), true);
                $form->addElement(new XoopsFormHidden("op", "purge_ads_user"), true);
                $form->addElement(new XoopsFormButton("", "submit", _AM_CATADS_PURGER, "submit"), true);
                $form->display();
                echo "<br />";
                ///////////////////////////////////////////////

                //Formulaire de Purge par nb jours
                ///////////////////////////////////////////////
                $delform = new XoopsThemeForm(_AM_CATADS_ADS_PURGE, "form", "purge.php");
                $elt_tray = new XoopsFormElementTray(_AM_CATADS_DELEXP2,'');
                $elt_tray->addElement(new XoopsFormText('', 'nbdays', 10, 5, 30), true);
                $elt_tray->addElement(new XoopsFormLabel('', _AM_CATADS_DAYS ));
                $delform->addElement($elt_tray);
                $delform->addElement(new XoopsFormHidden("op", "purge_ads_expired"), true);
                $delform->addElement(new XoopsFormButton('', 'submit', _AM_CATADS_PURGER, 'submit'));
                $delform->display();
                ///////////////////////////////////////////////
        }
        /////////////////////////////////////////////////////////////////////////////////////////////////////////


/*//Page permission.php
function sf_userIsAdmin()
{
        global $xoopsUser;

        $result = false;

        $smartModule =& sf_getModuleInfo();
        $module_id = $smartModule->getVar('mid');

        if (!empty($xoopsUser)) {
                $groups = $xoopsUser->getGroups();
                $result = (in_array(XOOPS_GROUP_ADMIN, $groups)) || ($xoopsUser->isAdmin($module_id));
        }
        return $result;
}*/

function show_footer()
{
        echo "<br /><br /><div align='center'><a href='http://www.frxoops.org/' target='_blank'><img src='../images/icon/thecat.gif' alt='' /></a></div>";
}

?>