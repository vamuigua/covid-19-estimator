<?php

require 'impact.php';
require 'sereveImpact.php';

function covid19ImpactEstimator($data)
{
  $impact = impact($data);
  $severeImpact = severeImpact($data);

  $result = array(
    "data" => $data,
    "impact" =>  $impact,
    "severeImpact" => $severeImpact
  );

  return $result;
}

if(!empty($_POST)){
  $population = $_POST['population'];
  $timeToElapse = $_POST['timeToElapse'];
  $reportedCases = $_POST['reportedCases'];
  $totalHospitalBeds = $_POST['totalHospitalBeds'];
  $periodType = $_POST['periodType']; 

  $data = array(
    "region" => array(
        "name" => "Africa",
        "avgAge" => 19.7,
        "avgDailyIncomeInUSD" => 4,
        "avgDailyIncomePopulation" => 0.66
    ),
    'population' => $population,
    'timeToElapse' => $timeToElapse,
    'reportedCases' => $reportedCases,
    'totalHospitalBeds' => $totalHospitalBeds,
    'periodType' => $periodType
  );
  
  //return the result as an array
  $result = covid19ImpactEstimator($data);
  $result = json_encode($result);
  echo $result;
}

