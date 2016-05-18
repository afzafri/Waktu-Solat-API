$(document).ready(function(){
	
	//event selector to detect if "pilih negeri" select box is change
	//if change, fetch and append the list of zones from zone.json (thx abam shahril) for the chosen state
	$(document).on("change","#pilih_negeri", function(){
		$('#pilih_zone').empty();
		
		//use jquery getJSON function to fetch json data
		$.getJSON( "./zone.json", function( data ) {
		
		//convert string to uppercase, needed so that can use to compare with the json file.
		//malas nak tukar satu2 kat index.html xD
		var negeri = $('#pilih_negeri').val().toUpperCase();
		  
		  var zons = "";
		  $.each( data[negeri], function( key, val ) {
			zons += "<option value='" + key + "'>" + val + "</option>";
		  });
		 var textPilih = "<option value=''>Pilih Zon</option>";
		  $('#pilih_zone').append(textPilih+zons); //append list
		});
	});
	
	//event selector to detect if "pilih zon" select box is change
	//if change, fetch data for the selected state and zone from solat.io API
	$(document).on("change","#pilih_zone", function(){
		
		$('#results').empty();
		
		var codeZon = $('#pilih_zone').val();
		var apiURL = "http://solat.io/api/my/"+codeZon; //solat.io JSON API
		
		$.getJSON( apiURL, function( data ) {
		
                //pulau pinang API use "." not ":". so convert time function will not work. need to replace to ":"
                if(codeZon == "PNG01")
                {
                  var imsak = convertTime(data["waktu_imsak"].replace(".", ":"));
		  var subuh = convertTime(data["waktu_subuh"].replace(".", ":"));
		  var syuruk = convertTime(data["waktu_syuruk"].replace(".", ":"));
		  var zohor = convertTime(data["waktu_zohor"].replace(".", ":"));
		  var asar = convertTime(data["waktu_asar"].replace(".", ":"));
		  var maghrib = convertTime(data["waktu_maghrib"].replace(".", ":"));
		  var isyak = convertTime(data["waktu_isyak"].replace(".", ":"));
                }
               else
               {
                   var imsak = convertTime(data["waktu_imsak"]);
		  var subuh = convertTime(data["waktu_subuh"]);
		  var syuruk = convertTime(data["waktu_syuruk"]);
		  var zohor = convertTime(data["waktu_zohor"]);
		  var asar = convertTime(data["waktu_asar"]);
		  var maghrib = convertTime(data["waktu_maghrib"]);
		  var isyak = convertTime(data["waktu_isyak"]);
               }

		var results = "<table>" +
		  "<tr><th>Imsak</th><td>" + imsak + "</td></tr>" +
		  "<tr><th>Subuh</th><td>" + subuh + "</td></tr>" +
		  "<tr><th>Syuruk</th><td>" + syuruk + "</td></tr>" +
		  "<tr><th>Zohor</th><td>" + zohor + "</td></tr>" +
		  "<tr><th>Asar</th><td>" + asar + "</td></tr>" +
		  "<tr><th>Maghrib</th><td>" + maghrib + "</td></tr>" +
		  "<tr><th>Isyak</th><td>" + isyak + "</td></tr>" +
		  "</table>";

		  $('#results').append(results).hide().fadeIn('slow'); //append the result with slow fade in animation
		  

		});
	});
	
	//function to convert 24h to 12h time
	//credit - TQ Shahril tolong cari hahaha
	function convertTime (time){
		var date = time;
		var newtime = null;
		date = date.replace(/[0-9]{1,2}(:[0-9]{2})/, function (time) {
			var hms = time.split(':'),
				h = +hms[0],
				suffix = (h < 12) ? 'am' : 'pm';
			hms[0] = h % 12 || 12;       
		 
			newtime = hms.join(':') + " " + suffix;
			
		});
		return newtime;
	}
	
});
				
