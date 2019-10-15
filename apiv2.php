<?php

/*
	Waktu Solat API v2 created by Afif Zafri
	XML data are fetch directly from JAKIM e-solat website
	This new version will be able to fetch prayer time data for the whole Year or by each month for chosen Zone

	Usage:
	1. Fetch data for a month
	example: http://localhost/apiv2.php?zon=PLS01&tahun=2017&bulan=5 ,
	where "PLS01" is the zone code, 2017 is the year, 5 is the month

	2. Fetch data for a year
	example: http://localhost/apiv2.php?zon=PLS01&tahun=2017 ,
	where "PLS01" is the zone code, 2017 is the year. No need to include the month
*/

# function for fetching the webpage and parse data
function fetchPage($kodzon,$tahun,$bulan)
{
	$url = "https://www.e-solat.gov.my/index.php?r=esolatApi/takwimsolat&period=duration&zone=".$kodzon;

		# data for POST request
		$dates = getDurationDate($bulan, $tahun);
    $postdata = http_build_query(
        array(
            'datestart' => $dates['start'],
            'dateend' => $dates['end'],
        )
    );

    # cURL also have more options and customizable
    $ch = curl_init(); # initialize curl object
    curl_setopt($ch, CURLOPT_URL, $url); # set url
    curl_setopt($ch, CURLOPT_POST, 1); # set option for POST data
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata); # set post data array
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); # receive server response
    $result = curl_exec($ch); # execute curl, fetch webpage content
    $httpstatus = curl_getinfo($ch, CURLINFO_HTTP_CODE); # receive http response status
    curl_close($ch);  # close curl

    $arrData = array();
		$arrData['data'] = array();
    $arrData['httpstatus'] = $httpstatus;
		$waktusolat = json_decode($result, true);

		if(count($waktusolat['prayerTime']) > 0) {
			foreach ($waktusolat['prayerTime'] as $waktu) {
				$arrData['data'][] = array(
					'hijri' => $waktu['hijri'],
					'date' => date("Y-m-d", myStrtotime($waktu['date'])),
					'day' => $waktu['day'],
					'imsak' => convertTime($waktu['imsak']),
					'subuh' => convertTime($waktu['fajr']),
					'syuruk' => convertTime($waktu['syuruk']),
					'zohor' => convertTime($waktu['dhuhr']),
					'asar' => convertTime($waktu['asr']),
					'maghrib' => convertTime($waktu['maghrib']),
					'isyak' => convertTime($waktu['isha']),
				);
			}
		}

    return $arrData; # return array data
}

# function for include project info details
function projInfo($arrData)
{
	$arrData['info']['creator'] = "Afif Zafri (afzafri)";
    $arrData['info']['project_page'] = "https://github.com/afzafri/Waktu-Solat-API/blob/master/apiv2.php";
    $arrData['info']['date_updated'] = "15/10/2019";

    return $arrData;
}

# if month is chosen, then only fetch data for the chosen month
if(isset($_GET['zon']) && isset($_GET['tahun']) && isset($_GET['bulan']))
{
	$kodzon = $_GET['zon']; # store get parameter in variable
	$tahun = $_GET['tahun'];
	$bulan = $_GET['bulan'];

	$arrData = fetchPage($kodzon,$tahun,$bulan);

	# print JSON data
	echo json_encode(projInfo($arrData));
}

# if month does not chosen, fetch for all 12 months
if(isset($_GET['zon']) && isset($_GET['tahun']) && !isset($_GET['bulan']))
{
	$kodzon = $_GET['zon']; # store get parameter in variable
	$tahun = $_GET['tahun'];

	$arrData = array();

	for($i=1;$i<=12;$i++)
	{
		$arrData[$i] = fetchPage($kodzon,$tahun,$i);
	}

	# print JSON data
	echo json_encode(projInfo($arrData));
}

# if no parameters is supplied, show usage message
if(!isset($_GET['zon']) && !isset($_GET['tahun']) && !isset($_GET['bulan']))
{
	?>
		<p>
			Waktu Solat API v2 created by Afif Zafri <br>
			XML data are fetch directly from JAKIM e-solat website <br>
			This new version will be able to fetch prayer time data for the whole Year or by each month for chosen Zone <br> <br>

			Usage: <br>
			1. Fetch data for a month <br>
			example: http://localhost/<font color="blue">apiv2.php?zon=<font color="red">PLS01</font>&tahun=<font color="red">2017</font>&bulan=<font color="red">5</font></font> ,  <br>
			where "<font color="red">PLS01</font>" is the zone code, <font color="red">2017</font> is the year, <font color="red">5</font> is the month <br> <br>

			2. Fetch data for a year <br>
			example: http://localhost/<font color="blue">apiv2.php?zon=<font color="red">PLS01</font>&tahun=<font color="red">2017</font></font> ,  <br>
			where "<font color="red">PLS01</font>" is the zone code, <font color="red">2017</font> is the year. No need to include the month <br>
		</p>
	<?php
}

function myStrtotime($date_string)
{
	 $convertDate = array('jan'=>'jan','feb'=>'feb','mac'=>'march','apr'=>'apr','mei'=>'may','jun'=>'jun','jul'=>'jul','ogos'=>'aug','sep'=>'sep','okt'=>'oct','nov'=>'nov','dis'=> 'dec');
	 return strtotime(strtr(strtolower($date_string), $convertDate));
}

function getDurationDate($month, $year)
{
	$month = str_pad($month,2,'0',STR_PAD_LEFT);
	$startdate = $year.'-'.$month.'-'.'01';
	$enddate = $year.'-'.$month.'-'.date("t", strtotime(date("F", mktime(0, 0, 0, $month, 10))));

	return array(
		'start' => $startdate,
		'end' => $enddate
	);
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

?>
