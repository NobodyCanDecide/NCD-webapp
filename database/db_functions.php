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

function loginUser($username, $password) {
    $username = trim($username);

    if ($username === '' || $password === '') {
        return ['success' => false, 'message' => 'Username and password are required'];
    }

    $db = getDB();

    // 1. Look up the user by username
    $stmt = $db->prepare("SELECT id, username, password_hash FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    // 2. Check the password (same message for both cases on purpose)
    if (!$user || !password_verify($password, $user['password_hash'])) {
        $db->close();
        return ['success' => false, 'message' => 'Invalid username or password'];
    }

    // 3. Make a random session token (64 hex characters)
    $token = bin2hex(random_bytes(32));

    // 4. Save the session, expiring 1 hour from now
    $stmt = $db->prepare("INSERT INTO sessions (token, user_id, expires_at) VALUES (?, ?, NOW() + INTERVAL 1 HOUR)");
    $stmt->bind_param("si", $token, $user['id']);
    $stmt->execute();
    $db->close();

    // 5. Send the token back
    return [
        'success'  => true,
        'message'  => 'Login successful',
        'token'    => $token,
        'user_id'  => $user['id'],
        'username' => $user['username']
    ];
}

function validateSession($token) {
    if ($token === '' || $token === null) {
        return ['success' => false, 'message' => 'No session token'];
    }

    $db = getDB();

    // Find the session, but only if it hasn't expired yet
    $stmt = $db->prepare(
        "SELECT s.user_id, u.username
         FROM sessions s
         JOIN users u ON u.id = s.user_id
         WHERE s.token = ? AND s.expires_at > NOW()"
    );
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $db->close();

    if (!$row) {
        return ['success' => false, 'message' => 'Invalid or expired session'];
    }

    return [
        'success'  => true,
        'message'  => 'Session valid',
        'user_id'  => $row['user_id'],
        'username' => $row['username']
    ];
}

function logoutUser($token) {
    $db = getDB();

    // Delete this session so the token can't be used again
    $stmt = $db->prepare("DELETE FROM sessions WHERE token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $deleted = $stmt->affected_rows;
    $db->close();

    if ($deleted === 0) {
        return ['success' => false, 'message' => 'Session not found'];
    }
    return ['success' => true, 'message' => 'Logged out'];
}
