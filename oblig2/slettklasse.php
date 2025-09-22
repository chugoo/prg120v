<?php /* slett-klasse */
/*
/* Programmet lager et skjema for å kunne slette et klasse
/* Programmet sletter det valgte studiet
*/
 include("start.html");
?>

<script src="funksjoner.js"> </script>
<h3>Slett klasse</h3>
<form method="post" action="" id="slettklasseSkjema" name="slettklasseSkjema" onSubmit="return bekreft()">
 klasse<select name="klassekode" id="klassekode"><?php include("dynamiskefunksjoner.php"); listeboksklassekode(); ?> required </select> <br/>
<input type="submit" value="Slett klasse" name="slettklasseKnapp" id="slettklasseKnapp" />      

</form>

<?php
 if (isset($_POST ["slettklasseKnapp"]))
 {
 include("dbtilkobling.php"); /* tilkobling til database-serveren utført og valg av database foretatt */
 $klassekode=$_POST ["klassekode"];
 $sqlSetning="DELETE FROM klasse WHERE klassekode='$klassekode';";
 mysqli_query($db,$sqlSetning) or die ("Du må slette studentene i klassen $klassekode før du kan slette klassen.");
 /* SQL-setning sendt til database-serveren */
 print ("F&oslash;lgende klasse er nå; slettet: $klassekode <br />");
 }
 include("slutt.html");
?>