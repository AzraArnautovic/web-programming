<?php
require_once 'BaseService.php';
require_once(__DIR__ . '/../dao/ReservationsDao.php');

class ReservationsService extends BaseService {

    public function __construct() {
        parent::__construct(new ReservationsDao());
    }

    public function getReservationsByUser($user_id) {
        return $this->dao->get_reservations_by_user($user_id);
    }

    public function getReservationsForLandlord($landlord_id) {
        return $this->dao->get_reservations_for_landlord($landlord_id);
    }
}
?>
