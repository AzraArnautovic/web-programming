<?php
require_once 'dao/ListingsDao.php';

$listingsDao = new ListingsDao();

// Insert a test listing
$listingsDao->insert_listing([
    "users_id" => 11, // landlord Lejla from earlier
    "title" => "Old Town Studio - Sarajevo",
    "municipality" => "Stari Grad",
    "address" => "Baščaršija 15",
    "price" => 50.00,
    "beds" => 1,
    "baths" => 1,
    "heating" => "Central",
    "size_m2" => 28,
    "cover_url" => "assets/listings/1a.jpg",
    "description" => "Cozy studio in Baščaršija. Walk everywhere.",
    "amenities" => "Wi-Fi, Heating, Kitchen"
]);

// Fetch and print all listings
echo "<h3>All Listings:</h3>";
print_r($listingsDao->get_all_listings());

// Fetch listings by user (Lejla’s ID)
echo "<h3>Lejla's Listings:</h3>";
print_r($listingsDao->get_listings_by_user(11));

// Fetch one listing by ID
echo "<h3>Listing by ID:</h3>";
print_r($listingsDao->get_listing_by_id(0));
?>
