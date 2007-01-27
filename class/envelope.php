<?php
// $Id: envelope.php,v 1.0 2006/12/29 root Exp $
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

class  Envelope extends XoopsObject {
    var $db;
    var $table;

    function Envelope()
    {
        $this->db = &Database::getInstance();
	$this->initVar('envelope',XOBJ_DTYPE_INT);
	$this->initVar('personid',XOBJ_DTYPE_INT);
    }

}    
    

class oscGivingEnvelopeHandler extends XoopsObjectHandler
{

    function &create($isNew = true)
    {
        $envelope = new Envelope();
        if ($isNew) {
            $envelope->setNew();
        }
        return $envelope;
    }

    function &getlistofunusedenvelopes()
    {
    	$aEnvelopeOptions=array();
    
    	$sSQL = "SELECT DISTINCT envelope AS iEnvelope FROM " . $this->db->prefix("oscmembership_person") . " WHERE envelope != 'NULL' ORDER BY envelope ASC";
	$result = $this->db->query($sSQL);
	$lastEnvelope = 0;
	$numOptions = 0;
	
	while ($aRow = $this->db->fetchArray($result))
	{
		$thisEnvelope = $aRow['iEnvelope'];
		for($iOption = $lastEnvelope + 1; $iOption < $thisEnvelope; $iOption++)
		{
			$aEnvelopeOptions['envelope'][$numOptions++] = $iOption;
		}
		$lastEnvelope = $thisEnvelope;
	}

	$return[0]=$aEnvelopeOptions;
	$return[1]=$numOptions;
	return $return;
    }

    function &assignEnvelopetoCart($xoopsuid)
    {
    
    	$person_handler = &xoops_getmodulehandler('person', 'oscmembership');
	$person=$person_handler->create(false);
	
	$unusedenv=$this->getlistofunusedenvelopes();
	$aEnvelopeOptions=$unusedenv[0];
	$numOptions=$unusedenv[1];
	
	$result=$person_handler->getCart($xoopsuid, true); //pull cartcontents with no envelope
	$rowcount=0;
	
	$lastEnvelope=$this->gethighestenvelopenumber();
	$lastEnvelope++;
	
	while($row = $this->db->fetchArray($result)) 
	{
		$rowcount++;
		$person->assignVars($row);
		
		// Use up any unassigned envelopes first.  Then, create new (higher) numbers.
		if ($numOptions > 0)
			$newEnvelope = $aEnvelopeOptions[$numOptions-- - 1];
		else
			$newEnvelope = ++$lastEnvelope;
			
		$person->assignVar('envelope',$newEnvelope);
		$person_handler->update($person);
	}
	
	return $rowcount;
	
    }

    function &reassignEnvelopeNumbers()
    {
    
	$person_handler = &xoops_getmodulehandler('person', 'oscmembership');
	
	$search=array();
	$sort="name";
	$search[0]="";
		
	$persons=$person_handler->search3($search,$sort,true);
	 
	
//	$sSQL = "SELECT per_ID FROM person_per WHERE per_Envelope != 'NULL' AND chu_Church_ID=" . $_SESSION['iChurchID'] . " ORDER BY per_LastName,per_Firstname ASC";
//	$result = RunQuery($sSQL);
	$newEnvelope = 1;
	foreach($persons as $person)
//	while ($aRow = mysql_fetch_array($result))
	{
	
		$person->assignVar('envelope',$newEnvelope);

		$person_handler->update($person);
		
		$newEnvelope++;
		
	}
	
	return $newEnvelope;
    }
    
    function &getcountofassignedenvelopes()
    {
	$sSQL = "SELECT DISTINCT count(1) as rowcount FROM " . $this->db->prefix("oscmembership_person") . " WHERE envelope != 'NULL' ";
	
	$rowcount=0;
		
	if ($result = $this->db->query($sSQL))	
	{
		$row = $this->db->fetchArray($result);
		$rowcount=$row['rowcount'];
	}
	
	return $rowcount;	//count
    
    }
    
    function &getactiveenvelopecount()
    {
    
    	$envelope=&$this->create(false);

	$sSQL = "SELECT DISTINCT don_Envelope as activecount FROM " . $envelope->db->prefix("oscgiving_donations") . " WHERE don_Envelope != 'NULL' AND year(don_Date) > year(now())-2" ;

	$rowcount=0;
		
	if ($result = $envelope->db->query($sSQL))	
	{
		$row = $envelope->db->fetchArray($result);
		$rowcount=$row['activecount'];
	}
	
	return $rowcount;	//count
    }

    function &gethighestenvelopenumber()
    {
    
	$sSQL = "SELECT MAX(envelope) AS iMaxEnvelopeID FROM " . $this->db->prefix("oscmembership_person");
	
	$rowcount=0;
		
	if ($result = $this->db->query($sSQL))	
	{
		$row = $this->db->fetchArray($result);
		$rowcount=$row['iMaxEnvelopeID'];
	}

	return $rowcount;	//count
    }
    
    function &getenvelopes()
    {
    	// Get the envelopes list
	$sSQL = "SELECT p.envelope, p.firstname, p.middlename, p.lastname, p.suffix, p.address1, p.address2, f.address1 as f_address1, f.address2 as f_address2 FROM " . $this->db->prefix("oscmembership_person") . " p LEFT JOIN " . $this->db->prefix("oscmembership_family") . " f ON p.famid = f.id
		WHERE p.envelope != 'NULL'
	ORDER BY p.envelope ASC";

    }

}

?>