<?php
require_once 'dao/MessagesDao.php';

$msgDao = new MessagesDao();

// Send new message
$msgDao->send_message([
    "sender_id" => 12,        //renter
    "receiver_id" => 11,      //landlord
    "content" => "Hello! I'm interested in your apartment. Is it available next week?"
]);

// Fetch inbox for landlord (receiver)
echo "<h3>Landlord's Inbox:</h3>";
print_r($msgDao->get_inbox(11));

// Fetch outbox for renter (sender)
echo "<h3>Renter's Sent Messages:</h3>";
print_r($msgDao->get_outbox(12));

// Fetch full conversation between the two users
echo "<h3>Conversation between user 3 and 4:</h3>";
print_r($msgDao->get_conversation(12, 11));
?>
