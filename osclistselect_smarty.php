<?php

include("../../mainfile.php");
include_once XOOPS_ROOT_PATH.'/header.php';

//include(XOOPS_ROOT_PATH."/header.php");
// language files

$language = $xoopsConfig['language'] ;

$xoopsOption['template_main'] = 'oscmembership_optionlist.html';

//redirect
if (!$xoopsUser)
{
    redirect_header(XOOPS_URL."/user.php", 3, _AD_NORIGHT);
}


//verify permission
if ( !is_object($xoopsUser) || !is_object($xoopsModule) || !$xoopsUser->isAdmin($xoopsModule->mid()) ) {
    exit(_oscmem_access_denied);
}

include XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/include/functions.php";

if(!hasPerm("oscmembership_view",$xoopsUser)) exit(_oscmem_access_denied);


include_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";
include_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/class/osclist.php';

//determine action
$op = '';

if (isset($_GET['op'])) $op = $_GET['op'];
if (isset($_POST['op'])) $op = $_POST['op'];
//echo $op;
switch (true) 
{
	case($op=="additemsubmit"):
		redirect_header("osclist.php?type=familyrole&action=create",0,$message);		
		break;    
}

$osclist_handler = &xoops_getmodulehandler('osclist', 'oscmembership');

if (isset($_GET['id'])) $id=$_GET['id'];
if (isset($_POST['id'])) $id=$_POST['id'];

$osclist = $osclist_handler->create();
$osclist->assignVar('id',$id);

$result = $osclist_handler->getitems($osclist);

//    $user_info = array ('uid' => $xoopsUser->getVar('uid'));


$xoopsTpl->assign("test","hi");
    
/*
$form = new XoopsThemeForm(_oscmem_osclist_TITLE, "osclistselectform", "osclistselect.php", "post", false);

$submit_button = new XoopsFormButton("", "osclistselectsubmit", _osc_select, "submit");

//echo "<h2 class=comTitle>" . _oscmem_personselect . "</h2>";
/*
$inner_table="<table class='outer'  >";
$inner_table = $inner_table . "<tr><th>&nbsp;</th><th>" . _oscmem_groupname . "</th>";
$inner_table = $inner_table . "<th>" . _oscdir_city . "," . _oscdir_state . "</th>";
$inner_table = $inner_table . "<th>" . _oscdir_email . "</th>";
$inner_table = $inner_table . "<th>&nbsp;</th>";
$inner_table = $inner_table . "</tr>";

*/
//$db = &Database::getInstance();

$osclist=new Osclist();
/*  
while($row = $db->fetchArray($result)) 
{
	$rowcount++;
	$osclist->assignVars($row);

	echo "<tr>";

	echo "<td><a href='admin/osclistdetailform.php?id=" . $osclist->getVar('id') . "'>" . _oscmem_edit . "</a></td>";
	
	echo "<td>" . $osclist->getVar('optionname') . "</td>";

	echo "</tr>";	
}
*/
/*
if(!isset($osclist_label))
{
	$osclist_label= new XoopsFormLabel('',_oscmem_noitems);
	$form->addElement($osclist_label);
}	

$osclist_label= new XoopsFormLabel('',"");
$form->addElement($osclist_label);

$add_link=new XoopsFormLabel("","<a href='osclistdetailform.php?id=" . $id . "&action=create'>" . _oscmem_addfamilyrole . "</a>");
$form->addElement($add_link);


//$form->addElement($topid_hidden);
//$form->addElement($table_label);
$form->display();
*/

include_once XOOPS_ROOT_PATH."/footer.php";


?>