<?php

// Set the reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL ^ (E_NOTICE | E_DEPRECATED));
error_log("DB USER: " . Config::DB_USER());
error_log("DB PASSWORD: " . Config::DB_PASSWORD());

class Config {
    public static function DB_NAME()
   {
       return Config::get_env("DB_NAME","bosnia_rentals"); 
   }
   public static function DB_PORT()
   {
       return  Config::get_env("DB_PORT",25060);
   }
   public static function DB_USER()
   {
       return Config::get_env("DB_USER","root");
   }
   public static function DB_PASSWORD()
   {
       return  Config::get_env("DB_PASSWORD", '');
   }
   public static function DB_HOST()
   {
       return Config::get_env("DB_HOST",'localhost');
   }

   public static function JWT_SECRET() {
       return Config::get_env("JWT_SECRET",'lalapopsik');
   }
public static function get_env($name, $default){
    $value = getenv($name); // check system-level env first
    if ($value !== false && trim($value) !== "") {
        return $value;
    }
    return isset($_ENV[$name]) && trim($_ENV[$name]) != "" ? $_ENV[$name] : $default;
}


}
?>