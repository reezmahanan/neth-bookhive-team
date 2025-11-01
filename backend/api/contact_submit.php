<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../config/database.php';

$response = ['success' => false, 'message' => 'Unknown error'];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$subject = isset($_POST['subject']) ? trim($_POST['subject']) : 'General';
$message = isset($_POST['message']) ? trim($_POST['message']) : '';

if ($name === '' || $email === '' || $message === '') {
    echo json_encode(['success' => false, 'message' => 'Please fill all required fields.']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Invalid email address.']);
    exit;
}

try {
    $db = (new Database())->getConnection();

    // Ensure contact_messages table exists
    $db->exec("CREATE TABLE IF NOT EXISTS contact_messages (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(191) NOT NULL,
        email VARCHAR(191) NOT NULL,
        subject VARCHAR(100) DEFAULT NULL,
        message TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    $stmt = $db->prepare('INSERT INTO contact_messages (name, email, subject, message) VALUES (:name, :email, :subject, :message)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':subject', $subject);
    $stmt->bindParam(':message', $message);
    $stmt->execute();

    // Send email to admin
    $adminEmail = 'admin@nethbookhive.com';
    $siteName = 'NETH Bookhive';
    $mailSubject = "Contact Form: " . ($subject ?: 'General');
    $mailBody = "<p>You received a new message from the contact form:</p>\n";
    $mailBody .= "<p><strong>Name:</strong> " . htmlspecialchars($name) . "</p>\n";
    $mailBody .= "<p><strong>Email:</strong> " . htmlspecialchars($email) . "</p>\n";
    $mailBody .= "<p><strong>Subject:</strong> " . htmlspecialchars($subject) . "</p>\n";
    $mailBody .= "<p><strong>Message:</strong><br>" . nl2br(htmlspecialchars($message)) . "</p>\n";

    $headers = [];
    $headers[] = 'MIME-Version: 1.0';
    $headers[] = 'Content-type: text/html; charset=utf-8';
    $headers[] = 'From: ' . $siteName . ' <noreply@nethbookhive.com>';
    $headers[] = 'Reply-To: ' . $email;

    // Suppress errors (local dev may not be configured). We'll still return success when stored.
    @mail($adminEmail, $mailSubject, $mailBody, implode("\r\n", $headers));

    echo json_encode(['success' => true, 'message' => 'Message submitted. Thank you!']);
    exit;

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
    exit;
}

?>
