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
	
	
	
	
	/* Unter Berücksichtigung aller Faktoren und Funktion, wie Urlaub, ÜST, Krankmeldungen, berechnet diese Funktion, ob der Standard Plan am gegebenen Termin problemlos klappt
		Hint: ja
	 */
	public static function funktioniert_problemlos($termin) {
		return true;
	}
	
	/* 
	* Beschreibt, ob der Standardplan an gegebenem Termin agewendet wird, oder ob auf schichtmitarbeiter als Sonderplan zurückgegriffen wird.
	*/
	public static function wird_angewendet($termin) {
		$schicht_feld = array();
		$puffer = mysql_query("SELECT * FROM schicht_mitarbeiter WHERE termin='".$termin."'");
		
		#Keine sonderschicht gefunden, also wird standard angewendet
		if ( mysql_num_rows($puffer) == 0 ) {
			return true;
		} else {
			return false;
		}
	}
	
}


?>