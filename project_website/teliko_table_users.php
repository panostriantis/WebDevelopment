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

//CREATE Table users

$sql1="CREATE TABLE users (
email VARCHAR(100) NOT NULL,
password VARCHAR(30) NOT NULL,
privileges CHAR(10) NOT NULL,
APIkey VARCHAR(200) NOT NULL,
requests_stathmoi INT NULL,
requests_mesi_timi INT NULL,
requests_apoluti_timi INT NULL,
PRIMARY KEY (email,APIkey)
)
ENGINE=INNODB
CHARACTER SET utf8 COLLATE utf8_general_ci";


if (mysqli_query($conn, $sql1)) {
    echo "Table USERS created successfully";
} else {
    echo "Error creating table: " . mysqli_error($conn);
}

mysqli_close($conn);

?>