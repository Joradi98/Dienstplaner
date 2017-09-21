<?php
if(isset($_GET['sub']))
{
	$activ = $_GET['sub'];
}
else
{
	$activ = 'uebersicht';
}

if(isset($_GET['sub']))
{
	switch($_GET['sub'])
	{
            case 'uebersicht': include_once('kalender_uebersicht.php'); break;
            case 'detail': include_once('kalender_detail.php'); break;
            case 'tag': include_once('kalender_tag.php'); break;

	    default: break;
	}
}
else
{
	include_once('kalender_uebersicht.php');
}