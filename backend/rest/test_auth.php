<?php
require_once 'services/AuthService.php';

$auth = new AuthService();

echo "<h3>Register:</h3>";
print_r($auth->register([
    'email' => 'lala1.renter@example.com',
    'password' => '123456',
    'role' => 'user',
    'first_name' => 'Lala1',
    'last_name' => 'Renter'
]));

echo "<h3>Login:</h3>";
print_r($auth->login([
    'email' => 'lala1.renter@example.com',
    'password' => '123456'
]));
?>
