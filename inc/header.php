
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
		WERDE ICH ÜBERHAUPT VERWENDET
		<div id="seite">
			<div id="kopf">
                           
				<div id="angemeldet">
<?php
					/* Variable $recht auf 1 setzen ,wenn Mitarbeiter Administratorrechte hat, ansonsten auf 0 setzen.
					 */
					if($_SESSION['mitarbeiter']->recht=='1')
					{
						$recht = 'Admin';
					}
					else
					{
						$recht = 'Mitarbeiter';
					}
            		echo $_SESSION['mitarbeiter']->name.' '.$_SESSION['mitarbeiter']->vname.' ('.$recht.') &nbsp;&nbsp;|&nbsp;&nbsp; ';
            		echo '<a href="abmelden.php">abmelden</a>';
                        
                      
?>
            	</div>
            	<a href="index.php" id="logo"></a>
            
	            <div id="menu">
		        	<a href="index.php?seite=mitarbeiter" id="menu_mitarbeiter" class="menu_objekt <?php if($_GET['seite']=='mitarbeiter') echo 'mitarbeiter_active'; ?>"><div class="menu_text" id="menu_text_mitarbeiter"> </div></a>
<?php
/* Nur anzeigen, wenn Mitarbeiter Administratorrechte hat
 */
if($_SESSION['mitarbeiter']->recht=='1')
{
?>
		            
		            <a href="index.php?seite=kalender" id="menu_kalender" class="menu_objekt <?php if($_GET['seite']=='kalender') echo 'kalender_active'; ?>"><div class="menu_text" id="menu_text_kalender"> </div></a>
<?php
}
?>
	           	 	<a href="index.php?seite=dienstplan" id="menu_dienstplan" class="menu_objekt <?php if($_GET['seite']=='dienstplan') echo 'dienstplan_active'; ?>"><div class="menu_text" id="menu_text_dienstplan"> </div></a>
                            <a href="index.php?seite=konfig" id="menu_schicht" class="menu_objekt <?php if($_GET['seite']=='konfig') echo 'konfig_active'; ?>"><div class="menu_text" id="menu_text_schicht"> </div></a>
	           	 </div>

                </div>
        	<div id="hauptbereich">
	           	 <div id="inhalt">
