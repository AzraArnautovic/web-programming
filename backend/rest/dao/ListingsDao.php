<?php
require_once __DIR__ . "/BaseDao.php";

class ListingsDao extends BaseDao {

    public function __construct() {
        //telling BaseDao tht this dao works w this table
        parent::__construct("listings");
    }

    //used by landlords
    public function insert_listing($listing) {
        $data = [
            "users_id"     => $listing["users_id"], //landlords
            "title"        => $listing["title"],
            "municipality" => $listing["municipality"],
            "address"      => $listing["address"],
            "price"        => $listing["price"],
            "beds"         => $listing["beds"],
            "baths"        => $listing["baths"],
            "heating"      => $listing["heating"], //ENUM:Central, Electric, Wood Stove
            "size_m2"      => $listing["size_m2"],
            "cover_url"    => $listing["cover_url"],
            "description"  => $listing["description"],
            "amenities"    => $listing["amenities"]
        ];
        return $this->insert($data);
    }

    //for homepage or renters
    public function get_all_listings() {
        return $this->getAll();
    }

    //for viewing single listing details
    public function get_listing_by_id($id) {
        return $this->getById($id);
    }

    //for landlord dashboard
    public function get_listings_by_user($user_id) {
        $stmt = $this->connection->prepare("SELECT * FROM listings WHERE users_id = :user_id");
        $stmt->bindParam(":user_id", $user_id);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    //for landlord edits
    public function update_listing($id, $listing) {
        return $this->update($id, $listing);
    }

    //landlord removes
    public function delete_listing($id) {
        return $this->delete($id);
    }
}
?>
