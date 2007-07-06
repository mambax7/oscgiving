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

include_once XOOPS_ROOT_PATH . '/modules/oscgiving/include/jpgraph-1.13/src/jpgraph.php';

class  Xoopsgraph extends XoopsObject {
    var $db;
    var $table;

    function Graph()
    {
        $this->db = &Database::getInstance();
/*
        $this->table = $this->db->prefix("oscgiving_donations");
	$this->initVar('don_id',XOBJ_DTYPE_INT);
	$this->initVar('personid',XOBJ_DTYPE_INT);
*/
	
     }

}    
    

class oscGivingXoopsgraphHandler extends XoopsObjectHandler
{

    function &create($isNew = true)
    {
        $graph = new Xoopsgraph();
        if ($isNew) {
            $graph->setNew();
        }
        return $graph;
    }
    
    function &generateGraph($title, $totals,$sdate,$currencysymbol)
    {
    
	// Create the graph.
	$graph = new PieGraph(550,200);
	$graph->SetShadow();
	// Set A title for the plot
	$graph->title->Set($title . " " . $sdate);
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
	$p1->value->SetFormat($aLocaleInfo["currency_symbol"] . ' %d');
	$p1->value->Show();
	
	// Set font for legend
	$p1->value->SetFont(FF_FONT1,FS_NORMAL,12);
	$p1->SetLegends($funds);
	
	// Add the plots to the graph
	$graph->Add($p1);
	
	// Display the graph
	$graph->Stroke();
    }

}

?>