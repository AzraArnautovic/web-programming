<?php
require_once 'BaseService.php';
require_once(__DIR__ . '/../dao/MessagesDao.php');

class MessagesService extends BaseService {

    public function __construct() {
        parent::__construct(new MessagesDao());
    }

    public function getInbox($receiver_id) {
        return $this->dao->get_inbox($receiver_id);
    }

    public function getOutbox($sender_id) {
        return $this->dao->get_outbox($sender_id);
    }

    public function getConversation($user1, $user2) {
        return $this->dao->get_conversation($user1, $user2);
    }
}
?>
