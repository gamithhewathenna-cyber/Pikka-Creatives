<?php
require_once __DIR__ . '/includes/functions.php';
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['ok' => false, 'message' => 'Invalid request.']); exit;
}

$name     = trim($_POST['name'] ?? '');
$email    = trim($_POST['email'] ?? '');
$business = trim($_POST['business'] ?? '');
$need     = trim($_POST['need'] ?? '');
$message  = trim($_POST['message'] ?? '');

if ($name === '' || $email === '' || $message === '') {
    echo json_encode(['ok' => false, 'message' => 'Please fill in all fields.']); exit;
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['ok' => false, 'message' => 'Please enter a valid email address.']); exit;
}

$details = [];
if ($business !== '') $details[] = 'Business: ' . $business;
if ($need !== '')     $details[] = 'Looking for: ' . $need;
$fullMessage = $details ? implode("\n", $details) . "\n\n" . $message : $message;

$stmt = mysqli_prepare(db(), "INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)");
mysqli_stmt_bind_param($stmt, 'sss', $name, $email, $fullMessage);

if (mysqli_stmt_execute($stmt)) {
    echo json_encode(['ok' => true, 'message' => 'Thanks — your message has been sent. We will be in touch soon.']);
} else {
    echo json_encode(['ok' => false, 'message' => 'Could not send right now. Please try again later.']);
}
