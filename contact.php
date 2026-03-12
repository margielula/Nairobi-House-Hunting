<?php
session_start();
$title = "Contact Us - House Hunting System";
include 'header.php';
include 'db.php';

$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $message_text = $conn->real_escape_string($_POST['message']);

    $sql = "INSERT INTO contacts (name, email, phone, message) VALUES ('$name', '$email', '$phone', '$message_text')";

    if ($conn->query($sql) === TRUE) {
        $message = "<h1>Message sent successfully!</h1><p>Thank you for contacting us. We will get back to you soon.</p>";
    } else {
        $message = "<p>Error: " . $conn->error . "</p>";
    }

    $conn->close();
}
?>

<section class="contact-agent">
    <h3>Contact an Agent</h3>
    <?php if ($message) echo $message; ?>
    <form action="contact.php" method="POST">
        <input type="text" name="name" placeholder="Your Full Name" required>
        <input type="email" name="email" placeholder="Your Email" required>
        <input type="text" name="phone" placeholder="Your Phone Number" required>
        <textarea name="message" placeholder="Your Message" rows="4" required></textarea>
        <button type="submit">Send Message</button>
    </form>
</section>

<?php include 'footer.php'; ?>
</html>
