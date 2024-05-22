<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Adjust the path as needed

// Initialize PHPMailer
$mail = new PHPMailer(true);

// SMTP configuration
$mail->isSMTP();
$mail->SMTPDebug = 0; // Set to 2 for debugging
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Username = 'mussamo0201@gmail.com';
$mail->Password = 'wpsjzduwhktgkxyy';
$mail->SMTPSecure = 'tls';
$mail->Port = 587;

// Email content and settings
$mail->setFrom('mussamo0201@gmail.com', 'Birthday Reminder');
$mail->isHTML(true);
$mail->Subject = 'Birthday Reminder';

// Database connection
$conn = new mysqli('localhost', 'root', 'passw0rd', 'birthday_reminder');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the current date
$currentDate = date("Y-m-d");

// Query for birthdays that are within the upcoming reminder period
$sql = "SELECT *, DATE_FORMAT(birthday, '%m-%d') AS month_day FROM Birthdays";
$result = $conn->query($sql);

// Check if there are rows returned from the query
if ($result->num_rows > 0) {
    // Loop through birthdays
    while ($row = $result->fetch_assoc()) {
        // Get the month and day of the birthday for the current year
        $birthdayThisYear = date("Y") . "-" . $row['month_day'];

        // Calculate time difference in days
        $currentDateTime = new DateTime($currentDate);
        $birthdayDateTime = new DateTime($birthdayThisYear);
        $interval = $birthdayDateTime->diff($currentDateTime);
        $daysDiff = $interval->days;

        // Define reminder periods (in days) and their corresponding remainder status columns
        $reminderPeriods = [0, 7, 14, 21, 28];
        $remainderStatusColumns = ['remainder1', 'remainder2', 'remainder3', 'remainder4', 'remainder5'];

        // Check if the birthday is within any of the reminder periods
        foreach ($reminderPeriods as $index => $reminderPeriod) {
            if ($daysDiff == $reminderPeriod) {
                // Compose email message
                $message = "Hello {$row['username']},<br><br>";
                $message .= "Just a reminder that your birthday is coming up on {$row['birthday']}.<br><br>";
                $message .= "Best regards,<br>Birthday Reminder";

                // Set recipient email
                $mail->addAddress($row['email'], $row['username']);

                // Set email message
                $mail->Body = $message;

                try {
                    // Send email
                    $mail->send();
                    
                    // Update status of the corresponding reminder
                    $updateStatusQuery = "UPDATE Birthdays SET {$remainderStatusColumns[$index]} = 1 WHERE id = ?";
                    $stmt = $conn->prepare($updateStatusQuery);
                    $stmt->bind_param("i", $row['id']);
                    $stmt->execute();
                    $stmt->close();

                    echo "Reminder email sent to {$row['email']} for {$row['username']} ({$daysDiff} days before birthday).<br>";
                } catch (Exception $e) {
                    echo "Error sending email: {$mail->ErrorInfo}<br>";
                }

                // Clear recipient
                $mail->clearAddresses();
            }
        }
    }
} else {
    echo "No upcoming birthdays found within the reminder period.<br>";
}

// Close database connection
$conn->close();
?>
