<?php
require 'vendor/autoload.php'; //run autoloader
require_once __DIR__ . '/rest/services/UsersService.php';
require_once __DIR__ . '/rest/services/ReservationsService.php';
require_once __DIR__ . '/rest/services/WishlistService.php';
require_once __DIR__ . '/rest/services/MessagesService.php';
require_once __DIR__ . '/rest/services/ListingsService.php';
require_once __DIR__ . '/rest/services/AuthService.php';
require_once __DIR__ .  '/middleware/AuthMiddleware.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
// Add CORS headers
Flight::before('start', function() {
    header("Access-Control-Allow-Origin: https://shark-app-ioj5q.ondigitalocean.app"); 
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization, Authentication");
    header("Access-Control-Allow-Credentials: true");

    // Handle preflight OPTIONS requests
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        Flight::halt(200, ''); // short‑circuit the request
    }
});


// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);


Flight::register('usersService', 'UsersService'); //Tell Flight to automatically create UsersService when you call Flight::usersService()
Flight::register('reservationsService', 'ReservationsService');
Flight::register('wishlistService', 'WishlistService');
Flight::register('messagesService', 'MessagesService');
Flight::register('listingsService', 'ListingsService');
Flight::register('authService', 'AuthService');
Flight::register('auth_middleware', "AuthMiddleware");

//This is a Flight lifecycle hook- it runs before any route is executed.
Flight::before('start', function() {
    $url = Flight::request()->url;
   if(
       strpos(Flight::request()->url, '/auth/login') === 0 ||
       strpos(Flight::request()->url, '/auth/register') === 0
   ) {
     // Debug to see if headers r reaching php
  //  var_dump($_SERVER);
//exit;
       return TRUE;
      
   } else {
   // var_dump($_SERVER);
//exit;

       try {
           $token = Flight::request()->getHeader("Authentication");
           if(Flight::auth_middleware()->verifyToken($token))
               return TRUE;
       } catch (\Exception $e) {
           Flight::halt(401, $e->getMessage());
       }
   }
});

require_once __DIR__ . '/rest/routes/UsersRoutes.php';
require_once __DIR__ . '/rest/routes/ReservationsRoutes.php';
require_once __DIR__ . '/rest/routes/WishlistRoutes.php';
require_once __DIR__ . '/rest/routes/MessagesRoutes.php';
require_once __DIR__ . '/rest/routes/ListingsRoutes.php';
require_once __DIR__ . '/rest/routes/AuthRoutes.php';
Flight::start();  //start FlightPHP
?>
