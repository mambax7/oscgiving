<?php
/************************************************************************* *
 *  filename    : DonationEditor.php
 *  last change : 2003-03-21
 *
 *  http://osc.sourceforge.net
 *
 *  This product is based upon work previously done by Infocentral (infocentral.org)
 *  on their PHP version Church Management Software that they discontinued
 *  and we have taken over.  We continue to improve and build upon this product
 *  in the direction of excellence.
 * 
 *  OpenSourceChurch (OSC) is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 * 
 *  Any changes to the software must be submitted back to the OpenSourceChurch project
 *  for review and possible inclusion.
 *
 *  Copyright 2003 Chris Gebhardt
 *  Copyright 2007 Steve McAtee
*********************************************************************/

include("../../mainfile.php");
$GLOBALS['xoopsOption']['template_main'] ="donationreview.html";

//redirect
if (!$xoopsUser)
{
    redirect_header(XOOPS_URL."/user.php", 3, _oscgiv_accessdenied);
}


include_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/include/functions.php';

include_once(XOOPS_ROOT_PATH . "/class/xoopsformloader.php");
include(XOOPS_ROOT_PATH."/header.php");
include(XOOPS_ROOT_PATH."/modules/" . $xoopsModule->getVar('dirname') . "/class/oscgivradio.php");

if(hasPerm("oscgiving_modify",$xoopsUser)) 
{
$ispermmodify=true;
}
if(!($ispermmodify==true) & !($xoopsUser->isAdmin($xoopsModule->mid())))
{
    redirect_header(XOOPS_URL , 3, _oscgiv_accessdenied);
}

include_once ICMS_ROOT_PATH."/kernel/icmspersistabletable.php";


$oscgiving_donation_handler = icms_getModuleHandler('donation');

$objectTable = new IcmsPersistableTable($oscgiving_donation_handler);
$objectTable->addColumn(new IcmsPersistableColumn('amount'));

//$objectTable->addIntroButton('adddonation', 'donation.php?op=mod', _AM_OSCTEST_DONATION_CREATE);
$icmsTpl->assign('oscgiving_donation_table', $objectTable->fetch());
//$icmsAdminTpl->display('db:osctest_admin_donation.html');

/*
$donations=array();
$donation_handler = &xoops_getmodulehandler('donation', 'oscgiving');

$person_handler = &xoops_getmodulehandler('person', 'oscmembership');

$donations=$donation_handler->getDonations();

$xoopsTpl->assign('donations',$donations);

*/


include(XOOPS_ROOT_PATH."/footer.php");

?>