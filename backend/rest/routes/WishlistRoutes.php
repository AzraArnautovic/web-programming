<?php
require_once __DIR__ . '/../../data/roles.php';
/**
 * @OA\Get(
 *      path="/wishlist/{user_id}",
 *      tags={"wishlist"},
 *      summary="Get all wishlist items for a specific user",
 *      @OA\Parameter(
 *          name="user_id",
 *          in="path",
 *          required=true,
 *          description="ID of the user whose wishlist items to retrieve",
 *          @OA\Schema(type="integer", example=2)
 *      ),
 * security={
 *         {"ApiKey": {}}
 *     },
 *      @OA\Response(
 *          response=200,
 *          description="Array of wishlist items for the specified user"
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="This user does not have any items in their wishlist"
 *      )
 * )
 */
Flight::route('GET /wishlist/@user_id', function($user_id) {
    Flight::auth_middleware()->authorizeRole(Roles::USER); //only renter has their wishlist displayed on dashboard
   
    $user = Flight::get('user'); // logged-in user from JWT

    // Enforce that the path user_id matches the logged-in user
    if ($user->id != $user_id) {
        Flight::halt(403, 'Access denied: you can only view your own wishlist');
        return;
    }

    $data = Flight::wishlistService()->getWishlistByUser($user_id);

    if (empty($data)) {
        Flight::json([
            'message' => 'This user does not have any items in their wishlist.'
        ], 404);
        return;
    }

    Flight::json($data);
});
/**
 * @OA\Post(
 *     path="/wishlist",
 *     tags={"wishlist"},
 *     summary="Add a listing to a user's wishlist",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"users_id", "listings_id"},
 *             @OA\Property(property="users_id", type="integer", example=3, description="ID of the user adding the listing"),
 *             @OA\Property(property="listings_id", type="integer", example=7, description="ID of the listing to be added")
 *         )
 *     ),
 * security={
 *         {"ApiKey": {}}
 *     },
 *     @OA\Response(
 *         response=200,
 *         description="Listing successfully added to the wishlist or message if already in wishlist"
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Missing users_id or listings_id"
 *     )
 * )
 */
Flight::route('POST /wishlist', function() {
    Flight::auth_middleware()->authorizeRole(Roles::USER);
    
     $data = Flight::request()->data->getData();
     $user = Flight::get('user');
     //žForce ownership: never trust client-supplied users_id
    $data['users_id'] = $user->id;

    if (empty($data['users_id']) || empty($data['listings_id'])) {
        Flight::json(['error' => 'Missing users_id or listings_id'], 400);
        return;
    }
//prevent duplicates
    if (Flight::wishlistService()->checkIfInWishlist($data['users_id'], $data['listings_id'])) {
        Flight::json(['message' => 'Listing already in wishlist']);
        return;
    }

    $result = Flight::wishlistService()->addToWishlist($data);
    Flight::json(['success' => $result]);
});
?>