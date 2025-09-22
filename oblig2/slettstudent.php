<?php 
 include("start.html");
?>

<script src="funksjoner.js"> </script>
<h3>Slett student</h3>
<form method="post" action="" id="slettstudentskjema" name="slettstudentskjema" onSubmit="return bekreft()">
 Brukernavn <select name="brukernavn" id="brukernavn"><?php include("dynamiskefunksjoner.php"); listeboksbrukernavn(); ?> required</select> <br/>
<input type="submit" value="Slett klasse" name="slettstudentknapp" id="slettstudentknapp" />
</form>
<?php
 if (isset($_POST ["slettstudentknapp"]))
 {
 include("dbtilkobling.php"); /* tilkobling til database-serveren utført og valg av database foretatt */
 $brukernavn=$_POST ["brukernavn"];
 $sqlSetning="DELETE FROM student WHERE brukernavn='$brukernavn';";
 mysqli_query($db,$sqlSetning) or die ("ikke mulig &aring; slette data i databasen");
 /* SQL-setning sendt til database-serveren */
 print ("F&oslash;lgende student er n&aring; slettet: $brukernavn <br />");
 }
 include("slutt.html");
?>