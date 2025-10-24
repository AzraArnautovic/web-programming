<?php
require_once __DIR__ . "/BaseDao.php";

class MessagesDao extends BaseDao {

    public function __construct() {
        parent::__construct("messages");
    }

    public function send_message($message) {
        $data = [
            "sender_id"   => $message["sender_id"],   //who sends the message
            "receiver_id" => $message["receiver_id"], //who receives the message
            "content"     => $message["content"]
        ];
        return $this->insert($data);
    }

    //for testing
    public function get_all_messages() {
        return $this->getAll();
    }

    //Get all messages received by a user (Inbox)
    public function get_inbox($receiver_id) {
        $stmt = $this->connection->prepare("
            SELECT m.*, 
                   s.first_name AS sender_name, 
                   s.last_name AS sender_lastname, 
                   s.email AS sender_email
            FROM messages m
            JOIN users s ON m.sender_id = s.id
            WHERE m.receiver_id = :receiver_id
            ORDER BY m.sent_at DESC
        ");
        $stmt->bindParam(":receiver_id", $receiver_id);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    //Get all messages sent by a user (Outbox)
    public function get_outbox($sender_id) {
        $stmt = $this->connection->prepare("
            SELECT m.*, 
                   r.first_name AS receiver_name, 
                   r.last_name AS receiver_lastname, 
                   r.email AS receiver_email
            FROM messages m
            JOIN users r ON m.receiver_id = r.id
            WHERE m.sender_id = :sender_id
            ORDER BY m.sent_at DESC
        ");
        $stmt->bindParam(":sender_id", $sender_id);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    //Fetches chat between two specific users
    public function get_conversation($user1_id, $user2_id) {
        $stmt = $this->connection->prepare("
            SELECT m.*, 
                   s.first_name AS sender_name,
                   r.first_name AS receiver_name
            FROM messages m
            JOIN users s ON m.sender_id = s.id
            JOIN users r ON m.receiver_id = r.id
            WHERE (m.sender_id = :user1_id AND m.receiver_id = :user2_id)
               OR (m.sender_id = :user2_id AND m.receiver_id = :user1_id)
            ORDER BY m.sent_at ASC
        ");
        $stmt->bindParam(":user1_id", $user1_id);
        $stmt->bindParam(":user2_id", $user2_id);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function delete_message($id) {
        return $this->delete($id);
    }
}
?>
