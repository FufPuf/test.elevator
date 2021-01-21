<?php
require_once "/Users/nikolaypetrov/Vaimo/test/test.elevator/src/Passenger/PassengerInterface/PassengerInterface.php";
require_once "/Users/nikolaypetrov/Vaimo/test/test.elevator/src/Elevator/AbstractElevator/AbstractElevator.php";
require_once "/Users/nikolaypetrov/Vaimo/test/test.elevator/src/Elevator/AbstractElevator/ElevatorInterface/ElevatorInterface.php";
require_once "/Users/nikolaypetrov/Vaimo/test/test.elevator/src/Passenger/Passenger.php";

class Elevator extends AbstractElevator
{
    static $elevatorLocation = 1;
    static $passengersId = [];
    static $enteredFloors = [];
    static $requiredFloors = [];
    static $passengersWeight = [];
    static $passengerCourse = [];
    static $elevatorStatus = [];
    private $maxWeight = 600;

    public function __construct(PassengerInterface $passenger)
    {
        $id = $passenger->getID();
        self::$passengersId[$id] = $id;
        self::$enteredFloors[$id] = $passenger->getEnteredFloor();
        self::$requiredFloors[$id] = $passenger->getRequiredFloor();
        self::$passengersWeight[$id] = $passenger->getPassengerWeight();
        self::$passengerCourse[$id] = $passenger->getPassengerCourse();
    }

    public function startWork()
    {
        $typeOfWork = [];

        if(count(self::$enteredFloors)) {
            if (empty(self::$elevatorStatus['course'])) {
                $typeOfWork['empty'] = $this->checkCloser(self::$enteredFloors);
                if ($this->checkCourse()) {
                    if (self::$elevatorStatus['course'] == 'up') {
                        $this->moveUp($typeOfWork);
                    } else {
                        $this->moveDown($typeOfWork);
                    }
                }
            }
            if (!empty(self::$elevatorStatus['course'])) {
                $typeOfWork['noEmpty'] = $this->nextFloor();
                if (self::$elevatorStatus['course'] == 'up') {
                    $this->moveUp($typeOfWork);
                } else {
                    $this->moveDown($typeOfWork);
                }
            }
        }
    }

    private function moveUp($typeOfWork)
    {
        if (array_key_exists('empty', $typeOfWork)) {
            $floorNeeded = $typeOfWork['empty'];
            $this->moveTo($floorNeeded);
            $this->elevatorStop($floorNeeded);
            self::$elevatorLocation = $floorNeeded;
            $this->openDoor(self::$elevatorLocation);
            $this->pickUp();
            $this->closeDoor(self::$elevatorLocation);
        }
        if (array_key_exists('noEmpty', $typeOfWork)) {
            $floorNeeded = $typeOfWork['noEmpty'];
                $this->moveTo($floorNeeded);
                $this->elevatorStop($floorNeeded);
                self::$elevatorLocation = $floorNeeded;
                $this->openDoor(self::$elevatorLocation);
                if (!empty($this->checkRequired())) {
                    $this->dropOff();
                }
                if (!empty($this->checkEntered())) {
                    $this->pickUp();
                }

                $this->closeDoor(self::$elevatorLocation);

        }

        $this->checkQueue();
    }

    private function moveDown(array $typeOfWork)
    {

        if (array_key_exists('empty', $typeOfWork)) {
            $floorNeeded = $typeOfWork['empty'];
            $this->moveTo($floorNeeded);
            $this->elevatorStop($floorNeeded);
            self::$elevatorLocation = $floorNeeded;
            $this->openDoor(self::$elevatorLocation);
            $this->pickUp();
            $this->closeDoor(self::$elevatorLocation);
        }
        if (array_key_exists('noEmpty', $typeOfWork)) {
            $floorNeeded = $typeOfWork['noEmpty'];

                $this->moveTo($floorNeeded);
                $this->elevatorStop($floorNeeded);
                self::$elevatorLocation = $floorNeeded;
                $this->openDoor(self::$elevatorLocation);
                if (!empty($this->checkRequired())) {
                    $this->dropOff();
                }
                if (!empty($this->checkEntered())) {
                    $this->pickUp();
                }
                $this->closeDoor(self::$elevatorLocation);
            }


        $this->checkQueue();
    }

    private function nextFloor()
    {
        if(!empty(self::$enteredFloors)) {
            $elevatorLocation = self::$elevatorLocation;
            if (self::$elevatorStatus['course'] == 'up') {
                for ($elevatorLocation; ; $elevatorLocation++) {
                    if ($elevatorLocation != self::$elevatorLocation) {
                        if (in_array($elevatorLocation, self::$enteredFloors) || in_array($elevatorLocation, self::$requiredFloors)) {
                            return $elevatorLocation;
                        }
                    }
                }
            }

            for ($elevatorLocation; ; $elevatorLocation--) {
                    if ($elevatorLocation != self::$elevatorLocation) {
                        self::$elevatorLocation = $elevatorLocation;
                        if (in_array($elevatorLocation, self::$enteredFloors) || in_array($elevatorLocation, self::$requiredFloors)) {
                            return $elevatorLocation;
                        }
                    }
                }

                self::$elevatorLocation = $elevatorLocation;

        } else {
            unset(self::$elevatorStatus['course']);
            $this->startWork();
        }
    }

    private function checkCourse(): bool
    {
        $checkId = $this->checkEntered();

        if (self::$elevatorStatus['course']) {
            if (self::$elevatorStatus['course'] === self::$passengerCourse[$checkId]) {
                return true;
            }
        }
        if (!self::$elevatorStatus['course']) {
            self::$elevatorStatus['course'] = self::$passengerCourse[$checkId];

            return true;
        }

        return false;
    }

    private function pickUp()
    {
        $checkId = $this->checkEntered();
        if (!is_null($checkId)) {
            self::$elevatorStatus[$checkId]['entered'] = self::$enteredFloors[$checkId];
            self::$elevatorStatus[$checkId]['required'] = self::$requiredFloors[$checkId];
            self::$elevatorStatus[$checkId]['weight'] = self::$passengersWeight[$checkId];
            echo "Passenger entered </br>";
        }
    }

    private function dropOff()
    {
        $checkId = $this->checkRequired();
        if ($this->checkRequired()) {
            unset(self::$elevatorStatus[$checkId]);
            echo "Passenger left </br>";
        }
        unset(self::$enteredFloors[$checkId]);
        unset(self::$requiredFloors[$checkId]);
        unset(self::$passengersWeight[$checkId]);
        unset(self::$passengersId[$checkId]);
        unset(self::$requiredFloors[$checkId]);
        unset(self::$passengerCourse[$checkId]);
    }

    private function checkCloser($floors): int
    {
        $min = min($floors);
        $max = max($floors);
        $result = 0;
        for ($i = self::$elevatorLocation, $y = self::$elevatorLocation; $i >= $min || $y <= $max; $i--, $y++) {

            if (in_array($i, $floors)) {
                $result = $i;
                break;
            }
            if (in_array($y, $floors)) {
                $result = $y;
                break;
            }
        }

        return $result;
    }

    private function checkQueue()
    {
        if(!empty(self::$elevatorStatus) || !empty(self::$enteredFloors) || !empty(self::$requiredFloors)) {
            $this->startWork();
        }
    }

    private function checkEntered()
    {
        $result = null;
        if (array_search(self::$elevatorLocation, self::$enteredFloors) == true) {
            $result = array_search(self::$elevatorLocation, self::$enteredFloors);
        } else {
            $enteredFloors = [];
            foreach (self::$elevatorStatus as $id => $passenger) {
                if ($id != 'course') {
                    $enteredFloors[$id] = $passenger['entered'];
                }
            }
            $result = array_search(self::$elevatorLocation, $enteredFloors);
        }

        return $result;

    }

    private function checkRequired()
    {
        $result = null;
        if (array_search(self::$elevatorLocation, self::$requiredFloors) == true) {
            $result = array_search(self::$elevatorLocation, self::$requiredFloors);
        }

        return $result;
    }

    private function checkWeight(): bool
    {
        $weight = 0;
        foreach (self::$elevatorStatus as $key => $passenger) {
            if ($key != 'course') {
                $weight += $passenger['weight'];
            }
        }

        return $weight < $this->maxWeight;
    }
}