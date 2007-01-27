<?php


include("../../../mainfile.php");
include_once  '../../../class/xoopsformloader.php';
include_once '../../../class/template.php';
$xoopsTpl = new XoopsTpl();


$xoopsOption['template_main'] = 'oscmembership_optionlist.html';

include '../../../include/cp_header.php';
include '../../../class/pagenav.php';

include_once '../../../class/xoopsform/grouppermform.php';

xoops_cp_header();

//$xTheme->loadModuleAdminMenu(4);

$module_id = $xoopsModule->getVar('mid');

//redirect
if (!$xoopsUser)
{
    redirect_header(XOOPS_URL."/user.php", 3, _AD_NORIGHT);
}


//verify permission
if ( !is_object($xoopsUser) || !is_object($xoopsModule) || !$xoopsUser->isAdmin($xoopsModule->mid()) ) {
    exit(_oscmem_admin_access_denied);
}

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

switch ($id)
{
case 2: //family roles
	$xoopsTpl->assign('option_class',_oscmem_addfamilyrole);
	$xoopsTpl->assign('title',_oscmem_osclist_TITLE_familyroles);
	
	break;

case 1: //member classification
	$xoopsTpl->assign("option_class",_oscmem_addmemberclassification );
	$xoopsTpl->assign("title",_oscmem_osclist_TITLE_memberclassifications);
	
	break;

case 3: //member classification
	$xoopsTpl->assign("option_class",_oscmem_addgrouptype );
	$xoopsTpl->assign("title",_oscmem_osclist_TITLE_grouptypes);
	
	break;
}


$optionItems = $osclist_handler->getitems($osclist);

//    $user_info = array ('uid' => $xoopsUser->getVar('uid'));

$xoopsTpl->assign("osc_optionsort",_oscmem_option_sort);
$xoopsTpl->assign("osc_optionname",_oscmem_familyrole_optionname);
$xoopsTpl->assign("optionitems",$optionItems);
$xoopsTpl->assign("id",$id);
$xoopsTpl->assign("oscmem_edit",_oscmem_edit);

$osclist=new Osclist();
$xoopsTpl->display( 'db:oscmembership_optionlist.html' );

xoops_cp_footer();

?>