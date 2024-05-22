<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Birthday Reminder</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
        }

        .navbar {
            background-color: #f8f9fa;
        }

        .card {
            width: 100%;
            max-width: 70%;
            /* Control the max-width of the card for better centering */
            margin: 20px auto;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Birthday Reminder</a>
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
                        <a class="nav-link active" href="passed.php">Passed Birthdays</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="remain.php">Remaining Birthdays</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="card shadow">
        <div class="card-body bg-light">
            <h2 class="card-title text-center">Register a Birthday</h2>
            <form action="script.php" method="post">
                <div class="row">
                    <!-- Username and Contact Info -->
                    <div class="col-md-6">
                        <div class="row mb-3">
                            <label for="username" class="col-sm-4 col-form-label">Username</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="username" name="username" required pattern="[A-Za-z0-9]+" title="Username must only contain letters and numbers.">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="email" class="col-sm-4 col-form-label">Email</label>
                            <div class="col-sm-8">
                                <input type="email" class="form-control" id="email" name="email" required pattern="[^@]+@[^@]+\.(com|net)" title="Please enter an email address ending with .com or .net">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="phone" class="col-sm-4 col-form-label">Phone Number</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="phone" name="phone" required pattern="\d{10}" title="Phone number must be 10 digits long" maxlength="10" minlength="10" placeholder="Enter a 10-digit phone number">
                            </div>
                        </div>

                    </div>
                    <!-- Location and Personal Details -->
                    <div class="col-md-6">
                        <div class="row mb-3">
                            <label for="location" class="col-sm-4 col-form-label">Location</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="location" name="location" required pattern="[A-Za-z0-9\s]*" title="Location must not contain special characters">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="birthday" class="col-sm-4 col-form-label">Birthday</label>
                            <div class="col-sm-8">
                                <input type="date" class="form-control" id="birthday" name="birthday" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="gender" class="col-sm-4 col-form-label">Gender</label>
                            <div class="col-sm-8">
                                <select class="form-control" id="gender" name="gender">
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="occupation" class="col-sm-4 col-form-label">Occupation</label>
                            <div class="col-sm-8">
                                <select class="form-control" id="occupation" name="occupation" required>
                                    <option value="">--Select an Occupation--</option>
                                    <option value="Software Engineer">Software Engineer</option>
                                    <option value="Doctor">Doctor</option>
                                    <option value="Teacher">Teacher</option>
                                    <option value="Artist">Artist</option>
                                    <option value="Engineer">Engineer</option>
                                    <option value="Nurse">Nurse</option>
                                    <option value="Business Analyst">Business Analyst</option>
                                    <option value="Sales Manager">Sales Manager</option>
                                    <option value="Researcher">Researcher</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>

                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>