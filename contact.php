<?php

$errors = [];
$errorMessage = '';

if (!empty($_POST)) {
    // 1. Sanitize and capture inputs from your HTML form fields
    $name = strip_tags(trim($_POST['name']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $message = strip_tags(trim($_POST['message']));
    $phone = strip_tags(trim($_POST['phone']));

    // 2. Validation Logic
    if (empty($name)) {
        $errors[] = 'Name is empty';
    }

    if (empty($email)) {
        $errors[] = 'Email is empty';
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Email is invalid';
    }

    if (empty($message)) {
        $errors[] = 'Message is empty';
    }

    // 3. Sending Logic (Only runs if there are no errors)
    if (empty($errors)) {
        $toEmail = 'info@ecogreensolutions.co.ke'; // Your recipient address
        $emailSubject = 'New email from your contact form';
        
        // CRITICAL FIX: The sender must be an email on YOUR domain to prevent "spoofing" blocks
        $senderEmail = 'contact-form@ecogreensolutions.co.ke';
        
        // Headers formatted as a string for maximum server compatibility
        $headers  = "From: " . $senderEmail . "\r\n";
        $headers .= "Reply-To: " . $email . "\r\n"; // So you can reply directly to the user
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

        // HTML Body construction
        $body = "<html><body>" .
                "<h2>New Contact Request</h2>" .
                "<b>Name:</b> {$name}<br>" .
                "<b>Phone:</b> {$phone}<br>" .
                "<b>Email:</b> {$email}<br><br>" .
                "<b>Message:</b><br>" . nl2br($message) . 
                "</body></html>";

        // 4. Attempt to send using the server's mail engine
        if (mail($toEmail, $emailSubject, $body, $headers)) {
            // Redirect to your thank you page or index if successful
            header('Location: index.html?status=success'); 
            exit;
        } else {
            $errorMessage = 'Oops, something went wrong. The server failed to send the message.';
        }
    } else {
        // Handle validation errors
        $allErrors = join('<br/>', $errors);
        $errorMessage = "<p style='color: red;'>{$allErrors}</p>";
    }
}
?>