<?php

// Impact Class
class Impact implements JsonSerializable
{
    protected $currentlyInfected;
    protected $infectionsByRequestedTime;
    protected $severeCasesByRequestedTime;
    protected $hospitalBedsByRequestedTime;
    protected $casesForICUByRequestedTime;
    protected $casesForVentilatorsByRequestedTime;
    protected $dollarsInFlight;

    public function __construct(array $data) 
    {
        $this->currentlyInfected = $data['currentlyInfected'];
        $this->infectionsByRequestedTime = $data['infectionsByRequestedTime'];
        $this->severeCasesByRequestedTime = $data['severeCasesByRequestedTime'];
        $this->hospitalBedsByRequestedTime = $data['hospitalBedsByRequestedTime'];
        $this->casesForICUByRequestedTime = $data['casesForICUByRequestedTime'];
        $this->casesForVentilatorsByRequestedTime = $data['casesForVentilatorsByRequestedTime'];
        $this->dollarsInFlight = $data['dollarsInFlight'];
    }

    public function getCurrentlyInfected() 
    {
        return $this->currentlyInfected;
    }
    
    public function getInfectionsByRequestedTime() 
    {
        return $this->infectionsByRequestedTime;
    }

    public function getSevereCasesByRequestedTime() 
    {
        return $this->severeCasesByRequestedTime;
    }

    public function getHospitalBedsByRequestedTime() 
    {
        return $this->hospitalBedsByRequestedTime;
    }

    public function getCasesForICUByRequestedTime() 
    {
        return $this->casesForICUByRequestedTime;
    }

    public function getCasesForVentilatorsByRequestedTime() 
    {
        return $this->casesForVentilatorsByRequestedTime;
    }

    public function getDollarsInFlight() 
    {
        return $this->dollarsInFlight;
    }

    public function jsonSerialize()
    {
        return 
        [
            'currentlyInfected'   => $this->getCurrentlyInfected(),
            'infectionsByRequestedTime' => $this->getInfectionsByRequestedTime(),
            'severeCasesByRequestedTime' => $this->getSevereCasesByRequestedTime(),
            'hospitalBedsByRequestedTime' => $this->getHospitalBedsByRequestedTime(),
            'casesForICUByRequestedTime' => $this->getCasesForICUByRequestedTime(),
            'casesForVentilatorsByRequestedTime' => $this->getCasesForVentilatorsByRequestedTime(),
            'dollarsInFlight' => $this->getDollarsInFlight()
        ];
    }
}

// returns the impact data as an array
function impact($data){
    // calculate currentlyInfected
    $currentlyInfected = $data["reportedCases"] * 10;

    // find the periodType to calculate the days in the correct format
    if($data['periodType'] === "days"){
        $days = $data["timeToElapse"];
    }
    else if($data['periodType'] === "weeks"){
        $weeks = $data["timeToElapse"];
        $days = $weeks * 7;
    }
    else if($data['periodType'] === "months"){
        $months = $data["timeToElapse"];
        $days = $months * 30;
    }

    // get the factor and calculate the infectionsByRequestedTime
    $factor = floor($days / 3);

    // calculate infectionsByRequestedTime
    $infectionsByRequestedTime = floor($currentlyInfected * (2 ** $factor));

    // calculate the estimated number of severe positive cases that will require hospitalization to recover
    $severeCasesByRequestedTime = floor(0.15 * $infectionsByRequestedTime);

    // calculate the number of available hospital beds for severe COVID-19 positive patients by the requested time
    $totalHospitalBeds = $data['totalHospitalBeds'];
    $availableBedsforPositivePatients = (0.35 * $totalHospitalBeds);
    $hospitalBedsByRequestedTime = round(($availableBedsforPositivePatients - $severeCasesByRequestedTime), 0);
    
    // check if $hospitalBedsByRequestedTime is negative
    if($hospitalBedsByRequestedTime < 0){
        $hospitalBedsByRequestedTime = floor(abs($hospitalBedsByRequestedTime));
        $hospitalBedsByRequestedTime = $hospitalBedsByRequestedTime * -1;
    }

    // calculate casesForICUByRequestedTime
    $casesForICUByRequestedTime = floor(0.05 * $infectionsByRequestedTime);

    // calculate casesForVentilatorsByRequestedTime
    $casesForVentilatorsByRequestedTime = floor(0.02 * $infectionsByRequestedTime);

    // calculate dollarsInFlight
    $avgDailyIncomePopulation = $data['region']['avgDailyIncomePopulation'];
    $avgDailyIncomeInUSD = $data['region']['avgDailyIncomeInUSD'];
    $dollarsInFlight = (($infectionsByRequestedTime * $avgDailyIncomePopulation * $avgDailyIncomeInUSD) / $days);
    $dollarsInFlight = floor($dollarsInFlight);

    // create an instance of Impact
    $impact_obj = new Impact(array(
        'currentlyInfected' => $currentlyInfected, 
        'infectionsByRequestedTime' => $infectionsByRequestedTime, 
        'severeCasesByRequestedTime' => $severeCasesByRequestedTime, 
        'hospitalBedsByRequestedTime' => $hospitalBedsByRequestedTime,
        'casesForICUByRequestedTime' => $casesForICUByRequestedTime,
        'casesForVentilatorsByRequestedTime' => $casesForVentilatorsByRequestedTime,
        'dollarsInFlight' => $dollarsInFlight
    ));

    $impact_arr = json_decode(json_encode($impact_obj), true);

    return $impact_arr;
}

?>