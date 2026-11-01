<?php
require_once __DIR__ . '/db_functions.php';

echo "1. Correct login:\n";
print_r(loginUser("alice", "password123"));

echo "\n2. Wrong password:\n";
print_r(loginUser("alice", "wrongpassword"));

echo "\n3. User that doesn't exist:\n";
print_r(loginUser("nobody", "password123"));
