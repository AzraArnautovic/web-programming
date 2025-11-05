<?php
Flight::route('GET /users', function() {
    Flight::json(Flight::usersService()->getAll());
});

Flight::route('GET /users/@id', function($id) {
    Flight::json(Flight::usersService()->getById($id));
});

//create new user
Flight::route('POST /users', function() {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::usersService()->registerUser($data));
});

//update user
Flight::route('PUT /users/@id', function($id) {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::usersService()->update($id, $data));
});

Flight::route('DELETE /users/@id', function($id) {
    try {
    Flight::json(Flight::usersService()->delete($id));
     } catch (Exception $e) {
        Flight::json(['error' => 'Cannot delete user with existing reservations'], 400);
    }
});

?>
