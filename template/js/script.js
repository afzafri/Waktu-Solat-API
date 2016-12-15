$(document).ready(function(){
	
	$('.se-pre-con').hide(); // hide loading 

	//event selector to detect if "pilih negeri" select box is change
	//if change, fetch and append the list of zones from zone.json (thx abam shahril) for the chosen state
	$(document).on("change","#pilih_negeri", function(){
		$('#pilih_zone').empty();
		$('#results').empty();
		
		//use jquery getJSON function to fetch json data
		$.getJSON( "./zone.json", function( data ) {
		
		//convert string to uppercase, needed so that can use to compare with the json file.
		//malas nak tukar satu2 kat index.html xD
		var negeri = $('#pilih_negeri').val().toUpperCase();
		  
		  var zons = "";
		  $.each( data[negeri], function( key, val ) {
			zons += "<option value='" + key + "'>" + val + "</option>";
		  });
		  var textpilih = "<option value=''>Pilih Zon</option>";
		  $('#pilih_zone').append(textpilih+zons); //append list

		});
	});
	
	//event selector to detect if "pilih zon" select box is change
	//if change, fetch data for the selected state and zone from my own waktu solat API
	//no longer using solat.io API as for 15/12/16 updates
	$(document).on("change","#pilih_zone", function(){
		
		$('.se-pre-con').fadeIn('fast'); // show loading
		$('#results').empty();
		
		var codeZon = $('#pilih_zone').val();
		var apiURL = "./api.php?zon="+codeZon; //my JSON API
		
		$.getJSON( apiURL, function( data ) {

	        var imsak = convertTime(data["waktu_imsak"]);
			var subuh = convertTime(data["waktu_subuh"]);
			var syuruk = convertTime(data["waktu_syuruk"]);
			var zohor = convertTime(data["waktu_zohor"]);
			var asar = convertTime(data["waktu_asar"]);
			var maghrib = convertTime(data["waktu_maghrib"]);
			var isyak = convertTime(data["waktu_isyak"]);

			var results = "<div class='table-responsive'>" +
			  "<table class='table table-bordered table-hover'>" +
			  "<tr><th>Imsak</th><td>" + imsak + "</td></tr>" +
			  "<tr><th>Subuh</th><td>" + subuh + "</td></tr>" +
			  "<tr><th>Syuruk</th><td>" + syuruk + "</td></tr>" +
			  "<tr><th>Zohor</th><td>" + zohor + "</td></tr>" +
			  "<tr><th>Asar</th><td>" + asar + "</td></tr>" +
			  "<tr><th>Maghrib</th><td>" + maghrib + "</td></tr>" +
			  "<tr><th>Isyak</th><td>" + isyak + "</td></tr>" +
			  "</table></div>";

			var panel1 = '<div class="col-md-6 center-block">' +
							'<div class="panel panel-danger">' +
								'<div class="panel-heading"></div>' +
								'<div class="panel-body">';
							
			var panel2 = '</div>' +
						'</div>' +
					'</div>';

			  $('#results').append(panel1 + results + panel2).hide().fadeIn('slow'); //append the result with slow fade in animation
			  $('.se-pre-con').fadeOut('fast'); // hide loading
		});

	});
	
	//function to convert 24h to 12h time
	//credit - TQ Shahril tolong cari hahaha
	function convertTime (time){
		var date = time;
		var newtime = null;
               
                //check if the Time have "." as separator instead of ":", change regex accordingly
                if(date.indexOf(".") > -1)
                {
                  var regexTime = /[0-9]{1,2}(.[0-9]{2})/;
                  var spt = '.';
                }
                else
                {
                  var regexTime = /[0-9]{1,2}(:[0-9]{2})/;
                  var spt = ':';
                }

		date = date.replace(regexTime, function (time) {
			var hms = time.split(spt),
				h = +hms[0],
				suffix = (h < 12) ? 'am' : 'pm';
			hms[0] = h % 12 || 12;       
		 
			newtime = hms.join(':') + " " + suffix;
		});

		return newtime;
	}
	
});
							