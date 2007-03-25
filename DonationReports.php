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


//verify permission
if ( !is_object($xoopsUser) || !is_object($xoopsModule) || !$xoopsUser->isAdmin($xoopsModule->mid()) ) {
    exit(_oscgiv_access_denied);
}


include_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/include/functions.php';

include_once(XOOPS_ROOT_PATH . "/class/xoopsformloader.php");

include(XOOPS_ROOT_PATH."/header.php");
/*
//Set the page title
$sPageTitle = gettext("Donation report");

$sDate = FilterInput($_GET['Date'],'char',10);

if (strlen($sDate) > 0)
{
	$sPageTitle .= " " . gettext("for") . " $sDate";
	require "Include/Header.php";

	$sSQL = "SELECT fun_Name as Fund, SUM(dna_Amount) as Total
		FROM donations_don
		LEFT JOIN donationamounts_dna ON donations_don.don_ID = donationamounts_dna.dna_don_ID
		LEFT JOIN donationfund_fun ON donationamounts_dna.dna_fun_ID = donationfund_fun.fun_ID
		WHERE don_Date = '$sDate' AND donations_don.chu_Church_ID=" . $_SESSION['iChurchID'] . "
		GROUP BY fun_ID ORDER BY fun_Name ASC";

	$rsFundAmounts = RunQuery($sSQL);
	if (mysql_num_rows($rsFundAmounts) > 0)
	{
		?>
		<br>
		<table align="center" cellpadding="5" cellspacing="0">
		<tr class="TableHeader">
			<td><?php echo gettext("Fund"); ?></td>
			<td><?php echo gettext("Total"); ?></td>
		</tr>
		<?php
		$iGrandTotal = 0;
		while ($aRow = mysql_fetch_array($rsFundAmounts))
		{
			$iGrandTotal += $aRow['Total'];
			echo "<tr><td>" . $aRow['Fund'] . "</td><td>" . formatNumber($aRow['Total'],'money') . "</td><tr>";
		}
		echo "<tr><td><b>" . gettext("Total Donations:") . "</b></td><td>" . formatNumber($iGrandTotal,'money') . "</td></tr>";
		echo "</table>";

		echo "<br><br><center>
		<img src=\"Graphs/funds1day.php?date=$sDate\"></center>";
		$iYear = substr($sDate, 0, 4);
		echo "<br><br><center><img src=\"Graphs/donByMonth.php?year=$iYear\"></center>";
	}

	$sSort = $_GET['sort'];
	$sOrder = $_GET['order'];

	switch($sSort)
	{
		case FirstName:
			$sSortField = "per_FirstName";
			break;
		case Amount:
			$sSortField = "Amount";
			break;
		case LastName:
		default:
			$sSortField = "per_LastName";
			break;
	}

	switch($sOrder)
	{
		case DESC:
			$sSortOrder = "DESC";
			$sOrderLink = "ASC";
			break;
		case ASC:
		default:
			$sSortOrder = "ASC";
			$sOrderLink = "DESC";
			break;
	}

	$sSQL = "SELECT per_FirstName as FirstName, per_LastName as LastName,
		don_ID as ReceiptNo, SUM(dna_Amount) as Amount, don_Date as date
		FROM donations_don
		LEFT JOIN person_per ON donations_don.don_DonorID = person_per.per_ID
		LEFT JOIN donationamounts_dna ON donations_don.don_ID = donationamounts_dna.dna_don_ID
		WHERE don_Date = '$sDate' AND donations_don.chu_Church_ID=" . $_SESSION['iChurchID'] . " GROUP BY don_ID ORDER BY $sSortField $sSortOrder";

	$rsDonations = RunQuery($sSQL);
	if (mysql_num_rows($rsDonations) > 0)
	{
		?>
		<br><br><hr><br>
		<table align="center" cellpadding="5" cellspacing="0">
			<tr class="TableHeader">
				<td><a href="<?php echo $_SERVER['PHP_SELF'] . "?Date=$sDate&sort=FirstName&order=$sOrderLink" ?>"><?php echo gettext("First Name"); ?></a></td>
				<td><a href="<?php echo $_SERVER['PHP_SELF'] . "?Date=$sDate&sort=LastName&order=$sOrderLink" ?>"><?php echo gettext("Last Name"); ?></a></td>
				<td><a href="<?php echo $_SERVER['PHP_SELF'] . "?Date=$sDate&sort=Amount&order=$sOrderLink" ?>"><?php echo gettext("Amount"); ?></a></td>
				<td><?php echo gettext("Receipt No."); ?></td>
			</tr>
		<?php
		while ($aRow = mysql_fetch_array($rsDonations))
		{
			$sReceiptLink = "<a href=\"DonationViewReceipt.php?Receipt=" . $aRow['ReceiptNo'] . "\" target=\"receipt\">" . $aRow['ReceiptNo'] . "</a>";

			echo "<tr>";
			echo "<td>" . $aRow['FirstName'] . "</td>";
			echo "<td>" . $aRow['LastName'] . "</td>";
			echo "<td>" . formatNumber($aRow['Amount'],'money') . "</td>";
			echo "<td>" . $sReceiptLink . "</td>";
			echo "</tr>";
		}
		echo "</table>";
	}

} else {
	require "Include/Header.php";
	$sSQL = "SELECT distinct don_date as Date FROM donations_don WHERE chu_Church_ID=" . $_SESSION['iChurchID'] . " ORDER BY don_date DESC";
	$rsDonationDates = RunQuery($sSQL);
*/

if(hasPerm("oscgiving_modify",$xoopsUser)) $ispermmodify=true;

if(!$ispermmodify | !$xoopsUser->isAdmin($xoopsModule->mid()))
{
	exit(_oscgiv_accessdenied);
}

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

/*
$xoopsTpl->assign('selection',$form->render());
$xoopsTpl->assign('fund_name',_oscgiv_fund);
$xoopsTpl->assign('fund_amount',_oscgiv_amount);
*/

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
	
	
//	LoadLib_JPGraph(pie,pie3d);
/*
	// Create the graph.
	$graph = new PieGraph(550,200);
	$graph->SetShadow();
	//_oscgiv_totalbyfundfor
	// Set A title for the plot
	$graph->title->Set( "X" . " $sdate ");
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
//	$p1->ExplodeSlice(1);
	
	// Use absolute values (type==1)
//	$p1->SetLabelType(PIE_VALUE_ABS);
	
	// Display the slice values
//	$p1->value->SetFormat($aLocaleInfo["currency_symbol"] . ' %d');
//	$p1->value->Show();

	// Set font for legend
	$p1->value->SetFont(FF_FONT1,FS_NORMAL,12);
	$p1->SetLegends($funds);
	
	// Add the plots to the graph
	$graph->Add($p1);
	
	// Display the graph
	$graph->Stroke();
*/
}

//	$iYear = substr($sDate, 0, 4);
	
//	echo "<br><br><center><img src=\"Graphs/donByMonth.php?year=$iYear\"></center>";
	
/*
	$graph_handler = &xoops_getmodulehandler('xoopsgraph', 'oscgiving');
	
	$graph_handler->generateGraph("",0,"1/1/07","");
*/	


/*
	<form name="getDate" method="get" action="<?php echo $_SERVER['PHP_SELF'] ?>">
	<table align="center" cellpadding="5" cellspacing="1" border="0">
		<tr>
			<td class="LabelColumn"><?php echo gettext("Date:"); ?></td>
			<td class="TextColumn">

				<select name="Date">
				<?php
					while ($aRow = mysql_fetch_array($rsDonationDates)) {
						echo "<option value=\"" . $aRow['Date'] . "\">" . $aRow['Date'] . "</option>";
					}
				?>
				</select>
			</td>
			<td valign="top" class="SmallText"><?php echo gettext("Choose date to calculate total in each fund for."); ?></td>
		</tr>
		<tr>
			<td colspan="3" align="center">
			<br>
			<input class="icButton" type="Submit" value="<?php echo gettext("Execute Query"); ?>" name="Submit">
			</td>
		</tr>
	</table>
	</form>

*/
include(XOOPS_ROOT_PATH."/footer.php");

?>
