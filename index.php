<!DOCTYPE html>
<html lang="en">
	<head>
		<!-- Required meta tags -->
		<meta charset="utf-8" />
		<meta
			name="description"
			content="Covid-19 Estimator is a novelty COVID-19 infections estimator"
		/>
		<meta name="keywords" content="Covid-19 Estimator" />
		<meta name="author" content="Victor Allen" />
		<meta
			name="viewport"
			content="width=device-width, initial-scale=1, shrink-to-fit=no"
		/>
		<meta name="”robots”" content="”index," follow” />

		<link rel="stylesheet" href="css/main.css" />
		<title>Covid-19 Estimator</title>
	</head>
	<body>
		<div class="heading">
			<h1>COVID-19 Estimator</h1>
		</div>

		<div class="container">
			<form method="POST" id="insert_form">
				<div class="row">
					<div class="col-25">
						<label for="population">Population</label>
					</div>
					<div class="col-75">
						<input
							type="text"
							class="form-control"
							id="population"
							name="population"
							data-population
							placeholder="Enter population"
							required
						/>
					</div>
				</div>
				<div class="row">
					<div class="col-25">
						<label for="timeToElapse">Time To Elapse</label>
					</div>
					<div class="col-75">
						<input
							type="text"
							class="form-control"
							id="timeToElapse"
							name="timeToElapse"
							data-time-to-elapse
							placeholder="Enter Time To Elapse"
							required
						/>
					</div>
				</div>
				<div class="row">
					<div class="col-25">
						<label for="reportedCases">Reported Cases</label>
					</div>
					<div class="col-75">
						<input
							type="text"
							class="form-control"
							id="reportedCases"
							name="reportedCases"
							data-reported-cases
							placeholder="Enter Reported Cases"
							required
						/>
					</div>
				</div>
				<div class="row">
					<div class="col-25">
						<label for="totalHospitalBeds">Total Hospital Beds</label>
					</div>
					<div class="col-75">
						<input
							type="text"
							class="form-control"
							id="totalHospitalBeds"
							name="totalHospitalBeds"
							data-total-hospital-beds
							placeholder="Enter Reported Cases"
							required
						/>
					</div>
				</div>
				<div class="row">
					<div class="col-25">
						<label for="periodType">Period Type</label>
					</div>
					<div class="col-75">
						<select id="periodType" name="periodType" data-period-type>
							<option value="days">Days</option>
							<option value="weeks">Weeks</option>
							<option value="months">Months</option>
						</select>
					</div>
				</div>
				<div class="row">
					<input type="submit" id="send_data" value="Go Estimate!" data-go-estimate />
				</div>
			</form>
		
			<!-- RESULTS -->
			<hr class="hr-line" />
			<div class="d-flex justify-content-center">
				<h2>RESULTS</h2>
			</div>
			<hr class="hr-line" />
			<div class="row m-md-4">
				<div class="col-md-6">
					<h2>Impact</h2>
					<p>Currently Infected:</p>
					<p id="impactCurrentlyInfected"></p>
					<p>Infections By Requested Time:</p>
					<p id="impactInfectionsByRequestedTime"></p>
					<p>Severe Cases By Requested Time:</p>
					<p id="impactSevereCasesByRequestedTime"></p>
					<p>Hospital Beds By Requested Time:</p>
					<p id="impactHospitalBedsByRequestedTime"></p>
					<p>Cases For ICU By Requested Time:</p>
					<p id="impactCasesForICUByRequestedTime"></p>
					<p>Cases For Ventilators By Requested Time:</p>
					<p id="impactCasesForVentilatorsByRequestedTime"></p>
					<p>Dollars In Flight:</p>
					<p id="impactDollarsInFlight"></p>
				</div>
				<div class="col-md-6">
					<h2>Severe Impact</h2>
					<p>Currently Infected:</p>
					<p id="severeImpactCurrentlyInfected"></p>
					<p>Infections By Requested Time:</p>
					<p id="severeImpactInfectionsByRequestedTime"></p>
					<p>Severe Cases By Requested Time:</p>
					<p id="severeImpactSevereCasesByRequestedTime"></p>
					<p>Hospital Beds By Requested Time:</p>
					<p id="severeImpactHospitalBedsByRequestedTime"></p>
					<p>Cases For ICU By Requested Time:</p>
					<p id="severeImpactCasesForICUByRequestedTime"></p>
					<p>Cases For Ventilators By Requested Time:</p>
					<p id="severeImpactCasesForVentilatorsByRequestedTime"></p>
					<p>Dollars In Flight:</p>
					<p id="severeImpactDollarsInFlight"></p>
				</div>
			</div>
		</div>

		<!-- JavaScript -->
		<script type="text/javascript" src="js/jquery.js"></script>
		<script type="text/javascript">
			//Ajax for Sending data
			$("#insert_form").on("submit", function (event) {
				event.preventDefault();
				$.ajax({
					url: "src/estimator.php",
					method: "POST",
					data: $("#insert_form").serialize(),
					beforeSend: function () {
						document.getElementById("send_data").innerHTML = "Calculating...";
					},
					success: function (result) {
						document.getElementById("send_data").innerHTML =
							"Go Estimate Again!";
						// parse the array to an object
						var obj = JSON.parse(result);
						// Impact Results
						document.getElementById("impactCurrentlyInfected").innerHTML =
							obj.impact.currentlyInfected;
						document.getElementById(
							"impactInfectionsByRequestedTime"
						).innerHTML = obj.impact.infectionsByRequestedTime;
						document.getElementById(
							"impactSevereCasesByRequestedTime"
						).innerHTML = obj.impact.severeCasesByRequestedTime;
						document.getElementById(
							"impactHospitalBedsByRequestedTime"
						).innerHTML = obj.impact.hospitalBedsByRequestedTime;
						document.getElementById(
							"impactCasesForICUByRequestedTime"
						).innerHTML = obj.impact.casesForICUByRequestedTime;
						document.getElementById(
							"impactCasesForVentilatorsByRequestedTime"
						).innerHTML = obj.impact.casesForVentilatorsByRequestedTime;
						document.getElementById("impactDollarsInFlight").innerHTML =
							obj.impact.dollarsInFlight;
						// Severe Impacts Results
						document.getElementById("severeImpactCurrentlyInfected").innerHTML =
							obj.severeImpact.currentlyInfected;
						document.getElementById(
							"severeImpactInfectionsByRequestedTime"
						).innerHTML = obj.severeImpact.infectionsByRequestedTime;
						document.getElementById(
							"severeImpactSevereCasesByRequestedTime"
						).innerHTML = obj.severeImpact.severeCasesByRequestedTime;
						document.getElementById(
							"severeImpactHospitalBedsByRequestedTime"
						).innerHTML = obj.severeImpact.hospitalBedsByRequestedTime;
						document.getElementById(
							"severeImpactCasesForICUByRequestedTime"
						).innerHTML = obj.severeImpact.casesForICUByRequestedTime;
						document.getElementById(
							"severeImpactCasesForVentilatorsByRequestedTime"
						).innerHTML = obj.severeImpact.casesForVentilatorsByRequestedTime;
						document.getElementById("severeImpactDollarsInFlight").innerHTML =
							obj.severeImpact.dollarsInFlight;
					},
				});
			});
		</script>
	</body>
</html>
