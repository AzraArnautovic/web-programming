<?php

/**  //Shows up at the top of Swagger UI
 * @OA\Info(
 *   title="API",
 *   description="Bosnia Rentals API",
 *   version="1.0",
 *   @OA\Contact(
 *     email="azra.arnautovic@atu.ibu.edu.ba",
 *     name="Web Programming"
 *   )
 * )
 * 
 * @OA\Server(
 *     url="https://starfish-app-q5czd.ondigitalocean.app",
 *     description="API server"
 * )
 * 
 * @OA\SecurityScheme(
 *     securityScheme="ApiKey",
 *     type="apiKey",
 *     in="header",
 *     name="Authentication"
 * )
 */
