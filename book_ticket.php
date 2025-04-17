<?php
session_start();

$host = "localhost";
$user = "root";
$pass = "@dbms123";
$db = "eventmanage";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

if (!isset($_SESSION['attendee_id'])) {
    header("Location: userlog.html");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!isset($_POST['ticket_id'])) {
        echo "<script>alert('Ticket ID missing!'); window.location.href = 'events.php';</script>";
        exit();
    }

    $attendee_id = $_SESSION['attendee_id'];
    $ticket_id = intval($_POST['ticket_id']);

    // Check if already booked
    $check_sql = "SELECT * FROM BUYS WHERE A_ID = ? AND T_ID = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("ii", $attendee_id, $ticket_id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('You have already booked this ticket.'); window.location.href = 'events.php';</script>";
        exit();
    }

    // If not booked, store ticket in session and go to payment
    $_SESSION['ticket_id'] = $ticket_id;
    header("Location: payment.php");
    exit();
} else {
    echo "Invalid request.";
}
?>
