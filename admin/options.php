<?php
/*
 * ****************************************************************************
 * Catads - MODULE FOR XOOPS
 * 2008/02/13 by The Cat
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

$op = '';
// nombre de listes déroulantes
$count_type = 4;

foreach ($_POST as $k => $v) {${$k} = $v;}
foreach ($_GET as $k => $v) {${$k} = $v;}

if ( isset($_POST['add'] )) $op = 'add';
elseif ( isset($_POST['delete']) ) $op = 'delete';
elseif ( isset($_POST['edit']) ) $op = 'edit';
elseif ( isset($_POST['save']) ) $op = 'save';

if (isset($_GET['op'])) $op=$_GET['op'];
if (isset($_POST['op'])) $op=$_POST['op'];

$option_handler =& xoops_getmodulehandler('option');

switch($op){
        case "edit":
                //Edition d'une option
                 xoops_cp_header();
                catads_admin_menu(3, _AM_CATADS_OPTMANAGE);
                echo "<br />" ; 

                $option = & $option_handler->get($option_id);
                $option_type = $option->getVar('option_type');
                $option_text = constant('_AM_CATADS_OPT'.$option_type);
                $option_desc = $option->getVar('option_desc');
                $option_order = $option->getVar('option_order');
                $option_id = $option->getVar('option_id');

            include_once XOOPS_ROOT_PATH.'/class/xoopsformloader.php';
                $form1 = new XoopsThemeForm(_AM_CATADS_OPT_EDIT, "modify", xoops_getenv('PHP_SELF'));
                $form1->addElement(new XoopsFormLabel(_AM_CATADS_OPT_IN, $option_text));
                $form1->addElement(new XoopsFormText(_AM_CATADS_OPT_TITLE, "option_desc", 20, 20, $option_desc), true);
                $form1->addElement(new XoopsFormText(_AM_CATADS_OPT_ORDER, "option_order", 2, 2, $option_order));
                $button_tray = new XoopsFormElementTray('','');
                $button_tray->addElement(new XoopsFormButton('', 'save',_SEND, 'submit'));
                $form1->addElement($button_tray);
                $form1->addElement(new xoopsFormHidden('option_id', $option_id));
                $form1->display();
                xoops_cp_footer();
           break;

    case "delete":
                //Effacement
        if ( $ok != 1 ) {
                        xoops_cp_header();
                        xoops_confirm(array('op' => 'delete', 'option_id' => $option_id, 'ok' => 1), 'options.php', _AM_CATADS_CONFDELOPT);
                        xoops_cp_footer();
              } else {
                        $option = & $option_handler->get($option_id);
                        $del_option_ok = $option_handler->delete($option);
                        // TODO: message si erreur
            redirect_header("options.php?op=default",1,_AM_CATADS_OPT_DEL);
            exit();
             }
           break;

           case "add":
                // Ajout d'option
                if (!is_numeric(trim($option_order))) {
                        redirect_header($HTTP_SERVER_VARS['PHP_SELF'],2,_AM_CATADS_MUST_NUMBER);
                }
                if (trim($option_desc) == '') {
                        redirect_header($HTTP_SERVER_VARS['PHP_SELF'],2,_AM_CATADS_MUST_TEXT);
                }
                $option_order = isset($option_order) ? intval($option_order) : 0;
                $option = $option_handler->create();
                $option->setVar('option_type', $option_type);
                $option->setVar('option_desc', $option_desc);
                $option->setVar('option_order', $option_order);

                if (!$option_handler->insert($option)) {
                        xoops_header();
//                        echo "<h4>"._AM_CATADS."</h4>";
                        xoops_error($option->getErrors());
                        xoops_footer();
                        exit();
                }
        redirect_header("options.php?op=op=default",1,_AM_CATADS_DB_UPDATED);
        exit();
           break;

          case "save":
                //Sauvegarde après modification
                if (!is_numeric(trim($option_order))) {
                        redirect_header($HTTP_SERVER_VARS['PHP_SELF'],2,_AM_CATADS_MUST_NUMBER);
                }
                $option = & $option_handler->get($option_id);
                $option->setVar('option_desc', $option_desc);
                $option->setVar('option_order', $option_order);
                $add_option_ok = $option_handler->insert($option);
                // TODO: message si erreur
        redirect_header("options.php?op=op=default",1,_AM_CATADS_DB_UPDATED);
        exit();
    break;

          case "default":
    default:
                   xoops_cp_header();
                catads_admin_menu(3, _AM_CATADS_OPTMANAGE);
                echo "<br />" ;

                $criteria = new Criteria('option_id', '0', '>');
                $criteria->setSort('option_order');
//                $criteria->setSort('option_type');
                $option = $option_handler->getObjects($criteria);
                $count_opt = 0;
                // Récupération des options dans un tableau
                foreach($option as $oneoption){
                        $arr_option[$count_opt]['option_id'] = $oneoption->getVar('option_id');
                        $arr_option[$count_opt]['option_type'] = $oneoption->getVar('option_type');
                        $arr_option[$count_opt]['option_desc'] = $oneoption->getVar('option_desc');
                        $arr_option[$count_opt]['option_order'] = $oneoption->getVar('option_order');
                        $count_opt++;
                }


            include_once XOOPS_ROOT_PATH.'/class/xoopsformloader.php';
                // Boucle formulaire listes des options
                for ($k = 1; $k < $count_type + 1 ; $k++)
                {
                        $title = constant('_AM_CATADS_OPT'.$k);
                        $form = new XoopsThemeForm($title, "modify", xoops_getenv('PHP_SELF'));
                        $select_option[$k] = new XoopsFormSelect('', "option_id");
                        for ( $i = 0; $i < $count_opt; $i++ )
                        {
                                if ($arr_option[$i]['option_type'] == $k)
                                {
                                        $select_option[$k]->addOption($arr_option[$i]['option_id'],$arr_option[$i]['option_desc']);
                                }
                        }
                        $form->addElement($select_option[$k], true);
                        $button_tray[$k] = new XoopsFormElementTray('','');
                        $button_tray[$k]->addElement(new XoopsFormButton('', 'edit',_EDIT, 'submit'));
                        $button_tray[$k]->addElement(new XoopsFormButton('', 'delete',_DELETE, 'submit'));
                        $form->addElement(new XoopsFormHidden('option_type',$k));
                        $form->addElement($button_tray[$k]);
                        $form->display();
                        echo "<br />";
                }

                echo "<br /><br />";
                // Formulaire d'ajout
                $form1 = new XoopsThemeForm(_AM_CATADS_OPT_ADD, "modify", xoops_getenv('PHP_SELF'));
                $select_type = new XoopsFormSelect(_AM_CATADS_OPT_IN, "option_type");
                $select_type->addOptionArray(array(1=>_AM_CATADS_OPT1, 2=>_AM_CATADS_OPT2, 3=>_AM_CATADS_OPT3, 4=>_AM_CATADS_OPT4));
                $form1->addElement($select_type);
                $form1->addElement(new XoopsFormText(_AM_CATADS_OPT_TITLE, "option_desc", 20, 20), true);
                $form1->addElement(new XoopsFormText(_AM_CATADS_OPT_ORDER, "option_order", 2, 2, 0));
                $button_tray = new XoopsFormElementTray('','');
                $button_tray->addElement(new XoopsFormButton('', 'add',_ADD, 'submit'));
                $form1->addElement($button_tray);
                $form1->display();
                xoops_cp_footer();
        break;
}
?>