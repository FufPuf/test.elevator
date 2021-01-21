<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);

require_once "/Users/nikolaypetrov/Vaimo/test/test.elevator/src/Passenger/PassengerInterface/PassengerInterface.php";
require_once "/Users/nikolaypetrov/Vaimo/test/test.elevator/src/Elevator/AbstractElevator/AbstractElevator.php";
require_once "/Users/nikolaypetrov/Vaimo/test/test.elevator/src/Elevator/AbstractElevator/ElevatorInterface/ElevatorInterface.php";
require_once "/Users/nikolaypetrov/Vaimo/test/test.elevator/src/Passenger/Passenger.php";
require_once "/Users/nikolaypetrov/Vaimo/test/test.elevator/src/Elevator/Elevator.php";




$elevator = new Elevator(new Passenger(1,2,80));
$elevator = new Elevator(new Passenger(3,6,100));
$elevator = new Elevator(new Passenger(2,9,60));
$elevator = new Elevator(new Passenger(8,12,60));
//$elevator = new Elevator(new Passenger(5,1,100));
//$elevator = new Elevator(new Passenger(3,10,60));
//$elevator = new Elevator(new Passenger(8,12,60));


$elevator->startWork();

echo "<pre>";
echo 'status ';
var_dump(Elevator::$elevatorStatus);
echo 'loc ';
var_dump(Elevator::$elevatorLocation);
echo 'entered ';
var_dump(Elevator::$enteredFloors);
echo 'required ';
var_dump(Elevator::$requiredFloors);
echo 'id ';
var_dump(Elevator::$passengersWeight);
echo 'weight ';
var_dump(Elevator::$passengersWeight);
echo "</pre>";

