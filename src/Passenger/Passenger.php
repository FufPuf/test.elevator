<?php

require_once "/Users/nikolaypetrov/Vaimo/test/test.elevator/src/Passenger/PassengerInterface/PassengerInterface.php";


class Passenger implements PassengerInterface
{
    private $enteredFloor;
    private $requiredFloor;
    private $passengerCourse;
    private $passengerWeight;
    private $id;

    public function __construct($enteredFloor, $requiredFloor, $passengerWeight)
    {
        $this->enteredFloor = $enteredFloor;
        $this->requiredFloor = $requiredFloor;
        $this->passengerWeight = $passengerWeight;
        $this->id = uniqid();

        if($this->enteredFloor < $this->requiredFloor) {
            $this->passengerCourse = 'up';
        } else {
            $this->passengerCourse = 'down';
        }
    }

    public function getEnteredFloor() : int
    {
        return $this->enteredFloor;
    }

    public function getRequiredFloor() : int
    {
        return $this->requiredFloor;
    }

    public function getPassengerCourse() : string
    {
        return $this->passengerCourse;
    }

    public function getPassengerWeight() : int
    {
        return $this->passengerWeight;
    }

    public function getID(): string
    {
        return $this->id;
    }
}

