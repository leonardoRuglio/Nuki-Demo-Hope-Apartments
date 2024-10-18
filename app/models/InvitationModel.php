<?php
// app/models/InvitationModel.php

class Invitation
{
    private $smartlockId;
    private $name;
    private $email;
    private $invitationCode;

    public function __construct($smartlockId, $name, $email)
    {
        $this->smartlockId = $smartlockId;
        $this->name = $name;
        $this->email = $email;
    }

    // Method to generate the invitation code using Nuki API
    public function generateInvitationCode()
    {
        $apiUrl = 'https://api.nuki.io/smartlock/' . $this->smartlockId . '/auth';

        $postData = [
            'name' => $this->name,
            'type' => 13, // Assuming 13 is the type for invitation code
            'remoteAllowed' => true,
            'sendInvite' => false // We will send the invite ourselves
        ];

        $options = [
            'http' => [
                'header' => "Authorization: Bearer " . API_TOKEN . "\r\n" .
                    "Content-Type: application/json\r\n" .
                    "Accept: application/json\r\n",
                'method' => 'POST',
                'content' => json_encode($postData)
            ]
        ];

        $context = stream_context_create($options);
        $response = file_get_contents($apiUrl, false, $context);

        if ($response === FALSE) {
            throw new Exception('Error generating invitation code.');
        }

        $responseData = json_decode($response, true);

        // Check for errors in the response
        if (isset($responseData['errorCode'])) {
            throw new Exception('API Error: ' . $responseData['message']);
        }

        // Get the invitation code from the response
        $this->invitationCode = $responseData['code'] ?? null;

        if (!$this->invitationCode) {
            throw new Exception('Invitation code not received from API.');
        }
    }

    // Method to send the invitation email
    public function sendInvitationEmail()
    {
        $subject = "Your Smartlock Invitation Code";
        $message = "Hello " . $this->name . ",\n\nHere is your invitation code: " . $this->invitationCode . "\n\nUse this code to access the smartlock.";
        $headers = 'From: no-reply@example.com' . "\r\n" .
            'Reply-To: no-reply@example.com' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();

        $mailSent = mail($this->email, $subject, $message, $headers);

        if (!$mailSent) {
            throw new Exception('Failed to send email.');
        }
    }

    // Method to retrieve invitation code
    public function getInvitationCode()
    {
        return $this->invitationCode;
    }

    // (Optional) Method to fetch all users
    public static function fetchAllSmartlockUsers()
    {
        // This function should retrieve all smartlock users from the database
        // Return as an associative array of users with smartlockId, name, and email
        return [
            ['smartlockId' => 1, 'name' => 'John Doe', 'email' => 'johndoe@example.com'],
            ['smartlockId' => 2, 'name' => 'Jane Smith', 'email' => 'janesmith@example.com'],
            // ... add more users as needed
        ];
    }
}
