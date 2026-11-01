<?php
require_once __DIR__ . '/db_functions.php';

echo "1. New user:\n";
print_r(registerUser("alice", "alice@example.com", "password123"));

echo "\n2. Same username again:\n";
print_r(registerUser("alice", "other@example.com", "password123"));

echo "\n3. Bad email:\n";
print_r(registerUser("bob", "not-an-email", "password123"));

echo "\n4. Short password:\n";
print_r(registerUser("bob", "bob@example.com", "shortpw"));

