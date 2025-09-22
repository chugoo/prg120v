<?php /* endre-studium */
/*
/* Programmet lager et skjema for kunne endre et studium
/* Programmet endrer det valgte studiet
*/
 include("start.html");
?>
<h3>Endre student</h3>
<form method="post" action="" id="endrestudentskjema" name="endrestudentskjema">
 Brukernavn <select name="brukernavn" id="brukernavn"><?php include("dynamiskefunksjoner.php"); listeboksbrukernavn(); ?> required</select> <br/>
 Fornavn (ny verdi)<input type="text" id="fornavn" name="fornavn" required /> <br/>
 Etternavn (ny verdi)<input type="text" id="etternavn" name="etternavn" required /> <br/>
 Klassekode  <select name="klassekode" id="klassekode"><?php include("dynamiskefunksjoner.php"); listeboksklassekode(); ?> required</select> <br/>
 <input type="submit" value="Endre student" name="finnstudentknapp" id="Knapp">
 <input type="reset" value="Nullstill" id="nullstill" name="nullstill" /> <br />
 </form>

 
 
 <?php
 if (isset($_POST ["finnstudentknapp"]))
 {
 $brukernavn=$_POST ["brukernavn"];
 $fornavn=$_POST ["fornavn"];
 $etternavn=$_POST ["etternavn"];
 $klassekode=$_POST ["klassekode"];
 if (!$brukernavn || !$fornavn || !$etternavn || !$klassekode)
 {
 print ("Alle felt m&aring; fylles ut");
 }
 else
 {
 include("dbtilkobling.php"); /* tilkobling til database-serveren utført og valg av database foretatt */
 $sqlSetning="UPDATE student SET fornavn='$fornavn',etternavn='$etternavn',klassekode='$klassekode'  WHERE brukernavn='$brukernavn';";
 mysqli_query($db,$sqlSetning) or die ("ikke mulig &aring; endre data i databasen");
 /* SQL-setning sendt til database-serveren */
 print ("Studenten med brukernavn $brukernavn er n&aring; endret<br />");
 }
}
 include("slutt.html");
?>