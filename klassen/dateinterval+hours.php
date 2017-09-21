<?php

class DateIntervalEnhanced extends DateInterval {

    public function recalculate()
    {
		echo "hello";
    }
}


$int = new DateInterval("PT3600S");
$int->recalculate();
?>