<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <title>Covid-19 Estimator</title>
  </head>
  <body>
	<div class="container">
		<h1>COVID-19 Estimator</h1>
		<div class="row">
			<div class="col-md-12">
				<h2>Enter data in the form</h2>
				<form method="POST" id="insert_form">
					<div class="form-group">
						<label for="population">Population</label>
						<input type="number" class="form-control" id="population" name="population" data-population placeholder="Enter population" required>
					</div>
					<div class="form-group">
						<label for="timeToElapse">Time To Elapse</label>
						<input type="number" class="form-control" id="timeToElapse" name="timeToElapse" data-time-to-elapse placeholder="Enter Time To Elapse" required>
					</div>
					<div class="form-group">
						<label for="reportedCases">Reported Cases</label>
						<input type="number" class="form-control" id="reportedCases" name="reportedCases" data-reported-cases placeholder="Enter Reported Cases" required>
					</div>
					<div class="form-group">
						<label for="totalHospitalBeds">Total Hospital Beds</label>
						<input type="number" class="form-control" id="totalHospitalBeds" name="totalHospitalBeds" data-total-hospital-beds placeholder="Enter Reported Cases" required>
					</div>
					<div class="form-group">
						<label for="periodType">Period Type</label>
						<select class="form-control" id="periodType" class="periodType" name="periodType" data-period-type>
							<option value="days">Days</option>
							<option value="weeks">Weeks</option>
							<option value="months">Months</option>
						</select>
					</div>

					<button type="submit" id="send_data" class="btn btn-primary" data-go-estimate>Go Estimate!</button>
				</form>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6" id="impactResults">
				<h2>Impact</h2>
				<p>Currently Infected:</p><p id="impactCurrentlyInfected"></p>
				<p>Infections By Requested Time:</p><p id="impactInfectionsByRequestedTime"></p>
				<p>Severe Cases By Requested Time:</p><p id="impactSevereCasesByRequestedTime"></p>
				<p>Hospital Beds By Requested Time:</p><p id="impactHospitalBedsByRequestedTime"></p>
				<p>Cases For ICU By Requested Time:</p><p id="impactCasesForICUByRequestedTime"></p>
				<p>Cases For Ventilators By Requested Time:</p><p id="impactCasesForVentilatorsByRequestedTime"></p>
				<p>Dollars In Flight:</p><p id="impactDollarsInFlight"></p>
			</div>
			<div class="col-md-6" id="severeImpactResults">
				<h2>Severe Impact</h2>
				<p>Currently Infected:</p><p id="severeImpactCurrentlyInfected"></p>
				<p>Infections By Requested Time:</p><p id="severeImpactInfectionsByRequestedTime"></p>
				<p>Severe Cases By Requested Time:</p><p id="severeImpactSevereCasesByRequestedTime"></p>
				<p>Hospital Beds By Requested Time:</p><p id="severeImpactHospitalBedsByRequestedTime"></p>
				<p>Cases For ICU By Requested Time:</p><p id="severeImpactCasesForICUByRequestedTime"></p>
				<p>Cases For Ventilators By Requested Time:</p><p id="severeImpactCasesForVentilatorsByRequestedTime"></p>
				<p>Dollars In Flight:</p><p id="severeImpactDollarsInFlight"></p>
			</div>
		</div>
	</div>

	<!-- Optional JavaScript -->
	<script type="text/javascript" src="js/jquery.js"></script>
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
	<script type="text/javascript">
		//Ajax for Sending data
		$('#insert_form').on("submit", function(event){
			event.preventDefault();  
			$.ajax({
				url:"src/estimator.php",
				method:"POST",
				data:$('#insert_form').serialize(),
				beforeSend:function(){
				document.getElementById("send_data").innerHTML = "Calculating...";
			},
			success:function(result){
				// $('#insert_form')[0].reset();
				document.getElementById("send_data").innerHTML = "Go Estimate Again!";
				// parse the array to an object
				var obj = JSON.parse(result);
				// Impact Results
				document.getElementById("impactCurrentlyInfected").innerHTML = obj.impact.currentlyInfected;
				document.getElementById("impactInfectionsByRequestedTime").innerHTML = obj.impact.infectionsByRequestedTime;
				document.getElementById("impactSevereCasesByRequestedTime").innerHTML = obj.impact.severeCasesByRequestedTime;
				document.getElementById("impactHospitalBedsByRequestedTime").innerHTML = obj.impact.hospitalBedsByRequestedTime;
				document.getElementById("impactCasesForICUByRequestedTime").innerHTML = obj.impact.casesForICUByRequestedTime;
				document.getElementById("impactCasesForVentilatorsByRequestedTime").innerHTML = obj.impact.casesForVentilatorsByRequestedTime;
				document.getElementById("impactDollarsInFlight").innerHTML = obj.impact.dollarsInFlight;
				// Severe Impacts
				document.getElementById("severeImpactCurrentlyInfected").innerHTML = obj.severeImpact.currentlyInfected;
				document.getElementById("severeImpactInfectionsByRequestedTime").innerHTML = obj.severeImpact.infectionsByRequestedTime;
				document.getElementById("severeImpactSevereCasesByRequestedTime").innerHTML = obj.severeImpact.severeCasesByRequestedTime;
				document.getElementById("severeImpactHospitalBedsByRequestedTime").innerHTML = obj.severeImpact.hospitalBedsByRequestedTime;
				document.getElementById("severeImpactCasesForICUByRequestedTime").innerHTML = obj.severeImpact.casesForICUByRequestedTime;
				document.getElementById("severeImpactCasesForVentilatorsByRequestedTime").innerHTML = obj.severeImpact.casesForVentilatorsByRequestedTime;
				document.getElementById("severeImpactDollarsInFlight").innerHTML = obj.severeImpact.dollarsInFlight;
			}
			});
		});
	</script>	
</body>
</html>