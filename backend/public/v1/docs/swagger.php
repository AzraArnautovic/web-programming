<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/../../../vendor/autoload.php';
define('PRODSERVER', 'https://starfish-app-q5czd.ondigitalocean.app');
define('LOCALSERVER', 'http://localhost/AzraArnautovic/web-programming/backend');


if($_SERVER['SERVER_NAME'] == 'localhost' || $_SERVER['SERVER_NAME'] == '127.0.0.1'){
    define('BASE_URL', PRODSERVER);
} else {
    define('BASE_URL', LOCALSERVER) ;
}

$openapi = \OpenApi\Generator::scan([
    __DIR__ . '/doc_setup.php',
    __DIR__ . '/../../../rest/routes'
]);
header('Content-Type: application/json');
echo $openapi->toJson();
?>
