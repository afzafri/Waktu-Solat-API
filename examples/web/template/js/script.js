document.addEventListener('DOMContentLoaded', function() {

	const spinner = document.querySelector('.se-pre-con');
	const pilihNegeri = document.getElementById('pilih_negeri');
	const pilihZone = document.getElementById('pilih_zone');
	const results = document.getElementById('results');

	// Hide loading spinner initially
	spinner.style.display = 'none';

	// Helper function to show spinner
	function showSpinner() {
		spinner.style.display = 'block';
	}

	// Helper function to hide spinner
	function hideSpinner() {
		spinner.style.display = 'none';
	}

	// On load, fetch list of states and append to UI
	showSpinner();
	fetch('../../api.php?getStates')
		.then(response => response.json())
		.then(data => {
			let stateslist = '';
			// Handle both array and object responses
			const states = Array.isArray(data) ? data : Object.values(data);
			states.forEach(state => {
				stateslist += `<option value="${state}">${state}</option>`;
			});
			pilihNegeri.insertAdjacentHTML('beforeend', stateslist);
			hideSpinner();
		})
		.catch(error => {
			console.error('Error fetching states:', error);
			hideSpinner();
		});

	// Event listener for state selection change
	pilihNegeri.addEventListener('change', function() {
		showSpinner();
		pilihZone.innerHTML = '';
		results.innerHTML = '';
		const negeri = pilihNegeri.value;

		// Fetch zones for selected state
		fetch(`../../api.php?stateName=${encodeURIComponent(negeri)}`)
			.then(response => response.json())
			.then(data => {
				let zonelist = '';

				Object.entries(data).forEach(([key, val]) => {
					zonelist += `<option value="${key}">${val}</option>`;
				});

				const textpilih = '<option value="">Pilih Zon</option>';
				pilihZone.innerHTML = textpilih + zonelist;
				hideSpinner();
			})
			.catch(error => {
				console.error('Error fetching zones:', error);
				hideSpinner();
			});
	});

	// Event listener for zone selection change
	pilihZone.addEventListener('change', function() {
		showSpinner();
		results.innerHTML = '';

		const codeZon = pilihZone.value;
		const apiURL = `../../api.php?zon=${codeZon}`;

		fetch(apiURL)
			.then(response => response.json())
			.then(data => {
				const imsak = data["waktu_imsak"];
				const subuh = data["waktu_subuh"];
				const syuruk = data["waktu_syuruk"];
				const zohor = data["waktu_zohor"];
				const asar = data["waktu_asar"];
				const maghrib = data["waktu_maghrib"];
				const isyak = data["waktu_isyak"];

				const tableHTML = `<div class='table-responsive'>
					<table class='table table-bordered table-hover mb-0'>
						<tr><th>Imsak</th><td>${imsak}</td></tr>
						<tr><th>Subuh</th><td>${subuh}</td></tr>
						<tr><th>Syuruk</th><td>${syuruk}</td></tr>
						<tr><th>Zohor</th><td>${zohor}</td></tr>
						<tr><th>Asar</th><td>${asar}</td></tr>
						<tr><th>Maghrib</th><td>${maghrib}</td></tr>
						<tr><th>Isyak</th><td>${isyak}</td></tr>
					</table>
				</div>`;

				const cardHTML = `<div class="card">
					<div class="card-body">
						${tableHTML}
					</div>
				</div>`;

				// Insert with fade effect
				results.style.opacity = '0';
				results.innerHTML = cardHTML;
				results.style.display = 'block';

				// Fade in animation
				setTimeout(() => {
					results.style.transition = 'opacity 0.5s';
					results.style.opacity = '1';
				}, 10);

				hideSpinner();
			})
			.catch(error => {
				console.error('Error fetching prayer times:', error);
				hideSpinner();
			});
	});

});
