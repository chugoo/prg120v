<?php

include("start.html");
?> 

<h3>Registrer Klasse</h3>

<form method="post" action="" id="registrerklasseskjema" name="registrerklasseskjema">

Klassekode<input type="text" id="klassekode" name="klassekode" required/> 
<br/>
Klassenavn<input type="text" id="klassenavn" name="klassenavn" required/>
<br/>
studiumkode<input type="text" id="studiumkode" name="studiumkode" required/>
<br/>
<input type="submit" value="Registrer klasse" id="registrerklasseknapp" name="registrerklasseknapp"/> 
<input type="reset" value="Nullstill" id="nullstill" name="nullstill" /> 
<br/>
</form>
<?php

if (isset($_POST["registrerklasseknapp"])) 
{
    $klassekode = $_POST["klassekode"];
    $klasssenavn = $_POST["klasssenavn"];
    $studiumkode = $_POST["studiumkode"];

    if (!$klassekode || !$klasssenavn || !$studiumkode) 
	{
        print("Alle felt må fylles ut");
    }
	else 
	{
		include("validering.php");
		$lovligklassekode=validerklassekode($klassekode);
	if (!$lovligklassekode)
	{
		print("Klassekoden er ikke korrekt utfylt<br/>");
	}
	else
		{	
    include("dbtilkobling.php");

        /* tilkobling til database-serveren utført og valg av database foretatt */

        $sqlSetning = "SELECT * FROM klasse WHERE klassekode='$klassekode';";
        $sqlResultat = mysqli_query($db,$sqlSetning) or die("ikke mulig &aring; hente data fra databasen");
        $antallRader = mysqli_num_rows($sqlResultat);
		
        if ($antallRader != 0) /* Denne klassen er registrert fra før */ 
		{
            print("Klassen er registrert fra f&oslashr");
		} 
		else 
		{
            $sqlSetning = "INSERT INTO klasse (klassekode, klasssenavn, studiumkode)
             VALUES('$klassekode','$klasssenavn','$studiumkode');";
            mysqli_query($db,$sqlSetning) or die("ikke mulig &aring; registrere data i databasen");
            /* SQL-setning sendt til database-serveren */

            print("F&oslash;lgende klasse er n&aring; registrert: $klassekode $klasssenavn $studiumkode");
			}
		}
    }
}
include("slutt.html");
?>