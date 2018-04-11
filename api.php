<?php

/*
	Waktu Solat API created by Afif Zafri
	XML data are fetch directly from JAKIM e-solat website
	This API will parse the XML and return the data as JSON
	This API only need to receive "zon" parameter
	example: http://localhost/api.php?zon=PLS01 , where "PLS01" is the zone code
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

	$xmlurl = "http://www2.e-solat.gov.my/xml/today/?zon=".$kodzon; # url of JAKIM eSolat XML data
	
	# fetch xml file (from JAKIM website)
	$ch = curl_init(); # initialize curl object
	curl_setopt($ch, CURLOPT_URL, $xmlurl);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$fetchxml = curl_exec($ch); # execute curl, fetch webpage content (xml data)
	
	# parse xml data into object
	$data = simplexml_load_string($fetchxml); # parse xml data into object

	curl_close($ch);  # close curl 
	
	# access xml data, get name of zone, trim object
	$namazon = trim($data->channel[0]->link); 
	$tarikhmasa = trim($data->channel[0]->children('dc',true)->date); # use children() to access tag with colon (:)
	
	# create associative array to store waktu solat
	$arrwaktu = array();

	# iterate through the "item" tag in the xml, access, and store into array
	foreach($data->channel[0]->item as $item)
	{	
		# access data and trims object, to store as string
		$solat = "waktu_" . strtolower(trim($item->title)); # append "waktu_" to the lowercase string. ex: "waktu_subuh"
		$waktu = trim($item->description);
		
		# store into associative array
		$arrwaktu[$solat] = convertTime($waktu);
	}

	# add kod_zon, nama_zon, and tarikh_masa to the array
	$arrwaktu["kod_zon"] = $kodzon;
	$arrwaktu["nama_zon"] = $namazon;
	$arrwaktu["tarikh_masa"] = $tarikhmasa;

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

?>