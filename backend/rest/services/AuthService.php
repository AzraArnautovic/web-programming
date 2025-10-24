<?php
require_once 'BaseService.php';
require_once(__DIR__ . '/../dao/UsersDao.php');

class AuthService extends BaseService {

    protected $auth_dao;

    public function __construct() {
        $this->auth_dao = new UsersDao(); 
        parent::__construct($this->auth_dao);
    }

    public function get_user_by_email($email) {
        return $this->auth_dao->get_user_by_email($email);
    }

    public function register($entity) {
        if (empty($entity['email']) || empty($entity['password']) || empty($entity['role'])) {
            return ['success' => false, 'error' => 'Email, password, and role are required.'];
        }

        //Check if email already exists
        $email_exists = $this->auth_dao->get_user_by_email($entity['email']);
        if ($email_exists) {
            return ['success' => false, 'error' => 'Email already registered.'];
        }

        $entity['password_hash'] = password_hash($entity['password'], PASSWORD_BCRYPT);
        unset($entity['password']); //remove plain password

        //Create user
        $entity = parent::create($entity);

        unset($entity['password_hash']); //don’t return hash in response

        return ['success' => true, 'data' => $entity];
    }

    public function login($entity) {
        if (empty($entity['email']) || empty($entity['password'])) {
            return ['success' => false, 'error' => 'Email and password are required.'];
        }

        $user = $this->auth_dao->get_user_by_email($entity['email']);
        if (!$user || !password_verify($entity['password'], $user['password_hash'])) {
            return ['success' => false, 'error' => 'Invalid email or password.'];
        }

        unset($user['password_hash']); //remove sensitive data before returning
        return ['success' => true, 'data' => $user];
    }
}
?>
