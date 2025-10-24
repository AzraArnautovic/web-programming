<?php
require_once __DIR__ . "/BaseDao.php";

class WishlistDao extends BaseDao {

    public function __construct() {
        parent::__construct("wishlist");
    }

    public function add_to_wishlist($wishlist) {
        $data = [
            "users_id" => $wishlist["users_id"],
            "listings_id" => $wishlist["listings_id"]
        ];
        return $this->insert($data);
    }

    public function get_wishlist_by_user($user_id) {
        $stmt = $this->connection->prepare("
            SELECT w.*, l.title, l.municipality, l.price, l.cover_url
            FROM wishlist w
            JOIN listings l ON w.listings_id = l.id
            WHERE w.users_id = :user_id
            ORDER BY w.created_at DESC
        ");
        $stmt->bindParam(":user_id", $user_id);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function remove_from_wishlist($user_id, $listing_id) {
        $stmt = $this->connection->prepare("
            DELETE FROM wishlist
            WHERE users_id = :user_id AND listings_id = :listing_id
        ");
        $stmt->bindParam(":user_id", $user_id);
        $stmt->bindParam(":listing_id", $listing_id);
        return $stmt->execute();
    }
    
    public function check_if_in_wishlist($user_id, $listing_id) {
        $stmt = $this->connection->prepare("
            SELECT COUNT(*) AS count
            FROM wishlist
            WHERE users_id = :user_id AND listings_id = :listing_id
        ");
        $stmt->bindParam(":user_id", $user_id);
        $stmt->bindParam(":listing_id", $listing_id);
        $stmt->execute();
        $row = $stmt->fetch();
        return $row["count"] > 0;
    }
}
?>
