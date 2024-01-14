<?php

include 'config.php';

if (isset($_POST['submit'])) {

   $username = mysqli_real_escape_string($conn, $_POST['username']);
   $pass = mysqli_real_escape_string($conn, md5($_POST['password']));
   $cpass = mysqli_real_escape_string($conn, md5($_POST['cpassword']));
   $title = mysqli_real_escape_string($conn, $_POST['title']);
   $gender = mysqli_real_escape_string($conn, $_POST['gender']);
   $fname = mysqli_real_escape_string($conn, $_POST['fname']);
   $lname = mysqli_real_escape_string($conn, $_POST['lname']);
   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $user = mysqli_real_escape_string($conn, $_POST['role']);
   $image = $_FILES['image']['name'];
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = 'uploaded_img/' . $image;

   $select = mysqli_query($conn, "SELECT * FROM `user_form` WHERE username = '$username' AND email = '$email' AND password = '$pass'") or die('query failed');
   $check_email_dupilcate = mysqli_query($conn, "SELECT email FROM user_form where email = '$email'");
   $check_username_dupilcate = mysqli_query($conn, "SELECT username FROM user_form where username = '$username'");

   if (mysqli_num_rows($check_email_dupilcate) > 0) {
      $message[] = 'Email already exist';
   } else if (mysqli_num_rows($check_username_dupilcate) > 0) {
      $message[] = 'Username already exist';
   } else {
      if ($pass != $cpass) {
         $message[] = 'Confirm password not matched!';
      } elseif ($image_size > 2000000) {
         $message[] = 'Image size is too large!';
      } else {
         $insert = mysqli_query($conn, "INSERT INTO `user_form` (username, password, title, gender, fname, lname, email, role, image, phone, date_of_birth) VALUES ('$username', '$pass', '$title', '$gender', '$fname', '$lname', '$email', 'user', '$image', '', NULL)") or die("query failed " . mysqli_error($conn));

         if ($insert) {
            move_uploaded_file($image_tmp_name, $image_folder);
            $message[] = 'registered successfully!';
            header('location:login.php');
         } else {
            $message[] = 'registeration failed!';
         }
      }
   }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>register</title>

   <!-- font awsome cdn link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

   <!--bootstrap cdn link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/register.css">

</head>


<body>

   <div class="form-container">

      <form action="" method="post" enctype="multipart/form-data">
         <h3>register now</h3>
         <?php
         if (isset($message)) {
            foreach ($message as $message) {
               echo '<div class="message">' . $message . '</div>';
            }
         }
         ?>
         <!--input for Username-->
         <div class="col-lg-12 d-flex justify-content-start">
            <label class="col-form-label ">Username</label>
         </div>
         <input type="text" name="username" placeholder="enter username" class="box form-control" required>

         <!--input for Password-->
         <div class="col-lg-12 d-flex justify-content-start">
            <label class="col-form-label ">Password</label>
         </div>
         <input type="password" name="password" placeholder="enter password" class="box form-control" required>

         <!--input for Confirm Password-->
         <div class="col-lg-12 d-flex justify-content-start">
            <label class="col-form-label ">Confirm Password</label>
         </div>
         <input type="password" name="cpassword" placeholder="confirm password" class="box form-control" required>

         <!--input for Title-->
         <div class="col-lg-12 d-flex justify-content-start">
            <label class="col-form-label ">Title</label>
         </div>
         <select name="title" id="inputtitle" placeholder="Title" class="form-control box" require>
            <option>-- Select Title --</option>
            <option value="Mr.">Mr.</option>
            <option value="Ms.">Ms.</option>
            <option value="Miss.">Miss.</option>
         </select>

         <!--input for Gender-->
         <div class="col-lg-12 d-flex justify-content-start">
            <label class="col-form-label ">Gender</label>
         </div>
         <select name="gender" id="inputtitle" placeholder="Title" class="form-control box" require>
            <option>-- Select Gender --</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
         </select>

         <!--input for First Name-->
         <div class="col-lg-12 d-flex justify-content-start">
            <label class="col-form-label ">First Name</label>
         </div>
         <input type="text" name="fname" placeholder="Name" class="box form-control" required>

         <!--input for Last Name-->
         <div class="col-lg-12 d-flex justify-content-start">
            <label class="col-form-label ">Last Name</label>
         </div>
         <input type="text" name="lname" placeholder="Last Name" class="box form-control" required>

         <!--input for Email-->
         <div class="col-lg-12 d-flex justify-content-start">
            <label class="col-form-label ">Email</label>
         </div>
         <input type="text" name="email" placeholder="enter email" class="box form-control" required>

         <!--input file for picture avatar-->
         <div class="col-lg-12 d-flex justify-content-start">
            <label class="col-form-label ">Picture for your Avatar</label>
         </div>
         <input type="file" name="image" class="box form-control" accept="image/jpg, image/jpeg, image/png">

         <!--input submit btn-->
         <input type="submit" name="submit" value="Register now" class="btn">

         <hr style="border: 2px solid gray;">
         <!-- go to page login-->
         <p>Already have an account? <a href="login.php">Login now</a></p>
      </form>

   </div>

</body>

</html>