<?php

require 'impact.php';
require 'sereveImpact.php';

$json_data = file_get_contents("data.json");
$data = json_decode($json_data, true);

function covid19ImpactEstimator($data)
{
  $impact = impact($data);
  $severeImpact = severeImpact($data);

  $result = array(
    "data" => $data,
    "impact" =>  $impact,
    "severeImpact" => $severeImpact
  );

  var_dump($result);
  die();

  return $result;
}

$result = covid19ImpactEstimator($data);
// $data = json_encode($result, true);
// echo $data;

