<?php
if (!isset($_POST)){
	$msg = "NO POST MESSAGE SET";
	echo json_encode($msg);
	exit(0);
}

$req = $_POST;
$res = "unsupported request type";
switch ($req["type"]) {
	case "login":
		$res = "Successfully logged in.";
	break;
	default:
		$res = "Unsuccessful request.";
	break;
}
echo json_encode($res);
exit(0);
?>
