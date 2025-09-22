<?php /* Oppgave0201 */
/*
/* Programmet mottar fra et HTML-skjema et svar på spørsmålet "Hva er 3 ganger 3 ?"
/* Programmet sjekker om svaret er riktig og skriver ut en melding avhengig av om svaret er riktig eller feil
*/
$svar=$_POST ["svar"];
if ($svar == 9) /* avgitt svar er riktig */
{
print("Riktig. 3 ganger 3 er 9 <br/>");
}
else /* avgitt svar er feil */
{
print("Feil. 3 ganger 3 er ikke $svar. 3 ganger 3 er 9 <br/>");
}
?>
