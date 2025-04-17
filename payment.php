<?php
session_start();
$host = "localhost";
$user = "root";
$pass = "@dbms123";
$db = "eventmanage";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

if (!isset($_SESSION['attendee_id']) || !isset($_SESSION['ticket_id'])) {
    echo "Invalid access.";
    exit();
}

$attendee_id = $_SESSION['attendee_id'];
$ticket_id = $_SESSION['ticket_id'];

// Get ticket info
$ticket_sql = "SELECT TYPE, PRICE FROM TICKET WHERE T_ID = ?";
$ticket_stmt = $conn->prepare($ticket_sql);
$ticket_stmt->bind_param("i", $ticket_id);
$ticket_stmt->execute();
$ticket_result = $ticket_stmt->get_result();
$ticket = $ticket_result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $payment_method = $_POST['payment_method'];
    $password = $_POST['password'];

    // Authenticate user
    $auth_sql = "SELECT A_PASSWORD FROM ATTENDEES WHERE A_ID = ?";
    $auth_stmt = $conn->prepare($auth_sql);
    $auth_stmt->bind_param("i", $attendee_id);
    $auth_stmt->execute();
    $auth_result = $auth_stmt->get_result();
    $auth = $auth_result->fetch_assoc();

    if ($auth && $auth['A_PASSWORD'] === $password) {
        // Insert payment
        $insert_payment = "INSERT INTO PAYMENT (PDATE, PMETHOD, A_ID, T_ID) VALUES (CURDATE(), ?, ?, ?)";
$payment_stmt = $conn->prepare($insert_payment);
$payment_stmt->bind_param("sii", $payment_method, $attendee_id, $ticket_id);

        if ($payment_stmt->execute()) {
            // Insert booking AFTER payment
            $insert_booking = "INSERT INTO BUYS (A_ID, T_ID) VALUES (?, ?)";
            $booking_stmt = $conn->prepare($insert_booking);
            $booking_stmt->bind_param("ii", $attendee_id, $ticket_id);
            $booking_stmt->execute();

            // Clean session & redirect
            unset($_SESSION['ticket_id']);
            echo "<script>alert('Payment successful! Your ticket has been booked.'); window.location.href='history.php';</script>";
            exit();
        } else {
            echo "<p style='color:red;'>Payment failed. Please try again.</p>";
        }
    } else {
        echo "<p style='color:red;'>Invalid password. Please try again.</p>";
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Payment</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f1ee;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .payment-box {
            background: #fff;
            padding: 30px 40px;
            border-radius: 15px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.1);
            width: 400px;
        }
        h2 {
            text-align: center;
            color: #5d4037;
        }
        p {
            font-size: 16px;
            color: #333;
        }
        select, input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 12px 0;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 14px;
        }
        button {
            width: 100%;
            background: linear-gradient(to right, #8d6e63, #5d4037);
            color: white;
            padding: 12px;
            border: none;
            border-radius: 50px;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        button:hover {
            background: linear-gradient(to right, #6d4c41, #3e2723);
        }
    </style>
</head>
<body>
    <div class="payment-box">
        <h2>Confirm Payment</h2>
        <p><strong>Ticket Type:</strong> <?= htmlspecialchars($ticket['TYPE']) ?></p>
        <p><strong>Amount:</strong> ₹<?= number_format($ticket['PRICE'], 2) ?></p>

        <form method="POST">
            <label>Payment Method:</label>
            <select name="payment_method" required>
                <option value="">Select</option>
                <option value="UPI">UPI</option>
                <option value="Card">Card</option>
            </select>

            <label>Password to confirm:</label>
            <input type="password" name="password" required>

            <button type="submit">Pay Now</button>
        </form>
    </div>
</body>
</html>
