<?php
class Status
{
	public $stid;
	public $bez;
	
	/* Konstruktor
	 */
	public function Status()
	{
		
	}
	
	/* Holt alle Urlaubseintr�ge anhand der �bergebenen Mitarbeiterid
	 * �bergabeparameter:	Mitarbeiterid
	 * R�ckgabewert:		Feld -> Urlaub Objekt(e)
	 */
	public static function name_vom_status($stid)
	{
		$puffer = mysql_query("SELECT * FROM status WHERE stid='".$stid."'");
		while($objekt = mysql_fetch_object($puffer, 'Status', array('stid', 'bez')))
		{
			return $objekt->bez;
		}
		return "";
	}
        
    public static function id_vom_status($bez)
	{
		$puffer = mysql_query("SELECT * FROM status WHERE stid='".$stid."'");
		while($objekt = mysql_fetch_object($puffer, 'Status', array('stid', 'bez')))
		{
			return $objekt->stid;
		}
		return "";
	}
	
	
	public static function hole_alle_status() {
		$array = array();

		$puffer = mysql_query("SELECT * FROM status ORDER BY stid");
		while($objekt = mysql_fetch_object($puffer, 'Status', array('stid', 'bez'))) {
			$array[] = $objekt;
		}
		return $array;
	}
	
}
?>