<?php
// $Id: customfielddetailform.php,v 1.1.1.1 2006/03/12 14:57:25 root Exp $
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2006 osc churchledger.com
//			http://www.churchledger.com
//
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
//$xoopsOption['template_main'] = 'cs_index.html';
//include(XOOPS_ROOT_PATH."/header.php");
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


//redirect
if (!$xoopsUser)
{
    redirect_header(XOOPS_URL."/user.php", 3, _AD_NORIGHT);
}


//verify permission
if ( !is_object($xoopsUser) || !is_object($xoopsModule) || !$xoopsUser->isAdmin($xoopsModule->mid()) ) {
    exit(_oscmem_admin_access_denied);
}


//determine action
$op = '';
$confirm = '';
$action='';


if (isset($_GET['op'])) $op = $_GET['op'];
if (isset($_POST['op'])) $op = $_POST['op'];
if (isset($_POST['action'])) $action=$_POST['action'];
if (isset($_GET['action'])) $action=$_GET['action'];
if (isset($_GET['id'])) $id=$_GET['id'];
if (isset($_POST['id'])) $id=$_POST['id'];

if (isset($_POST['custom_Order'])) $customOrder=$_POST['custom_Order'];
if (isset($_POST['custom_Field'])) $customField=$_POST['custom_Field'];
if (isset($_POST['custom_Name'])) $customName=$_POST['custom_Name'];
if (isset($_POST['custom_Special'])) $customSpecial=$_POST['custom_Special'];
if (isset($_POST['custom_Side'])) $customSide=$_POST['custom_Side'];
if (isset($_POST['type_ID'])) $typeID=$_POST['type_ID'];

$myts = &MyTextSanitizer::getInstance();
$customfield_handler = &xoops_getmodulehandler('customfield', 'oscmembership');

switch (true) 
{
  case ($op=="save" || $op=="create"):

	$customfield=$customfield_handler->create();
	$customfield->assignVar('custom_Order',$customOrder);
	$customfield->assignVar('custom_Field',$id);
	$customfield->assignVar('custom_Name',$customName);
	$customfield->assignVar('custom_Special',$customSpecial);
	$customfield->assignVar('custom_Side',$customSide);
	$customfield->assignVar('type_ID',$typeID);

	if($op=="save")
	{
		$customfield_handler->update($customfield);
		$message=_oscmem_admin_UPDATESUCCESS;
	}
	if($op=="create")
	{
	
		$customfield_handler->insert($customfield);	
		$message=_osmem_admin_createcustomfield_success;
	}
	    
	redirect_header("customfieldselectform.php" , 3, $message);
    break;
    
    default:
	$customfield=$customfield_handler->get($id);
    
	break;
}


//$id_hidden = new XoopsFormHidden("id",$person->getVar('id'));

$op_hidden = new XoopsFormHidden("op", "save");  //save operation
$submit_button = new XoopsFormButton("", "customdetailsubmit", _oscmem_admin_save, "submit");

if($action=="create")
{
	$op_hidden = new XoopsFormHidden("op", "create");  //save operation
	$submit_button = new XoopsFormButton("", "customfielddetailsubmit", _oscmem_admin_create, "submit");
}

$form = new XoopsThemeForm(_oscmem_customfield, "customfielddetailform", "customfielddetailform.php", "post", true);

$form->addElement($op_hidden);
//$form->addElement($id_hidden);

//Upload stuff

$form->addElement($submit_button);
//$form->setRequired($lastname_text);
//$form->setRequired($firstname_text);

$db = &Database::getInstance();

//Retrieve custom fields
$count=1;
$customFieldtypes_result = $customfield_handler->getcustomfieldtypes();
$option_array=array();

if(isset($customFieldtypes_result))
{
	while($row = $db->fetchArray($customFieldtypes_result))	
	{
		$option_array[$row['optionid']]=$row['optionname'];
	}
}

//$table_code="<table><tr>";
//$table_code = $table_code . "<th></th><th>" . _oscmem_customfieldName . "</th><th>" . _oscmem_customfieldType . "</th></tr>";

//$fieldname_text= new XoopsFormText(_oscmem_customfieldName, "custom_Field", 30, 50, $customField->getVar('custom_Field'));

$customOrder_text= new XoopsFormText(_oscmem_customfieldOrder, "custom_Order", 30, 50, $customfield->getVar('custom_Order'));
	
$customName_text= new XoopsFormText(_oscmem_customName, "custom_Name", 30, 50, $customfield->getVar('custom_Name'));

//$customTypeid_select = new XoopsFormSelect(_oscmem_customfield_Type,_oscmem_customfield_Type,_oscmem_customfield_Type,'z');
$customTypeid_select = new XoopsFormSelect(_oscmem_customfield_Type, "type_ID", $customfield->getVar('type_ID'));
//    $indeximage_select = new XoopsFormSelect('', 'indeximage', $fc->getVar('cat_image'));
 //   $indeximage_select->addOptionArray($graph_array);

$customTypeid_select->addOptionArray($option_array);

$id_hidden = new XoopsFormHidden("id",$customfield->getVar('custom_Field')); 
$form->addElement($customTypeid_select);

	
//$form->addElement($fieldname_text);
$form->addElement($customOrder_text);
$form->addElement($customName_text);
$form->setRequired($customOrder_text);
$form->setRequired($customName_text);
$form->setRequired($customTypeid_select);
$form->addElement($id_hidden);

xoops_cp_header();
$form->display();

xoops_cp_footer();

?>