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
	$url = "http://www.e-solat.gov.my/web/muatturun.php?zone=".$kodzon."&jenis=year&lang=en&year=".$tahun."&bulan=".$bulan;
		
	# use cURL to fetch webpage
    $ch = curl_init(); # initialize curl object
    curl_setopt($ch, CURLOPT_URL, $url); # set url
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); # receive server response
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); # do not verify SSL
    $data = curl_exec($ch); # execute curl, fetch webpage content
    echo curl_error($ch);
    $httpstatus = curl_getinfo($ch, CURLINFO_HTTP_CODE); # receive http response status
    curl_close($ch);  # close curl

    # parse the data using regex
    $patern = '#<table width=\"100%\" cellspacing=\"1\" cellpadding=\"3\" bgcolor=\"\#7C7C7C\"\>([\w\W]*?)</table>#'; 
    preg_match_all($patern, $data, $parsed);  

    $trpatern = "#<tr([\w\W]*?)</tr>#";
    preg_match_all($trpatern, implode('',$parsed[0]), $trparsed); 

    unset($trparsed[0][0]); # remove an array element because we don't need the 1st row (table heading) 
    $trparsed[0] = array_values($trparsed[0]); # rearrange the array index

    $arrData = array();
    $arrData['httpstatus'] = $httpstatus;

    if(count($trparsed[0]) > 0)
    {
        for($j=0;$j<count($trparsed[0]);$j++)
        {
            # parse the table by column <td>
            $tdpatern = "#<td([\w\W]*?)</td>#";
            preg_match_all($tdpatern, $trparsed[0][$j], $tdparsed);

            # store into variable, strip_tags is for removeing html tags
            $date = strip_tags($tdparsed[0][0]);
            $day = strip_tags($tdparsed[0][1]);
            $imsak = strip_tags($tdparsed[0][2]);
            $subuh = strip_tags($tdparsed[0][3]);
            $syuruk = strip_tags($tdparsed[0][4]);
            $zohor = strip_tags($tdparsed[0][5]);
            $asar = strip_tags($tdparsed[0][6]);
            $maghrib = strip_tags($tdparsed[0][7]);
            $isyak = strip_tags($tdparsed[0][8]);

            # replace/remove new line tag and empty space, and store into array
            $arrData['data'][$j]['date'] = str_replace(array("\n","        "),'',$date." ".$tahun);
            $arrData['data'][$j]['day'] = str_replace(array("\n"," "),'',$day);
            $arrData['data'][$j]['imsak'] = str_replace(array("\n"," "),'',$imsak);
            $arrData['data'][$j]['subuh'] = str_replace(array("\n"," "),'',$subuh);
            $arrData['data'][$j]['syuruk'] = str_replace(array("\n"," "),'',$syuruk);
            $arrData['data'][$j]['zohor'] = str_replace(array("\n"," "),'',$zohor);
            $arrData['data'][$j]['asar'] = str_replace(array("\n"," "),'',$asar);
            $arrData['data'][$j]['maghrib'] = str_replace(array("\n"," "),'',$maghrib);
            $arrData['data'][$j]['isyak'] = str_replace(array("\n"," "),'',$isyak);
        }
    }

    return $arrData; # return array data
}

# function for include project info details
function projInfo($arrData)
{
	$arrData['info']['creator'] = "Afif Zafri (afzafri)";
    $arrData['info']['project_page'] = "https://github.com/afzafri/Waktu-Solat-API/blob/master/apiv2.php";
    $arrData['info']['date_updated'] = "08/09/2017";

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

?>