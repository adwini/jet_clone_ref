<?php
include 'connect.php';

$registerMessage = "";

if(isset($_POST['btnRegister'])){      
    $fname = mysqli_real_escape_string($connection, $_POST['txtfirstname']);
    $lname = mysqli_real_escape_string($connection, $_POST['txtlastname']);
    $gender = mysqli_real_escape_string($connection, $_POST['txtgender']);
    $email = mysqli_real_escape_string($connection, $_POST['txtemail']);
    $uname = mysqli_real_escape_string($connection, $_POST['txtusername']);
    $pword = mysqli_real_escape_string($connection, $_POST['txtpassword']);
    $confirmpword = mysqli_real_escape_string($connection, $_POST['confirm_password']);
    
    // Check if image file is selected
    if($_FILES["image"]["error"] === 4){
        $registerMessage = "Please select an image file.";
    } else {
        $fileName = $_FILES["image"]["name"];
        $fileSize = $_FILES["image"]["size"];
        $tmpName = $_FILES["image"]["tmp_name"];

        $validImageExtension = ['jpg','jpeg','png'];
        $imageExtension = pathinfo($fileName, PATHINFO_EXTENSION);
        $imageExtension = strtolower($imageExtension);

        if(!in_array($imageExtension, $validImageExtension)){
            $registerMessage = "Invalid image extension. Allowed extensions are jpg, jpeg, and png.";
        } else if($fileSize > 2000000){
            $registerMessage = "Image size is too large. Please upload an image smaller than 2MB.";
        } else {
            $newImageName = uniqid().".".$imageExtension;
            $uploadPath = "uploads/".$newImageName;

            // Move uploaded file to destination
            if(move_uploaded_file($tmpName, $uploadPath)){
                // Image uploaded successfully
            } else {
                $registerMessage = "Failed to upload image. Please try again.";
            }
        }
    }

    // Check if username or email already exists
    $check_query = "SELECT * FROM userinfo WHERE username = '$uname' OR email = '$email'";
    $check_result = mysqli_query($connection, $check_query);

    if(mysqli_num_rows($check_result) > 0) {
        $registerMessage = "Username or email already exists. Please choose a different one.";
    } else {
        if ($pword != $confirmpword) {
            $registerMessage = "Passwords do not match.";
        } else {
            // Insert user information into the database
            $insert = mysqli_query($connection, "INSERT INTO userinfo (firstname, lastname, gender, email, username, password, image) VALUES ('$fname', '$lname', '$gender', '$email', '$uname', '$pword', '$newImageName')") or die('Query failed');
            if ($insert) {
                // Move uploaded file to the server
                move_uploaded_file($tmpName, $uploadPath);
                // Redirect user to login page
                header('location: login.php');
            } else {
                $registerMessage = 'Registration failed!';
            }
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="css/style.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <style>
        .message-box {
            display: none;
            padding: 10px 20px;
            border-radius: 5px;
            background-color: greenyellow;
            color: #000;
            opacity: 0.9;
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 9999;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .message-box.active {
            display: block;
        }

        .close-btn {
            float: right;
            margin-left: 10px;
            cursor: pointer;
            color: #000;
            background-color: greenyellow;
            font-size: 20px;
        }
    </style>
</head>
<body>
<div class="container">
    <nav class="m-0 p-3 flex items-center justify-between text-black">
        <div class="logo-div flex items-center">
            <img id="img-logo" src="images/logo.png" class="w-24"/>
            <h1 class="ml-0 font-bold text-3xl">GYMCHUM</h1>
        </div>
    </nav>

    <div class="message-box <?php echo ($registerMessage != "") ? 'active' : ''; ?>">
        <span class="close-btn" onclick="this.parentElement.classList.remove('active');">&times;</span>
        <?php echo $registerMessage; ?>
    </div>

    <div class="row justify-content-center align-items-center vh-100">
        <div class="col-lg-4 col-md-6 col-sm-8">
            <div class="card shadow rounded-3 border-0">
                <div class="card-body">
                    <h1 class="regText p-1 font-bold text-3xl ">Sign Up</h1>
                    <p class="mb-3 ml-1 text-gray-400">Register your account</p>
                    <form method="post" enctype="multipart/form-data">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="name" name="txtfirstname" placeholder="name" required autofocus />
                            <label for="name">Firstname</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="username" name="txtlastname" placeholder="username" required />
                            <label for="username">Lastname</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="email" class="form-control" id="txtemail" name="txtemail" placeholder="Enter email" required />
                            <label for="email">Email</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="username" name="txtusername" placeholder="Username" required />
                            <label for="username">Username</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="password" name="txtpassword" placeholder="password" required />
                            <label for="password">Password</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="confirm password" required />
                            <label for="password">Confirm Password</label>
                        </div>
                        <div class="form-floating mb-3">
                            <select class="form-select" aria-label="Default select example" id="gender" name="txtgender">
                                <option selected>Gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Others">Other</option>
                            </select>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="file" class="form-control" id="image" name="image" accept=".jpg, .jpeg,.png" placeholder="Choose a profile picture" required />
                            <label for="image">Profile Picture</label>
                        </div>
                        <input type="submit" class="btn btn-primary w-100 bg-black" name="btnRegister" value="Register">
                    </form>
                    <p class="text-center mt-3">
                        Already have an account? <a href="login.php" class="underline">Login here</a>.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<footer class="bg-black text-white mt-auto p-3 flex items-center justify-center">
    <div class="footer-content flex items-center justify-center space-x-4">
        <div>
            <h2>Jether Omictin</h2>
            <h2>BSCS-2</h2>
        </div>
    </div>
</footer>

<script src="js/script.js"></script>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>