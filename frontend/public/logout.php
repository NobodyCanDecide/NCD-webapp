<?php
chdir(__DIR__ . '/../..');
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

//if no token, logout is skipped
if (!empty($_COOKIE['session_token'])) {
    $client = new rabbitMQClient("testRabbitMQ.ini", "testServer");
    $client->send_request([
        'type'  => 'logout',
        'token' => $_COOKIE['session_token']
    ]);
}

//delets cookie
setcookie('session_token', '', [
    'expires' => time() - 3600,
    'path'    => '/'
]);

header('Location: index.html');
exit();
?>
