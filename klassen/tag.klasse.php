<?php




date_default_timezone_set('UTC');




#Helper functinos outside the class
function addDateIntervals() {
	$reference = new DateTimeImmutable;
	$endTime = clone $reference;

	foreach (func_get_args() as $dateInterval) {
	   	$endTime = $endTime->add($dateInterval);
	}
	
	return $reference->diff($endTime);	
}

/*
*	
*/
function subDateIntervals($int1, $int2) {
	$base_time1 = new DateTime('midnight');
	$base_time2 = new DateTime('midnight');

	$base_time1->add($int1);
	$base_time2->add($int2);
	
	return $base_time1->diff($base_time2);
}

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
		while($objekt = mysql_fetch_object($puffer, 'Tag', array('tid', 'name')))
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
	
	//Returns a "Tag" object mittels Termin (str)
	public static function tag_an_termin($termin) {
		$weekday = date("w",strtotime($termin));
		//Der SOnntag wird als 0 kodiert, in der DB aber als 7
		if ($weekday == 0) {
			$weekday = 7;
		}
		return Tag::hole_tag_durch_tid($weekday);
	}
	
	

	
	
}
?>