<?php
/*******************************************************************************
 *
 *  filename    : Graphs/funds1day.php
 *  last change : 2003-05-28
 *  description : Display pie chart of donation to funds breakdown for one day
 *
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
 *  Copyright 2003 Michael Slemen, Chris Gebhardt
 *  Copyright 2007 Steve McAtee
 ******************************************************************************/


/*
require "../Include/Config.php";
require "../Include/Functions.php";
require "../Include/ReportFunctions.php";

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

if(hasPerm("oscgiving_modify",$xoopsUser)) $ispermmodify=true;

if(!$ispermmodify | !$xoopsUser->isAdmin($xoopsModule->mid()))
{
	exit(_oscgiv_accessdenied);
}

if(isset($_GET['date'])) $sdate=$_GET['date'];

if (file_exists(XOOPS_ROOT_PATH. "/modules" . 	$xoopsModule->getVar('dirname') .  "/language/" . $xoopsConfig['language'] . "/modinfo.php")) 
{
    include XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/language/" . $xoopsConfig['language'] . "/modinfo.php";
}
elseif( file_exists(XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') ."/language/english/modinfo.php"))
{ include XOOPS_ROOT_PATH ."/modules/" . $xoopsModule->getVar('dirname') . "/language/english/modinfo.php";

}


require XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . "/include/jpgraph-1.13/src/jpgraph.php";

require XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . "/include/jpgraph-1.13/src/jpgraph_pie.php";

require XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . "/include/jpgraph-1.13/src/jpgraph_error.php";

require XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . "/include/jpgraph-1.13/src/jpgraph_gb2312.php";

require XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . "/include/jpgraph-1.13/src/jpgraph_pie3d.php";


// JPGraph seems to be fixed.. no longer needed
// setlocale(LC_ALL, 'C');

// Include JPGraph library and the pie chart drawing modules
//LoadLib_JPGraph(pie,pie3d);

$i = 0;
$fund_handler = &xoops_getmodulehandler('fund', 'oscgiving');
	
$funds=$fund_handler->getDonationsbydatebyfund($sdate);

foreach($funds as $fund)
{
	$grandtotal+=$fund->getVar("dna_Amount");
	$totals[$i]=$fund->getVar("dna_Amount");
	$fundnames[$i]=$fund->getVar("fund_Name");
	
	$i++;
}

// Start Graphing ---------------------------->

// Create the graph.
$graph = new PieGraph(550,200);
$graph->SetShadow();

// Set A title for the plot
$graph->title->Set(_oscgiv_totalbyfundfor . " $sDate");
$graph->title->SetFont(FF_FONT1,FS_BOLD,16);
$graph->title->SetColor("darkblue");
$graph->legend->Pos(0.02,0.15);

// Create the bar plot
$p1 = new PiePlot3d($totals) ;
$p1->SetTheme("sand");
$p1->SetCenter(0.285);
$p1->SetSize(85);

// Adjust projection angle
$p1->SetAngle(45);

// Adjsut angle for first slice
$p1->SetStartAngle(315);

// As a shortcut you can easily explode one numbered slice with
//$p1->ExplodeSlice(1);

// Use absolute values (type==1)
$p1->SetLabelType(PIE_VALUE_ABS);

// Display the slice values
$locale_info = localeconv();
$p1->value->SetFormat($locale_info["currency_symbol"] . ' %d');
$p1->value->Show();

// Set font for legend
$p1->value->SetFont(FF_FONT1,FS_NORMAL,12);
$p1->SetLegends($fundnames);

// Add the plots to the graph
$graph->Add($p1);

// Display the graph
$graph->Stroke();
?>
