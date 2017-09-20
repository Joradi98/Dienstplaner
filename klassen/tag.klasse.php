<?php
class Tag
{
	public $tid;
	public $name;
	
	/* Konstruktor
	 */
	public function Tag()
	{
		
	}
	
	
	public static function hole_tag_durch_tid($tid)
	{
		$query = "SELECT * FROM tag WHERE tid='".$tid."'";
		$puffer = mysql_query($query);
		$tag = new Tag();
		while($objekt = mysql_fetch_object($puffer, 'Status', array('tid', 'name')))
		{
			$tag->tid = $objekt->tid;
			$tag->name = $objekt->name;

		}
		return $tag;
	}
        
    public function naechster() {
		#Clip to range [1,7]
		$new_id = $this->tid + 1;
		if ( $new_id > 7) { $new_id = 1; }
		return Tag::hole_tag_durch_tid($new_id);
	}
	
	public function vorheriger() {
		#Clip to range [1,7]
		$new_id = $this->tid - 1;
		if ( $new_id < 1) { $new_id = 7; }
		return Tag::hole_tag_durch_tid($new_id);
	}
}
?>