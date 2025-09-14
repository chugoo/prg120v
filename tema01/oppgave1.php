<?php
/* Oppgave 1 */
/*
/* Programmet mottar fra et HTML-skjema et fornavn og et etternavn ved POST-metoden
/* Programmet skriver ut en "god dag"-melding med personens navn
*/
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fornavn = $_POST["fornavn"];
    $etternavn = $_POST["etternavn"];
    echo "God dag $fornavn $etternavn <br />";
}
?>
