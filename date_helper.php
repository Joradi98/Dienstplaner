<?php
	function addDateIntervals()
	{
	    $reference = new DateTimeImmutable;
	    $endTime = clone $reference;

	    foreach (func_get_args() as $dateInterval) {
	        $endTime = $endTime->add($dateInterval);
	    }

	    return $reference->diff($endTime);
	}
?>