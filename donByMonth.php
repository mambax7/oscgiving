<?php
/*******************************************************************************
 *
 *  filename    : Graphs/donByMonth.php
 *  last change : 2003-03-20
 *  description : Display bar graph of donations by month for the past year.
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
 ******************************************************************************/

include_once "../../mainfile.php";

//redirect
if (!$xoopsUser)
{
    redirect_header(XOOPS_URL."/user.php", 3, _AD_NORIGHT);
}

include_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/include/functions.php';

if(hasPerm("oscgiving_modify",$xoopsUser)) 
{
$ispermmodify=true;
}
if(!($ispermmodify==true) & !($xoopsUser->isAdmin($xoopsModule->mid())))
{
    redirect_header(XOOPS_URL , 3, _oscgiv_accessdenied);
}

if(isset($_GET['year'])) $iyear=$_GET['year'];

if (file_exists(XOOPS_ROOT_PATH. "/modules/" . 	$xoopsModule->getVar('dirname') .  "/language/" . $xoopsConfig['language'] . "/modinfo.php")) 
{
    include XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/language/" . $xoopsConfig['language'] . "/modinfo.php";
}
elseif( file_exists(XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') ."/language/english/modinfo.php"))
{ include XOOPS_ROOT_PATH ."/modules/" . $xoopsModule->getVar('dirname') . "/language/english/modinfo.php";

}

require XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . "/include/jpgraph-1.13/src/jpgraph.php";

require XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . "/include/jpgraph-1.13/src/jpgraph_error.php";

require XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . "/include/jpgraph-1.13/src/jpgraph_gb2312.php";

require XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . "/include/jpgraph-1.13/src/jpgraph_bar.php";

$i = 0;
$donation_handler = &xoops_getmodulehandler('donation', 'oscgiving');

$donationbyyear=$donation_handler->getDonationbyYearMonth($iyear);

$i=0;
foreach($donationbyyear as $donationyear)
{
	$months[$i] = strftime ("%b", mktime (0, 0, 0, $donationyear['month'], 1, 1978));

	// $totals[$i] = formatNumber($Total,'integer') ;
	$totals[$i] = $donationyear['Total'];
	//$funds[$i] = $funds[$i]." ($"."$Total)" ;
	$i++ ;
}

// JPGraph seems to be fixed.. no longer needed
// setlocale(LC_ALL, 'C');
/*
function formatNumber_money($value)
{
	return formatNumber($value,'intmoney');
}
*/
// Include JPGraph library and the bar graph drawing module
//LoadLib_JPGraph(bar);

// Start Graphing ---------------------------->

// Create the graph and setup the basic parameters
$graph = new Graph(475,200,'auto');
$graph->img->SetMargin(90,30,40,40);
$graph->SetScale("textint");
$graph->title->SetColor("darkblue");
$graph->SetMarginColor('white');
$graph->SetShadow();

// Add some grace to the top so that the scale doesn't
// end exactly at the max value.
//$graph->yaxis->scale->SetGrace(5);

// Setup X-axis labels
$graph->xaxis->SetTickLabels($months);
$graph->xaxis->SetFont(FF_FONT1);
$graph->xaxis->SetColor('darkblue','black');

//$graph->yaxis->SetLabelFormatCallback('formatNumber_money');

// Setup "hidden" y-axis by given it the same color
// as the background
$graph->yaxis->SetColor('black','black');
$graph->ygrid->SetColor('white');

// Setup graph title ands fonts
$graph->title->Set(gettext("Summary of donations for") . " $iyear");
$graph->title->SetFont(FF_FONT1,FS_BOLD,16);
//$graph->subtitle->Set('(With "hidden" y-axis)');

// Create a bar pot
$bplot = new BarPlot($totals);

$bplot->SetFillColor('darkblue');
$bplot->SetColor('black');
$bplot->SetWidth(0.5);
$bplot->SetShadow('darkgray');

// Setup the values that are displayed on top of each bar
$bplot->value->Show();
// Must use TTF fonts if we want text at an arbitrary angle
$bplot->value->SetFont(FF_FONT1,FS_NORMAL,8);
$bplot->value->SetFormatCallback('formatNumber');
$bplot->value->SetFormat(' %d');
$bplot->value->SetFormat('%01.0f');
// Dark blue for positive values and darkred for negative values
$bplot->value->SetColor("darkblue","darkred");
$graph->Add($bplot);

// Finally stroke the graph
$graph->Stroke();
?>









