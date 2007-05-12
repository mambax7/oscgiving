<?php
include("../../mainfile.php");
$GLOBALS['xoopsOption']['template_main'] ="oscgivreports.html";

//redirect
if (!$xoopsUser)
{
    redirect_header(XOOPS_URL."/user.php", 3, _AD_NORIGHT);
}

/*
//verify permission
if ( !is_object($xoopsUser) || !is_object($xoopsModule)) {
    redirect_header(XOOPS_URL , 3, _oscgiv_accessdenied);
}
*/

include XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/include/functions.php";

if(hasPerm("oscgiving_modify",$xoopsUser)) 
{
$ispermmodify=true;
}
if(!($ispermmodify==true) & !($xoopsUser->isAdmin($xoopsModule->mid())))
{
    redirect_header(XOOPS_URL , 3, _oscgiv_accessdenied);
}


$donation_handler = &xoops_getmodulehandler('donation', 'oscgiving');

$years=$donation_handler->getDonationyears();
include(XOOPS_ROOT_PATH."/header.php");

$xoopsTpl->assign('title',_oscgiv_reporttitle); 
$xoopsTpl->assign('oscgiv_yearlydonationreport',_oscgiv_yearlydonationreport); 
$xoopsTpl->assign('oscgiv_donationreport',_oscgiv_donationreport);
$xoopsTpl->assign('years',$years);
/*
$xoopsTpl->assign('OSCMEM_csvexport',_oscmem_csvexport);
$xoopsTpl->assign('oscmem_csvimport',_oscmem_csvimport);
*/

include(XOOPS_ROOT_PATH."/footer.php");
?>