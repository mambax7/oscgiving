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

if (file_exists(XOOPS_ROOT_PATH. "/modules" . 	$xoopsModule->getVar('dirname') .  "/language/" . $xoopsConfig['language'] . "/modinfo.php")) {
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
$confirm = '';
$optionid = (isset($_GET['optionid'])) ? intval($_GET['optionid']) : 0;


if (isset($_GET['op'])) $op = $_GET['op'];
if (isset($_POST['op'])) $op = $_POST['op'];
if (isset($_GET['optionid'])) $optionid=$_GET['optionid'];
if (isset($_POST['optionid'])) $optionid=$_POST['optionid'];
if (isset($_GET['id'])) $id=$_GET['id'];
if (isset($_POST['id'])) $id=$_POST['id'];
if (isset($_GET['action'])) $action=$_GET['action'];
if (isset($_POST['action'])) $action = $_POST['action'];


$myts = &MyTextSanitizer::getInstance();
$osclist_handler = &xoops_getmodulehandler('osclist', 'oscmembership');

$osclist=$osclist_handler->create($id);
$osclist->assignVar('id',$id);
$osclist->assignVar('optionid',$optionid);

$osclist= $osclist_handler->get($osclist);

switch (true) 
{
    case ($op=="save" || $op=="create"):
    	if(isset($_POST['optionname'])) $osclist->assignVar('optionname',$_POST['optionname']);
	
	if(isset($_POST['optionsequence'])) $osclist->assignVar('optionsequence',$_POST['optionsequence']);

	if($op=="save")
	{
		$osclist_handler->update($osclist);
		$message=_oscmem_UPDATESUCCESS;
	}
	if($op=="create")
	{
		$osclist_handler->insert($osclist);
		$message=_oscmem_createsuccess;
	}
	    
	redirect_header("osclistselect_smarty.php?id=" . $id, 3, $message);
    break;
}

$optionname=new XoopsFormText(_oscmem_optionname,"optionname",30,50,$osclist->getVar('optionname'));

$optionsequence=new XoopsFormText(_oscmem_optionsequence,"optionsequence",5,5,$osclist->getVar('optionsequence'));


$id_hidden = new XoopsFormHidden("id",$osclist->getVar('id'));
$optionid_hidden = new XoopsFormHidden("optionid",$osclist->getVar('optionid'));

$op_hidden = new XoopsFormHidden("op", $action);  //save operation

$submit_button = new XoopsFormButton("", "osclistdetailsubmit", _osc_save, "submit");

$form = new XoopsThemeForm(_oscmem_osclist_famrole_TITLE, "osclistdetailform", "osclistdetailform.php", "post", true);

$form->addElement($optionname);
$form->addElement($optionsequence);
$form->addElement($optionid_hidden);

$form->addElement($op_hidden);
$form->addElement($id_hidden);

//Upload stuff

$form->addElement($submit_button);
$form->setRequired($optionname);
$form->setRequired($optionsequence);

xoops_cp_header();
$form->display();

xoops_cp_footer();

?>