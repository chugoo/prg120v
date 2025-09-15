<?php /* Oppgave 2 */
/*
/* Programmet mottar fra et HTML-skjema et navn og en alder ved POST-metoden
/* Programmet skriver ut en "god dag"-melding med personens navn og alder
*/
$fornavn=$_POST ["fornavn"];
$etternavn=$_POST ["etternavn"];
$alder=$_POST ["alder"]; /* variable gitt verdier fra feltene i HTML-skjemaet */
print ("God dag $fornavn $etternavn, du er $alder &aring;r og like sprek. <br />"); /* resultat skrevet ut */
?>
