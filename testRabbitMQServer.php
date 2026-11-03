#!/usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');
require_once('database/db_functions.php');


function requestProcessor($request)
{
  echo "received request".PHP_EOL;
  if(!isset($request['type']))
  {
    return array('success' => false, 'message' => 'ERROR: unsupported message type');
  }
  try
  {
    switch ($request['type'])
    {
      case "register":
        return registerUser($request['username'], $request['email'], $request['password']);
      case "login":
        return loginUser($request['username'], $request['password']);
      case "validate_session":
        return validateSession($request['token']);
      case "logout":
        return logoutUser($request['token']);
    }
  }
  catch (Exception $error)
  {
    echo "database error: ".$error->getMessage().PHP_EOL;
    return array('success' => false, 'message' => 'Server error');
  }
  return array('success' => false, 'message' => 'ERROR: unsupported message type');
}

$server = new rabbitMQServer("testRabbitMQ.ini","testServer");

$server->process_requests('requestProcessor');
exit();
?>