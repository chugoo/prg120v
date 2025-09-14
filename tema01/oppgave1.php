<?php /* Oppgave 1 */
/*
/* Programmet mottar fra et HTML-skjema et fornavn og et etternavn ved POST-metoden
/* Programmet skriver ut en "god dag"-melding med personens navn
*/
$fornavn=$_POST ["fornavn"];
$etternavn=$_POST ["etternavn"]; /* variable gitt verdier fra feltene i HTML-skjemaet */
print ("God dag $fornavn $etternavn <br />"); /* resultat skrevet ut */
?>
