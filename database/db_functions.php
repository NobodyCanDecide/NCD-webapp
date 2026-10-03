<?php

require_once __DIR__ . '/db_config.php';

//opens connection to mysql
function getDB() {
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    return new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
}


function registerUser($username, $email, $password) {
//remove trailing spaces
	$username = trim($username);
	$email    = trim($email);

	if ($username === '' || $email === '' || $password === '') {
        return ['success' => false, 'message' => 'All fields are required'];
    }
	if (strlen($username) > 50) {
        return ['success' => false, 'message' => 'Username must be 50 characters or less'];
    }
	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return ['success' => false, 'message' => 'Invalid email address'];
    }
	if (strlen($password) < 8) {
        return ['success' => false, 'message' => 'Password must be at least 8 characters'];
    }

$db = getDB();

// check if username or email is already taken
    $stmt = $db->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        $db->close();
        return ['success' => false, 'message' => 'Username or email already taken'];
    }

// hash the password and insert the new user
    $hash = password_hash($password, PASSWORD_DEFAULT);
    try {
        $stmt = $db->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $hash);
        $stmt->execute();
    } catch (mysqli_sql_exception $e) {
        $db->close();
        if ($e->getCode() == 1062) {
            return ['success' => false, 'message' => 'Username or email already taken'];
        }
        return ['success' => false, 'message' => 'Registration failed'];
    }

    $userId = $db->insert_id;
    $db->close();
    return ['success' => true, 'message' => 'Registered', 'user_id' => $userId];
}
