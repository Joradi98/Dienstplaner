<?php
class StandardPlanManager
{
	public $tid;
	public $mid;
	public $von;
	public $bis;
	
	
	/* Konstrucktor */
	public function StandardPlanManager() {
		
	}
	
	public static function hole_alle_schichten_durch_tag($tid) {
		$schicht_feld = array();
		$puffer = mysql_query("SELECT * FROM standard_plan WHERE tid='".$tid."'");
	 		
		while($objekt = mysql_fetch_object($puffer, 'StandardPlanManager', array('tid', 'mid', 'von', 'bis')))
		{
			$schicht_feld[] = $objekt;
		}
		return $schicht_feld;
	}
	
	
	
	
}
?>