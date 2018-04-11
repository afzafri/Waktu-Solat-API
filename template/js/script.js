$(document).ready(function(){
	
	$('.se-pre-con').hide(); // hide loading 

	//onload, fetch list of states names and appends to UI
	$(function(){
		//use jquery getJSON function to fetch json data
		$.getJSON( "./api.php?getStates", function( data ) {
			$('.se-pre-con').fadeIn('fast'); // show loading
			var stateslist = "";
			$.each( data, function( key, val ) {
				stateslist += "<option value='"+val+"'>"+val+"</option>";
		 	});
			$('#pilih_negeri').append(stateslist);
			$('.se-pre-con').fadeOut('fast'); // hide loading
		});
	});

	//event selector to detect if "pilih negeri" select box is change
	//if change, fetch and append the list of zones from zone.json (thx abam shahril) for the chosen state
	$(document).on("change","#pilih_negeri", function(){
		$('.se-pre-con').fadeIn('fast'); // show loading
		$('#pilih_zone').empty();
		$('#results').empty();
		var negeri = $('#pilih_negeri').val();

		//use jquery getJSON function to fetch json data
		$.getJSON( "./api.php?stateName="+negeri, function( data ) {
			$('.se-pre-con').fadeIn('fast'); // show loading
			var zonelist = "";
			
 			$.each( data, function( key, val ) {
				zonelist += "<option value='" + key + "'>" + val + "</option>";
		 	});

			var textpilih = "<option value=''>Pilih Zon</option>";
		  	$('#pilih_zone').append(textpilih+zonelist); //append list
			$('.se-pre-con').fadeOut('fast'); // hide loading
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

	        var imsak = data["waktu_imsak"];
			var subuh = data["waktu_subuh"];
			var syuruk = data["waktu_syuruk"];
			var zohor = data["waktu_zohor"];
			var asar = data["waktu_asar"];
			var maghrib = data["waktu_maghrib"];
			var isyak = data["waktu_isyak"];

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
	
});
							