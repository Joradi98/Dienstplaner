<div id="hauptinhalt">
<?php
$wochentage = array("Montag", "Dienstag", "Mittwoch", "Donnerstag", "Freitag", "Samstag", "Sonntag");

/* alle gespeicherten Schichten und Tage holen */
$sql_s = ("SELECT * FROM schicht");
$query = mysql_query($sql_s);
$sql_t = ("SELECT * FROM tag");
$schicht_ma_sql = mysql_query($sql_t);

if($_GET['s']=='speichern')
{
	/* leeren der Tabellen schicht_ma und tag */

	$tag = $_POST["tag"];
	$tid = $_POST["TID"];
    for($i=1; $i<=7; $i++)
	{
		if(isset($tag[$i]) && $tag[$i]!= "")
        {
        		/* speichern der Angaben */

			while($schichten = mysql_fetch_assoc($query))
			{
				$sid = $schichten["sid"];
				//Verstecke die Sonderschicht
				if ($sid == Schicht::$sonderschicht_sid) {
					continue;
				}
		
				$id = "MA_".$i."_".$sid;
	           	$anzahl = $_POST[$id];
				$update_query = 'UPDATE schicht_ma SET ma='. $anzahl . ' WHERE tid=' . $tid[$i] . ' AND sid=' . $sid . ';';
				$speichern = mysql_query($update_query);
            }
            mysql_data_seek($query, 0); //array zurücksetzen
        }
	}
        
        $erfolg='Die Daten wurden aktualisiert.';
	echo '<table><tr><td colspan="2" class="erfolg">'.$erfolg.'</td></tr></table>';

}


$sql_t = ("SELECT * FROM tag");
$schicht_ma_sql = mysql_query($sql_t);


if($_GET['s']!=2)
{
	while($schicht_ma = mysql_fetch_assoc($schicht_ma_sql))
	{
		$tag[] = $schicht_ma['name'];
	}
?>
<form action="index.php?seite=konfig&sub=arbeitstage&s=2" method="post">
<table>
	<tr>
		<td colspan=2><h2>Arbeitstage</h2></td>
	</tr>
               
                
<?php
	$i=1;
     /* alle Wochetage auflisten und bereits gespeicherte ausw�hlen */
	foreach($wochentage as $wtag)
	{
		echo '<tr><td><input type="checkbox" name="tage['.$i.']" value="'.$wtag.'"';

		if(isset($tag))
		{
			if(in_array($wtag, $tag))
			{
				echo "checked";
			}
		}
		echo ' > '.$wtag.'</td></tr>';
	    $i++;
	}
?>
               
                <tr>
                    
                    <td>
                    	<input class="knopf_weiter" type="submit" value=" ">
                    </td>
                </tr>
            </table>
        </form>
<?php
}

/* nach Auswahl der Wochentage, "weiter" */
if($_GET['s']==2) {
?>
		<form action="index.php?seite=konfig&sub=arbeitstage&s=speichern" method="post">
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
                
                
                <tr>
<?php
        
while($schicht_ma = mysql_fetch_assoc($schicht_ma_sql))
{
	$tage[$schicht_ma['tid']] = $schicht_ma['name'];               
}
       
for($i=1; $i<=7; $i++) {
	$j=1;
	$col_7="";
	$tage = $_POST['tage'];
        
	if($i==7)$col_7="col_7";
	echo "<td class='kalenderfeld ".$col_7."'>";
	/* für jeden ausgew�hlten Wochentag alle gespeicherten Schichten auflisten */
	if(isset($tage[$i]) && $tage[$i]!= "") {
		echo "<input type='hidden' name='TID[".$i."]' value='".$i."' >";
		echo "<input type='hidden' name='tag[".$i."]' value='".$tage[$i]."' >";
         
		while($schichten = mysql_fetch_assoc($query)) {
          
			$schicht = $schichten["sid"];
			
			//Verstecke die Sonderschicht
			if ($schicht == Schicht::$sonderschicht_sid) {
				continue;
			}
			
			
			$sql_ma = ("select * from schicht_ma WHERE sid = ".$schicht." AND tid = ".$i);
			$anzahl_ma_sql = mysql_query($sql_ma);
			$anzahl_ma = mysql_fetch_assoc($anzahl_ma_sql);
                
			echo $schichten['bez'].": <br/>";
			echo "<input class='feld' type='Text' size='10' name='MA_".$i."_".$schicht."' ";
			/* bereits gespeicherte Mitarbeiteranzahl angeben */
			if(isset($anzahl_ma)) {
				echo "value='".$anzahl_ma['ma']."'";
			}
			echo "><br/><br/>";
		}
		
		mysql_data_seek($query, 0);
	}
        echo "</td>";
}
?>
                </tr>
                <tr>
                	<td>&nbsp;</td>
                </tr>
                <tr>
                   
                    <td colspan="7">
                        <input class="knopf_speichern" type="submit" value=" ">
                    </td>
                </tr>
            </table>
        </form>
<?php
}
/* nach Best�tigung aller Angaben */

?>
	
    
</div>