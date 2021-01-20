<?php

require_once "/Users/nikolaypetrov/Vaimo/test/test.elevator/src/Elevator/AbstractElevator/ElevatorInterface/ElevatorInterface.php";


abstract class AbstractElevator implements ElevatorInterface
{

    abstract public function __construct(Passenger $passenger);

    abstract public function move();

    protected function elevatorStop()
    {
        echo "Elevator stop";
    }

    protected function openDoor()
    {
        echo "Open door";
    }

    protected function closeDoor()
    {
        echo "Close door";
    }

    protected function moveUp()
    {
        echo "Move Up";
    }

    protected function moveDown()
    {
        echo "Move Down";
    }
}