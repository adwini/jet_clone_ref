<?php
session_start(); // Start the session

include 'connect.php';

// Check if the username session variable is set
if(!isset($_SESSION['username'])){
   header('location:login.php');
   exit();

// Fetch user data if user is logged in
if(isset($_SESSION['username'])){
    $username = $_SESSION['username'];
    $select = mysqli_query($connection, "SELECT * FROM `userinfo` WHERE username = '$username'") or die('Query failed');
    if(mysqli_num_rows($select) > 0){
        $fetch = mysqli_fetch_assoc($select);
    }
}
}

?>

<!DOCTYPE html>
<html lang="en">
<head>

                                         <!--Naa dri nako gibutang tailwindcss hahaha ikaw na balaha-->
      <link href="input.css" rel="stylesheet">
</head>
<body class="flex flex-col min-h-screen">

<nav class="flex items-center justify-between text-black shadow p-3 mb-5 bg-white rounded">
    <div class="logo-div flex items-center ml-10">
        <h1 class="font-bold text-2xl ">GYMCHUM</h1>
    </div>
    <div class="nav-link flex justify-between items-center space-x-4 ml-auto mr-10 font-bold p-2 text-l"></div>
    <div class="userProfile w-10 mr-10">
      <?php
          // Check if $fetch is not null before accessing it
          if($fetch && $fetch['image'] == ''){
              echo '<img src="images/profile.png">';
          } elseif ($fetch) {
              echo '<img class="w-10 h-10 rounded-full" alt="Rounded avatar" src="uploads/'.$fetch['image'].'">';
          }
      ?>
    </div>
         
</nav>

<section class="main-section flex items-center justify-center">
    <div class="bigtext text-center my-9">
        <h1 class="font-bold text-3xl">User Main Page</h1>
    </div>
</section>

<footer class="bg-black text-white mt-auto p-3 flex items-center justify-center">
    <div class="footer-content flex items-center justify-center space-x-4">
        <div>
            <h2>Jether Omictin</h2>
            <h2>BSCS-2</h2>
        </div>
        <div>
            <h2>Karl Christian Ajero</h2>
            <h2>BSCS-2</h2>
        </div>
    </div>
</footer>

<script src="./js/script.js"></script>
</body>
</html>
