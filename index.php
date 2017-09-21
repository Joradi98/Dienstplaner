<?php



/* Mitarbeiterklasse includieren, weil sie in der Session die danach gestartet wird ben�tigt wird.
 * Testen ob Mitarbeiterobjekt gesetzt wurde, d.h. Recht hat die Seiten einzusehen, ansonsten auf anmelden.php weiterleiten.
 */
include_once('inc/config.php');
include_once('klassen/mitarbeiter.klasse.php');
session_start();
if($_SESSION['mitarbeiter'])
{




?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
		<title>Dienstplaner</title>
		<link rel="stylesheet" type="text/css" href="css/main_css.css">
          <link rel="stylesheet" type="text/css" href="css/kalender.css">
          <script type="text/javascript" src="js/kalender.js"></script>
	</head>
	<body>

		<div id="seite">
			<div id="kopf">
                             
				<div id="angemeldet">
<?php
/* Variable $recht auf 1 setzen ,wenn Mitarbeiter Administratorrechte hat, ansonsten auf 0 setzen.
					 */	
if($_SESSION['mitarbeiter']->recht=='1') {
	$recht = 'Admin';
} else {
	$recht = 'Mitarbeiter';
}

echo $_SESSION['mitarbeiter']->name.' '.$_SESSION['mitarbeiter']->vname.' ('.$recht.') &nbsp;&nbsp;|&nbsp;&nbsp; ';
echo '<a href="abmelden.php">abmelden</a>';

if(isset($_GET['seite'])) {
	$mainactiv = $_GET['seite'];
} else {
	$mainactiv = 'mitarbeiter';
}
?>

</div>
<a href="index.php" id="logo"></a>

<?php
include_once('inc/hilfe.php');
?>
	<div id="menu">
		<a href="index.php?seite=mitarbeiter" id="menu_mitarbeiter" class="menu_objekt <?php if($mainactiv=='mitarbeiter') echo 'mitarbeiter_active'; ?>"><div class="menu_text" id="menu_text_mitarbeiter"> </div></a>
		<a href="index.php?seite=kalender" id="menu_kalender" class="menu_objekt <?php if($mainactiv=='kalender') echo 'kalender_active'; ?>"><div class="menu_text" id="menu_text_kalender"> </div></a>
		<a href="index.php?seite=dienstplan" id="menu_dienstplan" class="menu_objekt <?php if($mainactiv=='dienstplan') echo 'dienstplan_active'; ?>"><div class="menu_text" id="menu_text_dienstplan"> </div></a>
		<a href="index.php?seite=standard_plan" id="menu_standard_plan" class="menu_objekt <?php if($mainactiv=='standard_plan') echo 'standard_plan_active'; ?>" ><div class="menu_text"  style="padding:19px; margin-left:25px;">Standard Plan</div></a>

<?php
/* Konfigurations-reiter Nur anzeigen, wenn Mitarbeiter Administratorrechte hat
 */
if($_SESSION['mitarbeiter']->recht=='1') {
?>
    <a href="index.php?seite=konfig" id="menu_schicht" class="menu_objekt <?php if($mainactiv=='konfig') echo 'konfig_active'; ?>"><div class="menu_text" id="menu_text_schicht"> </div></a>
<?php
}
?>
</div>

                </div>
        	<div id="hauptbereich">
	           	 <div id="inhalt">

<?php
/* Datenbankverbindung herstellen
 */



/* Wenn Seite in URL �bergeben wurde includieren, ansonsten mitarbeiter.php includieren
 */
if(isset($_GET['seite']))
{
	switch($_GET['seite'])
	{
        case 'mitarbeiter': include_once('mitarbeiter.php'); break;
	    case 'konfig': include_once('konfig.php'); break;
	    case 'kalender': include_once('kalender.php'); break;
        case 'schicht_mitarbeiter': include_once('schicht_mitarbeiter.php'); break;
	    case 'dienstplan': include_once('dienstplan.php'); break;
	    case 'standard_plan': include_once('standard_plan.php'); break;

	    default: break;
	}
} else {
	include_once('mitarbeiter.php');
}

include_once('inc/footer.php');

#Von ganz oben
} else {
	header('Location: anmelden.php');
}

?>