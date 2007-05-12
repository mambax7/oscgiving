<?php
include("../../mainfile.php");
$GLOBALS['xoopsOption']['template_main'] ="donationenvelope.html";


include XOOPS_ROOT_PATH."/include/cp_functions.php";
include_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";
include_once XOOPS_ROOT_PATH . '/modules/oscmembership/class/person.php';
include_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/include/functions.php';


include(XOOPS_ROOT_PATH."/header.php");

//redirect
if (!$xoopsUser)
{
    redirect_header(XOOPS_URL."/user.php", 3, _oscgiv_accessdenied);
}

$user=$xoopsUser;
$perm="oscgiving_modify";
$userId = ($user) ? $user->getVar('uid') : 0;

if(hasPerm("oscgiving_modify",$xoopsUser)) 
{
$ispermmodify=true;
}
if(!($ispermmodify==true) & !($xoopsUser->isAdmin($xoopsModule->mid())))
{
    redirect_header(XOOPS_URL , 3, _oscgiv_accessdenied);
}

$giv_handler= &xoops_getmodulehandler('envelope', 'oscgiving');

$xoopsTpl->assign('title',_oscgiv_donationenvelopemgt); 
$xoopsTpl->assign('assignedenvelopes',$giv_handler->getcountofassignedenvelopes());
$xoopsTpl->assign('oscgiv_reassignenvelopeslink',_oscgiv_reassignenvelopeslink);
$xoopsTpl->assign('oscgiv_activeenvelopes',_oscgiv_activeenvelopes);
$xoopsTpl->assign('activeenvelopes',$giv_handler->getactiveenvelopecount());
$xoopsTpl->assign('oscgiv_highestenvelopenumber',_oscgiv_highestenvelopenumber);
$xoopsTpl->assign('highestenvelope',$giv_handler->gethighestenvelopenumber());
$xoopsTpl->assign('oscgiv_displaylistassignedenvelopes',_oscgiv_displaylistassignedenvelopes);
$xoopsTpl->assign('oscgiv_autoassignevelopeslink',_oscgiv_autoassignevelopeslink);

$xoopsTpl->assign('oscgiv_assignedenvelopes',_oscgiv_assignedenvelopes);

$xoopsTpl->assign('oscgiv_envelopassignconfirm',_oscgiv_envelopassignconfirm);


include(XOOPS_ROOT_PATH."/footer.php");

?>