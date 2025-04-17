<?php
session_start();
$host = "localhost";
$user = "root";
$pass = "@dbms123";
$db = "eventmanage";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

echo "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <title>Events</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: transparent;
            margin: 0;
            padding: 20px;
        }
        .event {
            background-color: #f7eee7;
            border-left: 6px solid #8d6e63;
            padding: 20px;
            margin: 20px 0;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(141, 110, 99, 0.2);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .event:hover {
            transform: scale(1.03);
            box-shadow: 0 4px 20px rgba(141, 110, 99, 0.3);
        }
        h3 { margin-top: 0; color: #5d4037; }
        p { color: #444; }
        form { margin-top: 15px; }
        select, input[type='number'] {
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 14px;
            margin-bottom: 10px;
        }
        button {
            background: linear-gradient(90deg, #8d6e63, #5d4037);
            color: white;
            border: none;
            border-radius: 50px;
            padding: 10px 22px;
            font-size: 15px;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.2s ease;
            margin-top: 10px;
        }
        button:hover {
            background: linear-gradient(90deg, #6d4c41, #3e2723);
            transform: scale(1.08);
        }
        hr { border: none; height: 1px; background-color: #ddd; }
    </style>
</head>
<body>";

$sql = "SELECT e.E_ID, e.E_NAME, e.DATE, v.VNAME, v.ADDR, v.VCITY
        FROM EVENTT e
        JOIN VENUE v ON e.V_ID = v.V_ID";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $event_id = $row['E_ID'];
        echo "<div class='event'>";
        echo "<h3>" . htmlspecialchars($row['E_NAME']) . "</h3>";
        echo "<p><strong>Date:</strong> " . $row['DATE'] . "</p>";
        echo "<p><strong>Venue:</strong> " . htmlspecialchars($row['VNAME']) . ", " . htmlspecialchars($row['ADDR']) . ", " . htmlspecialchars($row['VCITY']) . "</p>";

        $ticket_sql = "SELECT * FROM TICKET WHERE E_ID = ?";
        $ticket_stmt = $conn->prepare($ticket_sql);
        $ticket_stmt->bind_param("i", $event_id);
        $ticket_stmt->execute();
        $tickets = $ticket_stmt->get_result();

        if ($tickets->num_rows > 0) {
            echo "<form action='book_ticket.php' method='POST'>";
            echo "<label>Select Ticket Type:</label><br>";
            echo "<select name='ticket_id' required>";
            while ($ticket = $tickets->fetch_assoc()) {
                $label = $ticket['TYPE'] . " - ₹" . $ticket['PRICE'];
                echo "<option value='" . $ticket['T_ID'] . "'>$label</option>";
            }
            echo "</select><br>";
            echo "<button type='submit'>Book Now</button>";
            echo "</form>";
        } else {
            echo "<p>No tickets available for this event.</p>";
        }

        echo "</div><hr>";
    }
} else {
    echo "<p>No events found.</p>";
}

echo "</body></html>";
?>
