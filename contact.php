<?php
/**
 * COMPLETE contact.php 
 * Save this file in your root folder (the same place as contact.html)
 */

$errors = [];
$errorMessage = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Sanitize and capture inputs from your HTML form fields
    $name    = strip_tags(trim($_POST['name']));
    $email   = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $phone   = strip_tags(trim($_POST['phone']));
    $message = strip_tags(trim($_POST['message']));

    // 2. Validation Logic
    if (empty($name)) {
        $errors[] = 'Name is required';
    }

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'A valid email is required';
    }

    if (empty($message)) {
        $errors[] = 'Message cannot be empty';
    }

    // 3. Sending Logic (Only runs if there are no validation errors)
    if (empty($errors)) {
        $toEmail      = 'info@ecogreensolutions.co.ke'; 
        $emailSubject = "New Portfolio Message from $name";
        
        // CRITICAL: This MUST match your live domain (ecogreensolutions.co.ke) 
        // to prevent being blocked as a spoofing attempt.
        $senderEmail  = 'contact-form@ecogreensolutions.co.ke';
        
        // Constructing Headers for HTML Email
        $headers  = "From: " . $senderEmail . "\r\n";
        $headers .= "Reply-To: " . $email . "\r\n"; 
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

        // HTML Body construction
        $body = "<html><body style='font-family: Arial, sans-serif;'>" .
                "<h2>New Contact Request</h2>" .
                "<p><strong>Name:</strong> {$name}</p>" .
                "<p><strong>Phone:</strong> {$phone}</p>" .
                "<p><strong>Email:</strong> {$email}</p>" .
                "<p style='background: #f4f4f4; padding: 10px;'><strong>Message:</strong><br>" . nl2br($message) . "</p>" .
                "</body></html>";

        // 4. Attempt to send using the server's mail engine
        if (mail($toEmail, $emailSubject, $body, $headers)) {
            // Success: Redirect back to your contact page with a success flag
            header('Location: contact.html?success=1'); 
            exit;
        } else {
            $errorMessage = 'Server Error: The mail engine failed to send your message. Contact your host provider.';
        }
    } else {
        // Display validation errors
        $allErrors = join('<br/>', $errors);
        $errorMessage = "<p style='color: red;'>{$allErrors}</p>";
        echo $errorMessage;
    }
} else {
    // Direct access protection
    header('Location: contact.html');
    exit;
}
?>