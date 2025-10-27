<?php
require_once 'services/UsersService.php';
require_once 'services/ListingsService.php';
require_once 'services/ReservationsService.php';
require_once 'services/WishlistService.php';
require_once 'services/MessagesService.php';

echo "<h2>UsersService test</h2>";
$usersService = new UsersService();
$allUsers = $usersService->getAll();
print_r($allUsers);

echo "<h2>ListingsService test</h2>";
$listingsService = new ListingsService();
$newListing = $listingsService->create([
    "users_id" => 11, // existing landlord user ID
    "title" => "Sunny Apartment in Mostar",
    "municipality" => "Mostar",
    "address" => "Old Town 25",
    "price" => 75.00,
    "beds" => 2,
    "baths" => 1,
    "heating" => "Electric",
    "size_m2" => 50,
    "cover_url" => "assets/listings/2a.jpg",
    "description" => "Spacious flat with balcony view.",
    "amenities" => "Wi-Fi, Kitchen, Balcony"
]);
print_r($newListing);

echo "<h2>ReservationsService test</h2>";
$reservationsService = new ReservationsService();
$newReservation = $reservationsService->create([
    "listings_id" => 2, // must exist
    "users_id" => 16, // renter ID
    "start_date" => "2025-12-01",
    "end_date" => "2025-12-05",
    "total_price" => 300.00,
    "status" => "pending"
]);
print_r($newReservation);

echo "<h2>WishlistService test</h2>";
$wishlistService = new WishlistService();
$wishlistService->create([
    "users_id" => 17,
    "listings_id" => 2
]);
$wishlist = $wishlistService->getWishlistByUser(17);
print_r($wishlist);

echo "<h2>MessagesService test</h2>";
$messagesService = new MessagesService();
$newMessage = $messagesService->create([
    "sender_id" => 17,    // renter
    "receiver_id" => 11,  // landlord
    "content" => "Hello, is your Mostar apartment available next weekend?"
]);
print_r($newMessage);

$inbox = $messagesService->getInbox(11);
echo "<h3>Landlord inbox:</h3>";
print_r($inbox);

echo "<h3>All tests finished</h3>";
?>
