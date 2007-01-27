<?php
// $Id: optionsmanager.php,v 1.1.1.1 2006/03/12 14:57:25 root Exp $
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
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
include("../../mainfile.php");
$xoopsOption['template_main'] = 'cs_index.html';
include(XOOPS_ROOT_PATH."/header.php");
//include("../../../include/cp_header.php");
include_once(XOOPS_ROOT_PATH . "/class/xoopsformloader.php");
include_once(XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/class/group.php");

include_once(XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/include/functions.php");

include_once(XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/class/person.php");

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
    exit("Access Denied");
}

exit("Broken Page");

//determine action
$op = '';
$confirm = '';

if (isset($_GET['op'])) $op = $_GET['op'];
if (isset($_POST['op'])) $op = $_POST['op'];
if (isset($_GET['id'])) $groupid=$_GET['id'];
if (isset($_POST['id'])) $groupid=$_POST['id'];
if (isset($_POST['action'])) $action=$_POST['action'];
if (isset($_GET['action'])) $action=$_GET['action'];

if (isset($_POST['removeid'])) $removeid=$_POST['removeid'];
if (isset($_GET['removeid'])) $removeid=$_GET['removeid'];


$myts = &MyTextSanitizer::getInstance();
$groupdetail_handler = &xoops_getmodulehandler('group', 'oscmembership');
    
    if(isset($groupid))
    {
	$group = $groupdetail_handler->get($groupid);  //only one     
	$members = $groupdetail_handler->getmembers($group);
    }
    else
    {
	$group = $groupdetail_handler->create();    	
	
    }  
    
	if(isset($_POST['groupname'])) $group->assignVar('group_Name',$_POST['groupname']);

	if(isset($_POST['grouptype']))
	$group->assignVar('group_type',$_POST['grouptype']);
	
	if(isset($_POST['group_RoleListID']))
	{
		$group->assignVar('group_RoleListID',$_POST['group_RoleListID']);
	}
	else
	{
		$group->assignVar('group_RoleListID',0);
	}
	
	if(isset($_POST['group_DefaultRole'])) $group->assignVar('group_DefaulRole',$_POST['group_DefaulRole']);
	else
	$group->assignVar('group_DefaultRole',0);
	
	if(isset($_POST['groupdescription'])) $group->assignVar('group_Description',$_POST['groupdescription']);

	$group->assignVar('group_hasSpecialProps',0);
	
	/*	
	if(isset($_POST['group_hasSpecialProps'])) $group->assignVar('group_hasSpecialProps',$_POST['group_hasSpecialProps']);
	else
	$group->assignVar('group_hasSpecialProps','false');
	*/
	
switch (true) 
{
	case($op=="save"):
		$groupdetail_handler->update($group);
		$message=_oscmem_UPDATESUCCESS;
		redirect_header("groupdetailform.php?id=" . $groupid, 3, $message);

		break;
	
	case($op=="create"):	
		$groupid = $groupdetail_handler->insert($group);	
		$message=_oscmem_CREATESUCCESS_group;
		redirect_header("groupdetailform.php?id=" . $groupid, 3, $message);
		break;
		
				
	case($op=="addmembersubmit"):
		redirect_header("personselect.php?type=group&id=" . $familyid,0,$message);		
		break;    

			case($op=="addmembersubmit"):
		redirect_header("personselect.php?type=group&id=" . $groupid,0,$message);		
		break;    

	case($op=="remove"):
		//save form changes first
		$groupdetail_handler->update($group);
		
		$persondetail_handler= &xoops_getmodulehandler('person', 'oscmembership');
		$removeid=$_POST['removeid'];
		$persondetail_handler->removefromGroup($removeid,$groupid);

		$message = _oscmem_REMOVEGROUPMEMBERSUCCESS;
		redirect_header("groupdetailform.php?id=" . $groupid,3,$message);
		break;    

				
}

$groupname_text = new XoopsFormText(_oscmem_groupname, "groupname", 30, 50, $group->getVar('group_Name'));

$groupdescript_textarea= new XoopsFormTextArea(_oscmem_groupdescription,"groupdescription",$group->getVar('group_Description'));

$grouptype_select = new XoopsFormSelect(_oscmem_grouptype,"grouptype");

$db = &Database::getInstance();

// Get Group Types for the drop-down
$sSQL = "SELECT * FROM " . $db->prefix("oscmembership_list") . " WHERE id= 3 ORDER BY optionsequence";

$groupTypes=$db->query($sSQL);

$container='';
while($row = $db->fetchArray($groupTypes)) 
{
	$grouptype_select->addOption($row['optionid'],$row['optionname']);
}

$grouptype_select->setValue($group->getVar('group_type'));

$id_hidden = new XoopsFormHidden("id",$group->getVar('id'));

$op_hidden = new XoopsFormHidden("op", "save");  //save operation
$submit_button = new XoopsFormButton("", "groupdetailsubmit", _osc_save, "submit");

$addMember_link=new XoopsFormLabel('',"<a href='personselect.php?type=group&id=" . $groupid . "'>" . _osc_addmember . "</a>");

$addmembers_button = new XoopsFormButton("", "addmembers", _oscmem_addmembers, "submit");

if(isset($action))
{

	if($action=="create")
	{
		$op_hidden = new XoopsFormHidden("op", "create");  //save operation
		$submit_button = new XoopsFormButton("", "groupdetailsubmit", _osc_create, "submit");
	}



}



$form = new XoopsThemeForm(_oscmem_groupdetail_TITLE, "groupdetailform", "groupdetailform.php", "post", true);

$db = &Database::getInstance();

$person=new Person();

$member_tray = new XoopsFormElementTray('', '&nbsp;');

$memberresult="<table><th>" . _oscmem_lastname . "</th><th>" . _oscmem_firstname . "</th><th>" . _oscmem_actions . "</th>";

$len_memberresult=strlen($memberresult);
$rowcount=0;

while($row = $db->fetchArray($members)) 
{
	$rowcount++;
	
	$person->assignVars($row);
	$memberresult .= "<tr><td>" . $person->getVar('lastname') . "</td>";
	$memberresult .= "<td>" . $person->getVar('firstname') . "</td>";
	$memberresult .= "<td>";
	$remove_button = new XoopsFormButton($person->getVar("id"), "removemember", _oscmem_remove_member, "button", $person->getVar("id"));
	$remove_button->setExtra("onclick=groupdetailform.op.value='remove';groupdetailform.removeid.value=" . $person->getVar("id") . ";groupdetailform.submit()");
	
	$editmember_button=new XoopsFormButton($person->getVar("id"), "editmember", _oscmem_edit_member, "button", $person->getVar("id"));
	$editmember_button->setExtra("onclick=groupdetailform.op.value='edit';groupdetailform.removeid.value=" . $person->getVar("id") . ";groupdetailform.submit()");
	
	$memberresult .= $remove_button->render();
	$memberresult .= "&nbsp;&nbsp;" . $editmember_button->render() . "</td></tr>";
	
}
if($rowcount==0)
{
	$memberresult .= "<tr><td>" . _oscmem_nomembers . "</td></tr></table>" ;
	}
else
{
	$memberresult .= "</table>";
}


//$form->addElement($addmembers_button);
$form->addElement($groupname_text);
$form->addElement($groupdescript_textarea);
$form->addElement($grouptype_select);

$addMember_button = new XoopsFormButton("", "addmembersubmit", _osc_addgroupmember, "submit");

$addMember_link=new XoopsFormLabel('',"<a href='personselect.php?type=group&id=" . $groupid . "'>" . _osc_addgroupmember . "</a>");
$form->addElement($addMember_link);


$member_label = new XoopsFormLabel(_oscmem_groupmember, $memberresult);
$form->addElement($member_label);


$form->addElement($op_hidden);

$removeid_hidden = new XoopsFormHidden("removeid",'');

$form->addElement($removeid_hidden);
$form->addElement($id_hidden);

//Upload stuff

$form->addElement($submit_button);
$form->setRequired($groupname_text);

//xoops_cp_header();
$form->display();

//xoops_cp_footer();
include(XOOPS_ROOT_PATH."/footer.php");

?>

