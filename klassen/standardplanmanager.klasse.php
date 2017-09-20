<?php
class StandardPlanManager
{
	public $tmid;
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
	 		
		while($objekt = mysql_fetch_object($puffer, 'StandardPlanManager', array('tmid','tid', 'mid', 'von', 'bis')))
		{
			$schicht_feld[] = $objekt;
		}
		return $schicht_feld;
	}

	
	
	
	public static function update_zeiten($tid, $mid, $von, $bis) {
		#TODO: Primary key für standard_plan einführen, sonst könnte es hier zu mehreren updates kommen, obwohl nur eines gewollt ist
		$query = "UPDATE standard_plan SET von='" .  $von . "'" . " , bis='" . $bis . "'" . " WHERE tid='".$tid."' AND mid='".$mid."'";
		mysql_query($query);
	}

	public static function neue_schicht($tid, $mid, $von, $bis) {
		$query = "INSERT INTO standard_plan VALUES (NULL, '" .  $tid . "'" . ", '" . $mid  . "', '".$von."', '".$bis."')";
		mysql_query($query);

	}
	
	public static function entferne_tmid($tmid) {
		$query = 'DELETE FROM standard_plan WHERE tmid=' . $tmid;
		mysql_query($query);

	}
	
}
?>