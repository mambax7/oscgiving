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

class PDF extends HTML2FPDF
{

	//Page header
	function Header()
	{
		global $sExemptionLetter_Letterhead;

	}

	//Page footer
	function Footer()
	{
		global $churchdetail;
		$footer=$churchdetail->getVar('churchname') . " " . $churchdetail->getVar('address1') . " " . $churchdetail->getVar('city') . ", " . $churchdetail->getVar('state') . " " . $churchdetail->getVar('zip') . "  " . _oscgiv_phone . ":" . $churchdetail->getVar('phone') . "  " . _oscgiv_fax . ":" . $churchdetail->getVar('fax') . "  " . _oscgiv_website . ":" . $churchdetail->getVar('website');
		
		
		// if ($this->PageNo() == 1){
		// Position at 1.5 cm from bottom
		$this->SetY(-15);
		$this->SetFont('Arial','',9);
		$this->SetLineWidth(0.5);
		$this->Cell(0,10,$footer,'T',0,'C');

	}

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

		$tablehtml="<table border=1><tr bgcolor=#C0C0C0><th>" . _oscgiv_donationdate . "</th><th>" . _oscgiv_amount . "</th></tr>";
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
				
		$this->WriteHTML($out);
	
	
	}
	
}


// Main
$sStartDate = "$year-1-1";
$sEndDate = "$year-12-31";
$today = date("F j, Y");

$pdf=new PDF('P','mm',$paperFormat);
$pdf->Open();
$pdf->AliasNbPages();


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


$pdf->Output("YearlyDonationReport-" . date("Ymd-Gis") . ".pdf",true);

?>
