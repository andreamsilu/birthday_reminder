<?php
// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if all required fields are filled
    if (!empty($_POST['id']) && !empty($_POST['username']) && !empty($_POST['email']) && !empty($_POST['birthday'])) {
        // Retrieve form data
        $id = $_POST['id'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $location = $_POST['location'];
        $birthday = $_POST['birthday'];
        $gender = $_POST['gender'];
        $occupation = $_POST['occupation'];
        
        // Database connection
        $conn = new mysqli('localhost', 'root', 'passw0rd', 'birthday_reminder');
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Prepare and execute update statement
        $sql = "UPDATE Birthdays SET username=?, email=?, phone=?, location=?, birthday=?, gender=?, occupation=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssssi", $username, $email, $phone, $location, $birthday, $gender, $occupation, $id);
        $stmt->execute();

        // Check if update was successful
        if ($stmt->affected_rows > 0) {
            echo "<div class='alert alert-success'>Birthday updated successfully.</div>";
            header('location:index.php');
            exit;
        } else {
            echo "<div class='alert alert-danger'>Failed to update birthday.</div>";
        }

        $stmt->close();
        $conn->close();
    } else {
        echo "<div class='alert alert-danger'>All fields are required.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Birthday</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container">
    <a class="navbar-brand" href="#">Birthday Management</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link" href="index.php">List Birthdays</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="add.php">Add Birthday</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="passed.php">Passed Birthdays</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="remain.php">Remaining Birthdays</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h2 class="mb-4">Edit Birthday</h2>
            <?php
            // Check if ID is provided in the URL
            if (!isset($_GET['id'])) {
                echo "<div class='alert alert-danger'>No birthday ID provided.</div>";
                exit;
            }

            $id = $_GET['id'];

            // Database connection
            $conn = new mysqli('localhost', 'root', 'passw0rd', 'birthday_reminder');
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Fetch the birthday details
            $sql = "SELECT * FROM Birthdays WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
            ?>
                <form action="edit.php" method="POST" class="form-horizontal">
    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">

    <div class="row mb-3">
        <label for="username" class="col-md-3 col-form-label">Username:</label>
        <div class="col-md-9">
            <input type="text" class="form-control" id="username" name="username" value="<?php echo $row['username']; ?>" required>
        </div>
    </div>

    <div class="row mb-3">
        <label for="email" class="col-md-3 col-form-label">Email:</label>
        <div class="col-md-9">
            <input type="email" class="form-control" id="email" name="email" value="<?php echo $row['email']; ?>" required>
        </div>
    </div>

    <div class="row mb-3">
        <label for="location" class="col-md-3 col-form-label">Location:</label>
        <div class="col-md-9">
            <input type="text" class="form-control" id="location" name="location" value="<?php echo $row['location']; ?>">
        </div>
    </div>

    <div class="row mb-3">
        <label for="gender" class="col-md-3 col-form-label">Gender:</label>
        <div class="col-md-9">
            <select class="form-select" id="gender" name="gender">
                <option value="Male" <?php if($row['gender'] == 'Male') echo 'selected'; ?>>Male</option>
                <option value="Female" <?php if($row['gender'] == 'Female') echo 'selected'; ?>>Female</option>
                <option value="Other" <?php if($row['gender'] == 'Other') echo 'selected'; ?>>Other</option>
            </select>
        </div>
    </div>

    <div class="row mb-3">
        <label for="phone" class="col-md-3 col-form-label">Phone:</label>
        <div class="col-md-9">
            <input type="text" class="form-control" id="phone" name="phone" value="<?php echo $row['phone']; ?>">
        </div>
    </div>

    <div class="row mb-3">
        <label for="birthday" class="col-md-3 col-form-label">Birthday:</label>
        <div class="col-md-9">
            <input type="date" class="form-control" id="birthday" name="birthday" value="<?php echo $row['birthday']; ?>" required>
        </div>
    </div>

    <div class="row mb-3">
        <label for="occupation" class="col-md-3 col-form-label">Occupation:</label>
        <div class="col-md-9">
            <input type="text" class="form-control" id="occupation" name="occupation" value="<?php echo $row['occupation']; ?>">
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <button type="submit" class="btn btn-primary">Update Information</button>
        </div>
    </div>
</form>

            <?php
            } else {
                echo "<div class='alert alert-danger'>No birthday found with the provided ID.</div>";
            }

            $stmt->close();
            $conn->close();
            ?>
        </div>
    </div>
</div>

<!-- Bootstrap JS (optional) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
