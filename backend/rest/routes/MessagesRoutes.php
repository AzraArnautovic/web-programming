<?php
/**
 * @OA\Get(
 *      path="/messages",
 *      tags={"messages"},
 *      summary="Get all messages",
 *      @OA\Response(
 *           response=200,
 *           description="Array of all messages in the database"
 *      )
 * )
 */
Flight::route('GET /messages', function() {
    Flight::json(Flight::messagesService()->getAll());
});

/**
 * @OA\Post(
 *     path="/messages",
 *     tags={"messages"},
 *     summary="Send a new message",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"sender_id", "receiver_id", "content"},
 *             @OA\Property(property="sender_id", type="integer", example=3, description="ID of the user sending the message"),
 *             @OA\Property(property="receiver_id", type="integer", example=5, description="ID of the user receiving the message"),
 *             @OA\Property(property="content", type="string", example="Hi, is this listing still available?"),
 *             @OA\Property(property="sent_at", type="string", format="date-time", example="2025-11-10T10:45:00Z")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Message sent successfully"
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Missing sender_id, receiver_id, or content"
 *     )
 * )
 */
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

/**
 * @OA\Get(
 *     path="/messages/inbox/{receiver_id}",
 *     tags={"messages"},
 *     summary="Get inbox messages for a specific user (received messages)",
 *     @OA\Parameter(
 *         name="receiver_id",
 *         in="path",
 *         required=true,
 *         description="ID of the user whose inbox to retrieve",
 *         @OA\Schema(type="integer", example=5)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Array of messages received by the specified user"
 *     )
 * )
 */
Flight::route('GET /messages/inbox/@receiver_id', function($receiver_id) {
    $messages = Flight::messagesService()->getInbox($receiver_id);

    if (empty($messages)) {
        Flight::json(['message' => 'Inbox is empty for this user.']);
    } else {
        Flight::json($messages);
    }
});
/**
 * @OA\Get(
 *     path="/messages/outbox/{sender_id}",
 *     tags={"messages"},
 *     summary="Get outbox messages for a specific user (sent messages)",
 *     @OA\Parameter(
 *         name="sender_id",
 *         in="path",
 *         required=true,
 *         description="ID of the user whose sent messages to retrieve",
 *         @OA\Schema(type="integer", example=3)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Array of messages sent by the specified user"
 *     )
 * )
 */
Flight::route('GET /messages/outbox/@sender_id', function($sender_id) {
    $messages = Flight::messagesService()->getOutbox($sender_id);

    if (empty($messages)) {
        Flight::json(['message' => 'Outbox is empty for this user.']);
    } else {
        Flight::json($messages);
    }
});

/**
 * @OA\Get(
 *     path="/messages/conversation/{user1}/{user2}",
 *     tags={"messages"},
 *     summary="Get conversation between two users",
 *     @OA\Parameter(
 *         name="user1",
 *         in="path",
 *         required=true,
 *         description="ID of the first user in the conversation",
 *         @OA\Schema(type="integer", example=3)
 *     ),
 *     @OA\Parameter(
 *         name="user2",
 *         in="path",
 *         required=true,
 *         description="ID of the second user in the conversation",
 *         @OA\Schema(type="integer", example=5)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Array of messages exchanged between the two users"
 *     )
 * )
 */
Flight::route('GET /messages/conversation/@user1/@user2', function($user1, $user2) {
    $conversation = Flight::messagesService()->getConversation($user1, $user2);

    if (empty($conversation)) {
        Flight::json(['message' => 'No conversation found between these users.']);
    } else {
        Flight::json($conversation);
    }
});

/**
 * @OA\Delete(
 *     path="/messages/{id}",
 *     tags={"messages"},
 *     summary="Delete a message by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Message ID",
 *         @OA\Schema(type="integer", example=10)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Message deleted successfully"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Message not found or could not be deleted"
 *     )
 * )
 */
Flight::route('DELETE /messages/@id', function($id) {
    $deleted = Flight::messagesService()->delete($id);

    if ($deleted) {
        Flight::json(['message' => 'Message deleted successfully.']);
    } else {
        Flight::json(['error' => 'Failed to delete message or message not found.'], 404);
    }
});
?>