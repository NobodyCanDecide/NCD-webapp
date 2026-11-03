<?php
chdir(__DIR__ . '/../..');
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');


$client = new rabbitMQClient("testRabbitMQ.ini", "testServer");


$request = array();
$request['type'] = 'register';
$request['username'] = $_POST['username'];
$request['email'] = $_POST['email'];
$request['password'] = $_POST['password'];
$response = $client->send_request($request);
 
$payload = json_encode(['success' => !empty($response['success']), 'message' => $response['message'] ?? 'No response from server']);
echo $payload;

?>
