<?php

require_once 'db_config.php';

//connects and sends an error if fails
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

echo "Connected to MySQL as " . DB_USER . "\n";

//creates a test user
$username = "testuser";
$email    = "test@example.com";
$hash     = password_hash("secret123", PASSWORD_DEFAULT);

$stmt = $db->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $username, $email, $hash);
$stmt->execute();

echo "Inserted user '$username' with id " . $db->insert_id . "\n";

//turns row into an array and reads info back
$stmt = $db->prepare("SELECT id, username, password_hash FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();

echo "Found user: id={$row['id']} username={$row['username']}\n";
echo "Stored hash: {$row['password_hash']}\n";

// 4. Check passwords against the hash
echo "Correct password check: " . (password_verify("secret123", $row['password_hash']) ? "PASS" : "FAIL") . "\n";
echo "Wrong password check:   " . (password_verify("wrongpass", $row['password_hash']) ? "PASS" : "FAIL") . "\n";

// 5. Clean up the test row
$stmt = $db->prepare("DELETE FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
echo "Deleted test user\n";

$db->close();

