<?php
include("../../mainfile.php");
$GLOBALS['xoopsOption']['template_main'] ="oscgivreports.html";

//redirect
if (!$xoopsUser)
{
    redirect_header(XOOPS_URL."/user.php", 3, _AD_NORIGHT);
}

//verify permission
if ( !is_object($xoopsUser) || !is_object($xoopsModule)) {
    exit(_oscmem_access_denied);
}


include XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/include/functions.php";

if(hasPerm("oscgiving_modify",$xoopsUser)) $ispermmodify=true;

if(!$ispermmodify | !$xoopsUser->isAdmin($xoopsModule->mid()))
{
	exit(_oscgiv_accessdenied);
}


if(!hasPerm("oscmembership_view",$xoopsUser)) exit(_oscmem_access_denied);

include(XOOPS_ROOT_PATH."/header.php");

$xoopsTpl->assign('title',_oscgiv_reporttitle); 
$xoopsTpl->assign('oscgiv_donationreport',_oscmem_directoryreport); 
/*
$xoopsTpl->assign('OSCMEM_csvexport',_oscmem_csvexport);
$xoopsTpl->assign('oscmem_csvimport',_oscmem_csvimport);
*/

include(XOOPS_ROOT_PATH."/footer.php");
?>