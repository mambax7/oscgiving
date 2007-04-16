<?php


include("../../../mainfile.php");
include_once  '../../../class/xoopsformloader.php';
include_once '../../../class/template.php';
$xoopsTpl = new XoopsTpl();


$xoopsOption['template_main'] = 'fundlist.html';

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
    exit(_oscgiv_admin_access_denied);
}

//include_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/class/osclist.php';

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


//$osclist_handler = &xoops_getmodulehandler('osclist', 'oscmembership');

$fund_handler = &xoops_getmodulehandler('fund', 'oscgiving');

$funds=$fund_handler->getFundlist();

$xoopsTpl->assign('add_fund',_oscgiv_addfund);
$xoopsTpl->assign('title',_oscgiv_funds);
$xoopsTpl->assign('funds',$funds);

//    $user_info = array ('uid' => $xoopsUser->getVar('uid'));

$xoopsTpl->assign("oscgiv_fundname",_oscgiv_fundname);
$xoopsTpl->assign("oscgiv_edit",_oscgiv_edit);

$xoopsTpl->display( 'db:fundlist.html' );

xoops_cp_footer();

?>