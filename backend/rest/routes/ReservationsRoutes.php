<?php
require_once __DIR__ . '/../../data/roles.php';
/**
 * @OA\Get(
 *     path="/reservations/{id}",
 *     tags={"reservations"},
 *     summary="Get reservation by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Reservation ID",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 * security={
 *         {"ApiKey": {}}
 *     },
 *     @OA\Response(
 *         response=200,
 *         description="Returns the reservation with the given ID"
 *     )
 * )
 */
Flight::route('GET /reservations/@id', function($id) {
    Flight::auth_middleware()->authorizeRoles([Roles::LANDLORD, Roles::USER]);
    
    // Fetch reservation first and assign to a variable
    $reservation = Flight::reservationsService()->getById($id);

    if (!$reservation) {
        Flight::halt(404, 'Reservation not found');
        return;
    }

    $user = Flight::get('user'); // logged-in user from JWT

    if ($user->role === Roles::USER) {
        // Prevent renter A from seeing renter B’s reservation
        Flight::auth_middleware()->authorizeOwnership($reservation['users_id']);
    }

    if ($user->role === Roles::LANDLORD && $reservation['users_id'] != $user->id) {
        // Prevent landlord A from snooping on landlord B’s reservations
        Flight::halt(403, 'Access denied: not your listing reservation');
        return;
    }

    // If all checks pass, return reservation
    Flight::json($reservation);
});

/**
 * @OA\Get(
 *     path="/reservations/user/{user_id}",
 *     tags={"reservations"},
 *     summary="Get all reservations made by a specific user (renter)",
 *     @OA\Parameter(
 *         name="user_id",
 *         in="path",
 *         required=true,
 *         description="User (renter) ID",
 *         @OA\Schema(type="integer", example=4)
 *     ),
 * security={
 *         {"ApiKey": {}}
 *     },
 *     @OA\Response(
 *         response=200,
 *         description="Array of reservations made by the specified user"
 *     )
 * )
 */
Flight::route('GET /reservations/user/@user_id', function($user_id) {
     Flight::auth_middleware()->authorizeRole(Roles::USER);
    Flight::auth_middleware()->authorizeOwnership($user_id);
    Flight::json(Flight::reservationsService()->getReservationsByUser($user_id));
});
/**
 * @OA\Get(
 *     path="/reservations/landlord/{landlord_id}",
 *     tags={"reservations"},
 *     summary="Get all reservations for a specific landlord",
 *     @OA\Parameter(
 *         name="landlord_id",
 *         in="path",
 *         required=true,
 *         description="Landlord (owner) ID",
 *         @OA\Schema(type="integer", example=2)
 *     ),
 * security={
 *         {"ApiKey": {}}
 *     },
 *     @OA\Response(
 *         response=200,
 *         description="Array of reservations for listings owned by the landlord"
 *     )
 * )
 */
Flight::route('GET /reservations/landlord/@landlord_id', function($landlord_id) {
    Flight::auth_middleware()->authorizeRole(Roles::LANDLORD);
    Flight::auth_middleware()->authorizeOwnership($landlord_id);
    Flight::json(Flight::reservationsService()->getReservationsForLandlord($landlord_id));
});

/**
 * @OA\Post(
 *     path="/reservations",
 *     tags={"reservations"},
 *     summary="Create a new reservation",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"users_id", "listings_id", "check_in", "check_out", "total_price"},
 *             @OA\Property(property="users_id", type="integer", example=3, description="ID of the renter making the reservation"),
 *             @OA\Property(property="listings_id", type="integer", example=8, description="ID of the listing being reserved"),
 *             @OA\Property(property="start_date", type="string", format="date", example="2025-10-12"),
 *             @OA\Property(property="end_date", type="string", format="date", example="2025-10-15"),
 *             @OA\Property(property="guests", type="integer", example=2),
 *             @OA\Property(property="status", type="string", example="pending", enum={"pending", "confirmed", "cancelled"}),
 *             @OA\Property(property="total_price", type="number", example=240.00)
 *         )
 *     ),
 * security={
 *         {"ApiKey": {}}
 *     },
 *     @OA\Response(
 *         response=200,
 *         description="Reservation created successfully"
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid or missing reservation fields"
 *     )
 * )
 */
Flight::route('POST /reservations', function() {
    Flight::auth_middleware()->authorizeRole(Roles::USER);
    $data = Flight::request()->data->getData();
    $user = Flight::get('user');
    //Force renter ownership
    $data['users_id'] = $user->id; //Prevent spoofing by overriding users_id with JWT user.
//Calculate total_price in backend before inserting
    $listing = Flight::listingsService()->getById($data['listings_id']);
if (!$listing) {
    Flight::halt(404, 'Listing not found');
    return;
}

$start = new DateTime($data['start_date']);
$end = new DateTime($data['end_date']);
$days = $start->diff($end)->days;

$data['total_price'] = $listing['price'] * $days;

    Flight::json(Flight::reservationsService()->create($data));
});

/**
 * @OA\Patch(
 *     path="/reservations/{id}/status",
 *     tags={"reservations"},
 *     summary="Update the status of a reservation (e.g., completed or pending)",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Reservation ID",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"status"},
 *             @OA\Property(property="status", type="string", example="completed", enum={"pending", "cancelled", "completed"})
 *         )
 *     ),
 * security={
 *         {"ApiKey": {}}
 *     },
 *     @OA\Response(
 *         response=200,
 *         description="Reservation status updated successfully"
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Missing or invalid status field"
 *     )
 * )
 */
Flight::route('PATCH /reservations/@id/status', function($id) {
    Flight::auth_middleware()->authorizeRole(Roles::LANDLORD);
    
    $reservation = Flight::reservationsService()->getById($id);
    if (!$reservation) {
        Flight::halt(404, 'Reservation not found');
    }
    //Landlord ownership check
    Flight::auth_middleware()->authorizeOwnership($reservation['users_id']);
    $data = Flight::request()->data->getData();
    $status = $data['status'] ?? null;

    if (!$status) {
        Flight::json(['error' => 'Missing status field'], 400);
        return;
    }

    Flight::json(Flight::reservationsService()->updateReservationsStatus($id, $status));
});

/**
 * @OA\Put(
 *     path="/reservations/{id}",
 *     tags={"reservations"},
 *     summary="Update general reservation details (partial updates allowed)",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Reservation ID",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="start_date", type="string", format="date", example="2025-11-01"),
 *             @OA\Property(property="end_date", type="string", format="date", example="2025-11-05"),
 *             @OA\Property(property="guests", type="integer", example=3),
 *             @OA\Property(property="special_requests", type="string", example="Near window if possible."),
 *         )
 *     ),
 * security={
 *         {"ApiKey": {}}
 *     },
 *     @OA\Response(
 *         response=200,
 *         description="Reservation updated successfully"
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid JSON or missing data"
 *     )
 * )
 */
Flight::route('PUT /reservations/@id', function($id) {
    Flight::auth_middleware()->authorizeRole(Roles::USER);

    $reservation = Flight::reservationsService()->getById($id);
    if (!$reservation) {
        Flight::halt(404, 'Reservation not found');
    }
     //Ownership check: renter can only update their own reservation
    Flight::auth_middleware()->authorizeOwnership($reservation['users_id']);

    $raw = Flight::request()->data->getData();
    $data = is_string($raw) ? json_decode($raw, true) : $raw;//If $raw is a JSON string, decode it. Otherwise, assume it’s already an array and just use it.

    if (!is_array($data)) {
        Flight::json(['error' => 'Invalid JSON body'], 400);
        return;
    }

    Flight::json(Flight::reservationsService()->update($id, $data));
});

/**
 * @OA\Delete(
 *     path="/reservations/{id}",
 *     tags={"reservations"},
 *     summary="Delete a reservation by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Reservation ID",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 * security={
 *         {"ApiKey": {}}
 *     },
 *     @OA\Response(
 *         response=200,
 *         description="Reservation deleted successfully"
 *     )
 * )
 */
Flight::route('DELETE /reservations/@id', function($id) {
    Flight::auth_middleware()->authorizeRole(Roles::USER); //labeled as 'cancel' on frontend

    $reservation = Flight::reservationsService()->getById($id);
    if (!$reservation) {
        Flight::halt(404, 'Reservation not found');
    }

    //Ownership check: renter can only cancel their own reservation
    Flight::auth_middleware()->authorizeOwnership($reservation['users_id']);

    Flight::json(Flight::reservationsService()->delete($id));
});
?>
