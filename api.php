<?php

/*
	Waktu Solat API created by Afif Zafri
	XML data are fetch directly from JAKIM e-solat website
	This API will parse the XML and return the data as JSON
*/

// ---------- Fetch States----------
if(isset($_GET['getStates']))
{
	$jsonFile = file_get_contents("./zone.json");
	$jsonDat = json_decode($jsonFile, true);
	$statesList = array();

	foreach ($jsonDat as $key => $value)
	{
    	$statesList[] = $key;
	}

	# display data in json format
	echo json_encode($statesList, JSON_FORCE_OBJECT);
}
// ---------- Fetch zones for a state ----------
else if(isset($_GET['stateName']))
{
	$jsonFile = file_get_contents("./zone.json");
	$jsonDat = json_decode($jsonFile, true);
	$stateName = $_GET['stateName'];

	# display data in json format
	echo json_encode($jsonDat[$stateName]);
}
// ---------- Fetch Waktu Solat data by Zone ----------
else if(isset($_GET['zon']))
{
	$kodzon = $_GET['zon']; # store get parameter in variable

	$jsonurl = "https://www.e-solat.gov.my/index.php?r=esolatApi/TakwimSolat&period=today&zone=".$kodzon; # url of JAKIM eSolat JSON data

	# fetch JSON data (from JAKIM website)
	$ch = curl_init(); # initialize curl object
	curl_setopt($ch, CURLOPT_URL, $jsonurl);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$fetchjson = curl_exec($ch); # execute curl, fetch webpage content (JSON data)

	# parse json data into array
	$data = json_decode($fetchjson, true);

	curl_close($ch);  # close curl

	# get name of zone
	$zones = getZones();
	$namazon = $zones[$kodzon];
	# access data
	$tarikhmasa = $data['serverTime'];
	$bearing = $data['bearing'];
	$prayerTime = $data['prayerTime'][0];

	# create associative array to store waktu solat
	$arrwaktu = array(
		"waktu_imsak" => convertTime($prayerTime['imsak']),
		"waktu_subuh" => convertTime($prayerTime['fajr']),
		"waktu_syuruk" => convertTime($prayerTime['syuruk']),
		"waktu_zohor" => convertTime($prayerTime['dhuhr']),
		"waktu_asar" => convertTime($prayerTime['asr']),
		"waktu_maghrib" => convertTime($prayerTime['maghrib']),
		"waktu_isyak" => convertTime($prayerTime['isha']),
		"tarikh_gregory" => strftime("%d/%m/%Y", malayStrtotime($prayerTime['date'])),
		"tarikh_hijrah" => jakimHijri($prayerTime['hijri']),
		"hari" => translateDay($prayerTime['day']),
		"kod_zon" => $kodzon,
		"nama_zon" => $namazon,
		"tarikh_server" => $tarikhmasa,
		"bearing" => $bearing,
	);

	# display data in json format
	echo json_encode($arrwaktu);
}
else
{
	?>
	<p>
		Waktu Solat API created by Afif Zafri <br>
		XML data are fetch directly from JAKIM e-solat website <br>
		This API will parse the XML and return the data as JSON <br><br>

		<b>To get list of states</b><br>
		http://localhost/<font color="blue">api.php?getStates</font><br><br>

		<b>To get list of zones of a state</b><br>
		http://localhost/<font color="blue">api.php?stateName=</font><font color="red">PERLIS</font> , where "<font color="red">PERLIS</font>" is the state name<br><br>

		<b>To get the prayer time of a zone</b><br>
		http://localhost/<font color="blue">api.php?zon=</font><font color="red">PLS01</font> , where "<font color="red">PLS01</font>" is the zone code <br><br>
	</p>
	<?php
}

// Function to convert the time
function convertTime($time)
{
	// replace separator
	$time = str_replace(".", ":", $time);
	// convert 24h to 12h
	$newtime = date('h:i', strtotime($time));
	// include a.m. or p.m. prefix
    $newtime .= explode(':', $time)[0] <= 12 ? ' a.m.' : ' p.m.';

	return $newtime;
}

// Function to get all zones list
function getZones()
{
	$jsonFile = file_get_contents("./zone.json");
	$jsonDat = json_decode($jsonFile, true);
	$zoneList = array();

	foreach ($jsonDat as $key => $value)
	{
			$zoneList = array_merge($zoneList, $value);
	}

	# display data in json format
	return $zoneList;
}

// Function to replace malay months to english
function malayStrtotime($date_string) {
  return strtotime(strtr(strtolower($date_string), array('jan'=>'jan','feb'=>'feb','mac'=>'march','apr'=>'apr','mei'=>'may','jun'=>'jun','jul'=>'jul','ogos'=>'aug','sep'=>'sep','okt'=>'oct','nov'=>'nov','dis'=>'dec')));
}

// Function to translate day to BM
function translateDay($day) {
  $days = array(
    "Monday" => "Isnin",
    "Tuesday" => "Selasa",
    "Wednesday" => "Rabu",
    "Thursday" => "Khamis",
    "Friday" => "Jumaat",
    "Saturday" => "Sabtu",
    "Sunday" => "Ahad",
  );

  return $days[$day];
}

// Function to convert hijri date
function jakimHijri($hijri)
{
	$hijri = explode("-", $hijri);

	$y = $hijri[0];
	$m = $hijri[1];
	$d = $hijri[2];

	$hijriMonth = array(
			"Muharram", "Safar", "Rabiulawal", "Rabiulakhir",
			"Jamadilawal", "Jamadilakhir", "Rejab", "Syaaban",
			"Ramadhan", "Syawal", "Zulkaedah", "Zulhijjah"
	);

	return $d . " " . $hijriMonth[$m-1] . " " . $y;
 }

?>
