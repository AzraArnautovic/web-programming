<?php
require_once __DIR__ . "/BaseDao.php";

class ReservationsDao extends BaseDao {

    public function __construct() {
        parent::__construct("reservations");
    }

    //renter books listing
    public function insert_reservation($reservation) {
        $data = [
            "listings_id" => $reservation["listings_id"],
            "users_id"    => $reservation["users_id"], //renter id
            "start_date"  => $reservation["start_date"],
            "end_date"    => $reservation["end_date"],
            "total_price" => $reservation["total_price"],
            "status"      => $reservation["status"]    //pending, coancelled, completed.
        ];
        return $this->insert($data);
    }
    public function get_all_reservations() {
        return $this->getAll();
    }

    public function get_reservation_by_id($id) {
        return $this->getById($id);
    }
    //renter dashboard
    public function get_reservations_by_user($user_id) {
        $stmt = $this->connection->prepare("
            SELECT r.*, l.title, l.municipality, l.price
            FROM reservations r
            JOIN listings l ON r.listings_id = l.id
            WHERE r.users_id = :user_id
            ORDER BY r.start_date DESC
        ");
        $stmt->bindParam(":user_id", $user_id);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    //landlord dashboard
    public function get_reservations_for_landlord($landlord_id) {
        $stmt = $this->connection->prepare("
            SELECT r.*, u.first_name AS renter_name, u.email AS renter_email, l.title
            FROM reservations r
            JOIN listings l ON r.listings_id = l.id
            JOIN users u ON r.users_id = u.id
            WHERE l.users_id = :landlord_id
            ORDER BY r.start_date DESC
        ");
        $stmt->bindParam(":landlord_id", $landlord_id);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function update_reservation_status($id, $status) {
        $stmt = $this->connection->prepare("
            UPDATE reservations SET status = :status WHERE id = :id
        ");
        $stmt->bindParam(":status", $status);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    public function delete_reservation($id) {
        return $this->delete($id);
    }
}
?>
