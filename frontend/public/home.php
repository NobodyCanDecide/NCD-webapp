<?php
chdir(__DIR__ . '/../..');
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

$client = new rabbitMQClient("testRabbitMQ.ini", "testServer");

$request = array();
$request['type'] = 'validate_session';
$request['token'] = $_COOKIE['session_token'] ?? '';
$response = $client->send_request($request);

if (empty($response['success'])) {
	header('Location: index.html');
	exit();
}
?>
<html>
<head>
	<meta charset="UTF-8">
	<title>Home</title>
	<link rel="stylesheet" href="css/login.css">
</head>
<body>
	<h1>Home</h1>
	<p>Logged in as <?php echo htmlspecialchars($response['username']); ?></p>

	<form action="logout.php" method="POST">
		<button type="submit" name="logout">Log Out</button>
	</form>
</body>
</html>
