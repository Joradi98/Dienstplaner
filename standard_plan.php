<?php

include_once('klassen/tag.klasse.php');
include_once('klassen/status.klasse.php');
include_once('klassen/StandardPlanManager.klasse.php');

date_default_timezone_set('UTC');


#Fetche alle Informatino aus der URL und mache sie publicly accessible
if(isset($_GET['tid'])) {
	$tag = Tag::hole_tag_durch_tid(intval($_GET['tid']));
} else {
	#Monday = 0 will be the default
	$tag = Tag::hole_tag_durch_tid(1);
}



//Lösche einen Mitarbeiter
if(isset($_GET['l']))
{
    $smid = $_GET['l'];
    StandardPlanManager::entferne_tmid($smid);
           
}



#Form submission: Update for individual times
if(isset($_POST['timeUpdate'])) {
	
	$von = $_POST['von'];
	$bis = $_POST['bis'];
	$mid = $_POST['mid'];
	$alt_beginn = $_POST['alt_beginn'];
	$alt_ende = $_POST['alt_ende'];
	$tid = $tag->tid;
	
	//Varify input
	if (! isset($_POST['von']) || strlen($_POST['von']) == 0){
		$von = $alt_beginn;
	}
		
	if (! isset($_POST['bis']) || strlen($_POST['bis']) == 0 ){
		$bis = $alt_ende;
	}

	$caught = 0;
	try {
		$von = new DateTime($von);
		$bis = new DateTime($bis);

	} catch (Exception $e) {
		$fehler = "Bitte geben Sie eine g&uuml;ltige Uhrzeit an";
		$caught = 1;
	} 
	
	if ($caught == 0) {
		StandardPlanManager::update_zeiten($tid, $_POST['mid'], $von->format("H:i"), $bis->format("H:i"));
	}
}

#Form submission: Update for individual times
if(isset($_POST['neuerMA'])) {
	
	$von = $_POST['von'];
	$bis = $_POST['bis'];
	$mid = $_POST['mid'];
	$tid = $tag->tid;

	$caught = 0;
	try {
		$test = new DateTime($von);
		$test = new DateTime($bis);

	} catch (Exception $e) {
		$fehler = "Bitte geben Sie eine g&uuml;ltige Uhrzeit an";
		$caught = 1;
	} 

	if ($caught == 0) {
		StandardPlanManager::neue_schicht($tid, $mid, $von, $bis);
	}
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

#Fehler und Erfolg darstellen, oben im Hauptinhalt
if(isset($erfolg)) {
	echo '<table><tr><td colspan="2" class="erfolg">'.$erfolg.'</td></tr></table>';
}

if(isset($fehler)) {
	echo '<table><tr><td colspan="2" class="fehler">'.$fehler.'</td></tr></table>';
}





$alle_schichten = StandardPlanManager::hole_alle_schichten_durch_tag($tag->tid);

//Stelle alle MA dieses Tages in einer Tabelle dar
echo '	<table id="top_left"style="height:100px;">';
echo '	<tr><th colspan="4" style="vertical-align:top">Standardm&auml;&szlig;ig eingeplante Mitarbeiter:</th></tr>';
foreach($alle_schichten as $schicht)
{				
	$beginn = new DateTime($schicht->von);
	$ende = new DateTime($schicht->bis);
	
	$ma = Mitarbeiter::hole_mitarbeiter_durch_id($schicht->mid);
	#Form for updating individual times
	echo '<form action="index.php?seite=standard_plan&tid='.$tag->tid.'" method="post">';

	echo '<tr><td class="tablerow"><input type="checkbox" name = "mid" value="'.$schicht->mid.'" style="visibility:hidden;" checked />'.$ma->name.', '. $ma->vname . "</td>";
	
	#Hidden values: Old beginning and ending
	echo '<input type="hidden" name=alt_beginn value="' .$beginn->format("H:i"). '" >';
	echo '<input type="hidden" name=alt_ende value="' .$ende->format("H:i"). '">';

	#Input: Individual times
	echo '<td class="tablerow"><input name="von" type="text" class="uhrzeit_text" placeholder= ' . $beginn->format("H:i") . '></td>';
	echo '<td class="tablerow"><input name="bis" type="text" class="uhrzeit_text" placeholder= ' . $ende->format("H:i") . '></td>';


	echo '<td class="tablerow"> | <a href="index.php?seite=standard_plan&l=' . $schicht->tmid . ' ">entfernen</a></td></tr>';
	
	echo '</tr>';

	//Hide the button, so pressing enter will submit the form
	echo '<td><input type="submit" name="timeUpdate" value = "" class = "hidden_submit"></td>';
	echo "</tr>";
	#Unbedingt Form schließen
	echo '</form>';

}
echo '</table><table id="top_right">';





/* On the right top side: A form for adding new employees to the standard plan*/
if($_SESSION['mitarbeiter']->recht=='1') {
	echo '<form action="index.php?seite=standard_plan&tid='.$tag->tid.'" method="post">';
	echo '<tr><th colspan="2">Mitarbeiter hinzuf&uuml;gen</th></tr>';
	
	echo '<tr><td><select name="mid">';
      

	$mitarbeiter_feld = Mitarbeiter::hole_alle_mitarbeiter();
	foreach($mitarbeiter_feld as $mitarbeiter) {

		echo '<option value="'.$mitarbeiter->mid.'">'.$mitarbeiter->name.', '.$mitarbeiter->vname.'&ensp;</option>';

		/*#Berechne, in wievielen Schichten der MA diese Woch eschon eingesetzt ist
		$schicht_mitarbeiter = new Schicht_Mitarbeiter();
		#Berechen stundenzahl diese woche
		$stunden = $schicht_mitarbeiter->stunden_diese_woche($mitarbeiter->mid, $termin);*/
			
						
		/*#Wenn der MA schon eingetragen wurde, kann er nicht zweifach eingetragen werden
		$einteilungen = $schicht_mitarbeiter->hole_smid_durch_sid_termin_mid($sid,$termin,$mitarbeiter->mid);
		if (count($einteilungen) >= 2) {
			echo '<option disabled value="'.$mitarbeiter->mid.'">'.$mitarbeiter->name.', '.$mitarbeiter->vname.'&ensp; '.$stunden->format("%H:%I").'h in dieser Woche</option>';
		} else 	if($test=='0') {
			echo '<option value="'.$mitarbeiter->mid.'">'.$mitarbeiter->name.', '.$mitarbeiter->vname.'&ensp; '.$stunden->format("%H:%I").'h in dieser Woche</option>';
		}*/
                
	}
}
?>

</select></td></tr>
		<tr>
			<td><input type="text" name="von" placeholder="Uhrzeit: Von" required ></td>
			<td><input type="text" name="bis" placeholder="Uhrzeit: Bis" required></td>

		</tr>
		<tr>
              <td><input class="knopf_erstellen" type="submit" name="neuerMA" value=" "></td>
         </tr>
	</table>
</form>
</div>


<div id="monats_stats">
<table id="tagesplan"> 
<?php
#Erstelle "Dienstplan" Tabelle

#Zuerst die  Überschriften
$start = new DateTime("6:45");
$ende = new DateTime("16:15");

echo '<tr>';
echo '<th>Name</th>';
$i = clone $start;
while ($i <= $ende) {
	#<div style="width:10px; overflow:hidden"> to come maybe
	echo '<th id="clock_header">'. $i->format("H:i"). '</th>';
	$i->modify("+ 15 minutes");
}
echo '</tr>';


#Zeilen füllen
$alle_ma = Mitarbeiter::hole_alle_mitarbeiter();
foreach ($alle_ma as $mitarbeiter) {
	echo '<tr id="tagesplan_reihe">';

	#In der ersten Spalte der Name
	echo '<td>'.$mitarbeiter->vname.' '. $mitarbeiter->name. '</td>';

	
	#Die anderen Spalten färben, falls ein Einsatz stattfindet
	$i = clone $start;
	while ($i <= $ende) {
		if ( $mitarbeiter->hat_standard_dienst($tag->tid, $i->format("H:i")) ) {
			echo '<td id="tagesplan_zelle" bgcolor=blue></td>';
		} else {
			echo '<td id="tagesplan_zelle" bgcolor=white></td>';
		}

		$i->modify("+ 15 minutes");
	}



	
	echo '</tr>';
}


?>

   
</table>
</div>








