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
 ******************************************************************************/

include_once "../../mainfile.php";

//verify permission
if ( !is_object($xoopsUser) || !is_object($xoopsModule))  {
    exit("Access Denied");
}

require (XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/include/ReportConfig.php");

require (XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/include/html2fpdf/html2fpdf.php");

require XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/include/functions.php";

if (file_exists(XOOPS_ROOT_PATH. "/modules" . 	$xoopsModule->getVar('dirname') .  "/language/" . $xoopsConfig['language'] . "/modinfo.php")) 
{
    include XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/language/" . $xoopsConfig['language'] . "/modinfo.php";
}
elseif( file_exists(XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') ."/language/english/modinfo.php"))
{ include XOOPS_ROOT_PATH ."/modules/" . $xoopsModule->getVar('dirname') . "/language/english/modinfo.php";

}


if(hasPerm("oscgiving_modify",$xoopsUser)) $ispermmodify=true;

if(!$ispermmodify | !$xoopsUser->isAdmin($xoopsModule->mid()))
{
	exit(_oscgiv_accessdenied);
}

//Set the page title
$sPageTitle = _oscgiv_donationenvelopemgt;

$action="";
if(isset($_POST['postaction'])) $action=$_POST['postaction'];

$envelope_handler = &xoops_getmodulehandler('envelope', 'oscgiving');
$envelopesaffected=0;

switch ($action) 
{
	case "reassign":
	$envelopesaffected=$envelope_handler->reassignEnvelopeNumbers();
	break;
	
	case "assign":
	$envelopesaffected=$envelope_handler->assignEnvelopetoCart($xoopsUser->uid());
	break;	
}

switch($action)
{
	case "reassign":
	
	case "assign":
	
	redirect_header(XOOPS_URL."/modules/" . $xoopsModule->getVar('dirname') . "/index.php", 3, _oscgiv_ENVELOPEASSIGNSUCCESS . "<br>" . $envelopesaffected . " " . _oscgiv_envelopesassigned);
	
	break;

}

?>
