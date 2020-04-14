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

