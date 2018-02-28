<?php

header('Content-type: text/html; charset=utf-8');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "teliko_db";

$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
mysqli_set_charset($conn,"utf8");
for ($i=0; $i<=30; $i++){
$eth = 1984+$i;

$sel = "SELECT data FROM rupos WHERE year='$eth'";
$arxeio = mysqli_query($conn,$sel);

foreach ($arxeio as $rows) {
	$thesi = $rows["data"];
	$topo="C:\\wamp\\www\\panos\\mydatabase\\$thesi";
	
	if(file_exists($topo)){
	
	
	
	$sql = "CREATE TABLE `$thesi` (
	date VARCHAR(15) NULL,
	T01 VARCHAR(25) NULL,
	T02 VARCHAR(25) NULL,
	T03 VARCHAR(25) NULL,
	T04 VARCHAR(25) NULL,
	T05 VARCHAR(25) NULL,
	T06 VARCHAR(25) NULL,
	T07 VARCHAR(25) NULL,
	T08 VARCHAR(25) NULL,
	T09 VARCHAR(25) NULL,
	T10 VARCHAR(25) NULL,
	T11 VARCHAR(25) NULL,
	T12 VARCHAR(25) NULL,
	T13 VARCHAR(25) NULL,
	T14 VARCHAR(25) NULL,
	T15 VARCHAR(25) NULL,
	T16 VARCHAR(25) NULL,
	T17 VARCHAR(25) NULL,
	T18 VARCHAR(25) NULL,
	T19 VARCHAR(25) NULL,
	T20 VARCHAR(25) NULL,
	T21 VARCHAR(25) NULL,
	T22 VARCHAR(25) NULL,
	T23 VARCHAR(25) NULL,
	T24 VARCHAR(25) NULL)
	ENGINE=INNODB
	CHARACTER SET utf8 COLLATE utf8_general_ci";
	
	
	
	mysqli_query($conn,$sql) or die(mysqli_error($conn));
	$handle = fopen($topo, "r");
	while (($data = fgetcsv($handle)) !== FALSE) {
		$fields = count($data)-1;
		$data = array_replace($data,array_fill_keys(array_keys($data, '-9999'),''));
		$f = $data[0];
		$f = date("d-m-Y", strtotime($f));
		$import="INSERT into `$thesi`(date,T01,T02,T03,T04,T05,T06,T07,T08,T09,T10,T11,T12,T13,T14,T15,T16,T17,T18,T19,T20,T21,T22,T23,T24) VALUES('$f'";
		for($cnt=1; $cnt<=24; $cnt++){
			if($cnt<=$fields){
				$import.= "," . "'" . $data[$cnt] . "'";
			} else{
				$import.= ","."'"."'";
			}
		}
		$import .= ")";
		mysqli_query($conn,$import) or die(mysqli_error($conn));
	}
	fclose($handle);
}
}

};

echo ('telos');


mysqli_close($conn);

?>