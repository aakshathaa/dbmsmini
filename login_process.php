<?php
session_start();

$host = "localhost";
$user = "root";
$pass = "@dbms123";
$db = "eventmanage";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (!empty($username) && !empty($password)) {
        $stmt = $conn->prepare("SELECT A_ID, A_PASSWORD FROM ATTENDEES WHERE A_USERNAME = ?");
        if (!$stmt) {
            $_SESSION['error'] = "Something went wrong. Please try again.";
            header("Location: userlog.html");
            exit();
        }

        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            // Using plain text comparison (since your passwords aren't hashed)
            if ($password === $user['A_PASSWORD']) {
                $_SESSION['attendee_id'] = $user['A_ID']; // Assign attendee_id to session
                $_SESSION['user_id'] = $user['A_ID'];
                $_SESSION['username'] = $username;
                header("Location: dashboard.html");
                exit();
            }
             else {
                $_SESSION['error'] = "Invalid username or password.";
                header("Location: userlog.html");
                exit();
            }
        } else {
            $_SESSION['error'] = "Invalid username or password.";
            header("Location: userlog.html");
            exit();
        }

        $stmt->close();
    } else {
        $_SESSION['error'] = "Please fill in all fields.";
        header("Location: userlog.html");
        exit();
    }
}

$conn->close();
?>
