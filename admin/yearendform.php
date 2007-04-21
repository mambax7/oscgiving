<?php
// $Id: osclistdetailform.php,v 1.2 2006/07/25 21:44:20 root Exp $
//  ------------------------------------------------------------------------ //
//                ChurchLedger.com/OSC                      //
//                    Copyright (c) 2006 ChurchLedger.com//
//                       <http://www.churchledger.com/>                             //
//  ------------------------------------------------------------------------ //
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
// Author: Steve McAtee                                          //
// URL: http://www.churchledger.com, http://www.xoops.org/
// Project: The XOOPS Project, The Open Source Church project (OSC)
// ------------------------------------------------------------------------- //
include("../../../include/cp_header.php");
include_once(XOOPS_ROOT_PATH . "/class/xoopsformloader.php");
include_once(XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/include/functions.php");

// include the default language file for the admin interface
if ( file_exists( "../language/" . $xoopsConfig['language'] . "/main.php" ) ) {
    include "../language/" . $xoopsConfig['language'] . "/main.php";
}
elseif ( file_exists( "../language/english/main.php" ) ) {
    include "../language/english/main.php";
}

if (file_exists(XOOPS_ROOT_PATH. "/modules/" . 	$xoopsModule->getVar('dirname') .  "/language/" . $xoopsConfig['language'] . "/modinfo.php")) {
    include XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/language/" . $xoopsConfig['language'] . "/modinfo.php";
}
elseif( file_exists(XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') ."/language/english/modinfo.php"))
{ include XOOPS_ROOT_PATH ."/modules/" . $xoopsModule->getVar('dirname') . "/language/english/modinfo.php";

}


//redirect
if (!$xoopsUser)
{
    redirect_header(XOOPS_URL."/user.php", 3, _AD_NORIGHT);
}


//verify permission
if ( !is_object($xoopsUser) || !is_object($xoopsModule) || !$xoopsUser->isAdmin($xoopsModule->mid()) ) {
    exit(_oscmem_admin_accessdenied);
}


//determine action
$action='';
$op = '';


if (isset($_GET['id'])) $id=$_GET['id'];
if (isset($_POST['id'])) $id=$_POST['id'];
if (isset($_GET['action'])) $action=$_GET['action'];
if (isset($_POST['action'])) $action = $_POST['action'];
if (isset($_POST['op'])) $op=$_POST['op'];

if (isset($_POST['yearendletter'])) $yearendletter=$_POST['yearendletter'];


$setting_handler = &xoops_getmodulehandler('givsetting', 'oscgiving');
$oscgivsetting = $setting_handler->getSetting();

switch (true) 
{
    case ($op=="save"):
    
	$oscgivsetting->assignVar('letterendofyear',$yearendletter);
	$setting_handler->saveSetting($oscgivsetting);
	$message=_oscgiv_yearendletter_savesuccess;
	redirect_header("yearendform.php" , 3, $message);
	break;
    
}


$caption=_oscgiv_yearenddonationletter_text;
$name="yearendletter";
$value=$oscgivsetting->getVar('letterendofyear');
$supplemental="";

$editor = new XoopsFormDhtmlTextArea($caption, $name, $value, 10, 50, $supplemental);


$labelcontent = _oscgiv_letterfields_instructions . "<p>" . _oscgiv_letterfields_fields;

$label=new XoopsFormLabel(_oscgiv_letterfields,$labelcontent);

$submit_button = new XoopsFormButton("", "submit", _oscgiv_submit, "submit");

$ophidden=new XoopsFormHidden("op","save");

$form = new XoopsThemeForm(_oscgiv_settings_title, "yearendform", "yearendform.php", "post", true);

$form->addElement($ophidden);
$form->addElement($editor);
$form->addElement($label);
$form->addElement($submit_button);

//Upload stuff

//$form->setRequired($yearendform);

xoops_cp_header();
$form->display();

xoops_cp_footer();

?>