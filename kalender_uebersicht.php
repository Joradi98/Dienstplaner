<?php
include_once('klassen/kalender.klasse.php');
include_once('klassen/schicht.klasse.php');
include_once('klassen/StandardPlanManager.klasse.php');

date_default_timezone_set('UTC');

$kalender = new Kalender();
if(isset($_GET['monat'])&&isset($_GET['jahr']))
{
	$kalender->setze_termin($_GET['monat'], $_GET['jahr']);
	$termin = $_GET['jahr'].'-'.$_GET['monat'].'-'.date('t', mktime(0,0,0,$_GET['monat'],1,$_GET['jahr']));
}
else
{
	$kalender->setze_termin((int)date('m', time()), date('Y', time()));
	$termin = date('Y', time()).'-'.date('m', time()).'-'.date('t', mktime(0,0,0,date('m', time()),1,date('Y', time())));
}
$kalender_feld = $kalender->hole_kalender();
$vor = $kalender->hole_vor_monat();
$nach = $kalender->hole_nach_monat();
$schicht_mitarbeiter_kalender = new Schicht();
$schicht_mitarbeiter_kalender_feld = $schicht_mitarbeiter_kalender->hole_alle_schichten_fuer_kalender();
?>

<div id="submenu">
    <a id="pfeil_links" href="index.php?seite=kalender&sub=uebersicht&monat=<?php echo $vor[0]; ?>&jahr=<?php echo $vor[1]; ?>"></a>
    <span id="kalenderueberschrift"><?php echo $kalender->monats_name[$kalender->monat].' '.$kalender->jahr; ?></span>
    <a id="pfeil_rechts" href="index.php?seite=kalender&sub=uebersicht&monat=<?php echo $nach[0]; ?>&jahr=<?php echo $nach[1]; ?>"></a>

</div>
<div id="hauptinhalt_kal">
<table>
			
			<tr>
				<th class="kalenderhead">Montag</th>
				<th class="kalenderhead">Dienstag</th>
				<th class="kalenderhead">Mittwoch</th>
				<th class="kalenderhead">Donnerstag</th>
				<th class="kalenderhead">Freitag</th>
				<th class="kalenderhead">Samstag</th>
				<th class="kalenderhead_7">Sonntag</th>
			</tr>
<?php
$erster_counter = 0;
$col=0;
$row=1;
foreach($kalender_feld as $woche)
{
    
    
    switch($row){
        case 1: case 2: case 3: case 4: case 5: $row_class = ''; break;
        case 6: $row_class = 'row_6'; break;   
    }
	echo '<tr>';
	foreach($woche as $tag_id => $tag) {
		
		$col++;
		
		switch($col){
			case 1: case 2: case 3: case 4: case 5: case 6: $col_class = ''; break;
			case 7: $col_class = 'col_7'; break;   
		}
		
		#Wenn wir uns am ersten des Monats befinden
		if($tag == 01) {
			$erster_counter++;
		}
		
		if($erster_counter !=1 ) {
			//Für die Überhanänge aus anderen Monaten
			
			
			echo '<td class="kalenderfeld" style="color:#acacac;"><span>' .$tag. '</span></td>';
			

		} else {
			#Für den aktuellen Monat
			$kalender_termin = $kalender->jahr.'-'.$kalender->monat.'-'.$tag;
			
			#Zeige geburtstag an
			if ( Mitarbeiter::jemand_hat_geburtstag($kalender_termin) ) {
				$span_class = "birthdayspan";
			} else {
				$span_class = "";
			}
			echo '<td class="kalenderfeld" style="color:#150e7e;"><span class="'.$span_class.'"><a href="index.php?seite=kalender&sub=tag&jahr='.$kalender->jahr.'&monat='.$kalender->monat.'&tag='.$tag.'" style="color:#150e7e;">'.$tag.'</a>'.'</span>';
			
			
			#Zeige an, wer im Urlaub ist
			$alle_ma = Mitarbeiter::hole_alle_mitarbeiter();
			foreach($alle_ma as $mitarbeiter) {
				if  ($mitarbeiter->ist_im_Urlaub($kalender_termin) ) {
					echo '<br><div id="urlaub_text">Urlaub: '.$mitarbeiter->vname;
				}
				
			}
			
			
			if ( StandardPlanManager::wird_angewendet($kalender_termin) ) {
				
			} else {
				echo '<br><div id="sonder_plan_text">Sonderplan</div>';
			}
			
			#funktioniert_problemlos berücksichtigt nicht nur den standardplan
			if ( StandardPlanManager::funktioniert_problemlos($kalender_termin) ) {
				#echo '<br><div id="nach_plan_text">Nach Plan</div>';
			} else {
				echo '<br><div id="achtung_text">Achtung</div>';
			}	
			
			
			echo '</td>';
		}
		
		 
		
		
		
		
		if($col==7){
			$col=0;
			$row++;
		}
		
	}
	echo '</tr>';
}
?>
		</table>
</div>


<div id="monats_stats">
<?php
$verwaltung = new Mitarbeiter();
$alle = $verwaltung->hole_alle_mitarbeiter();
?>
<table class="stunden_tabelle"> 
	<tr>
    	<th>Name</th>
    	<th>Netto<br>Stunden</th> 
    	<th>Verbleibende<br>&Uuml;berstunden</th> 
    	<th>Resturlaub</th> 

    </tr>
  
	<?php
		foreach ($alle as $mitarbeiter) {
			echo "<tr> <td>" . $mitarbeiter->name . ", " . $mitarbeiter->vname . "</td>";
			$stunden = $mitarbeiter->netto_workload_diesen_monat($termin);
			echo '<td>' . $stunden. " </td> ";
			
			$ueberstunden = $mitarbeiter->ueberstunden_diesen_monat($termin);
			echo '<td>' . $ueberstunden. " </td> ";
			
			$resturlaub = $mitarbeiter->resturlaub();
			echo '<td>' . $resturlaub. " Tage </td> ";

			
			echo "</tr>";
			
		}
		
	?>
 
</table>
</div>
