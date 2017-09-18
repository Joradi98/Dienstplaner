<?php
/* Klassen Schicht und Urlaub einbinden */
include('klassen/schicht.klasse.php');
include('klassen/urlaub.klasse.php');
include('klassen/kalender.klasse.php');

date_default_timezone_set('UTC');


//Parse inputs
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


//Neue SonderSchicht wurde angelegt
if(isset($_POST['neueSonderschicht']))
{
	$schicht_verwaltung = new Schicht();
	$schicht_mitarbeiter = new Schicht_Mitarbeiter();

	$new_mid = $_POST['neueSonderschicht'];
	
	//TODO: lenny face
	$sonder_sid = $schicht_verwaltung->sonderschicht_sid();
	$von = "00:00";
	$bis = "00:00";

	
	$schicht_mitarbeiter->schreibe_schicht_mitarbeiter($sonder_sid, $_POST['mid'], $_POST['termin'], $von, $bis);

}




?>








<div id="submenu">
     <a href="index.php?seite=kalender&sub=uebersicht">zur&uuml;ck zum Kalender</a>
</div>
<div id="hauptinhalt">


<?php

echo '<h2> Details am '.$tag.'.'.$monat.'.'.$jahr.'</h2>';

//Fetche alle Mitarbeiter, die an diesem Tag Dienst haben

$schicht_mitarbeiter = new Schicht_Mitarbeiter();
$schicht_mitarbeiter_feld = $schicht_mitarbeiter->hole_alle_schicht_mitarbeiter_durch_termin($termin);


#TODO: Zählt auch MA mehrfach, die mehrfach eingesetzt sind
echo '<p>Eingetragene Mitarbeiter: '.count($schicht_mitarbeiter_feld).'</p>';


//Stelle alle MA dieses Tages in einer Tabelle dar
echo '	<table id="top_left" style="height:100px;">';
echo '	<tr><th colspan="2" style="vertical-align:top">An diesem Tag eingesetzte Mitarbeiter:</th></tr>';
foreach($schicht_mitarbeiter_feld as $sma)
{	
	$ma_manager = new Mitarbeiter();
	$mitarbeiter = $ma_manager->hole_mitarbeiter_durch_id($sma->mid);
	print $ma->mid;
		
	#TODO: Was passiert, wenn ein schon eingetragener MA in Urlaub geht?!
		
	#$urlaub = new Urlaub();
	#$urlaub_feld = $urlaub->hole_urlaub_durch_mid($schicht_mitarbeiter->mid);
    $schicht_mitarbeiter_smid = $sma->hole_smid_durch_sid_termin_mid($sid, $termin, $mitarbeiter->mid);
	
	$beginn = new DateTime($sma->von);
	$ende = new DateTime($sma->bis);
		

	echo '<tr><td class="tablerow"><input type="checkbox" name = "mid" value="'.$mitarbeiter->mid.'" style="visibility:hidden;" checked />'.$mitarbeiter->name.', '.$mitarbeiter->vname . "</td>";

	echo '<td class="tablerow">' . $beginn->format("H:i") . '</td>';
	echo '<td class="tablerow">' . $ende->format("H:i") . '</td>';
    echo '</tr>';
}
echo '</table><table id="top_right">'; 




//Admins können MA auf der rechten Seite hinzufügen
if($_SESSION['mitarbeiter']->recht=='1') {
	echo '<form action="index.php?seite=kalender&sub=tag" method="post">';
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
		$stunden = $schicht_mitarbeiter->stunden_diese_woche($mitarbeiter->mid, $termin);
			
			
		#Gehe alle Urlaube des Mitarbeiters durch
		foreach($urlaub_feld as $urlaub_objekt) {
			print "huhu";
			#Liegt der aktuell berabeitete Teremin mitten in seinem Urlaub, zeige den entsprechenden MA nicht an
			if($termin >= $urlaub_objekt->ab && $termin <= $urlaub_objekt->bis) {
				$test='2';
				echo '<option disabled value=' . $mitarbeiter->mid . '>' . $mitarbeiter->name.', '.$mitarbeiter->vname.' im Urlaub </option>';

			} 
		}


		#Wenn der MA schon eingetragen wurde, kann er nicht zweifach eingetragen werden
		$einteilungen = $schicht_mitarbeiter->hole_smid_durch_sid_termin_mid($sid,$termin,$mitarbeiter->mid);
		if (count($einteilungen) >= 2) {
			echo '<option disabled value="'.$mitarbeiter->mid.'">'.$mitarbeiter->name.', '.$mitarbeiter->vname.'&ensp; '.$stunden->format("%H:%I").'h in dieser Woche</option>';
		} else 	if($test=='0') {
            echo '<option value="'.$mitarbeiter->mid.'">'.$mitarbeiter->name.', '.$mitarbeiter->vname.'&ensp; '.$stunden->format("%H:%I").'h in dieser Woche</option>';
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