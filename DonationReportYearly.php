<?php
/*******************************************************************************
 *
 *  filename    : Reports/DonationReportYearly.php
 *  last change : 2003-03-14
 *  description : Creates a donations report letter for a single person or
 *                a multi-page document of letters for all members.
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
 *  Copyright 2003  Michael Slemen, Chris Gebhardt
 *  Copyright 2007, Steve McAtee
 ******************************************************************************/

//require "../Include/Config.php";
//require "../Include/Functions.php";
//require "../Include/ReportConfig.php";
//require "../Include/ReportFunctions.php";
include_once "../../mainfile.php";

//redirect
if (!$xoopsUser)
{
    redirect_header(XOOPS_URL."/user.php", 3, _oscgiv_accessdenied);
}

require (XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/include/ReportConfig.php");

require (XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/include/html2fpdf/html2fpdf.php");
//include XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/include/fpdf151/fpdf.php";

//require XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/include/class_fpdf_labels.php";

require XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/include/functions.php";

if(hasPerm("oscgiving_modify",$xoopsUser)) 
{
$ispermmodify=true;
}
if(!($ispermmodify==true) & !($xoopsUser->isAdmin($xoopsModule->mid())))
{
    redirect_header(XOOPS_URL , 3, _oscgiv_accessdenied);
}

if(isset($_GET['year'])) $year=$_GET['year'];

if (file_exists(XOOPS_ROOT_PATH. "/modules/" . 	$xoopsModule->getVar('dirname') .  "/language/" . $xoopsConfig['language'] . "/modinfo.php")) 
{
    include XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/language/" . $xoopsConfig['language'] . "/modinfo.php";
}
elseif( file_exists(XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') ."/language/english/modinfo.php"))
{ include XOOPS_ROOT_PATH ."/modules/" . $xoopsModule->getVar('dirname') . "/language/english/modinfo.php";

}

// Avoid a bug in FPDF..
setlocale(LC_NUMERIC,'C');

$setting_handler = &xoops_getmodulehandler('givsetting', 'oscgiving');
$oscgivsetting = $setting_handler->getSetting();

$churchdetail_handler = &xoops_getmodulehandler('churchdetail', 'oscmembership');
	
$churchdetail=$churchdetail_handler->get();

// Load the FPDF library
//LoadLib_FPDF();

class PDF extends HTML2FPDF
{

	//Page header
	function Header()
	{
		global $sExemptionLetter_Letterhead;

		// if ($this->PageNo() == 1)
		// {
//			if (is_readable($sExemptionLetter_Letterhead))
//				$this->Image($sExemptionLetter_Letterhead,10,5,190);
//			$this->Ln(30);
		// }
		// else
		//	$this->Ln(10);
	}

	//Page footer
	function Footer()
	{
		global $churchdetail;
		$footer=$churchdetail->getVar('churchname') . " " . $churchdetail->getVar('address1') . " " . $churchdetail->getVar('city') . ", " . $churchdetail->getVar('state') . " " . $churchdetail->getVar('zip') . "  " . _oscgiv_phone . ":" . $churchdetail->getVar('phone') . "  " . _oscgiv_fax . ":" . $churchdetail->getVar('fax') . "  " . _oscgiv_website . ":" . $churchdetail->getVar('website');
		
		
		//global $sExemptionLetter_FooterLine;

		// if ($this->PageNo() == 1){
		// Position at 1.5 cm from bottom
		$this->SetY(-15);
		$this->SetFont('Arial','',9);
		$this->SetLineWidth(0.5);
		$this->Cell(0,10,$footer,'T',0,'C');

	}

	/*
	function WriteHTML($html)
	{
		//HTML parser
//		$html=str_replace("\n",' ',$html);
		$a=preg_split('/<(.*)>/U',$html,-1,PREG_SPLIT_DELIM_CAPTURE);
		foreach($a as $i=>$e)
		{
			if($i%2==0)
			{
				//Text
				if($this->HREF)
					$this->PutLink($this->HREF,$e);
				else
					$this->Write(5,$e);
			}
			else
			{
				//Tag
				if($e{0}=='/')
					$this->CloseTag(strtoupper(substr($e,1)));
				else
				{
					//Extract attributes
					$a2=explode(' ',$e);
					$tag=strtoupper(array_shift($a2));
					$attr=array();
					foreach($a2 as $v)
						if(ereg('^([^=]*)=["\']?([^"\']*)["\']?$',$v,$a3))
								$attr[strtoupper($a3[1])]=$a3[2];
					$this->OpenTag($tag,$attr);
				}
			}
		}
	}

	function OpenTag($tag,$attr)
	{
		//Opening tag
		if($tag=='B' or $tag=='I' or $tag=='U')
			$this->SetStyle($tag,true);
		if($tag=='A')
			$this->HREF=$attr['HREF'];
		if($tag=='BR')
			$this->Ln(5);
	}

	function CloseTag($tag)
	{
		//Closing tag
		if($tag=='B' or $tag=='I' or $tag=='U')
			$this->SetStyle($tag,false);
		if($tag=='A')
			$this->HREF='';
	}

	function SetStyle($tag,$enable)
	{
		//Modify style and select corresponding font
		$this->$tag+=($enable ? 1 : -1);
		$style='';
		foreach(array('B','I','U') as $s)
			if($this->$s>0)
					$style.=$s;
		$this->SetFont('',$style);
	}

	function PutLink($URL,$txt)
	{
		//Put a hyperlink
		$this->SetTextColor(0,0,255);
		$this->SetStyle('U',true);
		$this->Write(5,$txt,$URL);
		$this->SetStyle('U',false);
		$this->SetTextColor(0);
	}

	// Function to draw a nicely formatted, automatically sized table
	// By: Chris Gebhardt   Note: this is a preliminary attempt! (:
	function AutoTable($header,$data,$rowHeight,$widthScalar)
	{
		$this->SetDrawColor(128);
		$this->SetLineWidth(.3);

		$numColumns = count($header);

		// Generate column widths
		for($i=0; $i<$numColumns; $i++)
		{
			$comp = 1;
			$largest = strlen($header[$i]);

			foreach($data as $row)
			{
				$dataLength = strlen($row[$i]);
				if ($dataLength > $largest)
					$largest = $dataLength;
			}

			// Compensate larger strings by not scaling the column width as much
			// Ideally, we could divide row size based on an absolute table width.
			$comp = 1 - ($largest / 6 * 0.1);
			if ($comp < 0.6) $comp = 0.6;

			$width[] = round($largest * $comp * $widthScalar,0);
		}

		// Set the colors and font for the header row
		$this->SetFillColor(100);
		$this->SetTextColor(255);
		$this->SetFont('','B');

		// Generate the header row
		for($i=0;$i<$numColumns;$i++)
			$this->Cell($width[$i],$rowHeight,$header[$i],1,0,'C',1);
		$this->Ln();

		// Set the colors and font for the data rows
		$this->SetFillColor(230);
		$this->SetTextColor(0);
		$this->SetFont('');

		$fill = 0;

		// Generate the data rows
		foreach($data as $row)
		{
			for($i=0;$i<$numColumns;$i++)
			{
				$this->Cell($width[$i],$rowHeight,$row[$i],'LR',0,'C',$fill);
			}
			$this->Ln();
			$fill=!$fill;
		}

		// Draw the bottom of the table
		$this->Cell(array_sum($width),0,'','T');
	}
*/
	function CreatepersonReport($person, $lpasstext, $lyear)
	{
	
		$out=$lpasstext;
		
		$myts =& MyTextSanitizer::getInstance();

		$html=1;
		$smiley=1;
		$out=$myts->displayTarea($out,$html,$smiley,1);

		$this->SetFont('Times','',12);
		$this->AddPage(); // Create a new page
	
		$donorname=$person->getVar('lastname') . ", " . $person->getVar('firstname');

		$out=str_replace("[donorname]",$donorname,$out);
		
		$donoraddress=$person->getVar('');
		
		$out=str_replace("[donoraddress]",$donoraddress,$out);
		$out=str_replace("\r\n","<br>",$out);
		$donation_handler = &xoops_getmodulehandler('donation', 'oscgiving');
		//get donations
		$donations=$donation_handler->getDonationsbypersonbyyear($person->getVar('id'),$lyear);

		$tablehtml.="<table border=1><tr bgcolor=#C0C0C0><th>" . _oscgiv_donationdate . "</th><th>" . _oscgiv_amount . "</th></tr>";
		//Create table from donations
		$totalamount=0;
		foreach($donations as $donation)
		{
			$totalamount+=$donation->getVar('dna_Amount');
			$tablehtml.="<tr><td>" . $donation->getVar('don_Date') . "</td><td align=right>" . "\$" . number_format( $donation->getVar('dna_Amount'),2) . "</td></tr>";
		}

		$tablehtml.="</table>";
				
		$out=str_replace("[donationtable]",$tablehtml,$out);
		$out=str_replace("[totaldonationamount]","\$" . number_format($totalamount,2),$out);

//		echo $out;
				
//		$out="<b>this is a test</b><br><br>oh yea";
//		$out.="<br><table><tr><td>yes</td><td>no</td></tr></table>";
		$this->WriteHTML($out);
	
	
	}
	
/*	
	function CreateReport($iPersonID)
	{
		// The standard database connection..
		global $cnInfoCentral;

		// Global report strings and stuff..
		global $sExemptionLetter_Signature, $sExemptionLetter_Intro, $sExemptionLetter_Closing, $sExemptionLetter_Author;
		global $sStartDate, $sEndDate, $today, $iYear;

		// Get the donation total of this person
		$sSQL = "SELECT sum(dna_Amount) as Total
				FROM donations_don
				LEFT JOIN donationamounts_dna ON donations_don.don_ID = donationamounts_dna.dna_don_ID
				WHERE don_DonorID = $iPersonID AND don_Date >= '$sStartDate' AND don_Date <= '$sEndDate' AND donations_don.chu_Church_ID=" . $_SESSION['iChurchID'];
		$result = RunQuery($sSQL);
		$row = mysql_fetch_array($result);
		extract($row);

		$sSQL = "SELECT per_Title, per_FirstName, per_MiddleName, per_LastName, per_Suffix,
			per_Address1, per_Address2, per_City, per_State, per_Zip, per_Country,
			fam_Address1, fam_Address2, fam_City, fam_State, fam_Zip, fam_Country
			FROM person_per
			LEFT JOIN family_fam ON person_per.per_fam_ID = family_fam.fam_ID
			WHERE per_ID=" . $iPersonID . " AND person_per.chu_Church_ID=" . $_SESSION['iChurchID'];

		$rsPerson = RunQuery($sSQL);
		extract(mysql_fetch_array($rsPerson));

		$sCity = SelectWhichInfo($per_City, $fam_City, false);
		$sState = SelectWhichInfo($per_State, $fam_State, false);
		$sZip = SelectWhichInfo($per_Zip, $fam_Zip, false);
		$sCountry = SelectWhichInfo($per_Country, $fam_Country, false);

		SelectWhichAddress($sAddress1, $sAddress2, $per_Address1, $per_Address2, $fam_Address1, $fam_Address2, false);

		// Generate the page

		$this->SetFont('Times','',12);
		$this->AddPage(); // Create a new page
		$out = "<br><br>$today<br><br><br>";

		$out .= FormatFullName($per_Title, $per_FirstName, $per_MiddleName, $per_LastName, $per_Suffix, 0) . "<br>";
		if (strlen($sAddress1)) $out .= "$sAddress1<br>";
		if (strlen($sAddress2)) $out .= "$sAddress2<br>";
		$out .= $sCity . ", $sState $sZip";
		if ($sCountry != $sDefaultCountry) $out .= "<br>$sCountry";

		$out .= "<br><br>";

		$out .= "Dear $per_FirstName $per_LastName,<br><br>";
		$out .= $sExemptionLetter_Intro;

		$out .= "<br><br>" . gettext("Total sum of your donations, received during the year") . ' '. $iYear . ': ' . formatNumber($Total,'money') . "<br><br>";

		$out .= gettext("Below are the details for your donations:") . "<br><br>";

		$this->WriteHTML($out);
		$out = "" ;

		// Select all donations for person
		$sSQL = "SELECT sum(dna_Amount) as Amount, don_ID as ReceiptNum, don_PaymentType as Type,
			don_CheckNumber as CheckNum, date_format(don_Date, '%M %e, %Y') as Date
			FROM donations_don
			LEFT JOIN donationamounts_dna ON donations_don.don_ID = donationamounts_dna.dna_don_ID
			WHERE don_donorID = $iPersonID AND don_Date >= '$sStartDate' AND don_Date <= '$sEndDate' 
			AND donations_don.chu_Church_ID=" . $_SESSION['iChurchID'] . " 
			GROUP BY don_ID
			ORDER BY don_date ASC" ;

		$result2 = RunQuery($sSQL);

		$tableHeader=array(gettext('Receipt Number'), gettext('Date'), gettext('Amount'), gettext('Type'));

		while ($row = mysql_fetch_array($result2))
		{
			extract($row) ;

			switch ($Type) {
			case 1:
				$Type = gettext("Cash") ;
				break;
			case 2:
				$Type = gettext("Check");
				if ($CheckNum > 0) $Type .= " #" . $CheckNum;
				break;
			case 3:
				$Type = gettext("Credit") ;
				break;
			}

			$data[] = array($ReceiptNum, $Date, formatNumber($Amount,'money'), $Type);
		}

		$this->AutoTable($tableHeader,$data,5,4);

		$out .= '<br><br>' ;
		$out .= $sExemptionLetter_EndLine ;
		$this->WriteHTML($out);

		$this->WriteHtml($sExemptionLetter_Closing) ;

		$this->Image($sExemptionLetter_Signature,$this->GetX(),$this->GetY(),40);
		$this->Ln(10);

		$this->WriteHtml($sExemptionLetter_Author) ;
	}
*/
}


// Main
$sStartDate = "$year-1-1";
$sEndDate = "$year-12-31";
$today = date("F j, Y");

$pdf=new PDF('P','mm',$paperFormat);
$pdf->Open();
$pdf->AliasNbPages();

// If a person's ID is given, print a report just for them.
/*
if (strlen($iPersonID) > 0)
{
	$pdf->CreateReport($iPersonID);
}
// Otherwise, print report of all donors
else
{
	// Get the IDs of everybody who donated this year.
	$sSQL = "SELECT DISTINCT per_ID FROM person_per
			RIGHT JOIN donations_don ON don_DonorID = per_ID
			WHERE don_Date >= '$sStartDate' AND don_Date <= '$sEndDate' AND person_per.chu_Church_ID=" . $_SESSION['iChurchID'];
	$rsDonors = RunQuery($sSQL);

	while($aTemp = mysql_fetch_array($rsDonors))
	{
		$pdf->CreateReport($aTemp['per_ID']);
	}

}
*/

$person_handler = &xoops_getmodulehandler('person', 'oscmembership');

$person=$person_handler->create(False);

$setting_handler = &xoops_getmodulehandler('givsetting', 'oscgiving');
$oscgivsetting = $setting_handler->getSetting();


$letterbody=$oscgivsetting->getVar('letterendofyear');

//[date] tab
$letterbody=str_replace("[date]",strftime('%D'),$letterbody);
$letterbody=str_replace("[churchname]",$churchdetail->getVar('churchname'),$letterbody);
if(isset($year)) $letterbody=str_replace("[year]",$year,$letterbody);

$oscgivsetting->assignVar('letterendofyear',$letterbody);


$persons=array();

$donation_handler = &xoops_getmodulehandler('donation', 'oscgiving');

$persons=$donation_handler->getPersonswhoDonatebyYear($year);
foreach($persons as $person)
{
//	echo $person->getVar('lastname');
	$passtext=$oscgivsetting->getVar('letterendofyear');
	$pdf->CreatepersonReport($person,$passtext,$year);
}


if ($iPDFOutputType == 1)
	$pdf->Output("YearlyDonationReport-" . date("Ymd-Gis") . ".pdf", true);
else
	$pdf->Output();
?>
