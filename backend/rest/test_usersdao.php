<?php
require_once 'dao/UsersDao.php';

$usersDao = new UsersDao();

/*$usersDao->insert_user([
    'role' => 'landlord',
    'first_name' => 'Lejla',
    'last_name' => 'Host',
    'email' => 'lejla.host2@example.com',
    'password_hash' => password_hash('mypassword', PASSWORD_DEFAULT),
]);

$usersDao->insert_user([
    'role' => 'user',
    'first_name' => 'Amir',
    'last_name' => 'Renter',
    'email' => 'amir.renter@example.com',
    'password_hash' => password_hash('renterpass', PASSWORD_DEFAULT),
]);*/

//fetch all users
echo "<h3>All users:</h3>";
$users = $usersDao->getAll();
echo "<pre>";
print_r($users);
echo "</pre>";

//fetch one user by email
echo "<h3>User fetched by email:</h3>";
$user = $usersDao->get_user_by_email('lejla.host2@example.com');
echo "<pre>";
print_r($user);
echo "</pre>";
?>
