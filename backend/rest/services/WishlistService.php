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
}
?>
