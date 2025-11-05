<?php

Flight::route('GET /reservations', function() {
    Flight::json(Flight::reservationsService()->getAll());
});

Flight::route('GET /reservations/@id', function($id) {
    Flight::json(Flight::reservationsService()->getById($id));
});

//GET reservations by renter (user) ID 
Flight::route('GET /reservations/user/@user_id', function($user_id) {
    Flight::json(Flight::reservationsService()->getReservationsByUser($user_id));
});

Flight::route('GET /reservations/landlord/@landlord_id', function($landlord_id) {
    Flight::json(Flight::reservationsService()->getReservationsForLandlord($landlord_id));
});

//POST create a new reservation
Flight::route('POST /reservations', function() {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::reservationsService()->create($data));
});

//PATCH update reservation status 
Flight::route('PATCH /reservations/@id/status', function($id) {
    $data = Flight::request()->data->getData();
    $status = $data['status'] ?? null;

    if (!$status) {
        Flight::json(['error' => 'Missing status field'], 400);
        return;
    }

    Flight::json(Flight::reservationsService()->updateReservationsStatus($id, $status));
});

//PUT update reservation (general fields)
Flight::route('PUT /reservations/@id', function($id) {
    $raw = Flight::request()->data->getData();
    $data = is_string($raw) ? json_decode($raw, true) : $raw;

    if (!is_array($data)) {
        Flight::json(['error' => 'Invalid JSON body'], 400);
        return;
    }

    Flight::json(Flight::reservationsService()->update($id, $data));
});

//DELETE reservation
Flight::route('DELETE /reservations/@id', function($id) {
    Flight::json(Flight::reservationsService()->delete($id));
});
?>
