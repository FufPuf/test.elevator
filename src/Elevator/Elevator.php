<?php

require_once "/Users/nikolaypetrov/Vaimo/test/test.elevator/src/Passenger/PassengerInterface/PassengerInterface.php";
require_once "/Users/nikolaypetrov/Vaimo/test/test.elevator/src/Elevator/AbstractElevator/AbstractElevator.php";
require_once "/Users/nikolaypetrov/Vaimo/test/test.elevator/src/Elevator/AbstractElevator/ElevatorInterface/ElevatorInterface.php";
require_once "/Users/nikolaypetrov/Vaimo/test/test.elevator/src/Passenger/Passenger.php";

class Elevator extends AbstractElevator
{
    private static $elevatorLocation = 1;
    private static $passengersId = [];
    private static $enteredFloors = [];
    private static $requiredFloors = [];
    private static $passengersWeight = [];
    public static $elevatorStatus = [];
    private $maxWeight = 600;

    public function __construct(PassengerInterface $passenger)
    {
        $id = $passenger->getID();
        self::$passengersId[] = $id;
        self::$enteredFloors[$id] = $passenger->getEnteredFloor();
        self::$requiredFloors[$id] = $passenger->getRequiredFloor();
        self::$passengersWeight[$id] = $passenger->getPassengerWeight();
        return $this;
    }

    public function move()
    {
        if(empty(self::$elevatorStatus)) {
            $this->ifEmpty();
        }

        if(!empty(self::$elevatorStatus)) {

        }
    }

    private function ifEmpty()
    {
        $closer = $this->checkCloser(self::$enteredFloors);

        if($this->checkWeight()) {

            if($closer > self::$elevatorLocation) {
                $this->moveUp($closer);
                $this->elevatorStop($closer);
                self::$elevatorLocation = $closer;
                $this->openDoor(self::$elevatorLocation);
                $this->pickUp();
                $this->closeDoor(self::$elevatorLocation);
            }

            if($closer < self::$elevatorLocation) {

                $this->moveUp($closer);
                $this->elevatorStop($closer);
                self::$elevatorLocation = $closer;
                $this->openDoor(self::$elevatorLocation);
                $this->pickUp();
                $this->closeDoor(self::$elevatorLocation);
            }
        }
    }

    private function ifNoEmpty()
    {

    }

    private function pickUp()
    {
        if($this->checkEntered()) {
            self::$elevatorStatus[$this->checkEntered()]['required'] = self::$requiredFloors[$this->checkEntered()];
            self::$elevatorStatus[$this->checkEntered()]['weight'] = self::$passengersWeight[$this->checkEntered()];
            unset(self::$enteredFloors[$this->checkRequired()]);
            unset(self::$requiredFloors[$this->checkRequired()]);
            unset(self::$passengersWeight[$this->checkRequired()]);
            echo "Passenger entered </br>";
        }
    }

    private function dropOff()
    {
        if($this->checkRequired()) {
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

$elev = new Elevator(new Passenger(3, 2, 80));
$elev = new Elevator(new Passenger(2, 5, 80));
$elev->move();
var_dump(Elevator::$elevatorStatus);