<?php
/*******************************************************************************
 *
 *  filename    : DonationReports.php
 *  last change : 2003-05-28
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
 *  Copyright 2002,2003 Michael Slemen, Chris Gebhardt
 *  Copyright 2007, Steve McAtee
 ******************************************************************************/

//Include the function library
/*
require "Include/Config.php";
require "Include/Functions.php";

if (!$_SESSION['bFinance'])
{
	Redirect("Menu.php");
	exit;
}
*/
include_once "../../mainfile.php";

//redirect
if (!$xoopsUser)
{
    redirect_header(XOOPS_URL."/user.php", 3, _AD_NORIGHT);
}


include_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/include/functions.php';

include_once(XOOPS_ROOT_PATH . "/class/xoopsformloader.php");

include(XOOPS_ROOT_PATH."/header.php");


if(hasPerm("oscgiving_modify",$xoopsUser)) 
{
$ispermmodify=true;
}
if(!($ispermmodify==true) & !($xoopsUser->isAdmin($xoopsModule->mid())))
{
    redirect_header(XOOPS_URL , 3, _oscgiv_accessdenied);
}

$sdate="";
if(isset($_POST['dateid'])) $sdate=$_POST['dateid'];

$form = new XoopsThemeForm(_oscgiv_donationreports_TITLE, "donationreport", "DonationReports.php", "post", true);

$submit_button = new XoopsFormButton("", "submit", _oscgiv_submit, "submit");

$date_select = new XoopsFormSelect(_oscgiv_donationdates, "dateid",$sdate);

$donation_handler = &xoops_getmodulehandler('donation', 'oscgiving');
$fund_handler = &xoops_getmodulehandler('fund', 'oscgiving');

$donationdates=$donation_handler->getDonationdates();

$op_hidden = new XoopsFormHidden("op", "create");  //save operation

$op="";
if (isset($_POST['op'])) $op=$_POST['op'];

$option_array=array();
$i=0;
foreach($donationdates as $donationdate)
{
	$option_array[$donationdate]=$donationdate;
}

$date_select->addOptionArray($option_array);

$form->addElement($date_select);
$form->addElement($submit_button);
$form->addElement($op_hidden);
echo $form->render();


//$form->display();

if($op=="create")
{
	$fund=$fund_handler->create(False);
	
	$funds=$fund_handler->getDonationsbydatebyfund($sdate);
	
	$idate=strtotime($sdate);
	
	echo "<br>";
	echo "<table align=center cellpadding=5 cellspacing=0>";
	echo "<tr >";
	echo "<th>" . _oscgiv_fund . "</th>";
	echo "<th>" . _oscgiv_total . "</th>";
	echo "</tr>";

	$i=0;	
	$grandtotal=0;
	foreach($funds as $fund)
	{
		$grandtotal+=$fund->getVar("dna_Amount");
		$totals[$i]=$fund->getVar("dna_Amount");
		$fundnames[$i]=$fund->getVar("fund_Name");
		
		echo "<tr>";
		echo "<td>" . $fund->getVar("fund_Name") . "</td>";
		echo "<td>" . number_format($fund->getVar("dna_Amount"),2). "</td>";
		echo "</tr>";
		$i++;
	
	}
	echo "<tr><td colspan=2><hr></td></tr>";
	echo "<tr><td>" . _oscgiv_grandtotal . "</td><td>" . number_format($grandtotal,2) . "</td></tr>";
	
	echo "</table>";

	echo "<br><br><center>
	<img src=\"" . XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/funds1day.php?date=$sdate\"></center>";
	$iYear = date("Y",$idate);
	
	echo "<br><br><center><img src=\"" . XOOPS_URL . "/modules/". $xoopsModule->dirname() . "/donByMonth.php?year=$iYear\"></center>";

}
	
include(XOOPS_ROOT_PATH."/footer.php");

?>
