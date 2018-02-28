<?php
if(isset($_GET["APIkey"]) && $_GET["APIkey"]!=""){
	header('Content-type: text/html; charset=utf-8');
	$api_key = $_GET["APIkey"];
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "teliko_db";
	$key_query = "SELECT privileges,requests_stathmoi,requests_mesi_timi,requests_apoluti_timi FROM users WHERE APIkey='$api_key'";
	$conn = mysqli_connect($servername, $username, $password, $dbname) or die();
	$see_user = mysqli_query($conn,$key_query) or die();
	if(mysqli_num_rows($see_user)!=0){
		foreach($see_user as $see_us){
			$privil = $see_us["privileges"];
			$req_stathmoi = $see_us["requests_stathmoi"];
			$req_m_t = $see_us["requests_mesi_timi"];
			$req_a_t = $see_us["requests_apoluti_timi"];
		}
	
	/* ++++++++ STATHMOI +++++++++ */
	
		if(isset($_GET["stathmoi"]) && $_GET["stathmoi"]=="stathmoi"){
			// Create connection
			$conn = mysqli_connect($servername, $username, $password, $dbname);
			// Check connection
			if (!$conn) {
				die("Connection failed: " . mysqli_connect_error());
			}
			mysqli_set_charset($conn,"utf8");
			
			if($privil == "user"){
				$new_req_stathmoi = $req_stathmoi+1;
				mysqli_query($conn,"UPDATE users SET requests_stathmoi = '$new_req_stathmoi' WHERE APIkey='$api_key'");
			}
			
			
			$result = mysqli_query($conn,"SELECT Name, Id, Location FROM Stathmos");
			$outp = "[";
			
			while($rs = $result->fetch_array(MYSQLI_ASSOC)) {
				if ($outp != "[") {$outp .= ",";}
				$outp .= '{"Name":"'  . $rs["Name"] . '",';
				$outp .= '"Id":"'   . $rs["Id"]        . '",';
				$outp .= '"Location":"'. $rs["Location"]     . '"}'; 
			}
			$outp .="]";
			
			mysqli_close($conn);
			
			print_r($outp);
		}
		
		/* ++++++ STD $ MEAN ++++++ */
		
		
		if(isset($_GET["meanStd"]) && isset($_GET["etos"]) && isset($_GET["eidos_rupou"]) && $_GET["etos"]!="" && $_GET["eidos_rupou"]!=""){
			$eidos_rupou = $_GET["eidos_rupou"];
			$etos = $_GET["etos"];
			$id = $_GET["id_stathmou"];
			$month = $_GET["mhnas"];
			$hmera = $_GET["hmera"];
			if ($hmera==="" && $month!=="" && $etos !==""){
				$timh1 = "01-$month-$etos";
				$timh2 = "31-$month-$etos";
			} elseif ($hmera==="" && $month === "" && $etos !== ""){
				$timh1 = "01-01-$etos";
				$timh2 = "31-12-$etos";
			} elseif ($hmera!=="" && $month!=="" && $etos !==""){
				$timh1 = "$hmera-$month-$etos";
				$timh2 = "$hmera-$month-$etos";
			}
			if($privil == "user"){
				$new_req_m_t = $req_m_t+1;
				mysqli_query($conn,"UPDATE users SET requests_mesi_timi = '$new_req_m_t' WHERE APIkey='$api_key'");
			}
			// Create connection
			$conn = mysqli_connect($servername, $username, $password, $dbname);
			// Check connection
			if (!$conn) {
				die("Connection failed: " . mysqli_connect_error());
			}
			mysqli_set_charset($conn,"utf8");
			if ($id==""){
				$select = "SELECT stathmos_id,data FROM rupos WHERE type='$eidos_rupou' AND year='$etos'";
				$arxeio = mysqli_query($conn,$select);
			} elseif ($id!=""){
				$select = "SELECT stathmos_id,data FROM rupos WHERE type='$eidos_rupou' AND year='$etos' AND stathmos_id = '$id'";
				$arxeio = mysqli_query($conn,$select);
			}
			$ft = array();
			$st = array();
			$ola = "[";
			foreach ($arxeio as $rows) {
				$stathm = $rows["stathmos_id"];
				$select_stathmos = "SELECT name,location FROM stathmos WHERE id='$stathm'";
				$thesi = $rows["data"];
				$sel = "SELECT * FROM `$thesi` WHERE STR_TO_DATE(date,'%d-%m-%Y')BETWEEN STR_TO_DATE('$timh1','%d-%m-%Y') AND STR_TO_DATE('$timh2','%d-%m-%Y')";
				$teliko = mysqli_query($conn,$sel) or die(mysqli_error($conn));
				$teliko_onoma = mysqli_query($conn,$select_stathmos) or die(mysqli_error($conn));
				foreach($teliko_onoma as $onoma){
					$tel_onoma = $onoma["name"];
					$lang_long = $onoma["location"];
				}
				$arr = array();
				$dat = array();
				foreach($teliko as $rs){
					$f = $rs["date"];
					$rs = array_slice($rs,1);
					$arr[$f] = $rs;
					$dat[$f] = $f;
					$count = (count(array_filter($rs,create_function('$a','return $a !=="";'))));
					if($count==0){
						$mean = "";
						$ft[$f] = $mean;
					}
					if($count!==0){
						$mean = array_sum($rs)/$count;
						$ft[$f] = $mean;
					}
				}
				$num_ens = (count(array_filter($ft,create_function('$a','return $a !=="";'))));
				if($num_ens!==0){
					$me = array_sum($ft)/$num_ens;
				}
				if($num_ens==0){
					$me = "";
				}
				$st[$tel_onoma] = $me;
				$whole_var = 0.0;
				$metr = 0.0;
				foreach($dat as $ps){
					$num_std = (count(array_filter($arr[$ps],create_function('$a','return $a !=="";'))));
					$metr += $num_std;
					foreach($arr[$ps] as $var){
						$whole_var += pow($var - $me,2);
					}
				}
				if($metr!==0){
					$std_pre = sqrt($whole_var)/sqrt($metr-1);
				}
				if($metr==0){
					$std_pre = "";
				}
				$standard_dev[$tel_onoma] = $std_pre;
				if ($ola != "[") {$ola .= ",";}
				$ola .= '{"Name":"' . $tel_onoma . '",';
				if(!empty($me)){
					$ola .= '"Mean":"' . $me . '",';
				}else{
					$me="";
					$ola .= '"Mean":"' . $me . '",';
				}
				if(!empty($std_pre)){
					$ola .= '"Std":"' . $std_pre . '",';
				}else{
					$std_pre="";
					$ola .= '"Std":"' . $std_pre . '",';
				}
				$ola .= '"Location":"' . $lang_long . '"}';
			}
			$ola .= "]";
			print_r($ola);
		}
		
		/* +++++++  APOLYTH   +++++++ */
		
		if(isset($_GET["apolyth"]) && isset($_GET["time"]) && $_GET["time"]!="" &&
		isset($_GET["month_apolyth"]) && isset($_GET["mera_apolyth"]) && $_GET["month_apolyth"]!="" && $_GET["mera_apolyth"]!="" &&
		isset($_GET["etos_apolyth"]) && $_GET["etos_apolyth"]!="" && isset($_GET["eidos_apolyth"]) && $_GET["eidos_apolyth"]!=""){
			
			$eidos_rupou_apolyth = $_GET["eidos_apolyth"];
			$etos_apolyth = $_GET["etos_apolyth"];
			$id_apolyth = $_GET["id_apolyth"];
			$month_apolyth = $_GET["month_apolyth"];
			$time = $_GET["time"];
			$mera_apolyth = $_GET["mera_apolyth"];
			$outarray= array();
			$hmeromhnia_apolyth = $mera_apolyth."-".$month_apolyth."-".$etos_apolyth;
			if(strpos($time,'πμ')===false){
				if(strpos($time,'μμ')===false){
					die("Λαθος Μορφη Ωρας");
				}
			}
			
			if($privil == "user"){
				$new_req_a_t = $req_a_t+1;
				mysqli_query($conn,"UPDATE users SET requests_apoluti_timi = '$new_req_a_t' WHERE APIkey='$api_key'");
			}
			
			if($time === "12πμ"){$wra = "T01";};
			if($time === "1πμ"){$wra = "T02";};
			if($time === "2πμ"){$wra = "T03";};
			if($time === "3πμ"){$wra = "T04";};
			if($time === "4πμ"){$wra = "T05";};
			if($time === "5πμ"){$wra = "T06";};
			if($time === "6πμ"){$wra = "T07";};
			if($time === "7πμ"){$wra = "T08";};
			if($time === "8πμ"){$wra = "T09";};
			if($time === "9πμ"){$wra = "T10";};
			if($time === "10πμ"){$wra = "T11";};
			if($time === "11πμ"){$wra = "T12";};
			if($time === "12μμ"){$wra = "T13";};
			if($time === "1μμ"){$wra = "T14";};
			if($time === "2μμ"){$wra = "T15";};
			if($time === "3μμ"){$wra = "T16";};
			if($time === "4μμ"){$wra = "T17";};
			if($time === "5μμ"){$wra = "T18";};
			if($time === "6μμ"){$wra = "T19";};
			if($time === "7μμ"){$wra = "T20";};
			if($time === "8μμ"){$wra = "T21";};
			if($time === "9μμ"){$wra = "T22";};
			if($time === "10μμ"){$wra = "T23";};
			if($time === "11μμ"){$wra = "T24";};
			
			// Create connection
			$conn = mysqli_connect($servername, $username, $password, $dbname);
			// Check connection
			if (!$conn) {
				die("Connection failed: " . mysqli_connect_error());
			}
			
			mysqli_set_charset($conn,"utf8");
			if($id_apolyth==""){
				$select_apolyth = "SELECT stathmos_id,data FROM rupos WHERE type='$eidos_rupou_apolyth' AND year='$etos_apolyth'";
				$arxeio_apolyth = mysqli_query($conn,$select_apolyth);
			}
			if($id_apolyth!==""){
				$select_apolyth = "SELECT stathmos_id,data FROM rupos WHERE type='$eidos_rupou_apolyth' AND year='$etos_apolyth' AND stathmos_id='$id_apolyth'";
				$arxeio_apolyth = mysqli_query($conn,$select_apolyth);
			}
			$apolyth_timh = "[";
			foreach ($arxeio_apolyth as $rows_apolyth) {
				$stathm_apol = $rows_apolyth["stathmos_id"];
				$select_stathmos_apolyth = "SELECT name,location FROM stathmos WHERE id='$stathm_apol'";
				$thesi = $rows_apolyth["data"];
				$sel_apolyth = "SELECT $wra FROM `$thesi` WHERE STR_TO_DATE(date,'%d-%m-%Y') = STR_TO_DATE('$hmeromhnia_apolyth','%d-%m-%Y')";
				$teliko_apol = mysqli_query($conn,$sel_apolyth) or die(mysqli_error($conn));
				$teliko_onoma_apol = mysqli_query($conn,$select_stathmos_apolyth) or die(mysqli_error($conn));
				foreach($teliko_onoma_apol as $onoma_apol){
					$tel_onoma_apol = $onoma_apol["name"];
					$lat_long_apol = $onoma_apol["location"];
				}
				$arr_apol = array();
				foreach($teliko_apol as $rs_apol){
					$apolyth = $rs_apol["$wra"];
					$arr_apol[$time] = $rs_apol["$wra"];
				}
				if ($apolyth_timh != "[") {$apolyth_timh .= ",";}
				$apolyth_timh .= '{"Name":"' . $tel_onoma_apol . '",';
				if(!empty($apolyth)){
					$apolyth_timh .= '"Apolyth":"' . $apolyth . '",';
				}else{
					$apolyth="";
					$apolyth_timh .= '"Apolyth":"' . $apolyth . '",';
				}
				$apolyth_timh .= '"Location":"' . $lat_long_apol . '"}';
			}
			$apolyth_timh .= "]";
			
			print_r($apolyth_timh);
		}
	}
	else{
		echo("Μη Έγκυρο APIkey!");
	}
}
?>