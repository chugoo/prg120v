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
 klasse <select name="klassekode" id="klassekode"><?php include("dynamiskefunksjoner.php"); listeboksklassekode(); ?> required </select> <br/>
<input type="submit" value="Slett klasse" name="slettklasseKnapp" id="slettklasseKnapp" />      

</form>

<?php
if (isset($_POST["slettklasseKnapp"])) {
    include("dbtilkobling.php");
    $klassekode = $_POST["klassekode"];
    $sqlSetning = "DELETE FROM klasse WHERE klassekode='$klassekode';";

    try {
        mysqli_query($db, $sqlSetning);
        print("Følgende klasse er nå slettet: $klassekode <br />");
    } catch (mysqli_sql_exception $e) {
        print("Du må slette studentene i klassen $klassekode før du kan slette klassen.<br />");
    }
}
include("slutt.html");
?>
