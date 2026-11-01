<?php
require_once __DIR__ . '/db_functions.php';

$login = loginUser("alice", "password123");
$token = $login['token'];
echo "1. Logged in, token starts: " . substr($token, 0, 12) . "\n";

echo "\n2. Validate fresh token:\n";
print_r(validateSession($token));

echo "\n3. Validate a fake token:\n";
print_r(validateSession("notarealtoken"));

echo "\n4. Logout:\n";
print_r(logoutUser($token));

echo "\n5. Validate after logout (should fail):\n";
print_r(validateSession($token));
