<?php
require_once 'BaseService.php';
require_once(__DIR__ . '/../dao/WishlistDao.php');

class WishlistService extends BaseService {

    public function __construct() {
        parent::__construct(new WishlistDao());
    }

    public function getWishlistByUser($user_id) {
        return $this->dao->get_wishlist_by_user($user_id);
    }

     public function checkIfInWishlist($user_id, $listing_id) {
        return $this->dao->check_if_in_wishlist($user_id, $listing_id);
    }

    public function addToWishlist($wishlist) {
        return $this->dao->add_to_wishlist($wishlist);
    }

    public function removeFromWishlist($user_id, $listing_id) {
        return $this->dao->remove_from_wishlist($user_id, $listing_id);
    }

    
}
?>
