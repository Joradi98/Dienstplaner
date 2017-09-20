<?php

include('klassen/tag.klasse.php');
include('klassen/status.klasse.php');
include('klassen/standardplanmanager.klasse.php');
date_default_timezone_set('UTC');


#Fetche alle Informatino aus der URL und mache sie publicly accessible
if(isset($_GET['tid'])) {
	$tag = Tag::hole_tag_durch_tid(intval($_GET['tid']));
} else {
	#Monday = 0 will be the default
	$tag = Tag::hole_tag_durch_tid(2);
}

//Submenu for navigating between days
?>
<div id="submenu">
    <a id="pfeil_links" href="index.php?seite=standard_plan&tid=<?php echo $tag->vorheriger()->tid ?>"></a>
    <span id="kalenderueberschrift"><?php echo $tag->name ?></span>
    <a id="pfeil_rechts" href="index.php?seite=standard_plan&tid=<?php echo $tag->naechster()->tid ?>"></a>
</div>


<div id="hauptinhalt">
<?php


$alle_schichten =  StandardPlanManager::hole_alle_schichten_durch_tag($tag->tid);

//Stelle alle MA dieses Tages in einer Tabelle dar
echo '	<table id="top_left" style="height:100px;">';
echo '	<tr><th colspan="2" style="vertical-align:top">Standardm&auml;&szlig;ig eingeplante Mitarbeiter:</th></tr>';
foreach($alle_schichten as $schicht)
{				
	$beginn = new DateTime($schicht->von);
	$ende = new DateTime($schicht->bis);
	
	$ma = Mitarbeiter::hole_mitarbeiter_durch_id($schicht->mid);

	echo '<tr><td class="tablerow"><input type="checkbox" name = "mid" value="'.$schicht->mid.'" style="visibility:hidden;" checked />'.$ma->name.', '. $ma->vname . "</td>";

	echo '<td class="tablerow">' . $beginn->format("H:i") . '</td>';
	echo '<td class="tablerow">' . $ende->format("H:i") . '</td>';
    echo '</tr>';
}
echo '</table>'; 


?>

</div>