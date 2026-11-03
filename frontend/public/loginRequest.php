<?php
chdir(__DIR__ . '/../..');
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');


$client = new rabbitMQClient("testRabbitMQ.ini", "testServer");



$request = array();
$request['type'] = 'login';
$request['username'] = $_POST['username'];
$request['password'] = $_POST['password'];
$response = $client->send_request($request);

if (!empty($response['success'])) {
        setcookie('session_token', $response['token'], [
                'expires'  => time() + 3600,
                'path'     => '/',
                'httponly' => true,
                'samesite' => 'Strict'
        ]);
}

$payload = json_encode(['success' => !empty($response['success']), 'message' => $response['message'] ?? 'No response from server']);
echo $payload;

?>
