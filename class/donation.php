<?php
// $Id: contribution.php,v 1.0 2007/2/5 root Exp $
// *  http://osc.sourceforge.net
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //

class  Donation extends XoopsObject {
    var $db;
    var $table;

    function Donation()
    {
        $this->db = &Database::getInstance();
        $this->table = $this->db->prefix("oscgiving_donationamounts");
	$this->initVar('don_id',XOBJ_DTYPE_INT);
	$this->initVar('personid',XOBJ_DTYPE_INT);
	$this->initVar('don_PaymentType',XOBJ_DTYPE_INT);
	$this->initVar('don_CheckNumber',XOBJ_DTYPE_INT);
	$this->initVar('don_Date',XOBJ_DTYPE_INT);
	$this->initVar('don_Envelope',XOBJ_DTYPE_INT);
	$this->initVar('dna_Amount',XOBJ_DTYPE_INT);
	$this->initVar('dna_fun_id',XOBJ_DTYPE_INT);
	
	$this->initVar('personlastname',XOBJ_DTYPE_TXTBOX);
	$this->initVar('personfirstname',XOBJ_DTYPE_TXTBOX);
	$this->initVar('displayaddress',XOBJ_DTYPE_TXTBOX);
	$this->initVar('searchvalue',XOBJ_DTYPE_TXTBOX);
	$this->initVar('iteration',XOBJ_DTYPE_TXTBOX);
	
     }

}    
    

class oscGivingDonationHandler extends XoopsObjectHandler
{

    function &create($isNew = true)
    {
        $donation = new Donation();
        if ($isNew) {
            $donation->setNew();
        }
        return $donation;
    }
    
    function &lookupDonator($lookupvalue)
    {
    

	$giv_family_handler = &xoops_getmodulehandler('family', 'oscmembership');
	$giv_person_handler = &xoops_getmodulehandler('person', 'oscmembership');
    	$person=$giv_person_handler->create(false);
    
    	//determine if we have a string or number
	if(is_numeric($lookupvalue))
	{

		$person=$giv_person_handler->getPersonbyenvelope($lookupvalue);
		if(($person->getVar('address1')==""))
		{
			//get family address
			if(($person->getVar('famid')!=""))
			{
				$family=$giv_family_handler->get($person->getVar('famid'));
				
				$person->assignVar('address1',$family->getVar('address1'));
				$person->assignVar('address2',$family->getVar('address2'));
				$person->assignVar('city',$family->getVar('city'));
				$person->assignVar('state',$family->getVar('state'));
				$person->assignVar('zip',$family->getVar('zip'));
			}
		}
	}
	else
	{
		//assume name 
		$searcharray=array();
		$searcharray[0]=$lookupvalue;
		$persons=$giv_person_handler->search3($searcharray,"",true);

		if(count($persons)>1)
		{
			$person=$giv_person_handler->create(false);
			$person->assignVar('lastname',_oscgiv_nonamefound);
		}
		else
		{
			$person=$persons[0];
		}
	}
	
	//Match against names
	
	//Match against envelopes    
    
	return $person;
    
    }
    
    function &submitDonation($donation)
    {
    	
    
    }
    
    function &getDonations($donation)
    {
    
    }


}

?>