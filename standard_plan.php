<?php

include('klassen/tag.klasse.php');
include('klassen/status.klasse.php');


#Fetche alle Informatino aus der URL und mache sie publicly accessible
if(isset($_GET['tid'])) {
	$tag = Tag::hole_tag_durch_tid(intval($_GET['tid']));
} else {
	#Monday = 0 will be the default
	$tag = Tag::hole_tag_durch_tid(2);
}
?>





<div id="submenu">
    <a id="pfeil_links" href="index.php?seite=standard_plan&tid=<?php echo $tag->vorheriger()->tid ?>"></a>
    <span id="kalenderueberschrift"><?php echo $tag->name ?></span>
    <a id="pfeil_rechts" href="index.php?seite=standard_plan&tid=<?php echo $tag->naechster()->tid ?>"></a>
</div>


<div id="hauptinhalt">
<?php
echo "Standardplan f&uuml;r ". $tag->name . ":";
?>

</div>