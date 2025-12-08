<?php
require_once __DIR__ . '/../../data/roles.php';

/**
 * @OA\Get(
 *      path="/listings",
 *      tags={"listings"},
 *      summary="Get all listings (for homepage or renters)",
 * security={
 *         {"ApiKey": {}}
 *     },
 *      @OA\Response(
 *           response=200,
 *           description="Array of all listings in the database or a message if none exist"
 *      )
 * )
 */
Flight::route('GET /listings', function() {
    Flight::auth_middleware()->authorizeRoles([Roles::LANDLORD, Roles::USER]);
    $listings = Flight::listingsService()->getAll();
    if (empty($listings)) {
        Flight::json(['message' => 'No listings available.']);
    } else {
        Flight::json($listings);
    }
});
/**
 * @OA\Get(
 *     path="/listings/{id}",
 *     tags={"listings"},
 *     summary="Get a single listing by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the listing",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 * security={
 *         {"ApiKey": {}}
 *     },
 *     @OA\Response(
 *         response=200,
 *         description="Returns the listing with the given ID"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Listing not found"
 *     )
 * )
 */

Flight::route('GET /listings/@id', function($id) {
    $listing = Flight::listingsService()->getById($id);
    if (!$listing) {
        Flight::json(['error' => 'Listing not found.'], 404);
    } else {
        Flight::json($listing);
    }
});

/**
 * @OA\Get(
 *     path="/listings/user/{user_id}",
 *     tags={"listings"},
 *     summary="Get all listings created by a specific user (landlord dashboard view)",
 *     @OA\Parameter(
 *         name="user_id",
 *         in="path",
 *         required=true,
 *         description="User ID (landlord) whose listings to fetch",
 *         @OA\Schema(type="integer", example=3)
 *     ),
 *     security={
 *         {"ApiKey": {}}
 *     },
 *     @OA\Response(
 *         response=200,
 *         description="Array of listings for the specified user or a message if none exist"
 *     )
 * )
 */
Flight::route('GET /listings/user/@user_id', function($user_id) {
  // First check role
     Flight::auth_middleware()->authorizeRole(Roles::LANDLORD);
    
    // Get the logged-in user from JWT
    $user = Flight::get('user');

    // Enforce that the path user_id matches the logged-in landlord
    if ($user->id != $user_id) {
        Flight::json(['error' => 'Unauthorized: you can only view your own listings'], 403);
        return;
    }

    // Fetch listings for this landlord
    $listings = Flight::listingsService()->getListingsByUser($user_id);

    // Optional: double-check ownership across all listings
    foreach ($listings as $listing) {
        Flight::auth_middleware()->authorizeOwnership($listing['users_id']);
    }

    Flight::json($listings?: []);
});

/**
 * @OA\Post(
 *     path="/listings",
 *     tags={"listings"},
 *     summary="Create a new listing",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"title", "price"},
 *             @OA\Property(property="title", type="string", example="Old Town Studio - Sarajevo"),
 *             @OA\Property(property="municipality", type="string", example="Stari Grad"),
 *             @OA\Property(property="price", type="number", example=80),
 *             @OA\Property(property="beds", type="integer", example=2),
 *             @OA\Property(property="baths", type="integer", example=1),
 *             @OA\Property(property="size_m2", type="integer", example=45),
 *             @OA\Property(property="heating", type="string", enum={"Central", "Electric", "Wood Stove"}, example="Central"),
 *             @OA\Property(property="description", type="string", example="Cozy apartment near Baščaršija."),
 *             @OA\Property(property="amenities", type="string", example="Wi-Fi, Parking, Kitchen"),
 *             @OA\Property(property="cover_url", type="string", example="assets/listings/studio.jpg")
 *         )
 *     ),
 * security={
 *         {"ApiKey": {}}
 *     },
 *     @OA\Response(
 *         response=200,
 *         description="Listing created successfully"
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Missing required fields"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal server error while creating listing"
 *     )
 * )
 */
Flight::route('POST /listings', function() {
    // Only landlords can create listings
    Flight::auth_middleware()->authorizeRole(Roles::LANDLORD);
    //get request body
    $data = Flight::request()->data->getData();

    //Force ownership: always use the loggedin landlord’s ID from JWT
    $user = Flight::get('user'); // comes from verifyToken()
    $data['users_id'] = $user->id; //wout this landlord could spoof ownership
    
    // Basic validation
    if (empty($data['title']) || empty($data['price'])) {
        Flight::json(['error' => 'Missing required fields: title or price.'], 400);
        return;
    }

    try {
        $newListing = Flight::listingsService()->create($data);
        Flight::json([
            'success' => true,
            'message' => 'Listing created successfully.',
            'listing' => $newListing
        ]);
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 500);
    }
});

/**
 * @OA\Put(
 *     path="/listings/{id}",
 *     tags={"listings"},
 *     summary="Update an existing listing (partial updates allowed)",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Listing ID",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="title", type="string", example="Updated Apartment Name"),
 *             @OA\Property(property="price", type="number", example=90),
 *             @OA\Property(property="beds", type="integer", example=3),
 *             @OA\Property(property="baths", type="integer", example=1),
 *             @OA\Property(property="heating", type="string", example="Electric"),
 *             @OA\Property(property="description", type="string", example="Newly renovated apartment near the center.")
 *         )
 *     ),
 * security={
 *         {"ApiKey": {}}
 *     },
 *     @OA\Response(
 *         response=200,
 *         description="Listing updated successfully"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Listing not found or failed to update"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal server error while updating listing"
 *     )
 * )
 */
Flight::route('PUT /listings/@id', function($id) {
    Flight::auth_middleware()->authorizeRole(Roles::LANDLORD);

    $listing = Flight::listingsService()->getById($id);
 if (!$listing) {
        Flight::halt(404, 'Listing not found');
    }
    Flight::auth_middleware()->authorizeOwnership($listing['users_id']);

    $data = Flight::request()->data->getData();
    try {
        $updated = Flight::listingsService()->update($id, $data);
        if ($updated) {
            Flight::json(['success' => true, 'message' => 'Listing updated successfully.']);
        } else {
            Flight::json(['error' => 'Failed to update listing.'], 404);
        }
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 500);
    }
});

/**
 * @OA\Delete(
 *     path="/listings/{id}",
 *     tags={"listings"},
 *     summary="Delete a listing by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Listing ID",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 * security={
 *         {"ApiKey": {}}
 *     },
 *     @OA\Response(
 *         response=200,
 *         description="Listing deleted successfully"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Listing not found or could not be deleted"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal server error while deleting listing"
 *     )
 * )
 */
Flight::route('DELETE /listings/@id', function($id) {
    Flight::auth_middleware()->authorizeRole(Roles::LANDLORD);

    $listing = Flight::listingsService()->getById($id);
    Flight::auth_middleware()->authorizeOwnership($listing['users_id']);

    try {
        $deleted = Flight::listingsService()->delete($id);
        if ($deleted) {
            Flight::json(['message' => 'Listing deleted successfully.']);
        } else {
            Flight::json(['error' => 'Listing not found or could not be deleted.'], 404);
        }
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 500);
    }
});
?>