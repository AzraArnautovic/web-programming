<?php

// Set the reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL ^ (E_NOTICE | E_DEPRECATED));

class Config {
    public static function DB_NAME()
   {
       return  self::get_env("DB_NAME", "defaultdb");
   }
   public static function DB_PORT()
   {
       return  self::get_env("DB_PORT", 25060);
   }
   public static function DB_USER()
   {
       return self::get_env("DB_USER", "doadmin");
   }
   public static function DB_PASSWORD()
   {
       return self::get_env("DB_PASSWORD", "AVNS_ONu2GOHJKoWcmO4UbRd");
   }
   public static function DB_HOST()
   {
       return self::get_env("DB_HOST", "db-mysql-nyc3-03457-do-user-30590475-0.h.db.ondigitalocean.com");
   }
   public static function JWT_SECRET() {
       return  self::get_env("JWT_SECRET", "lalapopsik");
   }
public static function get_env($name, $default){
       return isset($_ENV[$name]) && trim($_ENV[$name]) != "" ? $_ENV[$name] : $default;
   }
}
?>