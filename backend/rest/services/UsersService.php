<?php
require_once 'BaseService.php';
require_once(__DIR__ . '/../dao/UsersDao.php');

class UsersService extends BaseService {

    public function __construct() {
        parent::__construct(new UsersDao());
    }

    public function registerUser($data) {
        if (!filter_var($data["email"], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email");
        }

        $data["password_hash"] = password_hash($data["password"], PASSWORD_DEFAULT);
        unset($data["password"]);

        return $this->dao->insert_user($data);
    }

    public function getByEmail($email) {
        return $this->dao->get_user_by_email($email);
    }
}
?>
