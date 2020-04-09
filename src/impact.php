<?php

// Impact Class
class Impact implements JsonSerializable
{
    protected $currentlyInfected;
    protected $infectionsByRequestedTime;
    protected $severeCasesByRequestedTime;
    protected $hospitalBedsByRequestedTime;

    public function __construct(array $data) 
    {
        $this->currentlyInfected = $data['currentlyInfected'];
        $this->infectionsByRequestedTime = $data['infectionsByRequestedTime'];
        $this->severeCasesByRequestedTime = $data['severeCasesByRequestedTime'];
        $this->hospitalBedsByRequestedTime = $data['hospitalBedsByRequestedTime'];
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

    public function jsonSerialize()
    {
        return 
        [
            'currentlyInfected'   => $this->getCurrentlyInfected(),
            'infectionsByRequestedTime' => $this->getInfectionsByRequestedTime(),
            'severeCasesByRequestedTime' => $this->getSevereCasesByRequestedTime(),
            'hospitalBedsByRequestedTime' => $this->getHospitalBedsByRequestedTime()
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
    $factor = intval($days / 3); 

    // calculate infectionsByRequestedTime
    $infectionsByRequestedTime = $currentlyInfected * (2 ** $factor);

    // calculate the estimated number of severe positive cases that will require hospitalization to recover
    $severeCasesByRequestedTime = (0.15 * $infectionsByRequestedTime);

    // calculate the number of available hospital beds for severe COVID-19 positive patients by the requested time
    $totalHospitalBeds = $data['totalHospitalBeds'];
    $availableBedsforPositivePatients = (0.35 * $totalHospitalBeds);
    $hospitalBedsByRequestedTime = ceil(($availableBedsforPositivePatients - $severeCasesByRequestedTime));

    // create an instance of Impact
    $impact_obj = new Impact(array(
        'currentlyInfected' => $currentlyInfected,  // int
        'infectionsByRequestedTime' => $infectionsByRequestedTime, //int
        'severeCasesByRequestedTime' => $severeCasesByRequestedTime, //int
        'hospitalBedsByRequestedTime' => $hospitalBedsByRequestedTime //int
    ));

    $impact_arr = json_decode(json_encode($impact_obj), true);

    return $impact_arr;
}

?>