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
$GLOBALS['xoopsOption']['template_main'] ="donationeditor.html";

//redirect
if (!$xoopsUser)
{
    redirect_header(XOOPS_URL."/user.php", 3, _AD_NORIGHT);
}


//verify permission
if ( !is_object($xoopsUser) || !is_object($xoopsModule) || !$xoopsUser->isAdmin($xoopsModule->mid()) ) {
    exit(_oscgiv_access_denied);
}

include_once(XOOPS_ROOT_PATH . "/class/xoopsformloader.php");

include(XOOPS_ROOT_PATH."/header.php");

if (isset($_GET['Batch']))  $batchMode = $_GET['Batch'];

if(isset($_POST['defaultdate']))
	$sdefaultdate=$_POST['defaultdate'];
else
	$sdefaultdate=Date("Y-m-d");

if (isset($_POST['DefaultFundID'])) $iDefaultFundID=$_POST['DefaultFundID'];
if (isset($_POST['DefaultPaymentType'])) $defaultpaymenttype=$_POST['DefaultPaymentType'];

$donations=array();
$donation_handler = &xoops_getmodulehandler('donation', 'oscgiving');

$smode="";

for($i=0;$i<=3;$i++)
{
	$donation=$donation_handler->create(false);
	$donation->assignVar('iteration',$i);
/*
$donation=$donation_handler->create(false);
$donation->assignVar('iteration',1);
$donations[1]=$donation;

$donation=$donation_handler->create(false);
$donation->assignVar('iteration',2);
$donations[2]=$donation;

$donation=$donation_handler->create(false);
$donation->assignVar('iteration',3);
$donations[3]=$donation;
*/


	//Pull post information
	if (isset($_POST['postaction']))
	{
		$smode=$_POST['postaction'];
		
		if(isset($_POST['donationdate' . $i]))
		$donation->assignVar('don_Date',$_POST['donationdate' . $i]);
		
		if(isset($_POST['fund' . $i])) $donation->assignVar('dna_fun_id',$_POST['fund' . $i]);

	
		if(isset($_POST['paymenttype' . $i]))
		$donation->assignVar('don_PaymentType',$_POST['paymenttype' . $i]);
	
		if(isset($_POST['donationamount' . $i]))
		$donation->assignVar('dna_Amount',$_POST['donationamount' . $i]);
	
		if(isset($_POST['donationenvorname' . $i]))
		{
			$donation->assignVar('searchvalue',$_POST['donationenvorname' . $i]);
		}
			
			
	//	if(isset($_POST['don_Envelope']))
	//	$donation[0]->assignVar('don_Envelope',$_POST['donationenvorname']);
	
		
	}
	
	$donations[$i]=$donation;

}


switch ($smode)
{
	case "lookup":
	
		$lookupval="";
		//iterate thru donations
		$i=0;
		foreach($donations as $donation )
		{
			//lookup donator

			if($donation->getVar('searchvalue')=="")
			{
				$donation->assignVar('displayaddress',"");
			}
			else
			{
				$lookupval=$donation_handler->lookupDonator($donation->getVar('searchvalue'));

				if(isset($lookupval) && $lookupval->getVar('lastname')!="")
				{
					if($lookupval->getVar('lastname')==_oscgiv_nonamefound)
					{
						$donation->assignVar('displayaddress',$lookupval->getVar('lastname'));	
					}
					else
					{
						$displayline=$lookupval->getvar('lastname');
						$displayline.= ", " . $lookupval->getVar('firstname');
						$displayline.= " " . $lookupval->getVar('address1');
						if($lookupval->getVar('address2')!="") $displayline.=" " . $lookupval->getVar('address2');
						
						$displayline.=" " . $lookupval->getVar('city') . ", " . $lookupval->getVar('state');
						
						$donation->assignVar('displayaddress',$displayline);
					}
				}
			}
			$donations[$i]=$donation;
		
			$i++;
		}
	
	break;
	
	case "applydefaults":
		$i=0;

		foreach($donations as $donation )
		{

			$donation->assignVar('don_Date',$sdefaultdate);
			$donation->assignVar('dna_fun_id',$iDefaultFundID);
			$donation->assignVar('don_PaymentType',$defaultpaymenttype);
			$donations[$i]=$donation;
		
			$i++;		
		}
	
	break;
	
	case "submit":
		foreach($donations as $donation)
		{
			$donation_handler->submitDonation($donation);
			
			//recreate donation
			$i=$donation->getVar('iteration');
			$donation=$donation_handler->create(false);
			$donation->assignVar('iteration',$i);
			//apply defaults
			$donation->assignVar('don_Date',$sdefaultdate);
			$donation->assignVar('dna_fun_id',$iDefaultFundID);
			$donation->assignVar('don_PaymentType',$defaultpaymenttype);

			$donations[$i]=$donation;

		}
	
}

$fund_handler = &xoops_getmodulehandler('fund', 'oscgiving');

$funds=$fund_handler->getFundlist();


$donation_dt=array();
$donor_txt=array();
$donor_amount=array();
$donor_lbl=array();

$defaultdate_dt= new XoopsFormTextDateSelect(_oscgiv_defaultdate,'defaultdate', 15, $sdefaultdate);

$oscgiv_sdefaultdate="";

for($i=0;$i++;$i<3)
{

	$hold=new XoopsFormTextDateSelect(_oscgiv_defaultdate,'donationdate' . $i, 15, $sdefaultdate);	
	
	$donation_dt[$i]=$hold->render();
/*
	$donor_txt[$i]= new XoopsFormText(_oscgiv_envelopenumorlastname, "donor" . $i, 30, 50, null);
	
	$donor_lbl[$i]=new XoopsFormLabel("","item found here");

	$donor_amount[$i]= new XoopsFormText("", "amount" . $i, 30, 50, null);
*/
}



$xoopsTpl->assign('donations',$donations);
$xoopsTpl->assign('donation_dt',$donation_dt);
$xoopsTpl->assign('donor_txt',$donor_txt);
$xoopsTpl->assign('donor_lbl',$donor_lbl);
$xoopsTpl->assign('donor_amount',$donor_amount);

$xoopsTpl->assign('title',_oscgiv_donationbatchentry_title);
$xoopsTpl->assign('personid',1);
$xoopsTpl->assign('donationid',1);
$xoopsTpl->assign('batchMode',$batchMode);
$xoopsTpl->assign('defaultdate_dt',$defaultdate_dt->render());
$xoopsTpl->assign('sdefaultdate',$sdefaultdate);
$xoopsTpl->assign('funds',$funds);

$xoopsTpl->assign('oscgiv_batchentrymode',_oscgiv_batchentrymode);
$xoopsTpl->assign('oscgiv_batchentrymode',_oscgiv_batchentrymode);
$xoopsTpl->assign('oscgiv_defaultdate',_oscgiv_defaultdate);
$xoopsTpl->assign('oscgiv_sdefaultdate',$oscgiv_sdefaultdate);
$xoopsTpl->assign('oscgiv_defaultfund',_oscgiv_defaultfund);
$xoopsTpl->assign('oscgive_none',_oscgive_none);
$xoopsTpl->assign('oscgiv_defaulttype',_oscgiv_defaulttype);
$xoopsTpl->assign('oscgiv_paymenttype',_oscgiv_paymenttype);
$xoopsTpl->assign('defaultpaymenttype',$defaultpaymenttype);
$xoopsTpl->assign('oscgiv_cash',_oscgiv_cash);
$xoopsTpl->assign('oscgiv_check',_oscgiv_check);
$xoopsTpl->assign('oscgiv_credit',_oscgiv_credit);

$xoopsTpl->assign('oscgiv_clear',_oscgiv_clear);
$xoopsTpl->assign('oscgiv_delete',_oscgiv_delete);

$xoopsTpl->assign('oscgiv_receiptnumber',_oscgiv_receiptnumber);

$xoopsTpl->assign('iDefaultFundID',$iDefaultFundID);

$xoopsTpl->assign('oscgiv_donationdate',_oscgiv_donationdate);

$xoopsTpl->assign('oscgiv_dateerror',_oscgiv_dateerror);

$xoopsTpl->assign('oscgiv_envelopenumorlastname',_oscgiv_envelopenumorlastname);

$xoopsTpl->assign('oscgiv_submitdonations',_oscgiv_submitdonations);
$xoopsTpl->assign('oscgiv_lookup',_oscgiv_lookup);
$xoopsTpl->assign('oscgiv_selecteddonor',_oscgiv_selecteddonor);
$xoopsTpl->assign('oscgiv_applydefaults',_oscgiv_applydefaults);
$xoopsTpl->assign('oscgiv_fund',_oscgiv_fund);
$xoopsTpl->assign('oscgiv_amount',_oscgiv_amount);


include(XOOPS_ROOT_PATH."/footer.php");

?>
