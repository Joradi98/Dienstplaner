<?php
/* Klassen Schicht und Urlaub einbinden */
include('klassen/schicht.klasse.php');
include('klassen/urlaub.klasse.php');
include('klassen/kalender.klasse.php');

date_default_timezone_set('UTC');

if(isset($_GET['sid']) && isset($_GET['tag']) && isset($_GET['monat']) && isset($_GET['jahr']))
{
	$sid = $_GET['sid'];
	$tag = $_GET['tag'];
	$monat = $_GET['monat'];
	$jahr = $_GET['jahr'];
	$termin = $jahr.'-'.$monat.'-'.$tag;
}
else
{
	$sid = $_POST['sid'];
	$termin = $_POST['termin'];
	$tag = substr($_POST['termin'], 8, 2);
	$monat = substr($_POST['termin'], 5, 2);
	$jahr = substr($_POST['termin'], 0, 4);
}

$schicht_manager = new Schicht();
$aktuelle_schicht = $schicht_manager->hole_schicht_durch_id($sid);


$tid = date('w', mktime(0, 0, 0, $monat, $tag, $jahr));

/* Mitarbeiteranzahl anhand von Tag und Schicht holen */
$schicht_ma = new Schicht_Mitarbeiter();
$ma_anzahl = $schicht_ma->hole_mitarbeiter_anzahl_durch_id($sid, $tid);



//Neuer Mitarbeiter in der Schicht
if(isset($_POST['neuerMA']))
{
	$new_mid = $_POST['NeuerMA'];
	
	
	
	/* pr�fen der maximalen Mitarbeiteranzahl */
	if((count($_POST)-3)>$ma_anzahl['ma'])
	{
		$fehler = 'Wollen sie wirklich so viele Mitarbeiter auswaehlen? So viele werden laut Plan nicht benoetigt.';
	} else {
		$erfolg = 'Mitarbeiter in dieser Schicht wurden aktualisiert!';
	}
	
	
	
	#Wenn der MA schon eingetragen wurde, kann er nicht zweifach eingetragen werden
	$mitarbeiter = Mitarbeiter::hole_mitarbeiter_durch_id($new_mid);
	if ( $mitarbeiter->ist_verfuegbar_zur_zeit($_POST['termin'], $aktuelle_schicht->ab, $aktuelle_schicht->bis) == false) {
		$fehler = 'Achtung: Dieser Mitarbeiter ist schon eingesetzt. ';
		#TODO: SAGEN, WO UND WANN DER MA EINGESETZT IST
	}
	
	
	$schicht_mitarbeiter = new Schicht_Mitarbeiter();
	$schicht_mitarbeiter->schreibe_schicht_mitarbeiter($_POST['sid'], $new_mid, $_POST['termin'], $aktuelle_schicht->ab, $aktuelle_schicht->bis);

}

//Lösche einen Mitarbeiter
if(isset($_GET['l']))
{
    $smid = $_GET['l'];
    $schicht_ma->loesche_schicht_mitarbeiter_durch_smid($smid);
           
}


if(isset($_POST['timeUpdate']))
{
	$von = $_POST['von'];
	$bis = $_POST['bis'];
	$mid = $_POST['mid'];
	$sid = $_POST['sid'];
	
	
	$verwalter = new Schicht_Mitarbeiter;

	$vorher_ma = $verwalter->hole_einen($sid, $mid, $termin);
	$alt_beginn = $vorher_ma->von;
	$alt_ende = $vorher_ma->bis;
	
	//Varify input
	if (! isset($_POST['von']) || strlen($_POST['von']) == 0){
		$von = $alt_beginn;
	}
		
	if (! isset($_POST['bis']) || strlen($_POST['bis']) == 0 ){
		$bis = $alt_ende;
	}
	
	$caught = 0;
	try {
		$test = new DateTime($von);
		$test = new DateTime($bis);

	} catch (Exception $e) {
		$fehler = "Bitte geben Sie eine g&uuml;ltige Uhrzeit an";
		$caught = 1;
	} 
	
	if ($caught == 0) {
		$verwalter->update_zeiten($_POST['mid'], $sid, $termin, $von, $bis);
	}


}


$mitarbeiter = new Mitarbeiter();
$mitarbeiter_feld = array();
$mitarbeiter_feld = $mitarbeiter->hole_alle_mitarbeiter();

$schicht = new Schicht();
$schicht = $schicht->hole_schicht_durch_id($sid);

$schicht_mitarbeiter = new Schicht_Mitarbeiter();
$schicht_mitarbeiter_feld = $schicht_mitarbeiter->hole_alle_schicht_mitarbeiter_durch_sid_termin($sid, $termin);
?>


<div id="submenu">
     <a href="index.php?seite=kalender&sub=uebersicht">zur&uuml;ck zum Kalender</a>
</div>
<div id="hauptinhalt">

<?php
echo '<h2>'.$schicht->bez.' am '.$tag.'.'.$monat.'.'.$jahr.'</h2>';
echo '<p>Ben&ouml;tigte Mitarbeiter: '.$ma_anzahl['ma'].'</p>';


if(isset($erfolg))
{
	echo '<table><tr><td colspan="2" class="erfolg">'.$erfolg.'</td></tr></table>';
}

if(isset($fehler))
{
	echo '<table><tr><td colspan="2" class="fehler">'.$fehler.'</td></tr></table>';
}
        
    echo '	<table id="top_left" style="height:100px;">';
	echo '	<tr><th colspan="2" style="vertical-align:top">Eingeteilte Mitarbeiter</th></tr>';



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
		
		#Form for updating individual times
		echo '<form action="index.php?seite=kalender&sub=detail" method="post">';

		echo '<tr><td class="tablerow"><input type="checkbox" name = "mid" value="'.$mitarbeiter->mid.'" style="visibility:hidden;" checked />'.$mitarbeiter->name.', '.$mitarbeiter->vname . "</td>";

		echo '<td class="tablerow"><input name="von" type="text" class="uhrzeit_text" placeholder= ' . $beginn->format("H:i") . '></td>';
		echo '<td class="tablerow"><input name="bis" type="text" class="uhrzeit_text" placeholder= ' . $ende->format("H:i") . '></td>';
       	if ($_SESSION['mitarbeiter']->recht=='1') {
       		echo '<td class="tablerow"> | <a href="index.php?seite=kalender&sub=detail&l='.$schicht_mitarbeiter_smid['smid'].'&sid='.$sid.'&jahr='.$jahr.'&monat='.$monat.'&tag='.$tag.'">entfernen</a></td></tr>';
       	}
                 
        echo '</tr>';

 	 	//Hide the button, so pressing enter will submit the form
		echo '<td><input type="submit" name="timeUpdate" value = "" class = "hidden_submit"></td>';
		echo "</tr>";

		//Closing the form with necessary information..
		echo '<input type="hidden" name="sid" value=' . $sid . '>';
		echo '<input type="hidden" name="termin" value=' . $jahr . '-' .$monat.'-'.$tag . '>';
		echo '</form>';

                         
	}
	echo "<tr>";


        echo '</table><table id="top_right">';
        if($_SESSION['mitarbeiter']->recht=='1') {
			echo '<form action="index.php?seite=kalender&sub=detail" method="post">';
       		echo '<tr><th colspan="2">Mitarbeiter hinzuf&uuml;gen</th></tr>';
	
       		echo '<tr><td><select name="NeuerMA">';
      
	 		foreach($mitarbeiter_feld as $mitarbeiter) {
				$urlaub = new Urlaub();
				$urlaub_feld = $urlaub->hole_urlaub_durch_mid($mitarbeiter->mid);
				$test = 0;

				#Berechne, in wievielen Schichten der MA diese Woch eschon eingesetzt ist
				$schicht_mitarbeiter = new Schicht_Mitarbeiter();
				#Berechen stundenzahl diese woche
				$stunden = $schicht_mitarbeiter->stunden_diese_woche($mitarbeiter->mid, $termin);
			
				echo "Considerung";

				
				#Berücksichtige den Urlaub
				if ( $mitarbeiter->ist_im_Urlaub($termin) ) {
					$test='2';
					echo '<option disabled value=' . $mitarbeiter->mid . '>' . $mitarbeiter->name.', '.$mitarbeiter->vname.' im Urlaub </option>';
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
		</select></td></tr>
				<tr>
                    <td>
                    	<input type="hidden" name="sid" value="<?php echo $sid; ?>">
                    	<input type="hidden" name="termin" value="<?php echo $jahr.'-'.$monat.'-'.$tag; ?>">
                        <input class="knopf_erstellen" type="submit" name="neuerMA" value=" "></td>
                </tr>
			</table>
		</form>
	</div>
	