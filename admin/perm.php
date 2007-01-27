<?php
// $Id: perm.php,v 2006/11/21 srmcatee Exp $
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2006 XOOPS.org                           //
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
include '../../../include/cp_header.php';
include_once XOOPS_ROOT_PATH.'/class/xoopsform/grouppermform.php';

//redirect
if (!$xoopsUser)
{
    redirect_header(XOOPS_URL."/user.php", 3, _AD_NORIGHT);
}


//verify permission
if ( !is_object($xoopsUser) || !is_object($xoopsModule) || !$xoopsUser->isAdmin($xoopsModule->mid()) ) {
    exit(_oscmem_admin_access_denied);
}


xoops_cp_header();

//$xTheme->loadModuleAdminMenu(4);

$module_id = $xoopsModule->getVar('mid');
//$catHandler = xoops_getmodulehandler('cat', 'extcal');
//$item_list = $catHandler->getAllCatWithoutPermDisplay();
//if(count($item_list) > 0) {
	$title_of_form = _oscmem_permissions_view;
	$perm_name = 'oscmembership_view';
	$perm_desc = _oscmem_permissions_view_desc;
	$form = new XoopsGroupPermForm($title_of_form, $module_id, $perm_name, $perm_desc, 'admin/perm.php');
	$form->addItem('1',_oscmem_permissions_view);
//	foreach ($item_list as $item_id => $item_name) {
//	$form->addItem($item_id, $item_name);
//	}
	echo $form->render().'<br />';

	$title_of_form = _oscmem_permissions_modify;
	$perm_name = 'oscmembership_modify';
	$perm_desc = _oscmem_permissions_modify_desc;
	$form = new XoopsGroupPermForm($title_of_form, $module_id, $perm_name, $perm_desc, 'admin/perm.php');
	$form->addItem('1',_oscmem_permissions_modify);
/*
	foreach ($item_list as $item_id => $item_name) {
	$form->addItem($item_id, $item_name);
	}
*/
	echo $form->render().'<br />';

	$title_of_form = _oscmem_permissions_admin;
	$perm_name = 'oscmembership_admin';
	$perm_desc = _oscmem_permissions_admin_desc;
	$form = new XoopsGroupPermForm($title_of_form, $module_id, $perm_name, $perm_desc, 'admin/perm.php');
	$form->addItem('1',_oscmem_permissions_admin);
/*
	foreach ($item_list as $item_id => $item_name) {
	$form->addItem($item_id, $item_name);
	}
*/
	echo $form->render().'<br />';

	/*	
	$title_of_form = _AM_EXTCAL_AUTOAPPROVE_PERMISSION;
	$perm_name = 'extcal_cat_autoapprove';
	$perm_desc = _AM_EXTCAL_AUTOAPPROVE_PERMISSION_DESC;
	$form = new XoopsGroupPermForm($title_of_form, $module_id, $perm_name, $perm_desc, 'admin/perm.php');
	foreach ($item_list as $item_id => $item_name) {
	$form->addItem($item_id, $item_name);
	}
	echo $form->render();
} else {
	echo _AM_EXTCAL_PERM_NO_CATEGORY;
}
*/

xoops_cp_footer();
?>