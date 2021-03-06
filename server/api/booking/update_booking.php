<?php

//FOR AUTHORIZATION
//==========================================
ini_set("display_errors", 1);

require_once("../../auth/AuthClient.php");
//==========================================

//headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: PUT');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

include_once('../../config/Database.php');
include_once('../../models/Booking.php');

//instantiate db and connect
$database = new Database();
$db = $database->connect();

//instantiate booking object
$booking = new Booking($db);

//get raw posted data
$data = json_decode(file_get_contents("php://input"));



//FOR AUTHORIZATION
//======================================================
$headers = getallheaders();
$token = $headers["Authorization"];

$auth = new AuthClient(null, $token);
$payload = $auth->request_auth();

if(isset($payload->data)){
    //echo("Authorized");
    //set ID to UPDATE
    $booking->id = $data->id;

    $booking->booking_date = $data->booking_date;
    $booking->start_time = $data->start_time;
    $booking->end_time = $data->end_time;
    $booking->status = $data->status;
    $booking->rating = $data->rating;
    $booking->comments = $data->comments;
    $booking->worker_id = $data->worker_id;

    //update booking
    if($booking->update_booking()){
      echo json_encode(
        array('message' => 'Booking Updated')
      );
    }else{
      echo json_encode(
        array('message' => 'Booking Not Updated')
      );
    }

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
