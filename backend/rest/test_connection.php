<?php 
require_once ( __DIR__ . "/config.php");

$conn = Database::connect();
if ($conn) {
  echo "Database connection successful!";
}
?>