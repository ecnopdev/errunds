<?php

//FOR AUTHORIZATION
//==========================================
ini_set("display_errors", 1);

require_once("../../auth/AuthClient.php");
//==========================================

//headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

include_once('../../config/Database.php');
include_once('../../models/Booking.php');

//instantiate db and connect
$database = new Database();
$db = $database->connect();


//FOR AUTHORIZATION
//======================================================
$headers = getallheaders();
$token = $headers["Authorization"];

$auth = new AuthClient(null, $token);
$payload = $auth->request_auth();

if(isset($payload->data)){
    //instantiate Booking object
    $booking = new Booking($db);

    //get ID
    $booking->id = isset($_GET['id']) ? $_GET['id'] : die();

    //get booking
    $booking->read_single_booking();

    //create array
    $booking_array = array(
        'id' => $booking->id,
        'creation_date' => $booking->creation_date,
        'booking_date' => $booking->booking_date,
        'start_time' => $booking->start_time,
        'end_time' => $booking->end_time,
        'status' => $booking->status,
        'rating' => $booking->rating,
        'comments' => $booking->comments,
        'customer_id' => $booking->customer_id,
        'worker_id' => $booking->worker_id,
        'worker_firstname' => $booking->worker_firstname,
        'worker_lastname' => $booking->worker_lastname,
        'customer_firstname' => $booking->customer_firstname,
        'customer_lastname' => $booking->customer_lastname
    );

    //make JSON
    print_r(json_encode($booking_array));

}else{
    //echo("Not authorized");
    echo json_encode(
        array(
        'message' => 'User not authorized'
        )
    );
}
//=====================================================


?>
