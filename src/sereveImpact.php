<?php

// SevereImpact Class
class SevereImpact implements JsonSerializable
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

    public function gethospitalBedsByRequestedTime() 
    {
        return $this->hospitalBedsByRequestedTime;
    }

    public function jsonSerialize()
    {
        return 
        [
            'currentlyInfected'   => $this->getCurrentlyInfected(),
            'infectionsByRequestedTime' => $this->getInfectionsByRequestedTime(),
            'severeCasesByRequestedTime' => $this->getsevereCasesByRequestedTime(),
            'hospitalBedsByRequestedTime' => $this->gethospitalBedsByRequestedTime()
        ];
    }
}

// returns the severeImpact data
function severeImpact($data){
    // calculate currentlyInfected
    $currentlyInfected = $data["reportedCases"] * 50;
    
    // find the period type to calculate the days correct format
    if($data['periodType'] == "days"){
        $days = $data["timeToElapse"];
    }else if($data['periodType'] == "weeks"){
        $weeks = $data["timeToElapse"];
        $days = $weeks * 7;
    }else if($data['periodType'] == "months"){
        $months = $data["timeToElapse"];
        $days = $months * 30;
    }

    // get the factor and calculate the infectionsByRequestedTime
    $factor = floor(($days / 3));
    $infectionsByRequestedTime =($currentlyInfected * pow(2, $factor));

    // calculate the estimated number of severe positive cases that will require hospitalization to recover
    $percent = 0.15;
    $severeCasesByRequestedTime = ($percent * $infectionsByRequestedTime);

    // calculate the number of available hospital beds for severe COVID-19 positive patients by the requested time
    $totalHospitalBeds = $data['totalHospitalBeds'];
    $availableBedsforPositivePatients = floor(0.35 * $totalHospitalBeds);
    $hospitalBedsByRequestedTime = ($availableBedsforPositivePatients - $severeCasesByRequestedTime);

    // create an instance of SevereImpact
    $severeImpact_obj = new SevereImpact(array(
        'currentlyInfected' => $currentlyInfected, 
        'infectionsByRequestedTime' => $infectionsByRequestedTime,
        'severeCasesByRequestedTime' => $severeCasesByRequestedTime,
        'hospitalBedsByRequestedTime' => $hospitalBedsByRequestedTime
    ));

    $severeImpact_arr = json_decode(json_encode($severeImpact_obj), true);

    return $severeImpact_arr;
}

?>