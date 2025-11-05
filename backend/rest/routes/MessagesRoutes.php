<?php
Flight::route('GET /messages', function() {
    Flight::json(Flight::messagesService()->getAll());
});

//Send a new message
Flight::route('POST /messages', function() {
    $data = Flight::request()->data->getData();

    if (empty($data['sender_id']) || empty($data['receiver_id']) || empty($data['content'])) {
        Flight::json(['error' => 'Missing sender_id, receiver_id, or content.'], 400);
        return;
    }

    $result = Flight::messagesService()->create($data);
    Flight::json([
        'success' => $result,
        'message' => 'Message sent successfully.'
    ]);
});

//Get inbox (messages received by user)
Flight::route('GET /messages/inbox/@receiver_id', function($receiver_id) {
    $messages = Flight::messagesService()->getInbox($receiver_id);

    if (empty($messages)) {
        Flight::json(['message' => 'Inbox is empty for this user.']);
    } else {
        Flight::json($messages);
    }
});

//Get outbox (messages sent by user)
Flight::route('GET /messages/outbox/@sender_id', function($sender_id) {
    $messages = Flight::messagesService()->getOutbox($sender_id);

    if (empty($messages)) {
        Flight::json(['message' => 'Outbox is empty for this user.']);
    } else {
        Flight::json($messages);
    }
});

//Get conversation between two users
Flight::route('GET /messages/conversation/@user1/@user2', function($user1, $user2) {
    $conversation = Flight::messagesService()->getConversation($user1, $user2);

    if (empty($conversation)) {
        Flight::json(['message' => 'No conversation found between these users.']);
    } else {
        Flight::json($conversation);
    }
});

//Delete a message by ID
Flight::route('DELETE /messages/@id', function($id) {
    $deleted = Flight::messagesService()->delete($id);

    if ($deleted) {
        Flight::json(['message' => 'Message deleted successfully.']);
    } else {
        Flight::json(['error' => 'Failed to delete message or message not found.'], 404);
    }
});
?>