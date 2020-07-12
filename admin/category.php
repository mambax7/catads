<?php
/*
 * ****************************************************************************
 * Catads - MODULE FOR XOOPS
 * 2005/07/15 C. Felix AKA the Cat
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

include("admin_header.php");
include_once(XOOPS_ROOT_PATH."/modules/".$xoopsModule->dirname()."/class/cat.php");
include_once XOOPS_ROOT_PATH."/modules/".$xoopsModule->dirname()."/admin/functions.php";

$op = '';

foreach ($_POST as $k => $v) {${$k} = $v;}
foreach ($_GET as $k => $v) {${$k} = $v;}

if ( isset($_POST['save'] )) $op = 'save';
elseif ( isset($_POST['delete'])) $op = 'delete';
elseif ( isset($_POST['edit']) ) $op = 'edit';

if (isset($_GET['op'])) $op=$_GET['op'];


        switch($op){
            case "delete":
                   if ( $ok != 1 ) {
                                xoops_cp_header();
                                xoops_confirm(array('op' => 'delete', 'topic_id' => $topic_id, 'ok' => 1), 'category.php', _AM_CATADS_CONFDELCAT);
                                xoops_cp_footer();
                  } else {
                    $cat = new AdsCategory($topic_id);
                                $ads_handler =& xoops_getmodulehandler('ads');
                                // Ajout des sous-rubriques pour effacement
                $cat_arr = $cat->getAllChild();
                array_push($cat_arr, $cat);
                foreach($cat_arr as $eachcat){
                                        // Effacement des annonces de la rubrique
                                        $criteria = new Criteria('cat_id', $topic_id);
                                        $ads = $ads_handler->getObjects($criteria);
                                         foreach($ads as $oneads){
                                                if($oneads->getVar('photo')) {
                                                        $filename = XOOPS_ROOT_PATH.'/uploads/catads/images/annonces/original/'.$oneads->getVar('photo');
                                                        unlink($filename);
                                                }
                                                $del_ads_ok = $ads_handler->delete($oneads);
                                        }
                                        // Effacement de chaque rubrique
                                      $eachcat->delete();
                  }
                                // cache
                                include_once XOOPS_ROOT_PATH."/class/xoopsblock.php";
                                include_once XOOPS_ROOT_PATH.'/class/template.php';
                                xoops_template_clear_module_cache($xoopsModule->getVar('mid'));
                redirect_header("category.php?op=default",2,_AM_CATADS_CAT_DEL);
                exit();
                }
                break;

        case "edit":
            xoops_cp_header();
                        catads_admin_menu(2, _AM_CATADS_CATMANAGE);
                        categoryForm($topic_id,0);
            break;

        case "save":
                        if ($add != 1 && ($topic_id) == $topic_pid) {
                                redirect_header($_SERVER['PHP_SELF'],2,_AM_CATADS_CANNOT_MOVE_HERE);
                        }
                        ($add ==1) ? $cat = new AdsCategory() : $cat = new AdsCategory($topic_id);

                        if (!is_numeric(trim($weight)))
                                redirect_header($_SERVER['PHP_SELF'],2,_AM_CATADS_MUST_NUMBER);

                         $cat->setPid($topic_pid);
                        $cat->setTitle($title);
                        $cat->setDesc($desc);
                        $cat->setPrice($display_price);
                        $cat->setDisplayCat($display_cat);
                        $cat->setWeigth($weight);
                        $cat->setPhoto($nb_photo);

                        if (isset($indeximage)) {
                                $cat->setImg($indeximage);
                    }

                        $cat->store();
                        // cache
                        include_once XOOPS_ROOT_PATH."/class/xoopsblock.php";
                        include_once XOOPS_ROOT_PATH.'/class/template.php';
                        xoops_template_clear_module_cache($xoopsModule->getVar('mid'));
            redirect_header("category.php",1,_AM_CATADS_DB_UPDATED);
            exit();
            break;

        case "default":
        default:
                xoops_cp_header();
                        catads_admin_menu(2, _AM_CATADS_CATMANAGE);
                        echo "<br />" ;
                        if (AdsCategory::countCat() > 0) {
                           $xt = new XoopsTree($xoopsDB->prefix("catads_cat"),'topic_id','topic_pid');
                            // Formulaire éditer une rubrique
                    include_once XOOPS_ROOT_PATH.'/class/xoopsformloader.php';
                                $mform = new XoopsThemeForm(_AM_CATADS_MODIFYCATEGORY, "modify", xoops_getenv('PHP_SELF'));
                                ob_start();
                                $xt->makeMySelBox('topic_title','topic_title',0,0);

                                $mform->addElement(new XoopsFormLabel(_AM_CATADS_CATEGORY, ob_get_contents()));
                                ob_end_clean();
                                $button_tray = new XoopsFormElementTray('','');
                                $button_tray->addElement(new XoopsFormButton('', 'edit', _EDIT, 'submit'));
                                $button_tray->addElement(new XoopsFormButton('', 'delete', _DELETE, 'submit'));
                                $mform->addElement($button_tray);
                                $mform->display();
                        }
                        // Formulaire ajouter une rubrique
                        categoryForm(0,1);

                echo "<br><br>
                <fieldset><legend style='font-weight: bold; color: #900;'>" . _AM_CATADS_INFOS_CATS_TITLE . "</legend>
                        <div style='padding: 8px;'>" . _AM_CATADS_INFOS_CATS . "</div>
                </fieldset>";
                        break;
}
xoops_cp_footer();
?>