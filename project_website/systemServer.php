<?php

/* +++++++++ LOGIN FUNC ++++++++++++ */

if (isset($_POST["login"]) && $_POST["login"]=="active"){
	$email = $_POST["email"];
	$psw = $_POST["psw"];
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "teliko_db";
	
	// Create connection
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	// Check connection
	if (!$conn) {
		die("Connection failed: " . mysqli_connect_error());
	}
	mysqli_set_charset($conn,"utf8");
	
	
	
	
	$sql = "SELECT password FROM users WHERE email = '$email'";
	
	if ($new = mysqli_query($conn, $sql)) {
	} else {
		die();
	}
	
	if (mysqli_num_rows($new)!=0 ){
		foreach($new as $apanthsh){
			$pass = $apanthsh["password"];
			if($pass == $psw){
				$sql_new = mysqli_query($conn,"SELECT email, password, privileges, APIkey FROM users WHERE email = '$email' AND password = '$psw'") or die();
				foreach($sql_new as $n_pass){
					$obj_send = json_encode($n_pass);
					echo($obj_send);
					exit();
				}
			} else if($pass != $psw){
				$obj_send = array('email' => $email, 'password' => "undefined", 'privileges' => "undefined", 'APIkey' => "undefined");
				echo(json_encode($obj_send));
				exit();
			}
		}
	}else{
		$obj_send = array('email' => "undefined", 'password' => "undefined", 'privileges' => "undefined", 'APIkey' => "undefined");
		echo(json_encode($obj_send));
		exit();
	}
	mysqli_close($conn);
}



/* +++++++++++ SIGNUP FUNC ++++++++++++ */

if(isset($_POST["signup"]) && $_POST["signup"]=="active"){
	
	$email = $_POST["email"];
	$psw = $_POST["psw"];
	$key = md5($email."a1b2c3");
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "teliko_db";
	
	// Create connection
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	// Check connection
	if (!$conn) {
		die("Connection failed: " . mysqli_connect_error());
	}
	mysqli_set_charset($conn,"utf8");
	
	$sql = "INSERT INTO users (email, password, privileges, APIkey, requests_stathmoi, requests_mesi_timi, requests_apoluti_timi)
	VALUES ('$email', '$psw', 'user', '$key', '0', '0', '0')";
	

	if ($new = mysqli_query($conn, $sql)) {
		$success = 1;
	} else {
		$success = 0;
	}
	if($success==1){
		$obj_send = array('signup' => "success");
		echo(json_encode($obj_send));
	}else if($success==0){
		$obj_send = array('signup' => "failed");
		echo(json_encode($obj_send));
	}
	
	
	mysqli_close($conn);
}

/* ++++++++++ QUERY STATISTICS +++++++++ */

if(isset($_POST["statistika"]) && ($_POST["statistika"])=="active" && isset($_POST["APIkey"]) && isset($_POST["privileges"])){
	
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "teliko_db";
	if($_POST["APIkey"]=="" || $_POST["privileges"]==""){
		die("failed");
	}
	// Create connection
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	// Check connection
	if (!$conn) {
		die("Connection failed: " . mysqli_connect_error());
	}
	mysqli_set_charset($conn,"utf8");
	$apky = $_POST["APIkey"];
	$global = mysqli_query($conn,"SELECT privileges FROM users WHERE APIkey = '$apky'") or die();
	foreach ($global as $chk){
		$global_check = $chk["privileges"];
	}
	if($_POST["privileges"]!=$global_check){
		die("failed");
	}
	
	if($global_check=="admin"){
		$sql = "SELECT APIkey, requests_stathmoi, requests_mesi_timi, requests_apoluti_timi FROM users WHERE privileges = 'user'";
		
		if ($new = mysqli_query($conn, $sql)) {
		} else {
			die();
		}
		$order = array();
		$i=-1;
		$synolo_stathmoi = 0; $synolo_mesi_timi = 0; $synolo_apoluti_timi = 0;
		foreach($new as $resp){
			$i = $i+1;
			$synolo_stathmoi += $resp["requests_stathmoi"];
			$synolo_mesi_timi += $resp["requests_mesi_timi"];
			$synolo_apoluti_timi += $resp["requests_apoluti_timi"];
			$total = $resp["requests_stathmoi"] + $resp["requests_mesi_timi"] + $resp["requests_apoluti_timi"];
			$sum = array('synolo' => $total);
			$tel[$resp["APIkey"]] = array_merge($resp, $sum);
			$order[$resp["APIkey"]] = $total;
		}
		array_multisort($order, SORT_DESC);
		$order2 = array_slice($order,0,10);
		$i = $i+1;
		$aithmata = array_sum($order);
		$admin_send = array('arithmos_APIkeys' => $i, 'synolo_stathmoi' => $synolo_stathmoi, 'synolo_mesi_timi' => $synolo_mesi_timi, 'synolo_apoluti_timi' => $synolo_apoluti_timi,'synolika_aithmata' => $aithmata);
		$teliko = array();
		$teliko[0] = $admin_send;
		$c = 0;
		foreach ($order2 as $key => $value){
			$c = $c+1;
			if($key == $tel[$key]["APIkey"]){
				$teliko[$c] = $tel[$key];
			}
		}
		
		print_r(json_encode($teliko));
	}else if($global_check=="user"){
		$key = $_POST["APIkey"];
		$sql = "SELECT APIkey, requests_stathmoi, requests_mesi_timi, requests_apoluti_timi FROM users WHERE APIkey = '$key'";
		if ($new = mysqli_query($conn, $sql)) {
		} else {
			die();
		}
		foreach($new as $resp){
			$total = $resp["requests_stathmoi"] + $resp["requests_mesi_timi"] + $resp["requests_apoluti_timi"];
			$sum = array('synolo' => $total);
			$tel = array_merge($resp, $sum);
		}
		print_r(json_encode($tel));
	}
	mysqli_close($conn);
}

/* ++++++++++ ADMIN ADD STATION +++++++++++ */

if(isset($_POST["addstation"]) && $_POST["addstation"]=="active" && isset($_POST["stathmosName"]) && isset($_POST["stathmosId"]) && isset($_POST["stathmosLocation"]) &&
isset($_POST["APIkey"])){
	
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "teliko_db";
	$onoma_stathmou = $_POST["stathmosName"];
	$id_stathmou = $_POST["stathmosId"];
	$location = $_POST["stathmosLocation"];
	$apikey = $_POST["APIkey"];
	//array to check empty vars
	$exit = array($onoma_stathmou, $id_stathmou, $location, $apikey);
	//check if needed field is empty
	foreach($exit as $leave){
		if($leave==""){
			die("failed");
		}
	}
	
	// Create connection
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	// Check connection
	if (!$conn) {
		die("failed");
	}
	mysqli_set_charset($conn,"utf8");
	
	$q_one = mysqli_query($conn,"SELECT privileges FROM users WHERE APIkey = '$apikey'") or die("failed");
	foreach($q_one as $check){
		$adm = $check["privileges"];
	}
	if($adm =="admin"){
		$q_two = mysqli_query($conn,"INSERT INTO Stathmos (name, id, location) VALUES('$onoma_stathmou','$id_stathmou','$location')") or die("failed");
	}
	
	mysqli_close($conn);
	echo "success";
}

/* ++++++++++ ADMIN REMOVE STATION +++++++++++ */

if(isset($_POST["removestation"]) && $_POST["removestation"]=="active" && isset($_POST["stathmosName"]) && isset($_POST["stathmosId"]) && isset($_POST["stathmosLocation"]) &&
isset($_POST["APIkey"])){
	
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "teliko_db";
	$onoma_stathmou = $_POST["stathmosName"];
	$id_stathmou = $_POST["stathmosId"];
	$location = $_POST["stathmosLocation"];
	$apikey = $_POST["APIkey"];
	//array to check empty vars
	$exit = array($onoma_stathmou, $id_stathmou, $location, $apikey);
	//check if needed field is empty
	foreach($exit as $leave){
		if($leave==""){
			die("failed");
		}
	}
	
	// Create connection
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	// Check connection
	if (!$conn) {
		die("failed");
	}
	mysqli_set_charset($conn,"utf8");
	
	$q_one = mysqli_query($conn,"SELECT privileges FROM users WHERE APIkey = '$apikey'") or die();
	foreach($q_one as $check){
		$adm = $check["privileges"];
	}
	if($adm =="admin"){
		$q_two = mysqli_query($conn,"DELETE FROM Stathmos WHERE name='$onoma_stathmou' AND id='$id_stathmou' AND location='$location'") or die("failed");
		echo "failed";
		return;
	}
	$q_three = mysqli_query($conn,"SELECT name FROM Stathmos WHERE name='$onoma_stathmou' AND id='$id_stathmou'") or die();
	if(mysqli_num_rows($q_three)!=0){
		die("failed");
	}
	mysqli_close($conn);
	echo "success";
}

/* ++++++ ADMIN INSERT DATA +++++++++ */

if(isset($_POST['upload']) && $_POST['upload']=="active"  && isset($_POST["stathmos_Name"]) && isset($_POST["stathmos_Id"]) && isset($_POST["API_key"]) && isset($_POST["etos"]) && 
isset($_POST["type"]) && isset($_FILES["userfile"])){

	header('Content-type: text/html; charset=utf-8');

	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "teliko_db";
	
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	// Check connection
	if (!$conn) {
		die("Αποτυχία Σύνδεσης Στη Βάση Δεδομένων.\n Connection failed: " . mysqli_connect_error());
	}
	
	$year = $_POST['etos'];
	$typos = $_POST['type'];
	$name_stathmos = $_POST['stathmos_Name'];
	$stathmos = $_POST['stathmos_Id'];
	$filename = $_FILES['userfile']['name'];
	$apikey = $_POST["API_key"];
	
	//array to check empty vars
	$exit = array($stathmos, $typos, $apikey, $year, $filename);
	
	//check if needed field is empty
	foreach($exit as $leave){
		if($leave==""){
			mysqli_close($conn);
			die("Αποτυχία, Δεν Δώθηκαν Όλα Τα Απαραίτητα Στοιχεία!");
		}
	}

	if($_FILES['userfile']['size']>204800){
		die("Πολύ Μεγάλο Μέγεθος Αρχείου!");
	}
	
	$q_one = mysqli_query($conn,"SELECT privileges FROM users WHERE APIkey = '$apikey'") or die(mysqli_error($conn));
	foreach($q_one as $check){
		$adm = $check["privileges"];
	}
	if($adm!="admin"){
		mysqli_close($conn);
		die("Not An Admin!");
	}
	
	$topo="C:\\wamp\\www\\panos\\mydatabase\\";
	$target_file = $topo . basename($_FILES["userfile"]["name"]);

	if (file_exists($target_file)) {
		mysqli_close($conn);
		die("Το Αρχείο : ".$_FILES["userfile"]["name"]." Υπάρχει Ήδη.");
	}

	if (move_uploaded_file($_FILES["userfile"]["tmp_name"], $target_file)) {
		$sql="INSERT INTO Rupos (year, type, stathmos_id, data)
		VALUE('$year','$typos','$stathmos','$filename')";
		mysqli_query($conn,$sql) or die(mysqli_error($conn));
		
		$sel = "SELECT data FROM rupos WHERE year='$year' AND type='$typos' AND stathmos_id='$stathmos' ";
		$arxeio = mysqli_query($conn,$sel);
		
		mysqli_set_charset($conn,"utf8");
		
		foreach ($arxeio as $rows) {
			
			$thesi = $rows["data"];
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
			$topo="C:\\wamp\\www\\panos\\mydatabase\\$thesi";
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
		mysqli_close($conn);
		die("Το Αρχείο <<". basename( $_FILES["userfile"]["name"]). ">> Φορτώθηκε Επιτυχώς.");
	} else {
		mysqli_close($conn);
		die("Λυπούμαστε, Παρουσιάστηκε Ένα Πρόβλημα Κατά Τη Διεκπεραίωση Της Διαδικασίας. Προσπαθήστε Ξανά.");
	}
}

?>