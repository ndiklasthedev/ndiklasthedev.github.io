<?php

$errors = [];
$errorMessage = '';

if (!empty($_POST)) {
    // Sanitize and capture inputs
    $name = strip_tags(trim($_POST['name']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $message = strip_tags(trim($_POST['message']));
    $phone = strip_tags(trim($_POST['phone']));

    // Validation
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

    if (empty($errors)) {
        $toEmail = 'greentropikal@outlook.com';
        $emailSubject = 'New email from your contact form';
        
        // IMPORTANT: Replace 'yourdomain.com' with your actual website domain
        // This MUST be an email that looks like it belongs to your server.
        $senderEmail = 'website-form@yourdomain.com'; 
        
        // Constructing Headers as a string for maximum compatibility
        $headers  = "From: " . $senderEmail . "\r\n";
        $headers .= "Reply-To: " . $email . "\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

        // Constructing the Email Body
        $bodyParagraphs = [
            "<strong>Name:</strong> {$name}",
            "<strong>Phone:</strong> {$phone}",
            "<strong>Email:</strong> {$email}",
            "<br><strong>Message:</strong><br>",
            nl2br($message) // Converts line breaks to <br> tags
        ];
        $body = "<html><body>" . join("<br>", $bodyParagraphs) . "</body></html>";

        // Attempt to send
        if (mail($toEmail, $emailSubject, $body, $headers)) {
            // Redirect to thank you page
            header('Location: thank-you.html');
            exit; 
        } else {
            $errorMessage = 'Oops, something went wrong. The server failed to send the message.';
        }
    } else {
        // Display validation errors
        $allErrors = join('<br/>', $errors);
        $errorMessage = "<p style='color: red;'>{$allErrors}</p>";
    }
}

?>

<?php if (!empty($errorMessage)): ?>
    <div class="error-notification">
        <?php echo $errorMessage; ?>
    </div>
<?php endif; ?>