<?php
/*******************************************************************************
 *
 *  filename    : DonationEnvelopes.php
 *  last change : 2003-06-03
 *  description : Manages Donation Envelope assignments
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
 *  copyright   : Copyright 2003 Chris Gebhardt
 *  copyright   : Copyright 2006, Steve McAtee - Xoops Conversion
 ******************************************************************************/

include("../../mainfile.php");

//redirect
if (!$xoopsUser)
{
    redirect_header(XOOPS_URL."/user.php", 3, _AD_NORIGHT);
}


//verify permission
if ( !is_object($xoopsUser) || !is_object($xoopsModule) || !$xoopsUser->isAdmin($xoopsModule->mid()) ) {
    exit(_oscgiv_access_denied);
}

include(XOOPS_ROOT_PATH."/header.php");


//Include the function library
require XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/include/functions.php";

if(!hasPerm("oscgiving_view",$xoopsUser)) exit(_oscgiv_access_denied);

//require "Include/Config.php";
//require "Include/Functions.php";

$sort="";
$filter="";
if (isset($_POST['postaction'])) $smode = $_POST['postaction'];

$person_handler = &xoops_getmodulehandler('person', 'oscmembership');

$giv_handler= &xoops_getmodulehandler('envelope', 'oscgiving');
$affected=0;

switch ($smode)
{
	case "assign":
		$affected=$giv_handler->assignEnvelopetoCart($xoopsUser->getVar('uid'));
		
		if($affected==0)
		{
			$message=_oscgiv_NOONEINCART;
			redirect_header("index.php" , 3, $message);
		}
		else
		{
			$message=_oscgiv_ENVELOPEASSIGNSUCCESS . "<br><br>". _oscgiv_envelopesassigned . $affected;
			redirect_header("index.php" , 3, $message);
		}
		
	break;
	
	case "reassign":
		$affected=$giv_handler->reassignEnvelopeNumbers($xoopsUser->getVar('uid'));
		
		$affected=$affected-1;
		//redirect
		redirect_header(XOOPS_URL . "/modules/" . $xoopsModule->getVar('dirname') . "/index.php", 3, _oscgiv_envelopeassignment_success . "<br>" . _oscgiv_membersaffected . ":" . $affected);

	
}



?>