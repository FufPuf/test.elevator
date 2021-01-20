<?php

require_once "/Users/nikolaypetrov/Vaimo/test/test.elevator/src/Passenger/PassengerInterface/PassengerInterface.php";
require_once "/Users/nikolaypetrov/Vaimo/test/test.elevator/src/Elevator/AbstractElevator/AbstractElevator.php";
require_once "/Users/nikolaypetrov/Vaimo/test/test.elevator/src/Elevator/AbstractElevator/ElevatorInterface/ElevatorInterface.php";

class Elevator extends AbstractElevator
{
    private static $elevatorLocation = 1;
    private static $passengersId = [];
    private static $enteredFloors = [];
    private static $requiredFloors = [];
    private static $passengersWeight = [];
    private static $elevatorStatus = [];
    private $maxWeight = 600;

    public function __construct(PassengerInterface $passenger)
    {
        $id = $passenger->getID();
        self::$passengersId = $id;
        self::$enteredFloors[$id] = $passenger->getEnteredFloor();
        self::$requiredFloors[$id] = $passenger->getRequiredFloor();
        self::$passengersWeight[$id] = $passenger->getPassengerWeight();
    }

    public function move()
    {
        $closer = $this->checkCloser(self::$enteredFloors);
        if($this->checkWeight()) {
            if($closer > self::$elevatorLocation) {
                $this->moveUp();
                while ($closer == self::$elevatorLocation) {
                    self::$elevatorLocation++;
                }
            }
            if($closer > self::$elevatorLocation) {
                $this->moveDown();
                while ($closer == self::$elevatorLocation) {
                    self::$elevatorLocation--;
                }
            }
        }
    }

    private function pickUp()
    {
        if($this->checkEntered()) {
            self::$elevatorStatus[$this->checkEntered()]['required'] = self::$requiredFloors[$this->checkEntered()];
            self::$elevatorStatus[$this->checkEntered()]['weight'] = self::$passengersWeight[$this->checkEntered()];
            unset(self::$enteredFloors[$this->checkRequired()]);
            unset(self::$requiredFloors[$this->checkRequired()]);
            unset(self::$passengersWeight[$this->checkRequired()]);
        }
    }

    private function dropOff()
    {
        if($this->checkRequired()) {
            unset(self::$elevatorStatus[$this->checkRequired()]);
            unset(self::$elevatorStatus[$this->checkRequired()]);
        }
    }

    private function checkCloser($floors): int
    {
        $min = min($floors);
        $max = max($floors);
        $result = 0;

        for($i = self::$elevatorLocation, $y = self::$elevatorLocation; $i >= $min || $y <= $max; $i--, $y++) {

            if(in_array($i, $floors)) {
                $result = $i;
                break;
            }

            if(in_array($y, $floors)) {
                $result = $y;
                break;
            }
        }
        return $result;
    }

    private function checkEntered()
    {
        $result = '';
        if(array_search(self::$elevatorLocation, self::$enteredFloors) != false) {
            $result = array_search(self::$elevatorLocation, self::$enteredFloors);
        }
        return $result;
    }

    private function checkRequired()
    {
        $result = '';
        if(array_search(self::$elevatorLocation, self::$requiredFloors) != false)  {
            $result = array_search(self::$elevatorLocation, self::$requiredFloors);
        }
        return $result;
    }

    private function checkWeight(): bool
    {
        $weight = 0;
        foreach(self::$elevatorStatus as $passenger) {
            $weight += $passenger['weight'];
        }
        return $weight < $this->maxWeight;
    }

}

$elev = new Elevator(new Passenger(1, 2, 80));