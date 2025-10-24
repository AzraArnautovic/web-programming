<?php
require_once 'dao/WishlistDao.php';

$wishlistDao = new WishlistDao();

$wishlistDao->add_to_wishlist([
    "users_id" => 12,     
    "listings_id" => 2   
]);

echo "<h3>Wishlist for User 3:</h3>";
print_r($wishlistDao->get_wishlist_by_user(12));

// Remove from wishlist
//$wishlistDao->remove_from_wishlist(3, 2);
?>
