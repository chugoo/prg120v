<?php /* endre-studium */
/*
/* Programmet lager et skjema for kunne endre et studium
/* Programmet endrer det valgte studiet
*/
 include("start.html");
?>
<h3>Endre klasse</h3>
<form method="post" action="" id="endreklasseskjema" name="endreklasseskjema">
 Klassekode <select name="klassekode" id="klassekode"><?php include("dynamiskefunksjoner.php"); listeboksklassekode(); ?> required</select> <br/>
 Klassenavn (ny verdi)<input type="text" id="klassenavn" name="klassenavn" required /> <br/>
 <input type="submit" value="Endre klasse" name="finnklasseknapp" id="Knapp">
 <input type="reset" value="Nullstill" id="nullstill" name="nullstill" /> <br />
 </form>

 
 
 <?php
 if (isset($_POST ["finnklasseknapp"]))
 {
 $klassekode=$_POST ["klassekode"];
 $klassenavn=$_POST ["klassenavn"];
 if (!$klassekode || !$klassenavn)
 {
 print ("Alle felt m&aring; fylles ut");
 }
 else
 {
 include("dbtilkobling.php"); /* tilkobling til database-serveren utført og valg av database foretatt */
 $sqlSetning="UPDATE klasse SET klassenavn='$klassenavn' WHERE klassekode='$klassekode';";
 mysqli_query($db,$sqlSetning) or die ("ikke mulig &aring; endre data i databasen");
 /* SQL-setning sendt til database-serveren */
 print ("Klassen med klassekode $klassekode er n&aring; endret<br />");
 }
}
 include("slutt.html");
?>