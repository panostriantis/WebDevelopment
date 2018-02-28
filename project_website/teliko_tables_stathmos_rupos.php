<?php

header('Content-Type:text/html; charset=utf8');

$servername = "localhost";
$username = "root";
$password = "";
$dbname="teliko_db";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}


//CREATE Tables
$sql = "CREATE TABLE Stathmos (
name VARCHAR(20) NOT NULL,
id VARCHAR(6) NOT NULL,
location VARCHAR(30) NOT NULL,
PRIMARY KEY(id)
)
ENGINE=INNODB
CHARACTER SET utf8 COLLATE utf8_general_ci";

$sql1="CREATE TABLE Rupos(
year INT(5) NOT NULL,
type VARCHAR(6) NOT NULL,
stathmos_id VARCHAR(5) NOT NULL,
data VARCHAR(25) NOT NULL
)
ENGINE=INNODB
CHARACTER SET utf8 COLLATE utf8_general_ci";


if (mysqli_query($conn, $sql)) {
    echo "Table Stathmos created successfully";
} else {
    echo "Error creating table: " . mysqli_error($conn);
}

if (mysqli_query($conn, $sql1)) {
    echo "Table Rupos created successfully";
} else {
    echo "Error creating table: " . mysqli_error($conn);
}
mysqli_close($conn);
?>