<?php
//Generates and returns OpenAPI JSON by scanning my annotations
ini_set('display_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/../../../vendor/autoload.php'; //so the OpenAPI scanner and annotations work cuz it registers all Composer-installed libraries
define('PRODSERVER', 'https://starfish-app-q5czd.ondigitalocean.app');
define('LOCALSERVER', 'http://localhost/AzraArnautovic/web-programming/backend');

//switches between local and production servers so Swagger knows which backend to call when rendering or testing endpoints
if($_SERVER['SERVER_NAME'] == 'localhost' || $_SERVER['SERVER_NAME'] == '127.0.0.1'){
    define('BASE_URL', LOCALSERVER);
} else {
    define('BASE_URL', PRODSERVER) ;
}

$openapi = \OpenApi\Generator::scan([
    __DIR__ . '/doc_setup.php', //for global metadata/security
    __DIR__ . '/../../../rest/routes' //for endpoint annotations
]);
header('Content-Type: application/json');
echo $openapi->toJson();
?>
