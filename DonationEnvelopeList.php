<?php
/*******************************************************************************
 *
 *  filename    : Reports/DonationEnvelopeList.php
 *  last change : 2003-06-03
 *  description : Creates a printable list of all envelope assignments
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
 *  Copyright 2003  Chris Gebhardt
 ******************************************************************************/

include_once "../../mainfile.php";




// this works well for letter size pages
$maxColumnLength = 36;
require (XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/include/ReportConfig.php");

include XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/include/fpdf151/fpdf.php";

require XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/include/class_fpdf_labels.php";

if (file_exists(XOOPS_ROOT_PATH. "/modules/" . 	$xoopsModule->getVar('dirname') .  "/language/" . $xoopsConfig['language'] . "/modinfo.php")) 
{
    include XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/language/" . $xoopsConfig['language'] . "/modinfo.php";
}
elseif( file_exists(XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') ."/language/english/modinfo.php"))
{ include XOOPS_ROOT_PATH ."/modules/" . $xoopsModule->getVar('dirname') . "/language/english/modinfo.php";

}

//redirect
if (!$xoopsUser)
{
    redirect_header(XOOPS_URL."/user.php", 3, _oscgiv_accessdenied);
}


require XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/include/functions.php";

if(hasPerm("oscgiving_modify",$xoopsUser)) 
{
$ispermmodify=true;
}
if(!($ispermmodify==true) & !($xoopsUser->isAdmin($xoopsModule->mid())))
{
    redirect_header(XOOPS_URL , 3, _oscgiv_accessdenied);
}


// Get the envelopes list
$person_handler = &xoops_getmodulehandler('person', 'oscmembership');
$searcharray=array();
$searcharray[0]='';
$sort="";
$persons = $person_handler->search3($searcharray, $sort, true );

/*
$sSQL = "SELECT per_Envelope, per_FirstName, per_MiddleName, per_LastName, per_Suffix, per_Address1, per_Address2, fam_Address1, fam_Address2
		FROM person_per	LEFT JOIN family_fam ON person_per.per_fam_ID = family_fam.fam_ID
		WHERE per_Envelope != 'NULL'
		AND person_per.chu_Church_ID=" . $_SESSION['iChurchID'] . " ORDER BY per_Envelope ASC";
$result = RunQuery($sSQL);
$aData = array();
while ($aRow = mysql_fetch_array($result))
{
	$per_FirstName = "";
	$per_MiddleName = "";
	$per_LastName = "";
	$per_Suffix = "";
	$per_Address1 = "";
	$per_Address2 = "";
	$fam_Address1 = "";
	$fam_Address2 = "";

	extract($aRow);
	SelectWhichAddress($sAddress1, $sAddress2, $per_Address1, $per_Address2, $fam_Address1, $fam_Address2, false);
	$aData[$count++] = array($per_Envelope, $per_FirstName, $per_MiddleName, $per_LastName, $per_Suffix, $sAddress1, $sAddress2);
}
*/

class PDF extends FPDF
{

function Header()
{
	$title = _oscgiv_donationenvelopelist;
    $this->SetFont('Arial','B',15);
    $w=$this->GetStringWidth($title)+6;

    $this->SetLineWidth(0.5);
    $this->Cell($w,9,$title,1,1,'C',0);
    $this->Ln(10);
}

function Footer()
{
    //Page footer
    $this->SetY(-15);
    $this->SetFont('Arial','I',8);
    $this->SetTextColor(128);
    $this->Cell(0,10,'Page '.$this->PageNo(),0,0,'C');
}

function EnvelopeTable($header,&$data,$startIndex,$size)
{
	global $maxColumnLength;;
	
	// Header
	$this->SetFont('Times','B',9);
	$this->Cell(15,6,'Envelope',1,0,'C');
	$this->Cell(40,6,'Name',1,0,'C');
	$this->Cell(40,6,'Address',1,0,'C');
	$this->Cell(5,6,'',0);
	$this->Cell(15,6,'Envelope',1,0,'C');
	$this->Cell(40,6,'Name',1,0,'C');
	$this->Cell(40,6,'Address',1,0,'C');
	$this->Ln();

	// Data
	$this->SetFont('Times','',8);

	if ($size > $maxColumnLength)	{
		$Column1Size = $maxColumnLength;
		$Column2Size = $size - $maxColumnLength;
	} else {
		$Column1Size = $size;
		$Column2Size = 0;
	}
	

	$person_handler = &xoops_getmodulehandler('person', 'oscmembership');
	$person=$person_handler->create(false);
		
	for($i=0; $i < count($data); $i++)
	{

		if ($Column1Size > 0) {
			$person=$person_handler->create(false);
			$row = $startIndex + $i;
			$person=$data[$row];
			
			$sName1 = FormatFullName($person->getVar('title'), $person->getVar('firstname'), $person->getVar('middlename'), $person->getVar('lastname'), $person->getVar('suffix'), 1);

			if (strlen($person->getVar('address2')) > 0)
				$sAddress1 = $person->getVar('address1') . " " . $person->getVar('address2');
			else
				$sAddress1 = $person->getVar('address1');
			
			$sEnvelope1 = $person->getVar('envelope');
			$Column1Size--;
		} else {
			$sName1 = "";
			$sAddress1 = "";
			$sEnvelope1 = "";
		}

		if ($Column2Size > 0) {
			$person=$person_handler->create(false);
			$row = $maxColumnLength + $i;
			if(isset($data[$row]))
			{
				$person=$data[$row];
				$sName2 = FormatFullName($person->getVar('title'), $person->getVar('firstname'), $person->getVar('middlename'),$person->getVar('lastname'), $person->getVar('suffix'), 1);
	
				if (strlen($person->getVar('address1')) > 0)
					$sAddress2 = $person->getVar('address1');
				else
					$sAddress2 = $person->getVar('address2');
	
				$sEnvelope2 = $person->getVar('envelope');
			}
			else
			{
				$sName2="";
				$sEnvelope2="";
				$sAddress2="";
			}
			$Column2Size--;
		} else {
			$sName2 = "";
			$sAddress2 = "";
			$sEnvelope2 = "";
		}

		$this->Cell(15,6,$sEnvelope1,1,0,'C');
		$this->Cell(40,6,$sName1,1);
		$this->Cell(40,6,$sAddress1,1);
		$this->Cell(5,6,'',0);
		$this->Cell(15,6,$sEnvelope2,1,0,'C');
		$this->Cell(40,6,$sName2,1);
		$this->Cell(40,6,$sAddress2,1);

		if($Column1Size==0 && $i<count($data)-2)
		{
			//paginate
			$this->AddPage();
			if (count($data) - $i > $maxColumnLength)	{
				$Column1Size = $maxColumnLength;
				$Column2Size = count($data) - $i - $maxColumnLength;
			} else {
				$Column1Size = count($data)- $i;
				$Column2Size = 0;
			}

			$i=$i+$maxColumnLength;

		}
		else $this->Ln();

	}
}

}

// Generate the PDF file
$pdf=new PDF('P','mm',$paperFormat);
$pdf->Open();
// $pdf->AliasNbPages();

$startIndex = 0;
$count = count($persons);

if($count>0)
//while ($count > 0)
{
	$maxSize = $maxColumnLength * 2;
	if ($count >= $maxSize)
		$size = $maxSize;
	else
		$size = $count;
	$count -= $size;

	$pdf->AddPage();
	$header="";
	$pdf->EnvelopeTable($header,$persons,$startIndex,$size);

	$startIndex += $size;
}
if ($iPDFOutputType == 1)
	$pdf->Output("EnvelopeList-" . date("Ymd-Gis") . ".pdf", true);
else
	$pdf->Output();
?>
