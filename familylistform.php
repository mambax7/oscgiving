<?php
include("../../mainfile.php");
$GLOBALS['xoopsOption']['template_main'] ="familyview.html";

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

if(hasPerm("oscmembership_view",$xoopsUser)) $ispermview=true;
if(hasPerm("oscmembership_modify",$xoopsUser)) $ispermmodify=true;

if (isset($_POST['submit'])) $submit = $_POST['submit'];

include_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";
include_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/class/family.php';

$sort="";
$filter="";
if (isset($_GET['sort'])) $sort = $_GET['sort'];
if (isset($_POST['filter'])) $filter=$_POST['filter'];


include(XOOPS_ROOT_PATH."/header.php");

$family_handler = &xoops_getmodulehandler('family', 'oscmembership');
if(isset($filter))
{
	$searcharray[0]=$filter;
}
else $searcharray[0]='';

if(isset($submit))
{
	switch($submit)
	{
	case _oscmembership_addfamily:
		redirect_header("familydetailform.php?action=create", 2, _oscmem_addfamily_redirect);
		
		//do nothing
		break;
	}
}


$results = $family_handler->search($searcharray, $sort);
$xoopsTpl->assign("title",_oscmem_family_list);

$xoopsTpl->assign('oscmem_applyfilter',_oscmem_applyfilter);
$xoopsTpl->assign('oscmem_familyname',_oscmem_familyname);
$xoopsTpl->assign('oscmem_address',_oscmem_address);
$xoopsTpl->assign('oscmem_clearfilter',_oscmem_clearfilter);
$xoopsTpl->assign('oscmem_addmember',_oscmem_addmember);
$xoopsTpl->assign('oscmem_email',_oscmem_email);
$xoopsTpl->assign('is_perm_view',$ispermview);
$xoopsTpl->assign('is_perm_modify',$ispermmodify);
$xoopsTpl->assign('oscmem_view',_oscmem_view);
$xoopsTpl->assign('oscmem_edit',_oscmem_edit);
$xoopsTpl->assign('oscmembership_addfamily',_oscmembership_addfamily);

$xoopsTpl->assign('families',$results);

$xoopsTpl->assign('loopcount', $results[0]['totalloopcount']);


include(XOOPS_ROOT_PATH."/footer.php");

?>