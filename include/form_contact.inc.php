<?php
// $Id: signform.inc.php,v 1.2 2004/10/28 the Cat
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
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
//  ------------------------------------------------------------------------ //

include XOOPS_ROOT_PATH."/class/xoopsformloader.php";

$form_contact = new XoopsThemeForm(_MD_CATADS_CONTACTAUTOR, "form_contact", "contact.php");

$title_text = new XoopsFormText(_MD_CATADS_TITLE, "title", 52, 100, $title);
$title_text->setExtra("readonly = 'readonly'");
$form_contact->addElement($title_text, true);

$name_text = new XoopsFormText(_MD_CATADS_YOURNAME.'*', "name_user", 50, 100, $name_user);
$form_contact->addElement($name_text, true);

$email_text = new XoopsFormText(_MD_CATADS_YOUREMAIL.'*', "email_user", 50, 100, $email_user);
$form_contact->addElement($email_text,true);

$phone_text = new XoopsFormText(_MD_CATADS_YOURPHONE, "phone", 20, 20,$phone);
$form_contact->addElement($phone_text, false);

$annonce_text = new XoopsFormTextArea(_MD_CATADS_YOURMESSAGE.'*', "message", $message);
$form_contact->addElement($annonce_text, true);

$button_tray = new XoopsFormElementTray('' ,'');
$button_tray->addElement(new XoopsFormButton('', 'preview', _PREVIEW, 'submit'));
$button_tray->addElement(new XoopsFormButton('', 'post', _SEND, 'submit'));
$button_cancel = new XoopsFormButton('', 'cancel', _CANCEL, 'button');
$button_cancel->setExtra("' onclick='javascript:window.close();'");
$button_tray->addElement($button_cancel);

$form_contact->addElement($button_tray);

$form_contact->addElement(new XoopsFormHidden('email_author', $email_author));

$form_contact->display();
?>