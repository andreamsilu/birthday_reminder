<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Set up database connection
$conn = new mysqli('localhost', 'root', 'passw0rd', 'birthday_reminder');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare data from POST
$username = $_POST['username'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$location = $_POST['location'];
$birthday = $_POST['birthday'];
$gender = $_POST['gender'];
$occupation = $_POST['occupation'];

// Escape user input to prevent SQL injection
$username = mysqli_real_escape_string($conn, $username);
$email = mysqli_real_escape_string($conn, $email);
$phone = mysqli_real_escape_string($conn, $phone);
$location = mysqli_real_escape_string($conn, $location);
$birthday = mysqli_real_escape_string($conn, $birthday);
$gender = mysqli_real_escape_string($conn, $gender);
$occupation = mysqli_real_escape_string($conn, $occupation);

// SQL query with interpolated values
$sql = "INSERT INTO Birthdays (username, email, phone, location, birthday, gender, occupation) 
        VALUES ('$username', '$email', '$phone', '$location', '$birthday', '$gender', '$occupation')";

// Execute query
if ($conn->query($sql) === false) {
    echo "Error executing query: " . $conn->error; // Error handling for query execution
} else {
    header('Location:index.php');
    echo "Birthday registered successfully!";
}

// Close connection
$conn->close();
?>
