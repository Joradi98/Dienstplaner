<?php

#Helper functinos
function addDateIntervals() {
	$reference = new DateTimeImmutable;
	$endTime = clone $reference;

	foreach (func_get_args() as $dateInterval) {
	   	$endTime = $endTime->add($dateInterval);
	}
	
	return $reference->diff($endTime);	
}








 class Schicht_Mitarbeiter
 {
 	public $smid;
 	public $sid;
 	public $mid;
 	public $termin;
 	
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
 	public function schreibe_schicht_mitarbeiter($sid, $mid, $termin)
 	{
		#Null forauto increment
 		mysql_query('INSERT INTO schicht_mitarbeiter VALUES(NULL, "'.$sid.'", "'.$mid.'", "'.$termin.'")');
 	}
 	
 	/* L�scht alle Schicht_Mitarbeiter anhand der Schichtid
 	 * �bergabeparameter:	Schichtid
 	 */
 	public function loesche_alle_schicht_mitarbeiter_durch_sid($sid)
 	{
 		mysql_query("DELETE FROM schicht_mitarbeiter WHERE sid='".$sid."'");
 	}
 	
 	/* L�scht alle Schicht_Mitarbeiter anhand der Mitarbeiterid
 	 * �bergabeparameter:	Mitarbeiterid
 	 */
 	public function loesche_alle_schicht_mitarbeiter_durch_mid($mid)
 	{
 		mysql_query("DELETE FROM schicht_mitarbeiter WHERE mid='".$mid."'");
 	}
 	
 	/* L�scht den Schicht_Mitarbeiter anhand der Schichtid und dem Termin
 	 * �bergabeparameter:	Schichtid
 	 * 						Termin
 	 */
 	public function loesche_schicht_mitarbeiter_durch_sid_termin($sid, $termin)
 	{
 		mysql_query("DELETE FROM schicht_mitarbeiter WHERE sid='".$sid."' AND termin='".$termin."'");
 	}
        
        public function loesche_schicht_mitarbeiter_durch_smid($smid)
 	{
 		mysql_query("DELETE FROM schicht_mitarbeiter WHERE smid='".$smid."'");
 	}
 	
 	/* Holt alle Schit_Mitarbeiter anhand der Schichtid
 	 * �bergabeparameter:	Schichtid
 	 * R�ckgabewert:		Feld -> Schicht_mitarbeiter Objekt(e)
 	 */
 	public function hole_alle_schicht_mitarbeiter_durch_sid($sid)
 	{
 		$schichten_mitarbeiter_feld = array();
 		$puffer = mysql_query("SELECT * FROM schicht_mitarbeiter WHERE sid='".$sid."'");
 		
 		while($mitarbeiter_schicht_objekt = mysql_fetch_object($puffer, 'Schicht_Mitarbeiter', array('smid', 'sid', 'mid', 'termin')))
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
 	public function hole_alle_schicht_mitarbeiter_durch_sid_termin($sid,$termin)
 	{
 		$schichten_mitarbeiter_feld = array();
 		$puffer = mysql_query("SELECT * FROM schicht_mitarbeiter WHERE sid='".$sid."' AND termin='".$termin."'");
 		
 		while($mitarbeiter_schicht_objekt = mysql_fetch_object($puffer, 'Schicht_Mitarbeiter', array('smid', 'sid', 'mid', 'termin')))
 		{
 			$schichten_mitarbeiter_feld[] = $mitarbeiter_schicht_objekt;
 		}
 		return $schichten_mitarbeiter_feld;
 	}
        
    public function hole_smid_durch_sid_termin_mid($sid,$termin,$mid)
 	{
 		$puffer = mysql_query("SELECT smid FROM schicht_mitarbeiter WHERE sid='".$sid."' AND termin='".$termin."' AND mid='".$mid."'");
 		return mysql_fetch_array($puffer);
 	}
 	
 	/* Holt den jeweilige schicht_ma Eintrag mit Mitarbeiteranzahl anhand der Schichtid und der Tagesid
 	 * �bergabeparameter:	Schichtid
 	 * 						Tagesid
 	 * R�ckgabewert:		Feld -> schicht_ma Objekt(e) mit Mitarbeiteranzahl
 	 */
 	public function hole_mitarbeiter_anzahl_durch_id($sid, $tid)
 	{
 		$puffer = mysql_query("SELECT ma FROM schicht_ma WHERE sid='".$sid."' AND tid='".$tid."'");
 		return mysql_fetch_array($puffer);
 	}


	//Holt alle schicht_mitarbeiter Einträge für den gegebenen Mitarbeiter
	public function hole_schicht_mitarbeiter_durch_mid($mid) 
	{
		$schichten_mitarbeiter_feld = array();
		$puffer = mysql_query("SELECT * FROM schicht_mitarbeiter WHERE mid='".$mid."'");
		while($mitarbeiter_schicht_objekt = mysql_fetch_object($puffer, 'Schicht_Mitarbeiter', array('smid', 'sid', 'mid', 'termin')))
		{
		 	$schichten_mitarbeiter_feld[] = $mitarbeiter_schicht_objekt;
		}
		return $schichten_mitarbeiter_feld;
	}

	//Holt die Anzahl aller Shichten des gegebenen Mitarbeiters in der gegebenen Woche
	public function anzahl_schichten_diese_woche($mid, $termin) {
		#Berechne, in wievielen Schichten der MA diese Woch eschon eingesetzt ist
		$alle_schichten = $this->hole_schicht_mitarbeiter_durch_mid($mid);
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
	
		
	public function stunden_diese_woche($mid,$termin) {
		#Berechne, in wievielen Schichten der MA diese Woch eschon eingesetzt ist
		$alle_schichten = $this->hole_schicht_mitarbeiter_durch_mid($mid);
		$kalender = new Kalender();
		#Begrenzende Tage der Woche
		$montag = $kalender->wochenAnfang($termin);
		$sonntag = $kalender->wochenEnde($termin);
		
		$interval = new DateInterval("P0Y");
		foreach($alle_schichten as $schicht) {
			#Schaue nur die Schichten innerhalb der Woche an
			if ($schicht->termin >= $montag && $schicht->termin <= $sonntag) {
				
				$schicht_infos = new Schicht();
				$info = $schicht_infos->hole_schicht_durch_id($schicht->sid);
				
				$ab = new DateTime($info->ab);
				$bis = new DateTime($info->bis);
				
				$spanne = $ab->diff($bis);
				$interval = addDateIntervals($interval, $spanne);
			}
		}
		return $interval;

	}
	
	public function stunden_diesen_monat($mid, $termin) {
		#Berechne, in wievielen Schichten der MA diese Woch eschon eingesetzt ist
		$alle_schichten = $this->hole_schicht_mitarbeiter_durch_mid($mid);
		$kalender = new Kalender();

		$termin = new DateTime($termin);
		
		$interval = new DateInterval("P0Y");
		foreach($alle_schichten as $schicht) {
			#Schaue nur die Schichten innerhalb des Monats an.
			$schicht_termin = new DateTime($schicht->termin);
			if ($schicht_termin->format('Y-m') == $termin->format('Y-m')) {
				$schicht_infos = new Schicht();
				$info = $schicht_infos->hole_schicht_durch_id($schicht->sid);
				
				$ab = new DateTime($info->ab);
				$bis = new DateTime($info->bis);
				
				$spanne = $ab->diff($bis);
				$interval = addDateIntervals($interval, $spanne);
			}
		}
		return $interval;

	}


 }









 ?>