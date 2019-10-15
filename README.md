# Waktu Solat PHP API
There are two version of the API, which are ```api.php``` and ```apiv2.php```
1. ```api.php``` will fetch and parse the daily Prayer Time XML data from JAKIM e-Solat website. This will only return prayer time for the current day.
2. ```apiv2.php``` will fetch and parse prayer time for the chosen Zone for either a month, or for a whole year. This API fetch JSON data from JAKIM e-Solat website.

# Dynamic jQuery Webpage
- ```index.html``` and ```./template/js/script.js```
- Fetch and display Malaysia's Prayer Time dynamically using jQuery and my PHP API
- This version use HTML, Javascript and jQuery only in the main file.
- Original PHP Project and using Solat.io API : https://github.com/afzafri/Waktu-Solat

## Created By : 
1. Afif Zafri 
3. Date : 17/05/2016
4. Contact Me : http://fb.me/afzafri

## Update
- 15/10/19
	- Fixed API v2 by fetching data using new endpoint. JAKIM's changed their Waktu Solat web page which resulting in the API v2 to break.
- 11/04/18
	- Update API v1 and v2 to convert time from 24h to 12h format and include am pm prefix
- 08/04/18
	- Update API v1 to return states names and zones
	- Update the demo interface to use this updated API
- 08/09/17
	- Waktu Solat API v2, ```apiv2.php```
	- This new version will be able to fetch prayer time data for the whole Year or by each month for chosen Zone
- 15/12/16
	- As for 15/12/16, this project no longer using Solat.io API.
	- This project will now using my own PHP API, ```api.php```

## Installation

Drop all files into your server  

## Usage

- Select Country and Zone from the drop down menu.
- API v1 Usage (```api.php```):
	1. To get list of states
		- ```http://localhost/api.php?getStates```

	2. To get list of zones of a state
		- ```http://localhost/api.php?stateName=NAME``` , where ```NAME``` is the state name, ex: PERLIS

	3. To get the prayer time of a zone
		- ```http://localhost/api.php?zon=CODE``` , where ```CODE``` is the zone code, ex: PLS01 for Perlis
		
- API v2 Usage (```apiv2.php```):
	1. Fetch data for a month
		- example: ```http://localhost/apiv2.php?zon=PLS01&tahun=2017&bulan=5```
		- where "PLS01" is the zone code, 2017 is the year, 5 is the month

	2. Fetch data for a year
		- example: ```http://localhost/apiv2.php?zon=PLS01&tahun=2017```
		- where "PLS01" is the zone code, 2017 is the year. No need to include the month

## Credits

1. JAKIM e-Solat: http://www.e-solat.gov.my/web/waktusolat.php
2. API Inspiration by Solat.IO: http://Solat.IO
3. jQuery Library: https://jquery.com/
4. Bootstrap Library: http://getbootstrap.com/
5. Loading Spinner svg: http://loading.io/
6. References:  http://ijat.my/e-solat-xmljsonp-api, http://stackoverflow.com/
7. Sensei: Mohd Shahril (Thanks for convert time code and zone.json)

## License

This library is under ```MIT license```, please look at the LICENSE file
