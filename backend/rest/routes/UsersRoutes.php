<?php
require_once __DIR__ . '/../../data/roles.php';
/** 
 * @OA\Get(
 *      path="/users",
 *      tags={"users"},
 *      summary="Get all users",
 * security={
 *         {"ApiKey": {}}
 *     },
 *      @OA\Response(
 *           response=200,
 *           description="Array of all users in the database"
 *      )
 * )
 */
 Flight::route('GET /users', function() {
    Flight::json(Flight::usersService()->getAll());
});  
//this could be used only for debugging purposes cuz no user should see all users*/

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
 * security={
 *         {"ApiKey": {}}
 *     },
 *     @OA\Response(
 *         response=200,
 *         description="Returns the user with the given ID"
 *     )
 * )
 */

Flight::route('GET /users/@id', function($id) {
    Flight::auth_middleware()->authorizeRoles([ Roles::USER, Roles::LANDLORD]);
         
    $user = Flight::get('user');

    //Ownership check: users/landlords can only see their own profile
    Flight::auth_middleware()->authorizeOwnership($id);

    Flight::json(Flight::usersService()->getById($id));
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
 * security={
 *         {"ApiKey": {}}
 *     },
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
     Flight::auth_middleware()->authorizeOwnership($id);

    try {
    Flight::json(Flight::usersService()->delete($id));
     } catch (Exception $e) {
        Flight::json(['error' => 'Cannot delete user with existing reservations'], 400);
    }
});
//this one is also used only for managing db no user is allowed to delete themselves or others

?>
