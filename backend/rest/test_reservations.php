<?php
require_once 'dao/ReservationsDao.php';

$resDao = new ReservationsDao();

$resDao->insert_reservation([
    "listings_id" => 2,   // existing listing
    "users_id" => 12,      // existing renter 
    "start_date" => "2025-11-01",
    "end_date" => "2025-11-05",
    "total_price" => 250.00,
    "status" => "pending"
]);

echo "<h3>All Reservations:</h3>";
print_r($resDao->get_all_reservations());

echo "<h3>Reservations by Renter (ID 12):</h3>";
print_r($resDao->get_reservations_by_user(12));

echo "<h3>Reservations for Landlord (ID 11):</h3>";
print_r($resDao->get_reservations_for_landlord(11));
?>
