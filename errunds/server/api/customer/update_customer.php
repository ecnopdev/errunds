<?php

//headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: PUT');

include_once('../../config/Database.php');
include_once('../../models/Customer.php');

//instantiate db and connect
$database = new Database();
$db = $database->connect();

//instantiate customer object
$customer = new Customer($db);

//get raw posted data
$data = json_decode(file_get_contents("php://input"));

//set ID to UPDATE
$customer->id = $data->id;

$customer->first_name = $data->first_name;
$customer->last_name = $data->last_name;
$customer->email = $data->email;
$customer->address = $data->address;
$customer->contact = $data->contact;
$customer->username = $data->username;
$customer->password = $data->password;

//update customer
if($customer->update_customer()){
  echo json_encode(
    array('message' => 'Customer Updated')
  );
}else{
  echo json_encode(
    array('message' => 'Customer Not Updated')
  );
}

?>