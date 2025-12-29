# Waktu Solat PHP API
There are two version of the API, which are ```api.php``` and ```apiv2.php```, and also a states and zones list fetcher script.
1. ```api.php``` will fetch and parse the daily Prayer Time JSON data from JAKIM e-Solat website. This will only return prayer time for the current day.
2. ```apiv2.php``` will fetch and parse prayer time for the chosen Zone for either a month, or for a whole year. This API fetch JSON data from JAKIM e-Solat website.
3. ```zonefetcher.php``` will fetch and store the list of states and zones into the ```zone.json``` file. Always run this file first on fresh install, or every couple of months.

## Examples

### Basic Usage Examples
- ```examples/basic-usage.php```
- PHP script demonstrating how to use both API v1 and API v2 programmatically
- Includes examples for fetching states, zones, daily prayer times, and monthly/yearly data
  
### Web Interface
- ```examples/web/index.html``` and ```examples/web/template/js/script.js```
- Modern web interface built with vanilla JavaScript (no jQuery) and Bootstrap 5
- Fetch and display Malaysia's Prayer Time dynamically using Fetch API
- Original PHP Project and using Solat.io API : https://github.com/afzafri/Waktu-Solat

## Created By :
1. Afif Zafri
3. Date : 17/05/2016
4. Contact Me : http://fb.me/afzafri

## Update
- 29/12/25
	- Refactored web interface to use modern vanilla JavaScript with Fetch API (removed jQuery dependency)
	- Upgraded from Bootstrap 3 to Bootstrap 5
	- Reorganized project structure: moved web interface to ```examples/web/``` directory
	- Added ```examples/basic-usage.php``` with API usage examples
	- Replaced grid layout with flexbox for better responsiveness
	- Removed unused Glyphicons fonts
- 06/12/19
	- Updated ```zone.json```. JAKIM have restructured the States and Zones list. I also added new script ```zonefetcher.php``` to automatically fetch and update the states and zones list from JAKIM website.
	- Updated API v1 to fetch data from JAKIM JSON data instead of the old XML data. API v1 now also included the day name and hijri date.
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

- Zone Fetcher Usage (```zonefetcher.php```)
  1. Run the script and it will automatically fetch and store the latest JSON into the ```zone.json``` file.
		- ```http://localhost/zonefetcher.php```

## Credits

1. JAKIM e-Solat: http://www.e-solat.gov.my/web/waktusolat.php
2. API Inspiration by Solat.IO: http://Solat.IO
3. Bootstrap 5: https://getbootstrap.com/
4. Loading Spinner svg: http://loading.io/
5. References:  http://ijat.my/e-solat-xmljsonp-api, http://stackoverflow.com/
6. Sensei: Mohd Shahril (Thanks for convert time code and zone.json)

## License

This library is under ```MIT license```, please look at the LICENSE file
