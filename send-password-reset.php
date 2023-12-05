<?php
require_once 'email.php';
require 'vendor/autoload.php'; // Include SendGrid library

if (isset($_POST["email"])) {
    $recipientEmail = $_POST["email"]; // Use a different variable for recipient email

    $token = bin2hex(random_bytes(16));
    $token_hash = hash("sha256", $token);
    $expiry = date("Y-m-d H:i:s", time() + 60 * 5);

    $mysqli = require __DIR__ . "/config.php";

    $sql = "UPDATE users
            SET reset_token_hash = ?,
                reset_token_expires_at = ?
            WHERE email = ?";

    $stmt = $mysqli->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("sss", $token_hash, $expiry, $recipientEmail); // Use the recipient's email

        $stmt->execute();

        if ($mysqli->affected_rows) {
            $sendgrid = new \SendGrid(SENDGRID_API_KEY);

            // Create an email using the SendGrid template
            $email = new \SendGrid\Mail\Mail();
            $email->setFrom("micoffeemail@micoffe.store", "Micoffe");
            $email->setTemplateId("d-0af61a9c4df54f23abd73a4087a77ac1"); // Set the template ID

            // Set dynamic template data (replace placeholders in the template)
            $email->addDynamicTemplateData("reset_link", "http://localhost:3000/reset-password.php?token=$token");
            $email->addTo($recipientEmail, "Recipient Name"); // Set recipient's email

            try {
                $response = $sendgrid->send($email);
            
                if ($response->statusCode() == 202) {
                    header('Location: success.php'); // Redirect to success.php
                    exit();
                } else {
                    header('Location: error.php'); // Redirect to error.php
                    exit();
                }
            } catch (Exception $e) {
                header('Location: error.php'); // Redirect to error.php
                exit();
            }            
        } else {
            echo "Failed to update the database.";
        }
    } else {
        echo "Failed to prepare the SQL statement: " . $mysqli->error;
    }
} else {
    echo "Email not provided.";
}
?>
