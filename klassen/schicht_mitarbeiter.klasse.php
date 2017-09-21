<?php

include_once "tag.klasse.php";









 class Schicht_Mitarbeiter
 {
 	public $smid;
 	public $sid;
 	public $mid;
 	public $termin;
 	public $von;
	public $bis;
 	
/* Konstruktor
 	 */
 	public function Schicht_Mitarbeiter()
 	{
 		
 	}
 	
 	/* Schreib Schicht_Mitarbeiter
 	 * �bergabeparameter:	Schichtid
 	 * 						Mitarbeiterid
 	 * 						Termin
 	 */
 	public static function schreibe_schicht_mitarbeiter($sid, $mid, $termin, $von, $bis)
 	{
		#Null for auto increment
		$query = 'INSERT INTO schicht_mitarbeiter VALUES(NULL, "'.$sid.'", "'.$mid.'", "'.$termin.'", "'.$von.'", "'.$bis.'")';
 		mysql_query($query);
 	}
 	
 	/* L�scht alle Schicht_Mitarbeiter anhand der Schichtid
 	 * �bergabeparameter:	Schichtid
 	 */
 	public static function loesche_alle_schicht_mitarbeiter_durch_sid($sid)
 	{
 		mysql_query("DELETE FROM schicht_mitarbeiter WHERE sid='".$sid."'");
 	}
 	
 	/* L�scht alle Schicht_Mitarbeiter anhand der Mitarbeiterid
 	 * �bergabeparameter:	Mitarbeiterid
 	 */
 	public static function loesche_alle_schicht_mitarbeiter_durch_mid($mid)
 	{
 		mysql_query("DELETE FROM schicht_mitarbeiter WHERE mid='".$mid."'");
 	}
 	
 	/* L�scht den Schicht_Mitarbeiter anhand der Schichtid und dem Termin
 	 * �bergabeparameter:	Schichtid
 	 * 						Termin
 	 */
 	public static function loesche_schicht_mitarbeiter_durch_sid_termin($sid, $termin)
 	{
 		mysql_query("DELETE FROM schicht_mitarbeiter WHERE sid='".$sid."' AND termin='".$termin."'");
 	}
      
	/* L�scht den Schicht_Mitarbeiter anhand der Schichtid und dem Termin
 	 * �bergabeparameter:	Schichtid
 	 * 						Termin
 	 */
 	public static function loesche_schicht_mitarbeiter_durch_termin( $termin)
 	{
 		mysql_query("DELETE FROM schicht_mitarbeiter WHERE termin='".$termin."'");
 	}	
  
    public static function loesche_schicht_mitarbeiter_durch_smid($smid)
 	{
 		mysql_query("DELETE FROM schicht_mitarbeiter WHERE smid='".$smid."'");
 	}
 	
 	/* Holt alle Schit_Mitarbeiter anhand der Schichtid
 	 * �bergabeparameter:	Schichtid
 	 * R�ckgabewert:		Feld -> Schicht_mitarbeiter Objekt(e)
 	 */
 	public static function hole_alle_schicht_mitarbeiter_durch_sid($sid)
 	{
 		$schichten_mitarbeiter_feld = array();
 		$puffer = mysql_query("SELECT * FROM schicht_mitarbeiter WHERE sid='".$sid."'");
 		
 		while($mitarbeiter_schicht_objekt = mysql_fetch_object($puffer, 'Schicht_Mitarbeiter', array('smid', 'sid', 'mid', 'termin', 'von', 'bis')))
 		{
 			$schichten_mitarbeiter_feld[] = $mitarbeiter_schicht_objekt;
 		}
 		return $schichten_mitarbeiter_feld;
 	}
 	
 	/* Holt alle Schit_Mitarbeiter anhand der Schichtid und dem Termin
 	 * �bergabeparameter:	Schichtid
 	 * 						Termin
 	 * R�ckgabewert:		Feld -> Schicht_mitarbeiter Objekt(e)
 	 */
 	public static function hole_alle_schicht_mitarbeiter_durch_sid_termin($sid,$termin)
 	{
 		$schichten_mitarbeiter_feld = array();
 		$puffer = mysql_query("SELECT * FROM schicht_mitarbeiter WHERE sid='".$sid."' AND termin='".$termin."'");
 		
 		while($mitarbeiter_schicht_objekt = mysql_fetch_object($puffer, 'Schicht_Mitarbeiter', array('smid', 'sid', 'mid', 'termin', 'von', 'bis')))
 		{
 			$schichten_mitarbeiter_feld[] = $mitarbeiter_schicht_objekt;
 		}
 		return $schichten_mitarbeiter_feld;
 	}
      
	/* Holt alle Schit_Mitarbeiter anhand der Schichtid und dem Termin
	 	 * �bergabeparameter:	MitarbeiterID
	 	 * 						Termin
	 	 * R�ckgabewert:		Feld -> Schicht_mitarbeiter Objekt(e)
	 	 */
	 public static function hole_alle_schicht_mitarbeiter_durch_mid_termin($mid,$termin)
	 {
	 	$schichten_mitarbeiter_feld = array();
		$query = "SELECT * FROM schicht_mitarbeiter WHERE mid='".$mid."' AND termin='".$termin."'";
	 	$puffer = mysql_query($query);
	 		
	 	while($mitarbeiter_schicht_objekt = mysql_fetch_object($puffer, 'Schicht_Mitarbeiter', array('smid', 'sid', 'mid', 'termin', 'von', 'bis')))
	 	{
	 		$schichten_mitarbeiter_feld[] = $mitarbeiter_schicht_objekt;
	 	}
	 	return $schichten_mitarbeiter_feld;
	 }
	
 	/* Holt alle Schit_Mitarbeiter anhand des Termins
 	 * Übergabeparameter:	Termin
 	 * 						
 	 * Rückgabewert:		Feld -> Schicht_mitarbeiter Objekt(e)
 	 */
 	public static function hole_alle_schicht_mitarbeiter_durch_termin($termin)
 	{
	
 		$schichten_mitarbeiter_feld = array();
 		$puffer = mysql_query("SELECT * FROM schicht_mitarbeiter WHERE termin='".$termin."'");
 		
 		while($mitarbeiter_schicht_objekt = mysql_fetch_object($puffer, 'Schicht_Mitarbeiter', array('smid', 'sid', 'mid', 'termin', 'von', 'bis')))
 		{
 			$schichten_mitarbeiter_feld[] = $mitarbeiter_schicht_objekt;
 		}
 		return $schichten_mitarbeiter_feld;
 	}
 
    public static function hole_smid_durch_sid_termin_mid($sid,$termin,$mid)
 	{
 		$puffer = mysql_query("SELECT smid FROM schicht_mitarbeiter WHERE sid='".$sid."' AND termin='".$termin."' AND mid='".$mid."'");
 		return mysql_fetch_array($puffer);
 	}
 	
 	/* Holt den jeweilige schicht_ma Eintrag mit Mitarbeiteranzahl anhand der Schichtid und der Tagesid
 	 * �bergabeparameter:	Schichtid
 	 * 						Tagesid
 	 * R�ckgabewert:		Feld -> schicht_ma Objekt(e) mit Mitarbeiteranzahl
 	 */
 	public static function hole_mitarbeiter_anzahl_durch_id($sid, $tid)
 	{
 		$puffer = mysql_query("SELECT ma FROM schicht_ma WHERE sid='".$sid."' AND tid='".$tid."'");
 		return mysql_fetch_array($puffer);
 	}


	//Holt alle schicht_mitarbeiter Einträge für den gegebenen Mitarbeiter
	public static function hole_schicht_mitarbeiter_durch_mid($mid) 
	{
		$schichten_mitarbeiter_feld = array();
		$puffer = mysql_query("SELECT * FROM schicht_mitarbeiter WHERE mid='".$mid."'");
		while($mitarbeiter_schicht_objekt = mysql_fetch_object($puffer, 'Schicht_Mitarbeiter', array('smid', 'sid', 'mid', 'termin', 'von', 'bis')))
		{
		 	$schichten_mitarbeiter_feld[] = $mitarbeiter_schicht_objekt;
		}
		return $schichten_mitarbeiter_feld;
	}

	//Holt die Anzahl aller Shichten des gegebenen Mitarbeiters in der gegebenen Woche
	public static function anzahl_schichten_diese_woche($mid, $termin) {
		$verwalter = new Schicht_Mitarbeiter();
		#Berechne, in wievielen Schichten der MA diese Woch eschon eingesetzt ist
		$alle_schichten = $verwalter->hole_schicht_mitarbeiter_durch_mid($mid);
		$kalender = new Kalender();
		#Begrenzende Tage der Woche
		$montag = $kalender->wochenAnfang($termin);
		$sonntag = $kalender->wochenEnde($termin);
		
		$schicht_counter = 0;
		foreach($alle_schichten as $schicht) {
			#Schaue nur die Schichten innerhalb der Woche an
			if ($schicht->termin >= $montag && $schicht->termin <= $sonntag) {
				$schicht_counter += 1;
			}
		}
		return $schicht_counter;

	}


	public static function hole_einen($sid, $mid, $termin)
	 {
	 	$schichten_mitarbeiter_feld = array();
	 	$puffer = mysql_query("SELECT * FROM schicht_mitarbeiter WHERE sid='".$sid."' AND termin='".$termin."' AND mid='".$mid."'");
	 		
	 	while($mitarbeiter_schicht_objekt = mysql_fetch_object($puffer, 'Schicht_Mitarbeiter', array('smid', 'sid', 'mid', 'termin', 'von', 'bis')))
	 	{
	 			return $mitarbeiter_schicht_objekt;
	 	}
	 	return new Schicht_Mitarbeiter();
	 }

	public static function update_zeiten($mid, $sid, $termin, $von, $bis) {
		$query = "UPDATE schicht_mitarbeiter SET von='" .  $von . "'" . " , bis='" . $bis . "'" . " WHERE sid='".$sid."' AND termin='".$termin."' AND mid='".$mid."'";
		mysql_query($query);
	}
		
		
	/*
	Im Gegensatz zu brutto_stunden_am_termin liefert diese Funktion direkt einen String zurück. 
	*/
	public static function stunden_diese_woche($mid,$termin) {
		echo "ICH BIN DEPRECATED stunden_diese_woche";
		$verwalter = new Schicht_Mitarbeiter();
		#Berechne, in wievielen Schichten der MA diese Woch eschon eingesetzt ist
		$alle_schichten = $verwalter->hole_schicht_mitarbeiter_durch_mid($mid);
		$kalender = new Kalender();
		#Begrenzende Tage der Woche
		$montag = $kalender->wochenAnfang($termin);
		$sonntag = $kalender->wochenEnde($termin);
		$interval = new DateInterval("P0Y"); //0 years
		foreach($alle_schichten as $schicht) {
			#Schaue nur die Schichten innerhalb der Woche an
			if ($schicht->termin >= $montag && $schicht->termin <= $sonntag) {
				$ab = new DateTime($schicht->von);
				$bis = new DateTime($schicht->bis);
				
				$spanne = $ab->diff($bis);
				$interval = addDateIntervals($interval, $spanne);
			}
		}
		return $interval;
		

	}
	
	
	
	
	
	public static function stunden_diesen_monat($mid, $termin) {
		echo "ICH BIN DEPRECATED stunden_diesen_monat";

		$verwalter = new Schicht_Mitarbeiter();
		#Berechne, in wievielen Schichten der MA diese Woch eschon eingesetzt ist
		$alle_schichten = $verwalter->hole_schicht_mitarbeiter_durch_mid($mid);
		$kalender = new Kalender();

		$termin = new DateTime($termin);
		
		$interval = new DateInterval("P0Y");
		foreach($alle_schichten as $schicht) {
			#Schaue nur die Schichten innerhalb des Monats an.
			$schicht_termin = new DateTime($schicht->termin);
			if ($schicht_termin->format('Y-m') == $termin->format('Y-m')) {
				$ab = new DateTime($schicht->von);
				$bis = new DateTime($schicht->bis);
				
				$spanne = $ab->diff($bis);
				$interval = addDateIntervals($interval, $spanne);
			}
		}
		return $interval;

	}
	
	

 }









 ?>