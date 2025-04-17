<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

$host = "localhost";
$user = "root";
$pass = "@dbms123";
$db = "eventmanage";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$first_name = $last_name = $email = $phone = $username = "";
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Form inputs
    $first_name = htmlspecialchars(trim($_POST['first_name']));
    $last_name = htmlspecialchars(trim($_POST['last_name']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $phone = htmlspecialchars(trim($_POST['phone']));
    $username = htmlspecialchars(trim($_POST['username']));
    $password = htmlspecialchars(trim($_POST['password']));
    $confirm_password = htmlspecialchars(trim($_POST['confirm_password']));
    $terms = isset($_POST['terms']) ? 1 : 0;

    // Validation
    if (empty($first_name) || empty($last_name) || empty($email) || empty($phone) ||
        empty($username) || empty($password) || empty($confirm_password)) {
        $errors[] = "All fields are required.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }
    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }
    if (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters.";
    }
    if (!$terms) {
        $errors[] = "You must agree to the terms and conditions.";
    }

    // Check if email/username already exists
    if (empty($errors)) {
        $check_user = $conn->prepare("SELECT A_USERNAME FROM ATTENDEES WHERE A_USERNAME = ?");
        $check_user->bind_param("s", $username);
        $check_user->execute();
        $result_user = $check_user->get_result();

        $check_email = $conn->prepare("SELECT A_EMAIL FROM ATTENDEES WHERE A_EMAIL = ?");
        $check_email->bind_param("s", $email);
        $check_email->execute();
        $result_email = $check_email->get_result();

        if ($result_user->num_rows > 0) {
            $errors[] = "Username is already taken.";
        }
        if ($result_email->num_rows > 0) {
            $errors[] = "Email is already in use.";
        }
    }

    // Insert user
    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO ATTENDEES (A_NAME, A_EMAIL, A_PHONE, A_USERNAME, A_PASSWORD) VALUES (?, ?, ?, ?, ?)");
        if (!$stmt) die("SQL error: " . $conn->error);

        $full_name = $first_name . " " . $last_name;
        $stmt->bind_param("sssss", $full_name, $email, $phone, $username, $password); // password = plain

        if ($stmt->execute()) {
            $_SESSION['success'] = "Account created successfully!";
            header("Location: userlog.html");
            exit();
        } else {
            $errors[] = "Error creating account: " . $stmt->error;
        }

        $stmt->close();
    }
}

$conn->close();
?>