<?php
// $Id: funddetailform.php, 2007/4/12 $
//  ------------------------------------------------------------------------ //
//                ChurchLedger.com/OSC                      //
//                    Copyright (c) 2007 ChurchLedger.com//
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
    exit(_oscgiv_admin_accessdenied);
}


//determine action
$action='';
$op = '';
$confirm = '';

if (isset($_GET['op'])) $op = $_GET['op'];
if (isset($_POST['op'])) $op = $_POST['op'];
if (isset($_GET['id'])) $id=$_GET['id'];
if (isset($_POST['id'])) $id=$_POST['id'];
if (isset($_GET['action'])) $action=$_GET['action'];
if (isset($_POST['action'])) $action = $_POST['action'];


$myts = &MyTextSanitizer::getInstance();
$fund_handler = &xoops_getmodulehandler('fund', 'oscgiving');

$fund=$fund_handler->create(false);


if(isset($id)) $fund= $fund_handler->getFund($id);

switch (true) 
{
    case ($op=="save" || $op=="create"):
    	if(isset($_POST['fund_name'])) $fund->assignVar('fund_Name',$_POST['fund_name']);
	
	if(isset($_POST['fund_id']))
	$fund->assignVar('fund_id',$_POST['fund_id']);
	
	if($op=="save")
	{
		if($fund_handler->update($fund))
		{$message=_oscgiv_fundupdatesuccess;}
		else 
		{
			$message=_oscgiv_fundupdatefailure;
		}
	}
	if($op=="create")
	{
		if($fund_handler->insert($fund))
		$message=_oscgiv_fundcreatesuccess;
		else $message=_oscgiv_fundcreatefailure;
	}
	    
 redirect_header("fundselect.php", 3, $message);
    break;
}

$fundname=new XoopsFormText(_oscgiv_fundname,"fund_name",30,50,$fund->getVar('fund_Name'));


$id_hidden = new XoopsFormHidden("fund_id",$fund->getVar('fund_id'));

$op_hidden = new XoopsFormHidden("op", $action);  //save operation

$submit_button = new XoopsFormButton("", "funddetailsubmit", _oscgiv_save, "submit");

$form = new XoopsThemeForm(_oscgiv_fundadmin_TITLE, "funddetailform", "funddetailform.php", "post", true);

$form->addElement($fundname);

$form->addElement($op_hidden);
$form->addElement($id_hidden);

//Upload stuff

$form->addElement($submit_button);
$form->setRequired($fundname);

xoops_cp_header();
$form->display();

xoops_cp_footer();

?>