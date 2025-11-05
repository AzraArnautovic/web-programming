<?php
require 'vendor/autoload.php'; //run autoloader
require_once __DIR__ . '/rest/services/UsersService.php';
require_once __DIR__ . '/rest/services/ReservationsService.php';
require_once __DIR__ . '/rest/services/WishlistService.php';
require_once __DIR__ . '/rest/services/MessagesService.php';
require_once __DIR__ . '/rest/services/ListingsService.php';

Flight::register('usersService', 'UsersService'); //Tell Flight to automatically create UsersService when you call Flight::usersService()
Flight::register('reservationsService', 'ReservationsService');
Flight::register('wishlistService', 'WishlistService');
Flight::register('messagesService', 'MessagesService');
Flight::register('listingsService', 'ListingsService');


require_once __DIR__ . '/rest/routes/UsersRoutes.php';
require_once __DIR__ . '/rest/routes/ReservationsRoutes.php';
require_once __DIR__ . '/rest/routes/WishlistRoutes.php';
require_once __DIR__ . '/rest/routes/MessagesRoutes.php';
require_once __DIR__ . '/rest/routes/ListingsRoutes.php';
Flight::start();  //start FlightPHP
?>
