<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
class AuthMiddleware {
   public function verifyToken($token){ // decodes the JWT token passed in the request header to extract user data, such as their ID and role
       if(!$token)
           Flight::halt(401, "Missing authentication header");
       $decoded_token = JWT::decode($token, new Key(Config::JWT_SECRET(), 'HS256'));
       Flight::set('user', $decoded_token->user);
       Flight::set('jwt_token', $token);
       return TRUE;
   }
   public function authorizeRole($requiredRole) {
       $user = Flight::get('user');
       if ($user->role !== $requiredRole) {
           Flight::halt(403, 'Access denied: insufficient privileges');
       }
   }
   public function authorizeRoles($roles) {
       $user = Flight::get('user');
       if (!in_array($user->role, $roles)) {
           Flight::halt(403, 'Forbidden: role not allowed');
       }
   }
   /*function authorizePermission($permission) {
       $user = Flight::get('user');
       if (!in_array($permission, $user->permissions)) {
           Flight::halt(403, 'Access denied: permission missing');
       }
   }   */
   public function authorizeOwnership($resourceUserId) {
        $user = Flight::get('user');//from jwt
        if ($user->id != $resourceUserId) {
            Flight::halt(403, 'Access denied: you can only access your own resources');
        }
    }
}
