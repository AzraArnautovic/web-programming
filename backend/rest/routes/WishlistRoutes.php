<?php
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
//Add a listing to a user's wishlist
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

Flight::route('DELETE /wishlist', function() {
    $data = Flight::request()->data->getData();

    if (empty($data['users_id']) || empty($data['listings_id'])) {
        Flight::json(['error' => 'Missing users_id or listings_id'], 400);
        return;
    }

    $result = Flight::wishlistService()->removeFromWishlist($data['users_id'], $data['listings_id']);
    Flight::json(['success' => $result]);
});

//Check if a specific listing is already in a user’s wishlist
Flight::route('GET /wishlist/check/@user_id/@listing_id', function($user_id, $listing_id) {
    $exists = Flight::wishlistService()->checkIfInWishlist($user_id, $listing_id);
    Flight::json(['in_wishlist' => $exists]);
});
?>