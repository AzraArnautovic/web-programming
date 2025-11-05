<?php
//Get all listings (for homepage or renters)
Flight::route('GET /listings', function() {
    $listings = Flight::listingsService()->getAll();
    if (empty($listings)) {
        Flight::json(['message' => 'No listings available.']);
    } else {
        Flight::json($listings);
    }
});

//Get a single listing by ID
Flight::route('GET /listings/@id', function($id) {
    $listing = Flight::listingsService()->getById($id);
    if (!$listing) {
        Flight::json(['error' => 'Listing not found.'], 404);
    } else {
        Flight::json($listing);
    }
});

//Get listings created by a specific user (landlord dashboard)
Flight::route('GET /listings/user/@user_id', function($user_id) {
    $listings = Flight::listingsService()->getListingsByUser($user_id);
    if (empty($listings)) {
        Flight::json(['message' => 'This user has no listings.']);
    } else {
        Flight::json($listings);
    }
});

//Add a new listing
Flight::route('POST /listings', function() {
    $data = Flight::request()->data->getData();

    // Basic validation
    if (empty($data['title']) || empty($data['price']) || empty($data['users_id'])) {
        Flight::json(['error' => 'Missing required fields: title, price, or users_id.'], 400);
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

//Update an existing listing
Flight::route('PUT /listings/@id', function($id) {
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

//Delete a listing
Flight::route('DELETE /listings/@id', function($id) {
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