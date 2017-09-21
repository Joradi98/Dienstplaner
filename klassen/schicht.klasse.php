<?php
include_once('klassen/schicht_mitarbeiter.klasse.php');

$alle_farben = array('d6b2d4', 'b3b2c6', 'b2d4d3', 'b6d0b2', 'd0d0b2', 'd5c6b2', 'd2b2b2',
                     'e6b2e4', 'b4b2e0', 'b2eaea', 'bbeab2', 'e4e2b2', 'ecd7b2', 'ecb2b2',
                     'ffb2fe', 'b6b2ff', 'b2fffe', 'bdffb2', 'fffeb2', 'ffe8b2', 'ffb2b2',
                     'ffccfd', 'ccc9ff', 'd4fffe', 'daffd3', 'fffed1', 'fff1cf', 'ffcfcf',
                     'ffe0ff', 'e6e5ff', 'e5fffd', 'edffe9', 'ffffec', 'fff9e8', 'ffe8e8');

class Schicht
{
	public $sid;
	public $bez;
	public $kbez;
	public $ab;
	public $bis;
    public $color;

	//Gibt die standardmäßig verwendete SID für die Sonderschicht zurück.
	public static $sonderschicht_sid = 0;

     /* Konstruktor
	 */
	public function Schicht()
	{
		
	}
	
	//Gibt die standardmäßig verwendete SID für die Sonderschicht zurück.
	public static function sonderschicht_sid() {
		return 0;
	}
	
	/* Holt die jeweilige Schicht anhand der Schichtid
	 * �bergabeparameter:	Schichtid
	 * R�ckgabewert:		Feld -> Schicht Objekt(e)
	 */
	public static function hole_schicht_durch_id($sid)
	{
		$puffer = mysql_query('SELECT * FROM schicht where sid ='.$sid);
		$schicht_objekt = mysql_fetch_object($puffer, 'Schicht', array('sid', 'bez', 'kbez', 'ab', 'bis'));
		
		return $schicht_objekt;
	}
	
	/* Holt alle Schichten sortiert nach ab und bis
	 * R�ckgabewert:		Feld -> Schicht Objekt(e)
	 */
	public static function hole_alle_schichten()
	{
		$schichten_objekt_feld = array();
		$puffer = mysql_query('SELECT * FROM schicht ORDER BY ab, bis ASC');
		
		while($schicht_objekt = mysql_fetch_object($puffer, 'Schicht' , array('sid', 'bez', 'kbez', 'ab', 'bis', 'color')))
		{
			$schichten_objekt_feld[] = $schicht_objekt;
		}
		return $schichten_objekt_feld;
	}
        
    public static function hole_alle_schichtfarben()
	{
		$schichten_farben_feld = array();
		$puffer = mysql_query('SELECT color FROM schicht');
		
		while($schicht_farbe = mysql_fetch_assoc($puffer))
		{
			$schichten_farben_feld[] = $schicht_farbe['color'];
		}
		return $schichten_farben_feld;
	}
	
	/* Holt alle Schichten deren Schicht_Mitarbeiter und die ben�tigte Mitarbeiteranzahl
	 * R�ckgabewert:		Feld -> Schicht Objekt(e)
	 * 								Schicht_Mitarbeiter
	 * 								ben�tigte Mitarbeiteranzahl
	 */
	public static function hole_alle_schichten_fuer_kalender()
	{
		$schicht_mitarbeiter_kalender_feld = array();
		$puffer = mysql_query('SELECT * FROM schicht_ma');
		while($schicht_mitarbeiter_kalender_reihe = mysql_fetch_row($puffer))
		{
			$schicht = new Schicht();
			$schicht = $schicht->hole_schicht_durch_id($schicht_mitarbeiter_kalender_reihe['1']);
			$schicht_mitarbeiter = new Schicht_Mitarbeiter();
			$schicht_mitarbeiter_feld = array();
			$schicht_mitarbeiter_feld = $schicht_mitarbeiter->hole_alle_schicht_mitarbeiter_durch_sid($schicht_mitarbeiter_kalender_reihe['1']);
			$schicht_mitarbeiter_kalender_feld[$schicht_mitarbeiter_kalender_reihe['0']][] = $schicht;
			$schicht_mitarbeiter_kalender_feld[$schicht_mitarbeiter_kalender_reihe['0']][] = $schicht_mitarbeiter_feld;
			$schicht_mitarbeiter_kalender_feld[$schicht_mitarbeiter_kalender_reihe['0']][][] = $schicht_mitarbeiter_kalender_reihe;

		}
		return $schicht_mitarbeiter_kalender_feld;
	}

	/* Schreibt Schicht in datenbank
	 * �bergabeparameter:	Bezeichnung
	 * 						Kurzbezeichnung
	 * 						Schichtstartzeit
	 * 						Schichtendzeit
	 */
	public static function schreibe_schicht($bez, $kbez, $ab, $bis, $color)
	{
		mysql_query('INSERT INTO schicht VALUES(NULL, "'.$bez.'", "'.$kbez.'", "'.$ab.'", "'.$bis.'", "'.$color.'")');
		
		//Beim Erstellen einer neuen Schicht werden die benötigten MA standarmäßig auf 0 gesetzt 
		$select_query = 'SELECT * FROM schicht WHERE bez="'.$bez . '" AND kbez="'.$kbez . '"';
		$puffer = mysql_query($select_query);
		$schicht_objekt = mysql_fetch_object($puffer, 'Schicht', array('sid', 'bez', 'kbez', 'ab', 'bis'));
		$sid = $schicht_objekt->sid;
		//Für alle Tage
		for($i=1; $i<=7; $i++) {
			$query = 'INSERT INTO schicht_ma VALUES ('.$i. ','.$sid.',0)';
			mysql_query($query);
			
		}

		
	}
	
	/* Erneuert die bereits vorhandene Schicht
	 * �bergabeparameter:	Schichtid
	 * 						Bezeichnung
	 * 						Kurzbezeichnung
	 * 						Schichtstartzeit
	 * 						Schichtendzeit
	 */
	public static function erneuere_schicht($sid, $bez, $kbez, $ab, $bis, $color)
	{
		mysql_query("UPDATE schicht SET bez='".$bez."', kbez='".$kbez."', ab='".$ab."', bis='".$bis."', color='".$color."' WHERE sid='".$sid."'");
	}
	
	/* L�scht Schicht anhand der �bergebenen Schichtid
	 * �bergabeparameter:	Schichtid
	 */
	public static function loesche_schicht_durch_id($sid)
	{
		mysql_query("DELETE FROM schicht WHERE sid='".$sid."'");
	}
}
?>