<?php /* dynamiske-funksjoner */
function listeboksklassekode()
{
 include("dbtilkobling.php"); /* tilkobling til database-server og valg av database utført */

 $sqlSetning="SELECT * FROM klasse ORDER BY klassekode;";
 $sqlResultat=mysqli_query($db,$sqlSetning) or die ("ikke mulig &aring; hente data fra databasen");

 $antallRader=mysqli_num_rows($sqlResultat); /* antall rader i resultatet beregnet */
 for ($r=1;$r<=$antallRader;$r++)
 {
 $rad=mysqli_fetch_array($sqlResultat); /* ny rad hentet fra spørringsresultatet */
 $klassekode=$rad["klassekode"];
 $klassenavn=$rad["klassenavn"];
 print("<option value='$klassekode'>$klassekode $klassenavn</option>"); /* ny verdi i listeboksen
laget */
 }
}


function listeboksbrukernavn()
{
 include("dbtilkobling.php"); /* tilkobling til database-server og valg av database utført */

 $sqlSetning="SELECT * FROM student ORDER BY brukernavn;";
 $sqlResultat=mysqli_query($db,$sqlSetning) or die ("ikke mulig &aring; hente data fra databasen");

 $antallRader=mysqli_num_rows($sqlResultat); /* antall rader i resultatet beregnet */
 for ($r=1;$r<=$antallRader;$r++)
 {
 $rad=mysqli_fetch_array($sqlResultat); /* ny rad hentet fra spørringsresultatet */
 $brukernavn=$rad["brukernavn"];
 $fornavn=$rad["fornavn"];
 $etternavn=$rad["etternavn"];
 $klassekode=$rad["klassekode"];
 print("<option value='$brukernavn'>$brukernavn $fornavn $etternavn $klassekode </option>"); /* ny verdi i listeboksen laget */
 }
}
?>

