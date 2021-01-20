<?php

require_once "/Users/nikolaypetrov/Vaimo/test/test.elevator/src/Elevator/AbstractElevator/ElevatorInterface/ElevatorInterface.php";
require_once "/Users/nikolaypetrov/Vaimo/test/test.elevator/src/Passenger/PassengerInterface/PassengerInterface.php";

abstract class AbstractElevator implements ElevatorInterface
{

    abstract public function __construct(PassengerInterface $passenger);

    abstract public function move();

    protected function elevatorStop($floor)
    {
        echo "Elevator stop on " . $floor . "</br>";
    }

    protected function openDoor($floor)
    {
        echo "Open door on " . $floor . "</br>";
    }

    protected function closeDoor($floor)
    {
        echo "Close door on " . $floor . "</br>";
    }

    protected function moveUp($floor)
    {
        echo "Move Up to " . $floor . "</br>";
    }

    protected function moveDown($floor)
    {
        echo "Move Down to " . $floor . "</br>";
    }
}