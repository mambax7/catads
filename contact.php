<?php
// $Id: contact.php,v 1.0 2004/07/20 C. Felix AKA the Cat
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

$op = 'form';
foreach ($_POST as $k => $v) {${$k} = $v;}
foreach ($_GET as $k => $v) {${$k} = $v;}

if ( isset($preview)) {
        $op = 'preview';
} elseif ( isset($post) ) {
        $op = 'post';
}


function displaypost($title, $content) {
        echo '<table cellpadding="4" cellspacing="1" width="98%" class="outer"><tr><td class="head">'.$title.'</td></tr><tr><td><br />'.$content.'<br /></td></tr></table>';
}

switch($op) {

case "post":
        global $xoopsConfig;
        include("../../mainfile.php");
        $ts =& MyTextSanitizer::getInstance();

                $fullmsg = _MD_CATADS_FROMUSER." $name_user "._MD_CATADS_YOURADS." ".$xoopsConfig['sitename']." :\n\n";
                $fullmsg .= $title."\n";
                $fullmsg .= "-----------------------------------------------------------------\n";
                $fullmsg .= "$message\n\n";
                $fullmsg .= "-----------------------------------------------------------------\n\n";
                $fullmsg .= _MD_CATADS_CANJOINT." $email_user";
                if ($phone !='')
                        $fullmsg .= ' ou'._MD_CATADS_ORAT." $phone";

                $xoopsMailer =& getMailer();
                $xoopsMailer->useMail();
                $xoopsMailer->setFromEmail($email_user);
                $xoopsMailer->setFromName($xoopsConfig['sitename']);
                $xoopsMailer->setToEmails($email_author);
                $xoopsMailer->setSubject(_MD_CATADS_CONTACTAFTERADS);
                $xoopsMailer->setBody($fullmsg);
                $msgsend = "<div style='text-align:center;'><br /><br />";
                if ( !$xoopsMailer->send()) {
                        $msgsend .= $xoopsMailer->getErrors();
                } else {
                        $msgsend .= "<h4>"._MD_CATADS_MSGSEND."</h4>";
                }
                $msgsend .= "<br /><br /><a href=\"javascript:window.close();\">"._MD_CATADS_CLOSEF."</a></div>";
                echo $msgsend;
        break;

case "preview":
        include("../../mainfile.php");
        $ts =& MyTextSanitizer::getInstance();
        xoops_header();

        $p_title = $title;
//        $p_title = $myts->makeTboxData4Preview($p_title);
        $p_title = $ts->htmlSpecialChars($ts->stripSlashesGPC($p_title));
        $p_msg = _MD_CATADS_FROMUSER." $name_user "._MD_CATADS_YOURADS." ".$xoopsConfig['sitename']." :<br />";
                $p_msg .= $title.'<br />';
                $p_msg .= "-----------------------------------------------------------------<br />";
                $p_msg .= $message.'<br /><br />';
                $p_msg .= "-----------------------------------------------------------------<br />";
                $p_msg .= _MD_CATADS_CANJOINT." $email_user";
                if ($phone !='')
                        $p_msg .= '<br />'._MD_CATADS_ORAT." $phone";

        $p_msg .= '<br />';
        displaypost($p_title, $p_msg);

        $title =  $ts->htmlSpecialChars($ts->stripSlashesGPC($title));
        $message =  $ts->htmlSpecialChars($ts->stripSlashesGPC($message));

        include "include/form_contact.inc.php";
        xoops_footer();
        break;

case "form":
default:
        // pk add global
        global $xoopsModuleConfig ;

        include("../../mainfile.php");
        xoops_header();
        $ads_handler = & xoops_getmodulehandler('ads');
        $ads = & $ads_handler->get($ads_id);
        // initialisation variables
        $message = '';
        $phone = '';
        $name_user = '';
        $email_user ='';
        $email_author = $ads->getVar('email');

        // pk get show/hide ads type pref
        $show_ad_type = $xoopsModuleConfig['show_ad_type'];

        // pk make ad-type conditional
        if($show_ad_type == '1'){
        $title = $ads->getVar('ads_type'). ' : '.$ads->getVar('ads_title');
        } else {
        $title = $ads->getVar('ads_title');
        }



        if($xoopsUser) {
                $name_user = ($xoopsUser->getVar('name')!='')? $xoopsUser->getVar("name") : $xoopsUser->getVar("uname");
                $email_user = $xoopsUser->getVar("email", "E");
        }

        include "include/form_contact.inc.php";
        xoops_footer();
break;
}
?>