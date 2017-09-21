<?php

include_once "tag.klasse.php";
include_once "StandardPlanManager.klasse.php";

class Mitarbeiter
{
	public $mid;
	public $name;
	public $vname;
	public $adresse;
	public $tel;
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
	 * Übergabeparameter:	Name
	 * 						Vorname
	 * 						Adresse
	 * 						Telefon
	 * 						E-Mail UNIQUE
	 * 						Arbeitsstunden Tag
	 * 						Arbeitsstunden Woche
	 *						Arbeitsstunden Monat
	 *						Urlaubstage Jahr
	 *						Recht (0 = Mitarbeiter, 1 = Administrator)
	 *						Passwort (bereits md5 verschlüsselt)
	 *						Aktivstatus (0 = inaktiv, 1 = aktiv -> Standard = 0)
	 */
	public static function schreibe_mitarbeiter($name, $vname, $adresse, $tel, $email, $max_h_d, $max_h_w, $max_h_m, $max_u, $recht, $status, $pw, $aktiv = '0')
	{
		$query = 'INSERT INTO mitarbeiter VALUES(NULL,"'.$name.'","'.$vname.'","'.$adresse.'","'.$tel.'","'.$email.'","'.intval($max_h_d).'","'.intval($max_h_w).'","'.intval($max_h_m).'","'.intval($max_u).'","'.intval($recht). '","'.intval($status).'","'.$pw.'", "'.$aktiv.'")';
		mysql_query($query);
	}

	/* Holt den jeweiligen Mitarbeiter anhand der übergebenen Mitarbeiterid
	 * Übergabeparameter:	Mitarbeiterid
	 * Rückgabewert:		Mitarbeiter Objekt
	 */
	public static function hole_mitarbeiter_durch_id($mid)
	{
		$puffer = mysql_query('SELECT * FROM mitarbeiter WHERE mid = '.$mid);
		$mitarbeiter_objekt = mysql_fetch_object($puffer, 'Mitarbeiter' , array('mid', 'name', 'vname', 'adresse', 'tel', 'email', 'max_h_d', 'max_h_w', 'max_h_m', 'max_u', 'recht', 'status', 'pw', 'aktiv'));

		return $mitarbeiter_objekt;
	}

	/* Holt den jeweiligen Mitarbeiter anhand der übergebenen E-Mail
	 * Übergabeparameter:	Mitarbeiter-E-Mail
	 * Rückgabewert:		Mitarbeiter Objekt
	 */
	public static function hole_mitarbeiter_durch_email($email)
	{
		$puffer = mysql_query("SELECT * FROM mitarbeiter WHERE email='".$email."'");
		$mitarbeiter_objekt = mysql_fetch_object($puffer, 'Mitarbeiter' , array('mid', 'name', 'vname', 'adresse', 'tel', 'email', 'max_h_d', 'max_h_w', 'max_h_m', 'max_u', 'recht', 'status', 'pw', 'aktiv'));

		return $mitarbeiter_objekt;
	}

	/* Holt alle Mitarbeiter
	 * Rückgabewert:	Feld -> Mitarbeiter Objekt(e)
	 */
	public static function hole_alle_mitarbeiter()
	{
		$mitarbeiter_objekt_feld = array();
		$puffer = mysql_query('SELECT * FROM mitarbeiter');
		while($mitarbeiter_objekt = $mitarbeiter_objekt = mysql_fetch_object($puffer, 'Mitarbeiter' , array('mid', 'name', 'vname', 'adresse', 'tel', 'email', 'max_h_d', 'max_h_w', 'max_h_m', 'max_u', 'recht', 'status', 'pw', 'aktiv')))
		{
			$mitarbeiter_objekt_feld[] = $mitarbeiter_objekt;
		}
		return $mitarbeiter_objekt_feld;
	}

	/* Erneuert den bereits vorhandenen Mitarbeiter anhand der übergebenen Mitarbeiterid
	 * Übergabeparameter:	Mitarbeiterid
	 * 						Name
	 * 						Vorname
	 * 						Adresse
	 * 						Telefon
	 * 						E-Mail UNIQUE
	 * 						Arbeitsstunden Tag
	 * 						Arbeitsstunden Woche
	 *						Arbeitsstunden Monat
	 *						Urlaubstage Jahr
	 *						Recht (0 = Mitarbeiter, 1 = Administrator)
	 *						Passwort (bereits md5 verschlüsselt)
	 *						Aktivstatus (0 = inaktiv, 1 = aktiv -> Standard = 0)
	 */
	public static function erneuere_mitarbeiter($mid, $name, $vname, $adresse, $tel, $email, $max_h_d, $max_h_w, $max_h_m, $max_u, $recht, $pw, $aktiv = '0')
	{
		mysql_query("UPDATE mitarbeiter SET name='".$name."', vname='".$vname."', adresse='".$adresse."', tel='".$tel."', email='".$email."', max_h_d='".$max_h_d."', max_h_w='".$max_h_w."', max_h_m='".$max_h_m."', max_u='".$max_u."', recht='".$recht."', pw='".$pw."', aktiv='".$aktiv."' WHERE mid='".$mid."'");
	}

	/* Löscht den Mitarbeiter anhand der übergebenen Mitarbeiterid
	 * Übergabewert:	Mitarbeiterid
	 */
	public static function loesche_mitarbeiter_durch_id($mid)
	{
		mysql_query("DELETE FROM mitarbeiter WHERE mid='".$mid."'");
	}

	/* Aktiviert/Deaktiviert den Mitarbeiter anhand der übergebenen Mitarbeiterid und des Aktivstatus
	 * Übergabeparameter:	Mitarbeiterid
	 * 						Aktivstatus
	 */
	public static function aktiviere_mitarbeiter_durch_id($mid, $aktiv)
	{
		mysql_query("UPDATE mitarbeiter SET aktiv=".$aktiv." WHERE mid='".$mid."'");
	}

	/* Testet die übergebene E-Mail, ob sie bereits in der Datenbank vorhanden ist
	 * Übergabeparameter: E-Mail
	 * Rückgabewert:	True (E-Mail bereits vorhanden)
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
	
	
	//Gibt an, ob der MA fŸr eine gewisse Zeit verfŸgbar ist (oder schon andeerweitig eingesetzt). BerŸcksichtigt nicht Urlaub
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
	
	
	//Gibt an, ob der MA fŸr eine gewisse Zeit verfŸgbar ist (oder schon andeerweitig eingesetzt). BerŸcksichtigt nicht Urlaub
	public function ist_verfuegbar_zur_zeit($termin,$von,$bis) {
		$schichten_mitarbeiter_feld = array();
		$puffer = mysql_query("SELECT * FROM schicht_mitarbeiter WHERE mid='".$this->mid."' AND termin='".$termin."'");
	 		
		while($mitarbeiter_schicht_objekt = mysql_fetch_object($puffer, 'Schicht_Mitarbeiter', array('smid', 'sid', 'mid', 'termin', 'von', 'bis')))
		{
			#MA schon verplant
			$anfang_zwischendrin = ( $mitarbeiter_schicht_objekt->von > $von  && $mitarbeiter_schicht_objekt->von < $bisÊ);
			$ende_zwischendrin = ( $mitarbeiter_schicht_objekt->bis > $von  && $mitarbeiter_schicht_objekt->bis < $bisÊ);
			$von_eingeschlossen = ( $mitarbeiter_schicht_objekt->von <= $von  && $mitarbeiter_schicht_objekt->bis >= $vonÊ);
			$bis_eingeschlossen = ( $mitarbeiter_schicht_objekt->von <= $bis  && $mitarbeiter_schicht_objekt->bis >= $bisÊ);

			if ( $anfang_zwischendrin || $ende_zwischendrin || $von_eingeschlossen || $bis_eingeschlossen ) {
				return false;
			}
		}
		#Falls nichts gefunden wurde, ist der MA verfŸbar
		return true;
	 }

	//Gibt an, wie viele Stunden der MA am Termin arbeitet. Pausen nicht berŸcksichtigt
	public function brutto_stunden_am_tag($termin) {
		$tag = Tag::tag_an_termin($termin);
		if (StandardPlanManager::wird_angewendet($termin)) {
			#Nach Std-plan berechnen
			$schichten = StandardPlanManager::hole_alle_schichten_durch_ma_tid($this->mid, $tag->tid);
			$interval = new DateInterval("P0Y");
			
		} else {
			#nach sonderplan (schicht_mitarbeiter) berechnen
			
			
		}
	}



}
?>