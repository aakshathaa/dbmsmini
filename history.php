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

$attendee_id = $_SESSION['attendee_id'];

// Handle cancellation
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['cancel_ticket'])) {
    $ticket_id = intval($_POST['ticket_id']);

    // Delete from BUYS
    $cancel_sql = "DELETE FROM BUYS WHERE A_ID = ? AND T_ID = ?";
    $cancel_stmt = $conn->prepare($cancel_sql);
    $cancel_stmt->bind_param("ii", $attendee_id, $ticket_id);
    if ($cancel_stmt->execute()) {
        echo "<script>alert('Booking canceled successfully.'); window.location.href='history.php';</script>";
        exit();
    } else {
        echo "Cancellation failed: " . $conn->error;
    }
}

// Fetch booking history
$sql = "SELECT 
            e.E_NAME, e.DATE, v.VNAME, v.ADDR, v.VCITY, 
            t.TYPE, t.PRICE, t.T_ID, 
            p.PMETHOD, p.PDATE
        FROM BUYS b
        JOIN TICKET t ON b.T_ID = t.T_ID
        JOIN EVENTT e ON t.E_ID = e.E_ID
        JOIN VENUE v ON e.V_ID = v.V_ID
        LEFT JOIN PAYMENT p ON p.A_ID = b.A_ID
        WHERE b.A_ID = ?
        ORDER BY e.DATE DESC";


$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $attendee_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Booking History</title>
    <style>
        body {
            font-family: Arial;
            background: #fafafa;
            padding: 20px;
        }
        .ticket {
            border: 1px solid #ccc;
            background: #fff;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 8px;
        }
        h2 { color: #444; }
        form { display: inline; }
        button {
            background-color: #c62828;
            color: white;
            border: none;
            padding: 6px 14px;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #b71c1c;
        }
    </style>
</head>
<body>

<h2>Your Booking History</h2>

<?php
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div class='ticket'>";
        echo "<p><strong>Event:</strong> " . htmlspecialchars($row['E_NAME']) . "</p>";
        echo "<p><strong>Date:</strong> " . $row['DATE'] . "</p>";
        echo "<p><strong>Venue:</strong> " . htmlspecialchars($row['VNAME']) . ", " . htmlspecialchars($row['ADDR']) . ", " . htmlspecialchars($row['VCITY']) . "</p>";
        echo "<p><strong>Ticket Type:</strong> " . htmlspecialchars($row['TYPE']) . " - ₹" . number_format($row['PRICE'], 2) . "</p>";
        echo "<p><strong>Payment:</strong> " . ($row['PMETHOD'] ?? 'N/A') . " on " . ($row['PDATE'] ?? 'N/A') . "</p>";

        // Cancel button
        echo "<form method='POST'>";
        echo "<input type='hidden' name='ticket_id' value='" . $row['T_ID'] . "'>";
        echo "<button type='submit' name='cancel_ticket'>Cancel Booking</button>";
        echo "</form>";

        echo "</div>";
    }
} else {
    echo "<p>You have not booked any tickets yet.</p>";
}
?>

</body>
</html>
