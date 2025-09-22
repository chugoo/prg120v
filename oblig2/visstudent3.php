<?php
include("start.html");
include("dbtilkobling.php");

$sql = "SELECT id, klassekode, klassenavn FROM klasse";
$result = mysqli_query($db, $sql) or die("Ikke mulig &aring; hente data fra databasen");

if (mysqli_num_rows($result) > 0) {
    echo "<h3>Registrerte klasser</h3>";
    echo "<table border=1>";
    echo "<tr><th>id</th><th>klassekode</th><th>klassenavn</th></tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr><td>{$row['id']}</td><td>{$row['klassekode']}</td><td>{$row['klassenavn']}</td></tr>";
    }
    echo "</table>";
} else {
    echo "0 results";
}

include("slutt.html");
?>