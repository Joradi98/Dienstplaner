<?php
/* Klassen Schicht und Urlaub einbinden */
include_once('klassen/schicht.klasse.php');
include_once('klassen/urlaub.klasse.php');
include_once('klassen/kalender.klasse.php');
include_once('klassen/StandardPlanManager.klasse.php');
include_once('klassen/tag.klasse.php');
include_once('klassen/status.klasse.php');

date_default_timezone_set('UTC');


//Parse inputs from URL
if(isset($_GET['tag']) && isset($_GET['monat']) && isset($_GET['jahr']))
{
	$tag = $_GET['tag'];
	$monat = $_GET['monat'];
	$jahr = $_GET['jahr'];
	$termin = $jahr.'-'.$monat.'-'.$tag;
}
else
{
	$termin = $_POST['termin'];
	$tag = substr($_POST['termin'], 8, 2);
	$monat = substr($_POST['termin'], 5, 2);
	$jahr = substr($_POST['termin'], 0, 4);
}


//FORM: Neue SonderSchicht wurde angelegt
if(isset($_POST['neueSonderschicht'])) {
	/*$schicht_verwaltung = new Schicht();
	$schicht_mitarbeiter = new Schicht_Mitarbeiter();

	$new_mid = $_POST['neueSonderschicht'];
	
	//TODO: lenny face
	$sonder_sid = $schicht_verwaltung->sonderschicht_sid();
	$von = "00:00";
	$bis = "00:00";

	$schicht_mitarbeiter->schreibe_schicht_mitarbeiter($sonder_sid, $_POST['mid'], $_POST['termin'], $von, $bis);*/
	echo "Noch passiert hier nichts, check back soon(TM)";
	
}


//FORM: Zeiten wurden updated
if(isset($_POST['timeUpdate'])) {

	$von = $_POST['von'];
	$bis = $_POST['bis'];
	$mid = $_POST['mid'];
	$alt_beginn = $_POST['alt_beginn'];
	$alt_ende = $_POST['alt_ende'];

	//Varify input
	if (! isset($_POST['von']) || strlen($_POST['von']) == 0){ $von = $alt_beginn; }
	if (! isset($_POST['bis']) || strlen($_POST['bis']) == 0 ){ $bis = $alt_ende; }
	$caught = 0;
	try {
		$test = new DateTime($von);
		$test = new DateTime($bis);
	} catch (Exception $e) {
		$fehler = "Bitte geben Sie eine g&uuml;ltige Uhrzeit an";
		$caught = 1;
	} 
		
	#Gültige Eingabe
	if ($caught == 0) {
		
		if ( StandardPlanManager::wird_angewendet($termin) == true ) {
				#Bisher wurde der STD-PLAN verwendet, also INSERT VALUES alles neu im sonderplan
				$tid = Tag::tag_an_termin($termin)->tid;
				$bisherige_ma = StandardPlanManager::hole_alle_schichten_durch_tag($tid);

				foreach ($bisherige_ma as $schicht) {
					if ($schicht->mid == $mid) {
						#Hier müssen die Zeiten geupdated werden
						Schicht_Mitarbeiter::schreibe_schicht_mitarbeiter(1, $schicht->mid, $termin, $von, $bis);
					} else {
						#Einfach übernehmen
						Schicht_Mitarbeiter::schreibe_schicht_mitarbeiter(1, $schicht->mid, $termin, $schicht->von, $schicht->bis);
					}
					
				}
				
		} else {
			#Nur ein UPDATE des Sonderplans ist erforderlich
			Schicht_Mitarbeiter::update_zeiten($mid, 1, $termin, $von, $bis);
		}
			
	}
	
	
}




if(isset($_GET['reset'])) {
	#Lösche alles auf dem Sonderplan (TABLE schicht_mitarbeiter), dann wird automatisch auf den standardplan zurückgegriffen
	Schicht_Mitarbeiter::loesche_schicht_mitarbeiter_durch_termin($termin);
}



if(isset($_GET['l'])) {
	#Lösche einen spezifischen MA
	$von = $_GET['von'];
	$bis = $_GET['bis'];
	$mid = $_GET['l'];

	if ( StandardPlanManager::wird_angewendet($termin) == true ) {
			#Bisher wurde der STD-PLAN verwendet, also INSERT VALUES alles neu im sonderplan
			echo "noch nicht";
			#Bisher wurde der STD-PLAN verwendet, also INSERT VALUES alles neu im sonderplan
			$tid = Tag::tag_an_termin($termin)->tid;
			$bisherige_ma = StandardPlanManager::hole_alle_schichten_durch_tag($tid);

			foreach ($bisherige_ma as $schicht) {
				if ($schicht->mid == $mid && $schicht->von == $von && $schicht->bis == $bis) {
					#Aussetzen, also effektiv löschen für den Benutzer
				} else {
					#Einfach übernehmen
					echo $schicht->von;
					echo $von;

					Schicht_Mitarbeiter::schreibe_schicht_mitarbeiter(1, $schicht->mid, $termin, $schicht->von, $schicht->bis);
				}
								
			}
			
	} else {
		#Nur ein DELETE im Sonderplan ist erforderlich
		Schicht_Mitarbeiter::loesche_schicht_mitarbeiter_durch_mid_termin_von_bis($mid, $termin, $von, $bis);
	}
}



?>






<?php

#Dieses Submenu enthält auf der rechten Seite einen Link zum zurückseten auf den Standard plan
echo '<div id="submenu">';
echo     '<a href="index.php?seite=kalender&sub=uebersicht">zur&uuml;ck zum Kalender</a>';
echo    '<span id="right_link"><a href="index.php?seite=kalender&sub=tag&jahr=' . $jahr . '&monat=' . $monat . '&tag=' . $tag . '&reset=1">zur&uuml;cksetzen auf Standard</a></span>';

echo '</div>';
echo '<div id="hauptinhalt">';



#Fehler und Erfolg darstellen, oben im Hauptinhalt
if(isset($erfolg)) {
	echo '<table><tr><td colspan="2" class="erfolg">'.$erfolg.'</td></tr></table>';
}

if(isset($fehler)) {
	echo '<table><tr><td colspan="2" class="fehler">'.$fehler.'</td></tr></table>';
}





echo '<h2> Details am '.$tag.'.'.$monat.'.'.$jahr.'</h2>';

//Fetche alle Mitarbeiter, die an diesem Tag Dienst haben
$schicht_mitarbeiter = new Schicht_Mitarbeiter();
#Zuerst schauen, ob etwas außerplanmäßiges eingetragen ist
$schicht_mitarbeiter_feld = $schicht_mitarbeiter->hole_alle_schicht_mitarbeiter_durch_termin($termin);
#Falls nicht, nimm den Standardplan
if (count($schicht_mitarbeiter_feld) <= 0) {
	$tid = Tag::tag_an_termin($termin)->tid;
	$schicht_mitarbeiter_feld = StandardPlanManager::hole_alle_schichten_durch_tag($tid);
}


#TODO: Zählt auch MA mehrfach, die mehrfach eingesetzt sind
echo '<p>Eingetragene Mitarbeiter: '.count($schicht_mitarbeiter_feld).'</p>';


# Stelle alle MA dieses Tages in einer Tabelle dar
# Hier können auch die Zeiten geändert werden

echo '	<table id="top_left" style="height:100px;">';
echo '	<tr><th colspan="10" style="vertical-align:top">An diesem Tag eingesetzte Mitarbeiter:</th></tr>';
foreach($schicht_mitarbeiter_feld as $ma)
{	
	$ma_manager = new Mitarbeiter();
	$mitarbeiter = $ma_manager->hole_mitarbeiter_durch_id($ma->mid);
		
	#TODO: Was passiert, wenn ein schon eingetragener MA in Urlaub geht?!
		
	$beginn = new DateTime($ma->von);
	$ende = new DateTime($ma->bis);
		
	#Form: Time update
	echo '<form action="index.php?seite=kalender&sub=tag&jahr=' . $jahr . '&monat=' . $monat . '&tag=' . $tag . '" method="post">';


	echo '<tr><td class="tablerow"><input type="checkbox" name = "mid" value="'.$mitarbeiter->mid.'" style="visibility:hidden;" checked />'.$mitarbeiter->name.', '.$mitarbeiter->vname . "</td>";

	#Hidden values: Old beginning and ending
	echo '<input type="hidden" name=alt_beginn value="' .$beginn->format("H:i"). '" >';
	echo '<input type="hidden" name=alt_ende value="' .$ende->format("H:i"). '">';

	#Input: Individual times
	echo '<td class="tablerow"><input name="von" type="text" class="uhrzeit_text" placeholder= ' . $beginn->format("H:i") . '></td>';
	echo '<td class="tablerow"><input name="bis" type="text" class="uhrzeit_text" placeholder= ' . $ende->format("H:i") . '></td>';
	
	#Button: Remove
	if ($_SESSION['mitarbeiter']->recht=='1') {
		echo '<td class="tablerow"> | <a href="index.php?seite=kalender&sub=tag&l='.$ma->mid.'&jahr='.$jahr.'&monat='.$monat.'&tag='.$tag.'&von='.$beginn->format("H:i"). '&bis='. $ende->format("H:i") . '">entfernen</a></td>';
	}

	//Hide the button, so pressing enter will submit the form
	echo '<td><input type="submit" name="timeUpdate" value = "" class = "hidden_submit"></td>';
	echo '</tr>';
	echo '</form>';

}
echo '</table><table id="top_right">'; 




//Admins können MA auf der rechten Seite hinzufügen
if($_SESSION['mitarbeiter']->recht=='1') {
	echo '<form action="index.php?seite=kalender&sub=tag&jahr=' . $jahr . '&monat=' . $monat . '&tag=' . $tag . '" method="post">';
    echo '<tr><th colspan="2">Sonderschicht hinzuf&uuml;gen</th></tr>';
	
    echo '<tr><td><select name="mid">';
      
	//Hole alle MA
	$ma_verwalter = new Mitarbeiter();
	$alle_ma = $ma_verwalter->hole_alle_mitarbeiter();

	foreach($alle_ma as $mitarbeiter) {
		//Berücksichtige Urlaub
		$urlaub = new Urlaub();
		$urlaub_feld = $urlaub->hole_urlaub_durch_mid($mitarbeiter->mid);
		$test = 0;

		#Berechne, in wievielen Schichten der MA diese Woch eschon eingesetzt ist
		$schicht_mitarbeiter = new Schicht_Mitarbeiter();
		#Berechen stundenzahl diese woche
		$stunden = $mitarbeiter->brutto_workload_diese_woche($termin);
			
		#Gehe alle Urlaube des Mitarbeiters durch
		foreach($urlaub_feld as $urlaub_objekt) {
			#Liegt der aktuell berabeitete Teremin mitten in seinem Urlaub, zeige den entsprechenden MA nicht an
			if($termin >= $urlaub_objekt->ab && $termin <= $urlaub_objekt->bis) {
				$test='2';
				echo '<option disabled value=' . $mitarbeiter->mid . '>' . $mitarbeiter->name.', '.$mitarbeiter->vname.' im Urlaub </option>';

			} 
		}


		#Wenn der MA schon eingetragen wurde, kann er nicht zweifach eingetragen werden
		$einteilungen = $schicht_mitarbeiter->hole_smid_durch_sid_termin_mid($sid,$termin,$mitarbeiter->mid);
		if (count($einteilungen) >= 2) {
			echo '<option disabled value="'.$mitarbeiter->mid.'">'.$mitarbeiter->name.', '.$mitarbeiter->vname.'&ensp; '.$stunden.'h in dieser Woche</option>';
		} else 	if($test=='0') {
            echo '<option value="'.$mitarbeiter->mid.'">'.$mitarbeiter->name.', '.$mitarbeiter->vname.'&ensp; '.$stunden.'h in dieser Woche</option>';
		}
              
	}

		
}

?>
<tr>
	<td>
		<input type="hidden" name="termin" value="<?php echo $jahr.'-'.$monat.'-'.$tag; ?>">
		<input class="knopf_erstellen" type="submit" name="neueSonderschicht" value=" ">
	</td>
</tr>

</table>

</div>