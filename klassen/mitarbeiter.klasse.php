<?php

include_once "tag.klasse.php";
include_once "schicht_mitarbeiter.klasse.php";
include_once "urlaub.klasse.php";
include_once "StandardPlanManager.klasse.php";

class Mitarbeiter
{
	public $mid;
	public $name;
	public $vname;
	public $adresse;
	public $geburtstag;
	public $email;
	public $max_h_d;
	public $max_h_w;
	public $max_h_m;
	public $max_u;
	public $recht;
	public $status_id; #Status ID
	public $pw;
	public $aktiv;

	/* Konstruktor
	 */
	public function Mitarbeiter()
	{

	}

	/* Schreib Mitarbeiter in Datenbank
	 * �bergabeparameter:	Name
	 * 						Vorname
	 * 						Adresse
	 * 						geburtstag
	 * 						E-Mail UNIQUE
	 * 						Arbeitsstunden Tag
	 * 						Arbeitsstunden Woche
	 *						Arbeitsstunden Monat
	 *						Urlaubstage Jahr
	 *						Recht (0 = Mitarbeiter, 1 = Administrator)
	 *						Passwort (bereits md5 verschl�sselt)
	 *						Aktivstatus (0 = inaktiv, 1 = aktiv -> Standard = 0)
	 */
	public static function schreibe_mitarbeiter($name, $vname, $adresse, $geburtstag, $email, $max_h_d, $max_h_w, $max_h_m, $max_u, $recht, $status, $pw, $aktiv = '0')
	{
		$query = 'INSERT INTO mitarbeiter VALUES(NULL,"'.$name.'","'.$vname.'","'.$adresse.'","'.$geburtstag.'","'.$email.'","'.intval($max_h_d).'","'.intval($max_h_w).'","'.intval($max_h_m).'","'.intval($max_u).'","'.intval($recht). '","'.intval($status).'","'.$pw.'", "'.$aktiv.'")';
		mysql_query($query);
	}

	/* Holt den jeweiligen Mitarbeiter anhand der �bergebenen Mitarbeiterid
	 * �bergabeparameter:	Mitarbeiterid
	 * R�ckgabewert:		Mitarbeiter Objekt
	 */
	public static function hole_mitarbeiter_durch_id($mid)
	{
		$puffer = mysql_query('SELECT * FROM mitarbeiter WHERE mid = '.$mid);
		$mitarbeiter_objekt = mysql_fetch_object($puffer, 'Mitarbeiter' , array('mid', 'name', 'vname', 'adresse', 'geburtstag', 'email', 'max_h_d', 'max_h_w', 'max_h_m', 'max_u', 'recht', 'status', 'pw', 'aktiv'));

		return $mitarbeiter_objekt;
	}

	/* Holt den jeweiligen Mitarbeiter anhand der �bergebenen E-Mail
	 * �bergabeparameter:	Mitarbeiter-E-Mail
	 * R�ckgabewert:		Mitarbeiter Objekt
	 */
	public static function hole_mitarbeiter_durch_email($email)
	{
		$puffer = mysql_query("SELECT * FROM mitarbeiter WHERE email='".$email."'");
		$mitarbeiter_objekt = mysql_fetch_object($puffer, 'Mitarbeiter' , array('mid', 'name', 'vname', 'adresse', 'geburtstag', 'email', 'max_h_d', 'max_h_w', 'max_h_m', 'max_u', 'recht', 'status', 'pw', 'aktiv'));

		return $mitarbeiter_objekt;
	}

	/* Holt alle Mitarbeiter
	 * R�ckgabewert:	Feld -> Mitarbeiter Objekt(e)
	 */
	public static function hole_alle_mitarbeiter()
	{
		$mitarbeiter_objekt_feld = array();
		$puffer = mysql_query('SELECT * FROM mitarbeiter');
		while($mitarbeiter_objekt = $mitarbeiter_objekt = mysql_fetch_object($puffer, 'Mitarbeiter' , array('mid', 'name', 'vname', 'adresse', 'geburtstag', 'email', 'max_h_d', 'max_h_w', 'max_h_m', 'max_u', 'recht', 'status', 'pw', 'aktiv')))
		{
			$mitarbeiter_objekt_feld[] = $mitarbeiter_objekt;
		}
		return $mitarbeiter_objekt_feld;
	}

	/* Erneuert den bereits vorhandenen Mitarbeiter anhand der �bergebenen Mitarbeiterid
	 * �bergabeparameter:	Mitarbeiterid
	 * 						Name
	 * 						Vorname
	 * 						Adresse
	 * 						geburtstag
	 * 						E-Mail UNIQUE
	 * 						Arbeitsstunden Tag
	 * 						Arbeitsstunden Woche
	 *						Arbeitsstunden Monat
	 *						Urlaubstage Jahr
	 *						Recht (0 = Mitarbeiter, 1 = Administrator)
	 *						Passwort (bereits md5 verschl�sselt)
	 *						Aktivstatus (0 = inaktiv, 1 = aktiv -> Standard = 0)
	 */
	public static function erneuere_mitarbeiter($mid, $name, $vname, $adresse, $geburtstag, $email, $max_h_d, $max_h_w, $max_h_m, $max_u, $status, $pw, $aktiv = 0)
	{

		$query = "UPDATE mitarbeiter SET name='".$name."', vname='".$vname."', adresse='".$adresse."', geburtstag='".$geburtstag."', email='".$email."', max_h_d='".$max_h_d."', max_h_w='".$max_h_w."', max_h_m='".$max_h_m."', max_u='".$max_u."', status=".$status.", pw='".$pw."', aktiv=".$aktiv." WHERE mid='".$mid."'";
		mysql_query($query);
	}

	/* L�scht den Mitarbeiter anhand der �bergebenen Mitarbeiterid
	 * �bergabewert:	Mitarbeiterid
	 */
	public static function loesche_mitarbeiter_durch_id($mid)
	{
		mysql_query("DELETE FROM mitarbeiter WHERE mid='".$mid."'");
	}

	/* Aktiviert/Deaktiviert den Mitarbeiter anhand der �bergebenen Mitarbeiterid und des Aktivstatus
	 * �bergabeparameter:	Mitarbeiterid
	 * 						Aktivstatus
	 */
	public static function aktiviere_mitarbeiter_durch_id($mid, $aktiv)
	{
		mysql_query("UPDATE mitarbeiter SET aktiv=".$aktiv." WHERE mid='".$mid."'");
	}

	/* Testet die �bergebene E-Mail, ob sie bereits in der Datenbank vorhanden ist
	 * �bergabeparameter: E-Mail
	 * R�ckgabewert:	True (E-Mail bereits vorhanden)
	 * 					False (E-Mail noch nicht vorhanden)
	 */
	public static function teste_email($email)
	{
		$puffer = mysql_query("SELECT * FROM mitarbeiter WHERE email='".$email."'");
		if(mysql_fetch_row($puffer)) {
			return true;
		} else {
			return false;
		}
	}
	
	
	/*Gibt an, ob der MA f�r eine gewisse Zeit verf�gbar ist (oder schon andeerweitig eingesetzt). Ber�cksichtigt nicht Urlaub.
	* Termin: str
	*/
	public function ist_im_Urlaub($termin) {
		$urlaub = new Urlaub();
		$urlaub_feld = $urlaub->hole_urlaub_durch_mid($this->mid);
		#Gehe alle Urlaube des Mitarbeiters durch
		foreach($urlaub_feld as $urlaub_objekt) {
			#Liegt der aktuell berabeitete Teremin mitten in seinem Urlaub
			if($termin >= $urlaub_objekt->ab && $termin <= $urlaub_objekt->bis) {
				return true;
			} 
		}
		return false;
	}
	
	
	//Gibt an, ob der MA f�r eine gewisse Zeit verf�gbar ist (oder schon andeerweitig eingesetzt). Ber�cksichtigt nicht Urlaub
	public function ist_verfuegbar_zur_zeit($termin,$von,$bis) {
		$schichten_mitarbeiter_feld = array();
		$puffer = mysql_query("SELECT * FROM schicht_mitarbeiter WHERE mid='".$this->mid."' AND termin='".$termin."'");
	 		
		while($mitarbeiter_schicht_objekt = mysql_fetch_object($puffer, 'Schicht_Mitarbeiter', array('smid', 'sid', 'mid', 'termin', 'von', 'bis')))
		{
			#MA schon verplant
			$anfang_zwischendrin = ( $mitarbeiter_schicht_objekt->von > $von  && $mitarbeiter_schicht_objekt->von < $bis�);
			$ende_zwischendrin = ( $mitarbeiter_schicht_objekt->bis > $von  && $mitarbeiter_schicht_objekt->bis < $bis�);
			$von_eingeschlossen = ( $mitarbeiter_schicht_objekt->von <= $von  && $mitarbeiter_schicht_objekt->bis >= $von�);
			$bis_eingeschlossen = ( $mitarbeiter_schicht_objekt->von <= $bis  && $mitarbeiter_schicht_objekt->bis >= $bis�);

			if ( $anfang_zwischendrin || $ende_zwischendrin || $von_eingeschlossen || $bis_eingeschlossen ) {
				return false;
			}
		}
		#Falls nichts gefunden wurde, ist der MA verf�bar
		return true;
	 }





	/*	Gibt an, wie viele Stunden der MA am Termin arbeitet. Pausen nicht ber�cksichtigt
	*	Returns: DateInterval
	*/
	public function netto_stunden_am_termin($termin) {
		$tag = Tag::tag_an_termin($termin);
		$interval = new DateInterval("P0Y");
		if (StandardPlanManager::wird_angewendet($termin)) {
			#Nach Std-plan berechnen
			$schichten = StandardPlanManager::hole_alle_schichten_durch_ma_tid($this->mid, $tag->tid);
			
		} else {
			#nach sonderplan (schicht_mitarbeiter) berechnen
			$schichten = Schicht_Mitarbeiter::hole_alle_schicht_mitarbeiter_durch_mid_termin($this->mid, $termin);
		}
		
		#Careful here! Although both arrays are called $schichten, they contain objects of different classes
		#TODO: Merge these classes to one common 'schicht'-class. Guess this is No. >9000 in cleaning up this project
		foreach ($schichten as $schicht) {
			$ab = new DateTime($schicht->von);
			$bis = new DateTime($schicht->bis);
			$spanne = $ab->diff($bis);
			
			#Pause abziehen, wenn >=06:30h gearbeitet wird
			$check1 = ($spanne->h > 6);
			$check2 = ($spanne->h == 6 && $spanne->i >= 30);
			$pausenzeit = new DateInterval('PT30M'); #30 Minuten Pausenzeit (https://stackoverflow.com/questions/21742329/how-to-create-a-dateinterval-from-a-time-string)

			if ( $check1 || $check2 ) {
				$spanne = subDateIntervals($pausenzeit, $spanne); #Reihenfolge wichtig
			}
			
			$interval = addDateIntervals($interval, $spanne);
		}
		return $interval;

	}


	/*	Gibt an, wie viele Stunden der MA am Termin laut Standardplan arbeitet. Pausen nicht ber�cksichtigt
	*	Returns: DateInterval
	*/
	public function netto_standard_stunden_am_termin($termin) {
		$tag = Tag::tag_an_termin($termin);
		$interval = new DateInterval("P0Y");
		$schichten = StandardPlanManager::hole_alle_schichten_durch_ma_tid($this->mid, $tag->tid);
		foreach ($schichten as $schicht) {
			$ab = new DateTime($schicht->von);
			$bis = new DateTime($schicht->bis);
			$spanne = $ab->diff($bis);
			
			#Pause abziehen, wenn >=06:30h gearbeitet wird
			$check1 = ($spanne->h > 6);
			$check2 = ($spanne->h == 6 && $spanne->i >= 30);
			$pausenzeit = new DateInterval('PT30M'); #30 Minuten Pausenzeit (https://stackoverflow.com/questions/21742329/how-to-create-a-dateinterval-from-a-time-string)

			if ( $check1 || $check2 ) {
				$spanne = subDateIntervals($pausenzeit, $spanne); #Reihenfolge wichtig
			}
			
			$interval = addDateIntervals($interval, $spanne);
		}
		return $interval;
	}
	
	/*
	Im Gegensatz zu stunden_diese_woche liefert diese Funktion direkt einen String zur�ck. 
	*/
	public function netto_workload_diese_woche($termin) {
			
		$kalender = new Kalender();
		#Begrenzende Tage der Woche
		$montag = $kalender->wochenAnfang($termin);
		$sonntag = $kalender->wochenEnde($termin);
		$next_montag = date('Y-m-d',(strtotime ( '+ 1 day' , strtotime ( $sonntag) ) ));

		$interval = new DateInterval("P0Y"); //0 years

		#Nice while-statement :P
		while ($montag != $next_montag) {
			$netto_tag = $this->netto_stunden_am_termin($montag);
			$interval = addDateIntervals($interval, $netto_tag);
			$montag = date('Y-m-d',(strtotime ( '+ 1 day' , strtotime ( $montag) ) ));
		}
			
		$total_hours = $interval->d * 24 + $interval->h;
		return $total_hours . ":" . $interval->format("%I");
	}
		



	/*
	Im Gegensatz zu stunden_diesen_monat liefert diese Funktion direkt einen String zur�ck. 
	*/
	public function netto_workload_diesen_monat($termin) {
					
		$kalender = new Kalender();
		#Begrenzende Tage der Woche
		$datum = new DateTime($termin);
		$erster = new DateTime($datum->format("Y-m-01")) ;
		$interval = new DateInterval("P0Y"); //0 years
		
		#Solagen wir uns im gleichen Monat bewegen
		while ($erster->format('Y-m') == $datum->format('Y-m')) {
			$netto_tag = $this->netto_stunden_am_termin($erster->format("Y-m-d"));
			$interval = addDateIntervals($interval, $netto_tag);
			$erster = $erster->modify("+1 day") ;
		}
		$total_hours = $interval->d * 24 + $interval->h;
		return $total_hours . ":" . $interval->format("%I");

	}


	/*
	*	Berechnet �berstunden im aktuellen Monat beruhend auf dem Standardplan
	*/
	public function ueberstunden_diesen_monat($termin) {
		$kalender = new Kalender();
		#Begrenzende Tage der Woche
		$datum = new DateTime($termin);
		$erster = new DateTime($datum->format("Y-m-01")) ;
		$interval = new DateInterval("P0Y"); //0 years
		
		$ins = new DateTime();
		#Solagen wir uns im gleichen Monat bewegen
		while ($erster->format('Y-m') == $datum->format('Y-m')) {
			$standard_std = $this->netto_standard_stunden_am_termin($erster->format("Y-m-d"));
			$richtige_std = $this->netto_stunden_am_termin($erster->format("Y-m-d"));

			//Differenz berechnen
			$ueber_std = subDateIntervals($standard_std,$richtige_std); #FUnktioniert auch, wenn weniger gearbeitet wird, als normal. Verrehcnet schon abgefeierte �st.
			
			
			$interval = addDateIntervals($interval, $ueber_std);
		

			$erster = $erster->modify("+1 day") ;
		}
		

		#Hier weerden �berstunden mitgez�hlt
		$total_hours = $interval->d * 24 + $interval->h;
			
			#Es kann auch passieren, dass zu wenig gearbeitet wird	
		if ($interval->invert == 1) {
			$total_hours *= -1;	
		}

		return $total_hours . ":" . $interval->format("%I");
		
		
	}

	/*
	*	Gibt an, ob der MA am gegebenen Termin (str) schon eingesetzt ist.
	*/
	public function wird_eingesetzt_am_termin($termin) {
		
		if (StandardPlanManager::wird_angewendet($termin)) {
			#Schaue im Std-plan
			$tid = Tag::tag_an_termin($termin)->tid;
			$query = "SELECT * FROM standard_plan WHERE tid=".$tid. " AND mid=" . $this->mid;
			$puffer = mysql_query($query);
			return ( mysql_num_rows($puffer) ) != 0;
				
		} else {
			$query = "SELECT * FROM schicht_mitarbeiter WHERE termin='".$termin. "' AND mid=" . $this->mid;
			$puffer = mysql_query($query);
			return ( mysql_num_rows($puffer) ) != 0;

		}
		return true;
	}

	/*
	* Uhrzeit: str, Termin: str
	*/
	public function hat_dienst($termin, $uhrzeit) {

		if (StandardPlanManager::wird_angewendet($termin)) {
			$tid = Tag::tag_an_termin($termin)->tid;
			return $this->hat_standard_dienst($tid, $uhrzeit);
			
		} else {
			return $this->hat_sonder_dienst($termin,$uhrzeit);
		}
	}
	
	
	/*
	* Uhrzeit: str, Termin: str
	*/
	public function hat_standard_dienst($tid, $uhrzeit) {
		#Schaue im Std-plan
		$zeit = new DateTime($uhrzeit);

		$query = "SELECT * FROM standard_plan WHERE tid=".$tid. " AND mid=" . $this->mid;
		$puffer = mysql_query($query);
		while($objekt = mysql_fetch_object($puffer, 'StandardPlanManager', array('tmid','tid', 'mid', 'von', 'bis'))) {
			$von = new DateTime($objekt->von);
			$bis = new DateTime($objekt->bis);
	
			if ($zeit >= $von && $zeit <= $bis) {
				return true;
						
			}
		}
			
		return false;
	}
	
	
	
	public function hat_sonder_dienst($termin, $uhrzeit) {
		$zeit = new DateTime($uhrzeit);
		$schichten = Schicht_Mitarbeiter::hole_alle_schicht_mitarbeiter_durch_mid_termin($this->mid, $termin);
		foreach ($schichten as $schicht) {
			$von = new DateTime($schicht->von);
			$bis = new DateTime($schicht->bis);
			if ($zeit >= $von && $zeit <= $bis) {
				return true;
			}
		}
		return false;
		

	}
	
	
	/*
	*	Gibt die Anzahl der verbelibenden Urlaubstage zur�ck
	*/
	public function resturlaub() {
		$anzahl_urlaubstage = mysql_fetch_array(mysql_query('SELECT sum(tage) FROM urlaub WHERE mid = "'.$this->mid.'" GROUP BY mid'));
		/* Resturlaub berechnen */
		$resturlaub =  $this->max_u - $anzahl_urlaubstage[0];
		return $resturlaub;

	}
	
	public static function jemand_hat_geburtstag($termin) {
		
		$alle = Mitarbeiter::hole_alle_mitarbeiter();
		foreach ($alle as $mitarbeiter) {
			$birthday = new DateTime($mitarbeiter->geburtstag);
			$day = new DateTime($termin);
			
			if ( $birthday->format("m-d") == $day->format("m-d") ) {
				return true;
			}
		}
		return false;
		
	}
	

}
?>