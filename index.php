<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Birthday Management</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .topnav {
            overflow: hidden;
            background-color: #f8f9fa;
        }

        .topnav a {
            float: left;
            display: block;
            color: #333;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
        }

        .topnav a:hover {
            background-color: #ddd;
            color: black;
        }

        .main {
            padding: 20px;
        }

        h2 {
            margin-top: 0;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">Birthday Reminder</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" href="index.php">List Birthdays</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="add.php">Add Birthday</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="passed.php">Passed Birthdays</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="remain.php">Remaining Birthdays</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<div class="main">
    <h2 id="list">List Birthdays</h2>
    <!-- Search form -->
    <form action="index.php" method="GET" class="mb-3">
        <div class="input-group w-25">
            <input type="text" class="form-control" placeholder="Search by Username or Email" name="search">
            <button class="btn btn-primary" type="submit">Search</button>
        </div>
    </form>

    <table class="table">
        <thead>
            <tr>
                <th>Username</th>
                <th>Email</th>
                <th>Birthday</th>
                <th>Phone</th>
                <th>Location</th>
                <th>Gender</th>
                <th>occupation</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Include your PHP code here to fetch and display birthdays
            $conn = new mysqli('localhost', 'root', 'passw0rd', 'birthday_reminder');
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Check if search query is provided
            if (isset($_GET['search']) && !empty($_GET['search'])) {
                $search = $_GET['search'];
                $sql = "SELECT * FROM Birthdays WHERE username LIKE '%$search%' OR email LIKE '%$search%'";
            } else {
                $sql = "SELECT * FROM Birthdays";
            }

            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>".$row['username']."</td>";
                    echo "<td>".$row['email']."</td>";
                    echo "<td>".$row['birthday']."</td>";
                    echo "<td>".$row['phone']."</td>";
                    echo "<td>".$row['location']."</td>";
                    echo "<td>".$row['gender']."</td>";
                    echo "<td>".$row['occupation']."</td>";

                    echo "<td>
                            <a href='edit.php?id=".$row['id']."' class='btn btn-sm btn-primary'>Edit</a>
                            <a href='delete.php?id=".$row['id']."' class='btn btn-sm btn-danger'>Delete</a>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No birthdays found</td></tr>";
            }

            $conn->close();
            ?>
        </tbody>
    </table>
</div>

<!-- Bootstrap JS (optional) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
