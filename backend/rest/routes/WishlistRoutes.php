<?php
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
    $data = Flight::request()->data->getData();

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
/**
 * @OA\Delete(
 *     path="/wishlist",
 *     tags={"wishlist"},
 *     summary="Remove a listing from a user's wishlist",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"users_id", "listings_id"},
 *             @OA\Property(property="users_id", type="integer", example=3, description="ID of the user"),
 *             @OA\Property(property="listings_id", type="integer", example=7, description="ID of the listing to remove")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Wishlist item successfully removed"
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Missing users_id or listings_id"
 *     )
 * )
 */
Flight::route('DELETE /wishlist', function() {
    $data = Flight::request()->data->getData();

    if (empty($data['users_id']) || empty($data['listings_id'])) {
        Flight::json(['error' => 'Missing users_id or listings_id'], 400);
        return;
    }

    $result = Flight::wishlistService()->removeFromWishlist($data['users_id'], $data['listings_id']);
    Flight::json(['success' => $result]);
});
/**
 * @OA\Get(
 *     path="/wishlist/check/{user_id}/{listing_id}",
 *     tags={"wishlist"},
 *     summary="Check if a specific listing is already in a user's wishlist",
 *     @OA\Parameter(
 *         name="user_id",
 *         in="path",
 *         required=true,
 *         description="User ID",
 *         @OA\Schema(type="integer", example=3)
 *     ),
 *     @OA\Parameter(
 *         name="listing_id",
 *         in="path",
 *         required=true,
 *         description="Listing ID to check",
 *         @OA\Schema(type="integer", example=7)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Boolean indicating if the listing is already in the wishlist"
 *     )
 * )
 */
Flight::route('GET /wishlist/check/@user_id/@listing_id', function($user_id, $listing_id) {
    $exists = Flight::wishlistService()->checkIfInWishlist($user_id, $listing_id);
    Flight::json(['in_wishlist' => $exists]);
});
?>