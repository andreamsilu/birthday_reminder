<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once 'vendor/pear/http_request2/HTTP/Request2.php'; // Ensure the HTTP_Request2 library is installed via Composer
require 'vendor/autoload.php'; // Adjust the path as needed
include('connection.php'); // Database connection

// Infobip API credentials
$apiUrl = 'https://6gmgq5.api.infobip.com/sms/2/text/advanced';
$apiKey = '08f2a467629d010613f2a7c20de8f794-404dc1f9-c7fe-49c4-ad06-c982d4378406'; // Stored in an environment variable

// Get the current date
$currentDate = date("Y-m-d");

// Prepare database query
$stmt = $conn->prepare("SELECT id, username, phone, DATE_FORMAT(birthday, '%m-%d') AS month_day, remainder1, remainder2, remainder3, remainder4, remainder5 FROM Birthdays");
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Loop through birthdays
    while ($row = $result->fetch_assoc()) {
        $birthdayThisYear = date("Y") . "-" . $row['month_day'];
        $currentDateTime = new DateTime($currentDate);
        $birthdayDateTime = new DateTime($birthdayThisYear);
        $interval = $birthdayDateTime->diff($currentDateTime);
        $daysDiff = $interval->days;

        $reminderPeriods = [0, 7, 14, 21, 28];
        $remainderStatusColumns = ['remainder1', 'remainder2', 'remainder3', 'remainder4', 'remainder5'];

        foreach ($reminderPeriods as $index => $reminderPeriod) {
            if ($daysDiff == $reminderPeriod && $row[$remainderStatusColumns[$index]] == 0) {
                // Compose SMS message
                $smsMessage = "Hello {$row['username']}, just a reminder that your birthday is coming up on {$row['birthday']}. Visit https://www.webshop.co.tz/ to check out your birthday gift!";

                // Setup the request to Infobip API
                $request = new HTTP_Request2();
                $request->setUrl($apiUrl);
                $request->setMethod(HTTP_Request2::METHOD_POST);
                $request->setConfig(array('follow_redirects' => TRUE));
                $request->setHeader(array(
                    'Authorization' => 'App ' . $apiKey,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json'
                ));
                $request->setBody(json_encode(array(
                    "messages" => array(
                        array(
                            "destinations" => array(array("to" => $row['phone'])),
                            "from" => "Zalongwa Technologies Limited",
                            "text" => $smsMessage
                        )
                    )
                )));

                try {
                    $response = $request->send();
                    if ($response->getStatus() == 200) {
                        echo "SMS sent to {$row['phone']} for {$row['username']} ({$daysDiff} days before birthday).\n";
                        // Update status of the corresponding reminder
                        $updateQuery = "UPDATE Birthdays SET {$remainderStatusColumns[$index]} = 1 WHERE id = ?";
                        $updateStmt = $conn->prepare($updateQuery);
                        $updateStmt->bind_param("i", $row['id']);
                        $updateStmt->execute();
                        $updateStmt->close();
                    } else {
                        echo "Failed to send SMS: " . $response->getStatus() . ' ' . $response->getReasonPhrase() . "\n";
                    }
                } catch (HTTP_Request2_Exception $e) {
                    echo 'Error sending SMS: ' . $e->getMessage() . "\n";
                }

                // Clear the HTTP request headers for next iteration if needed
                $request->setHeader(array());
            }
        }
    }
} else {
    echo "No upcoming birthdays found within the reminder period.\n";
}

// Close statement and database connection
$stmt->close();
$conn->close();
?>
