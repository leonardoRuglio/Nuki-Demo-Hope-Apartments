<?php
// app/controllers/SendInvitationController.php

require_once '../../config/config.php';
require_once '../models/InvitationModel.php';

header('Content-Type: application/json');

try {
    $data = json_decode(file_get_contents('php://input'), true);

    // Log incoming request for debugging
    error_log(print_r($data, true));

    if (isset($data['sendToAll']) && $data['sendToAll'] === true) {
        // Send invitations to all users
        $users = Invitation::fetchAllSmartlockUsers();

        foreach ($users as $user) {
            $smartlockId = $user['smartlockId'];
            $name = $user['name'];
            $email = $user['email'];

            // Create and send invitation for each user
            $invitation = new Invitation($smartlockId, $name, $email);
            $invitation->generateInvitationCode();
            $invitation->sendInvitationEmail();
        }

        echo json_encode(['success' => true, 'message' => 'Invitations sent to all users successfully.']);
    } else {
        if (!isset($data['smartlockId']) || !isset($data['email'])) {
            throw new Exception('Smartlock ID and Email are required.');
        }

        $smartlockId = $data['smartlockId'];
        $name = $data['name'] ?? 'Unknown User';
        $email = $data['email'];

        // Create invitation instance
        $invitation = new Invitation($smartlockId, $name, $email);
        $invitation->generateInvitationCode();
        $invitation->sendInvitationEmail();

        echo json_encode(['success' => true, 'message' => 'Invitation sent successfully.']);
    }
} catch (Exception $e) {
    error_log($e->getMessage());
    echo json_encode(['error' => true, 'details' => $e->getMessage()]);
}
