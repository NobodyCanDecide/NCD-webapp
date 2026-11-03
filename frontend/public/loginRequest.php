<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
chdir(__DIR__ . '/../..');
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');


$client = new rabbitMQClient("testRabbitMQ.ini", "testServer");
if (isset($argv[1])) {
	$msg = $argv[1];
}
else {
	$msg = "test message";
}

$request = array();
$request['type'] = 'login';
$request['username'] = $_POST['username'];
$request['password'] = $_POST['password'];
$request['message'] = $msg;
$response = $client->send_request($request);



$payload = json_encode(['success' => !empty($response['success']), 'message' => $response['message'] ?? 'No response from server']);
echo $payload;

?>
