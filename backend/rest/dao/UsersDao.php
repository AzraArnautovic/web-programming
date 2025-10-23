<?php
require_once __DIR__ . "/BaseDao.php";

class UsersDao extends BaseDao {

    public function __construct() {
        parent::__construct("users");  //tells BaseDao to use tht table
    }

    public function get_user_by_email($email) {
        $stmt = $this->connection->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch();
    }

    //register new user
    public function insert_user($user) {
        $data = [
            "role" => $user["role"],             
            "first_name" => $user["first_name"],
            "last_name" => $user["last_name"],
            "email" => $user["email"],
            "password_hash" => $user["password_hash"],  //will already be hashed in BLL
        ];
        return $this->insert($data);
    }

    //for dashboard
    public function get_user_by_id($id) {
        return $this->getById($id);
    }
}
?>
