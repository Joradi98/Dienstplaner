<?php
include_once("tag.klasse.php");

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
	
	public static function hole_alle_schichten_durch_ma_tid($mid, $tid) {
			$schicht_feld = array();
			$puffer = mysql_query("SELECT * FROM standard_plan WHERE tid='".$tid."' AND mid='".$mid."'");
		 		
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
	
	/*
	* Termin: str, Uhrzeit: DateTime
	*/
	private static function fachkraft_anwesend($termin, $uhrzeit) {
		
	

		if ( StandardPlanManager::wird_angewendet($termin) ) {
			$tag = Tag::tag_an_termin($termin);
			$query = 'SELECT mitarbeiter.mid, standard_plan.von, standard_plan.bis FROM standard_plan INNER JOIN mitarbeiter ON standard_plan.mid=mitarbeiter.mid WHERE tid='.$tag->tid. ' AND mitarbeiter.status=1;';
			$puffer = mysql_query($query);
		} else {
			$query = 'SELECT * FROM schicht_mitarbeiter WHERE termin="'.$termin.'"';
			$puffer = mysql_query($query);
		}
		
		while($object = mysql_fetch_assoc($puffer)) {
			$von = new DateTime( $object["von"] );
			$bis = new DateTime( $object["bis"] );
			
			if ($von <= $uhrzeit && $bis >= $uhrzeit) { return true; }
		}

		

		return false;
		
	}
	
	
	/* Unter Berücksichtigung aller Faktoren und Funktion, wie Urlaub, ÜST, Krankmeldungen, berechnet diese Funktion, ob der Plan dieses Tages (Standard oder Sonder) funktioniert.
		Termin: str
	 */
	public static function funktioniert_problemlos($termin) {
		$tag = Tag::tag_an_termin($termin);

		#Regel nummer eins: Zwischen 6:45 und 16:16 muss immer eine Fachkraft anwesend sein. Gilt nur Montags-Freitags
		if ($tag->tid >= 6) { return true; }
		
		$start = new DateTime("6:45");
		$ende = new DateTime("16:15");

		$i = clone $start;
		while ($i <= $ende) {
			
			if ( StandardPlanManager::fachkraft_anwesend($termin, $i) == false ) {
				return false;
			}
			
			$i->modify("+ 15 minutes");
		}

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
	
	/*
	*	Beschreibt die Probleme, die  am gegebenen Termin auftreten. 
	*	Return: array von strings
	*/
	public static function probleme_am_termin($termin) {
		
		if (StandardPlanManager::funktioniert_problemlos($termin) ){ return array(); }
		$probleme = array();


		#Regel nummer eins: Zwischen 6:45 und 16:16 muss immer eine Fachkraft anwesend sein. Gilt nur Montags-Freitags
		if ($tag->tid >= 6) { return true; }
		
		$start = new DateTime("6:45");
		$ende = new DateTime("16:15");

		$i = clone $start;
		while ($i <= $ende) {
			
			if ( StandardPlanManager::fachkraft_anwesend($termin, $i) == false ) {
				$probleme[] = 'Um '.$i->format("H:i").' ist keine Fachkraft vorhanden.';
			}
			
			$i->modify("+ 15 minutes");
		}







		return $probleme;
		
	}
	
	
	
}


?>