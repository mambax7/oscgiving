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
    var $searchpersons;

    function Donation()
    {
        $this->db = &Database::getInstance();
        $this->table = $this->db->prefix("oscgiving_donations");
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
	$this->searchperson=array();
//	$this->initVar('searchpersons',XOBJ_DTYPE_TXTBOX);
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
	
	$persons=array();
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
		$persons[0]=$person;
	}
	else
	{
		//assume name 
		$searcharray=array();
		$searcharray[0]=$lookupvalue;
		$persons=$giv_person_handler->search3($searcharray,"",true);
	}
	
	return $persons;
    }
    
    function &submitDonation(&$donation)
    {
	$sql = "INSERT into " . $donation->table
		. "(don_DonorID, don_PaymentType, don_CheckNumber,don_Date,don_Envelope)";
	
	$sql .= "values(" . $donation->getVar("personid")
	. "," . 
	$donation->getVar('don_PaymentType')
	. ",";
	if(is_numeric($donation->getVar('don_CheckNumber')))
		$sql .= $donation->getVar('don_CheckNumber');
	else 
		$sql.="0";
		
	$sql .=  "," .
	$this->db->quoteString($donation->getVar('don_Date'))
	. ",0);";

	
	if (!$result = $this->db->query($sql)) 
	{
		return false;
	}
	else
	{
		$donationid=$this->db->getInsertId();
	}
	
	$sql = "  insert into " . $this->db->prefix("oscgiving_donationamounts");
	$sql .= " (don_id,dna_Amount,dna_fun_ID) values(" . $donationid . ",";
	if(is_numeric($donation->getVar('dna_Amount')))
	{
		$sql .= $donation->getVar('dna_Amount') . "," ;
	}
	else
	{
		$sql .= "0,";
	} 
	$sql .= $donation->getVar('dna_fun_id') . ")";

	if (!$result = $this->db->query($sql)) 
	{
		$sql="delete from " . $donation->table . "where don_id=" . $donationid;
		!$result = $this->db->query($sql);
		return false;
	}
	else
	{
		
		return $donationid;
	}
	    
    }
    
    function &getPersonswhoDonatebyYear($year)
    {
    
	$giv_person_handler = &xoops_getmodulehandler('person', 'oscmembership');
    	$person=$giv_person_handler->create(false);
	
	$persons=array();

	$sql="select distinct p.* from " . $this->db->prefix("oscmembership_person") . " p, " . $this->db->prefix("oscgiving_donations") . " d where id= don_DonorID and year(d.don_Date)=" . $year;
	
	$result=$this->db->query($sql);
	
	$i=0;	
	while ($row = $this->db->fetchArray($result))
	{
	    	$person=$giv_person_handler->create(false);
		$person->assignVars($row);
		$persons[$i]=$person;
		$i++;
	}
    
    	return $persons;
    
    }

    function &getDonationsbypersonbyyear($personid, $lyear)
    {
    
    	$donations=array();
    	$donation=$this->create(False);
   
	$sql="select d.*, da.dna_Amount, da.dna_fun_ID from " . $this->db->prefix("oscgiving_donations") . " d join " . $this->db->prefix("oscgiving_donationamounts") . " da
on d.don_id = da.don_id
where d.don_DonorID= " . $personid . "
and year(d.don_Date)=" . $lyear;

	$result=$this->db->query($sql);
	
	$i=0;	
	while ($row = $this->db->fetchArray($result))
	{
	    	$donation=&$this->create(False);
		$donation->assignVars($row);
		$donations[$i]=$donation;
		$i++;
	}
	
	return $donations;
    }

    /* Returns in a collection the individual years where donations have been received */
    function &getDonationyears()
    {
    
    	$years=array();
    	$donation=$this->create(False);

	$sql="select year(don_Date)  from " . $donation->table . " group by year(don_Date) order by year(don_Date) desc";
	
	$result=$this->db->query($sql);
	
	$i=0;
	while ($row=mysql_fetch_array($result))
	{
		$year=$row[0];
		$years[$i]=$year;
		$i++;
	}
    	return $years;
    
    }
    
    function &getDonationdates()
    {
    	$dates=array();
    	$donation=$this->create(False);

	$sql="select don_Date  from " . $donation->table . " group by don_Date order by don_Date desc";
	
	$result=$this->db->query($sql);
	$i=0;
	while ($row=mysql_fetch_array($result))
	{
		$donationdate=$row[0];
		$dates[$i]=$donationdate;
		$i++;
	}
    	return $dates;
    
    }
    
    function &getDonationsbydate($thisdate)
    {
	$sql="select d.*, da.dna_Amount, da.dna_fun_ID from " . $this->db->prefix("oscgiving_donations") . " d join " . $this->db->prefix("oscgiving_donationamounts") . " da
on d.don_id = da.don_id
where d.don_Date='" . $thisdate . "'";

    	$donations=array();
    	$donation=$this->create(False);

	$result=$this->db->query($sql);
	$i=0;	
	while ($row = $this->db->fetchArray($result))
	{
	    	$donation=&$this->create(False);
		$donation->assignVars($row);
		$donations[$i]=$donation;
		$i++;
	}

	return $donations;
    }
    
    function &getDonationbyYearMonth($year)
    {

	$sql= "SELECT date_format(don_Date, '%m') as month, sum(dna_Amount) as Total, date_format(don_Date, '%Y') as year, date_format(don_Date, '%b %Y') as monthyear FROM " . $this->db->prefix("oscgiving_donations") . " d LEFT JOIN " . $this->db->prefix("oscgiving_donationamounts") . " da  ON d.don_id = da.don_id GROUP BY monthyear HAVING year = '" . $year . "'ORDER BY month ASC"  ;

	$donationyear=array();
	$result=$this->db->query($sql);
	$i=0;	
	while ($row = $this->db->fetchArray($result))
	{
		$donationyear[$i]['month']=$row['month'];
		$donationyear[$i]['Total']=$row['Total'];
		$donationyear[$i]['year']=$row['year'];
		$donationyear[$i]['monthyear']=$row['monthyear'];
		$i++;
	}	   
	
	return $donationyear; 
    
    }

    function &getDonations()
    {
	$sql="select d.*, da.dna_Amount, da.dna_fun_ID from " . $this->db->prefix("oscgiving_donations") . " d join " . $this->db->prefix("oscgiving_donationamounts") . " da
on d.don_id = da.don_id ";
//where d.don_Date='" . $thisdate . "'";

    	$donations=array();
    	$donation=$this->create(False);

	$result=$this->db->query($sql);
	$i=0;	
	while ($row = $this->db->fetchArray($result))
	{
	    	$donation=&$this->create(False);
		$donation->assignVars($row);
		$donations[$i]=$donation;
		$i++;
	}

	return $donations;
    }



}

?>