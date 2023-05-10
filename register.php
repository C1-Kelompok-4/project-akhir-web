<?php

include 'components/connect.php';

// Mulai session
session_start();

if(isset($_POST['submit'])){

   $id = unique_id();
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);
   $cpass = sha1($_POST['cpass']);
   $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $ext = pathinfo($image, PATHINFO_EXTENSION);
   $rename = unique_id().'.'.$ext;
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = 'uploaded_files/'.$rename;

   $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
   $select_user->execute([$email]);

   $select_tutor = $conn->prepare("SELECT * FROM `tutors` WHERE email = ?");
   $select_tutor->execute([$email]);

   $select_admin = $conn->prepare("SELECT * FROM `admin` WHERE email = ?");
   $select_admin->execute([$email]);
   
   if($select_user->rowCount() > 0 || $select_tutor->rowCount() > 0){
      $message[] = 'Email already taken!';
   }else{
      if($pass != $cpass){
         $message[] = 'Confirm password does not match!';
      }else{
         $role = $_POST['role'];
         if ($role == "1") {
            $profession = $_POST['profession'];
            $profession = filter_var($profession, FILTER_SANITIZE_STRING);
            $insert_tutor = $conn->prepare("INSERT INTO `tutors`(id, name, profession, email, password, image) VALUES(?,?,?,?,?,?)");
            $insert_tutor->execute([$id, $name, $profession, $email, $cpass, $rename]);
            move_uploaded_file($image_tmp_name, $image_folder);
            $message[] = 'New tutor registered! Please login now';
         } else if($role == "2") {
            $insert_user = $conn->prepare("INSERT INTO `users`(id, name, email, password, image) VALUES(?,?,?,?,?)");
            $insert_user->execute([$id, $name, $email, $cpass, $rename]);
            move_uploaded_file($image_tmp_name, $image_folder);
            $message[] = 'New user registered! Please login now';
         }else{
            $insert_admin = $conn->prepare("INSERT INTO `admin`(id, name, email, password, image) VALUES(?,?,?,?,?)");
            $insert_admin->execute([$id, $name, $email, $cpass, $rename]);
            move_uploaded_file($image_tmp_name, $image_folder);
            $message[] = 'New admin registered! Please login now';

         }
         
         $verify_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND password = ? LIMIT 1");
         $verify_user->execute([$email, $pass]);
         $row = $verify_user->fetch(PDO::FETCH_ASSOC);
         
        // Setelah user berhasil login
         if($verify_user->rowCount() > 0){
         $_SESSION['user_id'] = $row['id'];
         header('location:login.php');
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
   <title>Register</title>
   <link rel="shortcut icon" href="images/silogoo.png" type="image/png">

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>
<body style="padding-left: 0;">



<section class="form-container">

<form class="register" action="" method="post" enctype="multipart/form-data">
      <h3>Create Account</h3>
      <div class="flex">
         <div class="col">
            <p>Name <span>*</span></p>
            <input type="text" name="name" placeholder="enter your name" maxlength="50" required class="box">
<p>Email <span>*</span></p>
<input type="email" name="email" placeholder="enter your email" maxlength="50" required class="box">
</div>
<div class="col">
<p>Password <span>*</span></p>
<input type="password" name="pass" placeholder="enter your password" maxlength="20" required class="box">
<p>Confirm Password <span>*</span></p>
<input type="password" name="cpass" placeholder="confirm your password" maxlength="20" required class="box">
</div>
</div>
<p>Role <span>*</span></p>
<select name="role" class="box" required onchange="toggleProfession(this.value)">
<option value="" disabled selected>Select your role</option>
<option value="1">Tutor</option>
<option value="2">User</option>
<option value="3">Admin</option>
</select>
<p>Profession <span>*</span></p>
<select name="profession" class="box" required id="professionSelect" disabled>
<option value="" disabled selected>Select your profession</option>
<option value="developer">Developer</option>
<option value="designer">Designer</option>
<option value="musician">Musician</option>
<option value="biologist">Biologist</option>
<option value="teacher">Teacher</option>
<option value="engineer">Engineer</option>
<option value="lawyer">Lawyer</option>
<option value="accountant">Accountant</option>
<option value="doctor">Doctor</option>
<option value="journalist">Journalist</option>
<option value="photographer">Photographer</option>
</select>
<p>Select Pic <span>*</span></p>
<input type="file" name="image" accept="image/*" required class="box">
<p class="link">Already have an account? <a href="login.php">Login</a></p>
<input type="submit" name="submit" value="register now" class="btn";>
</form>

<script>
function toggleProfession(role) {
   const professionSelect = document.getElementById("professionSelect");
   if (role === "2") {
      professionSelect.disabled = true;
      professionSelect.selectedIndex = 0;
   } else if(role == "3"){
      professionSelect.disabled = true;
      professionSelect.selectedIndex = 0;
   }else{
      professionSelect.disabled = false;
   }
}
</script>

</section>
<?php include 'components/footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>
   
</body>
</html>
