<?php

/**
 * @OA\Get(
 *      path="/users",
 *      tags={"users"},
 *      summary="Get all users",
 *      @OA\Response(
 *           response=200,
 *           description="Array of all users in the database"
 *      )
 * )
 */

Flight::route('GET /users', function() {
    Flight::json(Flight::usersService()->getAll());
});

/**
 * @OA\Get(
 *     path="/users/{id}",
 *     tags={"users"},
 *     summary="Get user by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the user",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Returns the user with the given ID"
 *     )
 * )
 */

Flight::route('GET /users/@id', function($id) {
    Flight::json(Flight::usersService()->getById($id));
});

/**
 * @OA\Post(
 *     path="/users",
 *     tags={"users"},
 *     summary="Register a new user",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"first_name", "last_name", "email", "password", "role"},
 *             @OA\Property(property="first_name", type="string", example="Amar"),
 *             @OA\Property(property="last_name", type="string", example="Hadžić"),
 *             @OA\Property(property="email", type="string", example="amar@example.com"),
 *             @OA\Property(property="password", type="string", example="securepassword123"),
 *             @OA\Property(property="role", type="string", example="landlord")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="New user registered successfully"
 *     )
 * )
 */

Flight::route('POST /users', function() {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::usersService()->registerUser($data));
});

/**
 * @OA\Put(
 *     path="/users/{id}",
 *     tags={"users"},
 *     summary="Update an existing user by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="User ID",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="first_name", type="string", example="Updated Firstname"),
 *             @OA\Property(property="last_name", type="string", example="Updated Lastname"),
 *             @OA\Property(property="email", type="string", example="updated@example.com"),
 *             @OA\Property(property="role", type="string", example="user")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User updated successfully"
 *     )
 * )
 */
Flight::route('PUT /users/@id', function($id) {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::usersService()->update($id, $data));
});
/**
 * @OA\Delete(
 *     path="/users/{id}",
 *     tags={"users"},
 *     summary="Delete a user by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="User ID",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User deleted successfully"
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Cannot delete user with existing reservations"
 *     )
 * )
 */
Flight::route('DELETE /users/@id', function($id) {
    try {
    Flight::json(Flight::usersService()->delete($id));
     } catch (Exception $e) {
        Flight::json(['error' => 'Cannot delete user with existing reservations'], 400);
    }
});

?>
