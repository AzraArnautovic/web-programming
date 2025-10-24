<?php
require_once 'BaseService.php';
require_once(__DIR__ . '/../dao/ListingsDao.php');

class ListingsService extends BaseService {

    public function __construct() {
        parent::__construct(new ListingsDao());
    }

    public function getListingsByUser($user_id) {
        return $this->dao->get_listings_by_user($user_id);
    }
}
?>
