<?php

include ("dbtilkobling.php");
$sql = "SELECT id, klassekode, klassenavn FROM klasse";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "<br> id: ". $row["id"]. " - Name: ". $row["klassekode"]. " " . $row["klassenavn"] . "<br>";
    }
} else {
    echo "0 results";
}

?>