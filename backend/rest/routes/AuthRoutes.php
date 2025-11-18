<?php

Flight::group('/auth', function() {

    /**
     * @OA\Post(
     *     path="/auth/register",
     *     tags={"auth"},
     *     summary="Register a new user",
     *     description="Creates a new user account (renter or landlord).",
     *     security={
     *         {"ApiKey": {}}
     *     },
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password", "role"},
     *             @OA\Property(property="first_name", type="string", example="Amar", description="User's first name"),
     *             @OA\Property(property="last_name", type="string", example="Hadžić", description="User's last name"),
     *             @OA\Property(property="email", type="string", example="amar@example.com", description="User email address"),
     *             @OA\Property(property="password", type="string", example="securePassword123", description="User password"),
     *             @OA\Property(property="role", type="string", example="landlord", enum={"user", "landlord"}, description="User role type")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User registered successfully"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Missing required fields or invalid data"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */
    Flight::route('POST /register', function () {
        $data = Flight::request()->data->getData();
        $response = Flight::authService()->register($data);

        if ($response['success']) {
            Flight::json([
                'message' => 'User registered successfully',
                'data' => $response['data']
            ]);
        } else {
            Flight::halt(500, $response['error']);
        }
    });

    /**
     * @OA\Post(
     *     path="/auth/login",
     *     tags={"auth"},
     *     summary="Login with email and password",
     *     description="Logs in an existing user and returns user data.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", example="amar@example.com", description="User email address"),
     *             @OA\Property(property="password", type="string", example="securePassword123", description="User password")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User logged in successfully"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Missing or invalid credentials"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Invalid email or password"
     *     )
     * )
     */
    Flight::route('POST /login', function() {
        $data = Flight::request()->data->getData();
        $response = Flight::authService()->login($data);

        if ($response['success']) {
            Flight::json([
                'message' => 'User logged in successfully',
                'data' => $response['data']
            ]);
        } else {
            Flight::halt(500, $response['error']);
        }
    });
});
?>
