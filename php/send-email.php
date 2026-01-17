<?php
$to = 'heyitsalicheema@gmail.com';

function url() {
    return sprintf(
        "%s://%s",
        isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
        $_SERVER['SERVER_NAME']
    );
}

if($_POST) {

    $name = trim(stripslashes($_POST['name']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $subject = trim(stripslashes($_POST['subject']));
    $contact_message = trim(stripslashes($_POST['message']));

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email address.");
    }

    // Prevent header injection
    if (preg_match("/[\r\n]/", $name) || preg_match("/[\r\n]/", $email)) {
        die("Header injection detected.");
    }

    if ($subject == '') { 
        $subject = "Contact Form Submission"; 
    }

    // Build message
    $message = "Email from: " . htmlspecialchars($name) . "<br />";
    $message .= "Email address: " . htmlspecialchars($email) . "<br />";
    $message .= "Message: <br />" . nl2br(htmlspecialchars($contact_message));
    $message .= "<br />-----<br/>This email was sent from your site " . url() . " contact form.<br />";

    $from =  $name . " <" . $email . ">";
    
    $headers = "From: " . $from . "\r\n";
    $headers .= "Reply-To: ". $email . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

    ini_set("sendmail_from", $to); // for windows server

    if (mail($to, $subject, $message, $headers)) {
        echo "OK";
    } else {
        echo "Something went wrong. Please try again.";
    }
}
?>