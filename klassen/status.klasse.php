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
}
?>