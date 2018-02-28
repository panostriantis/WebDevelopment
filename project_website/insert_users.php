<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "teliko_db";
$key1 = md5("triantip@ceid.upatras.gr"."a1b2c3");
$key2 = md5("misiakouli@ceid.upatras.gr"."a1b2c3");
$key3 = md5("papaki1@ceid.upatras.gr"."a1b2c3");
$key4 = md5("papaki2@ceid.upatras.gr"."a1b2c3");
$key5 = md5("papaki3@ceid.upatras.gr"."a1b2c3");
$key6 = md5("papaki4@ceid.upatras.gr"."a1b2c3");
$key7 = md5("papaki5@ceid.upatras.gr"."a1b2c3");
$key8 = md5("papaki6@ceid.upatras.gr"."a1b2c3");
$key9 = md5("papaki7@ceid.upatras.gr"."a1b2c3");
$key10 = md5("papaki8@ceid.upatras.gr"."a1b2c3");
$key11 = md5("papaki9@ceid.upatras.gr"."a1b2c3");
$key12 = md5("papaki10@ceid.upatras.gr"."a1b2c3");
$key13 = md5("papaki11@ceid.upatras.gr"."a1b2c3");
$key14 = md5("papaki12@ceid.upatras.gr"."a1b2c3");

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
mysqli_set_charset($conn,"utf8");

$sql = "INSERT INTO users (email, password, privileges, APIkey, requests_stathmoi, requests_mesi_timi, requests_apoluti_timi)
VALUES ('triantip@ceid.upatras.gr','triantis','admin','$key1','0','0','0'),
('misiakouli@ceid.upatras.gr','misiakoulis','admin','$key2','0','0','0'),
('papaki1@ceid.upatras.gr','123','user','$key3','10','10','10'),
('papaki2@ceid.upatras.gr','123','user','$key4','20','10','20'),
('papaki3@ceid.upatras.gr','123','user','$key5','5','50','10'),
('papaki4@ceid.upatras.gr','123','user','$key6','7','30','10'),
('papaki5@ceid.upatras.gr','123','user','$key7','8','30','20'),
('papaki6@ceid.upatras.gr','123','user','$key8','1','10','30'),
('papaki7@ceid.upatras.gr','123','user','$key9','2','30','10'),
('papaki8@ceid.upatras.gr','123','user','$key10','3','40','10'),
('papaki9@ceid.upatras.gr','123','user','$key11','4','30','10'),
('papaki10@ceid.upatras.gr','123','user','$key12','5','20','50'),
('papaki11@ceid.upatras.gr','123','user','$key13','6','10','10'),
('papaki12@ceid.upatras.gr','123','user','$key14','7','30','10')";

if (mysqli_query($conn, $sql)) {
    echo "Data successfully inserted";
} else {
    echo "Error: " . mysqli_error($conn);
}

mysqli_close($conn);
?>