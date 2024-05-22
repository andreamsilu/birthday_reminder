<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

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
include('connection.php');
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

        // Define reminder periods (in days) and their corresponding reminder status columns
        $reminderPeriods = [0, 7, 14, 21, 28];
        $remainderStatusColumns = ['remainder1', 'remainder2', 'remainder3', 'remainder4', 'remainder5'];

        // Check if the birthday is within any of the reminder periods
        foreach ($reminderPeriods as $index => $reminderPeriod) {
            if ($daysDiff == $reminderPeriod && $row[$remainderStatusColumns[$index]] == 0) {
                // Compose email message
                $message = "<h5>Hello {$row['username']}</h5>,<br><br>";
                $message = "<h2 style='text-align:center;background-color:blue;padding:10px;'>Birthday reminder</h2>,<br><br>";
                $message .= "Just a reminder that your birthday is coming up on {$row['birthday']}.<br>";
                $message .= "Click <a href='https://www.tapa.or.tz/'>here</a> to get your birthday gift.<br><br>";
                $message = "Dear customer,\nThank you for registering with TAPA. Your application has been received, and our team will get back to you after the application is processed and after payment of the fee. Your membership account will be activated only after paying the Registration and Annual Fees.\n\n<br>Application fees for all categories is 10,000 Tshs.\nAnnual Fees is as follows:\n\ni. Full Member: 50,000 Tshs per annum\nii. Associate Member I: 20,000 Tshs per annum\niii. Associate Member II: 20,000 Tshs per annum\niv. Student Member: 10,000 Tshs per annum\nv. Affiliates: 30,000 Tshs per annum\nvi. Foreign Affiliates: 50,000 Tshs per annum\n\nFor example, if you have a bachelor’s degree in psychology, you qualify to become a Full Member. You would then deposit your annual fee of 50,000 Tshs plus the 10,000 Tshs one-time application fee. The total amount to deposit would be 60,000 TShs.\n\n BANK: NMB. <br> Account No: 20810008255\n\nAfter payment upload proof of payment (receipt) <a style='font-family:bold;' href='https://tapa.or.tz/pay_annual_fees.php'> here.</a>\n\nFor any inquiries, please email admin@tapa.or.tz or Whatsapp +255 719911575.\n\nIf you did not register on our website, please ignore this message.\n\nRegards,\nAdministrative Assistant,\nTanzanian Psychological Association (TAPA)\n+255 719 911 575\n";

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
