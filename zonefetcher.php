<?php

$url = "https://www.e-solat.gov.my/index.php?siteId=24&pageId=24";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$result = curl_exec($ch);
$httpstatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$patern = '#<select id="inputZone" class="form-control">([\w\W]*?)</select>#';
preg_match_all($patern, $result, $result);

$patern = '#<optgroup([\w\W]*?)</optgroup>#';
preg_match_all($patern, $result[0][0], $result);

$stateJson = array();
foreach ($result[0] as $options) {

  // get state name
  $patern = '#label="([\w\W]*?)"#';
  preg_match_all($patern, $options, $statearr);
  $state  = $statearr[1][0];

  echo "<h3>".$state."</h3>";

  // get zones
  $patern = '#<option([\w\W]*?)</option>#';
  preg_match_all($patern, $options, $zonearr);

  $zonJson = array();
  foreach ($zonearr[0] as $zoneoption) {

    // get zone code
    $patern = "#value='([\w\W]*?)'#";
    preg_match_all($patern, $zoneoption, $zonecodearr);
    $zonecode = $zonecodearr[1][0];
    $zonename = (explode(" - ", strip_tags($zoneoption)))[1];

    echo $zonecode . " : " . $zonename . "<br>";

    $zonJson[$zonecode] = $zonename;
  }

  $stateJson[$state] = $zonJson;
}

// output json
file_put_contents("./zone.json", json_encode($stateJson, JSON_PRETTY_PRINT));

?>
