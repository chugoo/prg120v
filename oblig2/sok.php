 <?php /* sok */
/*
/* Programmet demonstrerer søk i databasetabeller
*/
 include("start.html");
 
 
?>
<h3>S&oslashk </h3>
<form method="post" action="" id="sokeSkjema" name="sokeSkjema">
 S&oslash;k etter student og klasse <input type="text" id="sokestreng" name="sokestreng" required /> <br/>
 <input type="submit" value="Fortsett" id="sokeKnapp" name="sokeKnapp" />
 <input type="reset" value="Nullstill" id="nullstill" name="nullstill" /> <br />
</form>
<?php


 if (isset($_POST ["sokeKnapp"]))
 {
 $sokestreng=$_POST ["sokestreng"];
 include("dbtilkobling.php"); /* tilkobling til database-serveren utført og valg av database foretatt */
 print ("Treff for s&oslash;kestrengen <strong>$sokestreng</strong> <br /><br />"); 

$sqlSetning="SELECT * FROM klasse WHERE klassekode LIKE '%$sokestreng%' OR klassenavn LIKE
'%$sokestreng%';";
 $sqlResultat=mysqli_query($db,$sqlSetning) or die ("ikke mulig &aring; hente data fra databasen");
 $antallRader=mysqli_num_rows($sqlResultat);
 if ($antallRader==0)
	{
 print ("Treff i klasse-tabellen: <br /> Ingen <br /> <br />");
	}
 else
	{
 print ("Treff i klasse-tabellen: <br />");
 print ("<table border=1");
 print ("<tr><th align=left>klassekode</th> <th align=left>klassenavn</th> </tr>");
 for ($r=1;$r<=$antallRader;$r++)
	{
 $rad=mysqli_fetch_array($sqlResultat); /* ny rad hentet fra spørringsresultatet */
 $klassekode=$rad["klassekode"];
 $klassenavn=$rad["klassenavn"];
 $sokestrenglengde=strlen($sokestreng); /* lengden på sokestrengen */

 print("<tr>");
 $tekst="<td>$klassekode</td> <td>$klassenavn</td>"; /* første tekststreng */
 $startpos=stripos($tekst,$sokestreng); /* første startpos */
 while ($startpos!==false)
 {
 $tekstlengde=strlen($tekst); /* lengden på teksten */
 $hode=substr($tekst,0,$startpos);
 $sok=substr($tekst,$startpos,$sokestrenglengde);
 $hale=substr($tekst,$startpos+$sokestrenglengde,$tekstlengde-$startpos-$sokestrenglengde);
 print("$hode<strong><font color='blue'>$sok</font></strong>"); /* deler av utskriften*/
 $tekst=$hale;/* ny tekst = nåværende hale */
 $startpos=stripos($tekst,$sokestreng); /* ny startpos */
 }
 print("$hale</tr>"); /* utskrift av siste hale */
 }
 print ("</table> </br />");
 }
 
 
 /* søk i student-tabellen*/
$sqlSetning="SELECT * FROM student WHERE brukernavn LIKE '%$sokestreng%' OR fornavn LIKE
'%$sokestreng%' OR etternavn LIKE '%$sokestreng%' OR klassekode LIKE '%$sokestreng%';";
 $sqlResultat=mysqli_query($db,$sqlSetning) or die ("ikke mulig &aring; hente data fra databasen");
 $antallRader=mysqli_num_rows($sqlResultat);
 if ($antallRader==0)
 {
 print ("Treff i student-tabellen: <br /> Ingen <br /> <br />");
 }
 else
 {
 print ("Treff i student-tabellen: <br />");
 print ("<table border=1>");
 print ("<tr><th align=left>brukernavn</th> <th align=left>fornavn</th> <th align=left>etternavn</th> <th align=left>klassekode</th>
</tr>");
 for ($r=1;$r<=$antallRader;$r++)
 {
 $rad=mysqli_fetch_array($sqlResultat); /* ny rad hentet fra spørringsresultatet */
 $brukernavn=$rad["brukernavn"];
 $fornavn=$rad["fornavn"];
 $etternavn=$rad["etternavn"];
 $klassekode=$rad["klassekode"];
 $sokestrenglengde=strlen($sokestreng); /* lengden på sokestrengen */

 print("<tr> <td>");
 $tekst="$brukernavn</td> <td>$fornavn</td> <td>$etternavn</td> <td>$klassekode"; /* første tekststreng */
 $startpos=stripos($tekst,$sokestreng); /* første startpos */
 while ($startpos!==false)
 {
 $tekstlengde=strlen($tekst); /* lengden på teksten */
 $hode=substr($tekst,0,$startpos);
 $sok=substr($tekst,$startpos,$sokestrenglengde);
 $hale=substr($tekst,$startpos+$sokestrenglengde,$tekstlengde-$startpos-$sokestrenglengde);
 print("$hode<strong><font color='blue'>$sok</font></strong>"); /* deler av utskriften*/
 $tekst=$hale;/* ny tekst = nåværende hale */
 $startpos=stripos($tekst,$sokestreng); /* ny startpos */
 }
 print("$hale </td> </tr>"); /* utskrift av siste hale */
 }
 print ("</table> </br />");
 }
 }
 include ("slutt.html");
 ?>