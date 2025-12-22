<?php
// CORS MUST BE FIRST - before any other output or require statements
$allowedOrigins = [
    "http://127.0.0.1",
    "http://localhost",
    "https://shark-app-ioj5q.ondigitalocean.app" // Your FRONTEND URL
];

// Set CORS headers FIRST
$origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';

if (in_array($origin, $allowedOrigins)) {
    header("Access-Control-Allow-Origin: " . $origin);
}

header("Access-Control-Allow-Methods: GET, POST, PATCH, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authentication");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 86400");

// Handle preflight OPTIONS requests IMMEDIATELY
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit();
}

// NOW load everything else
require 'vendor/autoload.php';
require_once __DIR__ . '/rest/services/UsersService.php';
require_once __DIR__ . '/rest/services/ReservationsService.php';
require_once __DIR__ . '/rest/services/WishlistService.php';
require_once __DIR__ . '/rest/services/MessagesService.php';
require_once __DIR__ . '/rest/services/ListingsService.php';
require_once __DIR__ . '/rest/services/AuthService.php';
require_once __DIR__ .  '/middleware/AuthMiddleware.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

// Health check
if ($_SERVER['REQUEST_URI'] === '/' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['status' => 'healthy', 'service' => 'Bosnia Rentals Backend']);
    exit;
}

Flight::register('usersService', 'UsersService');
Flight::register('reservationsService', 'ReservationsService');
Flight::register('wishlistService', 'WishlistService');
Flight::register('messagesService', 'MessagesService');
Flight::register('listingsService', 'ListingsService');
Flight::register('authService', 'AuthService');
Flight::register('auth_middleware', "AuthMiddleware");

Flight::before('start', function() {
    $url = Flight::request()->url;
    
    // Normalize URL - remove double slashes and trim
    $url = '/' . trim(preg_replace('#/+#', '/', $url), '/');
    
    // Log for debugging (remove after fixing)
    error_log("Normalized URL: " . $url);
    
    // Check if it's a public auth endpoint
    if(
        strpos($url, '/auth/login') === 0 ||
        strpos($url, '/auth/register') === 0
    ) {
        error_log("Public endpoint - skipping auth check");
        return TRUE;
    } else {
        error_log("Protected endpoint - checking auth");
        try {
            $token = Flight::request()->getHeader("Authentication");
            if(!$token) {
                throw new Exception("Authentication header missing");
            }
            if(Flight::auth_middleware()->verifyToken($token))
                return TRUE;
        } catch (\Exception $e) {
            error_log("Auth error: " . $e->getMessage());
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

Flight::start();
?>